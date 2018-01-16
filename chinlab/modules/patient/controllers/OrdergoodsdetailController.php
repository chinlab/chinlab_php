<?php

namespace app\modules\patient\controllers;

use app\modules\patient\models\OrderGoodsDetail;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class OrdergoodsdetailController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetlistbyids($ids)
    {

        try {
            $result = OrderGoodsDetail::find()
                ->where(['order_id' => $ids])
                ->asArray()->all();
            if (!is_array($result)) {
                $result = [];
            }

            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionCreate($info = []) {

        if (!$info) {
            return [];
        }

        try {
            $user = new OrderGoodsDetail();
            foreach($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
}
