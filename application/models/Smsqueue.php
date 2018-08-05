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
     * 到达率排除手机号
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_not_arrive = null;

    /**
     * 状态0待处理1处理中2处理成功3部分成功4处理失败
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
     * 发送手机号数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_total_num = 0;

    /**
     * 实际发送给云之讯数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_send_num = 0;

    /**
     * 成功发送给云之讯数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_success = 0;

    /**
     * 云之讯uid
     * 
     * Column Type: varchar(60)
     * 
     * @var string
     */
    protected $_uid = '';

    /**
     * 回调成功的数量
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_pull_num = 0;

    /**
     * 回调回来的数据
     * 
     * Column Type: text
     * 
     * @var string
     */
    protected $_pull = null;

    /**
     * 更新时间
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
     * 到达率排除手机号
     * 
     * Column Type: text
     * 
     * @param string $not_arrive
     * @return \SmsqueueModel
     */
    public function setNot_arrive($not_arrive) {
        $this->_not_arrive = (string)$not_arrive;
        $this->_params['not_arrive'] = (string)$not_arrive;
        return $this;
    }

    /**
     * 到达率排除手机号
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getNot_arrive() {
        return $this->_not_arrive;
    }

    /**
     * 状态0待处理1处理中2处理成功3部分成功4处理失败
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
     * 状态0待处理1处理中2处理成功3部分成功4处理失败
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
     * 发送手机号数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $total_num
     * @return \SmsqueueModel
     */
    public function setTotal_num($total_num) {
        $this->_total_num = (int)$total_num;
        $this->_params['total_num'] = (int)$total_num;
        return $this;
    }

    /**
     * 发送手机号数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getTotal_num() {
        return $this->_total_num;
    }

    /**
     * 实际发送给云之讯数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $send_num
     * @return \SmsqueueModel
     */
    public function setSend_num($send_num) {
        $this->_send_num = (int)$send_num;
        $this->_params['send_num'] = (int)$send_num;
        return $this;
    }

    /**
     * 实际发送给云之讯数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getSend_num() {
        return $this->_send_num;
    }

    /**
     * 成功发送给云之讯数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $success
     * @return \SmsqueueModel
     */
    public function setSuccess($success) {
        $this->_success = (int)$success;
        $this->_params['success'] = (int)$success;
        return $this;
    }

    /**
     * 成功发送给云之讯数量
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @return int
     */
    public function getSuccess() {
        return $this->_success;
    }

    /**
     * 云之讯uid
     * 
     * Column Type: varchar(60)
     * 
     * @param string $uid
     * @return \SmsqueueModel
     */
    public function setUid($uid) {
        $this->_uid = (string)$uid;
        $this->_params['uid'] = (string)$uid;
        return $this;
    }

    /**
     * 云之讯uid
     * 
     * Column Type: varchar(60)
     * 
     * @return string
     */
    public function getUid() {
        return $this->_uid;
    }

    /**
     * 回调成功的数量
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $pull_num
     * @return \SmsqueueModel
     */
    public function setPull_num($pull_num) {
        $this->_pull_num = (int)$pull_num;
        $this->_params['pull_num'] = (int)$pull_num;
        return $this;
    }

    /**
     * 回调成功的数量
     * 
     * Column Type: int(11)
     * Default: 0
     * 
     * @return int
     */
    public function getPull_num() {
        return $this->_pull_num;
    }

    /**
     * 回调回来的数据
     * 
     * Column Type: text
     * 
     * @param string $pull
     * @return \SmsqueueModel
     */
    public function setPull($pull) {
        $this->_pull = (string)$pull;
        $this->_params['pull'] = (string)$pull;
        return $this;
    }

    /**
     * 回调回来的数据
     * 
     * Column Type: text
     * 
     * @return string
     */
    public function getPull() {
        return $this->_pull;
    }

    /**
     * 更新时间
     * 
     * Column Type: bigint(20)
     * Default: 0
     * 
     * @param int $updated_at
     * @return \SmsqueueModel
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (int)$updated_at;
        $this->_params['updated_at'] = (int)$updated_at;
        return $this;
    }

    /**
     * 更新时间
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
            'id'         => $this->_id,
            'task_id'    => $this->_task_id,
            'content'    => $this->_content,
            'type'       => $this->_type,
            'mobiles'    => $this->_mobiles,
            'callback'   => $this->_callback,
            'not_arrive' => $this->_not_arrive,
            'status'     => $this->_status,
            'created_at' => $this->_created_at,
            'total_num'  => $this->_total_num,
            'send_num'   => $this->_send_num,
            'success'    => $this->_success,
            'uid'        => $this->_uid,
            'pull_num'   => $this->_pull_num,
            'pull'       => $this->_pull,
            'updated_at' => $this->_updated_at
        );
    }

}
