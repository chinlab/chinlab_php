<?php
namespace app\common\components;
use yii\base\Component;
/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 *
 * @author luoning<lniftt@163.com>
 */
/*
      Yii::$app->db;
      $client = Yii::$app->httpClient->getInstance();
      $response = $client->setUrl('http://www.baidu.com')
          ->send();
      $result = $response->getData();
      $result = $response->getCookies();
      $result = $response->getHeaders();
$response = $client->createRequest()
    ->setMethod('post')
    ->setUrl('http://domain.com/api/1.0/users')
    ->setData(['name' => 'John Doe', 'email' => 'johndoe@domain.com'])
    ->setOptions([
        'proxy' => 'tcp://proxy.example.com:5100', // use a Proxy
        'timeout' => 5, // set timeout to 5 seconds for the case server is not responding
    ])
    ->send();
*/
class HttpClient extends Component {

    public $options = [];

    public function init() {

    }

    public function getInstance() {
        $client = new \yii\httpclient\Client();
        $client->setTransport('yii\httpclient\CurlTransport');
        $curl = $client->createRequest();
        if ($this->options) {
            $curl->addOptions($this->options);
        }
        return $curl;
    }
}