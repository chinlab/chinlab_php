<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\CmsAsset;
CmsAsset::register($this);
$roleId = $userInfo['roleId'];
?>
		<div class="navbar-default sidebar" role="navigation">
					<div class="sidebar-nav navbar-collapse side">
						<ul class="nav" id="side-menu">
							<li class="firstMenu">
								<a class="menuc inside"  href="<?=Url::to('/cms/index.php')?>"><i class="iconfont">&#xe601;</i>&nbsp;<span>医疗资讯后台系统</span></a>
								<div id="longbar"></div>
							</li>
							<?php if($roleId == '1' || $roleId == '2' || $roleId == '3'){?>
							<li class="firstMenu">
								<a class="inside" href="javascript:void(0)"><i class="iconfont">&#xe743;</i>&nbsp;资讯管理</a>
								<div id="longbar"></div>
								<ul class="nav nav-second-level">
									<li class="secMenu">
										<a class="menuc"  href="<?=Url::to('/cms/information.php')?>"><i class="iconfont">&#xe602;</i>资讯管理列表</a>
										<div id="shortbar"></div>
									</li>
								</ul>
							</li>
							<?php }?>
							<?php if($roleId == '1' || $roleId == '2' || $roleId == '3'){?>
							<li class="firstMenu">
								<a class="inside" href="javascript:void(0)"><i class="iconfont">&#xea19;</i>&nbsp;广告管理</a>
								<div id="longbar"></div>
								<ul class="nav nav-second-level">
									<li class="secMenu">
										<a class="menuc" href="<?=Url::to('/cms/advertisement.php')?>"><i class="iconfont">&#xe602;</i>内容广告</a>
										<div id="shortbar"></div>
									</li>
								</ul>
							</li>
							<?php }?>
							<?php if($roleId == '1' || $roleId == '3'){?>
							<li class="firstMenu">
								<a class="inside" href="javascript:void(0)"><i class="iconfont">&#xe70e;</i>&nbsp;发布管理</a>
								<div id="longbar"></div>
								<ul class="nav nav-second-level">
									<li class="secMenu">
										<a class="menuc"  href="<?=Url::to('/cms/releasemessage.php')?>"><i class="iconfont">&#xe602;</i>资讯发布</a>
										<div id="shortbar"></div>
									</li>
									<li class="secMenu">
										<a class="menuc" href="<?=Url::to('/cms/releasead.php')?>"><i class="iconfont">&#xe602;</i>开机广告发布</a>
										<div id="shortbar"></div>
									</li>
								</ul>
							</li>
							<?php }?>
							<?php if($roleId == '1' || $roleId == '3'){?>
							<li class="firstMenu">
								<a class="inside" href="javascript:void(0)"><i class="iconfont">&#xe693;</i>&nbsp;频道管理</a>
								<div id="longbar"></div>
								<ul class="nav nav-second-level">
									<li class="secMenu">
										<a class="menuc"  href="<?=Url::to('/cms/channel.php')?>"><i class="iconfont">&#xe602;</i>频道管理列表</a>
										<div id="shortbar"></div>
									</li>
								</ul>
							</li>
							<?php }?>
							<?php if($roleId == '1'){?>
							<li class="firstMenu">
								<a class="inside" href="javascript:void(0)"><i class="iconfont">&#xe62f;</i>&nbsp;用户管理</a>
								<div id="longbar"></div>
								<ul class="nav nav-second-level">
									<li class="secMenu">
										<a class="menuc"  href="<?=Url::to('/cms/user.php')?>"><i class="iconfont">&#xe602;</i>用户管理列表</a>
										<div id="shortbar"></div>
									</li>
								</ul>
							</li>
							<?php }?>
							<?php if($roleId == '1'){?>
							<li class="firstMenu">
								<a class="inside" href="javascript:void(0)"><i class="iconfont">&#xe638;</i>&nbsp;权限管理</a>
								<div id="longbar"></div>
								<ul class="nav nav-second-level">
									<!--<li class="secMenu">
										<a class="menuc" url="jurisdiction.html" href="<?=Url::to('/cms/jurisdiction.php')?>"><i class="iconfont">&#xe602;</i>权限管理列表</a>
										<div id="shortbar"></div>
									</li>-->
									<li class="secMenu">
										<a class="menuc" url="role.html" href="<?=Url::to('/cms/role.php')?>"><i class="iconfont">&#xe602;</i>角色管理</a>
										<div id="shortbar"></div>
									</li>
								</ul>
							</li>
							<?php }?>
						</ul> 
					</div>
					<!-- /.sidebar-collapse -->
		   </div>
		    <div id="info_role" style="display: none"><?= $roleId ?></div>