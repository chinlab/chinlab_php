<?php

use yii\helpers\Html;
use yii\grid\GridView;
$this->title = '管理员';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dataTable_wrapper">
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
				      		'attribute' => 'created_at',
				      		'format' => ['date', 'php:Y-m-d H:i:s'],
				      		'value' => 'created_at',
				      		'headerOptions' => ['width' => '170'],
				      	],
				        'username',
				        'nickname',
				        'userphone',
				        'email:email',
				         [
						   'attribute' => 'status',  
						   'value' => function ($model) {
						   $state = [
							      '0' => '为选择',
							      '1' => '已禁用',
							      '10' =>'已启用',
							  ];
							  return $state[$model->status];
							},
							'headerOptions' => ['width' => '180'] 
						 ],
				         [
				            'attribute' => 'updated_at',
				            'format' => ['date', 'php:Y-m-d H:i:s'],
				            'value' => 'updated_at',
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
        </div>
        <!-- /.panel-body -->
    </div>
    <!-- /.panel -->
</div>
