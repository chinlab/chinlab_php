<?php

namespace app\modules\patient\controllers;

use app\modules\patient\models\UserOrderComment;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class UserordercommentController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateComment($info = []) {
        if (!$info) {
            return [];
        }
        try {
            $user = new UserOrderComment();
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
