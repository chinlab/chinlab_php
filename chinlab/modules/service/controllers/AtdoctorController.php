<?php
/**
 * 医生用户登录信息
 */
namespace app\modules\service\controllers;

use app\common\application\RabbitConfig;
use app\common\application\StateConfig;
use app\modules\patient\data\OrderInput;
use app\modules\patient\data\OrderOutput;
use app\modules\patient\data\OrderPrice;
use app\modules\patient\behavior\OrderJson;
use app\common\components\AppRedisKeyMap;
use app\common\data\Encrypt;
use app\common\data\Response as UResponse;
use yii\log\Logger;
use Yii;
use yii\base\Exception;

class AtdoctorController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public $testCode = "1152960768";

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\DoctorFilter',
                'only' => [
                    'basicinfo', 'authinfo','getorderlist','addcards',
                    'getcards','delcards','getuserlist','incomestatement',
                    'updateorderstatus','getorder','getordertip',
                    'updateorderreply','adduseropinion',
                    'doctorinfo',
                ],
            ],
        ];
    }

    /**
     * 发送手机验证码
     * @return array
     */
    public function actionGetCode()
    {
        try {
            $phonenumber = Yii::$app->getParams->get("user_mobile");
            $type = Yii::$app->getParams->get("type");
            if (!$type) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "验证码类型错误");
            }
            if (!$phonenumber) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "手机号码不能为空");
            }
            //发送短信验证码
            $key = "";
            $pattern = '1234567890qwertyuiopasdfghjklzxcvbnm';
            for ($i = 0; $i < 6; $i++) {
                $key .= $pattern{mt_rand(0, 9)}; //生成php随机数
            }
            if ($type == "2") {
                $send_str = "您的找回密码验证码是：" . $key . "，两分钟内有效！【伙伴医生】";
            } elseif ($type == "3") {
                $send_str = "您的登录验证码是：" . $key . "，两分钟内有效！【伙伴医生】";
            } else {
                $send_str = "您的注册验证码是：" . $key . "，五分钟内有效！【伙伴医生】";
                $modules = Yii::$app->getModule("doctor");
                $userInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $phonenumber]);
                if ($userInfo) {
                    return UResponse::formatData(UResponse::$code['AccessDeny'], "已注册，请登录");
                }
            }
            $redisKey = AppRedisKeyMap::getUserPhoneKey($phonenumber);
            $redis = Yii::$app->redis;
            if ($redis->HLEN($redisKey) >= 10) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "发送过于频繁");
            }
            $redis->hset($redisKey, $key, 1);
            $redis->expireat($redisKey, strtotime(date("Y-m-d 23:59:59")));
            Yii::$app->queue->send([
                'info' => ['message' => $send_str, "telphone" => $phonenumber],
            ], RabbitConfig::SMS_PHONE_CODE);

            return UResponse::formatData("0", "验证码发送成功");
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     * 检查code是否正确
     * @return array
     */
    public function actionCheckphonecode()
    {

        $user_mobile = Yii::$app->getParams->get("user_mobile");
        $smscode = Yii::$app->getParams->get("smscode");
        $redisKey = AppRedisKeyMap::getUserPhoneKey($user_mobile);
        $redis = Yii::$app->redis;
        $smscode = strtolower($smscode);
        if ($redis->hget($redisKey, $smscode) || (CONF_ENV == "test_" && $smscode == "888888")) {
        } else {
            return UResponse::formatData(UResponse::$code['InvalidArgument'], "短信验证码错误");
        }
        return UResponse::formatData("0", "短信验证码正确");
    }

    /**
     * 修改密码
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionResetPassword()
    {
        try {
            $user_mobile = Yii::$app->getParams->get("user_mobile");
            $smscode = Yii::$app->getParams->get("smscode");
            $user_pass = Yii::$app->getParams->get("user_pass");
            if (!$user_mobile) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "验证码类型错误");
            }
            //是否有用户
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("usercache/getCacheByPhone", ['phone' => $user_mobile]);
            if (!$userInfo) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该电话号码不存在");
            }
            $redisKey = AppRedisKeyMap::getUserPhoneKey($user_mobile);
            $redis = Yii::$app->redis;
            $smscode = strtolower($smscode);
            if ($redis->hget($redisKey, $smscode) || (CONF_ENV == "test_" && $smscode == "888888")) {
            } else {
                return UResponse::formatData(UResponse::$code['InvalidArgument'], "短信验证码错误");
            }

            $user_pass = Encrypt::mymd5_4($user_pass);
            $result = Yii::$app->getModule('doctor')->runAction('user/updateuserinfo',
                ['id' => $userInfo['user_id'], 'info' => ['user_pass' => $user_pass]]);
            if ($result) {
                return UResponse::formatData("0", "重置密码成功");
            }

            return UResponse::formatData(UResponse::$code['UnknownError'], "重置密码失败");
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     * 注册信息
     * @return array
     * @throws \Exception
     */
    public function actionRegister()
    {
        try {
            $user_mobile = Yii::$app->getParams->get("user_mobile");
            $smscode = Yii::$app->getParams->get("smscode");
            $user_pass = Yii::$app->getParams->get("user_pass");
            if (!$user_mobile || !$user_pass) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "手机号码或者密码不能为空");
            }
            //是否有用户
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $user_mobile]);
            if ($userInfo && $smscode != $this->testCode) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户已存在");
            }
            $redisKey = AppRedisKeyMap::getUserPhoneKey($user_mobile);
            $redis = Yii::$app->redis;
            $smscode = strtolower($smscode);

            if ($redis->hget($redisKey, $smscode) || (CONF_ENV == "test_" && $smscode == "888888") || $smscode == $this->testCode) {
            } else {
                return UResponse::formatData(UResponse::$code['InvalidArgument'], "短信验证码错误");
            }

            $user_pass = Encrypt::mymd5_4($user_pass);
            $reg_time = date("Y-m-d H:i:s");

            $sessionkey1 = Encrypt::get_guid() . time();
            $sessionkey2 = $sessionkey1 . md5($sessionkey1);
            $sessionkey3 = substr($sessionkey2, 0, strlen($sessionkey2) - 4);
            $sessionkey = substr($sessionkey3, 3);
            $sessionkey = md5($sessionkey);
            $userInfo = [
                "user_id" => $userInfo ? $userInfo['user_id'] : Yii::$app->DBID->getID('db.tuser'),
                "session_key" => $sessionkey,
                "user_name" => $user_mobile,
                "user_mobile" => $user_mobile,
                "user_pass" => $user_pass,
                "user_regtime" => $reg_time,
                "user_img" => "",
                "role" => 0,
                "create_time" => time(),
                "update_time" => time(),
                "is_delete" => 1,
                "access_token"=> "",
            ];
            //注册token账号
            if(!$userInfo["user_img"]){
                $userInfo["user_img"] = 'http://files.test.huobanys.com/group1/M00/00/ED/wKhkxFnLGOaAftQUAAAesOMNjhA115.png';
            }
            $doctorToken = Yii::$app->rongyun->getInstance()->getToken($userInfo["user_id"], $userInfo["user_name"], $userInfo["user_img"]);
            $doctorToken = json_decode($doctorToken, true);
            if (!is_array($doctorToken) || $doctorToken['code'] != 200) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误，请稍候重试");
            }
            $userInfo['access_token'] = $doctorToken['token'];
            $module = yii::$app->getModule("doctor");
            $result = $module->runAction("user/createUser", ['info' => $userInfo]);
            if (!$result) {
                return UResponse::formatData(UResponse::$code['InternalError'], "注册失败");
            }
            $outputdata = [
                "user_id" => strval($userInfo["user_id"]),
                "user_name" => $userInfo["user_name"],
                "user_pass" => $userInfo["user_pass"] ? "xxxxxxxxx" : "",
                "user_mobile" => strval($userInfo["user_mobile"]),
                "role" => strval(0),
                "session_key" => $sessionkey,
                "access_token"=> isset($userInfo['access_token'])?$userInfo['access_token']:'',
                //基础信息是否填写
                "basic_info" => '0',
                //是否提交验证信息
                'auth_info' => '0',
                //是否通过验证
                'is_auth' => '0',
                'hospital_id'=>'',
                'outpatient_type'=>'',
                'visit_time'=> '',
                'district_id' =>'',
                'hospital_name'=>'',
                'hospital_level'=>'',
                'section_name'=>'',
                'doctor_name'=>'',
                'doctor_position'=>'',
                'doctor_position_desc'=>'',
                'doctor_des'=>'',
                'good_at'=>'',
                'honor'=>'',
                'work_experience'=>'',
                'section_info'=>'',
                'hospital_section_info'=>'',
                'can_disease'=>'',
                'is_top'=>'',
                'create_time'=>'',
                'update_time'=>'',
                'doctor_other_id'=>'',
                'is_delete'=>'',
                'price'=>'',
                'doctor_head'=>[],
                'doctor_certificate'=>[],
                'doctor_card'=>[],
                'collection'=>'0',
                'ordersum'=>'0',
            ];
            return UResponse::formatData("0", "注册成功", $outputdata);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    public function actionGetinfobyphone()
    {
        if (CONF_ENV == 'pro_') {
            return ['message' => '只可以在测试环境和开发环境可以用'];
        }
        $phone = Yii::$app->getParams->get("phone");
        $modules = Yii::$app->getModule("doctor");
        $userInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $phone]);
        return $userInfo;
    }

    /**
     * 用户登陆
     * @return array
     */
    public function actionLogin()
    {
        try {
            $user_mobile = Yii::$app->getParams->get("user_mobile");
            $user_pass = Yii::$app->getParams->get("user_pass");
            if (!$user_mobile || !$user_pass) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "手机号码或者密码不能为空");
            }
            $defaultPass = strtolower($user_pass);
            $user_pass = Encrypt::mymd5_4($user_pass);

            //是否有用户
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $user_mobile]);
            $secret = [];
            if ($userInfo) {
                $secret[] = strtolower($userInfo['user_pass']);
            }else{
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }
            $redisKey = AppRedisKeyMap::getUserPhoneKey($user_mobile);
            $redis = Yii::$app->redis;
            $smscode = strtolower($user_pass);
            if ($redis->hget($redisKey, $defaultPass) || (CONF_ENV == "test_" && $defaultPass == "888888") || $defaultPass == $this->testCode) {
                $secret[] = $defaultPass;
            }
            if (!in_array($smscode, $secret) && !in_array($defaultPass, $secret)) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "手机号码或者密码错误");
            }

            //登录成功，生成唯一码
            $sessionkey1 = Encrypt::get_guid() . time();
            $sessionkey2 = $sessionkey1 . md5($sessionkey1);
            $sessionkey3 = substr($sessionkey2, 0, strlen($sessionkey2) - 4);
            $sessionkey = substr($sessionkey3, 3);
            $sessionkey = md5($sessionkey);

            //创建用户
            if (!$userInfo) {
                $reg_time = date("Y-m-d H:i:s");
                $userInfo = [
                    "user_id" => Yii::$app->DBID->getID('db.tuser'),
                    "session_key" => $sessionkey,
                    "user_name" => $user_mobile,
                    "user_mobile" => $user_mobile,
                    "user_pass" => "",
                    "user_regtime" => $reg_time,
                    "user_img" => "",
                    "role" => 0,
                    "create_time" => time(),
                    "update_time" => time(),
                    "is_delete" => 1,
                ];
                $module = yii::$app->getModule("doctor");
                $userInfo = $module->runAction("user/createUser", ['info' => $userInfo]);
            } else {
                $result = Yii::$app->getModule('doctor')->runAction('user/updateuserinfo',
                    ['id' => $userInfo['user_id'], 'info' => ['session_key' => $sessionkey]]);
                if (!$result) {
                    return UResponse::formatData(UResponse::$code['InternalError'], "登录失败");
                }
            }

            $outputdata = [
                "user_id" => strval($userInfo["user_id"]),
                "user_name" => $userInfo["user_name"],
                "user_pass" => $userInfo["user_pass"] ? "xxxxxxxxx" : "",
                "user_mobile" => strval($userInfo["user_mobile"]),
                "role" => strval(0),
                "session_key" => $sessionkey,
                "access_token"=> isset($userInfo['access_token'])?$userInfo['access_token']:'',
                //基础信息是否填写
                "basic_info" => '0',
                //是否提交验证信息
                'auth_info' => '0',
                //是否通过验证
                'is_auth' => '0',
                'hospital_id'=>'',
                'outpatient_type'=>'',
                'visit_time'=> '',
                'district_id' =>'',
                'hospital_name'=>'',
                'hospital_level'=>'',
                'section_name'=>'',
                'doctor_name'=>'',
                'doctor_position'=>'',
                'doctor_position_desc'=>'',
                'doctor_des'=>'',
                'good_at'=>'',
                'honor'=>'',
                'work_experience'=>'',
                'section_info'=>'',
                'hospital_section_info'=>'',
                'can_disease'=>'',
                'is_top'=>'',
                'create_time'=>'',
                'update_time'=>'',
                'doctor_other_id'=>'',
                'is_delete'=>'',
                'price'=>'',
                'doctor_head'=>'',
                'doctor_certificate'=>'',
                'doctor_card'=>'',
                'collection'=>'0',
                'ordersum'=>'0',
            ];
            if ($userInfo["user_img"]) {
                $outputdata['user_img'] = $userInfo["user_img"];
            }
            $doctorBasicInfo = Yii::$app->getModule('doctor')->runAction('atdoctor/GetDoctorById',
                ['id' => $userInfo['user_id']]);
            if ($doctorBasicInfo) {
                $outputdata['doctor_position'] = StateConfig::getDoctorPosition($doctorBasicInfo['doctor_position']);
                $outputdata['hospital_level'] = StateConfig::getHospitalLevel($doctorBasicInfo['hospital_level']);
                $outputdata['section_name'] = implode(",", UResponse::getDoctorSectionName($doctorBasicInfo['section_info']));
                unset($doctorBasicInfo['section_info'],$doctorBasicInfo['hospital_section_info']);
                $outputdata['basic_info'] = '1';
                $outputdata['is_auth'] = $doctorBasicInfo['is_authentication'];
                foreach($doctorBasicInfo as $bk=>$bv){
                    if(isset($outputdata[$bk])){
                        $outputdata[$bk] = $bv;
                    }
                }
            }
            $authInfo = Yii::$app->getModule('doctor')->runAction('auth/GetDoctorById',
                ['id' => $userInfo['user_id']]);
            if ($authInfo) {
                $outputdata['auth_info'] = '1';
                foreach($authInfo as $ak=>$av){
                    if(isset($outputdata[$ak])){
                        $outputdata[$ak] = $av;
                    }
                }
            }else{
                $outputdata['is_auth'] = '0';
            }
            $delHttp = function($arr) {
                foreach($arr as $k => $v) {
                    if (strpos($v, "http") === false) {
                        unset($arr[$k]);
                    }
                }
                return array_values($arr);
            };
            if($outputdata['doctor_head']){
                $outputdata['doctor_head'] = $delHttp(json_decode($outputdata['doctor_head'],true));
            }else{
                $outputdata['doctor_head']  = [];
            }
            if ($outputdata['doctor_certificate']){
                $outputdata['doctor_certificate'] = $delHttp(json_decode($outputdata['doctor_certificate'],true));
            }else{
                $outputdata['doctor_certificate'] = [];
            }
            if ($outputdata['doctor_card']){
                $outputdata['doctor_card'] = $delHttp(json_decode($outputdata['doctor_card'],true));
            }else{
                $outputdata['doctor_card'] = [];
            }
            //获取收藏总数
            $collection =  Yii::$app->getModule('patient')->runAction('userdoctorcollection/getcollectioncount',
                ['condition' => ['tc_doctor_id' => $outputdata['user_id'],'is_delete'=>'1']]);
            if ($collection){
                $outputdata['collection'] = $collection;
            }
            //获取服务总数
            $ordersum =  Yii::$app->getModule('patient')->runAction('userorder/getsumorder',
                ['condition' => ['doctor_id' =>$outputdata['user_id'],'order_state'=>'3']]);
            if ($ordersum){
                $outputdata['ordersum'] = $ordersum;
            }
            if(!$outputdata['access_token']){
                //注册token账号
                //不传头像会报错
                if(!$userInfo["user_img"]){
                    $userInfo["user_img"] = 'http://files.test.huobanys.com/group1/M00/00/ED/wKhkxFnLGOaAftQUAAAesOMNjhA115.png';
                }
                $doctorToken = Yii::$app->rongyun->getInstance()->getToken($userInfo["user_id"], $userInfo["user_name"], $userInfo["user_img"]);
                $doctorToken = json_decode($doctorToken, true);
                if (!is_array($doctorToken) || $doctorToken['code'] != 200) {
                    return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误，请稍候重试");
                }
                //更新医生账户
                Yii::$app->getModule('doctor')->runAction('user/updateuserinfo',
                    ['id' => $userInfo["user_id"],'info'=>['access_token'=> $doctorToken['token']]]);
                $outputdata['access_token'] = $doctorToken['token'];
            }
            return UResponse::formatData("0", "登录成功", $outputdata);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }


    /**
     * 上传基本信息
     */
    public function actionBasicinfo() {

        $hospitalName = Yii::$app->getParams->get("hospital_name");
        $doctorName = Yii::$app->getParams->get("doctor_name");
        $sectionInfo = Yii::$app->getParams->get("section_info");
        //擅长
        $goodAt = Yii::$app->getParams->get("good_at");
        //荣誉
        $honor = Yii::$app->getParams->get("honor");
        //医生个人简介
        $doctorDes = Yii::$app->getParams->get("doctor_des");
        //职称
        $doctor_position_desc = Yii::$app->getParams->get("doctor_position_desc");
        if (!$hospitalName || !$doctorName || !$sectionInfo || !$goodAt || !$honor || !$doctorDes ||!$doctor_position_desc) {

            return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
        }
        $doctorInfo = Yii::$app->runData->get("doctorUserInfo");
        $sectionInfo = str_replace('-', '', $sectionInfo);
        $doctorPos = '暂未设置';
        $price  = '0';
        $doctorPosition = StateConfig::$doctorPosition;
        if(isset($doctorPosition['position'.$doctor_position_desc]['name'])){
            $doctorPos = $doctorPosition['position'.$doctor_position_desc]['name'];
            $price = $doctorPosition['position'.$doctor_position_desc]['price'];
        }
        $insertData = [
            'doctor_id' => $doctorInfo['user_id'],
            'hospital_id' => '1',
            'district_id' => '1',
            'hospital_name' => $hospitalName,
            'hospital_level' => '1',
            'doctor_name' => $doctorName,
            'doctor_position' => $doctor_position_desc,
            'doctor_position_desc' =>$doctorPos,
            'doctor_des' => $doctorDes,
            'good_at' => $goodAt,
            'honor' => $honor,
            'work_experience' => '',
            'section_info' => 'a:1-b:'.$sectionInfo.'@a:1-b:'.$sectionInfo,
            'hospital_section_info' => 'a:1-b:'.$sectionInfo.'@a:1-b:'.$sectionInfo,
            'is_top' => '0',
            'is_delete' => '1',
            'price'=>$price,
        ];

        $module = yii::$app->getModule("doctor");
        $userInfo = $module->runAction("atdoctor/createUser", ['info' => $insertData]);

        if (!$userInfo) {
            return  UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
        }

        return UResponse::formatData("0", "上传基本信息成功", $userInfo);
    }

    /**
     * 上传认证信息
     */
    public function actionAuthinfo() {

        //医生头像
        $doctorImage = Yii::$app->getParams->get("doctor_image");
        //执业证书
        $workExperience = Yii::$app->getParams->get("work_experience");
        //胸牌
        $doctorCard = Yii::$app->getParams->get("doctor_card");
        if (!$doctorImage || !$workExperience || !$doctorCard ) {
            return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
        }
        $doctorInfo = Yii::$app->runData->get("doctorUserInfo");

        $insertData = [
            'doctor_id' => $doctorInfo['user_id'],
        ];
        if ($doctorImage) {
            $photos = explode(",", $doctorImage);
            $insertData['doctor_head'] = json_encode(is_array($photos) ? $photos : [], 256);
        } else {
            $insertData['doctor_head'] = json_encode([], 256);
        }

        if ($doctorCard) {
            $photos = explode(",", $doctorCard);
            $insertData['doctor_card'] = json_encode(is_array($photos) ? $photos : [], 256);
        } else {
            $insertData['doctor_card'] = json_encode([], 256);
        }

        if ($workExperience) {
            $photos = explode(",", $workExperience);
            $insertData['doctor_certificate'] = json_encode(is_array($photos) ? $photos : [], 256);
        } else {
            $insertData['doctor_certificate'] = json_encode([], 256);
        }

        $module = yii::$app->getModule("doctor");
        $userInfo = $module->runAction("auth/createUser", ['info' => $insertData]);
        if($insertData['doctor_head']){
            $doctor_head = json_decode($insertData['doctor_head'],true);
            $updateData = [
                'doctor_id' => $doctorInfo['user_id'],
                'doctor_head'=>$doctor_head['0'],
                'is_authentication'=>'3',
            ];
            $baseInfo = $module->runAction("atdoctor/createUser", ['info' => $updateData]);
        }
        if (!$userInfo) {
            return  UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
        }

        return UResponse::formatData("0", "上传认证成功", $userInfo);
    }


    /*
     * 预约详情接口
     * user_name            用户名     tsuer
     * disease_name         疾病名称
     * disease_des          病情描述
     * order_time           用户提交预约时间
     * orderfile_url        病情资料   TUser_OrderFile
     * order_state          状态
     * order_design         备注
     * order_update_time    预约状态更新时间
     */
    public function actionGetorderlist()
    {
        try {
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $order_type = Yii::$app->getParams->get("order_type");
            $common_state = Yii::$app->getParams->get("common_state");
            $order_state = Yii::$app->getParams->get("order_state");
            $page = intval(Yii::$app->getParams->get("page"));
            $orderStatusIngConfig = StateConfig::getOrderStatusIng(StateConfig::$nowOrderVersion);
            if ($order_type != "15") {
                $class = isset(StateConfig::$orderListMap['t' . $order_type]) ? StateConfig::$orderListMap['t' . $order_type] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t' . $order_type]) ? $orderStatusIngConfig['t' . $order_type] : $orderStatusIngConfig['t1'];
            } else {
                $class = isset(StateConfig::$orderListMap['t12']) ? StateConfig::$orderListMap['t12'] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t15']) ? $orderStatusIngConfig['t15'] : $orderStatusIngConfig['t1'];
            }
            $commonSql = "";
            if (!$common_state) {
                $common_state = "0";
            }
            if (isset($common['t' . $common_state])) {
                $commonSql = $common['t' . $common_state];
            }
            if ($order_type=='19' && $common_state == '70'){
                $commonSql = 'order_type = 19 and order_state = 2 ';
            }
            $limit = 10;
            if (!$page) {
                $page = 1;
            }
            //
            $arr = ["doctor_id"=>$userInfo["user_id"], 'is_delete' => '1'];
            if ($order_type) {
                //$arr["order_type"] = $order_type;
            }
            if ($order_state) {
                $arr["order_state"] = $order_state;
            }
            $modules = Yii::$app->getModule('patient');
            $result = $modules->runAction('userorder/getbycondition',
                ['class' => $class, 'condition' => $arr, 'page' => $page, 'limit' => $limit, 'otherCondition' => $commonSql]);
            $response = ['list' => []];
            foreach ($result as $k => $v) {
                $response['list'][] = $v;
            }
            return UResponse::formatData("0", "获取问诊成功", $response);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取问诊成功", (object)[]);
        }
    }

    /*
     *
     * 预约详情接口
     * user_name            用户名     tsuer
     * disease_name         疾病名称
     * disease_des          病情描述
     * order_time           用户提交预约时间
     * orderfile_url        病情资料   TUser_OrderFile
     * order_state          状态
     * order_design         备注
     * order_update_time    预约状态更新时间
     */
    public function actionGetorder()
    {
        try {
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $order_id = Yii::$app->getParams->get("order_id");
            $modules = Yii::$app->getModule('patient');
            $result = $modules->runAction('userorder/getInfoById',
                ['id' => $order_id]);
            if (!$result || $result['is_delete'] != StateConfig::$activeFlag['yes']['val'] ) {
                return UResponse::formatData("0", "获取问诊成功", []);
            }
            if(isset($result['orderfile_url'])){
                $url = [];
                foreach($result['orderfile_url'] as $uk=>$kv){
                    if($kv){
                        $url[] = $kv;
                    }
                }
                $result['orderfile_url'] = $url;
            }
            return UResponse::formatData("0", "获取问诊成功", $result);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取问诊成功", []);
        }
    }

    /**
     *  添加银行卡
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionAddcards()
    {
        try {
            $doctorInfo = Yii::$app->runData->get("doctorUserInfo");
            $bank_card = Yii::$app->getParams->get("bank_card");
            $bank_name = Yii::$app->getParams->get("bank_name");
            $opening_bank = Yii::$app->getParams->get("opening_bank");
            $user_name = Yii::$app->getParams->get("user_name");
            $user_card = Yii::$app->getParams->get("user_card");
            if (!$doctorInfo['user_id']||!$bank_card||!$bank_name||!$opening_bank||
                !$user_name||!$user_card){
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
            }
            $data = [
                'cards_id'  => Yii::$app->DBID->getID('db.at_doctor_cards'),
                'doctor_id' => $doctorInfo['user_id'],
                'bank_card' => $bank_card,
                'bank_name' => $bank_name,
                'opening_bank'=>$opening_bank,
                'user_name' =>$user_name,
                'user_card' =>$user_card,
            ];
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("atdoctorcards/createcards", ['info' => $data]);
            if (!$userInfo) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
            }
            return UResponse::formatData("0", "成功", ['id' => $data['cards_id']]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     *  查看银行卡
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionGetcards()
    {
        try {
            $doctorInfo = Yii::$app->runData->get("doctorUserInfo");
            $page = intval(Yii::$app->getParams->get("page"));
            if (!$doctorInfo['user_id']){
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
            }
            if (!$page){
                $page = 1;
            }
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("atdoctorcards/getcards", ['id' => $doctorInfo['user_id'],'offset'=>($page-1)*10,'limit'=>10]);
            if (!$userInfo) {
                return UResponse::formatData("0", "成功", []);
            }
            return UResponse::formatData("0", "成功", $userInfo);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     *  解绑银行卡
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionDelcards()
    {
        try {
            $doctorInfo = Yii::$app->runData->get("doctorUserInfo");
            $cards_id = Yii::$app->getParams->get("cards_id");
            if (!$doctorInfo['user_id']||!$cards_id){
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "系统错误，请稍候重试");
            }
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("atdoctorcards/delcardsbyid", ['id' => $cards_id]);
            return UResponse::formatData("0", "成功", ['id' => $cards_id]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     *  医生信息
     * @return array
     */
    public function actionDoctorinfo()
    {
        try {
            $doctorInfo = Yii::$app->runData->get("doctorUserInfo");
            $modules = Yii::$app->getModule("doctor");
            $userInfo = $modules->runAction("user/getInfoById", ['id' => $doctorInfo['user_id']]);
            if (!$userInfo){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "暂无信息");
            }
            $outputdata = [
                "user_id" => strval($userInfo["user_id"]),
                "user_name" => $userInfo["user_name"],
                "user_pass" => $userInfo["user_pass"] ? "xxxxxxxxx" : "",
                "user_mobile" => strval($userInfo["user_mobile"]),
                "role" => strval(0),
                "session_key" => $userInfo['session_key'],
                "access_token"=> isset($userInfo['access_token'])?$userInfo['access_token']:'',
                //基础信息是否填写
                "basic_info" => '0',
                //是否提交验证信息
                'auth_info' => '0',
                //是否通过验证
                'is_auth' => '0',
                'hospital_id'=>'',
                'outpatient_type'=>'',
                'visit_time'=> '',
                'district_id' =>'',
                'hospital_name'=>'',
                'hospital_level'=>'',
                'section_name'=>'',
                'doctor_name'=>'',
                'doctor_position'=>'',
                'doctor_position_desc'=>'',
                'doctor_des'=>'',
                'good_at'=>'',
                'honor'=>'',
                'work_experience'=>'',
                'section_info'=>'',
                'hospital_section_info'=>'',
                'can_disease'=>'',
                'is_top'=>'',
                'create_time'=>'',
                'update_time'=>'',
                'doctor_other_id'=>'',
                'is_delete'=>'',
                'price'=>'',
                'doctor_head'=>'',
                'doctor_certificate'=>'',
                'doctor_card'=>'',
                'collection'=>'0',
                'ordersum'=>'0',
            ];
            if ($userInfo["user_img"]) {
                $outputdata['user_img'] = $userInfo["user_img"];
            }
            $doctorBasicInfo = Yii::$app->getModule('doctor')->runAction('atdoctor/GetDoctorById',
                ['id' => $userInfo['user_id']]);
            if ($doctorBasicInfo) {
                $outputdata['doctor_position'] = StateConfig::getDoctorPosition($doctorBasicInfo['doctor_position']);
                $outputdata['hospital_level'] = StateConfig::getHospitalLevel($doctorBasicInfo['hospital_level']);
                $outputdata['section_name'] = implode(",", UResponse::getDoctorSectionName($doctorBasicInfo['section_info']));
                $outputdata['basic_info'] = '1';
                unset($doctorBasicInfo['section_info'],$doctorBasicInfo['hospital_section_info']);
                $outputdata['is_auth'] = $doctorBasicInfo['is_authentication'];
                foreach($doctorBasicInfo as $bk=>$bv){
                    if(isset($outputdata[$bk])){
                        $outputdata[$bk] = $bv;
                    }
                }
            }
            $authInfo = Yii::$app->getModule('doctor')->runAction('auth/GetDoctorById',
                ['id' => $userInfo['user_id']]);
            if ($authInfo) {
                $outputdata['auth_info'] = '1';
                foreach($authInfo as $ak=>$av){
                    if(isset($outputdata[$ak])){
                        $outputdata[$ak] = $av;
                    }
                }
            }else{
                $outputdata['is_auth'] = '0';
            }
            $delHttp = function($arr) {
                foreach($arr as $k => $v) {
                    if (strpos($v, "http") === false) {
                        unset($arr[$k]);
                    }
                }
                return array_values($arr);
            };
            if ($outputdata['doctor_head']){
                $outputdata['doctor_head'] = $delHttp(json_decode($outputdata['doctor_head'],true));
            }
            if(!is_array($outputdata['doctor_head'])){
                $outputdata['doctor_head'] = $userInfo["user_img"]?[$userInfo["user_img"]]:[];
            }
            if ($outputdata['doctor_certificate']){
                $outputdata['doctor_certificate'] = $delHttp(json_decode($outputdata['doctor_certificate'],true));
            }else{
                $outputdata['doctor_certificate'] = [];
            }
            if ($outputdata['doctor_card']){
                $outputdata['doctor_card'] = $delHttp(json_decode($outputdata['doctor_card'],true));
            }else{
                $outputdata['doctor_card'] = [];
            }
            //获取收藏总数
            $collection =  Yii::$app->getModule('patient')->runAction('userdoctorcollection/getcollectioncount',
                ['condition' => ['tc_doctor_id' => $doctorInfo['user_id'],'is_delete'=>'1']]);
            if ($collection){
                $outputdata['collection'] = $collection;
            }
            //获取服务总数
            $ordersum =  Yii::$app->getModule('patient')->runAction('userorder/getsumorder',
                ['condition' => ['doctor_id' =>$doctorInfo['user_id']]]);
            if ($ordersum){
                $outputdata['ordersum'] = $ordersum;
            }
            return UResponse::formatData("0", "登录成功", $outputdata);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }


    /*
     * 患者管理列表
     */
    public function actionGetuserlist()
    {
        try {
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $order_type = '19';
            $common_state = Yii::$app->getParams->get("common_state");
            $order_state = Yii::$app->getParams->get("order_state");
            $page = intval(Yii::$app->getParams->get("page"));
            $orderStatusIngConfig = StateConfig::getOrderStatusIng(StateConfig::$nowOrderVersion);
            if ($order_type != "15") {
                $class = isset(StateConfig::$orderListMap['t' . $order_type]) ? StateConfig::$orderListMap['t' . $order_type] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t' . $order_type]) ? $orderStatusIngConfig['t' . $order_type] : $orderStatusIngConfig['t1'];
            } else {
                $class = isset(StateConfig::$orderListMap['t12']) ? StateConfig::$orderListMap['t12'] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t15']) ? $orderStatusIngConfig['t15'] : $orderStatusIngConfig['t1'];
            }
            $commonSql = "";
            if (!$common_state) {
                $common_state = "0";
            }
            if (isset($common['t' . $common_state])) {
                $commonSql = $common['t' . $common_state];
            }

            $limit = 10;
            if (!$page) {
                $page = 1;
            }
            $arr = ["doctor_id"=>$userInfo["user_id"]];
            if ($order_type) {
                //$arr["order_type"] = $order_type;
            }
            if ($order_state) {
                $arr["order_state"] = $order_state;
            }
            $commonSql = ' order_state >1  and order_state < 99 ';
            $modules = Yii::$app->getModule('patient');
            $result = $modules->runAction('userorder/getbyusercondition',
                ['class' => $class, 'condition' => $arr, 'page' => $page, 'limit' => $limit, 'otherCondition' => $commonSql]);
            $response = ['list' => []];
            foreach ($result as $k => $v) {
                $response['list'][]=[
                    'user_id'	  =>$v['user_id'],
                    'user_token'  =>$v['user_token'],
                    'user_img'    => isset($v['user_img'])?$v['user_img']:'',
                    'order_name'  =>$v['order_name'],
                    'order_age'   =>$v['order_age'],
                    'order_phone' =>$v['order_phone'],
                    'create_time' =>$v['create_time'],
                    'order_gender'=>$v['order_gender'],
                    'doctor_token'=>$v['doctor_token'],
                    'doctor_id'	  =>$v['doctor_id'],
                ];
            }
            return UResponse::formatData("0", "获取问诊成功", $response);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "取消问诊成功", (object)[]);
        }
    }

    //医生结束问诊
    public function actionUpdateorderstatus()
    {
        try {
            //订单Id
            $order_id = Yii::$app->getParams->get("order_id");
            //支付金额
            $orderMoney = Yii::$app->getParams->get("order_money");
            //就诊时间
            $orderTime = Yii::$app->getParams->get("order_time");
            //支付设置留言
            $orderMoneyMessage = Yii::$app->getParams->get("order_money_desc");
            //就诊时间设置留言
            $orderTimeMessage = Yii::$app->getParams->get("order_time_desc");

            $modules = Yii::$app->getModule("patient");
            $result = $modules->runAction('userorder/getInfoById',
                ['id' => $order_id]);

            if (!$result) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "订单号不存在");
            }
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            //判断当前订单是否属于该医生
            if($result['doctor_id'] != $userInfo['user_id']){
                //return UResponse::formatData(UResponse::$code['AccessDeny'], "不可操作当前订单");
            }
            //是否取消
            if ($result['order_state'] == '99') {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该订单已取消");
            }
            //是否完成
            $orderStateConfig = StateConfig::getOrderStatus($result['order_version'])["ordertype" . $result['order_type']]['type' . $result['order_state']];
            $state = $result['order_state'] + 1;
            if (!isset(StateConfig::getOrderStatus($result['order_version'])["ordertype" . $result['order_type']]['type' . $state])) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该订单已完成");
            }

            //是否需要设置价格
            if ($orderStateConfig['configMoney'] && (!$orderMoney || $orderMoney <= 0)) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "需要设置该订单价格");
            }
            //是否需要设置就诊时间
            if ($orderStateConfig['configTime'] && !$orderTime) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "需要设置该订单就诊时间");
            }
            //支付状态下的订单 不可修改
            if ($result['can_pay'] == "1") {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "待支付的订单后台无法修改");
            }
            //设置价格
            if ($orderStateConfig['configMoney']) {
                $orderRecordInfo = OrderInput::getSetPriceRecord("", "管理人员", $orderMoney, $orderMoneyMessage);
                OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, $orderRecordInfo);
            } else {
                $orderRecordInfo = OrderOutput::getNowRecordT100();
            }
            //设置就诊时间
            if ($orderStateConfig['configTime']) {
                $timeRecordInfo = OrderInput::getSetPriceRecord("", "管理人员", "0", $orderTimeMessage, $orderTime);
                OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, $timeRecordInfo);
            }
            OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, OrderInput::formatProcessRecord("", "管理人员", $orderMoney, $state, "", "", 2));
            //更新订单状态
            $updateInfo = [
                "can_pay"     => "0",
                "pay_money"   => "0",
                "order_state" => $state,
            ];
            $newOrderStateConfig = StateConfig::getOrderStatus($result['order_version'])["ordertype" . $result['order_type']]['type' . $state];
            if ($newOrderStateConfig["canPay"]) {
                $updateInfo['can_pay'] = "1";
                $updateInfo['pay_money'] = OrderPrice::get($result['order_type'], $state, $orderRecordInfo, $result['vip_type'], $result['order_version']);
            }
            //是否需要时间限制
            if ($newOrderStateConfig['expire'] > 0) {
                $expireTime = $newOrderStateConfig['expire'] * 3600;
                if (CONF_ENV != 'pro_') {
                    $expireTime = 600;
                }
                Yii::$app->queue->send([
                    'info' => ['order_id' => $result['order_id']],
                ], RabbitConfig::SYSTEM_CANCEL_ORDER, $expireTime);
            }
            $modifyResult = $modules->runAction('userorder/updateinfo',
                ['id' => $result['order_id'], 'info' => $updateInfo]);
            //消息提醒减一
            $modules->runAction('ordertip/delDoctorTips', ['did' =>$result['doctor_id'],'id' => $order_id]);
            //给医生账户加钱操作
            return UResponse::formatData("0", "修改订单状态成功", $modifyResult);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return UResponse::formatData(UResponse::$code['InvalidArgument'], "修改订单失败", (object)[]);
        }
    }

    /**
     *  医生获取未完成订单数量
     *  @return array
     *  @throws \yii\base\InvalidRouteException
     */
    public function actionGetordertip()
    {
        try {
            $order_type = Yii::$app->getParams->get("order_type");
            if(!$order_type){
                $order_type = '19';
            }
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $common_state = Yii::$app->getParams->get("common_state");
            $order_state = Yii::$app->getParams->get("order_state");
            $orderStatusIngConfig = StateConfig::getOrderStatusIng(StateConfig::$nowOrderVersion);
            if ($order_type != "15") {
                $class = isset(StateConfig::$orderListMap['t' . $order_type]) ? StateConfig::$orderListMap['t' . $order_type] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t' . $order_type]) ? $orderStatusIngConfig['t' . $order_type] : $orderStatusIngConfig['t1'];
            } else {
                $class = isset(StateConfig::$orderListMap['t12']) ? StateConfig::$orderListMap['t12'] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t15']) ? $orderStatusIngConfig['t15'] : $orderStatusIngConfig['t1'];
            }
            $commonSql = "";
            if (!$common_state) {
                $common_state = "0";
            }
            if (isset($common['t' . $common_state])) {
                $commonSql = $common['t' . $common_state];
            }
            $arr = ["doctor_id"=>$userInfo["user_id"]];
            $arr["order_state"] = '2';
            if($order_state) {
                $arr["order_state"] = $order_state;
            }
            $modules = Yii::$app->getModule('patient');
            $result = $modules->runAction('userorder/getordercountbycondition',
                ['class' => $class, 'condition' => $arr,'otherCondition' => $commonSql]);
            return UResponse::formatData("0", "成功", ['count' => (string)$result]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     *  医生收入报表
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionIncomestatement()
    {
        try {
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $order_type = '19';
            $common_state = Yii::$app->getParams->get("common_state");
            $orderStatusIngConfig = StateConfig::getOrderStatusIng(StateConfig::$nowOrderVersion);
            if ($order_type != "15") {
                $class = isset(StateConfig::$orderListMap['t' . $order_type]) ? StateConfig::$orderListMap['t' . $order_type] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t' . $order_type]) ? $orderStatusIngConfig['t' . $order_type] : $orderStatusIngConfig['t1'];
            } else {
                $class = isset(StateConfig::$orderListMap['t12']) ? StateConfig::$orderListMap['t12'] : StateConfig::$orderListMap['t1'];
                $common = isset($orderStatusIngConfig['t15']) ? $orderStatusIngConfig['t15'] : $orderStatusIngConfig['t1'];
            }
            $commonSql = "";
            if (!$common_state) {
                $common_state = "0";
            }
            if (isset($common['t' . $common_state])) {
                $commonSql = $common['t' . $common_state];
            }
            $arr["doctor_id"]   = $userInfo["user_id"];
            $arr["order_state"] = '3';
            $arr["is_reply"] = '2';
            $result = [
                'all'=>'0',
                'month'=>'0',
            ];
            //查询全部的
            $modules = Yii::$app->getModule('patient');
            $all= $modules->runAction('userorder/getDoctorIncomecondition',
                ['class' => $class, 'condition' => $arr,'otherCondition' => $commonSql]);
            if($all){
                $result['all'] = $all['option_money'];
            }
            //查当月
            $BeginDate= date('Y-m-01', strtotime(date("Y-m-d")));
            $EndDate = date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
            $commonSql = ' update_time >='.strtotime($BeginDate).' and update_time<='.strtotime($EndDate);
            $month = $modules->runAction('userorder/getDoctorIncomecondition',
                ['class' => $class, 'condition' => $arr,'otherCondition' => $commonSql]);
            if($month){
                $result['month'] = $month['option_money'];
            }
            return UResponse::formatData("0", "获取问诊成功", $result);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取问诊成功",[]);
        }
    }


    //医生回复问诊
    public function actionUpdateorderreply()
    {
        try {
            //订单Id
            $order_id = Yii::$app->getParams->get("order_id");
            $modules = Yii::$app->getModule("patient");
            $result = $modules->runAction('userorder/getInfoById',
                ['id' => $order_id]);
            if (!$result) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "订单号不存在");
            }
            if ($result['is_reply']=='2'){
                return UResponse::formatData("0", "修改订单状态成功", (object)[]);
            }
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $updateInfo = [
                "is_reply" => 2,
            ];
            $orderType = substr($order_id, 3, 3) - 100;
            $id = substr($order_id, 6);
            $class = StateConfig::$orderType['type' . $orderType]['orderTable'];

            $user = $class::findOne(['order_id' => $id]);
            if (!$user) {
                return [];
            }
            foreach ($updateInfo as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            $res = OrderOutput::formatData($result);
            return UResponse::formatData("0", "修改订单状态成功", (object)[]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['InvalidArgument'], "修改订单失败", (object)[]);
        }
    }


    /**
     *  用户提交意见反馈信息
     */
    public function actionAdduseropinion() {
        try {
            $userInfo = Yii::$app->runData->get("doctorUserInfo");
            $data['desc']   = Yii::$app->getParams->get("desc");
            $images = Yii::$app->getParams->get("images");
            if($images){
                $data['images'] = explode(',', $images);
                $data['images'] = json_encode($data['images']);
            }
            if(!$userInfo['user_id']){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "必填项不能为空");
            }
            if(!$images && !$data['desc']){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "必填项不能为空");
            }
            $data['user_id']   = $userInfo['user_id'];
            $data['user_name'] = '';
            $data['id'] = Yii::$app->DBID->getID('db.tuser_opinion');
            $command = Yii::$app->db;
            $command->createCommand()->insert('tuser_opinion', $data)->execute();
            return UResponse::formatData("0", '意见反馈成功', $data);
        } catch (Exception $e) {
            return UResponse::formatData("0", '意见反馈成功', (object)[]);
        }
    }


}
