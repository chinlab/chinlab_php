<?php 
/**
 * @copyright Copyright (c) 2015-2016 getui! Consulting Group LLC
 * @link 
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace  kasole\getui;
use Yii;
use yii\web\Response;
use \Exception;

//header("Content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
require_once(dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
require_once(dirname(__FILE__) . '/' . 'IGt.Batch.php');
require_once(dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');

class Push extends \yii\base\Object{
	//消息模版：
	const TRANSMISSION = 1; //透传功能模板
	const LINK		   = 2; //通知打开链接功能模板
	const NOTIFICATION = 3; //通知透传功能模板
	const NOTYPOPLOAD  = 4; //通知弹框下载功能模板
	
	
	public  $options;
	
	public  $proxy;
	
	private $_igt = NULL;
	
	private $_push = NULL;
	
	private $_template = NULL; //模版
	
	private $_content = '您有一条新的提示消息!';//内容
	private $_title   = '新的提示消息已袭来';//通知栏标题内容
	private $_body    = '打开我让你好看';//通知栏内容
	private $_badge   = 0;     //icon显示数目
	private $_targetList = NULL;
	
	private $_aliasList = NULL;
	
	public $cid = NULL;
	public $alias = NULL;
	
	public function setContent($value)
	{
		$this->_content = $value;
	}
	
	
	public function setTargetList(array $value)
	{
		$this->_targetList = $value;
	}
	
	public function addTargetList($value)
	{
		$this->_targetList[] = $value;
	}
	
	public function setAliasList(array $value)
	{
		$this->_aliasList = $value;
	}
	
	public function addAliasList($value)
	{
		$this->_aliasList[] = $value;
	}
	
	public function setTitle($value)
	{
		$this->_title = $value;
	}
	
	public function setBody($value)
	{
		$this->_body  = $value;
	}
	
	
    public function getPush()
    {
    	if(!is_object($this->_push))
    	{
    		$this->setPush();
    	}
    	return $this->_push;
    }
	
    public function setPush()
    {
    	$this->_push = Yii::$app->getui;
    }
    
	public function setTui()
	{
	   try {
		  $this->_igt = new \IGeTui($this->getHost(),
					$this->getAppkey(), $this->getMastersecret());
		} catch (Exception $e) {
			throw new Exception( " proxy or getui options is not empty! ");
		}
	}
	
	public function getTui()
	{
		if(!is_object($this->_igt)){
			$this->setTui();
		}
		return $this->_igt;
	}
	
	
	public function parare()
	{
		if(!is_object($this->_igt)){
			$this->setTui();
		}
	}
	
	public function setHost($host=NUll)
	{
		$this->options['host'] = $host;
	}
	
	public function getHost()
	{
		return $this->options['host'];
	}
	
	public function setCid($cid=NUll)
	{
		$this->cid = $cid;
	}
	
	public function getCid()
	{
		return $this->cid;
	}
	
	public function setAlias($alias=NUll)
	{
		 $this->alias = $alias;
	}
	
	public function getAlias()
	{
		return $this->alias;
	}

	public function setMastersecret($mastersecret=NUll)
	{
		$this->options['mastersecret'] = $mastersecret;
	}
	
	public function getMastersecret()
	{
		return $this->options['mastersecret'];
	}
	
	public function setDevicetoken($devicetoken=NUll)
	{
		$this->options['devicetoken'] = $devicetoken;
	}
	
	public function getDevicetoken()
	{
		return $this->options['devicetoken'];
	}
	
	public function setAppid($appid=NUll)
	{
		$this->options['appid'] = $appid;
	}
	
	public function getAppid()
	{
		return $this->options['appid'];
	}
	
	
	public function setAppkey($appkey=NUll)
	{
		$this->options['appkey'] = $appkey;
	}
	
	public function getAppkey()
	{
		return $this->options['appkey'];
	}

	
	public function bindAlias()
	{
		$this->parare();
		$alias = $this->getAlias();
		$ret = $this->_igt->bindAlias($this->getAppid(),$alias,$this->getCid());
		var_dump($ret);
	}
	//根据别名获取clientid信息
	public function getClientIdByAlias()
	{
		$this->parare();
		$alias = $this->getAlias();//'10000000010010';
		$ret   = $this->_igt->queryClientId($this->getAppid(),$alias);
		var_dump($ret);
	}
	
	public function getPersonaTags() {
		$this->parare();
		$ret = $this->_igt->getPersonaTags($this->getAppid());
		var_dump($ret);
	}
	
	public function getUserCountByTags($tagList=["伙伴医生用户","北京用户"]) {
		$this->parare();
		$ret = $this->_igt->getUserCountByTags($this->getAppid(), $tagList);
		var_dump($ret);
	}
	
	public function getPushMessageResult(){
	
		//    putenv("gexin_default_domainurl=http://183.129.161.174:8006/apiex.htm");
		$this->parare();
		$ret = $this->_igt->getPushResult("OSA-0522_QZ7nHpBlxF6vrxGaLb1FA3");
		var_dump($ret);
	
		$ret = $this->_igt->queryAppUserDataByDate($this->getAppid(),"20140807");
		var_dump($ret);
	
		$ret = $this->_igt->queryAppPushDataByDate($this->getAppid(),"20140807");
		var_dump($ret);
	}
	

	//用户状态查询
   public function getUserStatus() {
		$this->_igt = $this->getTui();
		$rep = $this->_igt->getClientIdStatus($this->getAppid(),$this->getCid());
		return $rep;
	}
	
	//推送任务停止
	public function stoptask(){
		$this->parare();
		$rep = $this->_igt->stop("OSA-1127_QYZyBzTPWz5ioFAixENzs3");
		var_dump($rep);
		echo ("<br><br>");
	}
	
	//通过服务端设置ClientId的标签
	public function setTag($tagList = ['huobanys','用户端']){
		$this->parare();
		$rep = $this->_igt->setClientTag($this->getAppid(),$this->getCid(),$tagList);
		var_dump($rep);
		echo ("<br><br>");
	}
	
	public function getUserTags() {
		$this->parare();
		$rep = $this->_igt->getUserTags($this->getAppid(),$this->getCid());
		//$rep.connect();
		var_dump($rep);
		echo ("<br><br>");
	}
	
	
	public function chooseTemplate($templateType = self::TRANSMISSION)
	{
		/*
			self::TRANSMISSION; //透传功能模板
			self::LINK;		    //通知打开链接功能模板
			self::NOTIFICATION; //通知透传功能模板
			self::NOTYPOPLOAD;  //通知弹框下载功能模板
			*/
		switch ($templateType) {
			case self::TRANSMISSION:
				$this->_template = $this->IGtTransmissionTemplate();
				break;
			case self::LINK:
				$this->_template = $this->IGtLinkTemplate();
				break;
			case self::NOTIFICATION:
				$this->_template = $this->IGtNotificationTemplate();
				break;
			case self::NOTYPOPLOAD:
				$this->_template = $this->IGtNotyPopLoadTemplate();
				break;
			default:
				$this->_template = $this->IGtTransmissionTemplate();
				break;
		}
	}
	
	
	public function getTemplate()
	{
		$this->chooseTemplate();
		return $this->_template;
	}
	
	
	
	//
	//服务端推送接口，支持三个接口推送
	//1.PushMessageToSingle接口：支持对单个用户进行推送
	//2.PushMessageToList接口：支持对多个用户进行推送，建议为50个用户
	//3.pushMessageToApp接口：对单个应用下的所有用户进行推送，可根据省份，标签，机型过滤推送
	
	//单推接口案例
	public function pushMessageToSingle(){
		$this->parare();
		$template = $this->getTemplate();
		//个推信息体
		$message = new \IGtSingleMessage();
		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600*12*1000);//离线时间
		$message->set_data($template);//设置推送消息类型
		$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
		//接收方
		$target = new \IGtTarget();
		$target->set_appId($this->getAppid());
		if($this->cid){
			$target->set_clientId($this->cid);
		}
		if($this->alias){
			$target->set_alias($this->alias);
		}
		try {
			return  $this->_igt->pushMessageToSingle($message, $target);
		}catch(\RequestException $e){
			$requstId =e.getRequestId();
			return  $this->_igt->pushMessageToSingle($message, $target,$requstId);
		}
	}
	
	
	public function pushMessageToSingleBatch()
	{
		$this->parare();
		putenv("gexin_pushSingleBatch_needAsync=false");
		$batch = new \IGtBatch($this->getAppkey(), $this->_igt);
		$batch->setApiUrl($this->getHost());
		//$this->_igt->connect();
		$template = $this->getTemplate();
		//个推信息体
		$message = new \IGtSingleMessage();
		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(12 * 1000 * 3600);//离线时间
		$message->set_data($template);//设置推送消息类型
	    $message->set_PushNetWorkType(1);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
	
		$target = new \IGtTarget();
		$target->set_appId($this->getAppid());
		$target->set_clientId($this->getCid());
		$batch->add($message, $target);
		try {
			return $batch->submit();
		}catch(Exception $e){
			return $batch->retry();
		}
	}
	
	//多推接口案例
	public function pushMessageToList()
	{
		$this->parare();
		putenv("gexin_pushList_needDetails=true");
		putenv("gexin_pushList_needAsync=true");
		$template = $this->getTemplate();
		//个推信息体
		$message = new \IGtListMessage();
		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600 * 12 * 1000);//离线时间
		$message->set_data($template); //设置推送消息类型
		$message->set_PushNetWorkType(0); //设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
		//    $contentId = $this->_igt->getContentId($message);
		$contentId = $this->_igt->getContentId($message,"伙伴医生用户");	//根据TaskId设置组名，支持下划线，中文，英文，数字
		//接收方
		$targetList = [];
		if($this->_targetList)
		{
			foreach ($this->_targetList as $key => $value){
				$target = 'target_'.$key;
				$target = new \IGtTarget();
				$target->set_appId($this->getAppid());
				$target->set_clientId($value);
				$targetList[] = $target;
			}
		}
		if($this->_aliasList){
			foreach ($this->_aliasList as $key => $value){
				$target = 'target_'.$key;
				$target = new \IGtTarget();
				$target->set_appId($this->getAppid());
				$target->set_alias($value);
				$targetList[] = $target;
			}
		}
		return $this->_igt->pushMessageToList($contentId, $targetList);
	}
	
	//群推接口案例
	public function pushMessageToApp(){
		$this->parare();
		$template = $this->getTemplate();
		//基于应用消息体
		$message = new \IGtAppMessage();
		$message->set_isOffline(true);
		$message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
		$message->set_data($template);
		$appIdList     = array($this->getAppid());
		//$phoneTypeList = array('ANDROID');
		//$provinceList  = array('浙江');
		//$tagList	     = array('haha');
		//用户属性
		//$age = array("0000", "0010");
		//$cdt = new \AppConditions();
		// $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
		// $cdt->addCondition(AppConditions::REGION, $provinceList);
		//$cdt->addCondition(AppConditions::TAG, $tagList);
		//$cdt->addCondition("age", $age);
		$message->set_appIdList($appIdList);
		//$message->set_conditions($cdt->getCondition());
		return  $this->_igt->pushMessageToApp($message,"伙伴医生");
	}
	
	//所有推送接口均支持四个消息模板，依次为通知弹框下载模板，通知链接模板，通知透传模板，透传模板
	//注：IOS离线推送需通过APN进行转发，需填写pushInfo字段，目前仅不支持通知弹框下载功能
	
	public function IGtNotyPopLoadTemplate(){
		$template =  new \IGtNotyPopLoadTemplate();
	
		$template ->set_appId($this->getAppid());//应用appid
		$template ->set_appkey($this->getAppkey());//应用appkey
		//通知栏
		$template ->set_notyTitle("个推");//通知栏标题
		$template ->set_notyContent("个推最新版点击下载");//通知栏内容
		$template ->set_notyIcon("");//通知栏logo
		$template ->set_isBelled(true);//是否响铃
		$template ->set_isVibrationed(true);//是否震动
		$template ->set_isCleared(true);//通知栏是否可清除
		//弹框
		$template ->set_popTitle("弹框标题");//弹框标题
		$template ->set_popContent("弹框内容");//弹框内容
		$template ->set_popImage("");//弹框图片
		$template ->set_popButton1("下载");//左键
		$template ->set_popButton2("取消");//右键
		//下载
		$template ->set_loadIcon("");//弹框图片
		$template ->set_loadTitle("地震速报下载");
		$template ->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
		$template ->set_isAutoInstall(false);
		$template ->set_isActived(true);
		//$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
		return $this->_template = $template;
	}
	
	public function IGtLinkTemplate(){
		$template =  new \IGtLinkTemplate();
		$template ->set_appId($this->getAppid());//应用appid
		$template ->set_appkey($this->getAppkey());//应用appkey
		$template ->set_title("请输入通知标题");//通知栏标题
		$template ->set_text("请输入通知内容");//通知栏内容
		$template ->set_logo("");//通知栏logo
		$template ->set_isRing(true);//是否响铃
		$template ->set_isVibrate(true);//是否震动
		$template ->set_isClearable(true);//通知栏是否可清除
		$template ->set_url("http://www.igetui.com/");//打开连接地址
		//$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
		//iOS推送需要设置的pushInfo字段
		$apn = new IGtAPNPayload();
		$apn->alertMsg = "alertMsg";
		$apn->badge = 1;
		$apn->actionLocKey = "启动";
		$apn->category = "ACTIONABLE";
		$apn->contentAvailable = 1;
		$apn->locKey = "通知栏内容";
		$apn->title = "通知栏标题";
		$apn->titleLocArgs = array("titleLocArgs");
		$apn->titleLocKey = "通知栏标题";
		$apn->body = "body";
		$apn->customMsg = array("payload"=>"payload");
		$apn->launchImage = "launchImage";
		$apn->locArgs = array("locArgs");
		$apn->sound=("test1.wav");;
		$template->set_apnInfo($apn);
		return $this->_template = $template;
	}
	
	public function IGtNotificationTemplate(){
		$template =  new \IGtNotificationTemplate();
		$template->set_appId($this->getAppid());//应用appid
		$template->set_appkey($this->getAppkey());//应用appkey
		$template->set_transmissionType(1);//透传消息类型
		$template->set_transmissionContent("测试离线");//透传内容
		$template->set_title("个推");//通知栏标题
		$template->set_text("个推最新版点击下载");//通知栏内容
		$template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
		$template->set_isRing(true);//是否响铃
		$template->set_isVibrate(true);//是否震动
		$template->set_isClearable(true);//通知栏是否可清除
		//$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
		//iOS推送需要设置的pushInfo字段
		$apn = new IGtAPNPayload();
		$apn->alertMsg = "alertMsg";
		$apn->badge = 11;
		$apn->actionLocKey = "启动";
		$apn->category = "ACTIONABLE";
		$apn->contentAvailable = 1;
		$apn->locKey = "通知栏内容";
		$apn->title = "通知栏标题";
		$apn->titleLocArgs = array("titleLocArgs");
		$apn->titleLocKey = "通知栏标题";
		$apn->body = "body";
		$apn->customMsg = array("payload"=>"payload");
		$apn->launchImage = "launchImage";
		$apn->locArgs = array("locArgs");
		$apn->sound=("test1.wav");;
		$template->set_apnInfo($apn);
		return $this->_template = $template;
	}
	
	public function IGtTransmissionTemplate(){
		$template =  new \IGtTransmissionTemplate();
		$template->set_appId($this->getAppid());//应用appid
		$template->set_appkey($this->getAppkey());//应用appkey
		$template->set_transmissionType(2);//透传消息类型,收到消息是否立即启动应用，1为立即启动，2则广播等待客户端自启动
		$template->set_transmissionContent($this->_content);//透传内容
		//$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
		//APN高级推送
		$apn = new \IGtAPNPayload();
		$alertmsg=new \DictionaryAlertMsg();
		$alertmsg->body=  $this->_body;
		$alertmsg->actionLocKey="ActionLockey";
		$alertmsg->locKey= $this->_body;
		$alertmsg->locArgs = array("locargs");
		//$alertmsg->launchImage="launchimage";
		//IOS8.2 支持
		$alertmsg->title= $this->_title;
		//$alertmsg->titleLocKey="TitleLocKey";
		//$alertmsg->titleLocArgs=array("TitleLocArg");
		$apn->alertMsg=$alertmsg;
		$apn->badge= $this->_badge;
		$apn->sound="";
		$apn->add_customMsg("payload",$this->_content);
		//$apn->contentAvailable=1;
		$apn->category="ACTIONABLE";
		$template->set_apnInfo($apn);
		return $this->_template = $template;
	}
	
}



