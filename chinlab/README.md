### 项目文件说明

项目入口文件：web：web/index.php console：yii
管理后台网址：admin_index.html
用户api网址：userApi/admin_index.html

项目：模块地址->modules
article 文章和广告 内部模块
doctor 医生   内部模块
patient 患者  内部模块
service userApi/controllers 目录  外部模块  用户相关模块
service userApi/commands 目录 外部模块 rabbit队列和console应用入口

tips：内部模块只提供数据 不提供视图渲染

```
'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'suffix' => '.html',
        'enableStrictParsing' => false,
        'rules' => [
            'doctor/<controller:\w+>_<action:\w+>' => 'site/error',  doctor模块禁止web访问
            '<module:\w+>/<controller:\w+>_<action:\w+>'=>'<module>/<controller>/<action>',
            '<controller:\w+>_<action:\w+>'=>'<controller>/<action>',
        ]
    ],
```

增加的一些通用方法
```
 'DBID' => [
        'class' => 'app\common\components\DBID',
    ],
 Yii::$app->DBID->getID("db"."tableName"); 获取主键自增ID
 'runData' => [
            'class' => 'app\common\data\RunData',
  ],
  Yii::$app->runData 缓存一次调用的数据
  'getParams' => [
          'class' => 'app\common\data\GetParams',
  ],
  Yii::$app->getParams 获取get post 参数
  'queue' => [
          'class' => 'app\common\components\RabbitClient',
          'host' => '127.0.0.1',
          'port' => '5672',
          'user' => 'bjtest',
          'pass' => 'bjtest',
          'vhost' => '/bjtest',
      ],
  Yii::$app->queue->send 发送rabbitmq队列消息
      'queueConsumer' => [
              'class' => 'app\common\components\RabbitClient',
              'host' => '127.0.0.1',
              'port' => '5672',
              'user' => 'bjtest',
              'pass' => 'bjtest',
              'vhost' => '/bjtest',
          ],
  Yii::$app->queueConsumer 后台rabbitmq队列执行
```
yii模块调用方法：
```
$userInfo = Yii::$app->getModule('patient')->runAction('user/getinfobysession', ['session'=>$session_key]);
模块patient下面的userController需继承\app\common\controller\Controller自定义的controller
namespace app\modules\patient\controllers;
class UserController extends \app\common\controller\Controller
```

