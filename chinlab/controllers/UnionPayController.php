<?php 
namespace app\controllers;
use Yii;
use yii\web\Response;
use app\common\data\Response as UResponse;

class UnionPayController extends BaseController{
	
	
	public function beforeAction($action)
	{
		parent::beforeAction($action);
		Yii::$app->response->format =  Response::FORMAT_JSON;
		return true;
	}
	
	public function actionGetPayTn()
	{
		$unionPay = Yii::$app->unionPay;
		$data = $unionPay->getTn('20170119161057','1');
		if($data)
		{
			return  UResponse::formatData('0', 'success',$data);
		}
		return  UResponse::formatData('100','fail');
	}
	
	/**
	 *  获取签名
	 */
	public function actionGetPaySign()
	{
		$params = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.0.0',                 //版本号
				'encoding' => 'utf-8',				  //编码方式
				'txnType' => '01',				      //交易类型
				'txnSubType' => '01',				  //交易子类
				'bizType' => '000201',				  //业务类型
				'frontUrl' =>  'https://test.huobanys.com/userApi/pay_unionpay.php',  //前台通知地址
				'backUrl'   => 'https://test.huobanys.com/userApi/pay_unionpay.php',  //后台通知地址
				'signMethod' => '01',	              //签名方法
				'channelType' => '08',	              //渠道类型，07-PC，08-手机
				'accessType' => '0',		          //接入类型
				'currencyCode' => '156',	          //交易币种，境内商户固定156
					
				//TODO 以下信息需要填写
				'merId'   =>'898111980110147',//$_POST["merId"],		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
				'orderId' =>'20170119161057',//$_POST["orderId"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
				'txnTime' =>'20170119161056',// $_POST["txnTime"],	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
				'txnAmt'  =>'1',//$_POST["txnAmt"],	//交易金额，单位分，此处默认取demo演示页面传递的参数
				// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
				//TODO 其他特殊用法请查看 pages/api_05_app/special_use_purchase.php
		);
		$unionPay = Yii::$app->unionPay;
		$sign     = $unionPay->sign($params); // 签名
		return  UResponse::formatData('0', 'success',$sign);
	}
	
	

	public function actionPayCallBack()
	{
		
		$params = '{
					"accessType":"0",
					"bizType":"000201",
					"certId":"69597475696",
					"currencyCode":"156",
					"encoding":"utf-8",
					"merId":"898111980110147",
					"orderId":"20170209152703",
					"queryId":"201702091527037941308",
					"respCode":"00",
					"respMsg":"success",
					"settleAmt":"1",
					"settleCurrencyCode":"156",
					"settleDate":"0209",
					"signMethod":"01",
					"signature":"Qm7h8t2y9K6r17js1fOxTsbSsgcCyQ3HQvJXF4aBd0AOXSyJ\/WjJdpWi18JMmBRbKX2yy0phZS5dR40d6tm1aJ6KU8szI27vvNYG7ZvhrHi86EPco1XZ5Y2nr59cPjYXYBuiOLPlTDzgqWAJyyrH7M7BOcpK1Vvf1FRC51fk39MVTMjKczmd8OB2amMZboDO\/rtYegHLYYOsUh\/JZNPjwVhVCDZkOO66FGEpGTe4Ece7poQ6zAAace33wC3K4w\/17jboHQ\/7pgRQHTKY2NeS+2c62\/G49iYlhfcukejec89mWsqFohT2yUO14Hd8mkHreSXrifZ8Ss+oKuexRZQM5Q==",
					"traceNo":"794130",
					"traceTime":"0209152703",
					"txnAmt":"1",
					"txnSubType":"01",
					"txnTime":"20170209152703",
					"txnType":"01",
					"version":"5.0.0"
		           }';
		$params = json_decode($params,true);
		$unionPay = Yii::$app->unionPay;
		if (isset ( $params['signature'] )) {
			echo $unionPay->validate( $params ) ? '验签成功' : '验签失败';
			$orderId  = $params['orderId']; //其他字段也可用类似方式获取
			$respCode = $params['respCode']; //判断respCode=00或A6即可认为交易成功
		} else {
			echo '签名为空';
		}

	}
	
	
	//银联退货
	public function actionRefund()
	{
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
			'backUrl'   => 'https://test.huobanys.com/userApi/pay_unionpay.php',  //后台通知地址
			
			//TODO 以下信息需要填写
			'orderId' => $_POST["orderId"],	    //商户订单号，8-32位数字字母，不能含“-”或“_”，可以自行定制规则，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
			'merId' => $_POST["merId"],	        //商户代码，请改成自己的测试商户号，此处默认取demo演示页面传递的参数
			'origQryId' => $_POST["origQryId"], //原消费的queryId，可以从查询接口或者通知接口中获取，此处默认取demo演示页面传递的参数
			'txnTime' => $_POST["txnTime"],	    //订单发送时间，格式为YYYYMMDDhhmmss，重新产生，不同于原消费，此处默认取demo演示页面传递的参数
			'txnAmt' => $_POST["txnAmt"],       //交易金额，退货总金额需要小于等于原消费
			// 		'reqReserved' =>'透传信息',            //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据
		);
		$unionPay = Yii::$app->unionPay;
		$sign     = $unionPay->sign($params); // 签名
		if(!$sign){
			return  UResponse::formatData('100', '签名失败!');
		}
		$result_arr = $unionPay::post ( $sign, SDK_FRONT_TRANS_URL);
		if(count($result_arr)<=0) { //没收到200应答的情况
			return  UResponse::formatData('100', '没收到200应答的情况');
		}
		if(!$unionPay->validate( $result_arr )){
			return  UResponse::formatData('100', 'validate fail!');
		}
		if ($result_arr["respCode"] == "00"){
			//交易已受理，等待接收后台通知更新订单状态，如果通知长时间未收到也可发起交易状态查询
			//TODO
			return  UResponse::formatData('0', '退款订单受理成功!');
		} else if ($result_arr["respCode"] == "03"
				|| $result_arr["respCode"] == "04"
				|| $result_arr["respCode"] == "05" ){
			//后续需发起交易状态查询交易确定交易状态
			//TODO
			return  UResponse::formatData('100', '处理超时,请稍后查询!');
		} else {
			//其他应答码做以失败处理
			//TODO
			return  UResponse::formatData('100', '失败:'.$result_arr["respMsg"]);
		}
		
	}
	
}

