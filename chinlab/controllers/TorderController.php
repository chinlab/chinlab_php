<?php

namespace app\controllers;

use Yii;
use app\modules\patient\models\UserOrder;
use app\models\OrderQuery;
use app\models\ImgUploadForm;
use yii\web\UploadedFile;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use app\common\data\Response as UResponse;
use yii\log\Logger;
use app\models\StateConfig;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class TorderController extends BaseController
{
  
    
    public function actionIndexJson()
    {
    	$search['OrderQuery']   = Yii::$app->request->queryParams;
    	if(Yii::$app->request->isPost)
    		$search['OrderQuery']   = Yii::$app->request->post();
    	$searchModel = new OrderQuery();
    	$dataProvider  = $searchModel->search($search);
    	if(isset($search['OrderQuery']['order_time'])){
    		$end =$search['OrderQuery']['order_time'];
    		if($end){
    			$dataProvider->query->where(['between', 'order_time', 0, $end]);
    		}
    	}
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
    	$show['data']       = $this->dataFormat($data);
    	$show['pagination'] = $pagination;
    	if($data){
    		return  UResponse::formatData('0', 'success',$show);
    	}
    	return  UResponse::formatData('100', $searchModel->getFirstErrors());
    }
    
    protected function dataFormat($data,$alias=FALSE)
    {   
    	$name = $this->actionOrderTypeAllJson();
    	$pid = $name['data'];
    	foreach($data as $key => $value)
    	{   
    		$data[$key]['type_name']  = $this->currentType($pid['pid'],$value['order_type']);
    		$states = $pid[$value['order_type']];
    		$data[$key]['state_name'] = $this->currentState($states, $value['order_state'],$alias);
    	}
    	return $data;
    }
    
    protected static function currentType($pid,$order_type)
    {   
    	$currentType = '未知类型';
    	foreach ($pid as $key => $value){
    		if($value['value'] == $order_type){
    			$currentType =  $pid[$key]['option'];
    		}
    	}
    	return $currentType;
    }
    
    protected static function currentState($states,$order_state,$alias)
    {   
    	$currentState = '未知状态';
    	foreach ($states as $key => $value){
    		if($value['value'] == $order_state){
    			if($alias)
    			   $currentState = isset($value['alias'])?$value['alias']:$value['option'];
    			else{ 
    			   $currentState = $value['option'];
    			}
    		}
    	}
    	return $currentState;
    }
    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionViewJson()
    {
        $id     =  Yii::$app->getParams->get("id");
    	if($model = $this->findModel($id)){
    		$order = $model->toArray();
    		$data[]= $this->dataFormat([$order]);
    		$data[]= $this->stateType($order['order_type'],$order['order_state']);
    		return  UResponse::formatData('0', 'success',$data);
    	}
    	return  UResponse::formatData('100', '暂无数据');
    }

  
    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateJson()
    {   
     	$Update['UserOrder'] = Yii::$app->request->post();
    	if(!$id = $Update['UserOrder']['order_id']){
    		return  UResponse::formatData('100', 'order_id is not empty!');
    	}
        $model    = $this->findModel($id);
        if($model){
        	$order = $model->toArray();
        	$updateState = $Update['UserOrder']['order_state'];
        	if($updateState){
	        	if( $order['order_state'] > $updateState){
	        		return  UResponse::formatData('100', '订单状态不可逆!');
	        	}
        	}
        	$orderState = $this->isSubscriptionState($order['order_type'],$order['order_state']);
        	if($orderState && 
        			$updateState !=$order['order_state']
        			&& ($order['order_state'] +1) != $updateState){
        		return  UResponse::formatData('100', '订单状态不能跳跃!');
        	}
        	$state = StateConfig::$orderStatus['ordertype1'];
        	if($orderState && 
        			!$this->priceCheck($Update['UserOrder']['order_price'])
        			&& $updateState == $state['type4']['val']){
        		return  UResponse::formatData('100', '诊疗费不能为空!');
        	}
	        foreach ($Update['UserOrder'] as $key =>$value){
	        	if(strlen($value) === 0) unset($Update['UserOrder'][$key]);
	        }
	        if($model->load($Update) && $model->save()) {
	          	$data =  ['data' => $Update['UserOrder']];
	           	return  UResponse::formatData('0', 'success',$data);
	        } 
        }
        return  UResponse::formatData('100', '订单不存在');
    }
    
    
    protected  function priceCheck($value)
    {  
    	return is_numeric($value) && is_int($value+0);
    }
    /**
     * @TODO delete fstdfs files
     * @param string $file
     * @throws \Exception
     */
    public function actionDeleteImg($file = NULL)
    {
    	$fileByGet =  Yii::$app->getParams->get("file");
    	$file      =  empty($file)?$fileByGet:$file;
    	$imgModel  =  new ImgUploadForm();
    	$groupInfo =  $imgModel->getGroup($file);
    	if($groupInfo){
    		try {
    		   Yii::$app->fdfs->delete($groupInfo['group'],$groupInfo['file']);
    		   return  UResponse::formatData('0', 'success',$file);
    		} catch (Exception $e) {
    			throw new \Exception('cannot connect to tracker server:[' .
    					 Yii::$app->fdfs->get_last_error_no() . '] ' .
    					 Yii::$app->fdfs->get_last_error_info());
    		}
    	}
        $error = array_values($model->getFirstErrors());
        return  UResponse::formatData('100', $error['0']);
    }
    
    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteJson()
    {
        $id     =  Yii::$app->getParams->get("id");
        $model  = $this->findModel($id);
        if($model){
	        $model->is_delete = 2;
       	    if($model->update()){
        		$data = ['id' => $id];
        		return  UResponse::formatData('0', 'success',$data);
        	}
        }
        return  UResponse::formatData('100', '失败！');
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserOrder::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
    
    //是否手术费未支付,后台待确认
    protected  function isSubscriptionState($order_type,$order_state)
    {
    	$type  = StateConfig::$orderType;
    	$state = StateConfig::$orderStatus['ordertype1'];
    	if($order_type == $type['type1']['val']){
    		 if($state['type3']['val'] == $order_state){
    			return true;
    		 }
    	}
    	return false;
    }
    
    
    //是否是手术预约
    protected  function isSubscription($order_type)
    {
    	$type  = StateConfig::$orderType;
    	if($order_type == $type['type1']['val']){
    		return true;
    	}
    	return false;
    }
    
    //是否是预约诊疗
    protected  function isOrderSubscription($order_type)
    {
    	$type  = StateConfig::$orderType;
    	if($order_type == $type['type5']['val']){
    		return true;
    	}
    	return false;
    }
    
    //是否是Vip
    protected  function isVip($order_type)
    {
    	$type  = StateConfig::$orderType;
    	if($order_type == $type['type12']['val']){
    		return true;
    	}
    	return false;
    }
    
    protected  function nextStateOption($order_type,$order_state)
    {
    	$name = $this->actionOrderTypeAllJson();
    	$pid = $name['data'];
    	$states = $pid[$order_type];
    	return $this->nextState($states, $order_state);
    }
    
    protected static function nextState($states,$order_state,$alias=FALSE)
    {
    	$nextState = FALSE;
    	foreach ($states as $key => $value){
    		if($value['value'] == $order_state){
    			if(isset($states[$key+1])){
	    			if($alias)
	    				$nextState = isset($value['alias'])?
	    			  ['value'=>$states[$key+1]['value'],'name'=>$states[$key+1]['alias']]:
	    			  ['value'=>$states[$key+1]['value'],'name'=>$states[$key+1]['option']];
	    			else{
	    				$nextState =['value'=>$states[$key+1]['value'],'name'=>$states[$key+1]['option']];
	    			}
    			}
    		}
    	}
    	return $nextState;
    }
    
    
    protected  function  stateType($order_type,$order_state)
    {
    	$type  = StateConfig::$orderType;
    	$state = StateConfig::$orderStatus['ordertype1'];
    	$vip   = StateConfig::$orderStatus['ordertype12'];
    	$list = [];
    	$data['option'][] =  ['value'=>'','name' => '请选择'];
    	if($this->isVip($order_type)){
    		$lsit['order_type']['value'] = StateConfig::$orderType['type'.$order_type]['val'];
    		$lsit['order_type']['name']  = StateConfig::$orderType['type'.$order_type]['name'];
    		if(isset($vip['type'.$order_state])){
    		$lsit['order_state']['value'] = $vip['type'.$order_state]['val'];
    		$lsit['order_state']['name']  = $vip['type'.$order_state]['name'];
    		}
    		if($order_state=='2'){
	    		$next = $this->nextStateOption($order_type,$order_state);
	    		if($next)$data['option'][] = $next;
    		}
    	}elseif($this->isSubscription($order_type))
    	{
    		$lsit['order_type']['value']  = StateConfig::$orderType['type'.$order_type]['val'];
    		$lsit['order_type']['name']   = StateConfig::$orderType['type'.$order_type]['name'];
    		$lsit['order_state']['value'] = $state['type'.$order_state]['val'];
    		$lsit['order_state']['name']  = $state['type'.$order_state]['name'];
    		if($state['type2']['val'] == $order_state){
    			$data['option'][] = [
    					'value'=> $state['type3']['val'],
    					'name' => $state['type3']['name']
    			];
    		}elseif($state['type3']['val'] == $order_state)
    		{
    			$data['option'][] = [
    					'value'=> $state['type4']['val'],
    					'name' => $state['type4']['alias']
    			];
    			$data['order_price']= [
    					'type'=>'input',
    					'name'=> 'order_price',
    			];
    		}
    	}else{
    		$lsit['order_type']['value'] = StateConfig::$orderType['type'.$order_type]['val'];
    		$lsit['order_type']['name']  = StateConfig::$orderType['type'.$order_type]['name'];
    		$next = $this->nextStateOption($order_type,$order_state);
    		if($next)$data['option'][] = $next;
    	}
    	return array_merge($lsit,$data);
    }
    
    public function actionOrderTypeJson()
    {
    	$order_id = Yii::$app->getParams->get("order_id");
    	$model    = UserOrder::find()
    	->select(['order_type','order_state'])
    	->where(['order_id'=>$order_id])
    	->asArray()
    	->one();
    	if($model!==NULL){
    		$data = $this->stateType($model['order_type'],$model['order_state']);
    		return  UResponse::formatData('0', 'success',$data);
    	}
    	return  UResponse::formatData('100', '');
    }
    
    
    public function actionOrderTypeAllJson()
    {
    	$data = [
    			'pid' => [
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'手术预约'],
    					['value'=>'3' ,'option'=>'海外医疗'],
    					['value'=> '4','option'=>'绿色通道'],
    					['value'=>'5' ,'option'=>'预约诊疗'],
    					['value'=>'6' ,'option'=>'健康体检'],
    					['value'=> '7','option'=>'生育辅助'],
    					['value'=> '8','option'=>'膝关节手术'],
    					['value'=> '9','option'=>'医疗抗衰'],
    					['value'=> '10','option'=>'第二诊疗意见'],
    					['value'=> '11','option'=>'重症转诊'],
    					['value'=>'12' ,'option'=>'vip服务'],
    					['value'=>'13' ,'option'=>'慈善公益'],
    			],
    			'1' => [
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'咨询服务费未支付'],
    					['value'=>'2' ,'option'=>'咨询服务费已支付'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'4' ,'option'=>'手术费未支付','alias' => '待确认'],
    					['value'=>'5' ,'option'=>'手术费已支付'],
    					['value'=>'6' ,'option'=>'已完成'],
    					['value'=>'7' ,'option'=>'咨询服务取消'],
    					['value'=>'8' ,'option'=>'手术服务取消']
    			 ],
    			'3'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'4'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'5'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'6'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'7'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'8'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'9'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'10'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'11'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'12'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'vip服务未支付'],
    					['value'=>'2' ,'option'=>'vip服务已支付'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    			'13'  =>[
    					['value'=>'' ,'option'=>'请选择'],
    					['value'=>'1' ,'option'=>'已下单'],
    					['value'=>'3' ,'option'=>'待安排'],
    					['value'=>'6' ,'option'=>'已完成'],
    			],
    	];
        return  UResponse::formatData('0', 'success',$data);
    }
    
}
