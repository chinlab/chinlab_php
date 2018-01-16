<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no, email=no" />
    <title>商品</title>
    <link rel="stylesheet" type="text/css" href="/goods/css/details2.css?v=2"/>
    <link rel="stylesheet" type="text/css" href="/goods/css/swiper.min.css"/>
    <script type="text/javascript" src="/goods/js/rem.js"></script>
    <script type="text/javascript" src="/goods/js/zepto.min.js"></script>
    <script type="text/javascript" src="/goods/js/swiper.min.js"></script>
    <script type="text/javascript" src="/goods/js/echo.min.js"></script>
</head>
<body>
<div id="viewport">
    <div id="header">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(isset($detailInfo['banner_image'])){ ?>
                    <?php foreach ($detailInfo['banner_image'] as $k=>$v){ ?>
                        <div class="swiper-slide">
                            <img src="<?= $v ?>"/>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
        </div>  
    </div>
    <div id="main">
        <div class="title">
            <span><?= $detailInfo['goods_name'] ?></span><!--<b>()</b>-->
        </div>
        <div class="price">
            <div class="price_l left">
                <b>¥<?= $detailInfo['now_price'] ?></b> <span style="text-decoration: line-through">¥<?= $detailInfo['original_price'] ?></span>
            </div>
            <div class="price_r right">
                销售：<span><?= $detailInfo['goods_amount'] ?></span>
            </div>
        </div>
        <div class="clear"></div>
        <div class="statement_warp">
            <p class="statement">
                伙伴医生服务声明
            </p>
        </div>
        <div class="desc_warp">
            <ul>
                <li>
                    <img src="/goods/img/bingo.png"/>
							<span>
								正品低价
							</span>
                </li>
                <li>
                    <img src="/goods/img/bingo.png"/>
							<span>
								企业认证
							</span>
                </li>
                <li>
                    <img src="/goods/img/bingo.png"/>
							<span>
								售出不退
							</span>
                </li>
            </ul>
        </div>
        <div class="line">

        </div>
        <div class="details_warp">
            <div class="details">
                产品内容
            </div>
        </div>
    </div>
    <div class="line">

    </div>
    <ul id="img_list">
        <?php foreach($detailInfo['goods_image'] as $k => $v) { ?>
        <li>
            <img data-echo="<?= $v ?>"/>
        </li>
        <?php } ?>
    </ul>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var mySwiper = new Swiper ('.swiper-container', {
            paginationClickable: true,
            speed: 1000,
            loop: true,
            observer:true,
            observeParents:true,
            autoplayDisableOnInteraction : false,
            autoplay:3000,
            // 如果需要分页器
            pagination: '.swiper-pagination',
        })   
    })
</script>
</body>
<script type="text/javascript">
    echo.init();    
</script>
</html>
