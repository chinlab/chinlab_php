<?php
namespace app\modules\patient\data;
use app\common\application\CardConfig;
use app\common\application\StateConfig;
/**
 * 支付价格获取
 * Class OrderPrice
 * @package app\modules\patient\behavior
 */
class OrderPrice {

    static $productInfo = [];

    static $doctorLevel = null;
    
    static $price  = null;

    public static function setProductInfo($info) {

        static::$productInfo = $info;
    }

    public static function getProductInfo() {

        return static::$productInfo;
    }

    public static function setDoctorLevel($id) {
        static::$doctorLevel = $id;
    }

    public static function getDoctorLevel() {
        return static::$doctorLevel;
    }

    public static function setPrice($price) {
       static::$price = $price;
    }
    
    public static function getPrice() {
    	return static::$price;
    }
    
    /**
     * 获取支付价格
     * @param $orderType
     * @param $orderState
     * @param $recordJson
     * @param int $selectType
     *
     * @return string
     */
    public static function get($orderType, $orderState, $recordJson, $selectType = 0, $orderVersion = 1, $create_time = 0,$doctorPos=0) {
    	if (static::$price!==null) {
    		$money = static::$price;
    		static::$price = null;
    		return $money;
    	}
        if (static::$doctorLevel) {
            $money = CardConfig::getDoctorMoney(static::$doctorLevel);
            static::$doctorLevel = null;
            return $money;
        }
        $stateConfig = StateConfig::getOrderStatus($orderVersion);
        if ($create_time != 0 && $create_time < 1490772410) {
            $stateConfig = StateConfig::getOrderStatus(0);
        }
        if(isset($stateConfig['ordertype'.$orderType]['vip'.$selectType])){
            $stateConfig['ordertype'.$orderType] = $stateConfig['ordertype'.$orderType]['vip'.$selectType];
        }
        if (!isset($stateConfig['ordertype'.$orderType]) || !isset($stateConfig['ordertype'.$orderType]['type'.$orderState])) {
            return '0';
        }
        $config = $stateConfig['ordertype'.$orderType]['type'.$orderState];
        //是否可支付
        if ($config['canPay'] == '0') {
            return '0';
        }
        //有价格
        if ($config['payMoney'] > 0) {
            return sprintf("%.2f", $config['payMoney'] * ($config['moneyRate'] /100));
        }
        //后台设置价格
        if ($config['moneySource']) {
            if (!is_array($recordJson)) {
                $recordJson = json_decode($recordJson, true);
            }
            if (!is_array($recordJson)) {
                return '0';
            }
            if (!isset($recordJson[$config['moneySource']])) {
                return '0';
            }
            return sprintf("%.2f", $recordJson[$config['moneySource']]['option_money'] * ($config['moneyRate'] /100));
        }
        //vip选择价格
        if ($selectType > 0) {
            $priceItemConfig = StateConfig::getOrderPriceItem($orderVersion);
            if ($create_time != 0 && $create_time < 1490772410) {
                $priceItemConfig = StateConfig::getOrderPriceItem(0);
            }
            //商品购买
            if ($config['item'] == 't99') {
                if (!static::$productInfo) {
                    return "0";
                }
                $productInfo = static::$productInfo;
                static::$productInfo = [];
                return sprintf("%.2f", $productInfo['now_price'] * $productInfo['buy_number']);
            }
            if (!isset($priceItemConfig['ordertype' . $orderType]) || !isset($priceItemConfig['ordertype' . $orderType]['type' . $selectType])) {
                return '0';
            }
            if($orderType=='20'){
                //vip就医根据医生级别定价
                if($doctorPos){
                    $config['item'] = 't'.$doctorPos;
                }else{
                    $config['item'] = 't1';
                }
                return sprintf("%.2f", $priceItemConfig['ordertype' . $orderType]['type'.$selectType][$config['item']] * ($config['moneyRate'] /100));
            }
            //vip服务
            return sprintf("%.2f", $priceItemConfig['ordertype' . $orderType]['type'.$selectType][$config['item']] * ($config['moneyRate'] /100));
        }
        return '0';
    }
}