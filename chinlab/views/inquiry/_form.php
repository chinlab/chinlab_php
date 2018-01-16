<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inquiry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inquiry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inquiry_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inquiry_time')->textInput() ?>

    <?= $form->field($model, 'inquiry_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inquiry_gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inquiry_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inquiry_age')->textInput() ?>

    <?= $form->field($model, 'disease_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disease_des')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inquiry_state')->textInput() ?>

    <?= $form->field($model, 'inquiry_reanswer')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'inquiry_retime')->textInput() ?>

    <?= $form->field($model, 'inquiry_file')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
