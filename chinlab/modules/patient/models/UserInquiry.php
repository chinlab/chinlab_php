<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tuser_inquiry".
 *
 * @property integer $inquiry_id
 * @property integer $user_id
 * @property string $inquiry_time
 * @property string $inquiry_name
 * @property string $inquiry_gender
 * @property string $inquiry_phone
 * @property integer $inquiry_age
 * @property string $disease_name
 * @property string $disease_des
 * @property integer $inquiry_state
 * @property string $inquiry_reanswer
 * @property string $inquiry_retime
 * @property integer $is_delete
 */
class UserInquiry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tuser_inquiry';
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
        return [
            [['user_id', 'inquiry_age', 'inquiry_state', 'is_delete'], 'integer'],
            [['inquiry_time'], 'required'],
            [['inquiry_time', 'inquiry_retime'], 'safe'],
            [['inquiry_reanswer'], 'string'],
            [['inquiry_name'], 'string', 'max' => 24],
            [['inquiry_gender'], 'string', 'max' => 2],
            [['inquiry_phone'], 'string', 'max' => 32],
            [['disease_name'], 'string', 'max' => 64],
            [['disease_des'], 'string', 'max' => 640],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inquiry_id' => '问诊编号',
            'user_id'    => '用户编号',
            'inquiry_time' => '问诊时间',
            'inquiry_name' => '问诊人姓名',
            'inquiry_gender' => '问诊人性别',
            'inquiry_phone' => '问诊人手机号',
            'inquiry_age' => '问诊人年龄',
            'disease_name' => '疾病名称',
            'disease_des' => '疾病描述',
            'inquiry_state' => '回复状态',
            'inquiry_reanswer' => '问诊回复',
            'inquiry_retime' => '回复时间',
            'is_delete' => '订单状态',
        ];
    }
}
