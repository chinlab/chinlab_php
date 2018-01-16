<?php

namespace app\modules\subscriber\controllers;

use app\modules\subscriber\models\User;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class UserController extends \app\common\controller\Controller
{
    /**
     * @param $data
     * @return array
     * @throws \Exception
     *
     * 用户注册
     *
     */
    public function actionCreateUser($info){
        try {

            $user = User::findOne(["user_name"=>$info['user_name']]);

            if (!$user) {
                $user = new User();
            }

            foreach($info as $k => $v) {
                $user->$k = $v;
            }

            $user->save();
            $result = $user->toArray();
            return $result;

        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    /**
     * @param string $user_name
     * @return array
     *
     * 查询邮箱是否存在
     *
     */
    public function actionGetInfoByName($user_name = "") {

        try {
            if (!$user_name) {
                return [];
            }
            $customer = User::findOne([
                'user_name' => $user_name,
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
     * @param string $user_id
     * @return array
     *
     * 通过id查询用户
     *
     */
    public function actionGetInfoById($user_id = "") {

        try {
            if (!$user_id) {
                return [];
            }
            $customer = User::findOne([
                'user_id' => $user_id
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
     * @param $user_id
     * @param $user_passwd
     *
     * @return array
     *
     * 查询原密码是否正确
     *
     */
    public function actionGetUserPasswd($user_id,$user_passwd){
        try {

            if (!$user_id || !$user_passwd) {
                return [];
            }

            $customer = User::findOne([
                'user_id' => $user_id
            ]);

            if (!$customer || $customer['user_passwd'] !== $user_passwd) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return 1;
        }
    }

    /**
     * @param $user_name
     * @return array|null|\yii\db\ActiveRecord
     *
     * 获取用户信息并登录
     *
     */
    public function actionGetUserInfor ($user_name,$user_passwd) {

        try {
            if(!$user_name || !$user_passwd){
                return [];
            }
            return User::find()
                ->where(['user_name' => $user_name,'user_passwd' => $user_passwd])
                ->asArray()->one();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }
    /**
     * @param string $user_id
     * @param array $info
     * @return array
     *
     * 修改用户信息
     *
     */
    public function actionUpdateUserInfo ($user_id, $info = []) {
        try {

            if (!$user_id  || !$info) {
                return [];
            }
            $user = User::findOne(['user_id' => $user_id]);

            if (!$user) {
                return [];
            }
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }

            $user->save();
            $result = $user->toArray();
            return $result;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
}
