<?php

namespace app\modules\service\controllers;

use app\common\application\OverseasConfig;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response as UResponse;
use Yii;
use yii\web\Response;

class OverseasgoodinfoController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['getindexhot', 'recommendlist'],
                'duration'   => 3600,
                'variations' => [
                    Yii::$app->getParams->get("page"),
                ],
                'dependency' => [
                    'class'    => 'app\common\components\RedisDependency',
                    'redisKey' => AppRedisKeyMap::getOverseasDependency(),
                ],
            ],
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['getlistbycate'],
                'duration'   => 3600,
                'variations' => [
                    Yii::$app->getParams->get("category_id"),
                    Yii::$app->getParams->get("category_small_id"),
                    Yii::$app->getParams->get("page"),
                ],
                'dependency' => [
                    'class'    => 'app\common\components\RedisDependency',
                    'redisKey' => AppRedisKeyMap::getOverseasDependency(),
                ],
            ],
        ];
    }

    /**
     * 获取服务优势
     */
    public function actionGoodness() {

        return UResponse::formatData("0", "获取列表成功", OverseasConfig::$goodness);
    }

    /**
     * 获取首页热卖产品
     */
    public function actionGetindexhot()
    {
        //todo 加缓存
        $modules = Yii::$app->getModule("patient");
        $page = intval(Yii::$app->getParams->get("page"));
        $limit = 10;
        if (!$page) {
            $page = 1;
            $limit = 6;
        }
        $start = ($page-1)*$limit;
        $result = $modules->runAction("overseasgoodsinfo/getlist", [
            "condition"    => ["is_delete" => 1, 'is_sale' => 1],
            "andCondition" => ["goods_index_location > 0"],
            "orderBy"      => "goods_index_location asc",
            "start"        => $start,
            "limit"        => $limit,
        ]);
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 获取海外医疗类目
     */
    public function actionGetcategory()
    {

        $result = [];
        foreach (OverseasConfig::$bigCategory as $k => $v) {
            if ($v['is_list'] == "1") {
                if ($v['category']) {
                    $v['category'] = '1';
                } else {
                    $v['category'] = '0';
                }
                $result[] = $v;
            }
        }
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 获取推荐项目
     */
    public function actionRecommendlist()
    {

        //todo 加缓存
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("overseasgoodsinfo/getlist", [
            "condition"    => ["is_delete" => 1, "oc_parent_id" => 7, 'is_sale' => 1],
            "andCondition" => [],
            "orderBy"      => "create_time desc",
            "start"        => "1",
            "limit"        => "6",
        ]);
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 获取二级科目类型
     */
    public function actionSmallcategory()
    {

        $categoryId = Yii::$app->getParams->get("category_id");

        $result = [["val" => "0", "name" => "全部"]];
        foreach (OverseasConfig::$bigCategory as $k => $v) {
            if ($v['val'] == $categoryId) {
                foreach ($v['category'] as $item) {
                    $result[] = $item;
                }
            }
        }

        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 根据项目类型获取项目列表
     */
    public function actionGetlistbycate()
    {

        $categoryId = Yii::$app->getParams->get("category_id");
        $categorySmallId = Yii::$app->getParams->get("category_small_id");
        $page = Yii::$app->getParams->get("page");
        if (!$page) {
            $page = 1;
        }
        $condition = ["is_delete" => 1, 'is_sale' => 1];
        if ($categoryId) {
            $condition["oc_parent_id"] = $categoryId;
        }
        if ($categorySmallId) {
            $condition["oc_id"] = $categorySmallId;
        }
        //todo 是否加缓存
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("overseasgoodsinfo/getlist", [
            "condition"    => $condition,
            "andCondition" => [],
            "orderBy"      => "create_time desc",
            "start"        => $page,
            "limit"        => "10",
        ]);
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 详情页面
     * @return string
     */
    public function actionDetail()
    {

        $id = Yii::$app->getParams->get("id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("overseasgoodsinfo/getdetail", ['id' => $id]);
        Yii::$app->response->format = Response::FORMAT_HTML;
        if (!$result) {
            return "";
        }
        $flag = false;
        foreach(OverseasConfig::$bigCategory as $k => $v) {
            if ($v['show_type'] == "1") {
                $flag = true;
            }
        }
        return $this->renderPartial("detail", ["detailInfo" => $result, 'flag' => $flag]);
    }
}