<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\Modal;
use yii\web\NotFoundHttpException;
AppAsset::register($this);
$baseUrl = $this->assetBundles['app\assets\AppAsset']->baseUrl.'/';
if($userInfo = Yii::$app->user->identity){
	$userInfo = $userInfo->toArray();
}else{
	$userInfo['username'] = false;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		 <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>诺春风医疗系统</title>
		<!-- Bootstrap Core CSS -->
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/css/font-awesome.min.css" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="<?=$baseUrl?>resource/sbadmin/dist/css/sb-admin-2.css" rel="stylesheet">
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
		<!-- Custom Fonts -->
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/jquery/dist/jquery.min.js"></script>
		
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

		
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/layer/layer.js"></script>

		
		
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/datetimepicker/js/bootstrap-datetimepicker.zh-CN.js"></script>

        <!--fileinput plin-->
        <link href="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap-fileinput/css/fileinput.min.css" rel="stylesheet" type="text/css">
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap-fileinput/js/fileinput.min.js"></script>
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap-fileinput/js/zh.js"></script>
        
		<script src="<?=$baseUrl?>js/common.js" type="text/javascript" charset="utf-8"></script>
		
		
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <![endif]-->
	</head>
	<?php $this->head() ?>
    <body>
     <?php $this->beginBody() ?>
		<div id="wrapper">
			<!-- Navigation -->
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
					<?= Html::a('诺春风医疗系统', ['/chunfeng/home'], ['class' => 'navbar-brand','target'=>'iframepage']) ?>
				</div>
				<!-- /.navbar-header -->
				<ul class="nav navbar-top-links navbar-right">
					<li>
					  <a class="menuc black personal" href="javascript:void(0)"><span>您好：</span><?= $userInfo['username']?></a>
				    </li>
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> </a>
						<ul class="dropdown-menu dropdown-user">
							<li>
							   <?= Html::a('个人资料', ['/chunfeng/adminpassword'], ['class' => 'fa fa-gear fa-fw','target'=>'iframepage']) ?>
							</li>
							<li class="divider"></li>
							<li id="logout">
								<?= Html::a('退出登录', ['/site/logout'], ['class' => 'fa fa-sign-out fa-fw','id'=>'logout2']) ?>
							</li>
							
						</ul>
						<!-- /.dropdown-user -->
					</li>
					<!-- /.dropdown -->
				</ul>
				<!-- /.navbar-top-links -->
				<?= $this->render('menu.php')?>		
				<!-- /.navbar-static-side -->
			</nav>
			<!-- Page Content -->
			<div id="page-wrapper" style='padding:15px;'>
				<?= $content ?>
			</div>
			<!-- /#page-wrapper -->
		</div>
		<!-- /#wrapper -->

        <!-- jQuery --> 
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/jquery/dist/jquery.min.js"></script> 
        <!-- Bootstrap Core JavaScript --> 
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 

        <!-- Metis Menu Plugin JavaScript --> 
        <script src="<?=$baseUrl?>resource/sbadmin/bower_components/metisMenu/dist/metisMenu.min.js"></script> 

        <!-- Custom Theme JavaScript --> 
        <script src="<?=$baseUrl?>resource/sbadmin/dist/js/sb-admin-2.js"></script>
        <!--左边菜单控制切换右侧内容js-->
		<!--iframe自适应内容高度js-->
		<script type="text/javascript" language="javascript">
			function iFrameHeight() {
				var ifm = document.getElementById("iframecon");
				var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
				if(ifm != null && subWeb != null) {
					ifm.height = $(window).height() - 60;
				}
			}
		</script>
      <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
