<?php

namespace app\modules\patient;
use Yii;
use yii\web\Response;

/**
 * patient module definition class
 */
class Patient extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\patient\controllers';

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
