<?php

namespace app\modules\patient\controllers;

use app\common\components\AppRedisKeyMap;
use app\modules\patient\models\UserCollection;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class UsercollectionController extends \app\common\controller\Controller
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
            $user = UserCollection::findOne($condition);
            if (!$user) {
                $user = new UserCollection();
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
                return UserCollection::find()
                    ->select(['material_id', 'author', 'news_type', 'news_photo', 'title', 'channel_no', 'channel_name', 'news_url', 'publish_time'])
                    ->where($condition)->leftJoin("info_news", "tuser_collection.tc_news_id = info_news.material_id")
                    ->limit($limit)->offset(($page - 1) * $limit)->orderBy('tuser_collection.update_time desc')
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
            $redisKey = AppRedisKeyMap::getInfoCollection($userId);
            if (!$redis->exists($redisKey)) {
                $result = UserCollection::find()
                    ->select(['tc_user_id', 'tc_news_id'])
                    ->where(['tc_user_id' => $userId, "is_delete" => 1])
                    ->asArray()->all();
                $sadd = [$redisKey, 1];
                foreach ($result as $k => $v) {
                    $sadd[] = $v['tc_news_id'];
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
}
