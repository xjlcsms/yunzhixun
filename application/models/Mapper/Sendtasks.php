<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/6/30
 * Time: 10:25
 */
namespace Mapper;

class SendtasksModel extends \Mapper\AbstractModel
{

    use \Base\Model\InstanceModel;

    protected $modelClass = '\SendtasksModel';

    protected $table = 'send_tasks';

    protected $_status = array( 0 =>'处理中', 1=> '已完成', 2=> '部分失败',3=>'失败',4=>'驳回');

    public function getStatus(){
        return $this->_status;
    }

    public function getStatusStr($status){
        return isset($this->_status[$status])?$this->_status[$status]:'';
    }

}