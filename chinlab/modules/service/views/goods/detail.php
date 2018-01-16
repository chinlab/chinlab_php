<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no, email=no" />
    <title>详情</title>
    <link rel="stylesheet" type="text/css" href="/goods/css/details_info.css?v=2"/>
    <script type="text/javascript" src="/goods/js/rem.js"></script>
    <script type="text/javascript" src="/goods/js/echo.min.js"></script>
    <!--<script type="text/javascript" src="js/lib/flexible.js"></script>
    <script type="text/javascript" src="js/lib/flexible_css.js"></script>-->
</head>
<body>
<div id="viewport">
    <div class="details_warp">
        <div class="details">
            产品内容
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
</body>
<script type="text/javascript">
    echo.init();
</script>
</html>