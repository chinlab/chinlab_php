<?php

namespace app\common\components;

use Yii;
use yii\base\Component;

/**
 * 支付宝支付.
 * User: luoning
 * Date: 15/8/13
 * Time: 下午5:39
 */
class AliPayClient extends Component
{

    public $appId = "";
    public $partnerId = "";
    public $sellerId = "";
    public $notifyUrl = "";
    public $privateKeyPath = "";
    public $aliPublicKeyPath = "";
    private $fileCharset = "UTF-8";
    public $postCharset = "UTF-8";

    /**
     * 退款
     * @param string $transId  微信支付宝订单号
     * @param string $outTradeNo 咱系统的订单号
     * @param string $outRefundNo 退款单号
     * @param string $totalFee  订单总金额
     * @param string $refundFee 订单团款金额
     * @return array
     */
    public function refund($transId, $outTradeNo, $outRefundNo, $totalFee, $refundFee)
    {

        $params = [];
        $params['app_id'] = $this->appId;
        $params['method'] = "alipay.trade.refund";
        $params['format'] = "json";
        $params['charset'] = 'utf-8';
        $params['sign_type'] = "RSA";
        $params['timestamp'] = date("Y-m-d H:i:s");
        $params['version'] = '1.0';
        $params['biz_content'] = json_encode([
            "refund_amount" => $refundFee,
            "out_request_no" => $outRefundNo,
            "trade_no" => $transId,
            "out_trade_no" => strval($outTradeNo),
        ]);
        $params = $this->argSort($params);
        $tmpArr = [];
        foreach ($params as $k => $v) {
            $tmpArr[] = $k . "=" . $v;
        }
        $tmpStr = implode("&", $tmpArr);
        $params["sign"] = $this->sign($tmpStr);
        $tmpArr = [];
        foreach ($params as $k => $v) {
            $tmpArr[] = $k . "=" . urlencode($v);
        }
        $requestStr = implode("&", $tmpArr);
        $options = [
            CURLOPT_FAILONERROR => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => ['content-type: application/x-www-form-urlencoded;charset=UTF-8']
        ];
        $response = Yii::$app->httpClient->getInstance()
            ->setContent($requestStr)
            ->setMethod('post')
            ->setUrl("https://openapi.alipay.com/gateway.do")
            ->addOptions($options)
            ->send();
        $content = $response->getContent();
        $msg = json_decode(strval($content), true);
        if (is_array($msg)
            && isset($msg['alipay_trade_refund_response'])
            && isset($msg['alipay_trade_refund_response']['code'])
            && $msg['alipay_trade_refund_response']['code'] == '10000'
        ) {
            return ["status" => true, "response" => $content];
        }
        return ["status" => false, "response" => $content];
    }

    public function get($cpName, $orderId, $cpPrice)
    {

        $params = [];
        $params['app_id'] = $this->appId;
        $params['method'] = "alipay.trade.app.pay";
        $params['format'] = "json";
        $params['sign_type'] = "RSA";
        $params['timestamp'] = date("Y-m-d H:i:s");
        $params['version'] = '1.0';
        $params['charset'] = 'utf-8';
        $params['notify_url'] = $this->notifyUrl;
        $params['biz_content'] = json_encode([
            "timeout_express" => "30m",
            "seller_id" => strval($this->sellerId),
            "product_code" => "QUICK_MSECURITY_PAY",
            "total_amount" => strval($cpPrice),
            "subject" => strval($cpName),
            "body" => strval($cpName),
            "out_trade_no" => strval($orderId),
        ]);
        $params = $this->argSort($params);
        $tmpArr = [];
        foreach ($params as $k => $v) {
            $tmpArr[] = $k . "=" . $v;
        }
        $tmpStr = implode("&", $tmpArr);
        $params["sign"] = $this->sign($tmpStr);
        $tmpArr = [];
        foreach ($params as $k => $v) {
            $tmpArr[] = $k . "=" . urlencode($v);
        }
        return implode("&", $tmpArr);
    }

    public function getBackUp($cpPayName, $ordernu, $cpPrice)
    {
        $ali = [
            'service' => 'mobile.securitypay.pay',
            'partner' => $this->partnerId,//
            '_input_charset' => 'utf-8',
            'sign_type' => 'RSA',
            'sign' => '',
            'notify_url' => $this->notifyUrl,//回调地址
            'out_trade_no' => $ordernu,//商户网站唯一订单号
            'subject' => $cpPayName,//商品名称
            'payment_type' => 1,//支付类型
            'seller_id' => 'bpbhd@qq.com',//支付宝账号
            'total_fee' => $cpPrice,//总金额
            'body' => $cpPayName,//商品详情
            "it_b_pay" => "30m",
            "return_url" => "m.alipay.com",
        ];
        $ali = $this->argSort($ali);
        $str = '';

        foreach ($ali as $key => $val) {
            if ($key == 'sign_type' || $key == 'sign') {
                continue;
            } else {
                if ($str == '') {
                    $str = $key . '=' . '"' . $val . '"';
                } else {
                    $str = $str . '&' . $key . '=' . '"' . $val . '"';
                }
            }
        }

        $sign = urlencode($this->sign($str));
        $str = $str . '&sign=' . '"' . $sign . '"' . '&sign_type=' . '"' . $ali['sign_type'] . '"';//传给支付宝接口的数据
        return $str;
    }


    /**
     * 检测消息是否是阿里发过来的
     *
     * */
    public function checkIsAlipay($params)
    {
        $sign = $params['sign'];
        $params['sign_type'] = null;
        $params['sign'] = null;
        return $this->rsaVerify($this->getSignContent($params), $sign);
    }

    protected function getSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, $this->postCharset);

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }


    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    protected function characet($data, $targetCharset)
    {

        if (!empty($data)) {
            $fileType = $this->fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }


        return $data;
    }

    /**
     *
     *
     * */
    public function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }

    /**
     * 验证签名
     * */
    public function paraFilter($para)
    {
        $para_filter = [];
        while (list ($key, $val) = each($para)) {
            if ($key == "sign" || $key == "sign_type" || $val == "") continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 验证签名
     * @param string $data 需要签名的字符串
     * @param string $sign 签名结果
     * @return boolean
     */
    public function rsaVerify($data, $sign)
    {
        $pubKey = file_get_contents($this->aliPublicKeyPath);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }

    public function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    //RSA签名
    public function sign($data)
    {

        //读取私钥文件
        $priKey = file_get_contents($this->privateKeyPath);//私钥文件路径
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res);
        //释放资源
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
}