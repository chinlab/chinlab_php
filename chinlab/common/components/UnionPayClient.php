<?php
namespace app\common\components;
use Yii;
use yii\base\Component;


class UnionPayClient extends Component
{
		
	public $config;
	public $requestUrl;
	public $proxy;
	
	public function init(){
		foreach($this as $key => $value){
		if(is_array($value)){
			foreach($value as $constKey => $constVal){
				define($constKey, $constVal);
			}
		 }
	  }
	}
	
	
	public function getTn($orderId,$txnAmt)
	{  
	   $params = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.0.0',                 //版本号
				'encoding' => 'utf-8',				  //编码方式
				'txnType' => '01',				      //交易类型
				'txnSubType' => '01',				  //交易子类
				'bizType'  => '000201',				  //业务类型
				'frontUrl' =>  SDK_FRONT_NOTIFY_URL,     //前台通知地址
				'backUrl'   => SDK_FRONT_TRANS_URL,	  //后台通知地址
				'signMethod' => '01',	              //签名方法
				'channelType' => '08',	              //渠道类型，07-PC，08-手机
				'accessType' => '0',		          //接入类型
				'currencyCode' => '156',	          //交易币种，境内商户固定156
				//TODO 以下信息需要填写
				'merId'   => MER_ID,		//商户代码，请改自己的测试商户号
				'orderId' => $orderId,  			//商户订单号，8-32位数字字母，不能含“-”或“_”
				'txnTime' => date('YmdHis',time()),	    //订单发送时间，格式为YYYYMMDDhhmmss，取北京时间
				'txnAmt'  => $txnAmt, 				//交易金额，单位分
				//'reqReserved' =>'透传信息',   			//请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
		);
		$sign   = self::sign( $params ); // 签名
		$result = self::post($sign,SDK_App_Request_Url);
		if(is_array($result) && self::validate($result) && isset($result['tn'])){
			return ['sign'=> $sign,'tn'=>$result['tn']];
		}
		return false; 
	}
	
	/**
     * 退款
     * @param string $transId  微信支付宝订单号
     * @param string $outTradeNo 咱系统的订单号
     * @param string $outRefundNo 退款单号
     * @param string $totalFee  订单总金额
     * @param string $refundFee 订单退款金额
     * @return array
     */
	public static function Refund($transId, $outTradeNo, $outRefundNo, $totalFee, $refundFee){
		$params = array(
			//以下信息非特殊情况不需要改动
			'version' => '5.0.0',		      //版本号
			'encoding' => 'utf-8',		      //编码方式
			'signMethod' => '01',		      //签名方法
			'txnType' => '04',		          //交易类型
			'txnSubType' => '00',		      //交易子类
			'bizType' => '000201',		      //业务类型
			'accessType' => '0',		      //接入类型
			'channelType' => '07',		      //渠道类型
			'backUrl'   => SDK_FRONT_TRANS_URL, //后台通知地址
				
			//TODO 以下信息需要填写
			'orderId' => $outRefundNo,	//商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费
			'merId'   =>  MER_ID,	    //商户代码，请改成自己的测试商户号
			'origQryId' => $transId,    //原消费的queryId，可以从查询接口或者通知接口中获取
			'txnTime' => date('YmdHis',time()),	 //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费
			'txnAmt'  => $refundFee*100,       //交易金额,单位分，退货总金额需要小于等于原消费
			// 		'reqReserved' =>'透传信息',            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
		);
		$sign     = self::sign($params); // 签名
		if(!$sign){
			return  array('status'=>false, 'response'=>json_encode($sign));
		}
		$result_arr = self::post( $sign, SDK_App_Request_Url);
		if(count($result_arr)<=0) { //没收到200应答的情况
			return  array('status'=>false, 'response'=>json_encode($result_arr)); 
		}
		if(!self::validate( $result_arr )){
			return   array('status'=>false, 'response'=>json_encode($result_arr));
		}
		if ($result_arr["respCode"] == "00"){
			//交易已受理，等待接收后台通知更新订单状态，如果通知长时间未收到也可发起交易状态查询
			return   array('status'=> true, 'msg'=>'退款订单受理成功!');
		} else if ($result_arr["respCode"] == "03"
				|| $result_arr["respCode"] == "04"
				|| $result_arr["respCode"] == "05" ){
			//后续需发起交易状态查询交易确定交易状态
			return   array('status'=>false, 'response'=>json_encode($result_arr));
		} else {
			//其他应答码做以失败处理
			return   array('status'=>false, 'response'=>json_encode($result_arr));
		}
	}
	
	
	/**
	 * 签名
	 * @param req 请求要素
	 * @param resp 应答要素
	 * @return 是否成功
	 */
	public static function sign(&$params, $cert_path=SDK_SIGN_CERT_PATH, $cert_pwd=SDK_SIGN_CERT_PWD) {
		$params ['certId'] = self::getSignCertIdFromPfx($cert_path, $cert_pwd); //证书ID
		if(isset($params['signature'])){
			unset($params['signature']);
		}
		// 转换成key=val&串
		$params_str = self::createLinkString ( $params, true, false );
	
		$params_sha1x16 = sha1 ( $params_str, FALSE );
	
		$private_key = self::getSignKeyFromPfx( $cert_path, $cert_pwd );
		// 签名
		$sign_falg = openssl_sign ( $params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1 );
		if ($sign_falg) {
			$signature_base64 = base64_encode ( $signature );
			$params ['signature'] = $signature_base64;
			return $params;
		} else {
			return false;
		}
	}
	
	
	/**
	 * 验签
	 * @param $params 应答数组
	 * @return 是否成功
	 */
	public static function validate($params) {
		// 公钥
		$public_key = self::getVerifyCertByCertId ( $params ['certId'] );
		// 签名串
		$signature_str = $params ['signature'];
		unset ( $params ['signature'] );
		$params_str = self::createLinkString ( $params, true, false );
		$signature = base64_decode ( $signature_str );
		//	echo date('Y-m-d',time());
		$params_sha1x16 = sha1 ( $params_str, FALSE );
		$isSuccess = openssl_verify( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
		return $isSuccess;
	}
	
	public static function getVerifyCertByCertId($certId){
		if(count(self::$verifyCerts) == 0){
			self::initVerifyCerts();
		}
		//@TODO
		if(count(self::$verifyCerts) == 0){
			return null;
		}
		if(array_key_exists($certId, self::$verifyCerts)){
			return self::$verifyCerts[$certId]->key;
		} else {
			return null;
		}
	}
	
	
	private static function initVerifyCerts($cert_dir=SDK_VERIFY_CERT_DIR) {
		$handle = opendir ( $cert_dir );
		if (!$handle) {
			return;
		}
		while ($file = readdir($handle)) {
			clearstatcache();
			$filePath = $cert_dir . '/' . $file;
			if (is_file($filePath)) {
				if (pathinfo($file, PATHINFO_EXTENSION) == 'cer') {
					$x509data = file_get_contents($filePath);
					if($x509data === false ){
					
					}
					$cert = new self();
					openssl_x509_read($x509data);
					$certdata = openssl_x509_parse($x509data);
					$cert->certId = $certdata ['serialNumber'];
					$cert->key = $x509data;
					self::$verifyCerts[$cert->certId] = $cert;
				}
			}
		}
		closedir ( $handle );
	}
	
	public static function createLinkString($para, $sort, $encode) {
		if($para == NULL || !is_array($para))
			return "";
	
		$linkString = "";
		if ($sort) {
			$para = static::argSort ( $para );
		}
		while ( list ( $key, $value ) = each ( $para ) ) {
			if ($encode) {
				$value = urlencode ( $value );
			}
			$linkString .= $key . "=" . $value . "&";
		}
		// 去掉最后一个&字符
		$linkString = substr ( $linkString, 0, count ( $linkString ) - 2 );
	
		return $linkString;
	}
	
	public static function argSort($para) {
		ksort ( $para );
		reset ( $para );
		return $para;
	}
	
	public static function getSignKeyFromPfx($certPath=SDK_SIGN_CERT_PATH, $certPwd=SDK_SIGN_CERT_PWD)
	{
		if (!array_key_exists($certPath, self::$signCerts)) {
			self::initSignCert($certPath, $certPwd);
		}
		return self::$signCerts[$certPath] -> key;
	}
	
	
	public static function getSignCertIdFromPfx($certPath=SDK_SIGN_CERT_PATH, $certPwd=SDK_SIGN_CERT_PWD)
	{
		if (!array_key_exists($certPath, self::$signCerts)) {
			self::initSignCert($certPath, $certPwd);
		}
		return self::$signCerts[$certPath] -> certId;
	}
    
	
	
	private static $signCerts = array();
	private static $encryptCerts = array();
	private static $verifyCerts = array();
	public $cert;
	public $certId;
	public $key;
	
	private static function initSignCert($certPath, $certPwd){
		$pkcs12certdata = file_get_contents ( $certPath );
		if($pkcs12certdata === false ){
			//$logger->LogInfo($certPath . "读取失败。");
		}
		$cert = new self();
		openssl_pkcs12_read ( $pkcs12certdata, $certs, $certPwd );
		$x509data = $certs['cert'];
	
		openssl_x509_read ( $x509data );
		$certdata = openssl_x509_parse ( $x509data );
		$cert->certId = $certdata ['serialNumber'];
	
		// 		$certId = CertSerialUtil::getSerial($x509data, $errMsg);
		// 		if($certId === false){
		//         	$logger->LogInfo("签名证书读取序列号失败：" . $errMsg);
		//         	return;
		// 		}
		//         $cert->certId = $certId;
	
		$cert->key = $certs ['pkey'];
		$cert->cert = $x509data;
	
		//$logger->LogInfo("签名证书读取成功，序列号：" . $cert->certId);
		self::$signCerts[$certPath] = $cert;
	}
	
	
	
	/**
	 * 后台交易 HttpClient通信
	 *
	 * @param unknown_type $params
	 * @param unknown_type $url
	 * @return mixed
	 */
	public static function post($params, $url) {
		$opts = self::createLinkString ( $params, false, true );
		$ch   = curl_init ();
		curl_setopt($ch , CURLOPT_URL, $url );
		curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch , CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch , CURLOPT_POST, 1);
		curl_setopt($ch , CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch , CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch,  CURLOPT_VERBOSE, 1);
		curl_setopt($ch , CURLOPT_FOLLOWLOCATION,TRUE);
	    curl_setopt($ch , CURLOPT_HTTPHEADER, array (
	    	'Expect:',
			'Content-type:application/x-www-form-urlencoded;charset=UTF-8',
		)); 
		curl_setopt($ch , CURLOPT_POSTFIELDS, $opts);
		$curl_version = curl_version();
		if ($curl_version['version_number'] >= 462850) {
			curl_setopt($ch , CURLOPT_CONNECTTIMEOUT_MS, 30000);
			curl_setopt($ch , CURLOPT_NOSIGNAL, 1);
		}
		$result = self::exeBySetTimes(3, $ch );
		curl_close($ch);
		$result_arr = self::convertStringToArray ($result);
		return $result_arr;
	}
	

	/**
	 * key1=value1&key2=value2转array
	 * @param $str key1=value1&key2=value2的字符串
	 * @param $$needUrlDecode 是否需要解url编码，默认不需要
	 */
	public static function parseQString($str, $needUrlDecode=false){
		$result = array();
		$len = strlen($str);
		$temp = "";
		$curChar = "";
		$key = "";
		$isKey = true;
		$isOpen = false;
		$openName = "\0";
	
		for($i=0; $i<$len; $i++){
			$curChar = $str[$i];
			if($isOpen){
				if( $curChar == $openName){
					$isOpen = false;
				}
				$temp = $temp . $curChar;
			} elseif ($curChar == "{"){
				$isOpen = true;
				$openName = "}";
				$temp = $temp . $curChar;
			} elseif ($curChar == "["){
				$isOpen = true;
				$openName = "]";
				$temp = $temp . $curChar;
			} elseif ($isKey && $curChar == "="){
				$key = $temp;
				$temp = "";
				$isKey = false;
			} elseif ( $curChar == "&" && !$isOpen){
				self::putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
				$temp = "";
				$isKey = true;
			} else {
				$temp = $temp . $curChar;
			}
		}
		self::putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
		return $result;
	}
	
	
	/**
	 * 字符串转换为 数组
	 *
	 * @param unknown_type $str
	 * @return multitype:unknown
	 */
	public static function convertStringToArray($str) {
		return self::parseQString($str);
	}
	
	
	public static function putKeyValueToDictionary($temp, $isKey, $key, &$result, $needUrlDecode) {
		if ($isKey) {
			$key = $temp;
			if (strlen ( $key ) == 0) {
				return false;
			}
			$result [$key] = "";
		} else {
			if (strlen ( $key ) == 0) {
				return false;
			}
			if ($needUrlDecode)
				$result [$key] = urldecode ( $temp );
			else
				$result [$key] = $temp;
		}
	}
	/**
	 * 后台交易 HttpClient通信
	 *
	 * @param unknown_type $params
	 * @param unknown_type $url
	 * @return mixed
	 */
	public static function get($params, $url) {
		$opts = self::createLinkString ( $params, false, true );
		$ch = curl_init ();
		curl_setopt($ch, CURLOPT_URL, $url );
		curl_setopt($ch , CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch , CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch , CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch ,CURLOPT_FOLLOWLOCATION,TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array (
			'Expect:',
			'Content-type:application/x-www-form-urlencoded;charset=UTF-8',
		));
		$curl_version = curl_version();
		if ($curl_version['version_number'] >= 462850) {
			curl_setopt($ch , CURLOPT_CONNECTTIMEOUT_MS, 30000);
			curl_setopt($ch , CURLOPT_NOSIGNAL, 1);
		}
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $opts );
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
		$result   = self::exeBySetTimes(3, $ch);
		curl_close($ch);
		$result_arr = self::convertStringToArray ($result);
		return $result_arr;
	}
	

	private static function exeBySetTimes($count, $ch)
	{
		if(PROXY){
		 	curl_setopt ($ch, CURLOPT_PROXY, PROXY);
		 	curl_setopt ($ch, CURLOPT_PROXYPORT, PROXYPORT);
		} 
		$result = curl_exec($ch);
		$info   = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		$code   = $info["http_code"];
		//echo date('y-m-d h:i:s',time()).('错误信息：'.curl_error($ch)) . PHP_EOL;
		//echo date('y-m-d h:i:s',time()).('错误码：'.curl_errno($ch)) . PHP_EOL;
		//echo date('y-m-d h:i:s',time()).('返回值'.$result) . PHP_EOL;
		if (curl_errno($ch) != 0 && $code != 200) {
			$count--;
			if ($count > 0) {
				$result = self::exeBySetTimes($count, $ch);
			}
		}
		return $result;
	}
	
	
}