<?php

namespace app\modules\patient\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "card_info_secret".
 *
 * @property integer $secret_id
 * @property string $secret_alias_no
 * @property integer $secret_buyer_id
 * @property integer $is_active
 * @property integer $active_type
 * @property string $security_code
 * @property string $secret_phone
 * @property string $secret_phone_secret
 * @property integer $secret_phone_expire
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class CardInfoSecret extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card_info_secret';
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
            'secret_id' => 'Secret ID',
            'secret_alias_no' => 'Secret Alias No',
            'secret_buyer_id' => 'Secret Buyer ID',
            'is_active' => 'Is Active',
            'active_type' => 'Active Type',
            'security_code' => 'Security Code',
            'secret_phone' => 'Secret Phone',
            'secret_phone_secret' => 'Secret Phone Secret',
            'secret_phone_expire' => 'Secret Phone Expire',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
