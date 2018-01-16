<?php

namespace app\modules\patient\controllers;
use app\common\application\StateConfig;
use app\common\components\AppRedisKeyMap;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class UserordertipController extends \app\common\controller\Controller
{

    private $map = [
        "101" => ["手术订单", "surgery"],
        "109" => ["预约诊疗", "treat"],
        "112" => ["vip服务", "accompany"],
        "110" => ["海外就医", "overseas"],
        "113" => ["慈善公益", "commonweal"],
        "115" => ["健康卡购买", "card"],
        "116" => ["集团客户", "group_customer"],
        "117" => ["解读报告", "report"],
    ];

    private function getOtherCommonItem($userId) {

        $redisMap = [];
        $config = [];
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey($userId);
        $redisMap[] = $redisKey;
        $redisMap[] = "test";
        foreach(StateConfig::$orderListMap as $class) {
            $result = $class::find()->select(['order_id', 'order_version','order_type', 'order_state'])
                ->where("user_id = '{$userId}' and is_delete = 1 and order_state != 99")->asArray()->all();
            foreach($result as $k => $v) {
                if (!isset($config[$v['order_version']])) {
                    $config[$v['order_version']] = StateConfig::getOrderStatus($v['order_version']);
                }
                $nextStatus = $v['order_state'] + 1;
                if (isset($config[$v['order_version']]['ordertype'.$v['order_type']]['type'.$nextStatus])) {
                    $prefix = StateConfig::$orderType["type".$v['order_type']]['list'];
                    if ($v['order_type'] == "15") {
                        $prefix = "115";
                    }
                    $redisMap[] = $prefix.'.'.$v['order_id'];
                }
            }
        }

        $redis->executeCommand('SADD', $redisMap);
        $redis->expire($redisKey, 86400);
    }

    public function actionUpdateItemByState($id, $order_state, $order_version, $userId) {

        $order_type = substr($id, 3, 3) - 100;
        $config = StateConfig::getOrderStatus($order_version);
        $nextStatus = $order_state + 1;
        if (isset($config['ordertype'.$order_type]['type'.$nextStatus])) {
            $this->setCommonItem($id, $userId);
        } else {
            $this->delCommonItem($id, $userId);
        }
    }

    private function setCommonItem($id, $userId) {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey($userId);
        if (!$redis->exists($redisKey)) {
            $this->getOtherCommonItem($userId);
        }
        $prefix = substr($id, 0, 3);
        $orderType = substr($id, 3, 3) - 100;
        if ($orderType == "15") {
            $prefix = "115";
        }
        if (!array_key_exists($prefix, $this->map)) {
            return [];
        }
        try {
            $redis->SADD($redisKey, $prefix . "." . substr($id, 6));
            return [1];
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    private function delCommonItem($id, $userId) {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey($userId);
        if (!$redis->exists($redisKey)) {
            $this->getOtherCommonItem($userId);
        }
        $prefix = substr($id, 0, 3);
        $orderType = substr($id, 3, 3) - 100;
        if ($orderType == "15") {
            $prefix = "115";
        }
        if (!array_key_exists($prefix, $this->map)) {
            return [];
        }
        try {
            $redis->SREM($redisKey, $prefix . "." . substr($id, 6));
            return [1];
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    public function actionGetStaticString($userId) {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey($userId);
        if (!$redis->exists($redisKey)) {
            $this->getOtherCommonItem($userId);
        }
        try {
            $result = (array)$redis->SMEMBERS($redisKey);
            $mapRes = [];
            foreach($result as $k => $v) {
                if ($v == "test" || strpos($v, ".") === false) {
                    continue;
                }
                $tmpInfo = explode(".", $v);
                if (!isset($mapRes[$tmpInfo[0]])) {
                    $mapRes[$tmpInfo[0]] = 1;
                } else {
                    $mapRes[$tmpInfo[0]] += 1;
                }
            }
            foreach($this->map as $k => $v) {
                if (!isset($mapRes[$k])) {
                    $mapRes[$k] = '0';
                } else {
                    $mapRes[$k] = strval($mapRes[$k]);
                }
            }
            $result = [];
            foreach($mapRes as $k => $v) {
                if (isset($this->map[$k])) {
                    $result[$this->map[$k][1]] = $v;
                }
            }
            return $result;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
}
