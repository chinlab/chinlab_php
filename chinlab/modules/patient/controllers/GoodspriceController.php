<?php

namespace app\modules\patient\controllers;

use app\modules\patient\models\GoodsPrice;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class GoodspriceController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate($info = []) {

        if (!$info) {
            return [];
        }

        try {
            $user = new GoodsPrice();
            foreach($info as $k => $v) {
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
