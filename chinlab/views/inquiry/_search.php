<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InquiryQuery */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="mycommonfade" style="background-color:#000; opacity:0.3; filter:alpha(opacity=30);"></div><div class="col-lg-12">
   <div class="panel panel-default">
        <!-- /.panel-heading -->
       <div class="panel-body">
            <div class="panel-group" id="accordion">
                <?php $form = ActiveForm::begin([
        			'action' => ['index'],
       				 'method' => 'get',
   				 ]); ?>
                 <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" id="closesearchform" data-parent="#accordion" 
                               href="#collapseOne">
                              	  筛选条件<span class="icon-angle-up"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="col-sm-3">
                                <div class="form-group">
                                   <?= $form->field($model, 'inquiry_name',['inputOptions' =>['class' =>'form-control','placeholder'=>"问诊姓名"]])->textInput(['maxlength' => 20]); ?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <?= $form->field($model, 'disease_name',['inputOptions' =>['class' =>'form-control','placeholder'=>"疾病名称"]])->textInput(['maxlength' => 11]); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <?= $form->field($model, 'inquiry_phone',['inputOptions' =>['class' =>'form-control','placeholder'=>"问诊手机号"]])->textInput(['maxlength' => 20]); ?>
                                </div>
                            </div>
                             <div class="col-sm-2">
                                <div class="form-group">
                                	<?= $form->field($model, 'inquiry_state')->dropDownList(['1'=>'问诊中','2'=>'已回复','3'=>'已取消'],['prompt'=>'请选择','class' =>'form-control'])->label('回复状态'); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <?= $form->field($model, 'inquiry_retime',['inputOptions' =>['class' =>'form-control','placeholder'=>"问诊开始时间"]])->textInput(['maxlength' => 20]); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <div class="col-sm-1">
                                         <?= Html::submitButton('搜索', ['class' => 'btn btn-primary','style'=>'margin-top:25px;']) ?>
                                     </div>
                                     <div class="col-sm-1"></div>
                                     <div class="col-sm-1">
                                          <?= Html::resetButton('重置', ['class' => 'btn btn-default','style'=>'margin-top:25px;']) ?>
                                    </div>
                                </div>
                           		 <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
           </div>