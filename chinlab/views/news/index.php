<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '资讯';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
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
        	[
        	'attribute' => 'news_type',
        	'value' => function ($model) {
        		$state = [''=>'未知','0'=>'健康资讯','1'=>'企业文章','2'=>'常见问题','3'=>'关于我们','4'=>'海外医疗','5'=>'绿色通道'];
        		return $state[$model->news_type];
        	  },
        	 'headerOptions' => ['width' => '180']
        	],
            'news_title',
        	[
        	'attribute' => 'news_photo',
        	'format'=>'raw',
        	'value' => function($m){
        		return Html::img(
        			$m->news_photo,
        			['class' => 'img-circle','width' => '100']
        		 );
        		}
        	],
            'news_url:url',
            'news_time',
            [
            'attribute' => 'is_top',
            'value' => function ($model) {
            	$state = [''=>'未知','0'=>'不推荐','1'=>'推荐'];
            	return $state[$model->is_top];
            },
            'headerOptions' => ['width' => '120']
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
