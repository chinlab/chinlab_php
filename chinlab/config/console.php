<?php
define("CONF_ENV", "");
//define("APP_VERSION_FORCE", TRUE);//是否强制升级
$params = require(__DIR__ . '/params.php');
$components = require(__DIR__ . '/'.CONF_ENV.'components.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','rabbitmq'],
    'controllerNamespace' => 'app\commands',
    'components' => $components,
    'params' => $params,
    'modules' => [
        // ... other modules ...
        'rabbitmq' => [
            'class' => 'app\modules\service\Service',
        ],
//        'patient' => [
//            'class' => 'app\modules\patient\Patient',
//        ],
//        'doctor' => [
//            'class' => 'app\modules\doctor\Doctor',
//        ],
//        'article' => [
//            'class' => 'app\modules\article\Article',
//        ],
        'subscriber' => [
            'class' => 'app\modules\subscriber\Subscriber',
        ],
    ],
	'aliases' => [
		'@kasole/getui' => '@vendor/kasole/yii2-getui/src',
	],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
