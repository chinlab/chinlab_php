<?php

namespace app\modules\service\controllers;

use app\common\application\CardConfig;
use app\common\application\RabbitConfig;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response as UResponse;
use Yii;
use yii\web\Response;

class GoodsController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    /**
     * 获取商品列表
     */
    public function actionGetlist()
    {
        $page       =  intval(Yii::$app->getParams->get("page"));
        $goods_type =  Yii::$app->getParams->get("goods_type");
        if(!$goods_type){
            $goods_type = 1;
        }
        $andCondition = [];
        $orderBy = 'goods_onsalt_time desc';
        $limit = 10;
        if (!$page) {
            $andCondition = ["goods_index_location > 0"];
            $orderBy = 'goods_index_location asc';
            $page = 1;
        }
        /*
        $redis = Yii::$app->redis;
        $listKey = AppRedisKeyMap::getGoodsListKey('goods');
        if ($redis->exists($listKey)) {
            $tmpResult = $redis->hgetall($listKey);
            $result = [];
            foreach ($tmpResult as $k => $v) {
                if ($k % 2 == 1) {
                    $result[$k] = json_decode($v, true);
                    unset($result[$k]['goods_image']);
                    unset($result[$k]['goods_service']);
                }
            }
            usort($result, function($a, $b) {
                if ($a['goods_onsalt_time'] < $b['goods_onsalt_time']) {
                    return true;
                }
                return false;
            });
            return UResponse::formatData("0", "获取列表成功", array_values($result));
        }*/

        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("goodsinfo/getlist", [
            "condition"    => ['is_onsalt' => CardConfig::ONSALT_YES,'goods_type'=>$goods_type],
            "andCondition" => $andCondition,
            "orderBy"      => $orderBy,
            "page"        => $page,
            "limit"        => $limit,
        ]);
        $goods_tag =  CardConfig::$goodsTag;
        foreach ($result as $k => $v) {
            unset($result[$k]['goods_image']);
            unset($result[$k]['goods_service']);
            unset($result[$k]['banner_image']);
            $result[$k]['goods_tag'] = isset($goods_tag['s'.$v['goods_tag']])?$goods_tag['s'.$v['goods_tag']]:'';
        }
        Yii::$app->queue->send(['info' => []], RabbitConfig::GOODS_INFO_CACHE);
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 获取商品详情
     */
    public function actionGetdetail()
    {

        $id = Yii::$app->getParams->get("id");
        $listKey = AppRedisKeyMap::getGoodsListKey('goods');
        $redis = Yii::$app->redis;
        $result = $redis->hget($listKey, $id);
        if (!$result) {
            $modules = Yii::$app->getModule("patient");
            $result = $modules->runAction("goodsinfo/getdetail", ['id' => $id]);
            Yii::$app->queue->send(['info' => []], RabbitConfig::GOODS_INFO_CACHE);
            return UResponse::formatData("0", "获取详情成功", $result);
        }
        $result = json_decode($result, true);
        if (!is_array($result['goods_image'])) {
            $result['goods_image'] = [];
        }
        if (isset($result['banner_image'])) {
            $result['banner_image'] = json_decode($result['banner_image'],true);
        }
        if (!is_array($result['goods_service'])) {
            $result['goods_service'] = [];
        }
        foreach($result['goods_service'] as $k => $v) {
            $result['goods_service'][$k]['desc'] = CardConfig::$service["t".$v['id']]['name'];
            $result['goods_service'][$k]['category_type'] = strval(CardConfig::$service["t".$v['id']]['type']);
        }
        return  UResponse::formatData("0", "获取详情成功", $result);
    }

    /**
     * 详情页面
     * @return string
     */
    public function actionDetail()
    {
        $id = Yii::$app->getParams->get("id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("goodsinfo/getdetail", ['id' => $id]);
        Yii::$app->response->format = Response::FORMAT_HTML;
        if (!$result) {
            return "";
        }
        return $this->renderPartial("detail", ["detailInfo" => $result]);
    }

    /**
     * 商品介绍页面
     * @return string
     */
    public function actionInfo()
    {

        $id = Yii::$app->getParams->get("id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("goodsinfo/getdetail", ['id' => $id]);
        Yii::$app->response->format = Response::FORMAT_HTML;
        if (!$result) {
            return "";
        }
        if (isset($result['banner_image'])) {
            $result['banner_image'] = json_decode($result['banner_image'],true);
        }else{
            $result['banner_image'] = [];
        }
        return $this->renderPartial("info", ["detailInfo" => $result]);
    }

    /**
     * 获取选项简介
     */
    public function actionTestpage()
    {

        $id = Yii::$app->getParams->get("id");
        $documentRoot = Yii::getAlias('@app');
        $content = file_get_contents($documentRoot . "/common/application/usertest/s" . $id . ".tpl");
        $content = explode("\n", $content);
        Yii::$app->response->format = Response::FORMAT_HTML;
        return $this->renderPartial('testpage', ['content' => $content]);
    }
}