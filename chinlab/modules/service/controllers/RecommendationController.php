<?php

namespace app\modules\service\controllers;

use app\common\application\StateConfig;
use app\common\application\VipDescConfig;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response;
use Yii;
use app\common\data\Response as UResponse;
use yii\log\Logger;
use yii\base\Exception;

class RecommendationController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\ArticleDetailFilter',
                'only' => ['getdoctor'],
            ],
        ];
    }

    //广告轮播接口
    public function actionGetadvertisment()
    {

        try {
        	$size = Yii::$app->getParams->get("size");
        	$flag = FALSE;
        	if(empty($size)){
        		$size = 2;
        	}else{
        		$flag = TRUE;
        	}
        	$redis = Yii::$app->redis;
        	$key = 'ad';
        	$adKey = AppRedisKeyMap::getAdTypeKey($key);
        	if($flag){
        		Yii::$app->redis->del($adKey);
        	}
        	if($redis->exists($adKey)){
        		$response = $redis->get($adKey);
        		$response = json_decode($response,true);
        		if(isset($response['list']))
        			return UResponse::formatData("0", "获取广告信息成功", $response);
        	}
            $modules = Yii::$app->getModule("article");
            $result = $modules->runAction("tad/getadvertisment",['size' => $size]);

            $response = ['list' => []];
            foreach ($result as $val) {
                $outputdata = [
                    "ad_id" => $val["ad_id"],
                    "ad_title" => $val["ad_title"],
                    "ad_url" => "http://" . $_SERVER['HTTP_HOST'] . "/fad_info.html?id=" . $val["ad_id"]
                ];
                if ($val["ad_img"]) {
                    $outputdata['ad_img'] = $val["ad_img"];
                }
                if(isset($val["ad_url"]))
                {
                	if(preg_match("~group[0-9]|huobanys~", $val["ad_url"]))
                	{
                		$outputdata["ad_url"] = $val["ad_url"];
                	}
                }
                $response["list"][] = $outputdata;
            }
            if($result){
	            $redis = Yii::$app->redis;
	            $redis->set($adKey,json_encode($response,256));
	            $redis->expire($adKey,3600);
            } 
            return UResponse::formatData("0", "获取广告信息成功", $response);

        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取广告失败", (object)['list' => []]);
        }
    }

    //名医推荐接口
    public function actionGetfamousdoctor()
    {

        try {

            $redisKey = AppRedisKeyMap::getFamousDoctorKey();
            $responseStr = Yii::$app->redis->get($redisKey);
            $response = json_decode(strval($responseStr), true);
            if (is_array($response)) {
                return UResponse::formatData("0", "获取医生信息成功", $response);
            }
            $modules = Yii::$app->getModule("doctor");
            $result = $modules->runAction("doctor/getfamousdoctor");
            $response = ['list' => []];
            foreach ($result as $doctorinfo) {
                $tmpInfo = UResponse::getDoctorSectionName($doctorinfo['section_info']);
                $outputdata = [
                    "doctor_id" => $doctorinfo["doctor_id"],
                    "doctor_name" => $doctorinfo["doctor_name"],
                    "hospital_name" => $doctorinfo["hospital_name"],
                    "doctor_position" => StateConfig::getDoctorPosition($doctorinfo["doctor_position"]),
                    "doctor_des" => $doctorinfo["good_at"], //由医生简介改为医生擅长
                    "doctor_head" => $doctorinfo["doctor_head"],
                    "section_name" => $tmpInfo ? $tmpInfo[0] : "",
                    "pay_money" => $doctorinfo['pay_money'],
                ];
                $response["list"][] = $outputdata;
            }

            Yii::$app->redis->set($redisKey, json_encode($response));
            Yii::$app->redis->expire($redisKey, 3600);

            return UResponse::formatData("0", "获取医生信息成功", $response);

        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取医生信息失败", (object)['list' => []]);
        }
    }

    /**
     * 获取vip服务类型及医院信息类型
     */
    public function actionGetcommondata() {

        $vipInfo = array_values(StateConfig::$priceInfo["ordertype12"]);
        $detailObj = new VipDescConfig();
        foreach($vipInfo as $k => $v) {
            $method = "getType" . $v['id'];
            $vipInfo[$k]['detail_desc'] = $detailObj->$method();
        }
        $hospitalInfo = StateConfig::$districtInfo;
        return UResponse::formatData("0", "获取医生信息失败", (object)[
            'vip' => $vipInfo,
            'hospitalInfo' => $hospitalInfo,
        ]);
    }


    /**
     * 获取vip就医类型
     */
    public function actionGetvipcommondata() {
        $vipInfo = array_values(StateConfig::$priceInfo["ordertype20"]);
        $detailObj = new VipDescConfig();
        foreach($vipInfo as $k => $v) {
            $method = "getType" . $v['id'];
            $vipInfo[$k]['detail_desc'] = $detailObj->$method();
        }
        $doctorposition = StateConfig::$doctorVipPosition;
        return UResponse::formatData("0", "获取医生信息成功!", (object)[
            'vip' => $vipInfo,
            'doctorposition' => $doctorposition,
        ]);
    }

    //健康资讯接口
    public function actionGethealthnews()
    {
        try {
            $page = Yii::$app->getParams->get("page");   //获取变量
            $size = Yii::$app->getParams->get("size");
            $isTop = Yii::$app->getParams->get("is_top");

            if (!$page) {
                $page = 1;
            }
            if (!$size) {
                $size = 10;
            }
            $key = '';
            $condition = ['news_type' => 0];
            if ($isTop !== false) {
            	$key .= $isTop;
            }
            if($isTop == '1'){
            	$condition['is_top'] = $isTop;
            }
            $key .= '.'.$condition['news_type'];
            $key .= '.'.$page.'.'.$size;
            $redis = Yii::$app->redis;
            $newsKey = AppRedisKeyMap::getNewsTypeKey($key);
            if($redis->exists($newsKey)){
            	$response = $redis->get($newsKey);
            	$response = json_decode($response,true);
            	if(isset($response['list']))
            	return UResponse::formatData("0", "获取健康资讯信息成功", $response);
            }
            $modules = Yii::$app->getModule("article");
            $result = $modules->runAction("news/getbycondition", ['condition' => $condition, 'page' => $page, 'limit' => $size]);
            $response = ['list' => []];
            foreach ($result as $k => $doctorinfo) {
                $outputdata = [
                    "news_id" => $doctorinfo["news_id"],
                    "news_title" => $doctorinfo["news_title"],
                    "news_content" => $doctorinfo["news_summary"],
                    "news_url" => "http://" . $_SERVER['HTTP_HOST'] . "/fnews_info.html?id=" . $doctorinfo["news_id"]
                ];
                if ($doctorinfo["news_photo"]) {//判断是否有这张图片
                    $outputdata['news_photo'] = $doctorinfo["news_photo"];
                }
                if(isset($doctorinfo["news_url"]))
                {
                	if(preg_match("~group[0-9]~", $doctorinfo["news_url"]))
                	{
                		$outputdata["news_url"] = $doctorinfo["news_url"];
                	}
                }
                $response["list"][] = $outputdata;
            }
            if ($result) {
            	$redis = Yii::$app->redis;
            	$redis->set($newsKey,json_encode($response,256));
            	$redis->expire($newsKey,3600);
                return UResponse::formatData("0", "获取健康资讯信息成功", $response);
            }
            return UResponse::formatData("0", "获取健康资讯信息失败", $response);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取健康资讯信息失败", (object)['list' => []]);
        }
    }

    public function actionGetsectionlist() {

        $hospitalId = Yii::$app->getParams->get("hospital_id");
        $sectionId = Yii::$app->getParams->get("section_id");
        if (!$hospitalId) {
            return UResponse::formatData("0", "获取医院科室信息失败", (object)['list'=>[]]);
        }

        try {
            $redisKey = AppRedisKeyMap::getHospitalSectionKey($hospitalId);
            $responseStr = Yii::$app->redis->hget($redisKey, $sectionId);
            $response = json_decode(strval($responseStr), true);
            if (is_array($response)) {
                return UResponse::formatData("0", "获取医院信息成功", ['list' => $response]);
            }

            $modules = Yii::$app->getModule('doctor');
            if ($sectionId != 0) {

                $hospitalCondition = [
                    "condition" => "section_parent_id = $sectionId AND hospital_id = $hospitalId",
                ];
                $response = $modules->runAction('hospitalsections/getbycondition', ['condition' => $hospitalCondition,
                    'page' => 1, 'limit' => 1000, 'select' => ['section_id', 'section_name']]);
            } else {
                $hospitalCondition = [
                    "condition" => "hospital_id = $hospitalId",
                ];
                $result = $modules->runAction('hospitalsections/getbycondition', ['condition' => $hospitalCondition,
                    'page' => 1, 'limit' => 1000, 'select' => ['section_parent_id', 'section_parent_name']]);
                $checkId = [];
                $response = [];
                foreach($result as $k => $v) {
                    if (!in_array($v['section_parent_id'], $checkId)) {
                        $response[] = [
                            'section_id' => $v['section_parent_id'],
                            'section_name' => $v['section_parent_name'],
                        ];
                        $checkId[] = $v['section_parent_id'];
                    }
                }
            }
            $existFlag = false;
            if (Yii::$app->redis->exists($redisKey)) {
                $existFlag = true;
            }
            Yii::$app->redis->hset($redisKey, $sectionId, json_encode($response));
            if(!$existFlag) {
                Yii::$app->redis->expire($redisKey, 3600);
            }
            if ($response) {
                return UResponse::formatData("0", "获取医院信息成功", ['list' => $response]);
            }

            return UResponse::formatData("0", "获取医院信息失败", (object)['list' => []]);

        } catch(Exception $e) {
            return UResponse::formatData("0", "获取医院信息失败", (object)['list' => []]);
        }
    }

    //推荐医院接口
    public function actionGethothospital()
    {
        try {
            $page = Yii::$app->getParams->get("page");   //获取变量
            $size = Yii::$app->getParams->get("size");
            $district_id = Yii::$app->getParams->get("district_id");
            if (!$page) {
                $page = 1;
            }
            if (!$size) {
                $size = 10;
            }
            $condition = ["condition" => ['is_delete' => 1]];
            if ($district_id && $district_id != 100000) {
                $condition["match"] = [
                    "sdistrict_id" => rtrim($district_id, '0'),
                ];
            }
            $modules = Yii::$app->getModule('doctor');
            $result = $modules->runAction('hospital/getbycondition', ['condition' => $condition, 'page' => $page, 'limit' => $size]);
            $response = ['list' => []];
            foreach ($result as $k => $doctorinfo) {
                $outputdata = array(
                    "hospital_id" => $doctorinfo["hospital_id"],
                    "hospital_name" => $doctorinfo["hospital_name"],
                    "hospital_level" => StateConfig::getHospitalLevel($doctorinfo["hospital_level"]),
                    "section_num" => $doctorinfo["sections_num"],
                    "doctors_num" => $doctorinfo["doctors_num"]
                );
                if ($doctorinfo["hospital_big_img"]) {//判断是否有这张图片
                    $outputdata['hospital_big_img'] = $doctorinfo["hospital_small_img"];
                }
                $response["list"][] = $outputdata;
            }

            if ($response) {
                return UResponse::formatData("0", "获取医院信息成功", $response);
            }

            return UResponse::formatData("0", "暂无更多", $response);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取健康资讯信息失败", (object)['list' => []]);
        }
    }

    //医院详情接口
    public function actionGethospital()
    {
        try {

            $hospitalId = Yii::$app->getParams->get("hospital_id");   //获取变量

            $modules = Yii::$app->getModule('doctor');

            if (!$hospitalId) {
                return UResponse::formatData("0", "获取健康资讯信息失败", (object)[]);
            }

            $redisKey = AppRedisKeyMap::getHospitalKey($hospitalId);
            $info = Yii::$app->redis->get($redisKey);
            $info = json_decode(strval($info), true);
            if (is_array($info)) {
                return UResponse::formatData("0", "获取医院详情成功", $info);
            }

            $hospitalInfo = $modules->runAction('hospital/getById', ['id' => $hospitalId]);
            if (!$hospitalInfo) {
                return UResponse::formatData("0", "获取健康资讯信息失败", (object)[]);
            }
            $response = array(
                "hospital_id" => strval($hospitalInfo['info']["hospital_id"]),
                "hospital_name" => $hospitalInfo['info']["hospital_name"],
                "hospital_level" => StateConfig::getHospitalLevel($hospitalInfo['info']["hospital_level"]),
                "hospital_des" => $hospitalInfo['info']["hospital_des"],
                "hospital_sections" => strval($hospitalInfo['info']["sections_num"]),
                "hospital_doctors" => strval($hospitalInfo['info']["doctors_num"]),
                "section" => [],
            );
            if ($hospitalInfo['info']['hospital_small_img']) {
                $response['hospital_big_img'] = $hospitalInfo['info']['hospital_big_img'];
            }
            $checkSection = [];
            foreach ($hospitalInfo['item'] as $k => $v) {
                $response['section'][] = [
                    'section_id' => strval($v['hospital_sections_id']),
                    'section_name' => $v['hospital_sections_name'],
                    'parent_id' => strval($v['section_parent_id']),
                ];
                if (!in_array($v['section_parent_name'], $checkSection)) {
                    $response['section'][] = [
                        'section_id' => strval($v['section_parent_id']),
                        'section_name' => $v['section_parent_name'],
                        'parent_id' => strval(0),
                    ];
                }
            }
            Yii::$app->redis->set($redisKey, json_encode($response));
            Yii::$app->redis->expire($redisKey, 3600);
            return UResponse::formatData("0", "获取医院详情成功", $response);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取健康资讯信息失败", (object)[]);
        }
    }

    //医生列表接口
    public function actionGetdoctorslist()
    {
        try {
            $page = Yii::$app->getParams->get("page");   //获取变量
            $size = Yii::$app->getParams->get("size");
            $districtId = Yii::$app->getParams->get("district_id");
            $doctor_sort = Yii::$app->getParams->get("doctor_sort");
            $doctorName = Yii::$app->getParams->get("doctor_name");
            $hospital_id = Yii::$app->getParams->get("hospital_id");
            $sectionName = Yii::$app->getParams->get("section_name");
            $sectionId = Yii::$app->getParams->get("section_id");
            $desease_id = Yii::$app->getParams->get("desease_id");
            if (!$page) {
                $page = 1;
            }
            if (!$size) {
                $size = 10;
            }

            $condition = ["condition" => ['is_delete' => 1], "match" => []];
            if ($districtId) {
                    $condition['match']['sdistrict_id'] = rtrim($districtId, "0");
            } else {
                $condition['match']['expression'] = '@sdistrict_id ("11"|"71")';
            }
            if ($sectionName) {
                $condition['match']['ssection_info'] = $sectionName;
            }
            if ($sectionId) {
                $condition['match']['ssection_info'] = $sectionId;
            }
            if ($hospital_id) {
                $condition['condition']['hospital_id'] = $hospital_id;
            }
            if ($doctorName) {
                $condition["match"]["sdoctor_name"] = $doctorName;
            }
            if ($desease_id) {
                $condition["match"]["scan_disease"] = $desease_id;
            }

            $order = "doctor_id asc";
            if ($doctor_sort == 1) {
                $order = "hospital_level asc";
            } elseif ($doctor_sort == 2) {
                $order = "doctor_position asc";
            }

            $selectField = ["doctor_id", "doctor_name", "hospital_name", "doctor_position", "hospital_level", "doctor_des", "doctor_head"];
            $modules = Yii::$app->getModule("doctor");
            $results = $modules->runAction("doctor/getDoctorByCondition", ['condition' => $condition, 'select' => $selectField,
                'order' => $order, 'page' => $page, 'limit' => $size]);
            if (is_array($results)) { //判断是否为数组
                foreach ($results as $k => $v) {
                    $results[$k]['doctor_position'] = StateConfig::getDoctorPosition($v['doctor_position']);
                    $results[$k]['hospital_level'] = StateConfig::getHospitalLevel($v['hospital_level']);
                }
            }
            if ($results) {
                return UResponse::formatData("0", "获取医生列表成功", ['list' => $results]);
            }
            return UResponse::formatData("0", "获取医生列表失败", (object)['list' => []]);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData("0", "获取医生列表失败", (object)['list' => []]);
        }
    }

    //获取医生详情
    public function actionGetdoctor()
    {
        try {
            $doctorId = Yii::$app->getParams->get("doctor_id");   //获取变量
            $modules = Yii::$app->getModule('doctor');

            if (!$doctorId) {
                return UResponse::formatData("0", "获取医生详情失败", (object)[]);
            }

            $redisKey = AppRedisKeyMap::getDoctorKey($doctorId);
            $info = Yii::$app->redis->get($redisKey);
            $info = json_decode(strval($info), true);
            if (is_array($info)) {
                //return UResponse::formatData("0", "获取医生详情成功", $info);
            }
            $result = $modules->runAction('doctor/getDoctorById', ['id' => $doctorId]);
            if (!$result) {
            	$result = $modules->runAction('doctor/getAtDoctorById', ['id' => $doctorId]);
            	if (!$result) {
            		return UResponse::formatData("0", "获取医生详情失败", (object)[]);
            	}
            }
            $tmpInfo = UResponse::getDoctorSectionName($result['section_info']);

            $userInfo = Yii::$app->runData->get("userInfo");
            $isCollection = 0;
            if ($userInfo) {
                $userCollectionInfo = Yii::$app->getModule('patient')->runAction('userdoctorcollection/getIsCollection', ['userId' => $userInfo['user_id'], 'articleId' => $result['doctor_id']]);
                if ($userCollectionInfo) {
                    $isCollection = 1;
                }
            }

            $response = [
                "doctor_id" => $result['doctor_id'],
                "doctor_name" => $result['doctor_name'],
                "doctor_head" => $result['doctor_head'],
                "doctor_position" => StateConfig::getDoctorPosition($result['doctor_position']),
                "hospital_name" => $result['hospital_name'],
                "section_name" => isset($tmpInfo[0]) ? $tmpInfo[0] : "",
                "good_at" => $result['good_at'],
                "honor" => $result['honor'],
                "doctor_des" => $result['doctor_des'],
                "work_experience" => $result['work_experience'],
                'price' =>  isset($result['price'])?$result['price']:'30',
                'is_collection' => $isCollection,
            ];
            if ($result["doctor_head"]) {//判断是否有这张图片
                $response['doctor_head'] = $result["doctor_head"];
            }
            Yii::$app->redis->set($redisKey, json_encode($response));
            Yii::$app->redis->expire($redisKey, 3600);
            return UResponse::formatData("0", "获取医生详情成功", $response);
        } catch (Exception $e) {

            return UResponse::formatData("0", "获取医生详情失败", (object)[]);
        }
    }

    //获取疾病列表接口
    public function actionGetdeseaselist()
    {

        try {
            $sectionId = Yii::$app->getParams->get("section_id");   //获取变量
            $redisKey = AppRedisKeyMap::getDiseaseKey($sectionId);
            $info = Yii::$app->redis->get($redisKey);
            $info = json_decode(strval($info), true);
            if (is_array($info)) {
                return UResponse::formatData("0", "获取疾病列表成功", ['list' => $info]);
            }
            $modules = Yii::$app->getModule('doctor');
            $result = $modules->runAction('hospitalsections/getById', ['id' => $sectionId]);
            if (!$result || !$result['can_disease']) {
                Yii::$app->redis->set($redisKey, json_encode([]));
                Yii::$app->redis->expire($redisKey, 3600);
                return UResponse::formatData("0", "获取疾病列表失败", (object)['list' => []]);
            }
            $deseaseList = UResponse::getDiseaseList($result['can_disease']);
            Yii::$app->redis->set($redisKey, json_encode($deseaseList));
            Yii::$app->redis->expire($redisKey, 3600);

            return UResponse::formatData("0", "获取疾病列表成功", ['list' => $deseaseList]);
        } catch (Exception $e) {

            return UResponse::formatData("0", "获取疾病列表失败", (object)['list' => []]);
        }
    }

    //获取常见的科室信息
    public function actionGetcommonsection() {

        try {
            $redisKey = AppRedisKeyMap::getCommonSectionKey();
            $info = Yii::$app->redis->get($redisKey);
            $info = json_decode(strval($info), true);
            if (is_array($info)) {
                return UResponse::formatData("0", "获取科室列表成功", ['list' => $info]);
            }

            $modules = Yii::$app->getModule('doctor');
            $result = $modules->runAction('section/getcommon');

            Yii::$app->redis->set($redisKey, json_encode($result));
            Yii::$app->redis->expire($redisKey, 3600);

            return UResponse::formatData("0", "获取科室列表成功", ['list' => $result]);

        } catch (Exception $e) {

            return UResponse::formatData("0", "获取科室列表失败", (object)['list' => []]);
        }
    }

    //根据科室ID搜索医院信息
    public function actionGethospitalinfobysectionid() {

        $sectionId = Yii::$app->getParams->get("section_id");

        if (!$sectionId) {
            return UResponse::formatData("0", "获取医院信息失败", (object)['list' => []]);
        }

        try {
            $hospitalCondition = [
                "condition" => "section_id = $sectionId",
            ];
            $modules = Yii::$app->getModule('doctor');
            $response = $modules->runAction('hospitalsections/getbycondition', ['condition' => $hospitalCondition,
                'page' => 1, 'limit' => 50, 'select'=> ['hospital_sections_id','hospital_id', 'hospital_name']]);
            return UResponse::formatData("0", "获取医院信息成功", ['list' => $response]);

        } catch(Exception $e) {
            return UResponse::formatData("0", "获取医院信息失败", (object)['list' => []]);
        }
    }

    //获取医院科室的详细介绍信息
    public function actionGethospitalsection() {

        $hospitalSectionId = Yii::$app->getParams->get("hospital_sections_id");
        if (!$hospitalSectionId) {
            return UResponse::formatData("0", "获取医院科室信息失败", (object)[]);
        }

        try {

            $redisKey = AppRedisKeyMap::getHospitalSectionDetailKey($hospitalSectionId);
            $info = Yii::$app->redis->get($redisKey);
            $info = json_decode(strval($info), true);
            if (is_array($info)) {
                return UResponse::formatData("0", "获取医院信息成功", $info);
            }

            $modules = Yii::$app->getModule('doctor');
            $result = $modules->runAction('hospitalsections/getHospitalById', ['id' => $hospitalSectionId]);
            $response = [
                "hospital_id"  => strval($result['hospital_id']),
                "hospital_sections_name"  => $result['hospital_sections_name'],
                "hospital_sections_id"  => strval($result['hospital_sections_id']),
                "hospital_name"  => $result['hospital_name'],
                "hospital_sections_place"  => $result['hospital_sections_place'],
                "hospital_sections_chief"  => $result['hospital_sections_chief'],
                "hospital_sections_scale"  => $result['hospital_sections_scale'],
                "hospital_sections_special"  => $result['hospital_sections_special'],
                "hospital_sections_honor"  => $result['hospital_sections_honor'],
                "hospital_small_img"  => $result['hospital_small_img'],
                "hospital_big_img"  => $result['hospital_big_img'],
            ];

            Yii::$app->redis->set($redisKey, json_encode($response));
            Yii::$app->redis->expire($redisKey, 3600);
            return UResponse::formatData("0", "获取医院信息成功", $response);

        } catch(Exception $e) {
            return UResponse::formatData("0", "获取医院信息失败", (object)[]);
        }
    }

    //常见疾病
    public function actionGetcommondesease()
    {

        try {

            $redisKey = AppRedisKeyMap::getCommonDiseaseKey();
            $info = Yii::$app->redis->get($redisKey);
            $info = json_decode(strval($info), true);
            if (is_array($info)) {
                return UResponse::formatData("0", "获取疾病列表成功", ['list' => $info]);
            }

            $modules = Yii::$app->getModule('doctor');
            $result = $modules->runAction('desease/getcommondesease');
            $response = [];
            foreach ($result as $k => $val) {
                $response[] = [
                    "desease_id" => $val["disease_id"],
                    "desease_name" => $val["disease_name"]
                ];
            }

            Yii::$app->redis->set($redisKey, json_encode($response));
            Yii::$app->redis->expire($redisKey, 3600);

            if (!$response) {
                return UResponse::formatData("0", "获取疾病列表失败", (object)['list' => []]);
            }
            return UResponse::formatData("0", "获取疾病列表成功", ['list' => $response]);
        } catch (Exception $e) {

            return UResponse::formatData("0", "获取疾病列表失败", (object)['list' => []]);
        }
    }

    public function actionGetallsections() {

        $sectionId = Yii::$app->getParams->get("section_id");

        try {

            $redisKey = AppRedisKeyMap::getSectionKey();
            $redis = Yii::$app->redis;
            $info = $redis->get($redisKey);
            $info = json_decode($info, true);
            if (!$info) {
                $modules = Yii::$app->getModule('doctor');
                $result = $modules->runAction('section/getallSection');
                $response = [];
                foreach ($result as $k => $val) {
                    if ($val['parent_id'] == 0) {
                        $response[$val['section_id']] = [
                            "section_id" => $val['section_id'],
                            "section_name" => $val['section_name'],
                            "child" => [],
                        ];
                    } else {
                        $response[$val['parent_id']]['child'][] = [
                            "section_id" => $val['section_id'],
                            "section_name" => $val['section_name'],
                        ];
                    }
                }
                $redis->set($redisKey, json_encode($response));
                $redis->expire($redisKey, 3600);
                $info = $response;
            }
            if (!$info) {
                return UResponse::formatData("0", "获取疾病列表失败", (object)['list' => []]);
            }
            if ($sectionId && isset($info[$sectionId])) {
                $info = $info[$sectionId]['child'];
            } else {
                foreach($info as $k => $v) {
                    if (!$info[$k]['child']) {
                        unset($info[$k]['child']);
                        unset($info[$k]);
                    } else {
                        unset($info[$k]['child']);
                    }
                }
                $info = array_values($info);
            }
            return UResponse::formatData("0", "获取疾病列表成功", ['list' => $info]);
        } catch (Exception $e) {

            return UResponse::formatData("0", "获取疾病列表失败", (object)['list' => []]);
        }

    }

    //搜索医生
    public function actionSearchdoctor() {

        $key = Yii::$app->getParams->get("key_name");
        $page = Yii::$app->getParams->get("page");
        $size = Yii::$app->getParams->get("size");
        if (!$page) {
            $page = 1;
        }
        if (!$size) {
            $size = 50;
        }
        $doctorCondition = [
            "condition" => "doctor_id > 0  and is_authentication = 1 ",
            "match" => [],
        ];
        if ($key) {
            $doctorCondition["match"] = ['sdoctor_name' => $key];
        }
        $modules = Yii::$app->getModule('doctor');
        $selectField = ["good_at","doctor_id", "doctor_name", "hospital_name", "section_info", "doctor_position", "hospital_level", "doctor_des", "doctor_head","pay_money"];
        $response = [];
        $response['listdoc'] = $modules->runAction("doctor/getAtDoctorByCondition", ['condition' => $doctorCondition, 'select' => $selectField,
            'order' => 'doctor_id asc', 'page' => $page, 'limit' => $size]);
        foreach($response['listdoc'] as $k => $v) {
            $response['listdoc'][$k]['doctor_position'] = StateConfig::getDoctorPosition($v['doctor_position']);
            $response['listdoc'][$k]['section_name'] = implode(",", Response::getDoctorSectionName($v['section_info']));
            unset($response['listdoc'][$k]['section_info']);
        }
        return UResponse::formatData("0", "获取医生和医院列表成功", $response);
    }

    //查询医院和医生
    public function actionSearchdoctorhospital()
    {

        $key = Yii::$app->getParams->get("key_name");
        $more = Yii::$app->getParams->get("more");
        $districtId = Yii::$app->getParams->get("district_id");
        $page = Yii::$app->getParams->get("page");
        $size = Yii::$app->getParams->get("size");
        $sectionName = Yii::$app->getParams->get("section_name");
        $isVip = Yii::$app->getParams->get("is_vip");

        if (!$page) {
            $page = 1;
        }
        if (!$size) {
            $size = 50;
        }

        if (!$more) {
            $more = 1;
        }
        try {

            $doctorCondition = [
                "condition" => "doctor_id > 0",
                "match" => [],
            ];
            $hospitalCondition = [
                "condition" => "hospital_id > 0",
                "match" => [],
            ];
            if ($key) {
                $doctorCondition["match"] = ['sdoctor_name' => $key];
                $hospitalCondition["match"] = ['dhospital_name' => $key];
            }
            if ($isVip) {
                $hospitalCondition['condition'] .= " AND is_vip > 0";
            } else {
                $hospitalCondition['condition'] .= " AND is_search = 1 ";
                $doctorCondition['match']['expression'] = '@sdistrict_id ("11"|"71")';
            }
            $selectField = ["doctor_id", "doctor_name", "hospital_name", "doctor_position", "section_info"];

            $modules = Yii::$app->getModule('doctor');
            $response = [];
            if ($more == 1) {
                $response['doctor_count'] = $modules->runAction('doctor/getCountByCondition', ['condition'=>$doctorCondition])['num'];
                $response['hospital_count'] = $modules->runAction('hospital/getCountByCondition', ['condition'=>$hospitalCondition])['num'];
                $response['listdoc'] = $modules->runAction("doctor/getDoctorByCondition", ['condition' => $doctorCondition, 'select' => $selectField,
                    'order' => 'doctor_id asc', 'page' => 1, 'limit' => 3]);
                $response['listhos'] = $modules->runAction('hospital/getbycondition', ['condition' => $hospitalCondition,
                    'page' => 1, 'limit' => 3, 'select'=> ['hospital_id', 'hospital_name']]);
            } elseif ($more == 2) {

                if ($districtId) {
                    $doctorCondition['match']['sdistrict_id'] = rtrim($districtId, "0");
                }
                if ($sectionName) {
                    $doctorCondition['match']['ssection_info'] = $sectionName;
                }
                $response['listdoc'] = $modules->runAction("doctor/getDoctorByCondition", ['condition' => $doctorCondition, 'select' => $selectField,
                    'order' => 'doctor_id asc', 'page' => $page, 'limit' => $size]);
            } elseif($more == 3) {
                if ($districtId) {
                    $hospitalCondition['match']['sdistrict_id'] = rtrim($districtId, "0");
                }

                $response['listhos'] = $modules->runAction('hospital/getbycondition', ['condition' => $hospitalCondition,
                    'page' => $page, 'limit' => $size, 'select'=> ['hospital_id', 'hospital_name']]);
            }

            if (isset($response['listdoc'])) {
                foreach($response['listdoc'] as $k => $v) {
                    $response['listdoc'][$k]['doctor_position'] = StateConfig::getDoctorPosition($v['doctor_position']);
                    $response['listdoc'][$k]['section_name'] = implode(",", Response::getDoctorSectionName($v['section_info']));
                    unset($response['listdoc'][$k]['section_info']);
                }
            }
            if ((isset($response['listdoc']) && $response['listdoc']) || (isset($response['listhos']) && $response['listhos']) ) {
                return UResponse::formatData("0", "获取医生和医院列表成功", $response);
            }
            return UResponse::formatData("0", "获取医生和医院列表失败", (object)['list' => []]);
        } catch (Exception $e) {

            return UResponse::formatData("0", "获取医生和医院列表失败", (object)['list' => []]);
        }
    }

}
