<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use mdm\admin\components\MenuHelper;
AppAsset::register($this);
$callback = function($menu){
	$data = json_decode($menu['data'], true);
	$items = $menu['children'];
	$return = [
			'label' => $menu['name'],
			'url' => [$menu['route']],
	];
	//处理我们的配置
	if ($data) {
		//visible
		isset($data['visible']) && $return['visible'] = $data['visible'];
		//icon
		isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
		
		isset($data['class']) && $data['class'] && $return['class'] = $data['class'];
		//other attribute e.g. class...
		$return['options'] = $data;
	}
	//没配置图标的显示默认图标
	(!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'icon-user';
	(!isset($return['class']) || !$return['class']) && $return['class'] = 'menuc';
	$items && $return['items'] = $items;
	return $return;
};
$menu = MenuHelper::getAssignedMenu(Yii::$app->user->id ,null, $callback);
?>
<div class="navbar-default sidebar" role="navigation">
   <div class="sidebar-nav navbar-collapse">
	<ul class="nav" id="side-menu">
<?php 
   $menuMap = '';
   if($menu){
   	  foreach ($menu as $key=>$val){
   	  	$menuMap .= '<li>';
   	  	if(isset($val['label'])){
   	  		$icon = isset($val['icon'])?$val['icon']:'icon-user';
   	  		$menuMap .='<a href="javascript:void(0)"><span class="'.$icon.'"></span>&nbsp;'.$val['label'].'<span class="fa arrow"></span></a>';
   	  	}
   	  	if(isset($val['items'])){
   	  		$menuMap .='<ul class="nav nav-second-level collapse">';
   	  		foreach($val['items'] as $ikey=>$items){
   	  			$class = isset($items['class'])?$items['class']:'';
   	  			$menuMap .='<li><a class="'.$class.'" href="'.$items['url']['0'].'.php">'.$items['label'].'</a></li>';
   	  		}
   	  		$menuMap .='</ul>';
   	  	 }
   	  	 $menuMap .='</li>';
   	  }
   }else{
   	 if(Yii::$app->user->id == 1){
   	 	$menuMap .='<li>';
   		$menuMap.='<a href="javascript:void(0)"><span class="icon-user"></span>&nbsp;权限管理<span class="fa arrow"></span></a>';
   		$menuMap.='<ul class="nav nav-second-level collapse">';
   		$menuMap .='<li><a class="menuc" href="/manager/assignment/index.php">分配列表</a></li>';
   		$menuMap .='<li><a class="menuc" href="/manager/menu/index.php">菜单列表</a></li>';
   		$menuMap .='<li><a class="menuc" href="/manager/role/index.php">角色列表</a></li>';
   		$menuMap .='<li><a class="menuc" href="/manager/permission/index.php">权限列表</a></li>';
   		$menuMap .='<li><a class="menuc" href="/manager/route/index.php">路由列表</a></li>';
   		$menuMap .='</ul>';
   		$menuMap .='</li>';
   	 }
   }
  
   echo $menuMap;
?>
	</ul>
  </div>
	<!-- /.sidebar-collapse -->
</div>
<script src="/chunfeng/js/order_tip.js"></script>

 

