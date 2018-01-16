getui  push for yii2  
======================
getui  push for yii2,this my first yii2 extensiion,very happy!

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kasole/yii2-getui "*"
```

or add

```
"kasole/yii2-getui": "*"
```

to the require section of your `composer.json` file.


Usage
-----

#components中添加如下信息

	//个推扩展
	'getui'  => [
		'class' => 'kasole\getui\Push',
		'options' => [
			 'host'  => 'http://sdk.open.api.igexin.com/apiex.htm',
			 'appkey' => 'JsU4lNECAs9d9U9mkrZgo6',
			 'appid'  => 'KrOHiZ9EE18ZYO9EVSlXe3',
			 'mastersecret'=>'9sHjfZ34kp5GYGCVJGcWQ5',
			 'cid' => '',
			 'devicetoken' => '',
			 'alias' => '',
		   ],
		'proxy' => [
			CURLOPT_PROXY => '',
			CURLOPT_PROXYPORT => '',
		],
	  ],
#单推
		$data['cid'] 	= Yii::$app->getParams->get('cid');
    	$data['content']= Yii::$app->getParams->get('content');
    	$data['title']  = Yii::$app->getParams->get('title');
    	$data['body']   = Yii::$app->getParams->get('body');
    	$cid 	 = $data['cid']?$data['cid']:'23ba637aafbeedb36c2c97d12806c7ca';
    	$content = $data['content']?$data['content']:'您有一条新的提示消息!';
    	$title	 = $data['title']?$data['title']:'新的提示消息已来袭';
    	$body 	 = $data['body']?$data['body']:'打开我让你好看';
        $push = Yii::$app->getui->Push;
    	$push->setCid($cid);
    	$push->setContent($content);
    	$push->setTitle($title);
    	$push->setBody($body);
    	$rep = $push->pushMessageToSingle();
#多推

	    $content = $data['content']?$data['content']:'您有一条新的提示消息!';
    	$title	 = $data['title']?$data['title']:'新的提示消息已来袭';
    	$body 	 = $data['body']?$data['body']:'打开我让你好看';
    	$push  	 = Yii::$app->getui->Push;
    	$list = json_decode($data['cidList'],true);
    	$list = count($list)?$list:['23ba637aafbeedb36c2c97d12806c7ca'];
    	$push->setTargetList($list);
    	$push->setContent($content);
    	$push->setTitle($title);
    	$push->setBody($body);
    	$rep = $push->pushMessageToList();

 #群推

 	    $content = $data['content']?$data['content']:'您有一条新的提示消息!';
    	$title	 = $data['title']?$data['title']:'新的提示消息已来袭';
    	$body 	 = $data['body']?$data['body']:'打开我让你好看';
    	$push  	 = Yii::$app->getui->Push;
    	$push->setContent($content);
    	$push->setTitle($title);
    	$push->setBody($body);
    	$rep = $push->pushMessageToApp();