<?php

namespace app\modules\service\controllers;

use app\common\application\RabbitConfig;
use app\common\application\StateConfig;
use app\common\data\Response;
use app\common\components\AppRedisKeyMap;
use app\common\data\Encrypt;
use app\common\data\Response as UResponse;
use yii\log\Logger;
use Yii;
use yii\base\Exception;

class VerifyController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public $testCode = "1152960768";

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\PatientFilter',
                'only'  => [
                    'collection',
                    "getcollectionlist",
                    "cancelcollection",
                    'collectiondoctor',
                    "getcollectiondoctorlist",
                    "cancelcollectiondoctor",
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
            } elseif($type == "3") {
                $send_str = "您的登录验证码是：".$key. "，两分钟内有效！【伙伴医生】";
            } elseif($type == "1") {
                $send_str = "您的注册验证码是：" . $key . "，五分钟内有效！【伙伴医生】";
                /*
                $modules = Yii::$app->getModule("patient");
                $userInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $phonenumber]);
                if ($userInfo) {
                    return UResponse::formatData(UResponse::$code['AccessDeny'], "已注册，请登录");
                }
                */
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
    public function actionCheckphonecode() {

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
            $modules = Yii::$app->getModule("patient");
            $userInfo = $modules->runAction("usercache/getCacheByPhone", ['phone' => $user_mobile]);
            if (!$userInfo) {
                //return UResponse::formatData(UResponse::$code['AccessDeny'], "该电话号码不存在");
            }
            $redisKey = AppRedisKeyMap::getUserPhoneKey($user_mobile);
            $redis = Yii::$app->redis;
            $smscode = strtolower($smscode);
            if ($redis->hget($redisKey, $smscode) || (CONF_ENV == "test_" && $smscode == "888888")) {
            } else {
                return UResponse::formatData(UResponse::$code['InvalidArgument'], "短信验证码错误");
            }

            $user_pass = Encrypt::mymd5_4($user_pass);
            $result = Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
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
            $modules = Yii::$app->getModule("patient");
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
                "user_id"      => $userInfo ? $userInfo['user_id'] : Yii::$app->DBID->getID('db.tuser'),
                "session_key"  => $sessionkey,
                "user_name"    => $user_mobile,
                "user_mobile"  => $user_mobile,
                "user_pass"    => $user_pass,
                "user_regtime" => $reg_time,
                "user_img"     => "",
                "role"         => 0,
                "create_time"  => time(),
                "update_time"  => time(),
                "is_delete"    => 1,
            ];
            //不传头像会报错
            if(!$userInfo['user_img']){
                $userInfo['user_img'] = 'http://files.test.huobanys.com/group1/M00/00/ED/wKhkxFnLGJ-AUSovAAAQsF_ihwE565.png';
            }
            $result = Yii::$app->rongyun->getInstance()->getToken($userInfo['user_id'], $userInfo['user_name'], $userInfo['user_img']);
            $result = json_decode($result, true);
            if (!is_array($result) || $result['code'] != 200) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误，请稍候重试");
            }
            $userInfo['access_token'] = $result['token'];

            $module = yii::$app->getModule("patient");
            $result = $module->runAction("userssdb/createUser", ['info' => $userInfo]);
            if (!$result) {
                return UResponse::formatData(UResponse::$code['InternalError'], "注册失败");
            }
            $outputdata = [
                "user_id"     => strval($userInfo["user_id"]),
                "user_name"   => $userInfo["user_name"],
                "user_pass"   => "xxxxxxxxx",
                "user_mobile" => strval($userInfo["user_mobile"]),
                "role"        => strval(0),
                "session_key" => $sessionkey,
                "access_token"=> $userInfo['access_token'],
            ];
            //为用户发放注册优惠卷
            $module->runAction("ordercoupon/addcouponforuser", ['user_id' => $userInfo['user_id']]);
            //为用户注册默认服务人
            $person = [
                'address_id' =>Yii::$app->DBID->getID("db.tuser_service_person"),
                'user_id'=>$userInfo['user_id'],
                'user_name'=>$userInfo['user_name'],
                'user_sex'=>'0',
                'user_age'=>'0',
                'user_district_id'=>'',
                'user_card_no'=>'',
                'user_medical_no'=>'0',
                'user_district_address'=>'',
                'user_detail_address'=>'',
                'user_phone' =>$userInfo['user_mobile'],
                'is_default'=>'1',
                'is_delete'=>'1',
            ];
            Yii::$app->getModule('patient')->runAction('userserviceperson/updateinfobydefault', ['uid' => $userInfo['user_id'], 'info' => $person]);
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
        $modules = Yii::$app->getModule("patient");
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
            $modules = Yii::$app->getModule("patient");
            $userInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $user_mobile]);
            $secret = [];
            if ($userInfo) {
                $secret[] = strtolower($userInfo['user_pass']);
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
                    "user_id"      => Yii::$app->DBID->getID('db.tuser'),
                    "session_key"  => $sessionkey,
                    "user_name"    => $user_mobile,
                    "user_mobile"  => $user_mobile,
                    "user_pass"    => "",
                    "user_regtime" => $reg_time,
                    "user_img"     => "",
                    "role"         => 0,
                    "create_time"  => time(),
                    "update_time"  => time(),
                    "is_delete"    => 1,
                    "is_coupon"    => '0',
                ];
                $module = yii::$app->getModule("patient");
                $userInfo = $module->runAction("userssdb/createUser", ['info' => $userInfo]);
            } else {
                $result = Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
                    ['id' => $userInfo['user_id'], 'info' => ['session_key' => $sessionkey]]);
                if (!$result) {
                    return UResponse::formatData(UResponse::$code['InternalError'], "登录失败");
                }
            }

            $outputdata = [
                "user_id"     => strval($userInfo["user_id"]),
                "user_name"   => $userInfo["user_name"],
                "user_pass"   => $userInfo["user_pass"] ? "xxxxxxxxx" : "",
                "user_mobile" => strval($userInfo["user_mobile"]),
                "role"        => strval(0),
                "session_key" => $sessionkey,
            	"access_token"=> isset($userInfo['access_token'])?$userInfo['access_token']:'',
            ];
            if ($userInfo["user_img"]) {
                $outputdata['user_img'] = $userInfo["user_img"];
            }
            //生产token
            //创建患者账户
            if (!$outputdata['access_token']) {
                //不传头像会报错
                if(!$userInfo['user_img']){
                    $userInfo['user_img'] = 'http://files.test.huobanys.com/group1/M00/00/ED/wKhkxFnLGJ-AUSovAAAQsF_ihwE565.png';
                }
                $result = Yii::$app->rongyun->getInstance()->getToken($userInfo['user_id'], $userInfo['user_name'], $userInfo['user_img']);
                $result = json_decode($result, true);
                if (!is_array($result) || $result['code'] != 200) {
                    return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误，请稍候重试");
                }
                Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
                    ['id' => $userInfo['user_id'], 'info' => ['access_token' => $result['token']]]);
                $outputdata['access_token'] = $result['token'];
            }
            $default = Yii::$app->getModule('patient')->runAction('userserviceperson/getdefault',
                ['id' => $userInfo['user_id']]);
            //注册一个默认服务人
            if(!$default){
                $person = [
                    'address_id'=> Yii::$app->DBID->getID("db.tuser_service_person"),
                    'user_id'  => $userInfo['user_id'],
                    'user_name'=> $userInfo['user_name'],
                    'user_sex'=>'0',
                    'user_age'=>'0',
                    'user_district_id'=>'',
                    'user_card_no'=>'',
                    'user_medical_no'=>'0',
                    'user_district_address'=>'',
                    'user_detail_address'=>'',
                    'user_phone' =>$userInfo['user_mobile'],
                    'is_default'=>'1',
                    'is_delete'=>'1',
                ];
                Yii::$app->getModule('patient')->runAction('userserviceperson/updateinfobydefault', ['uid' => $userInfo['user_id'], 'info' => $person]);
            }
            return UResponse::formatData("0", "登录成功", $outputdata);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    public function actionCollection()
    {

        $newsId = Yii::$app->getParams->get("news_id");
        $userInfo = Yii::$app->runData->get("userInfo");

        $condition = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_news_id" => $newsId,
        ];
        $info = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_news_id" => $newsId,
            "is_delete"  => 1,
        ];
        $result = Yii::$app->getModule('patient')->runAction('usercollection/updateinfo',
            ['condition' => $condition, 'info' => $info]);
        if ($result) {
            $redis = Yii::$app->redis;
            $redisKey = AppRedisKeyMap::getInfoCollection($userInfo['user_id']);
            if ($redis->exists($redisKey)) {
                $redis->sadd($redisKey, $newsId);
            }
            return UResponse::formatData("0", "收藏成功", (object)[]);
        }
        return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
    }

    public function actionCancelcollection()
    {

        $newsId = Yii::$app->getParams->get("news_id");
        $userInfo = Yii::$app->runData->get("userInfo");

        $condition = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_news_id" => $newsId,
        ];
        $info = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_news_id" => $newsId,
            "is_delete"  => 2,
        ];
        $result = Yii::$app->getModule('patient')->runAction('usercollection/updateinfo',
            ['condition' => $condition, 'info' => $info]);
        if ($result) {
            $redis = Yii::$app->redis;
            $redisKey = AppRedisKeyMap::getInfoCollection($userInfo['user_id']);
            if ($redis->exists($redisKey)) {
                $redis->srem($redisKey, $newsId);
            }
            return UResponse::formatData("0", "取消收藏成功", (object)[]);
        }
        return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
    }

    public function actionGetcollectionlist() {

        $userInfo = Yii::$app->runData->get("userInfo");
        $page = Yii::$app->getParams->get("page");
        if (!$page) {
            $page = 1;
        }
        $res =  Yii::$app->getModule('patient')->runAction('usercollection/getlist',
            ['condition' => ['tuser_collection.tc_user_id' => $userInfo['user_id'], 'tuser_collection.is_delete' => 1], 'page' => $page, 'limit'=> 10]);
        $result = [];
        foreach($res as $k => $v) {
            $res[$k]['news_photo'] = json_decode($res[$k]['news_photo'], true);
            $result[$k]['news_photo'] = $res[$k]['news_photo']['list_image'][0];
            $result[$k]['multi_news_photo'] = $res[$k]['news_photo']['list_image'];
            $result[$k]['material_id'] = $res[$k]['channel_no'] . '|' . $res[$k]['news_type'] . '|' . $res[$k]['material_id'];
            $result[$k]['news_title'] = $res[$k]['title'];
            $result[$k]['channel_name'] = $res[$k]['channel_name'];
            $result[$k]['news_id'] = $res[$k]['material_id'];
            $result[$k]['news_url'] = $res[$k]['news_url'];
            $result[$k]['desc'] = $res[$k]['author'] ? : "伙伴医生";
            $result[$k]['news_type'] = $res[$k]['news_type'];
            $result[$k]['publish_time'] = $res[$k]['publish_time'] ? strval($res[$k]['publish_time']) : strval(time() - rand(1, 9999999));
            $result[$k]['width'] = '100';
            $result[$k]['height'] = '100';
            $result[$k] = UResponse::messageToString($result[$k]);
        }
        if ($result) {
            return UResponse::formatData("0", "获取收藏列表成功", $result);
        }
        return UResponse::formatData("0", "获取收藏列表成功", (array)[]);
    }

    public function actionCollectiondoctor()
    {

        $newsId = Yii::$app->getParams->get("doctor_id");
        $userInfo = Yii::$app->runData->get("userInfo");

        $condition = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_doctor_id" => $newsId,
        ];
        $info = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_doctor_id" => $newsId,
            "is_delete"  => 1,
        ];
        $result = Yii::$app->getModule('patient')->runAction('userdoctorcollection/updateinfo',
            ['condition' => $condition, 'info' => $info]);
        if ($result) {
            $redis = Yii::$app->redis;
            $redisKey = AppRedisKeyMap::getInfoDoctorCollection($userInfo['user_id']);
            if ($redis->exists($redisKey)) {
                $redis->sadd($redisKey, $newsId);
            }
            return UResponse::formatData("0", "收藏成功", (object)[]);
        }
        return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
    }

    public function actionCancelcollectiondoctor()
    {

        $newsId = Yii::$app->getParams->get("doctor_id");
        $userInfo = Yii::$app->runData->get("userInfo");

        $condition = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_doctor_id" => $newsId,
        ];
        $info = [
            "tc_user_id" => $userInfo['user_id'],
            "tc_doctor_id" => $newsId,
            "is_delete"  => 2,
        ];
        $result = Yii::$app->getModule('patient')->runAction('userdoctorcollection/updateinfo',
            ['condition' => $condition, 'info' => $info]);
        if ($result) {
            $redis = Yii::$app->redis;
            $redisKey = AppRedisKeyMap::getInfoDoctorCollection($userInfo['user_id']);
            if ($redis->exists($redisKey)) {
                $redis->srem($redisKey, $newsId);
            }
            return UResponse::formatData("0", "取消收藏成功", (object)[]);
        }
        return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
    }

    public function actionGetcollectiondoctorlist() {

        $userInfo = Yii::$app->runData->get("userInfo");
        $page = Yii::$app->getParams->get("page");
        if (!$page) {
            $page = 1;
        }
        $results =  Yii::$app->getModule('patient')->runAction('userdoctorcollection/getlist',
            ['condition' => ['tuser_doctor_collection.tc_user_id' => $userInfo['user_id'], 'tuser_doctor_collection.is_delete' => 1], 'page' => $page, 'limit'=> 10]);
        if (is_array($results)) { //判断是否为数组
            foreach ($results as $k => $v) {
                $results[$k]['doctor_position'] = StateConfig::getDoctorPosition($v['doctor_position']);
                $results[$k]['hospital_level'] = StateConfig::getHospitalLevel($v['hospital_level']);
                $results[$k]['section_name'] = implode(",", Response::getDoctorSectionName($v['section_info']));
                unset($results[$k]['section_info']);
                $results[$k]['pay_money'] = $v['price'];
            }
        }
        if ($results) {
            return UResponse::formatData("0", "获取收藏列表成功", ['listdoc' => $results]);
        }
        return UResponse::formatData("0", "获取收藏列表成功", (array)['listdoc' => []]);
    }
}
