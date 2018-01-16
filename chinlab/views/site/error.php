<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
use Yii;
use yii\helpers\Html;

$reoute = Yii::$app->controller->action->controller->module->requestedRoute;
$user = Yii::$app->user->identity;
if(!$user){
	if( preg_match('/cms/i',$reoute)){
		$url = '/cms_login.php';
		Yii::$app->controller->action->controller->module->layout = 'cms';
	}elseif(preg_match('/chunfeng/i',$reoute)){
		$url = '/site_login.php';
		Yii::$app->controller->action->controller->module->layout = 'main';
	}elseif(preg_match('/groupclient/i',$reoute)){
		$url = '/groupclient_login.php';
		Yii::$app->controller->action->controller->module->layout = false;
	}else{
		$url = '/groupclient_login.php';
	}
}else{
	if( preg_match('/cms/i',$reoute)){
		$url = '/cms_index.php';
		Yii::$app->controller->action->controller->module->layout = 'cms';
	}elseif(preg_match('/chunfeng/i',$reoute)){
		$url = '/chunfeng_home.php';
		Yii::$app->controller->action->controller->module->layout = 'main';
	}elseif(preg_match('/groupclient/i',$reoute)){
		$url = '/groupclient_servicelist.php';
		Yii::$app->controller->action->controller->module->layout = false;
	}else{
		$url = '/groupclient_servicelist.php';
	}
}


$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>
 
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
  
    <span style="color:red">页面将在3秒后跳转...<br/>
</div>
 <script  type="text/javascript">
     setTimeout(function(){
	   window.location="<?= $url ?>";
	 },3000);
 </script> 