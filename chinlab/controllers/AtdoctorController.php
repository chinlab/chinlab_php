<?php
namespace app\controllers;
use app\common\application\StateConfig;
use app\models\ExcelImage;
use app\common\data\Response as UResponse;
use app\modules\doctor\models\Atdoctor;
use app\modules\doctor\models\AtdoctorAuth;
use yii\web\Response;
use Yii;

/**
 *
 */
class AtdoctorController extends BaseController
{

    /**
     * 获取医生列表
     */
    public function actionDoctormanagementlist() {
        Yii::$app->response->format =  Response::FORMAT_JSON;
        $start = Yii::$app->getParams->get("start");
        $length = Yii::$app->getParams->get("length");
        $draw = Yii::$app->getParams->get('draw');

        if (!$draw) {
            $draw = 1;
        }
        if (!$start) {
            $start = 0;
        }
        if (!$length) {
            $length = 10;
        }
        $condition = [];
        //是否认证通过，1通过，默认:0未认证,2,认证失败3:认证中
        $is_auth = Yii::$app->getParams->get('is_authentication');
        if(!$is_auth){
            $condition['at_doctor_info.is_authentication'] = '3';
        }else{
            $condition['at_doctor_info.is_authentication'] = $is_auth;
        }
        $doctor_name = Yii::$app->getParams->get('doctor_name');
        if($doctor_name){
            $condition['at_doctor_info.doctor_name'] = $doctor_name;
        }
        $hospital_name = Yii::$app->getParams->get('hospital_name');
        if($hospital_name){
            $condition['at_doctor_info.hospital_name'] = $hospital_name;
        }
        $user_mobile = Yii::$app->getParams->get('user_mobile');
        if($user_mobile){
            $condition['at_doctor_login.user_mobile'] = $user_mobile;
        }
        $condition["at_doctor_info.is_delete"] = 1;
        $modules = Yii::$app->getModule('doctor');
        $count = $modules->runAction('atdoctor/countByConditionBackend', ['condition' => $condition, 'andCondition' => []]);
        $result = $modules->runAction('atdoctor/getByConditionBackend', ['condition' => $condition, 'andCondition'=> [], 'start' => $start, 'limit' => $length]);
        if($result){
            foreach($result as $key=>$val){
                $result[$key]['doctor_position'] = StateConfig::getDoctorPosition($val['doctor_position']);
                $result[$key]['hospital_level'] = StateConfig::getHospitalLevel($val['hospital_level']);
                $result[$key]['section_name'] = implode(",", UResponse::getDoctorSectionName($val['section_info']));
                unset($result[$key]['section_info'],$result[$key]['hospital_section_info']);
            }
        }
        return [
            "draw"            => intval($draw),
            "recordsTotal"    => intval($count),
            "recordsFiltered" => intval($count),
            "data"            => $result,
        ];
    }


    /**
     * 获取医生审核
     */
    public function actionDoctorexamine() {
        Yii::$app->response->format =  Response::FORMAT_JSON;
        $doctor_id = Yii::$app->getParams->get('doctor_id');
        $is_auth = Yii::$app->getParams->get('is_auth');
        $auth_desc = Yii::$app->getParams->get('auth_desc');
        if(!$doctor_id || !$is_auth ||!$auth_desc){
            return  UResponse::formatData('100', '医生编号或审核状态或医生审核描述不能为空!');
        }
        $doctorInfo  = Atdoctor::findOne(['doctor_id'=>$doctor_id]);
        if(!$doctorInfo){
            return  UResponse::formatData('100', '当前医生不存在!');
        }
        if($doctorInfo['is_authentication'] == $is_auth ){
            return  UResponse::formatData('0', 'success',$doctor_id);
        }
        $authInfo  = AtdoctorAuth::findOne(['doctor_id'=>$doctor_id]);
        if(!$authInfo){
            return  UResponse::formatData('100', '医生没有上传认证信息!');
        }
        $doctorInfo->is_authentication = $is_auth;
        $doctorInfo->update_time  = time();
        $doctorInfo->save();
        if($auth_desc){
            $authInfo->auth_desc = $auth_desc;
            $authInfo->update_time  = time();
            $authInfo->save();
        }
        $user     = Yii::$app->user->getIdentity();
        $user     = $user->toArray();
        $model = new \app\models\OperationLog();
        $model->operation_id      = Yii::$app->DBID->getID('db.order_operation_log');
        $model->manager_id        = $user['id'];
        $model->manager_name      = $user['username'];
        $model->order_id       	  =  $doctor_id;
        $model->operation_model   = '医生审核';
        $model->create_time 	  = time();
        $model->operation_desc    = $auth_desc;
        $model->operation_details = '{}';
        $model->save();
        return  UResponse::formatData('0', 'success',$doctor_id);
    }

}
