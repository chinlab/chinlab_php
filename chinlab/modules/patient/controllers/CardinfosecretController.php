<?php

namespace app\modules\patient\controllers;

use app\modules\patient\models\CardInfoSecret;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class CardinfosecretController extends \app\common\controller\Controller
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
            $customer = CardInfoSecret::findOne($id);
            if (!$customer) {
                return [];
            }
            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    public function actionGetbyalias($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = CardInfoSecret::findOne(['secret_alias_no'=>$id]);
            if (!$customer) {
                return [];
            }
            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }


    public function actionUpdateinfo($id = "",  $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }
            $user = CardInfoSecret::findOne($id);
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
}
