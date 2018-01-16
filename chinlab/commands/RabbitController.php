<?php
namespace app\commands;

use yii\console\Controller;
use app\common\application\RabbitConfig;
use yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RabbitController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->getParams();
        if(!isset(RabbitConfig::$configMap[$params[1]])) {
            return 1;
        }
        yii::$app->queueConsumer->consumer($params[1], RabbitConfig::$configMap[$params[1]]);
        return 1;
    }

    public function actionTestmodule() {
        $modules = Yii::$app->getModule("rabbitmq");
        $result = $modules->runAction("testing/index", ["message"=>"luoning"]);
        var_dump($result);
    }

    public function actionSend() {
        Yii::$app->queue->send([
            'info' => ['order_id' => '11233','order_phone' => '13269728109'],
        ], RabbitConfig::ORDER_SMS_EXPIRE_SYS, 2);
    }
}

