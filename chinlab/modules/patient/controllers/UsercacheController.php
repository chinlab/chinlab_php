<?php

namespace app\modules\patient\controllers;
use app\common\components\AppRedisKeyMap;
use Yii;

class UsercacheController extends \app\common\controller\Controller
{

    /**
     * 通过session设置缓存
     * @param $session
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionSetCacheBySession($session) {

        $userInfo = Yii::$app->getModule('patient')->runAction('userssdb/getinfobysession', ['session'=>$session]);
        if (!$userInfo) {
            $userInfo = Yii::$app->getModule('patient')->runAction('user/getinfobysession', ['session'=>$session]);
        }
        if (!$userInfo) {
            return [];
        }
        return $this->setCache($userInfo);
    }

    /**
     * 通过手机号设置缓存
     * @param $phone
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionSetCacheByPhone($phone) {

        $userInfo = Yii::$app->getModule('patient')->runAction('userssdb/getInfoByPhone', ['phone'=>$phone]);
        if (!$userInfo) {
            $userInfo = Yii::$app->getModule('patient')->runAction('user/getInfoByPhone', ['phone'=>$phone]);
        }
        if (!$userInfo) {
            return [];
        }
        return $this->setCache($userInfo);
    }

    /**
     * 通过用户信息设置缓存
     * @param $userInfo
     * @return mixed
     */
    public function actionSetCacheByUserInfo($userInfo) {

        return $this->setCache($userInfo);
    }

    /**
     * 设置缓存
     * @param $userInfo
     * @return mixed
     */
    private function setCache($userInfo) {

        $redisPhoneKey = AppRedisKeyMap::getUserPhoneAliasKey($userInfo['user_mobile']);
        $redisSessionKey = AppRedisKeyMap::getUserSessionAliasKey($userInfo['session_key']);
        $redisUserInfoKey = AppRedisKeyMap::getUserInfoKey($userInfo['user_id']);

        $redis = Yii::$app->redis;
        $redis->executeCommand('mset', [$redisPhoneKey, $userInfo['user_id'], $redisSessionKey, $userInfo['user_mobile'], $redisUserInfoKey, json_encode($userInfo)]);
        $redis->expire($redisPhoneKey, 3600);
        $redis->expire($redisSessionKey, 3600);
        $redis->expire($redisUserInfoKey, 3600);
        return $userInfo;
    }

    /**
     * 通过uid获取用户信息
     * @param $uid
     * @return bool|mixed
     */
    private function getCacheByUid($uid) {

        $redisUserInfoKey = AppRedisKeyMap::getUserInfoKey($uid);
        $redis = Yii::$app->redis;
        $userinfo = $redis->get($redisUserInfoKey);
        $userinfo = json_decode(strval($userinfo), true);
        if (!is_array($userinfo)) {
            return [];
        }
        return $userinfo;
    }

    /**
     * 通过手机号获取用户信息
     * @param $phone
     * @return array|bool|mixed
     */
    public function actionGetCacheByPhone($phone) {
        $redisPhoneKey = AppRedisKeyMap::getUserPhoneAliasKey($phone);
        $redis = Yii::$app->redis;
        $uid = $redis->get($redisPhoneKey);
        if (!$uid) {
            return $this->actionSetCacheByPhone($phone);
        }
        $uid = AppRedisKeyMap::getUserInfoKey($uid);
        $userInfo = $this->getCacheByUid($uid);
        if (!$userInfo) {
            return $this->actionSetCacheByPhone($phone);
        }
        return $userInfo;
    }

    /**
     * 通过session获取用户信息
     * @param $session
     * @return array|bool|mixed
     */
    public function actionGetCacheBySession($session) {

        $redisSessionKey = AppRedisKeyMap::getUserSessionAliasKey($session);
        $redis = Yii::$app->redis;
        $uid = $redis->get($redisSessionKey);
        if (!$uid) {
            return $this->actionSetCacheBySession($session);
        }
        $uid = AppRedisKeyMap::getUserInfoKey($uid);
        $userInfo = $this->getCacheByUid($uid);
        if (!$userInfo) {
            return $this->actionSetCacheBySession($session);
        }
        return $userInfo;
    }
}
