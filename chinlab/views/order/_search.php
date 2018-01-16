<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderQuery */
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
                                   <?= $form->field($model, 'order_number',['inputOptions' =>['class' =>'form-control','placeholder'=>"订单编号"]])->textInput(['maxlength' => 20])->label('订单号'); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                	<?= $form->field($model, 'order_type')->dropDownList(['1' => '手术预约','2' => '精准预约','3' => '海外医疗','4'=>'绿色通道','5'=>'手术预约','6'=>'健康体检','7'=>'生育辅助','8'=>'膝关节手术','9'=>'医疗抗衰','10'=>'第二诊疗意见','11'=>'重症转诊','12'=>'vip服务','13'=>'慈善公益'],['prompt'=>'请选择','class' =>'form-control'])->label('预约类型'); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                	<?= $form->field($model, 'order_state')->dropDownList(['1'=>'咨询服务费未支付','2'=>'咨询服务费已支付','3'=>'待安排','4'=>'手术费未支付','5'=>'手术费已支付','6'=>'已完成','7'=>'资讯服务取消','8'=>'手术服务取消'],['prompt'=>'请选择','class' =>'form-control'])->label('就诊状态')?>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <?= $form->field($model, 'order_name',['inputOptions' =>['class' =>'form-control','placeholder'=>"患者姓名"]])->textInput(['maxlength' => 11]); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <?= $form->field($model, 'order_phone',['inputOptions' =>['class' =>'form-control','placeholder'=>"患者手机号"]])->textInput(['maxlength' => 20]); ?>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <?= $form->field($model, 'create_time',['inputOptions' =>['class' =>'form-control','placeholder'=>"订单开始时间"]])->textInput(['maxlength' => 20]); ?>
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
