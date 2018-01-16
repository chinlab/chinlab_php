<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 2:34
 */
namespace app\modules\service\commands;

use app\common\application\RabbitConfig;
use app\common\components\AppRedisKeyMap;
use app\common\controller\Controller;
use Yii;
use yii\log\Logger;

class CardController extends Controller
{

    public function actionCreatecard($info) {

        $modules = Yii::$app->getModule("patient");
        $result =$modules->runAction('cardinfo/createcard',
            ['orderInfo' => $info['orderInfo']]);
        if ($result) {
            return [1];
        }
        return [];
    }
    
    //重建商品列表及详情缓存
    public function actionGoodscache($info = []) {
        try {

        	$listKey   = AppRedisKeyMap::getGoodsListKey('goods');
        	$redis = Yii::$app->redis;
        	if($redis->exists($listKey)){
        		//删除列表缓存
        		$redis->del($listKey);
        	}
        	//列表数据
            $modules = Yii::$app->getModule("patient");
	        $result = $modules->runAction("goodsinfo/getlist");
        	//重建缓存
	        if($result){
	        	$listValue = [$listKey];
	        	foreach($result as $key => $value){
	        		$listValue[] =  $value['goods_id'];
	        		$listValue[] = json_encode($value,256);
	        	}
	        	$redis->executeCommand("hmset", $listValue);
	        }
        	return [1];
        } catch (\Exception $e) {
        	Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
        	return [1];
        }
    }
    
    
    //订单退款操作
    public function actionRefundorder($info = []) {
    	try {
    		var_dump($info);
    		if(!$info || !isset($info['order_id'])){
    			return [1];
    		}
    		$modules = Yii::$app->getModule("patient");
    		$order   = $modules->runAction("userorder/getInfoById",['id'=>$info['order_id']]);
    		if(!$order||!isset($order['order_type'])){
    			return [1];
    		}
            if($order['order_type']!='19'){
                return [1];
            }
    		//订单状态已回复
    		if($order['is_reply']>1){
    			return [1];
    		}
    		$result = $modules->runAction("pay/refundorder",['order_id'=>$info['order_id']]);
    		if (!$result){
    			return [];
    		}
            return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
    
}