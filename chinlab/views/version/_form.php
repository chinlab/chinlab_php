<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Version */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="version-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'version_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version_design')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'version_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version_time')->textInput() ?>

    <?= $form->field($model, 'version_device')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
