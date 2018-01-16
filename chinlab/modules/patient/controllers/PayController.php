<?php

namespace app\modules\patient\controllers;

use app\common\application\RabbitConfig;
use app\common\application\StateConfig;
use app\modules\patient\data\OrderInput;
use app\modules\patient\data\OrderOutput;
use app\modules\patient\data\PayOutput;
use app\modules\patient\models\OrderInquiryOnline;
use app\modules\patient\models\PayAccompany;
use app\modules\patient\models\PayCardService;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class PayController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate($info = []) {
        if (!$info) {
            return [];
        }
        $table = StateConfig::$orderType['type'.$info['order_type']]['payTable'];
        try {
            $user = new $table();
            foreach($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            return PayOutput::formatData($result);
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetInfoById($id = "") {

        try {
            if (!$id) {
                return [];
            }

            $orderType = substr($id, 3, 3) - 100;
            $id = substr($id, 6);
            $class = StateConfig::$orderType['type'.$orderType]['payTable'];

            $customer = $class::findOne([
                'pay_id' => $id,
            ]);
            if (!$customer) {
                return [];
            }

            return PayOutput::formatData($customer->toArray());
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetInfoByUidOrderId($uid = "", $orderId = "") {

        try {
            if (!$uid || !$orderId) {
                return [];
            }
            $customer = PayAccompany::findOne([
                'user_id' => $uid, 'order_id' => $orderId, 'pay_status' => 2,
            ]);
            if (!$customer) {
                return [];
            }
            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetInfoByUidOrderIdCard($orderId = "") {

        try {
            if (!$orderId) {
                return [];
            }
            $customer = PayCardService::findOne([
                'order_id' => $orderId, 'pay_status' => 2,
            ]);
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
     * @param string $id
     * @param array $info
     * @return array|mixed
     */
    public function actionUpdateinfo($id = "", $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }

            $orderType = substr($id, 3, 3) - 100;
            $id = substr($id, 6);
            $class = StateConfig::$orderType['type'.$orderType]['payTable'];



            $user = $class::findOne(['pay_id' => $id]);
            if (!$user) {
                return [];
            }


            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();

            //咨询记录支付成功时间
            if ($orderType == "19" && isset($info['pay_status']) && $info['pay_status'] == 2) {
                $zixunModel = OrderInquiryOnline::findOne(['order_id'=>$user->order_id]);
                $tmpInfo = json_decode($zixunModel->patient_info, true);
                $tmpInfo['advise_pay_time'] = time();
                $tmpInfo['advise_expire_time'] = $tmpInfo['advise_pay_time'] + intval(86400 * StateConfig::LIMIT_INQUIRY);
                $zixunModel->patient_info = json_encode($tmpInfo, 256);
                $zixunModel->save();
                $tmpInfo = OrderOutput::formatData($zixunModel->toArray());
                Yii::$app->queue->send([
                    'info' => ['order_id' => $tmpInfo['order_id']],
                ], RabbitConfig::SYSTEM_FINISH_ORDER, CONF_ENV == 'pro_' ? intval(86400 * StateConfig::LIMIT_INQUIRY) : 3600);
            }
            $result = $user->toArray();
            return PayOutput::formatData($result);
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
    
    
    //订单退款操作 退款订单视作取消
    public function actionRefundorder($order_id = "") {
       try {
	       	if (!$order_id) {
	       		return [];
	       	}
    		$where = ' pay_status = 2 ';
	    	if ($order_id) {
	    		$orderid = substr($order_id, 6);
	    		$where .= ' AND order_id = ' . $orderid;
	    	}
	    	$sql = 'SELECT * FROM `pay_multi_backend` WHERE ' . $where;
	    	$command = Yii::$app->db;
	    	$paydata = $command->createCommand($sql)->queryOne();
	    	if (!$paydata) {
	    		return [1];
	    	}
	    	$t = time();
    	    $columns = [
    			'refund_id'        => Yii::$app->DBID->getID('db.pay_multi_backend_refund'),
    			'refund_money'     => $paydata['pay_money'],
    			'option_user_id'   => 0,
    			'option_user_name' => '',
    			'check_user_id'    => 0,
    			'check_user_name'  => '',
    			'backend_refund_status' => 1,
    			'refund_status' => 0,
    			'pay_id' 		=> $paydata['pay_id'],
    			'user_id' 		=> $paydata['user_id'],
    			'order_id' 		=> $paydata['order_id'],
    			'pay_money'		=> $paydata['pay_money'],
    			'pay_type'      => $paydata['pay_type'],
    			'pay_status'    => $paydata['pay_status'],
    			'pay_account'   => $paydata['pay_account'],
    			'pay_order_id'  => $paydata['pay_order_id'],
    			'order_type'    => $paydata['order_type'],
    			'order_state'   => $paydata['order_state'],
    			'requestParams' => $paydata['requestParams'],
    			'request_refund_params' => '{}',
    			'create_time' 	=> $t,
    			'update_time'	=> $t,
    			'is_delete'		=> 1,
    	    ];
	    	foreach ($paydata as $key => $val) {
	    		if (isset($columns[$key])) {
	    			$columns[$key] = $val;
	    		}
	    	}	
	    	//退款操作
	    	$data =[
	    		'order_type'	=>$paydata['order_type'],
	    		'pay_id'		=>$paydata['pay_id'],
	    		'pay_order_id' 	=>$paydata['pay_order_id'],
	    		'pay_type'      =>$paydata['pay_type'],
	    		'refund_id'	   	=>$columns['refund_id'],
	    		'pay_money'    	=>$paydata['pay_money'],
	    		'refund_money' 	=>$paydata['pay_money'],
	    		'requestParams'	=>$paydata['requestParams'],
	    	];
	    	$result = $this->actionRefund($data);
	    	if ($result['status']) {
	    		$refund_status = 1; //1:退款成功2:退款失败
	    	} else {
	    		$refund_status = 2;
	    	}
	    	$log = [];
	    	$log['refund_log_id'] = Yii::$app->DBID->getID('db.pay_multi_backend_refund_log');
	    	$log = array_merge($log, $columns);
	    	$flag = false;
	    	$command = Yii::$app->db;
	    	$transaction = $command->beginTransaction();
	    	try {
	    		//写入退款记录表
	    		$command->createCommand()->insert(
	    				'pay_multi_backend_refund_log', $log)->execute();
	    		//写入退款表
	    		$command->createCommand()->insert(
	    				'pay_multi_backend_refund', $columns)->execute();
	    		$command->createCommand()->update(
	    			'pay_multi_backend', ['refund_status' => $refund_status, 'update_time' => time()],
	    			'order_id=:order_id', [':order_id' =>$orderid])->execute();
                //更新订单表
	    		$command->createCommand()->update(
                    'order_multi_backend', ['order_state' => '4', 'update_time' => time()],
                    'order_id=:order_id', [':order_id' =>$orderid])->execute();
                //更新在线问诊订单表
	    		$command->createCommand()->update(
                    'order_inquiry_online', ['order_state' => '4', 'update_time' => time()],
                    'order_id=:order_id', [':order_id' =>$orderid])->execute();
	    		$transaction->commit();
	    		$flag = true;
	    	} catch (\Exception $e) {
	    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
	    		$transaction->rollBack();
	    	}
	    	if ($flag) {
	    		$models = '医生未回复主动退款';
	    		$desc = '退款金额:' . $data['refund_money'] . '元';
	    		$model = new \app\models\OperationLog();
	    		$model->operation_id      = Yii::$app->DBID->getID('db.order_operation_log');
	    		$model->order_id          = $paydata['pay_order_id'];
	    		$model->order_type        = $paydata['order_type'];
	    		$model->manager_id        = '0';
	    		$model->manager_name 	  = '';
	    		$model->operation_model   = $models;
	    		$model->create_time       = time();
	    		$model->operation_desc    = $desc;
	    		$model->operation_type    = 99; //退款操作
	    		$model->operation_details = '{}';
	    		$model->save();
	    	}
	    	//如果使用了优惠卷，退还优惠卷
           if($paydata['coupon_id']){
               Yii::$app->getModule("patient")->runAction('ordercoupon/updatecouponbycid',
                   ['cid' => $paydata['coupon_id'], 'status' => '1']);
           }
           return true;
	    } catch(Exception $e) {
	    	Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
	    	return [];
	    }
    }
    
   
    //退款返回结果
    public  function actionRefund($data)
    {
    	try {
    		if (!$data || !is_array($data)){
    			return false;
    		}
    		$middlePrefix = $data['order_type'] + 100;
            $orderPrefix = StateConfig::$orderType['type' . $data['order_type']]['list'];
            $outTradeNo = $orderPrefix . $middlePrefix . $data['pay_id'];
            $transId = $data['pay_order_id'];
            $outRefundNo = $data['refund_id'];
            $totalFee = $data['pay_money'];
            $refundFee = $data['refund_money'];
    		if (CONF_ENV != 'pro_') {
    			$totalFee = '0.01';
    			$refundFee = '0.01';
    		}
    		$result = false;
    		if ($data['pay_type'] == '1') {
    			$result = Yii::$app->alipay->refund($transId, $outTradeNo, $outRefundNo, $totalFee, $refundFee);
    		} elseif ($data['pay_type'] == '2') {
    			$requestParams = json_decode($data['requestParams'], true);
    			$outTradeNo = $requestParams['out_trade_no'];
    			$result = Yii::$app->weipay->refund($transId, $outTradeNo, $outRefundNo, $totalFee, $refundFee);
    		} elseif ($data['pay_type'] == '3') {
    			$requestParams = json_decode($data['requestParams'], true);
    			$transId = $requestParams['queryId'];
    			$result = Yii::$app->unionPay->refund($transId, $outTradeNo, $outRefundNo, $totalFee, $refundFee);
    		}
    		if (!isset($result['status']) || !isset($result['response'])) {
    			return false;
    		}
    		return  $result;
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return false;
    	}
    }
    
    
}