<?php
namespace app\common\data;
/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class RunData
{

    public $baseUrl;

    private static $data = [];

    private static $allowFields = [
        "userId",
        "doctorId",
        "userInfo",
        "orderId",
        //数据流向
        "isCardToOrder",
        "doctorUserInfo",
    ];

    public function set($key, $value)
    {

        if (!is_string($key) || !in_array($key, static::$allowFields)) {
            return false;
        }
        if ($value === false) {
            return false;
        }
        static::$data[$key] = $value;

        return true;
    }

    public function get($key)
    {
        if (!is_string($key) || !in_array($key, static::$allowFields)) {
            return false;
        }

        return isset(static::$data[$key]) ? static::$data[$key] : false;
    }

    public function clear()
    {
        static::$data = [];

        return true;
    }
}