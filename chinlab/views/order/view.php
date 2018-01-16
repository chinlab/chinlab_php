<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_id], [
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
            'order_id',
            'user_id',
            'hospital_id',
            'order_number',
            'order_type',
            'order_time',
            'order_name',
            'order_gender',
            'order_phone',
            'order_age',
            'order_city',
            'order_city_name',
            'order_date',
            'disease_name',
            'disease_des',
            'order_state',
            'order_update_time',
            'order_design',
            'create_time:datetime',
            'update_time:datetime',
            'order_file',
            'is_delete',
        ],
    ]) ?>

</div>
