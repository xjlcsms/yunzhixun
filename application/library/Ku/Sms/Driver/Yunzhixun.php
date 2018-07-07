<?php

/**
 * 短信SDK（云之讯）
 */

namespace Ku\Sms\Driver;

class Yunzhixun extends \Ku\Sms\DriverAbstract {

    protected $mt = 'https://api.ucpaas.com/sms-partner';

    public function send() {
        $service = '/access/%s/sendsms';
        $uid = date('ymdHis').mt_rand(1000, 9999);
        $url = sprintf($this->mt.$service,$this->getAccount());
        $password = substr(md5($this->getPassword()),-32);
        $params = array('uid'=>$uid,'clientid'=>$this->getAccount(),'password'=>$password);
        $params['mobile'] = $this->getPhones();
        $params['smstype'] = (string)$this->getType();
        $params['content'] = $this->getMsg();
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setParam($params,true,true);
        $http->setTimeout(5);
        try {
            $send = $http->postJson();
            $result = json_decode($send,true);
            return $result;
        } catch (\Exception $e) {
            $this->setError(json_encode(array('phones'=>$this->getPhones(),'msg'=>$this->getMsg())).'发送执行失败！');
            return false;
        }
    }


    public function push(){
        $service = '/report/%s/getreport';
        $url = sprintf($this->mt.$service,$this->getAccount());
        $password = substr(md5($this->getPassword()),-32);
        $params = array('clientid'=>$this->getAccount(),'password'=>$password);
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setParam($params,true,true);
        $http->setTimeout(5);
        try {
            $send = $http->postJson();
            $result = json_decode($send,true);
            return $result;
        } catch (\Exception $e) {
            $this->setError(json_encode(array('phones'=>$this->getPhones(),'msg'=>$this->getMsg())).'发送执行失败！');
            return false;
        }
    }

    public function pushup(){
        $service = '/report/%s/getmo';
        $url = sprintf($this->mt.$service,$this->getAccount());
        $password = substr(md5($this->getPassword()),-32);
        $params = array('clientid'=>$this->getAccount(),'password'=>$password);
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->setParam($params,true,true);
        $http->setTimeout(5);
        try {
            $send = $http->postJson();
            $result = json_decode($send,true);
            return $result;
        } catch (\Exception $e) {
            $this->setError(json_encode(array('phones'=>$this->getPhones(),'msg'=>$this->getMsg())).'发送执行失败！');
            return false;
        }
    }

}
