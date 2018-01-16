<?php

namespace app\modules\patient\controllers;

use app\common\components\AppRedisKeyMap;
use app\common\application\RabbitConfig;
use app\modules\patient\models\User;
use yii\base\Exception;
use yii\web\Response;
use yii\log\Logger;
use Yii;

class UserssdbController extends \app\common\controller\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDeleteUser($info) {

        $sessionKey = AppRedisKeyMap::getSsdbSessionKey($info['session_key']);
        $phoneKey = AppRedisKeyMap::getSsdbPhoneKey($info['user_mobile']);
        $uid = AppRedisKeyMap::getSsdbUidKey($info['user_id']);
        Yii::$app->ssdb->executeCommand('del', [$sessionKey, $phoneKey, $uid]);
    }

    public function actionCreateUser($info)
    {

        $sessionKey = AppRedisKeyMap::getSsdbSessionKey($info['session_key']);
        $phoneKey = AppRedisKeyMap::getSsdbPhoneKey($info['user_mobile']);
        $uid = AppRedisKeyMap::getSsdbUidKey($info['user_id']);
        $ssdb = Yii::$app->ssdb;
        $ssdb->executeCommand('mset', [$sessionKey, $info['user_id'], $phoneKey, $info['user_id'], $uid, json_encode($info)]);
        Yii::$app->queue->send([
            'info' => $info,
        ], RabbitConfig::USER_INFO_UPINSERT);
        Yii::$app->getModule('patient')->runAction('usercache/setCacheByUserInfo',
            ['userInfo' => $info]);
        return $info;
    }

    public function actionGetInfoByPhone($phone = "")
    {

        if (!$phone) {
            return [];
        }
        $phoneKey = AppRedisKeyMap::getSsdbSessionKey($phone);
        $ssdb = Yii::$app->ssdb;
        $uid = $ssdb->get($phoneKey);
        if (!$uid) {
            return [];
        }
        $uid = AppRedisKeyMap::getSsdbUidKey($uid);
        $info = $ssdb->get($uid);
        $info = json_decode(strval($info), true);
        if (!is_array($info)) {
            return [];
        }
        return $info;
    }

    /**
     * 通过session获取用户信息
     * @param string $session
     *
     * @return array
     */
    public function actionGetinfobysession($session = "ksadklf")
    {

        if (!$session) {
            return [];
        }

        $sessionKey = AppRedisKeyMap::getSsdbSessionKey($session);
        $ssdb = Yii::$app->ssdb;
        $uid = $ssdb->get($sessionKey);
        if (!$uid) {
            return [];
        }
        $uid = AppRedisKeyMap::getSsdbUidKey($uid);
        $info = $ssdb->get($uid);
        $info = json_decode(strval($info), true);
        if (!is_array($info)) {
            return [];
        }
        return $info;
    }


    /**
     * 修改用户信息
     * @param string $id
     * @param array $info
     * @return array
     */
    public function actionUpdateuserinfo($id = "", $info = [])
    {
        if (!$id || !$info) {
            return [];
        }
        $uid = AppRedisKeyMap::getSsdbUidKey($id);
        $ssdb = Yii::$app->ssdb;
        $ssdbinfo = $ssdb->get($uid);
        $ssdbinfo = json_decode(strval($ssdbinfo), true);
        if (!$ssdbinfo) {
            return Yii::$app->getModule('patient')->runAction('user/updateuserinfo',
                ['id' => $id, 'info' => $info]);
        }
        //删除旧的sessionKey
        $sessionKey = AppRedisKeyMap::getSsdbSessionKey($ssdbinfo['session_key']);
        $phoneKey = AppRedisKeyMap::getSsdbPhoneKey($ssdbinfo['user_mobile']);
        Yii::$app->ssdb->del($sessionKey);
        Yii::$app->ssdb->del($phoneKey);
        foreach ($info as $k => $v) {
            $ssdbinfo[$k] = $v;
        }
        $sessionKey = AppRedisKeyMap::getSsdbSessionKey($ssdbinfo['session_key']);
        $phoneKey = AppRedisKeyMap::getSsdbPhoneKey($ssdbinfo['user_mobile']);
        $uid = AppRedisKeyMap::getSsdbUidKey($ssdbinfo['user_id']);
        $ssdb = Yii::$app->ssdb;
        $ssdb->executeCommand('mset', [$sessionKey, $ssdbinfo['user_id'], $phoneKey, $ssdbinfo['user_id'], $uid, json_encode($ssdbinfo)]);
        Yii::$app->queue->send([
            'info' => $ssdbinfo,
        ], RabbitConfig::USER_INFO_UPINSERT);
        Yii::$app->getModule('patient')->runAction('usercache/setCacheByUserInfo',
            ['userInfo' => $ssdbinfo]);
        return $ssdbinfo;
    }
}
