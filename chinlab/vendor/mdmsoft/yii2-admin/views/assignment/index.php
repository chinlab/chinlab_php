<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */
/* @var $usernameField string */
/* @var $extraColumns string[] */

$this->title = Yii::t('rbac-admin', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    $usernameField,
    [
         'attribute' => 'status',
         'value' => function($model) {
               return $model->status == 10 ?'正常':'禁用';
           },
          'filter' => [
               0 => 'Inactive',
               10 => 'Active'
              ]
         ],
];
if (!empty($extraColumns)) {
    $columns = array_merge($columns, $extraColumns);
}
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
	'buttons' => [
		'view' => function($url, $model) {
		    if($model->status == 10) {
			   return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url);
			}else{
			   return Html::a('<span class="glyphicon glyphicon-lock"></span>', '');
		    }
	     }
	 ]
];

?>
<div class="assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>
