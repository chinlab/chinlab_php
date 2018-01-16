<?php

namespace app\modules\patient\controllers;
use app\modules\patient\models\LinkPage;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class LinkpageController extends \app\common\controller\Controller
{
    public function actionCreate($info = []) {
        if (!$info) {
            return [];
        }
        try {
            $user = new LinkPage();
            foreach($info as $k => $v) {
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

    public function actionFindOne() {

        try {

            $result = LinkPage::find()->select(['page_image', 'page_url'])->orderBy('create_time desc')->asArray()->one();
            return $result;
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
}
