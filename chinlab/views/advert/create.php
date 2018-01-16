<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Advert */

$this->title = '添加广告';
$this->params['breadcrumbs'][] = ['label' => '广告', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advert-create">
    <div class="panel panel-default">
    	<div class="panel-heading">
 			<h4><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="panel-body">
	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>
      	</div>
     </div>
</div>
