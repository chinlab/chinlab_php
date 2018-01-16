<?php
namespace app\modules\service;
/**
 * service module definition class
 */
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;
use yii\web\Response;

class Service extends BaseModule implements BootstrapInterface
{
    public $controllerNamespace = 'app\modules\service\controllers';

    public function init()
    {
        parent::init();
        if (!isset($_SERVER['PWD'])) {

            $route = Yii::$app->urlManager->parseRequest(Yii::$app->getRequest());
            if (count(explode("/", $route[0])) == 3) {
                Yii::$app->response->format = Response::FORMAT_JSON;
            }
        }
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'app\modules\service\commands';
        }
    }
}
