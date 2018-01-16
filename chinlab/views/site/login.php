<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\assets\AppAsset;
AppAsset::register($this);
$this->title = '诺春风医疗系统';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = $this->assetBundles['app\assets\AppAsset']->baseUrl.'/';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?= Html::encode($this->title) ?></title>
               <?= Html::csrfMetaTags(); ?>
        <!-- Bootstrap Core CSS -->
        <link href="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="<?=$baseUrl?>resource/sbadmin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="<?=$baseUrl?>resource/sbadmin/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="<?=$baseUrl?>resource/sbadmin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">诺春风医疗后台管理系统</h3>
                        </div>
                        <div class="panel-body">
	                         <?php $form = ActiveForm::begin([
	        					'id' => 'login-form',
					    	 ]); ?>
                               <fieldset>
                                    <?php if(strlen($message)){?>
		                            <div class="alert alert-warning alert-dismissable" id="Login-error-alert">
		                                 <?=$message?>
		                            </div>
                           			<?php }?>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="用户名" name="LoginForm[username]" type="text" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="密码" name="LoginForm[password]" type="password" value="">
                                    </div>
                                    <div class="code">
										<div class="left">
											<input type="text" name="LoginForm[verifyCode]" id="verify_code" placeholder="输入右侧验证码"/>
										</div>
										<div class="codeImg right">
											<div class="code_img">
												<img  src="<?=Url::to("/groupclient/captcha.php")?>"/>
											</div>
											<a href="#" class="changeImg">看不清？换一张</a>
										</div>
									</div>
                                    <?= Html::submitButton('登陆', ['class' => 'btn btn-lg btn-success btn-block', 'name' => 'login-button']) ?>
                                </fieldset>
                              <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery --> 
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/jquery/dist/jquery.min.js"></script> 

        <!-- Bootstrap Core JavaScript --> 
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 

        <!-- Metis Menu Plugin JavaScript --> 
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/metisMenu/dist/metisMenu.min.js"></script> 

        <!-- Custom Theme JavaScript --> 
        <script src="<?=$baseUrl?>resource/sbadmin/dist/js/sb-admin-2.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            var message = $("#Login-error-alert").text();
         	if(message !== 'false' &&  message.length > 0){
         	 	$("#Login-error-alert").show(300).delay(2000).hide(300); 
         	}
        });
		$(".changeImg").on("click",function(){
			var url = "/site/login.php?t="+(new Date()).getTime();
			$(window).attr('location',url);
		});
		</script>
    </body>
</html>

