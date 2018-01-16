<?php
namespace app\modules\patient\behavior;
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/12/21
 * Time: 11:51
 */
class OrderJson {

    const ORDER_STATE_RECORD = 'process_record';

    static $jsonInfo = [
        self::ORDER_STATE_RECORD => '{}',
    ];

    public static function setValue($key, $value) {

        if (!array_key_exists($key, self::$jsonInfo)) {
            return true;
        }

        if (!is_array($value)) {
            $value = json_decode($value, true);
        }

        $tmpInfo = json_decode(self::$jsonInfo[$key], true);
        foreach($value as $k => $v) {
            $tmpInfo[$k] = $v;
        }
        self::$jsonInfo[$key] = json_encode($tmpInfo);
        return true;
    }

    public static function getValue($key) {

        if (!array_key_exists($key, self::$jsonInfo)) {
            return '{}';
        }
        $result = self::$jsonInfo[$key];
        self::$jsonInfo[$key] = '{}';
        return $result;
    }
}
