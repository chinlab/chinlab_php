<?php

namespace app\modules\service\controllers;

use Yii;
use app\common\data\Response as UResponse;
use yii\base\Exception;
use app\common\data\Encrypt;
use yii\log\Logger;

class MysettingController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\PatientFilter',
                'only' => ['edituser','userinfo','edituserpass'],
            ],
        ];
    }
    /**
     *  用户信息
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionUserinfo()
    {
        try {
            $userInfo = Yii::$app->runData->get("userInfo");
            $result = Yii::$app->getModule('patient')->runAction('userserviceperson/getdefault',
                ['id' => $userInfo['user_id']]);
            $flag = false;
            //注册一个默认服务人
            if(!$result){
                $flag = true;
            }
            if($flag){
                $person = [
                    'address_id' =>isset($result['address_id'])?$result['address_id']:Yii::$app->DBID->getID("db.tuser_service_person"),
                    'user_id'=>$userInfo['user_id'],
                    'user_name'=>$userInfo['user_name'],
                    'user_sex'=>'0',
                    'user_age'=>'0',
                    'user_district_id'=>'',
                    'user_card_no'=>'',
                    'user_medical_no'=>'0',
                    'user_district_address'=>'',
                    'user_detail_address'=>'',
                    'user_phone' =>$userInfo['user_mobile'],
                    'is_default'=>'1',
                    'is_delete'=>'1',
                ];
                //编辑我的服务人信息
                $result = Yii::$app->getModule('patient')->runAction('userserviceperson/updateinfobydefault', ['uid' => $userInfo['user_id'], 'info' => $person]);
            }
            if ($result) {
                if(isset($result['create_time'])){
                    unset($result['create_time']);
                }
                $result['user_img'] = $userInfo['user_img'];
                $result['session_key'] = $userInfo['session_key'];
                $result['access_token'] = $userInfo['access_token'];
                $result['user_mobile']  = $userInfo['user_mobile'];
                $result['is_coupon'] = $userInfo['is_coupon'];
                return UResponse::formatData("0", "获取信息成功", $result);
            }
            return UResponse::formatData("0", "获取信息成功", (object)[]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取信息成功", (object)[]);
        }
    }
    /**
     * 修改用户信息
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionEdituser()
    {
        try {
            $data = [];
            $person = [];
            $user_name = Yii::$app->getParams->get("user_name");
            if($user_name){
                $data['user_name'] = $user_name;
                $person['user_name'] = $user_name;
            }
            $user_phone = Yii::$app->getParams->get("user_phone");
            if($user_phone){
                $person['user_phone'] = $user_phone;
            }
            $user_sex = Yii::$app->getParams->get("user_sex");
            if($user_sex){
                $person['user_sex'] = $user_sex;
            }
            $user_age = Yii::$app->getParams->get("user_age");
            if($user_age){
                $person['user_age'] = $user_age;
            }
            $user_card_no = Yii::$app->getParams->get("user_card_no");
            if($user_card_no){
                $person['user_card_no'] = $user_card_no;
            }
            $userInfo = Yii::$app->runData->get("userInfo");
            if($data){
                $result = Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
                    ['id' => $userInfo['user_id'], 'info' => $data]);
            }
            if ($person) {
                //编辑我的服务人信息
                $exist = Yii::$app->getModule('patient')->runAction('userserviceperson/getcount', ['uid' => $userInfo['user_id']]);
                if($exist){
                    $person['user_id'] = $userInfo['user_id'];
                    $person['is_default'] = '1';
                    Yii::$app->getModule('patient')->runAction('userserviceperson/updateinfobydefault', ['uid' => $userInfo['user_id'], 'info' => $person]);
                }
            }
            return UResponse::formatData("0", "编辑用户资料成功", (object)[]);
        } catch (\Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "编辑用户资料成功", (object)[]);
        }
    }


    /**
     * 修改用户密码
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionEdituserpass()
    {
        try {
            $user_oldpass = Yii::$app->getParams->get("user_oldpass");
            $user_pass = Yii::$app->getParams->get("user_pass");
            if(!$user_pass||!$user_oldpass){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "参数不能为空!");
            }
            $userInfo = Yii::$app->runData->get("userInfo");
            $user_oldpass = Encrypt::mymd5_4($user_oldpass);
            if($user_oldpass !=$userInfo['user_pass'] ){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "原密码错误!");
            }
            $user_pass = Encrypt::mymd5_4($user_pass);
            $userInfo = Yii::$app->runData->get("userInfo");
            $result = Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
                ['id' => $userInfo['user_id'], 'info' =>['user_pass'=>$user_pass]]);
            return UResponse::formatData("0", "修改密码成功", (object)[]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "修改密码成功", (object)[]);
        }
    }

    /**
     * 获取版本信息
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionInquiry()
    {
        try {
            $terminal = trim(Yii::$app->getParams->get("terminal"));
            $version = Yii::$app->getParams->get("version");
            if (!$terminal) {
                return UResponse::formatData("0", "检查更新失败", (object)['list' => []]);
            }
            $params = ["version_device" => $terminal];
            $modules = Yii::$app->getModule("patient");
            $result = $modules->runAction("version/getbycondition", ['condition' => $params]);
            $outputdata = [
                "version_id" => $result["version_id"],
                "version_name" => $result["version_name"],
                "version_code" => $result["version_code"],
                "version_design" => $result["version_design"],
                "version_url" => $result["version_url"],
                "version_force" => $result["version_force"],
            ];
			//if($terminal == 'ios')$outputdata["version_force"] = '0';
			if (APP_VERSION_FORCE && $version < $result["version_name"]) {
				 $outputdata["version_force"] = '1';
			}else{
				$outputdata["version_force"] = '0';
			}
			//1.5.0版本以下强制更新
            $new  = explode('.',$version);
            $old  = explode('.','1.5.0');
            foreach($old as $k=>$v){
                if(isset($old[$k])&&isset($new[$k])){
                    if($old[$k]>$new[$k]){
                        $outputdata["version_force"] = '1';
                        break;
                    }
                }
            }
			if($version=='1.5.0'){
				$outputdata["version_force"] = '1';
			}
			//安卓1.5版本该接口，version参数为空，不需要强制升级
			if(!$version){
				$outputdata["version_force"] = '0';
			}
            return UResponse::formatData("0", "检查更新成功", $outputdata);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return UResponse::formatData("0", "编辑用户资料失败", (object)[]);
        }
    }
}
