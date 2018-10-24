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
            if(!empty($model->getMobiles())){
                $result = $business->sms($user,$model);
                if($result === false or !isset($result['total_fee']) ){
                    $this->log('Id:'.$model->getId().':fail,短信发送失败');
                    $model->setError(json_encode($result));
                    $model->setStatus(4);
                    $model->setUpdated_at(date('YmdHis'));
                    $mapper->update($model);
                    continue;
                }
                if($result['total_fee'] == 0){
                    $this->log('Id:'.$model->getId().':fail,短信发送失败');
                    $model->setStatus(4);
                    $model->setError(json_encode($result));
                    $model->setUpdated_at(date('YmdHis'));
                    $mapper->update($model);
                    continue;
                }
                $recordMapper = \Mapper\SmsrecordModel::getInstance();
                $order = new \SmsrecordModel();
                $order->setTask_id($model->getTask_id());
                $order->setUser_id($task->getUser_id());
                $order->setSms_type($model->getType());
                $order->setContent('');
                foreach ($result['data'] as $datum){
                    $order->setUid($datum['uid']);
                    $order->setSid($datum['sid']);
                    $order->setPhone($datum['mobile']);
                    if($task->getType() == 2){
                        $order->setMasked_phone(substr_replace($datum['mobile'],'******',2,-3));
                    }else{
                        $order->setMasked_phone($datum['mobile']);
                    }
                    $order->setBilling_count($datum['fee']);
                    $order->setCode($datum['code']);
                    if($datum['code']!=0){
                        $order->setMessage($datum['msg']);
                        $order->setStatus(2);
                    }else{
                        $order->setStatus(1);
                    }
                    $order->setCreated_at(date('YmdHis'));
                    $res = $recordMapper->insert($order);
                    if($res === false){
                        $this->log($datum['sid'].'添加订单失败');
                        $model->setError(json_encode($result));
                    }
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
            }
            $onefee = $business->oneFee($task->getContent());
            $sendNum = $result['total_fee']/$onefee;
            if($model->getSend_num() > $sendNum){
                $model->setStatus(3);
            }else{
                $model->setStatus(2);
            }
            if(!empty($model->getNot_arrive())){
                $order = new \SmsrecordModel();
                $order->setTask_id($model->getTask_id());
                $order->setUser_id($task->getUser_id());
                $order->setSms_type($model->getType());
                $order->setContent('');
                $failMobiles = explode(',',$model->getNot_arrive());
                $sendNum += count($failMobiles);
                foreach ($failMobiles as $failMobile){
                    $order->setUid($model->getUid());
                    $order->setSid('');
                    $order->setPhone($failMobile);
                    if($task->getType() == 2){
                        $order->setMasked_phone(substr_replace($failMobile,'******',2,-3));
                    }else{
                        $order->setMasked_phone($failMobile);
                    }
                    $order->setBilling_count($onefee);
                    $order->setCode(0);
                    $order->setStatus(1);
                    $order->setCreated_at(date('YmdHis'));
                    $recordMapper->insert($order);
                }
            }
            $model->setSuccess($sendNum);
            $model->setUpdated_at(date('YmdHis'));
            $mapper->update($model);
            $this->success($model->getId());
        }
        return false;
    }


    public function pullAll(){
        $mapper = \Mapper\SmsqueueModel::getInstance();
        $begin = time();
        while (time() - $begin <60){
            $model = $mapper->pullsms();
            if(!$model instanceof \SmsqueueModel){
                sleep(1);
                continue;
            }
            if((time() - strtotime($model->getUpdated_at())) <5){
                sleep(5);
            }
            $task = \Mapper\SendtasksModel::getInstance()->findById($model->getTask_id());
            $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
            if(!$user instanceof \UsersModel){
                $this->fail($model->getId().':发送用户不存在');
                continue;
            }
            if($this->locked($user->getId(),__CLASS__,'pull',60) === false){
                sleep(5);
                continue;
            }
            $this->start($user->getId());
            $smser = new \Ku\Sms\Adapter('yunzhixun');
            $driver = $smser->getDriver();
            $driver->setAccount($user->getAccount());
            $driver->setPassword($user->getRaw_password());
            $result = $driver->pull();
            if($result === false){
                $this->fail($user->getId().':'.$driver->getError());
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
                    $this->fail($user->getId().':'.json_encode($data).'：'.$message['msg']);
                }
            }
            $this->success($user->getId());
        }
        return false;
    }


    public function pullOrder(){
        $mapper = \Mapper\SmsrecordModel::getInstance();
        $where = array('isapi'=>1,'report_status'=>0);
        $begin = time();
        while (time() - $begin <60){
            $order = $mapper->fetch($where,array('id desc'));
            if(!$order instanceof \SmsrecordModel){
                sleep(1);
                continue;
            }
            if($this->locked($order->getUser_id(),__CLASS__,'pull',60) === false){
                sleep(5);
                continue;
            }
            $user = \Mapper\UsersModel::getInstance()->findById($order->getUser_id());
            $this->start($order->getUser_id());
            $smser = new \Ku\Sms\Adapter('yunzhixun');
            $driver = $smser->getDriver();
            $driver->setAccount($user->getAccount());
            $driver->setPassword($user->getRaw_password());
            $result = $driver->pull();
            if($result === false){
                $this->fail($order->getUser_id().':'.$driver->getError());
                continue;
            }
            if($result['code'] !==0){
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
                    $this->fail($order->getUser_id().':'.json_encode($data).'：'.$message['msg']);
                }
            }
            $this->success($order->getUser_id());
        }
        return false;
    }


    /**回调处理
     * @return bool
     */
    public function callback(){
        $mapper = \Mapper\SmsrecordModel::getInstance();
        $where = array('isapi'=>1,'report_status in(1,2)','iscallback'=>0);
        $begin = time();
        $http = new \Ku\Http();
        while (time() - $begin <60){
            $order = $mapper->fetch($where,array('id desc'));
            if(!$order instanceof \SmsrecordModel){
                sleep(1);
                continue;
            }
            $order->setIscallback(1);
            $mapper->update($order);
            if($this->locked($order->getId(),__CLASS__,'pull',60) === false){
                sleep(5);
                continue;
            }
            $userCallback = \Mapper\UsercallbackModel::getInstance()->findByUser_id($order->getUser_id());
            if(!$userCallback instanceof \UsercallbackModel){
                $order->setUpdated_at(date('YmdHis'));
                $order->setIscallback(4);
                $mapper->update($order);
            }
            if(empty($userCallback->getUrl())){
                $order->setUpdated_at(date('YmdHis'));
                $order->setIscallback(4);
                $mapper->update($order);
            }
            $this->start($order->getId());
            $report_status = $order->getReport_status()==1?'Success':'Fail';
            $params = array(
                'report_status'=>$report_status,'sid'=>$order->getSid(),'mobile'=>$order->getMasked_phone(),'arrive_time'=>date('Y-m-d H:i:s',strtotime($order->getArrivaled_at()))
            );
            $http->setUrl($userCallback->getUrl());
            $http->setParam($params,true);
            $http->setTimeout(2);
            $send = $http->send();
            $send = json_decode($send,true);
            if($send and isset($send['receive_status'])){
                if($send['receive_status']){
                    $order->setUpdated_at(date('YmdHis'));
                    $order->setIscallback(2);
                    $mapper->update($order);
                }else{
                    $order->setUpdated_at(date('YmdHis'));
                    $order->setIscallback(3);
                    $mapper->update($order);
                }
            }else{
                $order->setUpdated_at(date('YmdHis'));
                $order->setIscallback(4);
                $mapper->update($order);
            }
            $this->success($order->getId());
        }
        return false;
    }




//    /**
//     *已获取回调的数据导入至文件保存
//     */
//    public function exportSms(){
//        $key = 'import_sms_pull_over_%s';
//        $mapper = \Mapper\SmsqueueModel::getInstance();
//        $copyMapper = \Mapper\SmsqueuecopyModel::getInstance();
//        $begin = time();
//        while (time() - $begin <60) {
//            $model = $mapper->pullover();
//            if (!$model instanceof \SmsqueueModel) {
//                sleep(1);
//                continue;
//            }
//            //加锁防止冲突
//            $res = $this->locked(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__,1200);
//            if($res === false){
//                sleep(1);
//                continue;
//            }
//            $this->log('Id:'.$model->getId().':start');
//            $task = \Mapper\SendtasksModel::getInstance()->findById($model->getTask_id());
//            $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
//            if(!$user instanceof \UsersModel){
//                $this->fail($model->getId().':发送用户不存在');
//                continue;
//            }
//            $res = \Ku\Tool::makeDir($this->_fileFirst);
//            if(!$res){
//                $this->log('创建目录失败:'.$this->_fileFirst);
//                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
//                continue;
//            }
//            $str = '';
//            $pulls = json_decode($model->getPull(),true);
//            foreach ($pulls as $pull){
//                $statusStr = $pull['report_status']=='SUCCESS'?'成功':'';
//                $str .= $pull['mobile'].','.$model->getContent().','.$statusStr.','.$pull['user_receive_time']."\n";
//            }
////            if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows nt')){
////                $str = mb_convert_encoding($str,'gbk','utf-8');
////            }
//           $fileName = 'task_'.$task->getId().'.csv';
//           if(!file_exists($this->_fileFirst.$fileName)){
//                $header = '发送手机号,发送内容,发送状态,到达时间'."\n";
//                $str = $header.$str;
//            }
//            $fp = fopen($this->_fileFirst.$fileName,'a');
//            $str = mb_convert_encoding($str,'gbk','utf-8');
//            $res = fwrite($fp,$str);
//            fclose($fp);
//            if($res <= 0){
//                $this->log('队列Id:'.$model->getId().'写入文件失败');
//                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
//                continue;
//            }
//            $mapper->begin();
//            $addres = $copyMapper->insert($model);
//            if(!$addres){
//                $mapper->rollback();
//                $this->log('队列Id:'.$model->getId().'数据备份失败');
//                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
//                continue;
//            }
//            if($model->getTotal_num() != $model->getSuccess()){
//                \Mapper\SendtasksModel::getInstance()->update(array('status'=>3,'updated_at'=>date('Y-m-d H:i:s')),array('id'=>$model->getTask_id()));
//            }
//            $delres = $mapper->del(array('id'=>$model->getId()));
//            if(!$delres){
//                $mapper->rollback();
//                $this->log('队列Id:'.$model->getId().'数据删除失败');
//                $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
//                continue;
//            }
//            $mapper->commit();
//            $this->log('队列Id:'.$model->getId().'数据导入至文件成功');
//            $this->unlock(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
//        }
//    }


}
