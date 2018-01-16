<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tuser_order_comment".
 *
 * @property integer $order_comment_id
 * @property integer $order_id
 * @property integer $hospital_id
 * @property integer $handle
 * @property string $comment
 * @property string $comment_time
 */
class UserOrderComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tuser_order_comment';
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
            [['order_id', 'hospital_id', 'handle'], 'integer'],
            [['comment'], 'string'],
            [['comment_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_comment_id' => 'Order Comment ID',
            'order_id' => 'Order ID',
            'hospital_id' => 'Hospital ID',
            'handle' => 'Handle',
            'comment' => 'Comment',
            'comment_time' => 'Comment Time',
        ];
    }
}
