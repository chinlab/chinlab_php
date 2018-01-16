<?php

namespace app\modules\patient\controllers;

use app\common\application\CardConfig;
use app\modules\patient\models\GoodsInfo;
use app\modules\patient\models\GoodsPrice;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class GoodsinfoController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate($info = []) {

        if (!$info) {
            return [];
        }

        try {
            $user = new GoodsInfo();
            foreach($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetlist($condition = [], $andCondition = [], $orderBy = "goods_onsalt_time desc", $page = 1, $limit = 10)
    {

        try {
            $object = GoodsInfo::find();
            if ($condition) {
                $object = $object->where($condition);
            }
            if ($andCondition) {
                foreach ($andCondition as $v) {
                    $object = $object->andWhere($v);
                }
            }
            /*$sql = $object->limit($limit)->offset(($page - 1) * $limit)->orderBy($orderBy)
                ->createCommand()->getRawSql();
            var_dump($sql);die;*/
            $result = $object->limit($limit)->offset(($page - 1) * $limit)->orderBy($orderBy)
                ->asArray()->all();
            if (!is_array($result)) {
                $result = [];
            }
            $ids = [];
            foreach($result as $k => $v) {
                $ids[] = $v['goods_id'];
            }

            $priceResult = GoodsPrice::find()
                ->select(['goods_id', 'original_price', 'now_price', 'freight_price', 'discount'])
                ->where(['goods_id' => $ids])
                ->asArray()->all();
            $price = [];
            foreach($priceResult as $k => $v) {
                $price[$v['goods_id']] = $v;
            }

            foreach($result as $k => $v) {
                $result[$k]['goods_image'] = json_decode($result[$k]['goods_image'], true);
                $result[$k]['goods_service'] = json_decode($result[$k]['goods_service'], true);
                if (!is_array($result[$k]['goods_image'])) {
                    $result[$k]['goods_image'] = [];
                }
                if (!is_array($result[$k]['goods_service'])) {
                    $result[$k]['goods_service'] = [];
                }
                $result[$k] = array_merge($price[$v['goods_id']], $result[$k]);
            }

            return $result;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * order 获取订单详情
     * @param $id
     * @return array
     */
    public function actionGetdetailorder($id)
    {
        if (!$id) {
            return [];
        }
        try {
            $customer = GoodsInfo::findOne($id);
            if (!$customer) {
                return [];
            }
            $result = $customer->toArray();
            $priceResult = GoodsPrice::findOne($id);
            if (!$priceResult) {
                return [];
            }
            $price = $priceResult->toArray();
            $keyMap = ['original_price', 'now_price', 'freight_price', 'discount'];
            foreach($keyMap as $k => $v) {
                $result[$v] = $price[$v];
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetdetail($id)
    {
        if (!$id) {
            return [];
        }
        try {
            $customer = GoodsInfo::findOne($id);
            if (!$customer) {
                return [];
            }
            $result = $customer->toArray();
            if ($result) {
                $result['goods_image'] = json_decode($result['goods_image'], true);
                $result['goods_service'] = json_decode($result['goods_service'], true);
                if (!is_array($result['goods_image'])) {
                    $result['goods_image'] = [];
                }
                if (!is_array($result['goods_service'])) {
                    $result['goods_service'] = [];
                }
            }
            foreach($result['goods_service'] as $k => $v) {
                $result['goods_service'][$k]['desc'] = CardConfig::$service["t".$v['id']]['name'];
                $result['goods_service'][$k]['category_type'] = strval(CardConfig::$service["t".$v['id']]['type']);
            }

            $priceResult = GoodsPrice::findOne($id);
            if (!$priceResult) {
                return [];
            }
            $price = $priceResult->toArray();
            $keyMap = ['original_price', 'now_price', 'freight_price', 'discount'];
            foreach($keyMap as $k => $v) {
                $result[$v] = $price[$v];
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
}
