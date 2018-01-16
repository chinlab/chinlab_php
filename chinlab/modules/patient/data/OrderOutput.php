<?php
namespace app\modules\patient\data;

use app\common\application\StateConfig;
use app\common\data\Encrypt;
use Yii;

/**
 * 格式化订单信息
 * Class OrderOutput
 * @package app\modules\patient\data
 */
class OrderOutput
{

    static $formatImageFlag = true;

    static $json = [
        't100'           => 1,
        't200'           => 1,
        'requirement'    => 0,
        'disease_desc'   => 0,
        'patient_info'   => 0,
        'process_record' => 0,
        'select_info' => 0,
        'doctor_reply' => 0,
    ];

    static $fields = [
        'id_card',
        'medical_card',
        'doctor_position',
        'advise_pay_time',
        'advise_expire_time',
        'order_name',
        'user_token',
        'doctor_token',
        'order_gender',
        'order_phone',
        'order_age',
        'order_city',
        'order_city_name',
        'order_date',
        'disease_name',
        'disease_des',
        'order_design',
        'hospital_id',
        'hospital_name',
        'hospital_section_id',
        'section_id',
        'section_name',
        'doctor_id',
        'doctor_name',
        'items_type',
        'report_check_time',
        'check_organization',
        'retry_status',
        'retry_info',
        'exception_info',
        'summary_info',
        'advise_info',
        'report_doctor_id',
        'report_doctor_name',
    	'user_img',
    	'doctor_img',
        'is_reply',
        'taxpayer_ident_no',
        'district_address',//医保地区
        'vip_type_desc',//VIP就医项目描述
    ];

    /**
     * 前端模板
     * @var array
     */
    private static $processTpl = [
        'process_money0'  => '0',
        'process_status0' => '0',
        'process_desc0'   => '',
        'process_money1'  => '0',
        'process_status1' => '0',
        'process_desc1'   => '',
        'process_money2'  => '0',
        'process_status2' => '0',
        'process_desc2'   => '',
        'order_tips'      => '',
    ];

    private static $processDesc = [
        'ordertype1'  => ['', '', ''],
        'ordertype5'  => ['', '', ''],
        'ordertype12' => ['', '', ''],
    ];

    static $nowProcessInfo = [];

    static $nowStatus = null;

    static $lastStatus = null;

    //商品价格
    static $productInfo = [];

    public static function formatImageFlag() {
        self::$formatImageFlag = false;
    }

    public static function setProductInfo($id, $info) {

        static::$productInfo[$id] = $info;
    }

    public static function getProductInfo($id) {

        return isset(static::$productInfo[$id]) ? static::$productInfo[$id] : [];
    }

    public static function setLastStatus($status) {
        self::$nowStatus = $status;
    }

    public static function getLastProcessStatus($data) {

        if (self::$nowStatus) {
            $state = self::$nowStatus;
            self::$nowStatus = null;
            self::$lastStatus = $state;
            return $state;
        }
        if ($data['order_state'] == "99" && isset($data['tlast']) && $data['tlast']) {
            self::$lastStatus = $data['tlast'];
            return $data['tlast'];
        }
        self::$lastStatus = $data['order_state'];
        return $data['order_state'];
    }

    public static function setNowRecordT100($data) {
        $nowProcessInfo['t100'] = $data;
    }

    public static function getNowRecordT100() {
        return static::$nowProcessInfo;
    }

    public static function formatData($data)
    {
        //获取价格设置
        if (isset($data['process_record']) && is_string($data['process_record']) && !isset($data['t100'])) {
            $data['process_record'] = json_decode($data['process_record'], true);
            $data['t100'] = isset($data['process_record']['t100']) ? $data['process_record']['t100'] : null;
            $data['t200'] = isset($data['process_record']['t200']) ? $data['process_record']['t200'] : null;
            if (isset($data['process_record']['tlast'])) {
                $data['tlast'] = $data['process_record']['tlast'];
            }
        }
        if (!isset($data['tlast'])) {
            $data['tlast'] = 0;
        }
        if (!isset($data['t100'])) {
            $data['t100'] = null;
        }
        if (!isset($data['t200'])) {
            $data['t200'] = null;
        }

        //获取就诊时间设置
        static::setNowRecordT100($data['t100']);
        if ($data['order_type'] == "15" || $data['order_type'] == "21") {
            $productInfo = self::getProductInfo($data['order_id']);
            OrderPrice::setProductInfo($productInfo);
        }
        $data = static::formatJson($data);
        $data = static::getProcessInfo($data);
        $data = static::toStrResult($data);
        if (isset($data['process_record'])) {
             unset($data['process_record']);
        }
        $data["note"] = $data["order_design"];
        $data["user_name"] = $data["order_name"];
        $data["order_update_time"] = date("Y-m-d H:i:s", $data["update_time"]);
        $data["advise_price"] = $data['pay_money'];
        $data["order_price"] = $data['pay_money'];
        if(isset(StateConfig::$priceInfo['ordertype'.$data['order_type']]['type' . $data['vip_type']])){
            $data['vip_type_desc'] =StateConfig::$priceInfo['ordertype'.$data['order_type']]['type' . $data['vip_type']]['name'];
        }else{
            $data['vip_type_desc'] = '';
        }
        $stateConfig = StateConfig::getOrderStatus($data['order_version']);
        $orderStateInfo = isset($stateConfig['ordertype' . $data['order_type']]['vip'. $data['vip_type']])
            ? $stateConfig['ordertype' . $data['order_type']]['vip'. $data['vip_type']]['type' . $data['order_state']]
            : $stateConfig['ordertype' . $data['order_type']]['type' . $data['order_state']];
        $data['order_state_desc'] = $orderStateInfo['name'];
        $data['order_type_desc'] = StateConfig::$orderType['type' . $data['order_type']]['name'];
        $data['pay_desc'] = $data['can_pay'] ? $orderStateInfo['payDesc'] : "";
        $data["visit_time"] = static::getVisitTime($data['t200']);
        //订单是否可取消
        $data["order_cancel"] = strval($orderStateInfo['cancel']);
        if(!is_numeric($data['create_time'])){
            $data['create_time'] = strtotime($data['create_time']);
        }
        $data["order_time"]  = date("Y-m-d H:i:s", $data['create_time']);
        $data["create_time"] = date("Y-m-d H:i:s", $data['create_time']);
        $data["buy_number"] = "0";
        $data["goods_small_image"] = "";
        if ($data['order_type'] == "15" || $data['order_type'] == "21") {
            $productInfo = self::getProductInfo(substr($data['order_id'], 6));
            if($productInfo){
                $data['order_type_desc'] = $productInfo['goods_name'];
                $data['user_name'] = $productInfo['user_name'];
                $data["buy_number"] = strval($productInfo['buy_number']);
                $data["goods_small_image"] = $productInfo["goods_small_image"];
                //设置商品价格
                OrderPrice::setProductInfo($productInfo);
            }
        }
        unset($data['t200']);
        if (isset($data['order_file'])) {
            $data['orderfile_url'] = is_array($data['order_file']) ? $data['order_file'] : [];
            unset($data['order_file']);
        } else {
            $data['orderfile_url'] = [];
        }
        //集团用户解读报告 用户提交确认后再解读
        $data['is_user_commit'] = "0";
        if ($data['order_type'] == "18" && $data['order_state'] == "1") {
            $data['is_user_commit'] = "1";
        }

        if (isset($data['tlast'])) {
            unset($data['tlast']);
        }
        $data = static::toStrResult($data);
        //解读报告 未完成以前 前端不显示解读信息
        $route = [""];
        if (!isset($_SERVER['PWD'])) {
            $route = Yii::$app->urlManager->parseRequest(Yii::$app->getRequest());
        }
        //是否可以查看报告
        $data["can_see_report"] = "0";
        if (in_array($data['order_type'], ['17', '18']) && $data['order_state'] == "3") {
            $data['can_see_report'] = "1";
        }
        if (strpos($route[0], "userApi") !== false && $data['order_state'] != "3") {
            $data["exception_info"] = "";
            $data["summary_info"] = "";
            $data["advise_info"] = "";
            $data["report_doctor_name"] = "";
        }
        //格式化retry——info
        if ($data['retry_info']){
        	$data['retry_info'] = explode(",", trim($data['retry_info']));
        }
        if (!is_array($data['retry_info'])) {
            $data['retry_info'] = [];
        }
        foreach($data['retry_info'] as $k => $v) {
            if (!is_numeric($v)) {
                unset($data['retry_info'][$k]);
            }
        }
        $data['retry_info'] = array_values($data['retry_info']);

        if (strpos($route[0], "userApi") !== false && self::$formatImageFlag) {
            $tmpInfo = [];
            foreach($data["retry_info"] as $k => $v) {
                if (isset($data["orderfile_url"][intval($v)])) {
                    $tmpInfo[] = $data["orderfile_url"][intval($v)];
                }
            }
            $data['retry_info'] = $tmpInfo;
        }

        if (strpos($route[0], "userApi") !== false && isset($data['is_supply']) && $data['is_supply'] > 1) {
            $data['is_supply'] = "1";
        }
        if($data['pay_money']+0>0){
            if($data['process_money0']){
                $data['process_money1'] = $data['process_money1']?$data['process_money1']:$data['pay_money'];
            }
        }
        return $data;
    }

    private static function toStrResult($data)
    {
        foreach (static::$fields as $k => $v) {
            if (!isset($data[$v])) {
                $data[$v] = "";
            }
        }
        foreach ($data as $k => $v) {
            if (!is_array($v)) {
                $data[$k] = strval($v);
            }
        }
        return $data;
    }

    private static function getVisitTime($t200)
    {

        if (!is_array($t200)) {
            return "";
        }
        if (is_numeric($t200['visit_time'])) {
            return date("Y-m-d H:i:s", $t200['visit_time']);
        }
        return $t200['visit_time'];
    }

    /**
     * @param $data
     * @return array
     */
    private static function getProcessInfo($data)
    {

        $data = array_merge($data, static::$processTpl);
        if (isset(static::$processDesc['ordertype' . $data['order_type']])) {
            foreach (static::$processDesc['ordertype' . $data['order_type']] as $k => $v) {
                $data['process_desc' . $k] = $v;
            }
        }
        $lastState = static::getLastProcessStatus($data);
        $processConfig = StateConfig::getOrderProcess($data['order_version']);
        if (isset($processConfig['ordertype' . $data['order_type']])) {
            if(!isset($data['doctor_position'])){
                $data['doctor_position'] = '0';
            }
            foreach ($processConfig['ordertype' . $data['order_type']] as $k => $v) {
                $data['process_money' . $k] = OrderPrice::get($data['order_type'], $v, $data, $data['vip_type'], $data['order_version'], $data['create_time'],$data['doctor_position']);
                if (intval($lastState) > intval($v)) {
                    $data['process_status' . $k] = '1';
                } else {
                    $data['process_status' . $k] = '0';
                }
            }
        }
        if(isset($data['process_record']['t1']['option_money'])){
            $data['process_money0'] = $data['process_record']['t1']['option_money'];
        }
        $idPrefix = 100 + $data['order_type'];
        $data['order_number'] = Encrypt::getOrderPrefix($data['order_type']) . $data['order_id'];
        //list.ordertype.id
        $data['order_id'] = StateConfig::$orderType['type' . $data['order_type']]['list'] . $idPrefix . $data['order_id'];
        //是否网页展示详情
        $data['web_url'] = "";
        if (StateConfig::$orderType['type' . $data['order_type']]['page'] == "1") {
            $data['web_url'] = Yii::$app->runData->baseUrl . "/userApi/process_orderdetail_{$data['order_id']}_".$data['update_time'].".php";
        }
        //商品购买特殊
        if ($data['order_type'] == "15") {
            $data['web_url'] = "";
        }
        //集团用户时间+3600
        if ($data['order_type'] == "16") {
            $route = Yii::$app->urlManager->parseRequest(Yii::$app->getRequest());
            if (strpos($route[0], "userApi") !== false) {
                $data['order_date'] .= " —— " . date("H:i", strtotime($data['order_date']) + 3600);
            }
        }
        $orderStatus = StateConfig::getOrderStatus($data['order_version'])['ordertype' . $data['order_type']];
        if(isset($orderStatus['vip'.$data['vip_type']])){
            $orderStatus = $orderStatus['vip'.$data['vip_type']];
        }
        $data['order_tips'] = $orderStatus['type' . $data['order_state']]['small_tip'];
        unset($data['t100']);
        return $data;
    }

    private static function formatJson($data)
    {

        foreach (static::$json as $k => $v) {
            if (!isset($data[$k])) {
                continue;
            }
            if (!is_array($data[$k])) {
                $data[$k] = json_decode($data[$k], true);
                if (!is_array($data[$k])) {
                    $data[$k] = [];
                }
                if (!$v) {
                    $data = array_merge($data, $data[$k]);
                    unset($data[$k]);
                }
            }
        }
        return $data;
    }
}