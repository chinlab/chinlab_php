<?php

namespace app\modules\patient\controllers;

use app\modules\patient\models\Version;
use yii\base\Exception;
use Yii;
use yii\log\Logger;

class VersionController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetbycondition($condition = [])
    {
        if (!$condition) {
            return false;
        }
        try {
            return Version::find()->where($condition)
                ->limit(1)->orderBy('version_time desc')
                ->asArray()->one();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }
}
