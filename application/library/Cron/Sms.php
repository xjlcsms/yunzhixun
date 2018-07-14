<?php

namespace Cron;

class Sms extends \Cron\CronAbstract {

    private $_smsId = 'yunzhixun.sms.id.%s';
    private $_fileFirst = APPLICATION_PATH.'/data/sms/';

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
            $res = $this->locked(sprintf($this->_smsId,$model->getId()),__CLASS__,__FUNCTION__);
            if($res){
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
                $model->setUpdated_at(date('YmdHis'));
                $mapper->update($model);
                continue;
            }
            if($result['total_fee'] == 0){
                $this->log('Id:'.$model->getId().':fail,短信发送失败');
                $model->setStatus(4);
                $model->setUpdated_at(date('YmdHis'));
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
                $this->fail($model->getId().':拉取数据失败');
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
        }
        return false;
    }

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
            $res = $this->locked(sprintf($key,$model->getId()),__CLASS__,__FUNCTION__);
            if($res){
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
            $fileName = 'task_'.$task->getId();
        }
    }

}
