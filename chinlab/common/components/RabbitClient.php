<?php
namespace app\common\components;
/**
 * rabbitmq 操作类封装
 *
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 下午 9:28
 */
use Yii;
use yii\log;
use yii\log\Logger;
use app\common\application\RabbitConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use yii\web\Application;
use yii\base\Component;

class RabbitClient extends Component
{

    public $host = "";
    public $port = "";
    public $user = "";
    public $pass = "";
    public $vhost = "";
    private $connection = null;
    private $channel = [];


    public function init()
    {

    }

    /**
     * 发送队列
     * @param $msg
     * @param $msgType
     * @param int $delayTime
     * @return bool
     */
    public function send($msg, $msgType, $delayTime = 0)
    {
        try {
            if (!array_key_exists($msgType, RabbitConfig::$configMap)) {
                return false;
            }
            if (is_array($msg)) {
                $msg = json_encode($msg, 256);
            }
            //正常队列
            $messageTTL = 0;
            $isDelayFlag = false;
            //延时队列
            if (isset(RabbitConfig::$DDLMap[$msgType])) {
                $messageTTL = RabbitConfig::$DDLMap[$msgType] * 1000;
                $isDelayFlag = true;
            }
            if ($delayTime) {
                $messageTTL = $delayTime * 1000;
            }
            $connectionConfig = [
                "host" => $this->host,
                "port" => $this->port,
                "user" => $this->user,
                "pass" => $this->pass,
                "vhost" => $this->vhost,
            ];
            if (!$this->connection) {
                $this->connection = new AMQPStreamConnection(
                    $connectionConfig['host'],
                    $connectionConfig['port'],
                    $connectionConfig['user'],
                    $connectionConfig['pass'],
                    $connectionConfig['vhost']
                );
            }
            if (!isset($this->channel[$msgType])) {
                $this->channel[$msgType] = $this->connection->channel();
                //正常队列
                if (!$isDelayFlag) {
                    $this->channel[$msgType]->queue_declare($msgType, false, true, false, false);
                    $this->channel[$msgType]->exchange_declare($msgType, 'direct', false, true, false);

                    //延时队列
                } else {
                    $this->channel[$msgType]->exchange_declare($msgType, 'x-delayed-message', false, true, false, false, false, new AMQPTable([
                        "x-delayed-type" => "direct",
                    ]));
                    $this->channel[$msgType]->queue_declare($msgType, false, true, false, false, false, new AMQPTable([
                        "x-dead-letter-exchange" => "delayed",
                    ]));
                }
                $this->channel[$msgType]->queue_bind($msgType, $msgType);
            }
            if (!$isDelayFlag) {
                $message = new AMQPMessage($msg, ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
            } else {
                $headers = new AMQPTable(["x-delay" => $messageTTL]);
                $message = new AMQPMessage($msg, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
                $message->set('application_headers', $headers);
            }
            $this->channel[$msgType]->basic_publish($message, $msgType);
            return true;
        } catch (\Exception $e) {
            Yii::getLogger()->log("your site has been hacked", Logger::LEVEL_ERROR);
        }
        return false;
    }

    /**
     * 关闭Rabbit链接 常驻程序发完消息后需调用
     *
     * @return bool
     */
    public function close()
    {
        try {
            if ($this->connection && method_exists($this->connection, "close")) {
                $this->connection->close();
            }
            $this->connection = null;
            foreach ($this->channel as $k => $v) {
                if (method_exists($this->channel[$k], "close")) {
                    $this->channel[$k]->close();
                }
                unset($this->channel[$k]);
            }
        } catch (\Exception $e) {

        }
        return true;
    }


    /**
     * @param $exchange
     * @param $serviceModulePath
     */
    public function consumer($exchange, $serviceModulePath)
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->pass,
                $this->vhost
            );
            $channel = $this->connection->channel();
            if (!isset(RabbitConfig::$DDLMap[$exchange])) {
                $channel->queue_declare($exchange, false, true, false, false);
                $channel->exchange_declare($exchange, 'direct', false, true, false);
            } else {
                $channel->queue_declare($exchange, false, true, false, false, false, new AMQPTable([
                    "x-dead-letter-exchange" => "delayed",
                ]));
                $channel->exchange_declare($exchange, 'x-delayed-message', false, true, false, false, false, new AMQPTable([
                    "x-delayed-type" => "direct",
                ]));
            }

            $function = function($message) use ($serviceModulePath) {
                    $body = json_decode($message->body, true);
                    if (is_array($body)) {
                        $module = Yii::$app->getModule('rabbitmq');
                        $result = $module->runAction($serviceModulePath, $body);
                        if ($result) {
                            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                        }
                    } else {
                        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
                    }
                    Yii::getLogger()->flush();
                    Yii::$app->db->close();
                    Yii::$app->redis->close();
                    Yii::$app->ssdb->close();
                    Yii::$app->queue->close();
                    Yii::$app->trigger(Application::EVENT_AFTER_REQUEST);
            };
            //超过10000个未完成任务 MQ将不再往该队列发送消息
            $channel->basic_qos(null, 10000, null);
            $channel->queue_bind($exchange, $exchange);
            $channel->basic_consume($exchange, "consumer", false, false, false, false, $function);
            register_shutdown_function([$this, 'shutdown'], $channel, $this->connection);
            while (count($channel->callbacks)) {
                $channel->wait();
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            Yii::getLogger()->log("your site has been hacked", Logger::LEVEL_ERROR);
        }
    }

    /**
     * 释放资源
     *
     * @param $channel
     * @param $connection
     */
    public function shutdown($channel, $connection)
    {
        $channel->close();
        $connection->close();
    }
}
