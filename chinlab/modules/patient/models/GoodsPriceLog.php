<?php

namespace app\modules\patient\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "goods_price_log".
 *
 * @property integer $price_log_id
 * @property integer $goods_id
 * @property string $original_price
 * @property string $now_price
 * @property string $discount
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class GoodsPriceLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_price_log';
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
            'price_log_id' => 'Price Log ID',
            'goods_id' => 'Goods ID',
            'original_price' => 'Original Price',
            'now_price' => 'Now Price',
            'discount' => 'Discount',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
