<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/7
 * Time: 20:57
 */
class JobController extends Base\ApplicationController{

    protected $_sendTypes = array(1=>'验证码',2=>'通知',3=>'营销');

    /**
     * 待处理任务列表
     */
    public function indexAction(){
        $where = ['status'=>0,'type'=>2];
        $uwhere = [];
        $username = $this->getParam('username','','string');
        $company = $this->getParam('company','','string');
        if(!empty($userid)){
            $uwhere[] = "username like '%".$username."%'";
        }
        if(!empty($company)){
            $uwhere[] = "name like '%".$company."%'";
        }
        if(!empty($uwhere)){
            $userids = \Mapper\UsersModel::getInstance()->fetchAll($uwhere,null,0,0,array('id'),false);
            $ids = [];
            foreach ($userids as $userid){
                $ids = $userid['id'];
            }
            if(empty($ids)){
                $where[] = '1=2';
            }else{
                $where[] = 'user_id in('.implode(',',$ids).')';
            }
        }
        $mapper = \Mapper\SendtasksModel::getInstance();
        $select = $mapper->select();
        $select->where($where);
        $select->order(array('created_at desc'));
        $page = $this->getParam('page', 1, 'int');
        $pagelimit = $this->getParam('pagelimit', 15, 'int');
        $pager = new \Ku\Page($select, $page, $pagelimit, $mapper->getAdapter());
        $this->assign('pager', $pager);
        $this->assign('username',$username);
        $this->assign('company',$company);
    }

    /**
     * 发送界面
     * @throws ErrorException
     */
    public function sendAction(){
        $taskid = $this->getParam('id',0,'int');
        $mapper = \Mapper\SendtasksModel::getInstance();
        $task = $mapper->fetch(array('id'=>$taskid,'status'=>0,'type'=>2));
        if(!$task instanceof \SendtasksModel){
            throw new ErrorException('发送任务不存在');
        }
        $this->assign('task',$task->toArray());
        $this->assign('sendTypes',$this->_sendTypes);
    }


    /**
     * 发送短信
     * @return false
     * @throws Exception
     * @throws \PHPExcel\PHPExcel\Reader_Exception
     */
    public function smsAction(){
        $type = $this->getParam('type',0,'int');
        $smstype = $this->getParam('smstype',0,'int');
        $taskid = $this->getParam('taskid',0,'int');
        $content = $this->getParam('content','','string');
        $task = \Mapper\SendtasksModel::getInstance()->findById($taskid);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('发送任务不存在',29204);
        }
        $user = \Mapper\UsersModel::getInstance()->findById($task->getUser_id());
        if(!$user instanceof \UsersModel){
            return $this->returnData('发送任务用户不存在',29205);
        }
        $smsBusiness = \Business\SmsModel::getInstance();
        $userBusiness = \Business\UserModel::getInstance();
        if($smstype == 1){
            $smsfile = $this->getParam('smsfile','','string');
            if(!file_exists(APPLICATION_PATH.'/public/uploads/sms/'.$smsfile || empty($smsfile))){
                return $this->returnData('发送文件不存在',29200);
            }
            $mobiles = $smsBusiness->importMobiles($smsfile);
        }else{
            $mobilesStr = $this->getParam('mobiles','','string');
            $mobiles = explode(',',$mobilesStr);
        }
        if(empty($mobiles)){
            return $this->returnData('没有获取到有效的手机号',29202);
        }
        //发送的总数
        $totalfee = $smsBusiness->totalFee($mobiles,$content);
        //到达率后的实际数量
        $arrivalMobiles = $smsBusiness->trueMobiles($user,$mobiles);
        $trueMobiles = $arrivalMobiles['true'];
        $truefee = $smsBusiness->totalFee($trueMobiles,$content);
        $virefy = $smsBusiness->virefy($user,$content,$totalfee);
        if(!$virefy){
            $message = $smsBusiness->getMessage();
            return $this->returnData($message['msg'],$message['code']);
        }
        $success = $smsBusiness->sms($user,$task,'yunzhixun',$type,$trueMobiles,$content,$arrivalMobiles['fail']);
        $typeStr = $type ==3?'market':'normal';
        if($success == $truefee){
            $res = $userBusiness->flow($user ,$success,$totalfee,$typeStr);
            if(!$res){
                $message = $userBusiness->getMessage();
                return $this->returnData($message['msg'],$message['code']);
            }
            return $this->returnData('发送成功',29201,true);
        }elseif($success <$truefee and $success >0){
            $res = $userBusiness->flow($user ,$success,$totalfee,$typeStr);
            if(!$res){
                $message = $userBusiness->getMessage();
                return $this->returnData($message['msg'],$message['code']);
            }
            return $this->returnData('发送部分失败',29208,true);
        }elseif ($success === false){
            $msg = $smsBusiness->getMessage();
            return $this->returnData($msg['msg'],$msg['code']);
        }
        return $this->returnData('发送失败',29207);
    }



    /**批量发送文件上传
     * @return false
     * @throws Exception
     * @throws \PHPExcel\PHPExcel\Reader_Exception
     */
    public function smsfileAction(){
        $fileInfo = $_FILES['smsfile'];
        $taskid = $this->getParam('taskid',0,'int');
        $task = \Mapper\SendtasksModel::getInstance()->findById($taskid);
        if(!$task instanceof \SendtasksModel){
            return $this->returnData('发送任务不存在',291004);
        }
        if (empty($fileInfo)) {
            return $this->returnData('没有文件上传！', 29100);
        }
        $name=explode('.',$fileInfo['name']);
        $lastName=end($name);
        if(strtolower($lastName) != 'xls' and strtolower($lastName) !='xlsx' and strtolower($lastName) !='xlsb'){
            return $this->returnData('上传文件格式必须为/xls/xlsx/xlsb等文件！', 28101);
        }
        if ($fileInfo['error'] > 0) {
            $errors = array(1=>'文件大小超过了PHP.ini中的文件限制！',2=>'文件大小超过了浏览器限制！',3=>'文件部分被上传！','没有找到要上传的文件！',4=>'服务器临时文件夹丢失，请重新上传！',5=>'文件写入到临时文件夹出错！');
            $error = isset($errors[$fileInfo['error']])?$errors[$fileInfo['error']]:'未知错误！';
            return $this->returnData($error, 29102);
        }
        $d = date("YmdHis");
        $randNum = rand((int)50000000, (int)10000000000);
        $filesname = $d . $randNum . '_'.$taskid.'.' .$lastName;
        $dir = APPLICATION_PATH . '/public/uploads/sms/';
        if(!file_exists($dir)){
            \Ku\Tool::makeDir($dir);
        }
        if (!copy($fileInfo['tmp_name'], $dir. $filesname)) {
            return $this->returnData('文件上传失败！', 29103);
        }
        try{
            $read = \PHPExcel\IOFactory::createReader('Excel2007');
            $obj = $read->load($dir. $filesname);
            $dataArray =$obj->getActiveSheet()->toArray();
            $mobiles = [];
            $fail = 0;
            unset($dataArray[0]);
            foreach ($dataArray as $key=> $item){
                if(!\Ku\Verify::isMobile($item[0])){
                    $fail++;
                    continue;
                }
                $mobiles[] = $item[0];
            }
            $isMobile = count($mobiles);
            $mobiles = array_unique($mobiles);
            $true = count($mobiles);
            $key = md5($filesname);
            $redis = $this->getRedis();
            $redis->set($key,json_encode($mobiles),3600*2);
            return $this->returnData('文件上传成功！', 29101,true,array('filename'=>$filesname,'total'=>$fail+$isMobile,'true'=>$true,'repeat'=>$isMobile-$true,'fail'=>$fail));
        }catch (ErrorException $errorException){
            return $this->returnData('读取文件错误',29105);
        }
    }


    /**文件删除
     * @return false
     * @throws Exception
     */
    public function delsmsfAction(){
        $fileName = $this->getParam('fileName','','string');
        $dir = APPLICATION_PATH.'/public/uploads/sms/'.$fileName;
        if(file_exists($dir)){
            @unlink($dir);
        }
        $redis = $this->getRedis();
        $redis->del(md5($fileName));
        return $this->returnData('删除成功',29011,true);
    }

    /**
     * 下载批量短信模板
     */
    public function downtempAction(){
        header('Content-Type:application/xlsx');
        header('Content-Disposition:attachment;filename=sms_template.xlsx');
        header('Cache-Control:max-age=0');
        readfile(APPLICATION_PATH.'/data/sms_template.xlsx');
        exit();
    }



}