<?php

namespace Cron;

class Sms extends \Cron\CronAbstract {

    private $_smsId = 'yunzhixun.sms.id.%s';

    public function main() {
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
                $mapper->update($model);
                continue;
            }
            $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
            if(!$user instanceof \UsersModel){
                $this->log('Id:'.$model->getId().':fail,用户不存在');
                $model->setStatus(4);
                $mapper->update($model);
                continue;
            }
            $result = $business->sms($user,$model);
            if($result === false or !isset($result['total_fee']) ){
                $this->log('Id:'.$model->getId().':fail,短信发送失败');
                $model->setStatus(4);
                $mapper->update($model);
                continue;
            }
            if($result['total_fee'] == 0){
                $this->log('Id:'.$model->getId().':fail,短信发送失败');
                $model->setStatus(4);
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
            $mapper->update($model);
        }
        return false;
    }

    



}
