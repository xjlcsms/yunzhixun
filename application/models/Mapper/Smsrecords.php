<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class SmsrecordsModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\SmsrecordsModel';

    protected $table = 'sms_records';


    /**
     * 导出队列数据模型
     * @return \Base\Model\AbstractModel|null
     */
    public function pull(){
        $where = array('status'=>0);
        $model = $this->fetch($where);
        if(!$model instanceof \SmsrecordsModel){
            return null;
        }
        $model->setStatus(1);
        $this->update($model);
        return $model;
    }


}