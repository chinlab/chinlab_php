<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '预约订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    	'layout'=> '{items}<div class="text-right tooltip-demo">{pager}</div>',
    	'pager'=>[
    			//'options'=>['class'=>'hidden']//关闭分页
    			'firstPageLabel'=>"首页",
    			'prevPageLabel'=>'上页',
    			'nextPageLabel'=>'下页',
    			'lastPageLabel'=>'尾页',
    	],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
        	'order_number',
        	[
        	    'attribute' => 'create_time',
        		'format'    =>  ['date', 'php:Y-m-d H:i:s'],
        		'value'     => 'create_time',
        		'headerOptions' => ['width' => '170'],
        	],
        	[
        	 'attribute' => 'order_type',
        	 'value' => function ($model) {
        		$state = [''=>'未知','1' => '手术预约','2' => '精准预约','3' => '海外医疗','4'=>'绿色通道','5'=>'手术预约','6'=>'健康体检','7'=>'生育辅助','8'=>'膝关节手术','9'=>'医疗抗衰','10'=>'第二诊疗意见','11'=>'重症转诊','12'=>'vip服务','13'=>'慈善公益'];
        		return $state[$model->order_type];
        	  },
        	  'headerOptions' => ['width' => '180']
        	 ],
        	'advise_price',
        	'order_price',
            'order_name',
        	'order_date',
        	'order_city_name',
        	'order_phone',
        	[
        	 'attribute' => 'order_state',
        	 'value' => function ($model) {
        		$state = [''=>'未知','1'=>'咨询服务费未支付','2'=>'咨询服务费已支付','3'=>'待安排','4'=>'手术费未支付','5'=>'手术费已支付','6'=>'已完成','7'=>'资讯服务取消','8'=>'手术服务取消'];
        		return $state[$model->order_state];
        	   },
        	  'headerOptions' => ['width' => '180']
        	], 
        	[
        	//动作列yii\grid\ActionColumn
        	//用于显示一些动作按钮，如每一行的更新、删除操作。
        	'class' => 'yii\grid\ActionColumn',
        	'header' => '操作',
        	'template' => '{view} {update}',//只需要展示查看和更新
        	'headerOptions' => ['width' => '240'],
        	],
        ],
    ]); ?>
</div>
