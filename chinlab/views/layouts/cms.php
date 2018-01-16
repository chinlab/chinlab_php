<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\CmsAsset;
use yii\bootstrap\Modal;
use yii\web\NotFoundHttpException;
CmsAsset::register($this);
//上传控件
CmsAsset::addCss($this,'@web/cms/resource/sbadmin/bower_components/bootstrap-fileinput/css/fileinput.min.css');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/bootstrap-fileinput/js/fileinput.min.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/bootstrap-fileinput/js/zh.js');
//日期控件
CmsAsset::addCss($this,'@web/cms/resource/sbadmin/bower_components/datetimepicker/css/bootstrap-datetimepicker.min.css');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/datetimepicker/js/bootstrap-datetimepicker.min.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/datetimepicker/js/bootstrap-datetimepicker.zh-CN.js');

//dataTable
CmsAsset::addCss($this,'@web/cms/resource/sbadmin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/datatables/media/js/jquery.dataTables.min.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/colResizable/colResizable-1.6.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/js/fnReloadAjax.js');

//summernote plin
CmsAsset::addCss($this,'@web/cms/resource/sbadmin/bower_components/summernote/summernote.css');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/summernote/summernote.min.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/summernote/lang/summernote-zh-CN.js');
CmsAsset::addScript($this,'@web/cms/resource/sbadmin/bower_components/summernote/summernote.min.js');

$baseUrl = $this->assetBundles['app\assets\CmsAsset']->baseUrl.'/';
if($userInfo = Yii::$app->user->identity){
	$userInfo = $userInfo->toArray();
	if(preg_match("/admin/i", $userInfo['username'])){
		$userInfo['roleId'] = 1;
	}
	if(empty($userInfo['roleId'])){
	    throw new NotFoundHttpException("无权访问!");
	}
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
		<link rel="stylesheet" href="<?=$baseUrl?>css/icon.css"/>
		<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>css/common.css"/>
		<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>css/index.css"/>
		<!-- Bootstrap Core CSS -->
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- MetisMenu CSS -->
		<link href="<?=$baseUrl?>resource/sbadmin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
		<!-- Custom CSS -->
		<link href="<?=$baseUrl?>resource/sbadmin/dist/css/sb-admin-2.css" rel="stylesheet">
		<!-- jQuery -->
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Metis Menu Plugin JavaScript -->
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
		<!-- Custom Theme JavaScript -->
		<script src="<?=$baseUrl?>resource/sbadmin/dist/js/sb-admin-2.js"></script>
		<script src="<?=$baseUrl?>resource/sbadmin/bower_components/layer/layer.js"></script>
		<script src="<?=$baseUrl?>js/index.js"></script>
		<!--<script src="js/common.js" type="text/javascript" charset="utf-8"></script>-->
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
					<a class="navbar-brand"  href="<?=Url::to('/cms/index.php')?>"><img src="<?=$baseUrl?>img/logo2.png"/></a>
				</div>

				<ul class="nav navbar-top-links navbar-right nav_right">
					<li>
						<a class="menuc black personal" href="javascript:void(0)"><span>您好：</span><?= $userInfo['username']?></a>
					</li>
					<li>
						<a class="menuc black changePw" href="javascript:void(0)">修改密码</a>
					</li>
					<li>
						<?= Html::a('【退出】', ['/cms/logout'], ['class' => 'menuc black']) ?>
					</li>
				</ul>
				<!-- /.navbar-top-links -->
				<?= $this->render('cmsmenu.php',['userInfo'=>$userInfo])?>		
				<!-- /.navbar-static-side -->
			</nav>
			<!-- Page Content -->
			<div id="page-wrapper" style='padding:15px;'>
				<?= $content ?>
			</div>
			<!-- /#page-wrapper -->
		</div>
      <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
