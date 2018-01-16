<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no, email=no" />
    <title>海外产品详情</title>
    <link rel="stylesheet" type="text/css" href="/overseasdetail/css/overseasPdetails.css"/>
	<script src="/overseasdetail/js/rem.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<div id="viewport">
			<div class="state">
				<img src="<?= $detailInfo['banner_image'] ?>"/>
			</div>
			<div class="details">
				<?php if ($flag) { ?>
					<p><?= $detailInfo['goods_country'].$detailInfo['goods_name'] ?></p>
					<div class="price">
						<div class="title left">
							伙伴价格：
						</div>
						<div class="originalPrice left">
							<span>¥<?= $detailInfo['sale_price'] ?></span>起
						</div>
						<div class="preferentialPrice left">
							优惠：<span>¥<?= $detailInfo['favoure_price'] ?></span>
						</div>
					</div>
					<div class="projectCharacteristics">
						<div class="title">
							项目特点：
						</div>
						<ul class="keywords">
							<?php foreach($detailInfo['goods_point'] as $k => $v) { ?>
									<li><?= $v ?></li>
							<?php } ?>
						</ul>
					</div>
					<div class="medicalInstitutions">
						<div class="title left">
							医疗机构：
						</div>
						<div class="name left">
							<?= $detailInfo['hospital_name'] ?>
						</div>
					</div>
				<?php } else { ?>
					<p><?= $detailInfo['goods_country'].$detailInfo['hospital_name'] ?></p>
					<div class="price">
						<div class="title left">
							伙伴价格：
						</div>
						<div class="originalPrice left">
							<span>¥<?= $detailInfo['sale_price'] ?></span>起
						</div>
						<div class="preferentialPrice left">
							优惠：<span>¥<?= $detailInfo['favoure_price'] ?></span>
						</div>
					</div>
				<?php } ?>
			</div>
			<ul class="imglist">
				<?php foreach($detailInfo['goods_image'] as $k => $v) {?>
				<li>
					<img src="<?= $v ?>"/>
				</li>
				<?php } ?>
			</ul>
	</div>
</body>
</html>