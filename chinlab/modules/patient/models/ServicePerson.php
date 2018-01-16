<?php

namespace app\modules\patient\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "tuser_service_person".
 *
 * @property integer $address_id
 * @property integer $user_id
 * @property string $user_name
 * @property integer $user_sex
 * @property integer $user_district_id
 * @property string $user_card_no
 * @property string $user_district_address
 * @property string $user_detail_address
 * @property string $user_phone
 * @property integer $is_default
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class ServicePerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tuser_service_person';
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
            'address_id' => 'Address ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_sex' => 'User Sex',
            'user_district_id' => 'User District ID',
            'user_card_no' => 'User Card No',
            'user_district_address' => 'User District Address',
            'user_detail_address' => 'User Detail Address',
            'user_phone' => 'User Phone',
            'is_default' => 'Is Default',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
