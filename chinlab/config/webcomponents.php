<?php
return [
    'request' => [
        // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey' => 'MMRkri1bmTOEH1goeb6NKYWlrTw4o-mC',
    ],
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'suffix' => '.php',
        'enableStrictParsing' => false,
        'rules' => [
            'doctor/<controller:\w+>_<action:\w+>' => 'site/error',
            'patient/<controller:\w+>_<action:\w+>' => 'site/error',
            'article/<controller:\w+>_<action:\w+>' => 'site/error',
            '<module:\w+>/<controller:\w+>_<action:\w+>'=>'<module>/<controller>/<action>',
            '<controller:\w+>_<action:\w+>'=>'<controller>/<action>',
        ]
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'user' => [
        'identityClass' => 'app\models\Admin',
        'enableAutoLogin' => true,
    ],
    'assetManager' => [
        'bundles' => [
            'yii\web\JqueryAsset' => [
                'js'=>[]
            ],
            'yii\bootstrap\BootstrapPluginAsset' => [
                'js'=>[]
            ],
            'yii\bootstrap\BootstrapAsset' => [
                'css' => [],
            ],
        ],
    ],
];
