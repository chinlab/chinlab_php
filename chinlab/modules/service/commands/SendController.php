<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 2:34
 */
namespace app\modules\service\commands;

use app\common\controller\Controller;
use app\modules\service\models\SendSMS;
use Yii;

class SendController extends Controller
{

    //发送短信
    public function actionPhonesend($info = ['message'=>"112233", "telphone"=>"13269721122"])
    {   
    	if(preg_match('~注册~i', $info['message'])){
    		if(preg_match('~[0-9]*\d~', $info['message'],$sub)){
	    		$datas = [$sub[0],'5'];
	    		$to    = $info['telphone'];
	    		$sms   = new SendSMS();
	    		$result  = $sms->sendTemplateSMS($to, $datas);
	    		if($result)return [1];
    		}
    	}
        $uid = 'FLRJ03356';
        $passwd = '123456';
        $message = rawurlencode($info['message']);
        $url = "http://api2.esoftsms.com/sdk/BatchSend.aspx?CorpID={$uid}&Pwd={$passwd}&Mobile={$info['telphone']}&Content={$message}&Cell=&SendTime=";
        $response = Yii::$app->httpClient->getInstance()->setUrl($url)->send();
        echo $response->getContent() . PHP_EOL;
        return [1];
    }
}