<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'news_type')->textInput() ?>

    <?= $form->field($model, 'news_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'news_content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'news_summary')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'news_photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'news_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'news_time')->textInput() ?>

    <?= $form->field($model, 'is_top')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
