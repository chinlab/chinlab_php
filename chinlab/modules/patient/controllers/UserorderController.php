<?php

namespace app\modules\patient\controllers;

use app\common\application\RabbitConfig;
use app\common\application\StateConfig;
use app\modules\patient\behavior\OrderJson;
use app\modules\patient\data\OrderInput;
use app\modules\patient\data\OrderOutput;
use yii\base\Exception;
use yii\db\Expression;
use yii\log\Logger;
use Yii;

class UserorderController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateOrder($info = [])
    {
        if (!$info) {
            return [];
        }
        $info = OrderInput::formatData($info);
        $table = StateConfig::$orderType['type' . $info['order_type']]['orderTable'];
        try {
            $user = new $table();
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            //订单配置文件ID
            $user->order_version = StateConfig::$nowOrderVersion;
            $user->save();
            $result = $user->toArray();
            $res = OrderOutput::formatData($result);
            $modules = Yii::$app->getModule('patient');
            $modules->runAction('userordertip/updateItemByState', [
                'id'            => $res['order_id'],
                'order_state'   => $res['order_state'],
                'order_version' => $res['order_version'],
                'userId'        => $res['user_id'],
            ]);
            return $res;
        } catch (Exception $e) {
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
            $class = StateConfig::$orderType['type'.$orderType]['orderTable'];
            $sql = $class::find()->where([
                'order_id' => $id,
            ])->createCommand()->getRawSql();
            $db = Yii::$app->db;
            $customer = $db->createCommand($sql)->queryOne();
            if(!$customer){
                $db_one = Yii::$app->db_master_one;
                $customer = $db_one->createCommand($sql)->queryOne();
                if(!$customer){
                    $db_two = Yii::$app->db_master_two;
                    $customer = $db_two->createCommand($sql)->queryOne();
                }
            }
            if (!$customer) {
                return [];
            }
            if ($customer['order_type'] == "15") {
                $modules = Yii::$app->getModule("patient");
                $detailResult = $modules->runAction('orderaccompanydetail/getlistbyids',
                    ['ids' => [$id]]);
                foreach ($detailResult as $k => $v) {
                    OrderOutput::setProductInfo($v['order_id'], $v);
                }
            }elseif($customer['order_type'] == "21"){
                $modules = Yii::$app->getModule("patient");
                $detailResult = $modules->runAction('ordergoodsdetail/getlistbyids',
                    ['ids' => [$id]]);
                foreach ($detailResult as $k => $v) {
                    OrderOutput::setProductInfo($v['order_id'], $v);
                }
            }
            return OrderOutput::formatData($customer);
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * @param array $condition
     * @param int $page
     * @param int $limit
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function actionGetbycondition($class, $condition = [], $page = 1, $limit = 10, $otherCondition = "")
    {
        if (!$condition) {
            return false;
        }
        try {
            $result = $class::find()->select([
                'order_id', 'user_id', 'requirement', 'process_record','order_version', 'doctor_reply', 'disease_desc', 'patient_info', 'select_info', 'order_type', 'can_pay', 'pay_money', 'order_state', 'create_time',
                'update_time', 'is_delete','is_free','is_reply'])
                ->where($condition);
            $result = $result->andWhere($otherCondition)->limit($limit)->offset(($page - 1) * $limit)->orderBy('create_time desc')->asArray()->all();
            $ids = [];
            foreach ($result as $k => $v) {
                if ($v['order_type'] == "15"||$v['order_type'] == "21") {
                    $ids[] = $v['order_id'];
                }
            }
            if ($ids) {
                $modules = Yii::$app->getModule("patient");
                $detailResult = $modules->runAction('orderaccompanydetail/getlistbyids',
                    ['ids' => $ids]);
                foreach ($detailResult as $k => $v) {
                    OrderOutput::setProductInfo($v['order_id'], $v);
                }
            }
            foreach ($result as $k => $v) {
                $result[$k] = OrderOutput::formatData($v);
                if ($v['is_free']=='2'){
                	 $result[$k]['process_money0'] = '0.00';
                }
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }


    /**
     *   获取全部订单
     */
    public function actionGetallbycondition($class, $userInfo = [], $common_state='0',$page = 1, $limit = 10)
    {
        if (!$userInfo) {
            return false;
        }
        $orderStatusIngConfig = StateConfig::getOrderStatusIng(StateConfig::$nowOrderVersion);
        try {
            $tmpClass = [];
            foreach($orderStatusIngConfig as $k=>$v){
                $type  = trim($k,'t');
                if($type=='9'){
                    $type = '5';
                }
                $tmpClass[] = $class::find()->where(["user_id" => $userInfo["user_id"], 'is_delete' => '1',
                    'order_type'=>$type])->andWhere($v['t'.$common_state]);
            }
            $tmp = $tmpClass['0'];
            foreach ($tmpClass as $tk=>$tv){
                if($tk=='0'){
                    continue;
                }
                $tmp->union($tv);
            }
           // $sql = (new \yii\db\Query())->select([
             //   'order_id', 'user_id', 'requirement', 'process_record','order_version', 'doctor_reply', 'disease_desc', 'patient_info', 'select_info', 'order_type', 'can_pay', 'pay_money', 'order_state', 'create_time',
             //   'update_time', 'is_delete','is_free','is_reply'])->from(['tmpA' => $tmp])->offset(($page - 1) * $limit)->limit($limit)->orderBy('create_time desc')->createCommand()->getRawSql();
            //echo $sql;die;
            $result =  (new \yii\db\Query())->select([
                'order_id', 'user_id', 'requirement', 'process_record','order_version', 'doctor_reply', 'disease_desc', 'patient_info', 'select_info', 'order_type', 'can_pay', 'pay_money', 'order_state', 'create_time',
                'update_time', 'is_delete','is_free','is_reply'])->from(['tmpA' => $tmp])->offset(($page - 1) * $limit)->limit($limit)->orderBy('create_time desc')->all();
            $ids = [];
            foreach ($result as $k => $v) {
                if ($v['order_type'] == "15"||$v['order_type'] == "21") {
                    $ids[] = $v['order_id'];
                }

            }
            if ($ids) {
                $modules = Yii::$app->getModule("patient");
                $detailResult = $modules->runAction('orderaccompanydetail/getlistbyids',
                    ['ids' => $ids]);
                foreach ($detailResult as $k => $v) {
                    OrderOutput::setProductInfo($v['order_id'], $v);
                }
            }
            foreach ($result as $k => $v) {
                $result[$k] = OrderOutput::formatData($v);
                if ($v['is_free']=='2'){
                    $result[$k]['process_money0'] = '0.00';
                }
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    /**
     *  只更订单新备注信息
     */
    public function actionUpdateReportFile($id = "", $info = [])
    {

        try {
            if (!$id || !$info || !$info['order_file']) {
                return [];
            }
            $orderType = substr($id, 3, 3) - 100;
            $id = substr($id, 6);
            $class = StateConfig::$orderType['type' . $orderType]['orderTable'];

            $user = $class::findOne(['order_id' => $id]);
            if (!$user) {
                return [];
            } else {
                $old_disease = json_decode($user->disease_desc, true);
                $disease_desc = array_merge($old_disease, $info);
                $disease_desc = json_encode($disease_desc, 256);
                $old_doctor_reply = json_decode($user->doctor_reply, true);
                $old_doctor_reply['retry_status'] = "0";
                $old_doctor_reply['is_supply'] = "1";
            }
            $user->disease_desc = $disease_desc;
            $user->doctor_reply = json_encode($old_doctor_reply, 256);
            $user->save();
            return OrderOutput::formatData($user->toArray());
        } catch (Exception $e) {
            //var_dump($e->getMessage());die;
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * @param string $id
     * @param array $info
     * @return array
     */
    public function actionUpdateinfo($id = "", $info = [])
    {

        try {
            if (!$id || !$info) {
                return [];
            }

            $orderType = substr($id, 3, 3) - 100;
            $id = substr($id, 6);
            $class = StateConfig::$orderType['type' . $orderType]['orderTable'];

            $user = $class::findOne(['order_id' => $id]);
            if (!$user) {
                return [];
            }

            if ($user->order_type == "15" || $user->order_type == "21") {
                $modules = Yii::$app->getModule("patient");
                $detailResult = $modules->runAction('orderaccompanydetail/getlistbyids',
                    ['ids' => [$id]]);
                foreach ($detailResult as $k => $v) {
                    OrderOutput::setProductInfo($v['order_id'], $v);
                }
            }
            //取消后记录订单的最后一次状态
            if ($info['order_state'] == "99") {
                OrderOutput::setLastStatus($user->order_state);
                OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, OrderInput::formatStatusRecord($user->order_state));
            }
            if (!isset($info['order_type'])) {
                $info['order_type'] = $user->order_type;
            }
            //商品购买已完成状态 创建健康卡
            $orderStatus = StateConfig::getOrderStatus($user->order_version)['ordertype' . $user->order_type];
            if (isset($info['order_type'])
                && $info['order_type'] == "15"
                && $info['order_state'] != "99"
                && !isset($orderStatus['type' . ($info['order_state'] + 1)])
                && isset($orderStatus['type' . ($user->order_state + 1)])
            ) {
                $detailInfo = OrderOutput::getProductInfo($id);
                for ($i = 0; $i < intval($detailInfo['buy_number']); $i++) {
                    Yii::$app->queue->send([
                        'info' => ['orderInfo' => $detailInfo],
                    ], RabbitConfig::NEWS_CARD_CREATE);
                }
            }
            $patientFlag = false;
            $patient_info = json_decode($user->patient_info,true);
            if(isset($info['advise_pay_time'])&& $info['advise_pay_time']){
                $patient_info['advise_pay_time'] = $info['advise_pay_time'];
                unset($info['advise_pay_time']);
                $patientFlag = true;
            }
            if(isset($info['advise_expire_time'])&&$info['advise_expire_time']){
                $patient_info['advise_expire_time'] = $info['advise_expire_time'];
                unset($info['advise_expire_time']);
                $patientFlag = true;
            }
            if($patientFlag){
                $info['patient_info'] = json_encode($patient_info,true);
            }
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            $res = OrderOutput::formatData($result);
            $modules = Yii::$app->getModule('patient');
            $modules->runAction('userordertip/updateItemByState', [
                'id'            => $res['order_id'],
                'order_state'   => $res['order_state'],
                'order_version' => $res['order_version'],
                'userId'        => $res['user_id'],
            ]);
            return $res;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     *  只更订单新备注信息
     */
    public function actionUpdatedesign($id = "", $info = [])
    {

        try {
            if (!$id || !$info || !$info['order_design']) {
                return [];
            }
            $orderType = substr($id, 3, 3) - 100;
            $id = substr($id, 6);
            $class = StateConfig::$orderType['type' . $orderType]['orderTable'];

            $user = $class::findOne(['order_id' => $id]);
            if (!$user) {
                return [];
            } else {
                $old_disease = json_decode($user->disease_desc, true);
                $disease_desc = array_merge($old_disease, $info);
                $disease_desc = json_encode($disease_desc, 256);
            }
            $connection = Yii::$app->db;
            $connection->createCommand()
                ->update($class::tableName(), ['disease_desc' => $disease_desc], 'order_id=:order_id', ['order_id' => $id])
                ->execute();
            return $user->toArray();
        } catch (Exception $e) {
            //var_dump($e->getMessage());die;
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
    //获取医生服务在线问诊的用户总次数
    public  function actionGetsumorder($condition){
    	if (!$condition) {
    		return [];
    	}
    	try {
    		return \app\modules\patient\models\OrderInquiryOnline::find()
    		->where($condition)
    		->count('*');
    	} catch (Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
    
    
    /**
     * @param array $condition
     * @param int $page
     * @param int $limit
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function actionGetbyusercondition($class, $condition = [], $page = 1, $limit = 10, $otherCondition = "")
    {
    	if (!$condition) {
    		return false;
    	}
    	try {
    		$result = $class::find()->select([
    				'user_id','any_value(order_id) as order_id','any_value(process_record) as process_record','any_value(requirement) as requirement', 'any_value(order_version) as order_version',
    				'any_value(doctor_reply) as doctor_reply', 'any_value(disease_desc) as disease_desc', 'any_value(patient_info) as patient_info', 
    				'any_value(select_info) as select_info', 'any_value(order_type) as order_type', 'any_value(can_pay) as can_pay',
    				'any_value(pay_money) as pay_money', 'any_value(order_state) as order_state', 
    				'any_value(create_time) as create_time', 'any_value(update_time) as update_time', 
    				'any_value(is_delete) as is_delete','any_value(is_free) as is_free'])
    				->where($condition);
    		$result = $result->andWhere($otherCondition)->limit($limit)->offset(($page - 1) * $limit)->orderBy('update_time desc')->groupBy('user_id')->asArray()->all();
    		$ids = [];
    		foreach ($result as $k => $v) {
    			if ($v['order_type'] == "15") {
    				$ids[] = $v['order_id'];
    			}
    		}
    		if ($ids) {
    			$modules = Yii::$app->getModule("patient");
    			$detailResult = $modules->runAction('orderaccompanydetail/getlistbyids',
    					['ids' => $ids]);
    			foreach ($detailResult as $k => $v) {
    				OrderOutput::setProductInfo($v['order_id'], $v);
    			}
    		}
    		foreach ($result as $k => $v) {
    			$result[$k] = OrderOutput::formatData($v);
    			if ($v['is_free']=='2'){
    				$result[$k]['process_money0'] = '0.00';
    			}
    		}
    		return $result;
    	} catch (Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
    
    
    /**  
     *  根据条件获取医生收入
     * @param array $condition
     * @param int $page
     * @param int $limit
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function actionGetDoctorIncomecondition($class, $condition = [], $otherCondition = "")
    {
    	if (!$condition) {
    		return false;
    	}
    	try {
    		$result = $class::find()->select([
                new Expression('sum(json_extract(process_record,\'$.t2.option_money\')) AS "option_money"')])
                ->where($condition);
            //$sql = $result->andWhere($otherCondition)->createCommand()->getRawSql();echo $sql;die;
            $result = $result->andWhere($otherCondition)->asArray()->one();
    		return $result;
    	} catch (Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }


    /**
     * @param array $condition
     * @param int $page
     * @param int $limit
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function actionGetordercountbycondition($class, $condition = [],  $otherCondition = "")
    {
        if (!$condition) {
            return false;
        }
        try {
            $result = $class::find()->where($condition);
            return $result->andWhere($otherCondition)->count('*');
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    public function actionGetorderinfobycondition($orderType= '19', $condition = [],  $otherCondition = "") {
        try {
            if (!$orderType) {
                return [];
            }
            if(!isset( StateConfig::$orderType['type'.$orderType]['orderTable'])){
                return [];
            }
            $class  = StateConfig::$orderType['type'.$orderType]['orderTable'];
            //$sql = $class::find()->where($condition)->andWhere($otherCondition)->createCommand()->getRawSql();echo $sql;die;
            $customer = $class::find()->where($condition)->andWhere($otherCondition)->one();
            if (!$customer) {
                return [];
            }
            $result = $customer->toArray();
            return OrderOutput::formatData($result);
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
    
}
