<?php
namespace app\common\filters;
use app\common\data\Response as UResponse;
use yii\base\ActionFilter;
use yii\web\Response;
use Yii;

/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class DoctorFilter extends ActionFilter
{
    public $enabled = true;


    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        if (!$this->enabled) {
            return true;
        }
        $response = Yii::$app->getResponse();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $headers = Yii::$app->request->getHeaders();
        $session_key = Yii::$app->getParams->get('session_key');
        if ((!isset($headers['auth']) || !$headers['auth']) && (!$session_key)) {
            $response->data = UResponse::formatData(UResponse::$code['InvalidSessionKey'], "登录会话失败");
            return false;
        }
        if (isset($headers['auth']) && $headers['auth']) {
            $auth = $headers["auth"];
            $session_key = substr($auth, 0, 32);
            $timestamp = substr($auth, 32, 10);

            $str = $session_key . $timestamp;
            $str2 = substr($str, 0, strlen($str) - 4);
            $str3 = substr($str2, 4, strlen($str2));
            $myauth = $str . md5($str3);
            if ($myauth != $auth) {
                $response->data = UResponse::formatData(UResponse::$code['InvalidSessionKey'], "登录会话失败");
                return false;
            }
        } elseif($session_key && CONF_ENV != 'pro_') {

        } else {
            $response->data = UResponse::formatData(UResponse::$code['InvalidSessionKey'], "登录会话失败");
            return false;
        }
        $userInfo = Yii::$app->getModule('doctor')->runAction('usercache/getCacheBySession', ['session'=>$session_key]);
        if (!$userInfo) {
            $response->data = UResponse::formatData(UResponse::$code['InvalidSessionKey'], "登录会话失败");
            return false;
        }
        Yii::$app->runData->set("doctorUserInfo", $userInfo);
        return true;
    }
}