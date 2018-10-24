<?php
/**
 * Created by PhpStorm.
 * User: Viter
 * Date: 2018/7/8
 * Time: 15:25
 */
namespace Business;

class SmsModel  extends \Business\AbstractModel
{
    use \Base\Model\InstanceModel;

    private $_yzxSmsTypes = array(1=>'4',2=>'0',3=>'5');
    private $_userTypes = array(1=>array(1,2),2=>array(3));

    /**短信发送
     * @param \UsersModel $user
     * @param \SmsqueueModel $sms
     * @return bool|int
     * @throws \Exception
     */
    public function sms(\UsersModel $user,\SmsqueueModel $sms){
        if(!isset($this->_yzxSmsTypes[$sms->getType()])){
            return $this->getMsg(29212,'请检查发送的类型');
        }
        $smser = new \Ku\Sms\Adapter('yunzhixun');
        $driver = $smser->getDriver();
        $driver->setType($this->_yzxSmsTypes[$sms->getType()]);
        $driver->setAccount($user->getAccount());
        $driver->setPassword($user->getRaw_password());
        $driver->setMsg($sms->getContent());
        $driver->setUid($sms->getUid());
        $driver->setPhones($sms->getMobiles());
        $result = $driver->send();
        return $result;
    }

    /**api发送短信
     * @param \UsersModel $user
     * @param \SmsrecordModel $order
     * @param $content
     * @return mixed
     * @throws \Exception
     */
    public function smsApi(\UsersModel $user,\SmsrecordModel $order ,$content){
        $smser = new \Ku\Sms\Adapter('yunzhixun');
        $driver = $smser->getDriver();
        $driver->setType(4);
        $driver->setAccount($user->getAccount());
        $driver->setPassword($user->getRaw_password());
        $driver->setMsg($content);
        $driver->setUid($order->getUid());
        $driver->setPhones($order->getPhone());
        $result = $driver->send();
        return $result;
    }


    /**发送验证
     * @param \UsersModel $user
     * @param $content
     * @param $type
     * @param $sendTotal
     * @return bool
     */
    public function virefy(\UsersModel $user,$content,$type,$sendTotal){
//        if(!in_array($type , $this->_userTypes[$user->getType()])){
//            return $this->getMsg(29209,'发送的短信类型与用户类型不一致');
//        }
        $strlen = mb_strlen($content);
        if($strlen>500){
            return $this->getMsg(29206,'消息长度不能超过500字');
        }
        if($user->getType()==1){
            if($user->getShow_normal_balance()<$sendTotal){
                return $this->getMsg(29210,'行业短信余额不足');
            }
        }else{
            if($user->getShow_marketing_balance()<$sendTotal){
                return $this->getMsg(29210,'营销短信余额不足');
            }
        }
        return true;
    }

    /**手机短信批量发送
     * @param $mobiles
     * @return array
     */
    public function divideMobiles($mobiles){
        $count = count($mobiles);
        $data = [];
        if($count<=100){
            $data[] =  $mobiles;
        }else{
            $total = ceil($count / 100);
            for ($i=0;$i<$total;$i++){
                $begin = $i*100;
                $data[] = array_slice($mobiles,$begin,100);
            }
        }
        return $data;
    }

    /**获取文件的有效手机号码
     * @param $filename
     * @return array|mixed|null
     * @throws \PHPExcel\PHPExcel\Reader_Exception
     */
    public function importMobiles($filename){
        $redis = $this->getRedis();
        $mobilesJson = $redis->get(md5($filename));
        if($mobilesJson !== false){
            $mobiles = json_decode($mobilesJson,true);
        }else{
            try{
                $read = \PHPExcel\IOFactory::createReader('Excel2007');
                $obj = $read->load(APPLICATION_PATH.'/public/uploads/sms/'. $filename);
                $dataArray =$obj->getActiveSheet()->toArray();
                $mobiles = [];
                unset($dataArray[0]);
                foreach ($dataArray as $key=> $item){
                    if(\Ku\Verify::isMobile($item[0])){
                        $mobiles[] = $item[0];
                    }
                }
                $mobiles = array_unique($mobiles);
            }catch (ErrorException $errorException){
                $mobiles = null;
            }
        }
        return $mobiles;
    }


    /**发送短信总费用
     * @param array $mobiles
     * @param $content
     * @return int
     */
    public function totalFee(array $mobiles,$content){
        $count = count($mobiles);
        $strlen = mb_strlen($content);
        if($strlen>70){
            $totalfee = (ceil($strlen/67))*$count;
        }else{
            $totalfee = $count;
        }
        return (int)$totalfee;
    }

    /**短信收费
     * @param $content
     * @return int
     */
    public function oneFee($content){
        $strlen = mb_strlen($content);
        if($strlen>70){
            $fee = ceil($strlen/67);
        }else{
            $fee = 1;
        }
        return (int)$fee;
    }

    /**根据到达率产生随机发送的手机号
     * @param \UsersModel $user
     * @param $mobiles
     * @return array
     */
    public function trueMobiles(\UsersModel $user , $mobiles){
        if($user->getArrival_rate() == 100){
            $data['true'] = $mobiles;
            $data['fail'] = null;
        }elseif($user->getArrival_rate()==0){
            $data['true'] = null;
            $data['fail'] = $mobiles;
        }else{
            shuffle($mobiles);
            $len = ceil(count($mobiles)*($user->getArrival_rate()/100));
            $data['true'] = array_slice($mobiles,0,$len);
            $data['fail'] = array_slice($mobiles,$len);
        }

        return $data;
    }



//    /**拉取的回调数据存入数据库
//     * @param array $result
//     * @return bool
//     */
//    public function pullAll(array $result){
//        $mapper = \Mapper\SmsqueueModel::getInstance();
//        $uid = $result['uid'];
//        $queue = $mapper->findByUid($uid);
//        if(!$queue instanceof \SmsqueueModel){
//            return $this->getMsg(10020,'没有找到对应的uid队列');
//        }
//        $pull = json_decode($queue->getPull());
//        if(empty($pull)){
//            $pull = [];
//        }
//        array_push($pull,$result);
//        $mapper->begin();
//        $update = array('pull'=>json_encode($pull),'pull_num'=>'pull_num+1','updated_at'=>date('Ymdhis'));
//        $res = $mapper->update($update,array('Id'=>$queue->getId()));
//        if(!$res){
//            $mapper->rollback();
//            return $this->getMsg(10022,'更新数据失败');
//        }
//        $mapper->commit();
//        return $res;
//    }
    /**拉取的回调数据存入数据库
     * @param array $result
     * @return bool
     */
    public function pullAll(array $result){
        $mapper = \Mapper\SmsrecordModel::getInstance();
        $sid = $result['sid'];
        $order = $mapper->fetch(array('sid'=>$sid,'phone'=>$result['mobile']));
        if(!$order instanceof \SmsrecordModel){
            return $this->getMsg(10020,'没有找到对应的sid订单:'.$sid);
        }
        $mapper->begin();
        $order->setDesc($result['desc']);
        $order->setArrivaled_at(date('YmdHis',strtotime($result['user_receive_time'])));
        $order->setUpdated_at(date('YmsHis'));
        $order->setReport_status($result['report_status']=='SUCCESS'?1:2);
//        $order->setStatus($result['report_status']=='SUCCESS'?2:1);
        $res = $mapper->update($order);
        if(!$res){
            $mapper->rollback();
            return $this->getMsg(10022,'更新订单数据失败');
        }
        if($order->getIsapi() == 0){
            if($result['report_status']=='SUCCESS'){
                $mapper->update(array('report_status'=>1,'updated_at'=>date('YmsHis'),'rrivaled_at'=>date('YmdHis',strtotime($result['user_receive_time']))),array('uid'=>$order->getUid(),'sid'=>'','report_status'=>0));
            }
            $update = array('pull_num'=>'pull_num+1','updated_at'=>date('Ymdhis'));
            $res = \Mapper\SmsqueueModel::getInstance()->update($update,array('uid'=>$order->getUid()));
            if(!$res){
                $mapper->rollback();
                return $this->getMsg(10022,'更新发送队列数据失败');
            }
        }
        $mapper->commit();
        return $res;
    }

}