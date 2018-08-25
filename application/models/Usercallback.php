<?php

/**
 * User_callback
 * 
 * @Table Schema: sms_test
 * @Table Name: user_callback
 */
class UsercallbackModel extends \Base\Model\AbstractModel {

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
     * User_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_user_id = 0;

    /**
     * 回调地址
     * 
     * Column Type: varchar(400)
     * 
     * @var string
     */
    protected $_url = '';

    /**
     * Created_at
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @var int
     */
    protected $_created_at = 0;

    /**
     * Updated_at
     * 
     * Column Type: bigint(20) unsigned
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
     * @return \UsercallbackModel
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
     * User_id
     * 
     * Column Type: int(11) unsigned
     * Default: 0
     * 
     * @param int $user_id
     * @return \UsercallbackModel
     */
    public function setUser_id($user_id) {
        $this->_user_id = (int)$user_id;
        $this->_params['user_id'] = (int)$user_id;
        return $this;
    }

    /**
     * User_id
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
     * 回调地址
     * 
     * Column Type: varchar(400)
     * 
     * @param string $url
     * @return \UsercallbackModel
     */
    public function setUrl($url) {
        $this->_url = (string)$url;
        $this->_params['url'] = (string)$url;
        return $this;
    }

    /**
     * 回调地址
     * 
     * Column Type: varchar(400)
     * 
     * @return string
     */
    public function getUrl() {
        return $this->_url;
    }

    /**
     * Created_at
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $created_at
     * @return \UsercallbackModel
     */
    public function setCreated_at($created_at) {
        $this->_created_at = (int)$created_at;
        $this->_params['created_at'] = (int)$created_at;
        return $this;
    }

    /**
     * Created_at
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
     * Updated_at
     * 
     * Column Type: bigint(20) unsigned
     * Default: 0
     * 
     * @param int $updated_at
     * @return \UsercallbackModel
     */
    public function setUpdated_at($updated_at) {
        $this->_updated_at = (int)$updated_at;
        $this->_params['updated_at'] = (int)$updated_at;
        return $this;
    }

    /**
     * Updated_at
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
     * Return a array of model properties
     * 
     * @return array
     */
    public function toArray() {
        return array(
            'id'         => $this->_id,
            'user_id'    => $this->_user_id,
            'url'        => $this->_url,
            'created_at' => $this->_created_at,
            'updated_at' => $this->_updated_at
        );
    }

}
