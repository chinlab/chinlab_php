<?php

namespace app\modules\subscriber;
use Yii;
use yii\web\Response;

/**
 * user module definition class
 */
class Subscriber extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\subscriber\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($_SERVER['PWD'])) {
            $route = Yii::$app->urlManager->parseRequest(Yii::$app->getRequest());
            if (count(explode("/", $route[0])) == 3) {
                Yii::$app->response->format = Response::FORMAT_JSON;
            }
        }
        // custom initialization code goes here
    }
}
