<?php

namespace app\modules\patient\controllers;

use app\common\application\CardConfig;
use app\common\components\AppRedisKeyMap;
use app\modules\patient\models\CardInfo;
use app\modules\patient\models\CardInfoSecret;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class CardinfoController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetbyid($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = CardInfo::findOne($id);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * 获取卡片详情
     * */
    public function actionGetdetail($id) {

        $map = [
            "58" => "orderRegistered.html",
            "60" => "highendMedical.html",
            "61" => "readReport.html",
            "62" => "healthRecords.html",
            "63" => "onlineTriage.html",
            "64" => "accompany.html",
            "65" => "forMedical.html",
            "66" => "insurance.html",
            "67" => "overseaSecondOpinion.html",
            "68" => "orderReferral.html",
            "69" => "onlineConsult.html",
            "70" => "highendMedical.html",
        ];

        try {
            if (!$id) {
                return [];
            }
            $customer = CardInfo::findOne($id);
            if (!$customer) {
                return [];
            }
            $result = $customer->toArray();
            $result['goods_service'] = json_decode($result['goods_service'], true);
            foreach($result["goods_service"] as $k => $v) {
                $result["goods_service"][$k]['name'] = CardConfig::$service["t".$v['id']]['name'];
                $result["goods_service"][$k]['index'] = CardConfig::$service["t".$v['id']]['index'];
                $result['goods_service'][$k]['category_type'] = strval(CardConfig::$service["t".$v['id']]['type']);
                if (!isset($result["goods_service"][$k]["has"])) {
                    $result["goods_service"][$k]["has"] = "0";
                } else {
                    $result["goods_service"][$k]["has"] = strval($result["goods_service"][$k]["has"]);
                }
                $result["goods_service"][$k]["card_no"] = $result["card_no"];
                $result["goods_service"][$k]["count"] = strval($v["count"]);
                $result["goods_service"][$k]["serviceDesUrl"] = isset($map[$v['id']]) ? Yii::$app->runData->baseUrl.'/card/'.$map[$v['id']] : "";
                $result["goods_service"][$k]["desc"] = str_replace("{count}", $v["count"], CardConfig::$service["t".$v['id']]['desc'][0]);
            }
            usort($result["goods_service"], function($a, $b) {
                if ($a['index'] > $b['index']) {
                    return true;
                }
                return false;
            });
            foreach($result["goods_service"] as $k => $v) {
                unset($result["goods_service"][$k]['index']);
            }
            return $result;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * 获取用户卡片列表
     * @param $uid
     */
    public function actionGetlist($uid, $page, $limit) {

        if (!$uid) {
            return [];
        }
        try {
            return CardInfo::find()
                ->where("(apply_user_id = '$uid' and active_status = 1 and active_type = 0) or (apply_user_id = '$uid' and active_status = 0 )")
                ->limit($limit)->offset(($page - 1) * $limit)->orderBy('create_time desc')
                ->asArray()->all();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    /**
     * 创建卡片
     */
    public function actionCreatecard($orderInfo)
    {
        try {
            $cardInfo = Yii::$app->DBID->getNewCardID($orderInfo['goods_type'], $orderInfo['now_price']);
            $cardInsert = [
                "card_no"          => Yii::$app->DBID->getID("db.card_info"),
                "card_alias_no"    => $cardInfo['id'],
                "active_user_type" => CardConfig::ACTIVE_USER_NORMAL,
                "active_status"    => CardConfig::SECRET_NO_ACTIVE,
            ];
            $cardSecret = [
                "secret_id"       => $cardInsert["card_no"],
                "secret_alias_no" => $cardInfo['id'],
                "secret_buyer_id" => $orderInfo["user_id"],
                "security_code"   => $cardInfo["secret"],
            ];
            $orderDetailMap = [
                "goods_id"           => "goods_id",
                "goods_name"         => "goods_name",
                "goods_small_image"  => "goods_small_image",
                "service_user_limit" => "goods_service_limit",
                "goods_service"      => "goods_service",
                "active_user_id"     => "user_id",
                "apply_user_id"     => "user_id",
                "active_user_name"   => "user_name",
                "apply_user_name"   => "user_name",
                "order_id"          => "order_id",
                "active_expire_time" => "goods_expire_time",
            ];
            foreach ($orderDetailMap as $k => $v) {
                $cardInsert[$k] = $orderInfo[$v];
            }
            $result = $this->actionCreateCardInfo($cardInsert);
            if (!$result) {
                return [];
            }
            $result = $this->actionCreateCardSecretInfo($cardSecret);
            if (!$result) {
                return [];
            }
            return [1];
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * 创建卡密
     */
    public function actionCreateCardSecretInfo($info)
    {

        try {
            $user = new CardInfoSecret();
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    /**
     * 创建卡片
     */
    public function actionCreateCardInfo($info)
    {

        try {
            $user = new CardInfo();
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionUpdateinfo($id = "",  $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }
            $user = CardInfo::findOne($id);
            if (!$user) {
                return [];
            }
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    /**
     * 获取卡密
     */
    public function getCardIdSecret()
    {

        $redis = Yii::$app->redis;
        $redisTime = $redis->executeCommand('time');
        if ($redisTime[1] < 100000) {
            $pl = 6 - strlen(strval($redisTime[1]));
            for ($i = 0; $i < $pl; $i++) {
                $redisTime[1] = "0" . $redisTime[1];
            }
        }
        $rand = rand(10000, 99999);
        $secretTime = $redisTime[0] . $rand . $redisTime[1];
        $time = $redisTime[0] . $redisTime[1];
        $redisKey = AppRedisKeyMap::getCardIds($time);
        $idmax = $redis->incr($redisKey);
        $redis->expire($redisKey, 600);
        if ($idmax >= 10000) {
            sleep(1);
            return $this->getCardIdSecret();
        }
        $idmax = $idmax + date("is", $redisTime[0]);
        $length = strlen(strval($idmax));
        if ($length < 4) {
            $pl = 4 - $length;
            for ($i = 0; $i < $pl; $i++) {
                $idmax = $idmax . "0";
            }
        }
        return [
            "id"     => $time . $idmax,
            "secret" => md5($secretTime . $idmax),
        ];
    }
}
