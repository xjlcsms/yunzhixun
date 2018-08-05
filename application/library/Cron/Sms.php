<?php

namespace Cron;

class Sms extends \Cron\CronAbstract {

    private $_smsId = 'yunzhixun.sms.id.%s';
    private $_fileFirst = APPLICATION_PATH.'/public/uploads/sms/';

    public function main() {
        $func = $this->getArgv(2);
        call_user_func(array('\Cron\Sms', $func));
    }

    /**群发短信队列处理
     * @return bool
     * @throws Exception
     */
    public function smsAll(){
        $mapper = \Mapper\SmsqueueModel::getInstance();
        $business = \Business\SmsModel::getInstance();
        $userBusiness = \Business\UserModel::getInstance();
        $begin = time();
        while (time() - $begin <60){
            $model = $mapper->pull();
            if(!$model instanceof \SmsqueueModel){
                sleep(1);
                continue;
            }
            //加锁防止冲突
            $res = $this->locked(sprintf($this->_smsId,$model->getId()),__CLASS__,__FUNCTION__,1200);
            if($res === false){
                continue;
            }
            $this->log('Id:'.$model->getId().':start');
            $task = \Mapper\SendtasksModel::getInstance()->findById($model->getTask_id());
            if(!$task instanceof \SendtasksModel){
                $this->log('Id:'.$model->getId().':fail,任务不存在');
                $model->setStatus(4);
                $model->setUpdated_at(date('YmdHis'));
                $mapper->update($model);
                continue;
            }
            $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
            if(!$user instanceof \UsersModel){
                $this->log('Id:'.$model->getId().':fail,用户不存在');
                $model->setStatus(4);
                $model->setUpdated_at(date('YmdHis'));
                $mapper->update($model);
                continue;
            }
            $result = $business->sms($user,$model);
            if($result === false or !isset($result['total_fee']) ){
                $this->log('Id:'.$model->getId().':fail,短信发送失败');
                $model->setStatus(4);
                $model->setCallback(json_encode($result));
                $model->setUpdated_at(date('YmdHis'));
                $mapper->update($model);
                continue;
            }
            if($result['total_fee'] == 0){
                $this->log('Id:'.$model->getId().':fail,短信发送失败');
                $model->setStatus(4);
                $model->setUpdated_at(date('YmdHis'));
                $model->setCallback(json_encode($result));
                $mapper->update($model);
                continue;
            }
            $account = $model->getType()==3?'market':'normal';
            $res = $userBusiness->flow($user,$result['total_fee'],0,$account);
            if(!$res){
                $config = \Yaf\Registry::get('config');
                $key = $config->get('flow.error');
                $redis = $this->getRedis();
                $redis->lPush($key,json_encode(array('userid'=>$user->getId(),'type'=>$account.'_true','fee'=>$result['total_fee'])));
                $this->log('Id:'.$model->getId().':fail,扣除用户余额失败');
            }
            $onefee = $business->oneFee($task->getContent());
            $sendNum = $result['total_fee']/$onefee;
            if($model->getSend_num() > $sendNum){
                $model->setStatus(3);
            }else{
                $model->setStatus(2);
            }
            $model->setSuccess($sendNum);
            $model->setCallback(json_encode($result));
            $model->setUpdated_at(date('YmdHis'));
            $mapper->update($model);
            $this->success($model->getId());
        }
        return false;
    }


    public function allPull(){
        $mapper = \Mapper\SmsqueueModel::getInstance();
        $begin = time();
        while (time() - $begin <60){
            $model = $mapper->pullsms();
            if(!$model instanceof \SmsqueueModel){
                sleep(1);
                continue;
            }
            if((time() - strtotime($model->getUpdated_at())) <3){
                sleep(2);
            }
            $this->log('Id:'.$model->getId().':start');
            $task = \Mapper\SendtasksModel::getInstance()->findById($model->getTask_id());
            $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
            if(!$user instanceof \UsersModel){
                $this->fail($model->getId().':发送用户不存在');
                continue;
            }
            $smser = new \Ku\Sms\Adapter('yunzhixun');
            $driver = $smser->getDriver();
            $driver->setAccount($user->getAccount());
            $driver->setPassword($user->getRaw_password());
            $result = $driver->pull();
            if($result === false){
                $this->fail($model->getId().':'.$driver->getError());
                continue;
            }
            if($result['code'] !==0){
//                $this->fail($model->getId().':未拉取到数据');
                sleep(5);
                continue;
            }
            if(empty($result['data'])){
                continue;
            }
            $business = \Business\SmsModel::getInstance();
            foreach ($result['data'] as $data){
                $res = $business->pullAll($data);
                if(!$res){
                    $message = $business->getMessage();
                    $this->fail($model->getId().':'.json_encode($data).'：'.$message['msg']);
                }
            }
            $this->success($model->getId());
        }
        return false;
    }

    /**
     *已获取回调的数据导入至文件保存
     */
    public function exportSms(){
        $key = 'import_sms_pull_over_%s';
        $mapper = \Mapper\SmsqueueModel::getInstance();
        $copyMapper = \Mapper\SmsqueuecopyModel::getInstance();
        $begin = time();
        while (time() - $begin <60) {
            $model = $mapper->pullover();
            if (!$model instanceof \SmsqueueModel) {
                sleep(1);
                continue;
            }
            //加锁防止冲突
            $res = $this->locked(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__,1200);
            if($res === false){
                sleep(1);
                continue;
            }
            $this->log('Id:'.$model->getId().':start');
            $task = \Mapper\SendtasksModel::getInstance()->findById($model->getTask_id());
            $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
            if(!$user instanceof \UsersModel){
                $this->fail($model->getId().':发送用户不存在');
                continue;
            }
            $res = \Ku\Tool::makeDir($this->_fileFirst);
            if(!$res){
                $this->log('创建目录失败:'.$this->_fileFirst);
                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
                continue;
            }
            $str = '';
            $pulls = json_decode($model->getPull(),true);
            foreach ($pulls as $pull){
                $statusStr = $pull['report_status']=='SUCCESS'?'成功':'';
                $str .= $pull['mobile'].','.$model->getContent().','.$statusStr.','.$pull['user_receive_time']."\n";
            }
//            if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows nt')){
//                $str = mb_convert_encoding($str,'gbk','utf-8');
//            }
           $fileName = 'task_'.$task->getId().'.csv';
           if(!file_exists($this->_fileFirst.$fileName)){
                $header = '发送手机号,发送内容,发送状态,到达时间'."\n";
                $str = $header.$str;
            }
            $fp = fopen($this->_fileFirst.$fileName,'a');
            $str = mb_convert_encoding($str,'gbk','utf-8');
            $res = fwrite($fp,$str);
            fclose($fp);
            if($res <= 0){
                $this->log('队列Id:'.$model->getId().'写入文件失败');
                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
                continue;
            }
            $mapper->begin();
            $addres = $copyMapper->insert($model);
            if(!$addres){
                $mapper->rollback();
                $this->log('队列Id:'.$model->getId().'数据备份失败');
                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
                continue;
            }
            if($model->getTotal_num() != $model->getSuccess()){
                \Mapper\SendtasksModel::getInstance()->update(array('status'=>3,'updated_at'=>date('Y-m-d H:i:s')),array('id'=>$model->getTask_id()));
            }
            $delres = $mapper->del(array('id'=>$model->getId()));
            if(!$delres){
                $mapper->rollback();
                $this->log('队列Id:'.$model->getId().'数据删除失败');
                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
                continue;
            }
            $mapper->commit();
            $this->log('队列Id:'.$model->getId().'数据导入至文件成功');
            $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
        }
    }


    /**更新已拉取完回调数据的发送任务
     * @return bool
     */
    public function pullStatus(){
        $mapper = \Mapper\SendtasksModel::getInstance();
        $queueMapper = \Mapper\SmsqueueModel::getInstance();
        $where = array('pull_status'=>1,"created_at >='2018-07-16 00:00:00'",'quantity >0');
        $sendTasks = $mapper->fetchAll($where,array('created_at desc'));
        if(empty($sendTasks)){
            $this->log('没有需要更新拉取状态的发送任务');
            return false;
        }
        foreach ($sendTasks as $task){
            $queue = $queueMapper->fetch(array('task_id'=>$task->getId(),'status'=>2));
            if($queue instanceof \SmsqueuecopyModel){
                continue;
            }
            $task->setPull_status(2);
            if($task->getStatus() != 3){
                $task->setStatus(1);
            }
            $task->setUpdated_at(date('Y-m-d H:i:s'));
            $res = $mapper->update($task);
            if(!$res){
                $this->fail($task->getId().'：更新状态失败');
                continue;
            }
            $this->log($task->getId().'：更新状态成功');
        }
        return false;
    }

}
