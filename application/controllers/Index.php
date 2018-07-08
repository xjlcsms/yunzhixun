<?php
//1200
class IndexController extends \Base\ApplicationController
{
    /**
     * 首页
     */
    public function indexAction()
    {

    }

    /**
     * 修改账号密码
     * @return false
     */
    public function changepwdAction(){
        $old = $this->getParam('old','','string');
        $new = $this->getParam('new','','string');
        $sure = $this->getParam('sure','','string');
        $business = \Business\LoginModel::getInstance();
        $admin = $business->getCurrentUser();
        if(!$admin instanceof \AdminModel){
            return $this->returnData('请先登陆',1200,false,array('url'=>'/login'));
        }
        if (\Ku\Tool::valid($old, $admin->getPassword(), null) === false) {
            return $this->returnData('原密码错误，请重新输入',1202);
        }
        if(strlen($new) <6){
            return $this->returnData('新密码长度至少6位',1203);
        }
        if($new != $sure){
            return $this->returnData('两次输入的密码不一致',1204);
        }
        $admin->setPassword(\Ku\Tool::encryption($new));
        $admin->setUpdated_at(date('Y-m-d H:i:s'));
        $res = \Mapper\AdminModel::getInstance()->update($admin);
        if(!$res){
            return $this->returnData('更改密码失败,请重试！',1205);
        }
        return $this->returnData('修改成功',1201,true);
    }


    public function sendtestAction(){
        $smser = new \Ku\Sms\Adapter('yunzhixun');
        $driver = $smser->getDriver();
        $driver->setType(0);
        $driver->setAccount('b00bp2');
        $driver->setPassword('d424eb6c');
        $driver->setPhones('13606061652');
        $driver->setMsg('【测试】云之讯的测试');
        $result = $driver->send();
        if($result === false){
            var_dump($driver->getError());
        }else{
            var_dump($result);
        }
        return false;
    }

    public function testAction(){
        $smser = new \Ku\Sms\Adapter('yunzhixun');
        $driver = $smser->getDriver();
        $driver->setType(0);
        $driver->setAccount('b00bp2');
        $driver->setPassword('d424eb6c');
        $result = $driver->push();
        if($result === false){
            var_dump($driver->getError());
        }else{
            var_dump($result);
        }
        return false;
    }

}
