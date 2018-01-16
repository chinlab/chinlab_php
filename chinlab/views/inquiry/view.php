<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Inquiry */

$this->title = $model->inquiry_id;
$this->params['breadcrumbs'][] = ['label' => 'Inquiries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inquiry-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->inquiry_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->inquiry_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'inquiry_id',
            'user_id',
            'inquiry_time',
            'inquiry_name',
            'inquiry_gender',
            'inquiry_phone',
            'inquiry_age',
            'disease_name',
            'disease_des',
            'inquiry_state',
            'inquiry_reanswer:ntext',
            'inquiry_retime',
            'inquiry_file',
            'create_time:datetime',
            'update_time:datetime',
            'is_delete',
        ],
    ]) ?>

</div>
