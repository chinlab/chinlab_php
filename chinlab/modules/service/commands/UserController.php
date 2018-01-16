<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 2:34
 */
namespace app\modules\service\commands;

use app\common\application\RabbitConfig;
use app\common\application\StateConfig;
use app\common\controller\Controller;
use app\modules\patient\behavior\OrderJson;
use app\modules\patient\data\OrderInput;
use Yii;

class UserController extends Controller
{

    /**
     * 更新用户信息
     * @param array $info
     * @return array
     */
    public function actionUpdateuserinfo($info = []) {

        if (!$info || !is_array($info) || !isset($info['user_id'])) {
            return [1];
        }
        $result = Yii::$app->getModule('patient')->runAction('user/updateuserinfo',
            ['id' => $info['user_id'], 'info' => $info]);
        if (!$result) {
            $result = Yii::$app->getModule('patient')->runAction("user/createUser", ['info' => $info]);
        }
        if ($result) {
            Yii::$app->getModule('patient')->runAction('userssdb/deleteUser',
                ['info' => $info]);
        }
        return $result;
    }

    /**
     * 获取超时的订单信息
     */
    public function actionFindorderlist() {

        //当前时间-更新时间 > 5天 且 id = 1
        //当前时间-5天 < 更新时间
        if (CONF_ENV == "pro_") {
            $lastMinTime = 5 * 86400;
            $lastMaxTime = 7 * 86400;
        	$expireDate  = date('Y-m-d',time()).' 12:00:00';
        	$expireTime  = strtotime($expireDate);
        	$expireTime  = $expireTime + rand(1, 1800);
        } else {
            $lastMinTime = 0;
            $lastMaxTime = 1800;
            $expireTime  = time()+900;
        }
        $maxTime = time() - $lastMinTime;
        $minTime = time() - $lastMaxTime;
        $tmpInfo = StateConfig::$orderListMap;
        unset($tmpInfo['t58']);
        $orderNewType = array_keys($tmpInfo);
        $modules = Yii::$app->getModule('patient');
        $hasOrderPhone = [];
        foreach($orderNewType as $k => $v) {
            $order_type = str_replace('t', '', $v);
            $class = isset(StateConfig::$orderListMap['t' . $order_type]) ? StateConfig::$orderListMap['t' . $order_type] : StateConfig::$orderListMap['t1'];
            $arr = ['is_delete' => '1','order_state'=> '1'];
            $commonSql = "update_time > {$minTime} AND update_time < {$maxTime}";
            $result = $modules->runAction('userorder/getbycondition',
                ['class' => $class, 'condition' => $arr, 'page' => 1, 'limit' => 10000, 'otherCondition'=>$commonSql]);
            foreach($result as $item) {
                Yii::$app->queue->send([
                    'info' => ['order_id' => $item['order_id']],
                ], RabbitConfig::SYSTEM_CANCEL_FIRST_ORDER, $lastMaxTime + $item['update_time'] - time());
                //临近过期取消订单的最后短信通知延迟队列
                if (!in_array($item['order_phone'], $hasOrderPhone)) {
                    Yii::$app->queue->send([
                        'info' => ['order_id' => $item['order_id'], 'order_phone' => $item['order_phone']],
                    ], RabbitConfig::ORDER_SMS_EXPIRE_SYS, $expireTime - time());
                }
                $hasOrderPhone[] = $item['order_phone'];
            }
        }
        return [1];
    }
    /**
     * 7天设置订单超时过期
     * @param array $info
     * @return array
     */
    public function actionCancelfirstorder($info = []) {

        if (!$info || !is_array($info) || !isset($info['order_id'])) {
            return [1];
        }

        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction('userorder/getInfoById',
            ['id' => $info['order_id']]);
        if (!$result || $result['order_state'] == "99") {
            return [1];
        }
        $orderStateConfig = StateConfig::getOrderStatus($result['order_version'])["ordertype".$result['order_type']]['type'.$result['order_state']];
        if ($orderStateConfig['sysCancel'] == "0") {
            return [1];
        }
        $updateInfo = [
            "order_state" => "99",
            "update_time" => time(),
            "can_pay" => 0,
            "pay_money" => 0,
        ];
        OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, OrderInput::formatProcessRecord("", "管理人员", 0, 99, "", "",2));
        $modifyResult = $modules->runAction('userorder/updateinfo',
            ['id' => $result['order_id'], 'info' => $updateInfo]);
        $modules->runAction('ordertip/delCommonItem', ['id' => $modifyResult['order_id']]);
        return $modifyResult;
    }

    /**
     * 系统取消订单
     * @param array $info
     * @return array
     */
    public function actionCancelorder($info = []) {

        if (!$info || !is_array($info) || !isset($info['order_id'])) {
            return [1];
        }

        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction('userorder/getInfoById',
            ['id' => $info['order_id']]);
        if (!$result || $result['order_state'] == "99") {
            return [1];
        }
        $orderStateConfig = StateConfig::getOrderStatus($result['order_version'])["ordertype".$result['order_type']]['type'.$result['order_state']];
        //todo 是否取消订单
        if ($orderStateConfig['sysCancel'] == "0") {
            //return [1];
        }
        if (!isset($info['order_state'])) {
            return [1];
        }
        if ($info['order_state'] != $result['order_state']) {
            return [1];
        }
        $updateInfo = [
            "order_state" => "99",
            "update_time" => time(),
            "can_pay" => 0,
            "pay_money" => 0,
        ];
        OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, OrderInput::formatProcessRecord("", "管理人员", 0, 99, "", "",2));
        $modifyResult = $modules->runAction('userorder/updateinfo',
            ['id' => $result['order_id'], 'info' => $updateInfo]);
        $modules->runAction('ordertip/delCommonItem', ['id' => $modifyResult['order_id']]);
        return $modifyResult;
    }

    /**
     * 系统完成订单 在线咨询
     * @param array $info
     * @return array
     */
    public function actionFinishorder($info = []) {

        if (!$info || !is_array($info) || !isset($info['order_id'])) {
            return [1];
        }

        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction('userorder/getInfoById',
            ['id' => $info['order_id']]);
        if (!$result || $result['order_state'] != '2') {
            return [1];
        }
        //只针对已付款还未完成的订单做系统完成操作
        $updateInfo = [
            "order_state" => 3,
            "update_time" => time(),
            "can_pay" => 0,
            "pay_money" => 0,
        ];
        OrderJson::setValue(OrderJson::ORDER_STATE_RECORD, OrderInput::formatProcessRecord("", "管理人员", 0, 3, "", "",2));
        $modifyResult = $modules->runAction('userorder/updateinfo',
            ['id' => $result['order_id'], 'info' => $updateInfo]);
        $modules->runAction('ordertip/delCommonItem', ['id' => $modifyResult['order_id']]);
        return $modifyResult;
    }
    
    /**
     *  查询到期的新闻放入删除新闻缓存的延迟队列
     */
    public function actionDelcache() {
        $modules = Yii::$app->getModule('article');
        $result  =  $modules->runAction('infonews/Getexpirenews');
        if(is_array($result) && count($result)){
        	foreach($result as $expirekey =>$expireval){
        		if(isset($expireval['end_time'])){
        			$expireTime = $expireval['end_time']-time();
        			$expireTime = $expireTime>0?$expireTime:10;
        			Yii::$app->queue->send([
        					'info' => $expireval,
        			], RabbitConfig::NEWS_SHOW_END, $expireTime);
        		}
        	}
        }
        return [1];
    }
    
}