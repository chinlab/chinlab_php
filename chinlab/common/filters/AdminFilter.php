<?php
namespace app\common\filters;
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
class AdminFilter extends  ActionFilter{

    public $enabled = true;

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
   /* public function beforeAction($action)
    {
        if (!$this->enabled) {
            return true;
        }

        $response = Yii::$app->getResponse();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response->data = [
            "name" => "112233",
            "pass" => "askdfk",
        ];
        return false;
    }*/
}
