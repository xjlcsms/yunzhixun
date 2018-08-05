<?php
/**
 * Created by PhpStorm.
 * User: chendongqin
 * Date: 18-8-5
 * Time: 下午4:55
 */
namespace Cron;

class Error extends CronAbstract{

    public function main() {
        $func = $this->getArgv(2);
        call_user_func(array('\Cron\Error', $func));
    }


    /**扣款失败处理
     * @return bool
     */
    public function flow(){
        $userBusiness = \Business\UserModel::getInstance();
        $userMapper = \Mapper\UsersModel::getInstance();
        $redis = $this->getRedis();
        $config = \Yaf\Registry::get('config');
        $key = $config->get('flow.error');
        $begin = time();
        while (time() - $begin <60){
            $error = $redis->rPop($key);
            if(empty($error)){
                sleep(1);
                continue;
            }
            $this->start($error);
            $errorData = json_decode($error,true);
            $user = $userMapper->fingdById($errorData['userId']);
            if(!$user instanceof \UsersModel){
                $this->log($errorData['userId'].'不存在');
            }
            if(strpos($errorData['type'],'_true') !== false){
                $true = $errorData['fee'];
                $show = 0;
                $account = str_replace('_true','',$errorData['type']);
            }else{
                $true = 0;
                $show = $errorData['fee'];
                $account = str_replace('_show','',$errorData['type']);
            }
            $res = $userBusiness->flow($user,$true,$show,$account);
            if(!$res){
                $redis = $this->getRedis();
                $redis->lPush($key,$error);
                $msg = $userBusiness->getMessage();
                $this->log($msg['msg']);
            }
            $this->success($error);
        }
        return false;
    }
}