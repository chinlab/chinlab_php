<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
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
            //'user_id',
            'user_name',
            //'user_pass',
        	[
        	 'label'=>'用户头像',
        	 'format'=>'raw',
        	 'value'=> function($m){
        			return Html::img(
        				$m->user_img,
        				['class' => 'img-circle','width' => 60]
        			);
        		}
        	],
            'user_mobile',
            // 'session_key',
            'user_regtime',
            // 'role',
            // 'create_time:datetime',
            // 'update_time:datetime',
            // 'is_delete',
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
