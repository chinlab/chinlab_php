<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Inquiry */

$this->title = 'Update Inquiry: ' . $model->inquiry_id;
$this->params['breadcrumbs'][] = ['label' => 'Inquiries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->inquiry_id, 'url' => ['view', 'id' => $model->inquiry_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inquiry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
