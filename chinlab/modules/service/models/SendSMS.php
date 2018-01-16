<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */
namespace app\modules\service\models;
use Yii;
use yii\base\Exception;
use app\modules\service\models\CCPRestSDK;

class SendSMS{
	
	//主帐号
	public $accountSid = '8a216da858ce0b3c0158d858552007ae';
	
	//主帐号Token
	public	$accountToken = '935f27ac69d840c9acc4d795224045c4';
	
	//应用Id
	public	$appId = '8a216da858ce0b3c0158dc8136c70a1f';
	
	//请求地址，格式如下，不需要写https://
	public	$serverIP = 'app.cloopen.com';
	
	//请求端口
	public	$serverPort = '8883';
	
	//REST版本号
	public	$softVersion = '2013-12-26';
	
	public  $tempId  =  [
			'sms' => '140010',  //短信模版id
	];
	/**
	 * 发送模板短信
	 * @param to 手机号码集合,用英文逗号分开
	 * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
	 * @param $tempId 模板Id
	 */
	function sendTemplateSMS($to,$datas,$tempId=NULL)
	{  
		if(!$tempId)$tempId = $this->tempId['sms'];
		// 初始化REST SDK
		$rest = new CCPRestSDK($this->serverIP,$this->serverPort,$this->softVersion);
		$rest->setAccount($this->accountSid,$this->accountToken);
		$rest->setAppId($this->appId);
		
		// 发送模板短信
		//echo date("Y-m-d H:i:s") . "Sending TemplateSMS to $to " . PHP_EOL;
		$result = $rest->sendTemplateSMS($to,$datas,$tempId);
		if($result == NULL ) {
			//echo "result error!";
			return false;
		}
		if($result->statusCode!=0) {
			//echo "error code :" . $result->statusCode . PHP_EOL;
			//echo "error msg :" . $result->statusMsg . PHP_EOL;
			//TODO 添加错误处理逻辑
			return false;
		}else{
			//echo "Sendind TemplateSMS success!<br/>";
			// 获取返回信息
			$smsmessage = $result->TemplateSMS;
			//echo "dateCreated:".$smsmessage->dateCreated.PHP_EOL;
			//echo "smsMessageSid:".$smsmessage->smsMessageSid.PHP_EOL;
			//TODO 添加成功处理逻辑
           return true;			
		}
	}
	//Demo调用,参数填入正确后，放开注释可以调用
	//sendTemplateSMS("手机号码","内容数据","模板Id");
	
}

?>
