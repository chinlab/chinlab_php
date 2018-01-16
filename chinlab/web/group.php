<?php
include __DIR__.'/../common/application/TransOldVersion.php';
TransOldVersion::transArticleHtml();
if ($_SERVER['REQUEST_URI'] == "/alipay_pay.asp") {
    $_SERVER['REQUEST_URI'] = "/userApi/pay_alipay.php";
    $_SERVER['PATH_INFO'] = "/userApi/pay_alipay.php";
    $_SERVER['PHP_SELF'] = "/userApi/pay_alipay.php";
}
$arr = parse_url($_SERVER['REQUEST_URI']);
if (isset($arr['query'])) {
    parse_str($arr['query'], $getInfo);
    foreach ($getInfo as $k => $v) {
        $_GET[$k] = $v;
    }
}
if (isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = str_replace(".php/?", ".php?", $_SERVER['REQUEST_URI']);
}
if (isset($_SERVER['DOCUMENT_URI'])) {
    $_SERVER['DOCUMENT_URI'] = trim($_SERVER['DOCUMENT_URI'], '/');
}
//旧版本入口
if (TransOldVersion::trans()) {
    require_once(__DIR__."/../oldversion/web/index.php");
    exit(1);
}

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV_GII') or define('YII_ENV_GII', true);
defined('YII_ENV') or define('YII_ENV', 'test');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);
$application->defaultRoute = 'groupclient/login';
$application->run();
