<?php

/**
 * 拉取任务列表
 * 
 * @Table Schema: sms_test
 * @Table Name: pull_task
 */
class PulltaskModel extends \Base\Model\AbstractModel {

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
     * Column Type: int(11)
     * Default: 0
     * 
     * @var int
     */
    protected $_user_id = 0;

    /**
     * 在进行中的任务数
     * 
     * Column Type: int(5)
     * Default: 0
     * 
     * @var int
     */
    protected $_run = 0;

    /**
     * 更新时间
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @var int
     */
    protected $_updated_at = 0;

    /**
     * 拉取回调的时间
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @var int
     */
    protected $_pulled_at = 0;

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
     * @return \PulltaskModel
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
     * Column Type: int(11)
     * Default: 0
     * 
     * @param int $user_id
     * @return \PulltaskModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * 用户id
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
     * 在进行中的任务数
     * 
     * Column Type: int(5)
     * Default: 0
     * 
     * @param int $run
     * @return \PulltaskModel
     */
    public function setRun($run) {
        $this->_run = (int)$run;
        $this->_params['run'] = (int)$run;
        return $this;
    }

    /**
     * 在进行中的任务数
     * 
     * Column Type: int(5)
     * Default: 0
     * 
     * @return int
     */
    public function getRun() {
        return $this->_run;
    }

    /**
     * 更新时间
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @param int $updated_at
     * @return \PulltaskModel
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (int)$updated_at;
        $this->_params['updated_at'] = (int)$updated_at;
        return $this;
    }

    /**
     * 更新时间
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @return int
     */
    public function getUpdated_at() {
        return $this->_updated_at;
    }

    /**
     * 拉取回调的时间
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @param int $pulled_at
     * @return \PulltaskModel
     */
    public function setPulled_at($pulled_at) {
        $this->_pulled_at = (int)$pulled_at;
        $this->_params['pulled_at'] = (int)$pulled_at;
        return $this;
    }

    /**
     * 拉取回调的时间
     * 
     * Column Type: int(10)
     * Default: 0
     * 
     * @return int
     */
    public function getPulled_at() {
        return $this->_pulled_at;
    }

    /**
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'         => $this->_id,
            'user_id'    => $this->_user_id,
            'run'        => $this->_run,
            'updated_at' => $this->_updated_at,
            'pulled_at'  => $this->_pulled_at
        );
    }

}
