<?php

namespace app\modules\subscriber\models;

use Yii;

/**
 * This is the model class for table "tb_user".
 *
 * @property string $user_id
 * @property string $user_name
 * @property string $user_passwd
 * @property string $user_image
 * @property integer $country_id
 * @property integer $lang
 * @property string $interest
 * @property integer $user_initlevel
 * @property integer $user_curlevel
 * @property integer $user_targetlevel
 * @property integer $learn_time
 * @property integer $user_age
 * @property integer $user_agent
 * @property string $user_nickname
 * @property integer $create_time
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_name', 'user_passwd', 'create_time'], 'required'],
            [['lang', 'user_initlevel', 'user_curlevel', 'user_targetlevel', 'learn_time', 'user_age', 'user_agent', 'create_time'], 'integer'],
            [['interest'], 'string'],
            [['user_id'], 'unique'],
            [['user_name'], 'string', 'max' => 50],
            [['user_passwd'], 'string', 'max' => 32],
            [['user_image'], 'string', 'max' => 200],
            [['user_nickname'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户id',
            'user_name' => '用户邮箱',
            'user_passwd' => '密码',
            'user_image' => '头像',
            'country_id' => '国家代码',
            'lang' => '母语',
            'interest' => '兴趣',
            'user_initlevel' => '用户初始级别',
            'user_curlevel' => '用户当前级别',
            'user_targetlevel' => '用户目标级别',
            'learn_time' => '用户设置的学习时长',
            'user_age' => '生日',
            'user_agent' => '性别',
            'user_nickname' => '昵称',
            'create_time' => '创建时间',
        ];
    }
}