<?php
define("CONF_ENV", "");
//define("APP_VERSION_FORCE", false);//是否强制升级
$params = require(__DIR__ . '/params.php');
$components = array_merge(require(__DIR__ . '/'.CONF_ENV.'components.php'), require(__DIR__ . '/webcomponents.php'));

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'userApi' => [
            'class' => 'app\modules\service\Service',
        ],
        'patient' => [
            'class' => 'app\modules\patient\Patient',
        ],
        'doctor' => [
            'class' => 'app\modules\doctor\Doctor',
        ],
        'article' => [
            'class' => 'app\modules\article\Article',
        ],
        'subscriber' => [
            'class' => 'app\modules\subscriber\Subscriber',
        ],
    	/*'manager' => [
    		'class' => 'mdm\admin\Module',
    	],*/
    ],
    'components' => $components,
    'params' => $params,
	'aliases' => [
		'@kasole/getui' => '@vendor/kasole/yii2-getui/src',
		'@mdm/admin' => '@vendor/mdmsoft/yii2-admin',
	],
	// 配置语言
	'language'=>'zh-CN',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
