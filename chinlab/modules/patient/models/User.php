<?php

namespace app\modules\patient\models;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "tuser".
 *
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_pass
 * @property string $user_img
 * @property string $user_mobile
 * @property string $session_key
 * @property string $user_regtime
 * @property integer $role
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tuser';
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
            'user_id' => '主键',
            'user_name' => '账号',
            'user_pass' => '密码',
            'user_img' => '头像',
            'user_mobile' => '手机',
            'session_key' => '登录key',
            'user_regtime' => '注册时间',
            'role' => '用户分类',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'is_delete' => '1 有效 2失效',
        ]; 
    } 
}
