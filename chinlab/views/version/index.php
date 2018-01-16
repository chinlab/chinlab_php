<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VersionQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Versions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-index">
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
            'version_name',
            'version_design:ntext',
            'version_url:url',
            'version_time',
            'version_device',
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
