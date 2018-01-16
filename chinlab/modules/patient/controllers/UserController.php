<?php

namespace app\modules\patient\controllers;
use app\modules\patient\models\User;
use yii\base\Exception;
use app\common\components\AppRedisKeyMap;
use yii\web\Response;
use yii\log\Logger;
use Yii;

class UserController extends \app\common\controller\Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateUser($info) {

        try {
            $user = User::findOne(["user_mobile"=>$info['user_mobile']]);
            if (!$user) {
                $user = new User();
            } else {
                //删除旧的sessionKey
                $redisPhoneKey = AppRedisKeyMap::getUserPhoneAliasKey($user->user_mobile);
                $redisSessionKey = AppRedisKeyMap::getUserSessionAliasKey($user->session_key);
                Yii::$app->redis->executeCommand('del', [$redisPhoneKey, $redisSessionKey]);
                $sessionKey = AppRedisKeyMap::getSsdbSessionKey($user->session_key);
                $phoneKey = AppRedisKeyMap::getSsdbPhoneKey($user->user_mobile);
                Yii::$app->ssdb->del($phoneKey);
                Yii::$app->ssdb->del($sessionKey);
            }
            foreach($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            Yii::$app->getModule('patient')->runAction('usercache/setCacheByUserInfo',
                ['userInfo' => $result]);
            return $result;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetInfoByPhone($phone = "") {

        try {
            if (!$phone) {
                return [];
            }
            $customer = User::findOne([
                'user_mobile' => $phone,
            ]);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * 通过session获取用户信息
     * @param string $session
     *
     * @return array
     */
    public function actionGetinfobysession($session = "ksadklf") {

        try {
            if (!$session) {
                return [];
            }
            $customer = User::findOne([
                'session_key' => $session,
            ]);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     *  通过 user_id获取用户信息
     *
     * @return array
     */
    public function actionGetinfobyid($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = User::findOne([
                'user_id' => $id,
            ]);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * 修改用户信息
     * @param string $id
     * @param array $info
     * @return array
     */
    public function actionUpdateuserinfo($id = "",  $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }
            $user = User::findOne(['user_id' => $id]);
            if (!$user) {
                return [];
            }
            //删除旧的sessionKey
            $redisPhoneKey = AppRedisKeyMap::getUserPhoneAliasKey($user->user_mobile);
            $redisSessionKey = AppRedisKeyMap::getUserSessionAliasKey($user->session_key);
            Yii::$app->redis->executeCommand('del', [$redisPhoneKey, $redisSessionKey]);
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            Yii::$app->getModule('patient')->runAction('usercache/setCacheByUserInfo',
                ['userInfo' => $result]);
            return $result;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
    
}
