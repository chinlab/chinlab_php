<?php
namespace app\controllers;
use Yii;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use app\common\data\Response as UResponse;
use yii\web\Response;
use app\models\Admin;
use app\models\Menu;
use app\models\AdminQuery;
use app\models\LoginForm;
use app\models\SignupForm;
use app\modules\patient\models\User;
use app\modules\patient\models\UserInquiry;
use app\modules\patient\models\UserOrder;
/**
 *   @author user
 *   管理员
 */
class AdminController extends BaseController
{

    public function actionIndex()
    {   
        $searchModel = new AdminQuery();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

  
    public function actionCreate()
    {
        $model =  new Admin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionAttributeLabelsJson()
    {
    	$model = new Admin();
    	$label = $model->attributeLabels();
    	return  UResponse::formatData('0', 'success',$label);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
    protected function findModel($id)
    {
    	if (($model = Admin::findOne($id)) !== null) {
    		return $model;
    	} else {
    		return false;
    	}
    }
    
    public function actionSignupJson()
    {
    	Yii::$app->response->format =  Response::FORMAT_JSON;
    	$create['SignupForm']       = Yii::$app->request->post();
    	$model = new SignupForm();
    	$create['SignupForm']['sys_type'] = '1';
    	if(!isset($create['SignupForm']['status'])){
    		$create['SignupForm']['status'] = 0;
    	}
    	if(($create['SignupForm']['status']+0)>0){
    		$create['SignupForm']['status'] = 10;
    	}else{
    		$create['SignupForm']['status'] = 0;
    	}
    	if ($model->load($create) ) {
    		$model->status = $create['SignupForm']['status'];
    		if ( $model->validate() && $user = $model->signup()) {
    			return  UResponse::formatData('0', 'success',$create['SignupForm']);
    		}
    		$error = current($model->getFirstErrors());
    		return  UResponse::formatData('100',$error);
    	}
    	return  UResponse::formatData('100', '');
    }
    
    
    public function actionLoginJson()
    {
    	Yii::$app->response->format =  Response::FORMAT_JSON;
    	$login['LoginForm']       = Yii::$app->request->post();
    	$model = new LoginForm();
    	if ($model->load($login) && $model->login()) {
    		$login = $model->getUser()->toArray();
    		if(isset($login['id']) && $login['id']=='1')
    		{
    			$login['type'] = 0;
    		}else{
    			$login['type'] = 1;
    		}
    		unset($login['password_hash'],$login['password_reset_token'],$login['auth_key']);
    		$ip = Yii::$app->request->getUserIP();
    		Yii::$app->session->writeSession($ip,$ip);
    		return  UResponse::formatData('0', 'success',$login);
    	} else {
    		$errors = array_values($model->getFirstErrors());
    		return  UResponse::formatData('100',$errors['0']);
    	}
    	return  UResponse::formatData('100', '');
    }
    
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogoutJson()
    {
    	Yii::$app->user->logout();
    	$ip = Yii::$app->request->getUserIP();
    	Yii::$app->session->destroySession($ip);
    	return  UResponse::formatData('0', 'success',[]);
    }
    
    
    /**
     * Lists all Disease models.
     * @return mixed
     */
    public function actionIndexJson()
    {
    	$search   = Yii::$app->request->queryParams;
    	if(Yii::$app->request->isPost)
    		$search   = Yii::$app->request->post();
        $search['sys_type'] = 1;
    	$searchModel = new AdminQuery();
    	$dataProvider  = $searchModel->search($search);
    	$pageSize      = Yii::$app->getParams->get("pageSize");
    	$page          = Yii::$app->getParams->get("page");
    	$pagination    = [];
    	$dataProvider->Pagination->pageSize = empty($pageSize)?10:$pageSize;
    	$dataProvider->Pagination->page     = empty($page)?0:$page;
    	$pagination['totalCount']           = $dataProvider->query->count();
    	$pagination['pageSize']    			= $dataProvider->Pagination->pageSize;
    	$pagination['page']    	   			= $dataProvider->Pagination->page;
    	$pagination['pageTotalCount']       = ceil($pagination['totalCount']/$pagination['pageSize']);
    	$data = $dataProvider->getModels();
    	$show['data']       = $data;
    	$show['pagination'] = $pagination;
    	if($data){
    		return  UResponse::formatData('0', 'success',$show);
    	}
    	return  UResponse::formatData('100', $searchModel->getFirstError());
    }
    
    
    public function actionViewJson()
    {
    	$id    =  Yii::$app->getParams->get("id");
    	$search['AdminQuery']['id'] = $id;
    	$searchModel = new AdminQuery();
    	$search['AdminQuery']['sys_type'] = 1;
    	$dataProvider  = $searchModel->search($search);
    	$dataProvider->query->select(["id","username","email",
    			"nickname","userphone","password_hash","status","created_at"]);
    	$data['role'] = $dataProvider->getModels();
    	if(empty($data['role'])){
    		return  UResponse::formatData('100', 'this id is not exists!');
    	}
    	//$data['menu'] = Menu::find()->select(["id","name","parent","route","order"])->all();
    	$auth 	= Yii::$app->getAuthManager();
    	$role   = false;//$auth->getRolesByUser($id);
    	//$data[] = $auth->getPermissionsByUser($id);
    	$ids  = [];
    	$list = [];
    	$childList = [];
    	if($role){
    		$role = array_values($role);
    		$menu = $auth->getChildren($role['0']->name);
    		$menu = array_keys($menu);
    		$query = Menu::find()
    		->select(['id','name','parent','route'])
    		->where(['in','name',$menu])
    		->asArray()->all();
    		if($query){
    			$i = 0;
    			foreach ($query as $key => $base){
    				$ids[] = $base['id'];
    				$list[$key]['id']   =  $base['id'];
    				$list[$key]['name'] =  $base['name'];
    				$list[$key]['pId']  =  empty($base['parent'])?0:$base['parent'];
    				$list[$key]['open'] = 'true';
    				//获取子集菜单
    				$child  = Menu::find()
    				->select(['id','name','parent','route'])
    				->where(['parent'=>$base['id']])
    				->asArray()->all();
    				if(is_array($child)){
	    				foreach ($child as $childKey => $childValue){
	    					$ids[]  =  $childValue['id'];
	    					$childList[$i]['id']   =  $childValue['id'];
	    					$childList[$i]['name'] =  $childValue['name'];
	    					$childList[$i]['pId']  =  empty($childValue['parent'])?0:$childValue['parent'];
	    					$childList[$i]['open'] = 'true';
	    					$i ++;
	    				}
    				}
    			}
		    	
    		}
    	}
    	$data['menu'] = array_merge($list,$childList);
    	$data['menulist']  = $ids;
    	return  UResponse::formatData('0', 'success',$data);
    }
    
    public function actionDayCountJson()
    {   
    	$t = date('Y-m-d',strtotime("-1 day"));
    	$t1 = strtotime($t)-(3600*8);
    	$data['user'] 	 = User::find()->where(['between','create_time',$t1,time()])->count();
    	$data['inquiry'] = UserInquiry::find()->where(['between','create_time',$t1,time()])->count();
    	$data['order'] 	 = UserOrder::find()->where(['between','create_time',$t1,time()])->count();
    	return  UResponse::formatData('0', 'success',$data);
    }
    
    
    public function actionUpdateJson()
    {
    	$Update['Admin'] = Yii::$app->request->post();
    	if(!isset($Update['Admin']['id'])){
    		if($userInfo = Yii::$app->user->identity){
    			$Update['Admin']['id'] =$userInfo->id;
    		}else{
    			return  UResponse::formatData('100', '管理员编号不能为空!');
    		}
    	}
    	if(!$id = $Update['Admin']['id']){
    		return  UResponse::formatData('100', '管理员编号不能为空!');
    	}
    	if(isset($Update['Admin']['menulist']))
    	{
    		$menulist = $Update['Admin']['menulist'];
    		unset($Update['Admin']['menulist']);
    	}
    	if($Update['Admin']['status']+0 > 0){
    		$Update['Admin']['status'] = '10';
    	}else{
    		$Update['Admin']['status'] = '0';
    	}
    	$model = $this->findModel($id);
    	if($model){
	    	foreach ($Update['Admin'] as $key =>$value){
	    		 if(strlen($value) === 0){
	    		 	unset($Update['Admin'][$key]);
	    		 }else if($key == 'password'){
	    		   $pass = $Update['Admin']['password']; 
	    		   $model->setPassword($pass);
	    		   $model->generateAuthKey();
	    		}else{
	    			$model->$key = $value;
	    		}
	    	}
	    	if($model->load($Update) && $model->save()) {
	    		$data =  ['data' => $Update['Admin']];
	    		return  UResponse::formatData('0', 'success',$data);
	    	}
    	}
    	return  UResponse::formatData('100', '暂无当前管理员信息');
    }
    
    
}
