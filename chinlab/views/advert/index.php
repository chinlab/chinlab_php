<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvertQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = '广告';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-index">
	<div class="panel panel-default">
    	<div class="panel-heading">
 			<h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="panel-body">
    		<?php  echo $this->render('_search', ['model' => $searchModel]); ?>
	        <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'filterModel'  => $searchModel,
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
	            'ad_title',
	        	[
	        	'attribute' => 'ad_img',
	        	'format'=>'raw',
	        	'value' => function($m){
	        		return Html::img(
	        			$m->ad_img,
	        			['class' => 'img-circle','width' => 160]
	        		 );
	        		}
	        	],
	            //'ad_url:html',
	            [
	            'attribute' => 'is_ok',
	            'value' => function ($model) {
	            	$state = [''=>'未知','0'=>'正常','1'=>'禁用'];
	            	return $state[$model->is_ok];
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
	 </div>
</div>
