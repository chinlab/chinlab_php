<?php

namespace app\models;
use Yii;
use app\modules\service\models\SendSMS;
use app\common\application\StateConfig;

class PushModel extends \yii\base\Model{
	
	const  PUSH_ORDER  = '1'; //订单信息推送
	const  PUSH_NEWS   = '2'; //新闻推送
	const  PUSH_APP    = '3'; //打开APP 
	
	const  CAN_SMS     = '1'; //可以短信通知
	const  CAN_PUSH    = '1';  //可以推送
	const  IS_VIP      = '12'; //vip服务
	const  IS_VIP_TEMP = '14'; //vip套餐4
	const  IS_ORDER    = '1';  //手术预约
	const  IS_PRACTICE = '5'; //预约诊疗
	const  IS_EXPIRE   = '10000'; //过期订单类型
	const  IS_PAY_ORDER = 'pay';
	const  IS_REPORT_TO_C = '17';
	const  IS_REPORT_TO_B = '18';
	
	const  VIP_TEMP_ID = '145886';//vip陪诊服务 套餐1,2,3
	const  VIP_ID = '145886';//vip套餐4
	const  PRACTICE_TEMP_ID = '145885';//预约服务短信模版
	const  ORDER_TEMP_ID = '145865';//手术预约短信模版
	const  ORDER_EXPIRE_TEMP_ID  = '202483';//临近过期订单短信通知模版
	const  PAY_NOTICE   = '148645';//支付通知模版
	const  REPORT_NOTICE   = '176669';//c端报告解读
	
	
	protected static function temp($type)
	{
		$temp =[
			self::IS_VIP_TEMP => [
				'tempId' =>self::VIP_TEMP_ID,
				'params' =>['2','400-001-2255'],
			],
			self::IS_VIP => [
				'tempId' =>self::VIP_ID,
				'params' =>['2','400-001-2255'],
			],
			self::IS_ORDER 	 => [
				'tempId' =>self::ORDER_TEMP_ID,
				'params' =>['','2','400-001-2255'],
			],
			self::IS_PRACTICE =>[
				'tempId' =>self::PRACTICE_TEMP_ID,
				'params' =>['48','400-001-2255'],
			],
			self::IS_EXPIRE => [
				'tempId' => self::ORDER_EXPIRE_TEMP_ID,
				'params' =>['400-001-2255'],
			],
			self::IS_PAY_ORDER=> [
				'tempId' => self::PAY_NOTICE,
			],
			self::IS_REPORT_TO_C=>[
				'tempId' => self::REPORT_NOTICE,
				'params' =>[],
			],
	    ];
		$key = array_keys($temp);
		return in_array($type,$key)?$temp[$type]:false;
	}

	public static function setContent($orderInfo,$state)
	{
		$stateConfig = StateConfig::getOrderStatus($orderInfo['order_version']);
		if(!isset($stateConfig["ordertype" . $orderInfo['order_type']]['type' .$state]))
		{
			return FALSE;
		}
		$msg    = $stateConfig["ordertype" . $orderInfo['order_type']]['type' .$state];
		//订单修改成功后，推送消息
		$title  = '您的订单'.$msg['name'];
		$body   = $msg['small_tip'];
		$type   = substr($orderInfo['order_id'],0,3)-100;
		$content = [
				'title' => $title,
				'body'  => $body,
				'push_type' => self::PUSH_ORDER,
				'info' => [
					'user_id'     => $orderInfo['user_id'],
					'order_id'    => $orderInfo['order_id'],
					'order_number'=> $orderInfo['order_number'],
					'order_type'  => $type,
					'order_state' => $orderInfo['order_state'],
				],
				'news' => [
					'news_id'     => '',
					'news_title'  => '',
					'news_content'=> '',
					'news_photo'  => '',
					'news_url'    => '',
				],
		];
		//$push_type = ['1'=>'跳转到当前订单','2'=>'跳转到当前新闻','3'=>'打开app不做操作'];
		$content = json_encode($content,256);
		return ['title'=>$title,'body'=>$body,'content'=>$content];
	}
	/**
	 *   单推
	 */
	public static function pushOneByID($uid,$content=NULL,$title=NULL,$body=NULL)
	{
		$content = $content?$content:'您有一条新的提示消息!';
		$title	 = $title?$title:'新的提示消息已来袭';
		$body 	 = $body?$body:'打开我让你好看';
		$push  	 = Yii::$app->getui->Push;
		if(!$uid){
			return false;
		}
		rawurlencode($body);
		rawurlencode($title);
		rawurlencode($content);
		//$push->setCid('477651eebc13be269a59d8b84eb49eb8');
		$push->setAlias($uid);
		$push->setContent($content);
    	$push->setTitle($title);
    	$push->setBody($body);
    	return $push->pushMessageToSingle();
    	
	}
	
	public  static  function sendSms($to,$msg,$orderType)
	{
			$sms   = new SendSMS();
			$data = self::temp($orderType);
			if(!$data)return false;
			return $result  = $sms->sendTemplateSMS($to, $data['params'],$data['tempId']);
	}
	
	public  static  function sendSmsManage($to,$msg)
	{
		$sms   = new SendSMS();
		$data  = self::temp(self::IS_PAY_ORDER);
		if(!$data)return false;
		return $result  = $sms->sendTemplateSMS($to, $msg,$data['tempId']);
	}
	
	/**
	 *  处理格式
	 */
	public static function newsFormat($news=[])
	{
		if(!isset($news['material_id'])||!isset($news['show_type'])||
				!isset($news['title'])||!isset($news['channel_no'])){
			return [];
		}
		if(isset($news['news_photo']) && $news['news_photo']){
			$news['news_photo'] = json_decode($news['news_photo'],true);
		}
		//订单修改成功后，推送消息
		$title  = $news['title'];
		$body   = $news['title'];
		$content = [
				'title' => $title,
				'body'  => $body,
				'push_type' => self::PUSH_NEWS,
				'info' => [
					'user_id'     => '',
					'order_id'    => '',
					'order_number'=> '',
					'order_type'  => '',
					'order_state' => '',
				],
				'news' => $news,
		];
		$content = json_encode($content,256);
		return ['title'=>$title,'body'=>$body,'content'=>$content];
	}
	/**
	 * 
	 * @param string $content
	 * @param string $title
	 * @param string $body
	 */
	public static function pushToApp($content=NULL,$title=NULL,$body=NULL)
	{
		$content = $content?$content:'您有一条新的提示消息!';
		$title	 = $title?$title:'新的提示消息已来袭';
		$body 	 = $body?$body:'打开我让你好看';
		rawurlencode($body);
		rawurlencode($title);
		rawurlencode($content);
		$push  	 = Yii::$app->getui->Push;
		$push->setAlias($uid);
		$push->setContent($content);
		$push->setTitle($title);
		$push->setBody($body);
		return $push->pushMessageToApp();
	}
	
}
