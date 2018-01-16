<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SectionQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '科室信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="section-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('新建科室', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
            'section_id',
            'section_name',
            'parent_id',
        	[
        	'attribute' => 'can_disease',
        	'value' => function ($model) {
        		//@a:10000000001125-b:
        		if(!$model->can_disease)return '(not set)';
        		$state = preg_replace("~a:[0-9]*(\d+)-b:|@a:[0-9]*(\d+)-b:~i", ",", $model->can_disease);
        		$state = trim($state,',');
        		return $state;
        	 },
        	'headerOptions' => ['width' => '200']
        	],
        	[
        	'attribute' => 'create_time',
        	'format' => ['date', 'php:Y-m-d H:i:s'],
        	'value' => 'create_time',
        	'headerOptions' => ['width' => '170'],
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
