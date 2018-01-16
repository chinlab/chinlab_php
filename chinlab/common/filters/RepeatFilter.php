<?php
namespace app\common\filters;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response as UResponse;
use yii\base\ActionFilter;
use yii\web\Response;
use Yii;

/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class RepeatFilter extends ActionFilter
{
    public $enabled = true;


    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        if (!$this->enabled) {
            return true;
        }

        $route = Yii::$app->urlManager->parseRequest(Yii::$app->getRequest());
        $userInfo = Yii::$app->runData->get('userInfo');
        $checkKey = md5($route[0].$userInfo['user_id']);
        $redis = Yii::$app->redis;
        $response = Yii::$app->getResponse();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $redisKey = AppRedisKeyMap::getUserRepeatKey($checkKey);
        if ($redis->incr($redisKey) > 1) {
            $response->data = UResponse::formatData(UResponse::$code['AccessDeny'], "您的操作过于频繁，请稍候再试");
            return false;
        }
        $redis->expire($redisKey, 3);
        return true;
    }
}