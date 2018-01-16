<?php
namespace app\modules\patient\data;
use app\common\application\StateConfig;

/**
 * 格式化输入信息
 * User: user
 * Date: 2016/12/22
 * Time: 16:27
 */
class OrderInput {

    /**
     * 新建订单
     * @param $data
     * @return array
     */
    public static function formatData($data) {
        $result = [];
        $doctor_position = isset($data['doctor_position'])&&$data['doctor_position']?$data['doctor_position']:'0';
        list($result['requirement'], $data) = static::formatRequirement($data);
        list($result['disease_desc'], $data) = static::formatDiseaseDesc($data);
        list($result['patient_info'], $data) = static::formatPatientInfo($data);
        list($result['doctor_reply'], $data) = static::formatDoctorInfo($data);
        $money = OrderPrice::get($data['order_type'], $data['order_state'], [], $data['vip_type'], StateConfig::$nowOrderVersion,0,$doctor_position);
        $result['process_record'] = static::formatProcessRecord($data['user_id'], "", $money, $data['order_state']);
        list($result['select_info'], $data) = static::formatSelectInfo($data);
        $result['order_type'] = $data['order_type'];
        $result['order_state'] = $data['order_state'];
        $result['order_id'] = $data['order_id'];
        $result['pay_money'] = $money;
        $result['user_id'] = $data['user_id'];
        $result['can_pay'] = $result['pay_money'] > 0 ? 1 : 0;
        $result['is_free']   = isset($data['is_free'])?$data['is_free']:"1";
        $result['doctor_id'] = isset($data['doctor_id'])?$data['doctor_id']:"0";
        $result['is_reply']  = isset($data['is_reply'])?$data['is_reply']:"1";
        return $result;
    }

    /**
     * 后台设置价格
     * @param $optionId
     * @param $optionName
     * @param $optionMoney
     * @param $message
     * @param $visitTime
     * @param int $optionType
     * @return string
     */
    public static function getSetPriceRecord($optionId, $optionName, $optionMoney, $message = '', $visitTime = 0, $optionType = 2) {

        return static::formatProcessRecord($optionId, $optionName, $optionMoney, "100", $message, $visitTime, $optionType);
    }

    /**
     * 设置就诊时间
     * @param $optionId
     * @param $optionName
     * @param $optionMoney
     * @param $message
     * @param $visitTime
     * @param int $optionType
     * @return string
     */
    public static function getSetTimeRecord($optionId, $optionName, $optionMoney, $message = '', $visitTime = 0, $optionType = 2) {

        return static::formatProcessRecord($optionId, $optionName, $optionMoney, "200", $message, $visitTime, $optionType);
    }

    /**
     * 记录订单取消前的状态
     * @param $status
     */
    public static function formatStatusRecord($status) {
        return json_encode(['tlast' => $status]);
    }

    /**
     * 设置
     */

    /**
     * 设置记录信息
     * @param $optionId
     * @param $optionName
     * @param $optionMoney
     * @param $orderState
     * @param $message
     * @param $visitTime
     * @param int $optionType
     * @return string
     */
    public static function formatProcessRecord($optionId, $optionName, $optionMoney, $orderState, $message = '', $visitTime = 0, $optionType = 1 ) {

        $result = [
            'option_id' => $optionId,
            'option_type' => $optionType,
            'option_name' => $optionName,
            'option_time' => strval(time()),
            'visit_time' => strval($visitTime),
            'option_money' => strval($optionMoney),
            'message' => $message,
        ];
        return json_encode(['t'.$orderState => $result]);
    }

    /**
     * 用户选择信息
     * @param $data
     * @return array
     */
    private static function formatSelectInfo($data) {

        $fields = [
            'vip_type',
        ];
        $result = [];
        foreach($fields as $k => $v) {
            if (isset($data[$v]) && $data[$v]) {
                $result[$v] = $data[$v];
                unset($data[$v]);
            } else {
                $result[$v] = 0;
            }
        }
        return [json_encode($result, JSON_UNESCAPED_UNICODE), $data];
    }

    /**
     * 用户基本信息
     * @param $data
     * @return array
     */
    private static function formatPatientInfo($data) {

        $fields = [
            'id_card',
            'medical_card',
            'order_name',
            'order_gender',
            'order_phone',
            'order_age',
            'order_city',
            'order_city_name',
            'order_date',
            'user_token',
        	'advise_pay_time',
        	'advise_expire_time',
            'taxpayer_ident_no',
        ];
        $result = [];
        foreach($fields as $k => $v) {
            if (isset($data[$v]) && $data[$v]) {
                $result[$v] = $data[$v];
                unset($data[$v]);
            }
        }
        return [json_encode($result, JSON_UNESCAPED_UNICODE), $data];
    }

    /**
     * 用户疾病描述信息
     * @param $data
     * @return array
     */
    private static function formatDiseaseDesc($data) {

        $fields = [
            'disease_name',
            'disease_des',
            'order_design',
            'order_file',
            'check_organization',
            'report_check_time',
        ];
        $result = [];
        foreach($fields as $k => $v) {
            if (isset($data[$v])) {
                $result[$v] = $data[$v];
                unset($data[$v]);
            }
        }
        return [json_encode($result, JSON_UNESCAPED_UNICODE), $data];
    }

    /**
     * 用户疾病描述信息
     * @param $data
     * @return array
     */
    private static function formatDoctorInfo($data) {

        $fields = [
            'retry_status',
            'retry_info',
            'exception_info',
            'summary_info',
            'advise_info',
            'report_doctor_id',
            'report_doctor_name',
            'is_supply'
        ];
        $result = [];
        foreach($fields as $k => $v) {
            if (isset($data[$v])) {
                $result[$v] = $data[$v];
                unset($data[$v]);
            }
        }
        return [json_encode($result, JSON_UNESCAPED_UNICODE), $data];
    }

    /**
     * 用户需求信息
     * @param $data
     * @return array
     */
    private static function formatRequirement($data) {

        $fields = [
            'hospital_id',
            'hospital_name',
            'hospital_section_id',
            'section_id',
            'section_name',
            'doctor_id',
        	'doctor_img',
            'doctor_name',
            'doctor_token',
            'doctor_position',
            'items_name',
        	'user_img',
        ];
        $exist = ['doctor_id','doctor_position'];
        $result = [];
        foreach($fields as $k => $v) {
            if (isset($data[$v])  && $data[$v]) {
                $result[$v] = $data[$v];
                if (!in_array($v,$exist)){
                	unset($data[$v]);
                }
            }
        }
        return [json_encode($result, JSON_UNESCAPED_UNICODE), $data];
    }
}
