<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 2:34
 */
namespace app\modules\service\commands;

use app\common\controller\Controller;
use Yii;

class TestingController extends Controller
{

    public function actionIndex($message = "aslkdlfsjf")
    {
        echo $message . PHP_EOL;

        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("user/getinfobysession", ['session' => $message]);
        return $result;
    }
}