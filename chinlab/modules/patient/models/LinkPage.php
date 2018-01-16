<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "tlink_page".
 *
 * @property integer $page_id
 * @property string $page_image
 * @property string $page_url
 * @property integer $create_time
 */
class LinkPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tlink_page';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
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
            'page_id' => 'Page ID',
            'page_image' => 'Page Image',
            'page_url' => 'Page Url',
            'create_time' => 'Create Time',
        ];
    }
}
