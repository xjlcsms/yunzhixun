<?php

/**
 * 发送队列表
 * 
 * @Table Schema: sms_test
 * @Table Name: sms_queue
 */
class SmsqueueModel extends \Base\Model\AbstractModel {

    /**
     * Params
     * 
     * @var array
     */
    protected $_params = null;

    /**
     * Id
     * 
     * Column Type: int(11)
     * auto_increment
     * PRI
     * 
     * @var int
     */
    protected $_id = null;

    /**
     * 发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_task_id = 0;

    /**
     * 发送内容包含签名
     * 
     * Column Type: varchar(500)
     * 
     * @var string
     */
    protected $_content = '';

    /**
     * 发送类型
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @var int
     */
    protected $_type = 0;

    /**
     * 发送短信
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_mobiles = null;

    /**
     * 云之讯请求结果
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_callback = null;

    /**
     * 是否到达丢列
     * 
     * Column Type: tinyint(3)
     * Default: 1
     * 
     * @var int
     */
    protected $_is_arrive = 1;

    /**
     * 状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

    /**
     * 创建时间
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @var int
     */
    protected $_created_at = 0;

    /**
     * Params
     * 
     * Column Type: array
     * Default: null
     * 
     * @var array
     */
    public function getParams() {
        return $this->_params;
    }

    /**
     * Id
     * 
     * Column Type: int(11)
     * auto_increment
     * PRI
     * 
     * @param int $id
     * @return \SmsqueueModel
     */
    public function setId($id) {
        $this->_id = (int)$id;
        $this->_params['id'] = (int)$id;
        return $this;
    }

    /**
     * Id
     * 
     * Column Type: int(11)
     * auto_increment
     * PRI
     * 
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * 发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $task_id
     * @return \SmsqueueModel
     */
    public function setTask_id($task_id) {
        $this->_task_id = (int)$task_id;
        $this->_params['task_id'] = (int)$task_id;
        return $this;
    }

    /**
     * 发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @return int
     */
    public function getTask_id() {
        return $this->_task_id;
    }

    /**
     * 发送内容包含签名
     * 
     * Column Type: varchar(500)
     * 
     * @param string $content
     * @return \SmsqueueModel
     */
    public function setContent($content) {
        $this->_content = (string)$content;
        $this->_params['content'] = (string)$content;
        return $this;
    }

    /**
     * 发送内容包含签名
     * 
     * Column Type: varchar(500)
     * 
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * 发送类型
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @param int $type
     * @return \SmsqueueModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 发送类型
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 发送短信
     * 
     * Column Type: text
     * 
     * @param string $mobiles
     * @return \SmsqueueModel
     */
    public function setMobiles($mobiles) {
        $this->_mobiles = (string)$mobiles;
        $this->_params['mobiles'] = (string)$mobiles;
        return $this;
    }

    /**
     * 发送短信
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getMobiles() {
        return $this->_mobiles;
    }

    /**
     * 云之讯请求结果
     * 
     * Column Type: text
     * 
     * @param string $callback
     * @return \SmsqueueModel
     */
    public function setCallback($callback) {
        $this->_callback = (string)$callback;
        $this->_params['callback'] = (string)$callback;
        return $this;
    }

    /**
     * 云之讯请求结果
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getCallback() {
        return $this->_callback;
    }

    /**
     * 是否到达丢列
     * 
     * Column Type: tinyint(3)
     * Default: 1
     * 
     * @param int $is_arrive
     * @return \SmsqueueModel
     */
    public function setIs_arrive($is_arrive) {
        $this->_is_arrive = (int)$is_arrive;
        $this->_params['is_arrive'] = (int)$is_arrive;
        return $this;
    }

    /**
     * 是否到达丢列
     * 
     * Column Type: tinyint(3)
     * Default: 1
     * 
     * @return int
     */
    public function getIs_arrive() {
        return $this->_is_arrive;
    }

    /**
     * 状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @param int $status
     * @return \SmsqueueModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @return int
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * 创建时间
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @param int $created_at
     * @return \SmsqueueModel
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (int)$created_at;
        $this->_params['created_at'] = (int)$created_at;
        return $this;
    }

    /**
     * 创建时间
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @return int
     */
    public function getCreated_at() {
        return $this->_created_at;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'         => $this->_id,
            'task_id'    => $this->_task_id,
            'content'    => $this->_content,
            'type'       => $this->_type,
            'mobiles'    => $this->_mobiles,
            'callback'   => $this->_callback,
            'is_arrive'  => $this->_is_arrive,
            'status'     => $this->_status,
            'created_at' => $this->_created_at
        );
    }

}
