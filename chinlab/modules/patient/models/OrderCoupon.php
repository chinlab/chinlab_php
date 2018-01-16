<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\patient\behavior\OrderStateUpdateBehavior;

/**
 * This is the model class for table "order_coupon".
 *
 */
class OrderCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_coupon';
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
            'cid' => '主键ID',
            'user_id' => '用户ID',
            'coupon_name' => '优惠卷名称',
            'coupon_type' => '优惠卷类型',
            'order_money' => '优惠卷金额',
            'order_type' => '使用订单类型',
            'order_desc' => '使用订单类型描述',
            'use_rule'   => '使用规则,1:通用2:满减',
            'rule_desc'  => '使用规则描述',
            'expiry_time'=> '有效期',
            'status'     => '1:未使用2:已使用',
            'create_time'=> '创建时间',
            'update_time'=> '修改时间',
        ];
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
            [
                'class' => OrderStateUpdateBehavior::className(),
            ],
        ];
    }
}
