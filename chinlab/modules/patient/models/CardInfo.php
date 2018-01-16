<?php

namespace app\modules\patient\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "card_info".
 *
 * @property integer $card_no
 * @property string $card_alias_no
 * @property integer $goods_id
 * @property string $goods_name
 * @property integer $active_expire_time
 * @property string $goods_small_image
 * @property integer $active_user_id
 * @property string $active_user_name
 * @property integer $active_user_type
 * @property string $goods_service
 * @property integer $apply_user_id
 * @property string $apply_user_name
 * @property integer $service_user_limit
 * @property string $phone_no
 * @property integer $active_status
 * @property integer $active_type
 * @property integer $active_time
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class CardInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_info';
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
            'card_no' => 'Card No',
            'card_alias_no' => 'Card Alias No',
            'goods_id' => 'Goods ID',
            'goods_name' => 'Goods Name',
            'active_expire_time' => 'Active Expire Time',
            'goods_small_image' => 'Goods Small Image',
            'active_user_id' => 'Active User ID',
            'active_user_name' => 'Active User Name',
            'active_user_type' => 'Active User Type',
            'goods_service' => 'Goods Service',
            'apply_user_id' => 'Apply User ID',
            'apply_user_name' => 'Apply User Name',
            'service_user_limit' => 'Service User Limit',
            'phone_no' => 'Phone No',
            'active_status' => 'Active Status',
            'active_type' => 'Active Type',
            'active_time' => 'Active Time',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
