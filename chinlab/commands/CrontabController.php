<?php
namespace app\commands;

use yii\console\Controller;
use yii;
/**
 * 定时任务
 * User: user
 * Date: 2016/12/29
 * Time: 12:08
 */
class CrontabController extends Controller
{

    /**
     * 定时检查快到期的任务
     * @throws yii\base\InvalidRouteException
     */
    public function actionExpirelist() {
        $modules = Yii::$app->getModule("rabbitmq");
        $modules->runAction("user/findorderlist");
        return 1;
    }

    /**
     * 删除快到期的广告列表
     * @return int
     * @throws yii\base\InvalidRouteException
     */
    public function actionArticleexpirelist() {
        $modules = Yii::$app->getModule("rabbitmq");
        $modules->runAction("user/delcache");
        return 1;
    }

    /**
     * 统计点赞数量
     * @return int
     * @throws yii\base\InvalidRouteException
     */
    public function actionUpdategoodnews() {
        $modules = Yii::$app->getModule("rabbitmq");
        $modules->runAction("news/updategoodnews");
        return 1;
    }
}