<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 上午 2:34
 */
namespace app\modules\service\commands;

use app\common\application\RabbitConfig;
use app\common\components\AppRedisKeyMap;
use app\common\controller\Controller;
use yii\db\Expression;
use yii\log\Logger;
use Yii;

class NewsController extends Controller
{

    /**
     *  后台发布新闻写缓存
     * @param array $info 是新闻发布的 id 数组
     * @return array
     */
    public function actionUpdatenewscacheone($info = []) {
        try {
        	echo json_encode($info,JSON_UNESCAPED_UNICODE);
        	if (!$info || !is_array($info) || !isset($info['material_id'])){
        		return [1];
        	}
        	$modules =  Yii::$app->getModule('article');
        	$newInfo =  $modules->runAction('infonews/GetInfoById', ['id' => $info['material_id'] ]);
        	if($newInfo)
        	$result  =  $modules->runAction('infonews/writeredis', ['info' => $newInfo ]);
        	return [1];
        } catch (\Exception $e) {
        	Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
        	return [1];
        }
    }

	/**
	 * 更新点赞数量到数据库
	 */
	public function actionUpdategoodnews() {

		try {

			$redis = Yii::$app->redis;
			$redisKey = AppRedisKeyMap::getGoodsNewsPre();
			$result = $redis->executeCommand("ZRANGE", [$redisKey, 0, -1, 'WITHSCORES']);
			foreach($result as $k => $v) {
				if (!($k % 2)) {
					$newsId = $v;
					$numbers = $result[$k+1];
					//new Expression(
					Yii::$app->db->createCommand()->update('info_news', ['good_time' => new Expression('good_time + ' . $numbers)], 'material_id = :id', [':id' => $newsId])->execute();
				}
			}

			return [1];
		} catch (\Exception $e) {
			Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
			return [1];
		}
	}

    
    /**
     *  根据频道和类型写入一千条缓存
     *  @return array
     */
    public function actionUpdatenewscachemore($info = []) {
    	try {
    		echo json_encode($info,JSON_UNESCAPED_UNICODE);
    		if (!$info || !is_array($info) || !isset($info['channel_no']) || !isset($info['show_type'])){
    			return [1];
    		}
    		$modules   =  Yii::$app->getModule('article');
    		$result    =  $modules->runAction('infonews/writemorenews', ['info' => $info ]);
    		return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    

    /**
     *  后台发布新闻写推送
     *  @return array
     */
    public function actionPushlishpush($info = []) {
    	try {
    		if (!$info || !is_array($info) || !isset($info['material_id'])){
    			return [1];
    		}
    		$modules =  Yii::$app->getModule('article');
    		$newInfo =  $modules->runAction('infonews/GetInfoById', ['id' => $info['material_id'] ]);
    		if($newInfo){
    			$Newsformat = $modules->runAction('infonews/newsformat', ['news' => $newInfo]);
    			if($Newsformat){
			    	$result = $modules->runAction('infonews/Pushtoapp', ['info' => $Newsformat]);
			    	echo json_encode($result,JSON_UNESCAPED_UNICODE);
    			}
    		}
    		echo json_encode($newInfo,JSON_UNESCAPED_UNICODE);
    		return [1];
    	} catch (\Exception $e) {
    		echo $e->getMessage();
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
    /**
     *  删除单个缓存操作
     */
    public function actionDelcache($info = []){
    	try {
    		echo json_encode($info,JSON_UNESCAPED_UNICODE);
    		if (!$info || !is_array($info) || !isset($info['material_id']) 
    				 || !isset($info['show_type']) || !isset($info['news_type'])
    				 || !isset($info['channel_no'])){
    			return [1];
    		}
    		$modules =  Yii::$app->getModule('article');
    		$result  =  $modules->runAction('infonews/Delredis', ['info' => $info]);
    		return [1];
    	} catch (\Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [1];
    	}
    }
    
}