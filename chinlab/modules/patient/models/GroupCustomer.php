<?php

namespace app\modules\patient\models;

use Yii;

/**
 * This is the model class for table "{{%group_customer}}".
 *
 * @property string $customer_id
 * @property string $customer_name
 * @property string $user_id
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class GroupCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_name', 'user_id', 'create_time', 'update_time', 'is_delete'], 'required'],
            [['customer_id', 'user_id', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['customer_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => '主键ID',
            'customer_name' => '客户名称',
            'user_id' => '用户id',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'is_delete' => '1代表未删除，2已经删除',
        ];
    }

}
