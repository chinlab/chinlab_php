<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\InquiryQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '患者问诊管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inquiry-index">
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
        	'inquiry_time',
            'inquiry_name',
            'inquiry_phone',
            'disease_name',
        	[
        		'attribute' => 'inquiry_state',
        		'value' => function ($model) {
        			$state = [''=>'未知','1'=>'问诊中','2'=>'已回复','3'=>'已取消'];
        			return $state[$model->inquiry_state];
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
