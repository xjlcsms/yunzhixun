<?php

namespace Ku\Sms;

abstract class DriverAbstract {
    protected $_phones = null;
    protected $_msg = null;
    protected $_config = null;
    protected $_sender = '';
    protected $_error = '';
    protected $_type = '';
    protected $_sendTime = '';
    protected $_account = '';
    protected $_password = '';
    protected $_uid = '';



    final public function __construct() {
        $conf = \Yaf\Registry::get('config');
        $this->_config = $conf;
    }

    private function __clone() {}
    private function __sleep() {}
    
    public function setPhones($phones){
        $this->_phones = $phones;
    }
    
    public function getPhones(){
       return $this->_phones;
    }

    public function setUid($uid){
        $this->_uid = $uid;
    }

    public function getUid(){
        return $this->_uid;
    }
    public function setMsg($msg){
        $this->_msg = $msg;
    }
    
    public function getMsg(){
        return $this->_msg;
    }
    public function setAccount($account){
        $this->_account = $account;
    }

    public function getAccount(){
        return $this->_account;
    }
    public function setPassword($password){
        $this->_password = $password;
    }

    public function getPassword(){
        return $this->_password;
    }
    public function setType($type){
        $this->_type = $type;
    }

    public function getType(){
        return $this->_type;
    }

    public function setSendTime($sendTime){
        $this->_sendTimee = $sendTime;
    }

    public function getSendTime(){
        return $this->_sendTimee;
    }

    /**
     * 
     * @return \Yaf\
     */
    public function getConfig(){
        return $this->_config;
    }
    
    public function getError(){
        return $this->_error;
    }
    public function setError($error){
        $this->_error = $error;
    }

    abstract public function send();
    abstract public function pull();
    abstract public function pullup();
}

