<?php

namespace app\modules\patient\models;

use Yii;

/**
 * This is the model class for table "order_multi_backend".
 *
 * @property string $order_id
 * @property string $user_id
 * @property string $requirement
 * @property string $disease_desc
 * @property string $patient_info
 * @property string $process_record
 * @property string $select_info
 * @property string $doctor_reply
 * @property integer $order_type
 * @property integer $can_pay
 * @property string $pay_money
 * @property integer $order_state
 * @property integer $order_version
 * @property integer $order_process
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_delete
 * @property integer $is_look
 */
class OrderMultiBackend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_multi_backend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'requirement', 'disease_desc', 'patient_info', 'process_record', 'select_info', 'doctor_reply', 'order_type', 'can_pay', 'pay_money', 'order_state', 'create_time', 'update_time'], 'required'],
            [['order_id', 'user_id', 'order_type', 'can_pay', 'order_state', 'order_version', 'order_process', 'create_time', 'update_time', 'is_delete', 'is_look'], 'integer'],
            [['requirement', 'disease_desc', 'patient_info', 'process_record', 'select_info', 'doctor_reply'], 'string'],
            [['pay_money'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单ID',
            'user_id' => '用户id',
            'requirement' => '用户需求{\"hospital_id\":\"医院ID\",\"hospital_name\":\"医院名称\",\"hospital_section_id\":\"医院科室ID\",\"section_id\":\"科室ID\",\"section_name\":\"科室名称\",\"doctor_id\":\"医生ID\",\"doctor_name\":\"医生姓名\"....}',
            'disease_desc' => '病情描述{\"disease_name\":\"疾病名称\",\"disease_des\":\"疾病描述\",\"order_design\":\"预约备注\",\"order_file\":\"病情图片\" check_organization 检查机构 report_check_time 检查时间',
            'patient_info' => '患者信息{\"id_card\":\"身份证号\",\"order_name\":\"姓名\",\"order_gender\":\"用户性别\",\"order_phone\":\"手机号码\",\"	order_age\":\"年龄\",\"order_city\":\"地区code\",\"order_city_name\":\"地区名称\",\"order_date\":\"期望就诊时间\",\"\"}',
            'process_record' => '{\"t1\"=>{\"option_id\":\"操作者ID\",\"option_type\":\"操作者类别 用户或者后台管理人员\",\"option_name\":\"操作者姓名\",\"option_time\":\"操作时间\",\"option_money\":\"设置费用或者用户支付的费用\"}}',
            'select_info' => '{\"vip_type\":\"用户选择的服务类型\",.....\"}',
            'doctor_reply' => '医生回复 retry_status 是否需要重新上传 retry_info 需要重新上传的图片 exception_info异常信息 summary_info总结信息 advise_info 建议信息 report_doctor_id 解读报告医生ID report_doctor_name 解读报告医生姓名',
            'order_type' => '订单类型',
            'can_pay' => '是否需要支付',
            'pay_money' => '支付金额',
            'order_state' => '订单状态',
            'order_version' => '当前version版本号',
            'order_process' => '订单进行状态0 进行中 1 已完成 2已取消',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'is_delete' => '1代表未删除，2已经删除',
            'is_look' => '1:未被查看;2:已查看',
        ];
    }
}
