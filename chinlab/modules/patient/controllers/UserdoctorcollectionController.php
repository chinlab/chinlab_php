<?php

namespace app\modules\patient\controllers;

use app\common\components\AppRedisKeyMap;
use app\modules\patient\models\UserDoctorCollection;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class UserdoctorcollectionController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdateinfo($condition = [],  $info = []) {

        try {
            if (!$condition || !$info) {
                return [];
            }
            $user = UserDoctorCollection::findOne($condition);
            if (!$user) {
                $user = new UserDoctorCollection();
            }
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetlist($condition = [], $page = 1, $limit = 10) {
            if (!$condition) {
                return [];
            }
            try {
                return UserDoctorCollection::find()
                    ->select(["good_at","doctor_id", "doctor_name", "hospital_name", "doctor_position", "hospital_level", "doctor_des", "doctor_head","price","section_info"])
                    ->where($condition)->leftJoin("at_doctor_info", "tuser_doctor_collection.tc_doctor_id = at_doctor_info.doctor_id")
                    ->limit($limit)->offset(($page - 1) * $limit)->orderBy('tuser_doctor_collection.update_time desc')
                    ->asArray()->all();
            } catch (Exception $e) {
                Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
                
                return [];
            }
    }

    public function actionGetIsCollection($userId, $articleId)
    {
        try {
            $redis = Yii::$app->redis;
            $redisKey = AppRedisKeyMap::getInfoDoctorCollection($userId);
            if (!$redis->exists($redisKey)) {
                $result = UserDoctorCollection::find()
                    ->select(['tc_user_id', 'tc_doctor_id'])
                    ->where(['tc_user_id' => $userId, "is_delete" => 1])
                    ->asArray()->all();
                $sadd = [$redisKey, 1];
                foreach ($result as $k => $v) {
                    $sadd[] = $v['tc_doctor_id'];
                }
                $redis->executeCommand("sadd", $sadd);
                $redis->expire($redisKey, 3600);
            }
            if ($redis->SISMEMBER($redisKey, $articleId)) {
                return [1];
            }
            return [];
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
    
    //查询医生收藏总记录
    public function actionGetcollectioncount($condition = []) {
    	if (!$condition) {
    		return [];
    	}
    	try {
    		return UserDoctorCollection::find()
    		->where($condition)->count('*');
    	} catch (Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
    
    
}
