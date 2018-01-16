<?php

namespace app\modules\patient\controllers;
use yii\base\Exception;
use yii\log\Logger;
use Yii;
use app\modules\patient\models\OrderCoupon;
use app\common\application\StateConfig;

class OrdercouponController extends \app\common\controller\Controller
{
	
	public function actionGetlistbyuserid($where)
	{  
		try {
			$command = Yii::$app->db;
			$sql = "SELECT * FROM `".OrderCoupon::tableName()."` WHERE ".$where;
			$result = $command->createCommand($sql)->queryAll();
			return $result;
		} catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
   

	public function actionGetcouponbycid($cid)
	{
		try {
			$result = OrderCoupon::find()
			->where(['cid' => $cid,'status'=>1])
			->limit(1)->asArray()->one();
			if(!is_array($result)) {
				$result = [];
			}
			return $result;
		 } catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	
	public function actionUpdatecouponbycid($cid,$status)
	{
		try {
			$connection = Yii::$app->db;
			$res = $connection->createCommand()->update('order_coupon', ['status'=>$status,'update_time'=>time()], 'cid=:cid', [':cid' => $cid])->execute();
			if($res){
				return true;
			}
			return [];
		} catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	
	//给用户发放优惠卷
	public function actionAddcouponforuser($user_id)
	{
		try {
			$orderCoupon = StateConfig::$registerCoupon;
	    	$t =  time();
	    	foreach($orderCoupon as $k=>$v){
	    		$model = new OrderCoupon();
	    		foreach($v as $ck=>$cv){
	    			$model->$ck=$cv;
	    		}
	    		$model->cid  =  Yii::$app->DBID->getID('db.order_coupon');
	    		$model->user_id  = $user_id;
	    		$model->expiry_time = $v['expiry_time'] + $t;
	    		$model->save();
	    	}
	    	//弹出优惠卷提示信息
            Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
                ['id' =>$user_id, 'info' => ['is_coupon' =>'1']]);
			return [];
		} catch (Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [];
		}
	}
	
	
}
