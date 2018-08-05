<?php
/**
 * Created by PhpStorm.
 * User: chendongqin
 * Date: 18-8-5
 * Time: 下午2:32
 */
//1200
class TestController extends \Base\ApplicationController
{
    private $_mobiles = array(13386936061,13606061652);

    public function indexAction()
    {
        $task_id = $this->getParam('taskid',2,'int');
        $task = \Mapper\SendtasksModel::getInstance()->findById($task_id);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('发送任务不存在',1000);
        }
        $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
        if(!$user instanceof \UsersModel){
            return $this->returnData('用户不存在',1000);
        }
        $smsMapper = \Mapper\SmsqueueModel::getInstance();
        $model = new \SmsqueueModel();
        $model->setTask_id($task_id);
        $model->setContent('【'.$task->getSign().'】'.$task->getContent());
        $model->setType($user->getType());
        $model->setCallback('');
        $model->setPull('');
        $uid = $task_id.date('ymdHis').mt_rand(1000, 9999);
        $model->setUid($uid);
        $model->setCreated_at(date('Ymdhis'));
        $model->setNot_arrive('');
        $true = implode(',',$this->_mobiles);
        $model->setMobiles(empty($true)?'':$true);
        $model->setSend_num(count($this->_mobiles));
        $model->setTotal_num(count($this->_mobiles));
        $res = $smsMapper->insert($model);
        if(!$res){
            return $this->returnData('错误',1000);
        }
        \Mapper\SendtasksModel::getInstance()->update(array('pull_status'=>1),array('id'=>$task_id));
        return $this->returnData('发送成功',1001,true);
    }



}