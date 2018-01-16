<?php

namespace app\modules\patient\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "card_persion_user".
 *
 * @property integer $persion_user_id
 * @property integer $card_no
 * @property integer $persion_type
 * @property string $user_name
 * @property string $user_phone
 * @property string $user_card_no
 * @property integer $user_sex
 * @property integer $user_district_id
 * @property string $user_district_address
 * @property string $user_detail_address
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class CardPersonUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_persion_user';
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
            'persion_user_id' => 'Persion User ID',
            'card_no' => 'Card No',
            'persion_type' => 'Persion Type',
            'user_name' => 'User Name',
            'user_phone' => 'User Phone',
            'user_card_no' => 'User Card No',
            'user_sex' => 'User Sex',
            'user_district_id' => 'User District ID',
            'user_district_address' => 'User District Address',
            'user_detail_address' => 'User Detail Address',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
