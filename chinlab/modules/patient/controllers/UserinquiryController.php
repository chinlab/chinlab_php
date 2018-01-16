<?php

namespace app\modules\patient\controllers;
use app\modules\patient\models\UserInquiry;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

class UserinquiryController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreateInquiry($info = []) {

        if (!$info) {
            return [];
        }
        try {
            $user = new UserInquiry();
            foreach($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            return $user->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetInquiryById($id) {

        try {
            if (!$id) {
                return [];
            }
            $customer = UserInquiry::findOne([
                'inquiry_id' => $id,
            ]);
            if (!$customer) {
                return [];
            }
            return $customer->toArray();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    public function actionGetbycondition($condition = [], $page = 1, $limit = 10)
    {
        if (!$condition) {
            return [];
        }
        try {
            return UserInquiry::find()
                ->select(['inquiry_id','inquiry_name', 'inquiry_state', 'disease_des', 'inquiry_time'])
                ->where($condition)
                ->limit($limit)->offset(($page - 1) * $limit)->orderBy('inquiry_time desc')
                ->asArray()->all();
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    /**
     * 修改用户信息
     * @param string $id
     * @param array $info
     * @return array
     */
    public function actionUpdateinfo($id = "",  $info = []) {

        try {
            if (!$id || !$info) {
                return [];
            }
            $user = UserInquiry::findOne(['inquiry_id' => $id]);
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
}
