<?php

namespace app\modules\patient\controllers;

use app\modules\patient\models\CardPersonUser;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class CardpersionuserController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetbyid($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = CardPersonUser::findOne($id);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionFindOneByCondition($condition) {

        try {
            if (!$condition) {
                return [];
            }
            $customer = CardPersonUser::findOne($condition);
            if (!$customer) {
                return [];
            }

            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionCreate($info)
    {

        try {
            $user = new CardPersonUser();
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetlist($uid, $card, $page, $limit) {

        if (!$uid) {
            return [];
        }
        try {
            $result =  CardPersonUser::find()
                ->select(['user_card_no'])
                ->where(['is_delete' => 1, 'card_no' => $card])
                ->limit($limit)->offset(($page - 1) * $limit)->groupBy("user_card_no")
                ->asArray()->all();
            $res = [];
            foreach($result as $k => $v) {
                $res[] = $v['user_card_no'];
            }
            return $res;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    public function actionGetcount($uid, $card) {

        if (!$uid) {
            return [];
        }
        try {
            return CardPersonUser::find()
                ->where(['is_delete' => 1, 'card_no' => $card])
                ->asArray()->count();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    public function actionUpdateinfo($id = "",  $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }
            $user = CardPersonUser::findOne($id);
            if (!$user) {
                return [];
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

    
    public function actionGetbycardno($user_card_no,$card_no) {
    
    	try {
    		//根据身份证和卡号确定是否存在用户
    		if (!$user_card_no || !$card_no) {
    			return [];
    		}
    		$customer = CardPersonUser::find()
    		->where(['card_no'=>$card_no,'user_card_no'=>$user_card_no])->one();
    		if (!$customer) {
    			return [];
    		}
    
    		return $customer->toArray();
    	} catch(Exception $e) {
    		Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
    		return [];
    	}
    }
}
