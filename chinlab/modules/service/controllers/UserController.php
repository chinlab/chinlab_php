<?php

namespace app\modules\service\controllers;

use app\common\application\CardConfig;
use app\common\application\RabbitConfig;
use app\common\application\StateConfig;
use app\common\components\AppRedisKeyMap;
use app\common\data\Encrypt;
use app\common\data\Response as UResponse;
use yii\log\Logger;
use Yii;
use yii\base\Exception;

class UserController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\PatientFilter',
                'only'  => [
                    'getaddresslist', 'deladdress', 'addaddress', 'defaultaddress',
                    'getpersonlist', 'delperson', 'addperson', 'defaultperson',
                    'modifyaddress', 'modifyperson','adduseropinion','getcoupontip',
                    'updatecoupontip','expressinfo',
                ],
            ],
        ];
    }

    /**
     * 地址列表
     */
    public function actionGetaddresslist() {

        $userInfo = Yii::$app->runData->get("userInfo");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("expressaddress/getlist", ['uid' => $userInfo['user_id'], 'page' => 1, 'limit' => 20]);
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 删除地址
     */
    public function actionDeladdress() {

        $id = Yii::$app->getParams->get("id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("expressaddress/updateinfo", ['id' => $id, 'info' => ['is_delete' => 2]]);
        return UResponse::formatData("0", "删除地址成功", (object)[]);
    }


    /**
     * 修改地址
     */
    public function actionModifyaddress() {

        $id = Yii::$app->getParams->get("id");

        if (!$id) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "请提供修改地址ID");
        }
        $modules = Yii::$app->getModule("patient");
        $user_name = Yii::$app->getParams->get("user_name");
        $user_district_id = Yii::$app->getParams->get("user_district_id");
        $user_district_address = Yii::$app->getParams->get("user_district_address");
        $user_detail_address = Yii::$app->getParams->get("user_detail_address");
        $user_phone = Yii::$app->getParams->get("user_phone");
        $user_card_no = Yii::$app->getParams->get("user_card_no");
        $userInfo = Yii::$app->runData->get("userInfo");

        if (!$user_name || !$user_detail_address || !$user_phone) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "姓名，详细地址，手机号必填");
        }
        $insertData = [
            "user_id" => $userInfo['user_id'],
            "user_name" => $user_name,
            "user_district_id" => $user_district_id,
            "user_district_address" => $user_district_address,
            "user_detail_address" => $user_detail_address,
            "user_phone" => $user_phone,
            'user_card_no'=>$user_card_no,
        ];
        $modules->runAction("expressaddress/updateinfo", ['id' => $id, 'info' => $insertData]);
        return UResponse::formatData("0", "修改地址成功", (object)[]);
    }

    /**
     * 添加地址
     */
    public function actionAddaddress() {

        $user_name = Yii::$app->getParams->get("user_name");
        $user_district_id = Yii::$app->getParams->get("user_district_id");
        $user_district_address = Yii::$app->getParams->get("user_district_address");
        $user_detail_address = Yii::$app->getParams->get("user_detail_address");
        $user_phone = Yii::$app->getParams->get("user_phone");
        $user_card_no = Yii::$app->getParams->get("user_card_no");
        $userInfo = Yii::$app->runData->get("userInfo");

        if (!$user_name || !$user_detail_address || !$user_phone || !$user_card_no) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "姓名，详细地址，手机号,身份证号必填");
        }

        $modules = Yii::$app->getModule("patient");
        $count = $modules->runAction("expressaddress/getcount", ['uid' => $userInfo['user_id']]);
        if ($count >= 20) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "地址管理人最多添加20个");
        }

        $insertData = [
            "address_id" => Yii::$app->DBID->getID("db.tuser_express_address"),
            "user_id" => $userInfo['user_id'],
            "user_name" => $user_name,
            "user_district_id" => $user_district_id,
            "user_district_address" => $user_district_address,
            "user_detail_address" => $user_detail_address,
            "user_phone" => $user_phone,
            'user_card_no'=>$user_card_no,
        ];
        $modules->runAction("expressaddress/create", ['info' => $insertData]);
        return UResponse::formatData("0", "添加地址成功", (object)[]);
    }


    /**
     * 设置默认地址
     */
    public function actionDefaultaddress() {
        $id = Yii::$app->getParams->get("id");
        $modules = Yii::$app->getModule("patient");
        $userInfo = Yii::$app->runData->get("userInfo");
        Yii::$app->db->createCommand()->update('tuser_express_address', ['is_default' => CardConfig::NO_DEFAULT], 'user_id=:id', [':id' => $userInfo['user_id']])->execute();
        $result = $modules->runAction("expressaddress/updateinfo", ['id' => $id, 'info' => ['is_default' => CardConfig::IS_DEFAULT]]);
        return UResponse::formatData("0", "设置默认地址成功", (object)[]);
    }

    /**
     * 默认服务人列表
     */
    public function actionGetpersonlist() {
        $userInfo = Yii::$app->runData->get("userInfo");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("userserviceperson/getlist", ['uid' => $userInfo['user_id'], 'page' => 1, 'limit' => 20]);
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 删除服务人
     */
    public function actionDelperson() {
        $id = Yii::$app->getParams->get("id");
        $modules = Yii::$app->getModule("patient");
        $result = $modules->runAction("userserviceperson/updateinfo", ['id' => $id, 'info' => ['is_delete' => 2]]);
        return UResponse::formatData("0", "删除服务人成功", (object)[]);
    }

    /**
     * 添加服务人
     */
    public function actionAddperson() {

        $user_name = Yii::$app->getParams->get("user_name");
        $user_district_id = Yii::$app->getParams->get("user_district_id");
        $user_sex = intval(Yii::$app->getParams->get("user_sex"));
        $user_card_no = Yii::$app->getParams->get("user_card_no");
        $user_district_address = Yii::$app->getParams->get("user_district_address");
        $user_detail_address = Yii::$app->getParams->get("user_detail_address");
        $user_phone = Yii::$app->getParams->get("user_phone");
        $userInfo  = Yii::$app->runData->get("userInfo");
        $user_age   = Yii::$app->getParams->get("user_age");
        $is_default = Yii::$app->getParams->get("is_default");
        $user_medical_no = Yii::$app->getParams->get("user_medical_no");
        if (!$user_name ) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "姓名必填");
        }
        
        $modules = Yii::$app->getModule("patient");
        $count = $modules->runAction("userserviceperson/getcount", ['uid' => $userInfo['user_id']]);
        if ($count >= 7) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "地址管理人最多添加7个");
        }

        $insertData = [
            "address_id" => Yii::$app->DBID->getID("db.tuser_service_person"),
            "user_id" => $userInfo['user_id'],
            "user_name" => $user_name,
            "user_sex" => $user_sex?$user_sex:0,
            "user_card_no" => $user_card_no,
            "user_medical_no" => $user_medical_no,
            "user_district_id" => $user_district_id,
            "user_district_address" => $user_district_address,
            "user_detail_address" => $user_detail_address,
            "user_phone" => $user_phone,
        	"user_age" => $user_age?$user_age:0,
        	"is_default"=>$is_default?$is_default:0,
        ];
        $modules->runAction("userserviceperson/create", ['info' => $insertData]);
        return UResponse::formatData("0", "添加地址成功", (object)[]);
    }

    /**
     * 修改服务人
     */
    public function actionModifyperson() {
        $id = Yii::$app->getParams->get("id");
        $user_name = Yii::$app->getParams->get("user_name");
        $user_district_id = Yii::$app->getParams->get("user_district_id");
        $user_sex = intval(Yii::$app->getParams->get("user_sex"));
        $user_card_no = Yii::$app->getParams->get("user_card_no");
        $user_district_address = Yii::$app->getParams->get("user_district_address");
        $user_detail_address = Yii::$app->getParams->get("user_detail_address");
        $user_phone = Yii::$app->getParams->get("user_phone");
        $userInfo = Yii::$app->runData->get("userInfo");
        $user_age   = Yii::$app->getParams->get("user_age");
        $is_default = Yii::$app->getParams->get("is_default");
        $user_medical_no = Yii::$app->getParams->get("user_medical_no");
        if (!$user_name) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "姓名必填");
        }

        $modules = Yii::$app->getModule("patient");

        $insertData = [
            "user_id" => $userInfo['user_id'],
            "user_name" => $user_name,
            "user_sex" => $user_sex?$user_sex:'0',
            "user_card_no" => $user_card_no?$user_card_no:'0',
            "user_medical_no" => $user_medical_no?$user_medical_no:'0',
            "user_district_id" => $user_district_id?$user_district_id:'0',
            "user_district_address" => $user_district_address?$user_district_address:'',
            "user_detail_address" => $user_detail_address?$user_detail_address:'',
            "user_phone" => $user_phone?$user_phone:'0',
        	"user_age" => $user_age?$user_age:'0',
        	"is_default"=>$is_default?$is_default:0,
        ];
        foreach($insertData as $k=>$v){
            if(!$v){
                unset($insertData[$k]);
            }
        }
        $modules->runAction("userserviceperson/updateinfo", ['id' => $id, 'info' => $insertData]);
        return UResponse::formatData("0", "", (object)[]);
    }


    /**
     * 设置默认服务人
     */
    public function actionDefaultperson() {

        $id = Yii::$app->getParams->get("id");
        $userInfo = Yii::$app->runData->get("userInfo");
        $modules = Yii::$app->getModule("patient");
        Yii::$app->db->createCommand()->update('tuser_service_person', ['is_default' => CardConfig::NO_DEFAULT], 'user_id=:id', [':id' => $userInfo['user_id']])->execute();
        $result = $modules->runAction("userserviceperson/updateinfo", ['id' => $id, 'info' => ['is_default' => CardConfig::IS_DEFAULT]]);
        return UResponse::formatData("0", "设置默认服务人成功", (object)[]);
    }
    
    /**
     *  用户提交意见反馈信息
     */
    public function actionAdduseropinion() {
    	try {
    		$userInfo = Yii::$app->runData->get("userInfo");
    		$data['desc']   = Yii::$app->getParams->get("desc");
    		$images = Yii::$app->getParams->get("images");
    		if($images){
    			$data['images'] = explode(',', $images);
    			$data['images'] = json_encode($data['images']);
    		}
    		if(!$userInfo['user_id']){
    			return UResponse::formatData(UResponse::$code['AccessDeny'], "必填项不能为空");
    		}
    		if(!$images && !$data['desc']){
    			return UResponse::formatData(UResponse::$code['AccessDeny'], "必填项不能为空");
    		}
    		$data['user_id']   = $userInfo['user_id'];
    		$data['user_name'] = '';
    		$data['id'] = Yii::$app->DBID->getID('db.tuser_opinion');
    		$command = Yii::$app->db;
    		$command->createCommand()->insert('tuser_opinion', $data)->execute();
    		return UResponse::formatData("0", '意见反馈成功', $data);
    	} catch (Exception $e) {
    		return UResponse::formatData("0", '意见反馈成功', (object)[]);
    	}
    }
    
    //用户获取优惠卷提示
    public function actionGetcoupontip(){
        $userInfo = Yii::$app->runData->get("userInfo");
        if(isset($userInfo['is_coupon'])){
            $is_coupont = $userInfo['is_coupon'];
        }else{
            $modules = Yii::$app->getModule("patient");
            $user= $modules->runAction("user/getinfobyid", ['id' => $userInfo['user_id']]);
            if(isset($user['is_coupont'])){
                $is_coupont =$user['is_coupon'];
            }else{
                $is_coupont = '0';
            }
        }
        return UResponse::formatData("0", '成功', ['is_coupon'=>$is_coupont]);
    }

    //用户更新优惠卷提示
    public function actionUpdatecoupontip(){
        $userInfo = Yii::$app->runData->get("userInfo");
        Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
            ['id' => $userInfo['user_id'], 'info' => ['is_coupon' => '0']]);
        return UResponse::formatData("0", '成功', ['is_coupon'=> '0']);
    }

    //根据快递单号查询信息
    public function actionExpressinfo()
    {
        $express_id = Yii::$app->getParams->get("express_id");
        if (!$express_id) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "必填项不能为空");
        }
        $expressData = \app\modules\patient\models\OrderAccompanyDetailExpress::find()
            ->where(['express_id' => $express_id])->one();
        if (!$expressData) {
            return UResponse::formatData(UResponse::$code['AccessDeny'], "暂无快递信息!");
        }
        $express = $expressData->express_com;
        $expresssn = $expressData->express_no;
        $url = "https://m.kuaidi100.com/query?type=" . $express . "&postid=" . $expresssn . "&id=1&valicode=&temp=" . time();
        $header = [
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1',
        ];
        $options = [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSLVERSION => 1,
        ];
        $response = Yii::$app->httpClient->getInstance()
            ->setUrl($url)
            ->setFormat('json')
            ->setHeaders($header)
            ->addOptions($options)
            ->send();
        $result = $response->getContent();
        if ($result) {
            $preg = explode("\r\n", $result);
            if (is_array($preg)) {
                $data = json_decode($preg[count($preg) - 1], true);
                if(isset($data['com'])){
                    $express = StateConfig::$express;
                    foreach ($express as $k=>$v){
                        if($v['express_com']==$data['com']){
                            $data['com'] = $v['express_company'];
                        }
                    }
                }
                return $data;
            }
        }
       return  [
            "message"=> "服务器错误",
            "nu"=>"",
            "ischeck"=> "0",
            "condition" =>"",
            "com"  => "yuantong",
            "status"  =>"500",
            "state"  => "0",
            "data"  =>[],
        ];
    }


}