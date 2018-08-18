<?php

/**
 * 单条发送消息表
 * 
 * @Table Schema: sms_test
 * @Table Name: sms_order
 */
class SmsorderModel extends \Base\Model\AbstractModel {

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
     * 用户id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_user_id = 0;

    /**
     * 短信类型
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_type = 0;

    /**
     * 发送内容
     * 
     * Column Type: varchar(500)
     * 
     * @var string
     */
    protected $_content = '';

    /**
     * 发送手机号
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_mobile = 0;

    /**
     * 发送时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_created_at = 0;

    /**
     * 更新时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_updated_at = 0;

    /**
     * 到达时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_arrivaled_at = 0;

    /**
     * Status
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_status = 0;

    /**
     * 计费
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_fee = 0;

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
     * @return \SmsorderModel
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
     * 用户id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $user_id
     * @return \SmsorderModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * 用户id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getUser_id() {
        return $this->_user_id;
    }

    /**
     * 短信类型
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $type
     * @return \SmsorderModel
     */
    public function setType($type) {
        $this->_type = (int)$type;
        $this->_params['type'] = (int)$type;
        return $this;
    }

    /**
     * 短信类型
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
     * 发送内容
     * 
     * Column Type: varchar(500)
     * 
     * @param string $content
     * @return \SmsorderModel
     */
    public function setContent($content) {
        $this->_content = (string)$content;
        $this->_params['content'] = (string)$content;
        return $this;
    }

    /**
     * 发送内容
     * 
     * Column Type: varchar(500)
     * 
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * 发送手机号
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $mobile
     * @return \SmsorderModel
     */
    public function setMobile($mobile) {
        $this->_mobile = (int)$mobile;
        $this->_params['mobile'] = (int)$mobile;
        return $this;
    }

    /**
     * 发送手机号
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getMobile() {
        return $this->_mobile;
    }

    /**
     * 发送时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $created_at
     * @return \SmsorderModel
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (int)$created_at;
        $this->_params['created_at'] = (int)$created_at;
        return $this;
    }

    /**
     * 发送时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getCreated_at() {
        return $this->_created_at;
    }

    /**
     * 更新时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $updated_at
     * @return \SmsorderModel
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (int)$updated_at;
        $this->_params['updated_at'] = (int)$updated_at;
        return $this;
    }

    /**
     * 更新时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getUpdated_at() {
        return $this->_updated_at;
    }

    /**
     * 到达时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $arrivaled_at
     * @return \SmsorderModel
     */
    public function setArrivaled_at($arrivaled_at) {
        $this->_arrivaled_at = (int)$arrivaled_at;
        $this->_params['arrivaled_at'] = (int)$arrivaled_at;
        return $this;
    }

    /**
     * 到达时间
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getArrivaled_at() {
        return $this->_arrivaled_at;
    }

    /**
     * Status
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @param int $status
     * @return \SmsorderModel
     */
    public function setStatus($status) {
        $this->_status = (int)$status;
        $this->_params['status'] = (int)$status;
        return $this;
    }

    /**
     * Status
     * 
     * Column Type: tinyint(3) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getStatus() {
        return $this->_status;
    }

    /**
     * 计费
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @param int $fee
     * @return \SmsorderModel
     */
    public function setFee($fee) {
        $this->_fee = (int)$fee;
        $this->_params['fee'] = (int)$fee;
        return $this;
    }

    /**
     * 计费
     * 
     * Column Type: int(10) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getFee() {
        return $this->_fee;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'           => $this->_id,
            'user_id'      => $this->_user_id,
            'type'         => $this->_type,
            'content'      => $this->_content,
            'mobile'       => $this->_mobile,
            'created_at'   => $this->_created_at,
            'updated_at'   => $this->_updated_at,
            'arrivaled_at' => $this->_arrivaled_at,
            'status'       => $this->_status,
            'fee'          => $this->_fee
        );
    }

}
