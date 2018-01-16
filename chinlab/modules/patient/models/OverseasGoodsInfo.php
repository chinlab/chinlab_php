<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "overseas_goods_info".
 *
 * @property integer $goods_id
 * @property string $goods_name
 * @property integer $is_sale
 * @property string $hospital_name
 * @property string $hospital_desc
 * @property string $goods_tag
 * @property integer $goods_index_location
 * @property string $goods_index_image
 * @property string $banner_image
 * @property string $goods_image
 * @property string $list_image
 * @property integer $oc_id
 * @property string $oc_name
 * @property integer $oc_parent_id
 * @property string $oc_parent_name
 * @property string $sale_price
 * @property string $favoure_price
 * @property string $goods_point
 * @property string $goods_country
 * @property string $share_title
 * @property string $share_desc
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class OverseasGoodsInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'overseas_goods_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
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
    public function attributeLabels()
    {
        return [
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'is_sale' => 'Is Sale',
            'hospital_name' => 'Hospital Name',
            'hospital_desc' => 'Hospital Desc',
            'goods_tag' => 'Goods Tag',
            'goods_index_location' => 'Goods Index Location',
            'goods_index_image' => 'Goods Index Image',
            'banner_image' => 'Banner Image',
            'goods_image' => 'Goods Image',
            'list_image' => 'List Image',
            'oc_id' => 'Oc ID',
            'oc_name' => 'Oc Name',
            'oc_parent_id' => 'Oc Parent ID',
            'oc_parent_name' => 'Oc Parent Name',
            'sale_price' => 'Sale Price',
            'favoure_price' => 'Favoure Price',
            'goods_point' => 'Goods Point',
            'goods_country' => 'Goods Country',
            'share_title' => 'Share Title',
            'share_desc' => 'Share Desc',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
