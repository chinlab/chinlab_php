<?php

namespace app\controllers;

use Yii;
use app\modules\patient\models\User;
use app\models\UserQuery;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use app\common\data\Response as UResponse;
use app\modules\service\models\CCPRestSDK;
use app\modules\service\models\SendSMS;
use app\modules\patient\models\UserInquiry;
use app\modules\patient\models\UserOrder;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {   
    	
    	$searchModel  = new UserQuery();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    	]);
    	
    }


    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    
    
    public function actionUpdate($id)
    {
    	$model = $this->findModel($id);
    
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->id]);
    	} else {
    		return $this->render('view', [
    				'model' => $model,
    		]);
    	}
    }
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionAttributeLabelsJson()
    {
    	$model = new User();
    	$label = $model->attributeLabels();
    	return  UResponse::formatData('0', 'success',$label);
    }
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndexJson()
    {   
    	$search['UserQuery']   = Yii::$app->request->queryParams;
    	if(Yii::$app->request->isPost)
    	$search['UserQuery']   = Yii::$app->request->post();
        $searchModel   =  new UserQuery();
        $dataProvider  =  $searchModel->search($search);
        if(isset($search['UserQuery']['user_regtime'])){
        	$end =$search['UserQuery']['user_regtime'];
        	if($end){
        		$dataProvider->query->where(['between', 'user_regtime', 0, $end]);
        	}
        }
        $pageSize      =  Yii::$app->getParams->get("pageSize");
        $page          =  Yii::$app->getParams->get("page");
        $pagination    =  [];
        $dataProvider->Pagination->pageSize = empty($pageSize)?10:$pageSize;
        $dataProvider->Pagination->page     = empty($page)?0:$page;
        $pagination['totalCount']           = $dataProvider->query->count();
        $pagination['pageSize']    			= $dataProvider->Pagination->pageSize;
        $pagination['page']    	   			= $dataProvider->Pagination->page;
        $pagination['pageTotalCount']       = ceil($pagination['totalCount']/$pagination['pageSize']);
        $data = $dataProvider->getModels();
        $list = [];
        foreach ($data as $key => $value){
        	$rep = $value->toArray();
        	if(is_array($rep)){
        		$num  = $this->actionNum($rep['user_id']);
        		$list[$key] =  array_merge($rep,$num);
        	}
        }
        $show['data']       = count($list)?$list:$data;
        $show['pagination'] = $pagination;
        if($data){
          return  UResponse::formatData('0', 'success',$show);
        }
        return  UResponse::formatData('100', $searchModel->getFirstErrors());
    }

	protected function  actionNum($uid=Null)
	{
		$id  =  Yii::$app->getParams->get("id");
		$id  = empty($id)?$uid:$id;
		$data['inquirysum']  = UserInquiry::find()->where(['user_id'=>$id])->count();
		$data['ordersum'] 	 = UserOrder::find()->where(['user_id'=>$id])->count();
		return $data;
	}
    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionViewJson()
    {
        $id     =  Yii::$app->getParams->get("id");
    	if($model = $this->findModel($id)){
    		return  UResponse::formatData('0', 'success',$model);
    	}
    	return  UResponse::formatData('100', $model->getFirstErrors());
    }
    
    public function actionUpdateJson()
    {
    	$update['User'] = Yii::$app->request->post();
    	$id   = $update['User']['user_id'];
    	$model = $this->findModel($id);
    	foreach ($update['User'] as $key =>$value){
    		if(strlen($value) === 0) unset($update['User'][$key]);
    	}
    	if ($model->load($update) && $model->save()) {
    		$data = ['id' => $model->user_id];
    		return  UResponse::formatData('0', 'success',$data);
    	}
    	return  UResponse::formatData('100', $model->getFirstErrors());
    }
    
    public function actionCreateJson()
    {   
    	$model  = new User();
    	$user_name   =  Yii::$app->getParams->get("user_name");
    	if($user_name){
    		if($this->findModelByName(['user_name' => $user_name])){
    			return  UResponse::formatData('100', 'user_name is exists!');
    		}
    		$model->user_name = $user_name;
    	}
    	if(!$user_name){
    		return  UResponse::formatData('100', '用户名不能为空！');
    	}
    	$model->user_id = Yii::$app->DBID->getID('db.'.$model::tableName());//取id方法
    	$model->user_regtime 	=  date("Y-m-d H:i:s",time());
    	$user_pass   =  Yii::$app->getParams->get("user_pass");
    	if(!$user_pass){
    		return  UResponse::formatData('100', '密码不能为空！');
    	}
    	$model->user_pass = $user_pass;
    	$role   =  Yii::$app->getParams->get("role");
    	if($role){
    		$model->role = $role;
    	}
    	$user_img   =  Yii::$app->getParams->get("tciconsrc");
    	if($user_img){
    		$model->user_img = $user_img;
    	}
    	$user_mobile   =  Yii::$app->getParams->get("user_mobile");
    	if($user_mobile){
    		$model->user_mobile = $user_mobile;
    	}
    	$model->company_all_seek = 0;
    	$model->company_has_seek=0;
    	if ($model->save()) {
    		$data = ['id'=> $model->user_id ];
    		return  UResponse::formatData('0', 'success',$data);
    	}
    	return  UResponse::formatData('100', $model->getFirstErrors());
    }
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteJson()
    {
    	$id     =  Yii::$app->getParams->get("id");
        if($model = $this->findModel($id)->delete()){
        	$data = ['id' => $id];
        	return  UResponse::formatData('0', 'success',$data);
        }
        return  UResponse::formatData('100', $model->getFirstErrors());
    }
    
    protected function findModelByName($id)
    {
    	if (($model = User::findOne($id)) !== null) {
    		return $model;
    	} else {
    		return false;
    	}
    }
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
             return false;
        }
    }
    
    
    public function actionSendSmsJson()
    {
    	ini_set("display_errors", "On");
    	error_reporting(E_ALL | E_STRICT);
    	$phone   =  Yii::$app->getParams->get("phone");
    	$code   =  Yii::$app->getParams->get("code");
    	if(!$phone){
    		return  UResponse::formatData('100', 'phone is not empty!');
    	}
    	$json = false;
    	$code = empty($code)?'888888':$code;
    	$str = '您的注册验证码是：'.$code.'，两分钟内有效！【伙伴医生】';
    	if(preg_match('~[0-9]*\d~', $str,$sub)){
    		$datas = [$sub[0],'5'];
    		$sms = new SendSMS();
    		$json = $sms->sendTemplateSMS($phone, $datas);
    	}
    	if($json){
    		$data = ['phone' => $phone,'code' => $code];
    		return  UResponse::formatData('0', 'success',$data);
    	}
    	return  UResponse::formatData('100','fial!');
    }
    
    
    public function actionCurl()
    {
    	$data = '<TemplateSMS>
                    <to>18515422930</to> 
                    <appId>8a216da858ce0b3c0158dc8136c70a1f</appId>
                    <templateId>140010</templateId>
                    <datas><data>888888</data><data>5</data></datas>
                  </TemplateSMS>';
    	$header = [
    			 'Accept:application/xml',
    			 'Content-Type:application/xml;charset=utf-8',
    		     'Authorization:OGEyMTZkYTg1OGNlMGIzYzAxNThkODU4NTUyMDA3YWU6MjAxNjEyMTAwNzQ1MTk=',
    		    ];
    	
    	$url = 'https://app.cloopen.com:8883/2013-12-26/Accounts/8a216da858ce0b3c0158d858552007ae/SMS/TemplateSMS?sig=451B11347300F25AEA7A652260CEB4D5';
    	
    	$https = Yii::$app->httpClient->options;
    	//初始化curl
    	$ch = curl_init();
    	//参数设置
    	curl_setopt ($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt ($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    	curl_setopt($ch, CURLOPT_PROXY, $https[CURLOPT_PROXY]);
    	curl_setopt($ch, CURLOPT_PROXYPORT, $https[CURLOPT_PROXYPORT]);
    	$result = curl_exec ($ch);
    	$errorNumber = curl_errno($ch);
    	$errorMessage = curl_error($ch);
    	$info = curl_getinfo($ch);
    	curl_close($ch);
    	echo "<pre>";
    	var_dump($errorNumber,$errorMessage,$result,$info);
    }
  
    
    
}
