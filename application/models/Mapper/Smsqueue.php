<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class SmsqueueModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\SmsqueueModel';

    protected $table = 'sms_queue';

    /**
     * 导出队列数据模型
     * @return \Base\Model\AbstractModel|null
     */
    public function pull(){
        $where = array('status'=>0);
        $model = $this->fetch($where);
        if(!$model instanceof \SmsqueueModel){
            return null;
        }
        $model->setStatus(1);
        $model->setUpdated_at(date('YmdHis'));
        $this->update($model);
        return $model;
    }


    /**获取需要拉取回调的数据
     * @return \Base\Model\AbstractModel|null
     */
    public function pullsms(){
        $where = array('success !=0','success>pull_num');
        $order = array('updates_at asc');
        $madel = $this->fetch($where,$order);
        if(!$madel instanceof \SmsqueueModel){
            return null;
        }
        return $madel;
    }


    public function pullover(){
        $where = array('success !=0','success=pull_num','status'=>2);
        $order = array('updates_at asc');
        $madel = $this->fetch($where,$order);
        if(!$madel instanceof \SmsqueueModel){
            return null;
        }
        return $madel;
    }

}