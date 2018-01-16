<?php
namespace app\common\components;
use yii\base\Component;
use Yii;

include_once __DIR__ . "/WxPay.Api.php";
include_once __DIR__ . "/WxPay.Data.php";
include_once __DIR__ . "/WxPay.Notify.php";
/**
 * 支付宝支付.
 * User: luoning
 * Date: 15/8/13
 * Time: 下午5:39
 */
class WeixinClient extends Component
{

    public $config = [];

    public function getPrePay($desc, $outTradeno, $money, $time = 0)
    {

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($desc);
        $input->SetAttach('');
        $input->SetDetail($desc);
        $input->SetOut_trade_no($outTradeno);
        $input->SetTotal_fee(strval($money * 100));
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 6000));
        $input->SetGoods_tag("test");
        $input->SetTrade_type("APP");
        $order = \WxPayApi::unifiedOrder($input);
        if (!is_array($order) || !isset($order['prepay_id'])) {
            if ($time > 2) {
                return false;
            } else {
                return $this->getPrePay($desc, $outTradeno, $money, ++$time);
            }
        }
        $apppayinof = [
            'appid' => $order['appid'],
            'partnerid' => $order['mch_id'],
            'prepayid' => $order['prepay_id'],
            'noncestr' => $order['nonce_str'],
            'timestamp' => time(),
            'package' => 'Sign=WXPay',
        ];
        $apppayinof['sign'] = $this->refreshSign($apppayinof);

        return [
            "appId" => $apppayinof['appid'],
            "partnerId" => $apppayinof['partnerid'],
            "prepayId" => $apppayinof['prepayid'],
            "nonceStr" => $apppayinof['noncestr'],
            "timeStamp" => $apppayinof['timestamp'],
            "package" => $apppayinof['package'],
            "sign" => $apppayinof['sign'],
        ];
    }

    public function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 退款
     * @param string $transId  微信支付宝订单号
     * @param string $outTradeNo 咱系统的订单号
     * @param string $outRefundNo 退款单号
     * @param string $totalFee  订单总金额
     * @param string $refundFee 订单团款金额
     * @return array
     */
    public function refund($transId, $outTradeNo, $outRefundNo, $totalFee, $refundFee) {

        $input = new \WxPayRefund();
        //$input->SetOut_trade_no($outTradeNo);
        $input->SetTotal_fee(strval($totalFee * 100));
        $input->SetOut_refund_no($outRefundNo);
        $input->SetRefund_fee(strval($refundFee) * 100);
        $input->SetTransaction_id($transId);
        $input->SetOp_user_id('1418513502');
        $order = \WxPayApi::refund($input);
        if ($order['result_code'] == "SUCCESS") {
            return ["status" => true, "response" => json_encode($order)];
        }
        return ["status" => false, "response" => json_encode($order)];
    }

    /**
     * 重新生成签名
     *
     * */
    public function refreshSign($data)
    {

        //签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->ToUrlParams($data);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . Yii::$app->weipay->config['KEY'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;

    }
}