<?php

namespace app\controllers;

use Yii;
use app\modules\article\models\Tad;
use app\models\AdvertQuery;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\ImgUploadForm;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response as UResponse;
use yii\log\Logger;
/**
 * AdvertController implements the CRUD actions for Advert model.
 */
class AdvertController extends BaseController
{  


    public function actionIndex()
    {
        $searchModel = new AdvertQuery();
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
        $model = new Tad();
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
	
    public function actionAttributeLabelsJson()
    {
    	$model = new Tad();
    	$label = $model->attributeLabels();
    	return  UResponse::formatData('0', 'success',$label);
    }
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndexJson()
    {
    	$search['AdvertQuery']   = Yii::$app->request->queryParams;
    	if(Yii::$app->request->isPost)
    	$search['AdvertQuery']   = Yii::$app->request->post();
    	$searchModel   =  new AdvertQuery();
    	$dataProvider  =  $searchModel->search($search);
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
    	return  UResponse::formatData('100', $searchModel->getFirstErrors());
    }
    
    /**
     * Displays a single Advert model.
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
     * Creates a new Advert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateJson()
    {
    	$create['Tad']       = Yii::$app->request->post();
        $imgModel = new ImgUploadForm();
        $imgModel->imageFile = UploadedFile::getInstanceByName('ad_img');
        $flag = false;
        if($imgModel->imageFile){
        	$url['url'] = NULL;
        	$tempName   = $imgModel->imageFile->tempName;
        	$url = Yii::$app->fdfs->upload($tempName, $imgModel->getExtension());
        	if(empty($url['url'])){
        		return  UResponse::formatData('100', 'upload fail!');
        	}
        	$create['Tad']['ad_img'] = $url['url'];
        	$flag = TRUE;
        }
        $model    = new Tad();
        if ($model->load($create)) {
        	//$model->ad_id  = Yii::$app->DBID->getID('db.tad');
        	if($model->save()){
        		$this->fulshKey();
            	$data =  ['data' => $create['Tad']];
            	return  UResponse::formatData('0', 'success',$data);
        	}
        }
        if($flag){
	       //删除上传的图片
        	$groupInfo = $imgModel->getGroup($create['Tad']['ad_img']);
        	if($groupInfo)
        	Yii::$app->fdfs->delete($groupInfo['group'],$groupInfo['file']);
        }
        return  UResponse::formatData('100', $model->getFirstErrors());
    }
    protected  function fulshKey()
    {
    	$redis = Yii::$app->redis;
    	$newsKey = AppRedisKeyMap::getAdTypeKey('ad');
    	if($redis->exists($newsKey)){
    		$redis->del($newsKey);
    	}
    	return true;
    }
    /**
     * Updates an existing Advert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateJson()
    {   
    	$update['Tad'] = Yii::$app->request->post();
        $imgModel =  new ImgUploadForm();
        $flag     =  false;
        if($imgModel->imageFile){
        	$url['url'] = NULL;
        	$tempName   = $imgModel->imageFile->tempName;
        	$url = Yii::$app->fdfs->upload($tempName, $imgModel->getExtension());
        	if(empty($url['url'])){
        		return  UResponse::formatData('100', 'upload fail!');
        	}
        	$update['Tad']['ad_img'] = $url['url'];
        	$flag = TRUE;
        }
        $model    = new Tad();
        $id       = $update['Tad']['ad_id'];
        $model    = $this->findModel($id);
        foreach ($update['Tad'] as $key =>$value){
        	if(strlen($value) === 0) unset($update['Tad'][$key]);
        }
        if ($model->load($update) && $model->save()) {
        	    $this->fulshKey();
        		$data =  ['id' => $model->ad_id];
        		return  UResponse::formatData('0', 'success',$data);
        }
        if($flag){
	       //删除上传的图片
        	$groupInfo = $imgModel->getGroup($update['Tad']['ad_img']);
        	if($groupInfo)
        	Yii::$app->fdfs->delete($groupInfo['group'],$groupInfo['file']);
        }
        return  UResponse::formatData('100', $model->getFirstErrors());
        
    }

    /**
     * Deletes an existing Advert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteJson()
    {  
    	$id     =  Yii::$app->getParams->get("id");
        if($model = $this->findModel($id)->delete()){
        	$data = ['id' => $id];
        	$this->fulshKey();
        	return  UResponse::formatData('0', 'success',$data);
        }
        return  UResponse::formatData('100', $model->getFirstErrors());
    }

    /**
     * Finds the Advert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tad::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
}
