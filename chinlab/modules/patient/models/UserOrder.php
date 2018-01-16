<?php

namespace app\modules\patient\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tuser_order".
 *
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $hospital_id
 * @property string $order_number
 * @property integer $order_type
 * @property string $order_time
 * @property string $order_name
 * @property integer $order_gender
 * @property string $order_phone
 * @property integer $order_age
 * @property integer $order_city
 * @property string $order_date
 * @property string $disease_name
 * @property string $disease_des
 * @property integer $order_state
 * @property string $order_update_time
 * @property string $order_design
 * @property integer $is_delete
 */
class UserOrder extends \yii\db\ActiveRecord
{
  
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
    public static function tableName() 
    { 
        return 'tuser_order'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['order_id', 'order_number', 'order_type'], 'required'],
            [['order_id', 'user_id', 'hospital_id', 'order_type', 'order_gender', 'order_age', 'order_city', 'order_state', 'create_time', 'update_time', 'is_delete', 'hospital_section_id', 'section_id', 'doctor_id'], 'integer'],
            [['order_time', 'order_date', 'order_update_time'], 'safe'],
            [['order_number'], 'string', 'max' => 48],
            [['advise_price', 'order_price', 'order_city_name'], 'string', 'max' => 200],
            [['order_name'], 'string', 'max' => 24],
            [['order_phone'], 'string', 'max' => 32],
            [['disease_name', 'order_design'], 'string', 'max' => 64],
            [['disease_des'], 'string', 'max' => 640],
            [['order_file'], 'string', 'max' => 4000],
            [['hospital_name', 'section_name', 'doctor_name'], 'string', 'max' => 256],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'order_id' => '主键',
            'user_id' => '所属用户',
            'hospital_id' => '所属医院',
            'order_number' => '预定订单号码',
            'order_type' => '订单类型',
            'advise_price' => '预约价格',
            'order_price' => '手术价格',
            'order_time' => '下单时间',
            'order_name' => '就诊人姓名',
            'order_gender' => '性别',
            'order_phone' => '就诊人号码',
            'order_age' => '就诊人年龄',
            'order_city' => '城市编码，type=2时必填',
            'order_city_name' => '城市名称',
            'order_date' => '期望就诊时间',
            'disease_name' => '疾病名称',
            'disease_des' => '疾病描述',
            'order_state' => '预约状态',
            'order_update_time' => '预约状态更新时间',
            'order_design' => '预约状态备注',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'order_file' => '图片文件',
            'is_delete' => '1代表未删除，2已经删除',
            'hospital_name' => 'Hospital Name',
            'hospital_section_id' => 'Hospital Section ID',
            'section_id' => 'Section ID',
            'section_name' => 'Section Name',
            'doctor_id' => 'Doctor ID',
            'doctor_name' => 'Doctor Name',
        ]; 
    } 
}
