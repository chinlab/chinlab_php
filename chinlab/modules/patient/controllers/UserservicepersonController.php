<?php

namespace app\modules\patient\controllers;
use app\modules\patient\models\ServicePerson;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class UserservicepersonController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetbyid($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = ServicePerson::findOne($id);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetlist($uid, $page, $limit) {

        if (!$uid) {
            return [];
        }
        try {
            return ServicePerson::find()
                ->where(['user_id' => $uid, 'is_delete' => 1])
                ->limit($limit)->offset(($page - 1) * $limit)->orderBy('is_default desc, update_time desc')
                ->asArray()->all();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    public function actionCreate($info)
    {

        try {
            $user = new ServicePerson();
            foreach ($info as $k => $v) {
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

    public function actionGetcount($uid) {

        if (!$uid) {
            return 100;
        }
        try {
            return ServicePerson::find()
                ->where(['user_id' => $uid, 'is_delete' => 1])
                ->asArray()->count();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return 100;
        }
    }

    public function actionUpdateinfo($id = "",  $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }
            $user = ServicePerson::findOne($id);
            if (!$user) {
                return [];
            }
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    public function actionUpdateinfobydefault($uid = "",  $info = []) {

        try {
            if (!$uid || !$info) {
                return [];
            }
            $user = ServicePerson::find()->where(['user_id'=>$uid,'is_default'=>'1'])->one();
            if (!$user) {
                $user = new ServicePerson();
            }
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetdefault($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = ServicePerson::find()->where(['user_id'=>$id,'is_default'=>'1'])->one();
            if (!$customer) {
                return [];
            }
            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

}
