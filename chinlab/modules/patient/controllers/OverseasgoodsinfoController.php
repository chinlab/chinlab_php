<?php

namespace app\modules\patient\controllers;

use app\common\components\AppRedisKeyMap;
use app\modules\patient\models\OverseasGoodsInfo;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class OverseasgoodsinfoController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetdetail($id)
    {
        try {

            $redisKey = AppRedisKeyMap::getOverseasDependency();
            $redis = Yii::$app->redis;
            $onlyFlag = intval($redis->get($redisKey));

            $customer = OverseasGoodsInfo::findOne($id);
            if (!$customer) {
                return [];
            }
            $result = $customer->toArray();
            $tmpImage = json_decode($result['goods_image'], true);
            if (!is_array($tmpImage)) {
                $tmpImage = [];
            }
            $result['goods_image'] = $tmpImage;
            $tmpPoint = json_decode($result['goods_point'], true);
            if (!is_array($tmpPoint)) {
                $tmpPoint = [];
            }
            $result['goods_point'] = $tmpPoint;
            $result['detail_url'] = Yii::$app->runData->baseUrl . "/userApi/overseasgoodinfo_detail_{$result['goods_id']}_" . $onlyFlag . ".html";
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    /**
     * condition limit
     */
    public function actionGetlist($condition = [], $andCondition = [], $orderBy = "create_time desc", $start = 1, $limit = 10)
    {

        $redisKey = AppRedisKeyMap::getOverseasDependency();
        $redis = Yii::$app->redis;
        $onlyFlag = intval($redis->get($redisKey));

        try {
            $object = OverseasGoodsInfo::find();
            if ($condition) {
                $object = $object->where($condition);
            }
            if ($andCondition) {
                foreach ($andCondition as $v) {
                    $object = $object->andWhere($v);
                }
            }
            $result = $object->limit($limit)->offset(($start - 1) * $limit)->orderBy($orderBy)
                ->asArray()->all();
            foreach ($result as $k => $v) {
                $tmpImage = json_decode($v['goods_image'], true);
                if (!is_array($tmpImage)) {
                    $tmpImage = [];
                }
                $result[$k]['goods_image'] = $tmpImage;
                $tmpPoint = json_decode($v['goods_point'], true);
                if (!is_array($tmpPoint)) {
                    $tmpPoint = [];
                }
                $result[$k]['goods_point'] = $tmpPoint;
                $result[$k]['detail_url'] = Yii::$app->runData->baseUrl . "/userApi/overseasgoodinfo_detail_{$v['goods_id']}_" . $onlyFlag . ".html";
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }
}
