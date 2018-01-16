<?php
namespace app\common\data;
/**
 * 加密类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class Encrypt {

    /**
     * 根据类型生成不同的订单
     * @param $orderType
     *
     * @return bool
     */
    public static function getOrderPrefix($orderType) {

        $maps = [
            "0" => 'dd',
            "1" => 'SS',
            "2" => 'JZ',
            "3" => 'HW',
            "4" => 'LS',
            "12" => "hb",
            "14" => "hb",
        ];
        return isset($maps[$orderType]) ? $maps[$orderType] : '';
    }

    /**
     * 密码加密
     * @param $data
     *
     * @return string
     */
    public static function mymd5_4($data) {
        //先得到密码的密文
        $data = md5($data);
        //再把密文中的英文母全部转为大写
        $data = strtoupper($data);
        //最后再进行一次MD5运算并返回
        return strtoupper(md5($data));
    }

    public static function get_guid($upper = FALSE, $hyphen = "") {
        $charid = md5(uniqid(mt_rand(), true));
        if ($upper) {
            $charid = strtoupper($charid);
        }
        //$hyphen = chr(45); // "-"
        //$uuid = chr(123)// "{"
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        //. chr(125); // "}"
        return $uuid;
    }
}