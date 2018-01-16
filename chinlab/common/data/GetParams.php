<?php
namespace app\common\data;
use Yii;
/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class GetParams {

    public function post($name) {

        if (Yii::$app->request->post($name)) {
            return Yii::$app->request->post($name);
        }

        if (Yii::$app->request->get($name)) {
            return Yii::$app->request->get($name);
        }
        return "";
    }
}