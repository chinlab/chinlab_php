<?php

namespace app\modules\patient\models;

use Yii;

/**
 * This is the model class for table "pay_multi_backend".
 *
 * @property string $pay_id
 * @property string $user_id
 * @property string $order_id
 * @property string $pay_money
 * @property integer $pay_type
 * @property integer $pay_status
 * @property string $pay_account
 * @property string $pay_order_id
 * @property integer $order_type
 * @property integer $order_state
 * @property string $requestParams
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class PayMultiBackend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_multi_backend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_id', 'user_id', 'order_id', 'pay_money', 'pay_type', 'pay_status', 'pay_account', 'pay_order_id', 'order_type', 'order_state', 'requestParams', 'create_time', 'update_time', 'is_delete'], 'required'],
            [['pay_id', 'user_id', 'order_id', 'pay_type', 'pay_status', 'order_type', 'order_state', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['requestParams'], 'string'],
            [['pay_money'], 'string', 'max' => 50],
            [['pay_account', 'pay_order_id'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => '支付ID号',
            'user_id' => '用户id',
            'order_id' => '订单ID',
            'pay_money' => '支付订单金额',
            'pay_type' => '1，支付宝 2,微信 3银联',
            'pay_status' => '0 未支付， 1支付失败， 2支付成功',
            'pay_account' => '支付账号',
            'pay_order_id' => '第三方ID',
            'order_type' => '订单类型',
            'order_state' => '订单状态',
            'requestParams' => '第三方回调参数',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'is_delete' => '1代表未删除，2已经删除',
        ];
    }
}
