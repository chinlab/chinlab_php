<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = 'Update Version: ' . $model->version_id;
$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->version_id, 'url' => ['view', 'id' => $model->version_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="version-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
