<?php
use app\common\application\StateConfig;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no, email=no" />
    <title>订单详情</title>
    <link rel="stylesheet" type="text/css" href="/orderprocess/css/swiper.min.css"/>
    <link rel="stylesheet" type="text/css" href="/orderprocess/css/orderDetails.css"/>
    <script src="/orderprocess/js/swiper.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/orderprocess/js/rem.js" type="text/javascript" charset="utf-8"></script>
</head>
<body><div id="viewport">
<?php if ($orderInfo['order_version'] <= 1): ?>
    <div class="state">
        <span style="color: black;">订单当前状态:&nbsp;</span><?= $orderInfo['order_state_desc'] ?>
    </div>
    <div class="prompt">
        <?= $orderInfo['order_tips'] ?>
    </div>
    <ul class="list">
        <li>
            <div class="left designation">
                预约单号
            </div>
            <div class="left content">
                <?= $orderInfo['order_number'] ?>
            </div>

        </li>
        <li>
            <div class="left designation">
                订单时间
            </div>
            <div class="left content">
                <?= $orderInfo['create_time'] ?>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                服务定金
            </div>
            <div class="left content">
                <div class="left designation" style="padding-top:0;width: 4rem;font-size: 0.26rem;color: #626262;">
                    <?= $orderInfo['process_money0'] ?>
                </div>
                <div class="left content" style="padding-top:0;width: 1.3rem;color: #f67227;font-size:0.26rem;text-align: right;">
                    <?php if ($orderInfo['process_status0'] == "0") : echo "未支付"; else: echo "已支付"; endif; ?>
                </div>
            </div>
        </li>
        <li>
            <div class="left designation">
                VIP套餐
            </div>
            <div class="left content">
                <div class="left designation" style="padding-top:0;width: 4rem;font-size: 0.26rem;color: #626262;">
                    <?= $orderInfo['process_money1'] ?>
                </div>
                <div class="left content" style="padding-top:0;width: 1.3rem;color: #f67227;font-size:0.26rem;text-align: right;">
                    <?php if ($orderInfo['process_status1'] == "0") : echo "未支付"; else: echo "已支付"; endif; ?>
                </div>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                客户姓名
            </div>
            <div class="left content">
                <?= $orderInfo['order_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                身份证号
            </div>
            <div class="left content">
                <?= $orderInfo['id_card'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                手机号码
            </div>
            <div class="left content">
                <?= $orderInfo['order_phone'] ?>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                就诊医院
            </div>
            <div class="left content">
                <?= $orderInfo['hospital_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                就诊科室
            </div>
            <div class="left content">
                <?= $orderInfo['section_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                医生姓名
            </div>
            <div class="left content">
                <?= $orderInfo['doctor_name'] ?>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                病例名称
            </div>
            <div class="left content">
                <?= $orderInfo['disease_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                病情描述
            </div>
            <div class="left content">
                <?= $orderInfo['disease_des'] ?>
            </div>
        </li>
    </ul>

<?php else: ?>
    <div class="state">
        <?= $orderInfo['order_state_desc'] ?>
    </div>
    <div class="prompt">
        <?= $orderInfo['order_tips'] ?>
    </div>
    <ul class="list">
        <li>
            <div class="left designation">
                VIP套餐
            </div>
            <div class="left content">
                <?= StateConfig::$priceInfo['ordertype12']['type'.$orderInfo['vip_type']]['name'].":&nbsp;&nbsp;&nbsp;¥".StateConfig::$priceInfo['ordertype12']['type'.$orderInfo['vip_type']]['price'] ?>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                就诊医院
            </div>
            <div class="left content">
                <?= $orderInfo['hospital_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                就诊科室
            </div>
            <div class="left content">
                <?= $orderInfo['section_name'] ?>
            </div>
        </li>
        <?php if ($orderInfo['vip_type'] != "1"): ?>
        <li>
            <div class="left designation">
                医生姓名
            </div>
            <div class="left content">
                <?= $orderInfo['doctor_name'] ?>
            </div>
        </li>
        <?php endif ?>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                客户姓名
            </div>
            <div class="left content">
                <?= $orderInfo['order_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                身份证号
            </div>
            <div class="left content">
                <?= $orderInfo['id_card'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                手机号码
            </div>
            <div class="left content">
                <?= $orderInfo['order_phone'] ?>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                病例名称
            </div>
            <div class="left content">
                <?= $orderInfo['disease_name'] ?>
            </div>
        </li>
        <li>
            <div class="left designation">
                病情描述
            </div>
            <div class="left content">
                <?= $orderInfo['disease_des'] ?>
            </div>
        </li>
    </ul>
    <ul class="list">
        <li>
            <div class="left designation">
                预约单号
            </div>
            <div class="left content">
                <?= $orderInfo['order_number'] ?>
            </div>

        </li>
        <li>
            <div class="left designation">
                订单时间
            </div>
            <div class="left content">
                <?= $orderInfo['create_time'] ?>
            </div>
        </li>
    </ul>
    <!--<div class="list swiper-container">
        <ul class="swiper-wrapper">
            <li class="swiper-slide">
                <img src="img/743.jpg"/>
            </li>
            <li class="swiper-slide">
                <img src="img/743.jpg"/>
            </li>
            <li class="swiper-slide">
                <img src="img/743.jpg"/>
            </li>
            <li class="swiper-slide">
                <img src="img/743.jpg"/>
            </li>
            <li class="swiper-slide">
                <img src="img/743.jpg"/>
            </li>
            <li class="swiper-slide">
                <img src="img/743.jpg"/>
            </li>
        </ul>

    </div> -->
<?php endif; ?>
</div>
<script type="text/javascript">
    var mySwiper = new Swiper ('.swiper-container', {
        slidesPerView : 'auto',
    })
</script>
</body>
</html>
