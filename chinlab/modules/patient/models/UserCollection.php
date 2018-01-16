<?php

namespace app\modules\patient\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "tuser_collection".
 *
 * @property integer $tc_user_id
 * @property integer $tc_news_id
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class UserCollection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tuser_collection';
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
            'tc_user_id' => 'Tc User ID',
            'tc_news_id' => 'Tc News ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_delete' => 'Is Delete',
        ];
    }
}
