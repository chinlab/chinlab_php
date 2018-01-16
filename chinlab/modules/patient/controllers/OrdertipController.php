<?php

namespace app\modules\patient\controllers;
use app\common\components\AppRedisKeyMap;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class OrdertipController extends \app\common\controller\Controller
{

    private $map = [
        "101" => "手术订单",
        "109" => "预约诊疗",
        "112" => "vip服务",
        "110" => "海外就医",
        "113" => "慈善公益",
        "117" => "解读报告",
    	'119' => "在线问诊",
        "999" => "会员卡服务",
    ];

    public function actionSetCommonItem($id) {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey();

        $prefix = substr($id, 0, 3);
        if (!array_key_exists($prefix, $this->map)) {
            return [];
        }
        $orderType = substr($id, 3, 3);
        if ($orderType == "118") {
            return [];
        }
        try {
            $redis->SADD($redisKey, $prefix . "." . $id);
            return [1];
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionDelCommonItem($id) {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey();

        $prefix = substr($id, 0, 3);
        if (!array_key_exists($prefix, $this->map)) {
            return [];
        }
        $orderType = substr($id, 3, 3);
        if ($orderType == "118") {
            return [];
        }
        try {
            $redis->SREM($redisKey, $prefix . "." . $id);
            return [1];
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionSetOtherItem($id) {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey();

        try {
            $redis->SADD($redisKey, "999." . $id);
            return [1];
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionDelOtherItem($id) {

        /*
        if (strlen($id) > 20) {
            $id = substr($id, 6);
        }
        */
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey();

        try {
            $redis->SREM($redisKey, "999." . $id);
            return [1];
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    public function actionGetStaticString() {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getOrderTipKey();

        try {
            $result = (array)$redis->SMEMBERS($redisKey);
            $res = ["list" => [], "desc" => ""];
            $mapRes = [];
            foreach($result as $k => $v) {
                $tmpInfo = explode(".", $v);
                if (!isset($mapRes[$tmpInfo[0]])) {
                    $mapRes[$tmpInfo[0]] = 1;
                } else {
                    $mapRes[$tmpInfo[0]] += 1;
                }
            }
            foreach($this->map as $k => $v) {
            	if ($v=='119'){
            		continue;
            	}
                $res["list"][$k] = [
                    "desc" => $v,
                    "count" => 0,
                ];
            }
            $res['desc'] .= '<div style="width: 100%; text-align: center; margin-top: 20px">';
            foreach($mapRes as $k => $v) {
                $res["desc"] .= '<p>您有<span style="color: red">'.$v.'条</span>'.$this->map[$k].'订单等待处理！</p>';
                $res["list"][$k]["count"] = $v;
            }
            $res['desc'] .= '</div>';
            return $res;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
    
    //获取医生订单消息提醒数量
    public function actionGetDoctorTips($did,$type='119') {
    	$redis = Yii::$app->redis;
    	try {
    		$redisKey = AppRedisKeyMap::getOrderTipKey($did);
    		$result = (array)$redis->SMEMBERS($redisKey);
    		$res = ["list" => [], "desc" => ""];
    		$mapRes = [];
    		foreach($result as $k => $v) {
    			$tmpInfo = explode(".", $v);
    			if (!isset($mapRes[$tmpInfo[0]])) {
    				$mapRes[$tmpInfo[0]] = 1;
    			} else {
    				$mapRes[$tmpInfo[0]] += 1;
    			}
    		}
    		if(isset($mapRes[$type])){
    			return $mapRes[$type];
    		}
    		return 0;
    	} catch(Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
    
    //设置医生订单消息提醒数量
    public function actionSetDoctorTips($did,$id) {
    
    	$redis = Yii::$app->redis;
    	$redisKey = AppRedisKeyMap::getOrderTipKey($did);
    
    	$prefix = substr($id, 0, 3);
    	if (!array_key_exists($prefix, $this->map)) {
    		return [];
    	}
    	$orderType = substr($id, 3, 3);
    	if ($orderType == "118") {
    		return [];
    	}
    	try {
    		$redis->SADD($redisKey, $prefix . "." . $id);
    		return [1];
    	} catch(Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
    
    //删除医生订单消息提醒数量
    public function actionDelDoctorTips($did,$id) {
    
    	$redis = Yii::$app->redis;
    	$redisKey = AppRedisKeyMap::getOrderTipKey($did);
    
    	$prefix = substr($id, 0, 3);
    	if (!array_key_exists($prefix, $this->map)) {
    		return [];
    	}
    	$orderType = substr($id, 3, 3);
    	if ($orderType == "118") {
    		return [];
    	}
    	try {
    		$redis->SREM($redisKey, $prefix . "." . $id);
    		return [1];
    	} catch(Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
    
    
    
}
