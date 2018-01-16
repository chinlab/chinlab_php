<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use app\modules\patient\models\Version;
use app\models\VersionQuery;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\ImgUploadForm;
use app\common\data\Response as UResponse;
use yii\log\Logger;

/**
 * VersionController implements the CRUD actions for Version model.
 */
class VersionController extends BaseController
{
	
	/**
	 * Lists all Version models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel 		=   new VersionQuery();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
		]);
	}
	
	
	/**
	 * Displays a single Version model.
	 * @param integer $id
	 * @return mixed
	 */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
	
	/**
	 * Creates a new Version model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model  = new Version();
	    if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
	}
	
	/**
	 * Updates an existing Version model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
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
	
	/**
	 * Deletes an existing Version model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
    public function actionAttributeLabelsJson()
    {
    	$model = new Version();
    	$label = $model->attributeLabels();
    	return  UResponse::formatData('0', 'success',$label);
    }
    /**
     * Lists all Version models.
     * @return mixed
     */
    public function actionIndexJson()
    {
    	$search['VersionQuery']   = Yii::$app->request->queryParams;
    	if(Yii::$app->request->isPost)
    		$search['VersionQuery']   = Yii::$app->request->post();
    	$searchModel 		=   new VersionQuery();
        $dataProvider 		=   $searchModel->search($search);
        $pagination 		=   [];
        $pageSize           =   Yii::$app->getParams->get("pageSize");
        $page         	   	=   Yii::$app->getParams->get("page");
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
        return  UResponse::formatData('100', $model->getFirstErrors());
    }

    
    /**
     * Displays a single Version model.
     * @param integer $id
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
    

    /**
     * Creates a new Version model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateJson()
    {   
    	$create['Version'] 	 = Yii::$app->request->post();
    	$imgModel   = new ImgUploadForm();
        $flag     = false;
        $imgModel->imageFile = UploadedFile::getInstanceByName('version_url');
        if($imgModel->imageFile){
        	$url['url'] = NULL;
        	$url = Yii::$app->fdfs->upload($imgModel->imageFile->tempName, $imgModel->getExtension());
        	if(empty($url['url'])){
        		return  UResponse::formatData('100', 'upload fail!');
        	}
        	$create['Version']['version_url'] = $url['url'];
        	$flag = TRUE;
        }
        $model  = new Version();
    	$model->version_id  = Yii::$app->DBID->getID('db.tversion');//取id方法
        if ($model->load($create) && $model->save()) {
        	$data =  ['id'=> $model->version_id ];
        	return  UResponse::formatData('0', 'success',$data);
        }
        if($flag){
        	//删除上传的图片
        	$groupInfo = $imgModel->getGroup($create['Version']['version_url']);
        	if($groupInfo)
        	 Yii::$app->fdfs->delete($groupInfo['group'],$groupInfo['file']);
        }
        return  UResponse::formatData('100', $model->getFirstErrors());
    }

    /**
     * Updates an existing Version model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateJson()
    {  
    	$update['Version'] = Yii::$app->request->post();
    	$imgModel   = new ImgUploadForm();
        $imgModel->imageFile = UploadedFile::getInstanceByName('version_url');
        $flag     = false;
        if($imgModel->imageFile){
        	$url['url'] = NULL;
        	$url = Yii::$app->fdfs->upload($imgModel->imageFile->tempName, $imgModel->getExtension());
        	if(empty($url['url'])){
        		return  UResponse::formatData('100', 'upload fail!');
        	}
        	$create['Version']['version_url'] = $url['url'];
        	$flag = TRUE;
        }
        $model      =  $this->findModel($update['Version']['version_id']);
        foreach ($update['Version'] as $key =>$value){
        	if(strlen($value) === 0) unset($update['Version'][$key]);
        }
        if ($model->load($update) && $model->save()) {
            $data = ['id'=> $model->version_id ];
            return  UResponse::formatData('0', 'success',$data);
        }
        if($flag){
        	//删除上传的图片
        	$groupInfo = $imgModel->getGroup($update['Version']['version_url']);
        	if($groupInfo)
        	 Yii::$app->fdfs->delete($groupInfo['group'],$groupInfo['file']);
        }
        return  UResponse::formatData('100', $model->getFirstErrors());
    }

    /**
     * Deletes an existing Version model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
    

    /**
     * Finds the Version model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Version the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Version::findOne($id)) !== null) {
            return $model;
        } else {
             return false;
        }
    }
    
    
}
