<?php
return [
    /*'sphinx' => [
        'class' => 'yii\sphinx\Connection',
        'dsn' => 'mysql:host=127.0.0.1;port=9306;',
        'username' => '',
        'password' => '',
    ],
    'queue' => [
        'class' => 'app\common\components\RabbitClient',
        'host' => '47.93.28.225',
        'port' => '5672',
        'user' => 'bjtest',
        'pass' => 'bjtest',
        'vhost' => '/bjtest',
    ],
    'queueConsumer' => [
        'class' => 'app\common\components\RabbitClient',
        'host' => '47.93.28.225',
        'port' => '5672',
        'user' => 'bjtest',
        'pass' => 'bjtest',
        'vhost' => '/bjtest',
    ],
    'DBID' => [
        'class' => 'app\common\components\RDBID',
    ],
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 2,
    ],
    'session' => [
        'class' => 'yii\redis\Session',
    ],
    'ssdb' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 2,
    ],
    'cache' => [
        'class' => 'yii\redis\Cache',
    ],*/
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning', 'info'],
                'logFile' => 'E:\xampp\htdocs\beginner\hanyu-php\log\chinlab.log',
                'maxFileSize' => 1024 * 2,
                'maxLogFiles' => 20,
            ],
        ],
    ],
    'db' => [
        'class' => 'yii\db\Connection',
        'masterConfig' => [
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
//            'tablePrefix' => 'tb_', //表前缀
            'attributes' => [
                PDO::ATTR_TIMEOUT => 10,
            ],
        ],
        'masters' => [
            ['dsn' => 'mysql:host=192.168.1.90;dbname=chinlab'],
        ],
//       'slaveConfig' => [
//            'username' => 'root',
//            'password' => '123',
//            'charset' => 'utf8',
//            'tablePrefix' => 'tb_', //表前缀
//            'attributes' => [
//                PDO::ATTR_TIMEOUT => 10,
//            ],
//        ],
//        'slaves' => [
//            ['dsn' => 'mysql:host=192.168.1.90;dbname=chinlab'],
//        ],
    ],
    /*'db_master_one' => [
        'class' => 'yii\db\Connection',
        'masterConfig' => [
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'attributes' => [
                PDO::ATTR_TIMEOUT => 10,
            ],
        ],
        'masters' => [
            ['dsn' => 'mysql:host=localhost;dbname=db_doctor'],
        ],
        'slaveConfig' => [
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            'attributes' => [
                PDO::ATTR_TIMEOUT => 10,
            ],
        ],
        'slaves' => [
            ['dsn' => 'mysql:host=localhost;dbname=db_doctor'],
        ],
    ],
    'rongyun' => [
        'class' => 'app\common\components\rongyun\Rongyun',
        'appKey' => 'cpj2xarlc14tn',
        'appSecret' => 'HDfs3k3IympCB',
        'jsonPath' => "jsonsource/",
    ],
  'db_master_two' => [
        'class' => 'yii\db\Connection',
        'masterConfig' => [
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'attributes' => [
                PDO::ATTR_TIMEOUT => 10,
            ],
        ],
        'masters' => [
            ['dsn' => 'mysql:host=localhost;dbname=db_doctor'],
        ],
        'slaveConfig' => [
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
            'attributes' => [
                PDO::ATTR_TIMEOUT => 10,
            ],
        ],
        'slaves' => [
            ['dsn' => 'mysql:host=localhost;dbname=db_doctor'],
        ],
    ],*/
    'runData' => [
        'class' => 'app\common\data\RunData',
        'baseUrl' => 'https://test.huobanys.com',
    ],
    'getParams' => [
        'class' => 'app\common\data\GetParams',
    ],
    'particle' => [
        'class' => 'app\common\data\Particle',
    ],
    'phpMailer' => [
        'class' => 'app\common\components\PhpMailer',
    ],
    //fdfs上传文件
    'fdfs' => [
        'class' => 'app\common\components\Fdfs',
        'baseUrl' => 'http://192.168.37.133/',
    ],
    'httpClient' => [
        'class' => 'app\common\components\HttpClient',

        'options' => [
            CURLOPT_PROXY => '',
            CURLOPT_PROXYPORT => '',
        ],

    ],
    'qiniu' => [
        'class' => 'crazyfd\qiniu\Qiniu',
        'accessKey' => 'zpY92ZZHmsSYOBhZ0QtGMhS5iJPQQ7DXvpaxhCKG',
        'secretKey' => 'zVghaYhiBSQ-G0iBp4T1irWE2KOOsrgDIledBh9a',
        'domain' => 'http://p2dih97r8.bkt.clouddn.com',
        'bucket' => 'chinlab-image-head',
    ],
    /*'alipay' => [
        'class' => 'app\common\components\AlipayClient',
        'appId' => '2016110402530013',
        'partnerId' => '2088521157569805',
        'sellerId' => 'info@huobanys.com',
        'notifyUrl' => 'https://test.huobanys.com/alipay_pay.asp',
        'privateKeyPath' => __DIR__ . '/rsa_private_key.pem',
        'aliPublicKeyPath' => __DIR__ . '/alipay_public_key.pem',
    ],
    'weipay' => [
        'class' => 'app\common\components\WeixinClient',
        'config' => [
            'APPID' => 'wx4351c8739d86940d',
            'MCHID' => '1418513502',
            'KEY' => 'qa4h9zdxgtjlvj9c53uzrvksslgdij1h',
            'APPSECRET' => '8fa8cc0ddd7118e151b2a76ae622cf04',
            'SSLCERT_PATH' => __DIR__ . '/cert/apiclient_cert.pem',
            'SSLKEY_PATH' => __DIR__ . '/cert/apiclient_key.pem',
            'REPORT_LEVENL' => 1,
            'GONGZONGHAO' => 'wx4351c8739d86940d',
            'GONGZONGHAO_MATCHID' => '1418513502',
            'GONGZONGHAO_KEY' => 'qa4h9zdxgtjlvj9c53uzrvksslgdij1h',
            'GONGZONGHAO_PASS' => '8fa8cc0ddd7118e151b2a76ae622cf04',
            'NOTIFY_URL' => 'https://test.huobanys.com/userApi/pay_weipay.php',
        ],
    ],
    'unionPay' => [
        'class' => 'app\common\components\UnionPayClient',
        'config' => [
            'MER_ID' => '777290058110048',  //银联商户号
            'SDK_SIGN_CERT_PWD' => '000000',// 签名证书密码
            'SDK_SIGN_CERT_PATH' => __DIR__ . '/certs/' . CONF_ENV . 'certs/' . 'acp_' . CONF_ENV . 'sign.pfx',// 签名证书路径
            'SDK_ENCRYPT_CERT_PATH' => __DIR__ . '/certs/' . CONF_ENV . 'certs/' . 'acp_' . CONF_ENV . 'enc.cer',// 密码加密证书（这条一般用不到的请随便配）
            'SDK_VERIFY_CERT_DIR' => __DIR__ . '/certs/' . CONF_ENV . 'certs/',// 验签证书路径
        ],
        'requestUrl' => [
            'SDK_App_Request_Url' => 'https://101.231.204.80:5000/gateway/api/appTransReq.do',//app交易地址
            'SDK_FRONT_NOTIFY_URL' => 'https://test.huobanys.com/userApi/pay_unionpay.php',// 前台通知地址 (商户自行配置通知地址)
            'SDK_FRONT_TRANS_URL' => 'https://test.huobanys.com/userApi/pay_unionpay.php',//后台通知地址 (商户自行配置通知地址，需配置外网能访问的地址)
        ],
        'proxy' => [
            'PROXY' => '',
            'PROXYPORT' => '',
        ],
    ],
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
        'itemTable' => 'auth_item',
        'assignmentTable' => 'auth_assignment',
        'itemChildTable' => 'auth_item_child',
        'defaultRoles' => ['guest'],
    ],
    //个推扩展
    'getui' => [
        'class' => 'kasole\getui\Push',
        'options' => [
            'host' => 'http://sdk.open.api.igexin.com/apiex.htm',
            'appkey' => 'JsU4lNECAs9d9U9mkrZgo6',
            'appid' => 'KrOHiZ9EE18ZYO9EVSlXe3',
            'mastersecret' => '9sHjfZ34kp5GYGCVJGcWQ5',
            'cid' => '',
            'devicetoken' => '',
            'alias' => '',
        ],
        'proxy' => [
            CURLOPT_PROXY => '',
            CURLOPT_PROXYPORT => '',
        ],
    ],*/
];
