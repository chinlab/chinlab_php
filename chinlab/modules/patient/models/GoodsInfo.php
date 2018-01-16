<?php

namespace app\modules\patient\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "goods_info".
 *
 * @property integer $goods_id
 * @property string $goods_name
 * @property integer $goods_type
 * @property integer $goods_expire_time
 * @property string $goods_big_image
 * @property string $goods_small_image
 * @property string $goods_image
 * @property integer $goods_amount
 * @property integer $user_id
 * @property integer $user_name
 * @property integer $goods_onsalt_time
 * @property integer $is_onsalt
 * @property string $goods_service
 * @property integer $goods_service_limit
 * @property string $goods_url
 * @property string $detail_url
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class GoodsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_info';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                //'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'goods_name' => '商品名称',
            'goods_type' => '商品类型',
            'goods_expire_time' => '时效',
            'goods_big_image' => '商品大图',
            'goods_small_image' => '商品列表图',
            'goods_image' => '商品展示图片',
            'goods_amount' => '销量',
            'user_id' => '上架商品人员id',
            'user_name' => '上架人用户名',
            'goods_onsalt_time' => '上架时间',
            'is_onsalt' => '是否上架 0 不上架 1 上架',
            'goods_service' => '服务类型：[{\"id\":服务ID, \"count\":\"数量\"},{\"id\":服务ID, \"count\":\"数量\"}]',
            'goods_service_limit' => '限制使用人数',
            'goods_url' => '商品html页面网址',
            'detail_url' => '商品详情html页面网址',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'is_delete' => '1 未删除 2 已删除',
        ];
    }
}
