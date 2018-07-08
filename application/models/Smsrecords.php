<?php

/**
 * 短信发送记录
 * 
 * @Table Schema: sms_test
 * @Table Name: sms_records
 */
class SmsrecordsModel extends \Base\Model\AbstractModel {

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
     * 外键，用户id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_user_id = 0;

    /**
     * 外键发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_task_id = 0;

    /**
     * 发送得消息内容
     * 
     * Column Type: varchar(500)
     * 
     * @var string
     */
    protected $_content = '';

    /**
     * 短信平台
     * 
     * Column Type: varchar(50)
     * 
     * @var string
     */
    protected $_driver = '';

    /**
     * 返回的数据
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_return_data = null;

    /**
     * 短信类型 1 验证码 2 通知 3 营销
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_type = 0;

    /**
     * 扣费条数
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_fee = 0;

    /**
     * 是否达到率排除的数据
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_isfail = 0;

    /**
     * 发送的手机号
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_mobiles = null;

    /**
     * 云之讯用户透传ID，随状态报告返回
     * 
     * Column Type: varchar(60)
     * 
     * @var string
     */
    protected $_uid = '';

    /**
     * 队列处理状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

    /**
     * Created_at
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @var int
     */
    protected $_created_at = 0;

    /**
     * Updated_at
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @var int
     */
    protected $_updated_at = 0;

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
     * @return \SmsrecordsModel
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
     * 外键，用户id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $user_id
     * @return \SmsrecordsModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * 外键，用户id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @return int
     */
    public function getUser_id() {
        return $this->_user_id;
    }

    /**
     * 外键发送任务id
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $task_id
     * @return \SmsrecordsModel
     */
    public function setTask_id($task_id) {
        $this->_task_id = (int)$task_id;
        $this->_params['task_id'] = (int)$task_id;
        return $this;
    }

    /**
     * 外键发送任务id
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
     * 发送得消息内容
     * 
     * Column Type: varchar(500)
     * 
     * @param string $content
     * @return \SmsrecordsModel
     */
    public function setContent($content) {
        $this->_content = (string)$content;
        $this->_params['content'] = (string)$content;
        return $this;
    }

    /**
     * 发送得消息内容
     * 
     * Column Type: varchar(500)
     * 
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * 短信平台
     * 
     * Column Type: varchar(50)
     * 
     * @param string $driver
     * @return \SmsrecordsModel
     */
    public function setDriver($driver) {
        $this->_driver = (string)$driver;
        $this->_params['driver'] = (string)$driver;
        return $this;
    }

    /**
     * 短信平台
     * 
     * Column Type: varchar(50)
     * 
     * @return string
     */
    public function getDriver() {
        return $this->_driver;
    }

    /**
     * 返回的数据
     * 
     * Column Type: text
     * 
     * @param string $return_data
     * @return \SmsrecordsModel
     */
    public function setReturn_data($return_data) {
        $this->_return_data = (string)$return_data;
        $this->_params['return_data'] = (string)$return_data;
        return $this;
    }

    /**
     * 返回的数据
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getReturn_data() {
        return $this->_return_data;
    }

    /**
     * 短信类型 1 验证码 2 通知 3 营销
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $type
     * @return \SmsrecordsModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 短信类型 1 验证码 2 通知 3 营销
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * 扣费条数
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $fee
     * @return \SmsrecordsModel
     */
    public function setFee($fee) {
        $this->_fee = (int)$fee;
        $this->_params['fee'] = (int)$fee;
        return $this;
    }

    /**
     * 扣费条数
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @return int
     */
    public function getFee() {
        return $this->_fee;
    }

    /**
     * 是否达到率排除的数据
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $isfail
     * @return \SmsrecordsModel
     */
    public function setIsfail($isfail) {
        $this->_isfail = (int)$isfail;
        $this->_params['isfail'] = (int)$isfail;
        return $this;
    }

    /**
     * 是否达到率排除的数据
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getIsfail() {
        return $this->_isfail;
    }

    /**
     * 发送的手机号
     * 
     * Column Type: text
     * 
     * @param string $mobiles
     * @return \SmsrecordsModel
     */
    public function setMobiles($mobiles) {
        $this->_mobiles = (string)$mobiles;
        $this->_params['mobiles'] = (string)$mobiles;
        return $this;
    }

    /**
     * 发送的手机号
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getMobiles() {
        return $this->_mobiles;
    }

    /**
     * 云之讯用户透传ID，随状态报告返回
     * 
     * Column Type: varchar(60)
     * 
     * @param string $uid
     * @return \SmsrecordsModel
     */
    public function setUid($uid) {
        $this->_uid = (string)$uid;
        $this->_params['uid'] = (string)$uid;
        return $this;
    }

    /**
     * 云之讯用户透传ID，随状态报告返回
     * 
     * Column Type: varchar(60)
     * 
     * @return string
     */
    public function getUid() {
        return $this->_uid;
    }

    /**
     * 队列处理状态
     * 
     * Column Type: tinyint(3)
     * Default: 0
     * 
     * @param int $status
     * @return \SmsrecordsModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * 队列处理状态
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
     * Created_at
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @param int $created_at
     * @return \SmsrecordsModel
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (int)$created_at;
        $this->_params['created_at'] = (int)$created_at;
        return $this;
    }

    /**
     * Created_at
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
     * Updated_at
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @param int $updated_at
     * @return \SmsrecordsModel
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (int)$updated_at;
        $this->_params['updated_at'] = (int)$updated_at;
        return $this;
    }

    /**
     * Updated_at
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @return int
     */
    public function getUpdated_at() {
        return $this->_updated_at;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'          => $this->_id,
            'user_id'     => $this->_user_id,
            'task_id'     => $this->_task_id,
            'content'     => $this->_content,
            'driver'      => $this->_driver,
            'return_data' => $this->_return_data,
            'type'        => $this->_type,
            'fee'         => $this->_fee,
            'isfail'      => $this->_isfail,
            'mobiles'     => $this->_mobiles,
            'uid'         => $this->_uid,
            'status'      => $this->_status,
            'created_at'  => $this->_created_at,
            'updated_at'  => $this->_updated_at
        );
    }

}
