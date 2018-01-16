<?php

namespace app\modules\patient\models;

use Yii;

/**
 * This is the model class for table "pay_multi_backend_refund_log".
 *
 * @property string $refund_log_id
 * @property string $refund_id
 * @property string $refund_money
 * @property string $option_user_id
 * @property string $option_user_name
 * @property string $check_user_id
 * @property string $check_user_name
 * @property string $backend_refund_status
 * @property string $refund_status
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
 * @property string $request_refund_params
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 */
class PayMultiBackendRefundLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_multi_backend_refund_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['refund_log_id', 'refund_id', 'refund_money', 'option_user_id', 'option_user_name', 'check_user_id', 'check_user_name', 'backend_refund_status', 'refund_status', 'pay_id', 'user_id', 'order_id', 'pay_money', 'pay_type', 'pay_status', 'pay_account', 'pay_order_id', 'order_type', 'order_state', 'requestParams', 'request_refund_params', 'create_time', 'update_time', 'is_delete'], 'required'],
            [['refund_log_id', 'refund_id', 'option_user_id', 'check_user_id', 'pay_id', 'user_id', 'order_id', 'pay_type', 'pay_status', 'order_type', 'order_state', 'create_time', 'update_time', 'is_delete'], 'integer'],
            [['requestParams', 'request_refund_params'], 'string'],
            [['refund_money', 'option_user_name', 'check_user_name', 'backend_refund_status', 'refund_status', 'pay_money'], 'string', 'max' => 50],
            [['pay_account', 'pay_order_id'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'refund_log_id' => '退款log ID号',
            'refund_id' => '退款ID号',
            'refund_money' => '退款金额',
            'option_user_id' => '操作人ID',
            'option_user_name' => '操作人姓名',
            'check_user_id' => '审核人ID',
            'check_user_name' => '审核人姓名',
            'backend_refund_status' => '后台申请状态',
            'refund_status' => '是否申请退款成功',
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
            'request_refund_params' => '第三方退款回调参数',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'is_delete' => '1代表未删除，2已经删除',
        ];
    }
}
