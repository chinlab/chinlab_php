<?php
namespace app\common\components;

use Yii;
use yii\log\Logger;
use yii\base\Component;

/**
 * 获取主键ID
 * User: user
 * Date: 2017/3/10
 * Time: 11:05
 */
class RDBID extends Component
{

    const SKIP_KEY = "0123456789abcdef2345";
    const SECRET_KEY = "0123456789fdecba4567";

    static $dbObject = null;

    /**
     * 获取主键ID
     */
    public function getID($tableName)
    {

        $nowTimeTen = substr(time(), 0, 9);
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getPrimaryKey();
        if (!$redis->exists($redisKey)) {
            $startTime = intval(substr(strtotime(date("Y-m-d 00:00:00")), 0, 9));
            $hmsetData = [$redisKey];
            for ($i = 0; $i < 8640; $i++) {
                $hmsetData[] = $startTime + $i;
                $hmsetData[] = $i * 497102;
            }
            if (!$redis->executeCommand("hmset", $hmsetData)) {
                $redis->executeCommand("hmset", $hmsetData);
            }
            if (!$redis->expire($redisKey, 86400 * 3)) {
                $redis->expire($redisKey, 86400 * 3);
            }
        }
        $plusNumber = $redis->HINCRBY($redisKey, $nowTimeTen, 1);
        $skip32Key = $key = pack('H20', static::SKIP_KEY); // 10 bytes key
        $cipher = new Skip32Cipher($skip32Key);
        $bin = pack('N', $plusNumber);
        $encrypted = $cipher->encrypt($bin);
        list(, $encryptedInt) = unpack('N', $encrypted);
        return $nowTimeTen . str_pad($encryptedInt, 10, "0", STR_PAD_LEFT);
    }

    public function getOrderId($userID, $orderType) {

        $redis = Yii::$app->redis;
        $nowTime = time();
        $orderType = 10 + $orderType;
        $last = substr($userID, -4);
        $redisKey = AppRedisKeyMap::getOrderKey($last . $nowTime);
        $date = date("ymdHis", $nowTime);
        $cardNumber = $redis->incr($redisKey);
        if ($cardNumber == 1) {
            if (!$redis->expire($redisKey, 600)) {
                $redis->expire($redisKey, 600);
            }
        } elseif($cardNumber > 99) {
            sleep(1);
            return $this->getOrderId($userID, $orderType - 10);
        }
        return substr($date, 0, 6) . $orderType . str_pad(($nowTime % 86400), 5, "0", STR_PAD_LEFT). str_pad($cardNumber, 2, "0", STR_PAD_LEFT) . $last;
    }

    /**
     * 生成卡密
     * @param $payType
     * @param $cardType
     */
    public function getCardID($cardType)
    {
        $redis = Yii::$app->redis;
        $cardType = $cardType + 10;
        $nowTime = time();
        $cardKey = AppRedisKeyMap::getCardKey($cardType . $nowTime);
        $cardNumber = $redis->incr($cardKey);
        if ($cardNumber == 1) {
            if (!$redis->expire($cardKey, 600)) {
                $redis->expire($cardKey, 600);
            }
        } elseif ($cardNumber > 999) {
            sleep(1);
            return $this->getCardID($cardType - 10);
        }
        $date = date('ymdHis', $nowTime);
        $skip32Key = $key = pack('H20', static::SKIP_KEY); // 10 bytes key
        $cipher = new Skip32Cipher($skip32Key);
        $bin = pack('N', substr($date, 4, 6) . str_pad(($nowTime % 86400), 5, "0", STR_PAD_LEFT) . str_pad($cardNumber, 3, "0", STR_PAD_LEFT));
        $encrypted = $cipher->encrypt($bin);
        list(, $encryptedInt) = unpack('N', $encrypted);
        $cardID = $cardType
            . substr($date, 0, 4)
            . str_pad($encryptedInt, 10, "0", STR_PAD_LEFT);
        $skip32Key = $key = pack('H20', static::SECRET_KEY); // 10 bytes key
        $cipher = new Skip32Cipher($skip32Key);
        $bin1 = pack('N', substr($cardID, 0, 10));
        $bin2 = pack('N', substr($cardID, 6, 10));
        $encryp1 = $cipher->encrypt($bin1);
        $encryp2 = $cipher->encrypt($bin2);
        list(, $encrypted1) = unpack('N', $encryp1);
        list(, $encrypted2) = unpack('N', $encryp2);
        return [
            "id"     => $cardID,
            "secret" => str_pad($encrypted1, 10, "0", STR_PAD_LEFT) . str_pad($encrypted2, 10, "0", STR_PAD_LEFT),
        ];
    }


    public function getNewCardID($cardType, $money)
    {
        $cardType = $cardType + 10;
        if (!self::$dbObject) {
            self::$dbObject = new DBID();
        }
        $money = intval($money);
        $key = 543178;
        $tmpNumber = intval(substr(self::$dbObject->getID('db.card_info_secret'), -6)) + 111111;
        $tmp = $key ^ $tmpNumber;
        if (strlen($tmp) != 6) {
            return $this->getNewCardID($cardType, $money);
        }
        $prefix = strlen(strval($money));
        $money = substr(strval($money), 0, 2);
        return [
            "id" => $prefix.$cardType.$money.((substr($tmp, 0, 3) + substr($tmp, 2)) % 9) . $tmp,
            "secret" => $prefix.$cardType.$money.((substr($tmp, 0, 3) + substr($tmp, 2)) % 9) . $tmp,
        ];
    }
}