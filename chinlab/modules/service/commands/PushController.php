<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 2:34
 */
namespace app\modules\service\commands;

use app\common\components\AppRedisKeyMap;
use app\common\controller\Controller;
use app\modules\service\models\SendSMS;
use app\models\PushModel;
use app\common\application\StateConfig;
use yii\log\Logger;
use Yii;

class PushController extends Controller
{

    public function actionOrderpush($info)
    {
    	try {
    		if (!$info || !is_array($info) || !isset($info['user_id'])
    			|| !isset($info['content'])|| !isset($info['title'])|| !isset($info['body'])) {
    			return [1];
    		}
    		 $uid     = $info['user_id'];
    	     $content = $info['content'];
    		 $title   = $info['title'];
    		 $body    = $info['body'];
    		 $push    = Yii::$app->getui;
    		 rawurlencode($body);
    		 rawurlencode($title);
    		 rawurlencode($content);
    		 //$push->setCid('477651eebc13be269a59d8b84eb49eb8');
    		 $push->setAlias($uid);
    		 $push->setContent($content);
    		 $push->setTitle($title);
    		 $push->setBody($body);
    		 $res =  $push->pushMessageToSingle();
    		 echo json_encode($res,256);
    		 return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
    
    public function actionSmspush($info)
    {
    	try {
    		echo json_encode($info,JSON_UNESCAPED_UNICODE);
    		if (!$info || !is_array($info) || !isset($info['order_phone'])
    				||!isset($info['msg']) 
    			    ||!isset($info['order_type'])) {
    			return [1];
    		}
    		 PushModel::sendSms($info['order_phone'],$info['msg'],$info['order_type']);
    		return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
    /**
     *  用户端订单临近过期短信发送
     */
    public function actionOrderexpirepush($info)
    {
    	try {
    		echo json_encode($info,JSON_UNESCAPED_UNICODE);
    		if (!$info || !is_array($info) || !isset($info['order_id'])
    				|| !isset($info['order_phone'])){
    			return [1];
    		}

    		$redis = Yii::$app->redis;
    		$redisKey = AppRedisKeyMap::getOrderExpire($info['order_id']);
    		if ($redis->incr($redisKey) > 1) {
                return [1];
            }
            $redis->expire($redisKey, 86400);
    		PushModel::sendSms($info['order_phone'],'',PushModel::IS_EXPIRE);
    		return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
    /**
     *  运营人员短信发送
     */
    public function actionOrderpaypush($info)
    {
    	try {
    		echo json_encode($info,JSON_UNESCAPED_UNICODE);
    		if (!$info || !is_array($info) || !isset($info['order_id']) || !isset($info['order_phone'])
    				|| !isset($info['order_name']) || !isset($info['order_state'])
    				|| !isset($info['order_type']) || !isset($info['order_update_time'])){
    			return [1];
    		}
    		if($info['order_state'] == '99' || !isset(StateConfig::$orderType['type'.$info['order_type']])){
    			return [1];
    		}
    		$orderType  = StateConfig::$orderType['type'.$info['order_type']];
    		$orderState = StateConfig::getOrderStatus($info['order_version'])["ordertype" . $info['order_type']];
    		$state      = $info['order_state']+1;
    		$orderTypeName  = $orderType['name'];
    		$orderStateName = $orderState['type' . $state]['name'];
    		$phone   = ['18515422930'];
    		$uid = 'FLRJ03356';
    		$passwd = '123456';
    		$message = '您有一条'.$orderTypeName.','.$orderStateName.'订单需要处理，请尽快与用户核实处理；';
    		$message.= '用户名:'.$info['order_name'].';';
    		$message.= '订单时间：'.$info['order_update_time'].'，订单号：'.$info['order_id'].'，联系方式:'.$info['order_phone'].'。';
    		$message = rawurlencode($message);
    		foreach ($phone as $value){
    			$url = "http://api2.esoftsms.com/sdk/BatchSend.aspx?CorpID={$uid}&Pwd={$passwd}&Mobile={$value}&Content={$message}&Cell=&SendTime=";
    			$response = Yii::$app->httpClient->getInstance()->setUrl($url)->send();
    			echo $response->getContent() . PHP_EOL;
    		}
    		return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
    
}