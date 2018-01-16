<?php

namespace app\modules\service\controllers;

use app\common\application\CardConfig;
use app\common\application\RabbitConfig;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response as UResponse;
use app\modules\patient\models\CardInfo;
use app\modules\patient\models\CardOrderService;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class CardController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\PatientFilter',
                'only' => ['getlist', 'activecard', 'activesendphone', 'getdetail', 'selectservice', 'sevicelist', 'servicedetail', 'gettestitem'],
            ],
            [
                'class' => 'app\common\filters\RepeatFilter',
                'only' => ['selectservice', 'activecard', 'activesendphone'],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取体检选择详情
     * */
    public function actionGettestitem()
    {

        $id = Yii::$app->getParams->get("card_id");
        $serviceType = Yii::$app->getParams->get("service_type");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("cardinfo/getdetail", ['id' => $id]);
        if (!$result) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "未查询到该卡片信息");
        }
        $items = [];
        foreach ($result["goods_service"] as $k => $v) {
            if ($v['id'] == $serviceType && isset($v['items'])) {
                $items = $v["items"];
            }
        }
        if (!$items && CONF_ENV == "test_") {
            $items = [1, 2, 3, 4, 5, 6, 7, 8];
        }
        $res = ['boy' => [], 'girl' => []];
        foreach ($items as $k => $v) {
            $config = isset(CardConfig::$testInfo["s" . $v]) ? CardConfig::$testInfo["s" . $v] : CardConfig::$testInfo["s1"];
            $config["detail_url"] = Yii::$app->runData->baseUrl . "/userApi/goods_testpage_" . $v . "_1.html";
            if ($config['sex'] == 1) {
                $res['boy'][] = $config;
            } else {
                $res['girl'][] = $config;
            }
        }

        $tmpInfo = CardConfig::$testNewInfo;
        $tmpInfo['s1']['detail_url'] = "";
        $tmpInfo['s2']['detail_url'] = "";
        if ($serviceType == "60" || $serviceType == "70") {
            $res = [
                'boy' => [
                    $tmpInfo['s1'],
                ],
                'girl' => [
                    $tmpInfo['s2'],
                ],
            ];
            //检查时间 判断是否为一年限制12次的卡片
            if ($result['create_time'] > strtotime("2017-08-27 00:00:00")
                && $result['create_time'] < strtotime("2017-09-03 00:00:00")
            ) {
                $res = [
                    'boy' => [
                        [
                            "name" => "高端男性体检",
                            "val" => "1",
                            "sex" => "1",
                            "desc" => "高端男性体检",
                            "detail_url" => "",
                        ]
                    ],
                    'girl' => [
                        [
                            "name" => "高端女性体检",
                            "val" => "2",
                            "sex" => "2",
                            "desc" => "高端女性体检",
                            "detail_url" => "",
                        ]
                    ],
                ];
            }
        }
        return UResponse::formatData("0", "获取详情成功", Uresponse::messageToString($res));
    }

    /**
     * 获取新体检选择详情
     * */
    public function actionGetnewtestitem()
    {

        $res = array_values(CardConfig::$testNewInfo);
        return UResponse::formatData("0", "获取详情成功", Uresponse::messageToString($res));
    }


    public function actionGetserviceconfig()
    {

        $serviceType = Yii::$app->getParams->get("service_type");
        $res = [
            "test_info" => array_values(CardConfig::$testNewInfo),
            "test_area" => CardConfig::$testArea,
            "register_area" => CardConfig::$registerArea,
            "doctor_level" => CardConfig::$doctorLevelMoney,
        ];
        //$modules = Yii::$app->getModule('doctor');
        foreach ($res['register_area'] as $k => $v) {
            /*
            $cityInfo = $modules->runAction('district/findByCondition', ['condition' => ['parentid'=>$v['parent_id']]]);
            $res['register_area'][$k]['child'] = [];
            foreach($cityInfo as $vv) {
                $res['register_area'][$k]['child'][] = $vv['district_shortname'];
            }
            */
            unset($res['register_area'][$k]['parent_id']);
        }

        if($serviceType == "70") {
            $res['test_info'] = array_values([
                "s1" => [
                    "name" => "高端男性体检",
                    "val" => "1",
                    "sex" => "1",
                    "desc" => "高端男性体检",
                ],
                "s2" => [
                    "name" => "高端女性体检",
                    "val" => "2",
                    "sex" => "2",
                    "desc" => "高端女性体检",
                ],
            ]);
            $res["test_area"] = [
                [
                    "name" => "美年大健康",
                    "area_info" => [ "北京市"],
                ],
            ];
        }

        return UResponse::formatData("0", "获取详情成功", Uresponse::messageToString($res));
    }

    /**
     * 获取卡片详情
     */
    public function actionGetdetail()
    {

        $id = Yii::$app->getParams->get("card_id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("cardinfo/getdetail", ['id' => $id]);
        foreach ($result["goods_service"] as $k => $v) {
            if (isset($v['items'])) {
                unset($result["goods_service"][$k]["items"]);
            }
        }
        return UResponse::formatData("0", "获取详情成功", Uresponse::messageToString($result));
    }

    /**
     * 选择服务
     */
    public function actionSelectservice()
    {

        $id = Yii::$app->getParams->get("card_id");
        $serviceType = Yii::$app->getParams->get("service_type");
        $personId = Yii::$app->getParams->get("address_id");
        $userInfo = Yii::$app->runData->get("userInfo");

        if (!$personId) {
            $personId = '10000';
        }

        //验证卡的服务次数
        $modules = Yii::$app->getModule("patient");
        $cardInfo = $modules->runAction("cardinfo/getdetail", ['id' => $id]);
        $servicePerson = $modules->runAction("userserviceperson/getbyid", ['id' => $personId]);
        if (!$servicePerson) {
            $servicePerson = $modules->runAction("cardpersionuser/findOneByCondition", ["condition" => ['card_no' => $id, 'persion_type' => 2]]);
        }
        if (!$servicePerson) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "未查询到该服务人信息");
        }
        $hasPerson = $modules->runAction("cardpersionuser/getlist", ['uid' => $userInfo['user_id'], 'card' => $id, 'page' => 1, 'limit' => 200]);
        if (count($hasPerson) >= $cardInfo['service_user_limit'] && !in_array($servicePerson['user_card_no'], $hasPerson)) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片的最大服务人数为" . $cardInfo['service_user_limit'] . "人");
        }
        if (!$cardInfo) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "未查询到该卡片信息");
        }

        //检查时间 判断是否为一年限制12次的卡片
        if ($serviceType == "69"
            && $cardInfo['create_time'] > strtotime("2017-08-27 00:00:00")
            && $cardInfo['create_time'] < strtotime("2017-09-03 00:00:00")
        ) {
            $serviceTimeInfo = CardOrderService::find()->select(['create_time'])->where(["goods_service_type"=>$serviceType, "card_no"=>$cardInfo['card_no']])->orderBy("create_time desc")->asArray()->one();
            if ($serviceTimeInfo && date("Ym", $serviceTimeInfo['create_time']) == date("Ym", time())) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "咨询服务每月只限使用一次");
            }
        }

        if ($cardInfo['apply_user_id'] != $userInfo['user_id']) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "你无权操作该卡片");
        }
        if ($cardInfo['active_type'] != CardConfig::ACTIVE_APPLY_OWN) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片你已为他人激活，你无权使用");
        }
        if ($cardInfo['active_status'] != CardConfig::SECRET_YES_ACTIVE) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片还未激活");
        }
        if ($cardInfo['active_time'] + $cardInfo['active_expire_time'] < time()) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片已过期");
        }
        foreach ($cardInfo['goods_service'] as $k => $v) {
            if ($v['id'] == $serviceType && $v['has'] >= $v['count']) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该服务已经超过该卡片最大值");
            } elseif ($v['id'] == $serviceType) {
                $cardInfo['goods_service'][$k]["has"] += 1;
            }
        }
        $categoryType = CardConfig::$service['t' . $serviceType]['type'];
        $otherInfo = [];
        foreach (CardConfig::$serviceExtra[$categoryType] as $k => $v) {
            if ($k == "order_file") {
                $photos = explode(",", Yii::$app->getParams->get($k));
                $otherInfo['order_file'] = is_array($photos) ? $photos : [];
            } else {
                $otherInfo[$k] = Yii::$app->getParams->get($k);
            }
        }
        $tmpMoney = 0;
        if (Yii::$app->getParams->get("doctor_level_id")) {
            $tmpMoney = CardConfig::getDoctorMoney(Yii::$app->getParams->get("doctor_level_id"));
        }

        //北京地区 需要支付
        if ($serviceType == "58" && strpos(Yii::$app->getParams->get("order_city_name"), "北京") !== false) {
            $serviceType = "59";
        }
        //补充解读报告和健康档案数据
        if ($serviceType == "61" || $serviceType == "62") {
            $otherInfo["retry_status"] = "0";
            //逗号隔开 json里套json mysql可能报错
            $otherInfo["retry_info"] = "";
            $otherInfo["is_supply"] = "0";
        }
        $insertData = [
            "card_order_id" => Yii::$app->DBID->getOrderId($userInfo['user_id'], $serviceType),
            "persion_user_id" => $personId,
            "card_no" => $id,
            "persion_add_type" => CardConfig::PERSON_ADD_TYPE_APP,
            "persion_type" => 1,
            "goods_service_type" => $serviceType,
            "goods_category_type" => CardConfig::$service['t' . $serviceType]['back_type'],
            "goods_service_name" => CardConfig::$service['t' . $serviceType]['name'],
            "service_status" => CardConfig::$service['t' . $serviceType]['status']['s1']['val'],
            "service_process_status" => CardConfig::$service['t' . $serviceType]['status']['s1']['process_status'],
            "user_other_info" => json_encode($otherInfo, JSON_UNESCAPED_UNICODE),
        ];
        $modules->runAction('ordertip/setOtherItem', ['id' => $insertData['card_order_id']]);
        $map = [
            "user_name",
            "user_sex",
            "user_card_no",
            "user_district_id",
            "user_district_address",
            "user_detail_address",
            "user_phone",
        ];
        foreach ($map as $k => $v) {
            $insertData[$v] = $servicePerson[$v];
            if (Yii::$app->getParams->get($v)) {
                $insertData[$v] = Yii::$app->getParams->get($v);
            }
        }
        $persionData = [
            'persion_user_id' => Yii::$app->DBID->getID("db.tuser_express_address"),
            'card_no' => $id,
            'persion_type' => CardConfig::PERSON_TYPE_APP,
        ];
        $personmap = [
            "user_name",
            "user_sex",
            "user_card_no",
            "user_district_id",
            "user_district_address",
            "user_detail_address",
            "user_phone",
        ];
        foreach ($personmap as $k => $v) {
            $persionData[$v] = $servicePerson[$v];
        }
        //同步数据用
        Yii::$app->runData->set("isCardToOrder", true);
        Yii::$app->runData->set("orderId", "");
        Yii::$app->db->transaction(function ($db) use ($modules, $persionData, $insertData, $id, $cardInfo) {
            $modules->runAction("cardpersionuser/create", ['info' => $persionData]);
            $modules->runAction("cardorderservice/create", ['info' => $insertData]);
            $modules->runAction("cardinfo/updateinfo", ["id" => $id, "info" => ['goods_service' => json_encode($cardInfo['goods_service'])]]);
        });
        return UResponse::formatData("0", "提交成功", (object)["card_order_id" => $insertData['card_order_id'], "order_id" => Yii::$app->runData->get("orderId"), "pay_money" => $tmpMoney]);
    }

    /**
     * 服务列表
     */
    public function actionServicelist()
    {

        $id = Yii::$app->getParams->get("card_id");
        $serviceType = Yii::$app->getParams->get("service_type");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("cardorderservice/getlist", ['card' => $id, 'service' => $serviceType]);
        return UResponse::formatData("0", "获取服务列表成功", $result);
    }

    /**
     * 获取服务详情
     * */
    public function actionServicedetail()
    {

        $id = Yii::$app->getParams->get("card_order_id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("cardorderservice/getdetail", ['card_order_no' => $id]);
        return UResponse::formatData("0", "获取服务列表成功", $result ? $result[0] : (object)[]);
    }

    /**
     * 获取卡片列表
     */
    public function actionGetlist()
    {
        try {
            $userInfo = Yii::$app->runData->get("userInfo");
            $page = Yii::$app->getParams->get('page');
            $limit = Yii::$app->getParams->get('limit');
            if (!$page) {
                $page = 1;
            }
            if (!$limit) {
                $limit = 10;
            }

            $modules = Yii::$app->getModule("patient");
            $result = $modules->runAction("cardinfo/getlist", ['uid' => $userInfo['user_id'], 'limit' => $limit, 'page' => $page]);
            return UResponse::formatData("0", "获取列表成功", $result);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return UResponse::formatData("0", "获取资料成功", (object)[]);
        }
    }

    /**
     * 卡片激活发送验证码
     */
    public function actionActivesendphone()
    {

        try {
            $userInfo = Yii::$app->runData->get("userInfo");
            //todo 增加并发限制 redis
            $phone = Yii::$app->getParams->get('phone');
            $card_no = Yii::$app->getParams->get('card_no');
            $card_alias_no = Yii::$app->getParams->get('card_alias_no');
            $modules = Yii::$app->getModule("patient");

            $cardSecretInfo = $modules->runAction("cardinfosecret/getbyid", ['id' => $card_no]);
            if (!$cardSecretInfo) {
                $cardSecretInfo = $modules->runAction("cardinfosecret/getbyalias", ['id' => $card_alias_no]);
            }


            //检查卡片是否存在
            if (!$cardSecretInfo) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片不存在，请重新输入卡号");
            }
            //不是系统生成的卡片用户
            if ($cardSecretInfo['secret_buyer_id'] != $userInfo['user_id'] && $cardSecretInfo['secret_buyer_id'] != '10000') {
                //return UResponse::formatData(UResponse::$code['AccessDeny'], "你不是该卡片的购买人，无法激活操作");
            }
            if ($cardSecretInfo['is_active'] != CardConfig::SECRET_NO_ACTIVE) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片已激活，请勿重复操作");
            }

            $card_no = $cardSecretInfo['secret_id'];

            //发送短信验证码
            $key = "";
            $pattern = '1234567890qwertyuiopasdfghjklzxcvbnm';
            for ($i = 0; $i < 6; $i++) {
                $key .= $pattern{mt_rand(0, 9)}; //生成php随机数
            }
            $send_str = $send_str = "您的注册验证码是：" . $key . "，五分钟内有效！【伙伴医生】";
            Yii::$app->queue->send([
                'info' => ['message' => $send_str, "telphone" => $phone],
            ], RabbitConfig::SMS_PHONE_CODE);

            $updateInfo = [
                "secret_phone" => $phone,
                "secret_phone_secret" => $key,
                "secret_phone_expire" => time() + 3600,
            ];
            $result = $modules->runAction("cardinfosecret/updateinfo", ['id' => $card_no, 'info' => $updateInfo]);
            return UResponse::formatData("0", "发送验证码成功", (object)[]);

        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return UResponse::formatData("0", "获取资料成功", (object)[]);
        }
    }

    /**
     * 通过手机号码激活卡片
     */
    public function actionActivecard()
    {

        try {
            //todo 增加并发限制 redis
            $card_no = Yii::$app->getParams->get('card_no');
            $card_alias_no = Yii::$app->getParams->get('card_alias_no');
            $secret = Yii::$app->getParams->get('secret');
            $userName = Yii::$app->getParams->get('user_name');
            $userCardNo = Yii::$app->getParams->get('user_card_no');
            $userInfo = Yii::$app->runData->get("userInfo");
            $modules = Yii::$app->getModule("patient");
            $cardSecretInfo = $modules->runAction("cardinfosecret/getbyid", ['id' => $card_no]);
            if (!$cardSecretInfo) {
                $cardSecretInfo = $modules->runAction("cardinfosecret/getbyalias", ['id' => $card_alias_no]);
            }
            if ($cardSecretInfo['secret_phone_secret'] != $secret) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "激活码错误");
            }
            if ($cardSecretInfo['secret_phone_expire'] < time()) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "激活码已过期");
            }
            if ($cardSecretInfo['secret_buyer_id'] != $userInfo['user_id'] && $cardSecretInfo['secret_buyer_id'] != '10000') {
                //return UResponse::formatData(UResponse::$code['AccessDeny'], "你不是该卡片的购买人，无法激活操作");
            }
            if ($cardSecretInfo['is_active'] != CardConfig::SECRET_NO_ACTIVE) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "该卡片已激活，请勿重复操作");
            }
            $card_no = $cardSecretInfo['secret_id'];
            $tmp_card_no = $card_no;
            $cardInfo = $modules->runAction("cardinfo/getbyid", ['id' => $card_no]);
            unset($cardInfo['create_time'], $cardInfo['update_time'], $cardInfo['is_delete']);
            unset($cardSecretInfo['create_time'], $cardSecretInfo['update_time'], $cardSecretInfo['is_delete']);

            //创建用户
            $cardUpdateInfo = $cardInfo;
            $cardSecretUpdateInfo = $cardSecretInfo;
            $nowTime = time();
            $msg = "恭喜你，激活卡片成功";
            if ($userInfo['user_mobile'] != $cardSecretInfo['secret_phone']) {
                $msg = "恭喜你，给手机号码为".$cardSecretInfo['secret_phone']."的用户激活卡片成功";
                $cardSecretUpdateInfo['is_active'] = CardConfig::SECRET_YES_ACTIVE;
                $cardSecretUpdateInfo['active_type'] = CardConfig::SECRET_ACTIVE_PHONE;

                $cardUpdateInfo['apply_user_id'] = $userInfo['user_id'];
                $cardUpdateInfo['apply_user_name'] = $userInfo['user_name'];
                $cardUpdateInfo['phone_no'] = $userInfo['user_mobile'];
                $cardUpdateInfo['active_time'] = $nowTime;
                $cardUpdateInfo['active_type'] = CardConfig::ACTIVE_APPLY_OTHER;
                $cardUpdateInfo['active_status'] = CardConfig::SECRET_YES_ACTIVE;

                $cardOtherInfo = $cardUpdateInfo;
                $cardOtherInfo['card_no'] = Yii::$app->DBID->getID("db.card_info");
                $tmp_card_no = $cardOtherInfo['card_no'];
                $isUserInfo = $modules->runAction("user/getInfoByPhone", ['phone' => $cardSecretInfo['secret_phone']]);
                if ($isUserInfo) {
                    $cardOtherInfo['apply_user_id'] = $isUserInfo['user_id'];
                    $cardOtherInfo['apply_user_name'] = $isUserInfo['user_name'];
                    $cardOtherInfo['phone_no'] = $isUserInfo['user_mobile'];
                    $cardOtherInfo['active_time'] = $nowTime;
                    $cardOtherInfo['active_type'] = CardConfig::ACTIVE_APPLY_OWN;
                    $cardOtherInfo['active_status'] = CardConfig::SECRET_YES_ACTIVE;
                    //创建用户
                } else {
                    $insertUserInfo = [
                        "user_id" => Yii::$app->DBID->getID('db.tuser'),
                        "session_key" => "",
                        "user_name" => $cardSecretInfo['secret_phone'],
                        "user_mobile" => $cardSecretInfo['secret_phone'],
                        "user_pass" => "123456",
                        "user_regtime" => date("Y-m-d H:i:s"),
                        "user_img" => "",
                        "role" => 0,
                        "create_time" => time(),
                        "update_time" => time(),
                        "is_delete" => 1,
                    ];
                    $result = $modules->runAction("userssdb/createUser", ['info' => $insertUserInfo]);
                    if (!$result) {
                        return UResponse::formatData(UResponse::$code['InternalError'], "系统错误，请稍后重试");
                    }
                    $cardOtherInfo['apply_user_id'] = $result['user_id'];
                    $cardOtherInfo['apply_user_name'] = $result['user_name'];
                    $cardOtherInfo['phone_no'] = $result['user_mobile'];
                    $cardOtherInfo['active_time'] = $nowTime;
                    $cardOtherInfo['active_type'] = CardConfig::ACTIVE_APPLY_OWN;
                }
            } else {

                $cardOtherInfo = [];
                $cardSecretUpdateInfo['is_active'] = CardConfig::SECRET_YES_ACTIVE;
                $cardSecretUpdateInfo['active_type'] = CardConfig::SECRET_ACTIVE_PHONE;

                $cardUpdateInfo['apply_user_id'] = $userInfo['user_id'];
                $cardUpdateInfo['apply_user_name'] = $userInfo['user_name'];
                $cardUpdateInfo['phone_no'] = $userInfo['user_mobile'];
                $cardUpdateInfo['active_time'] = $nowTime;
                $cardUpdateInfo['active_type'] = CardConfig::ACTIVE_APPLY_OWN;
                $cardUpdateInfo['active_status'] = CardConfig::SECRET_YES_ACTIVE;
            }
            $persionData = [
                'persion_user_id' => Yii::$app->DBID->getID("db.tuser_express_address"),
                'card_no' => $tmp_card_no,
                'persion_type' => CardConfig::PERSON_TYPE_BACKEND,
                "user_name" => $userName,
                "user_sex" => "1",
                "user_card_no" => $userCardNo,
                "user_district_id" => "110000",
                "user_district_address" => "北京",
                "user_detail_address" => "未知",
                "user_phone" => $cardSecretInfo["secret_phone"],
                "is_delete" => 2,
            ];
            Yii::$app->db->transaction(function ($db) use ($modules, $card_no, $cardSecretUpdateInfo, $cardUpdateInfo, $cardOtherInfo, $persionData) {
                $modules->runAction("cardinfosecret/updateinfo", ['id' => $card_no, 'info' => $cardSecretUpdateInfo]);
                $modules->runAction("cardinfo/updateinfo", ['id' => $card_no, 'info' => $cardUpdateInfo]);
                $modules->runAction("cardpersionuser/create", ['info' => $persionData]);
                if ($cardOtherInfo) {
                    $modules->runAction("cardinfo/createCardInfo", ['id' => $card_no, 'info' => $cardOtherInfo]);
                }
            });
            return UResponse::formatData("0", $msg, (object)[]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return UResponse::formatData("0", "激活卡片失败", (object)[]);
        }
    }
}
