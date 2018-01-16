<?php
namespace app\common\data;
use app\common\application\StateConfig;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/15 0015
 * Time: 下午 10:57
 */
class Response
{

    static $code = [
        "AccessDeny" => "10001",         //数据不能为空
        "ListGainError" => "10002",      //列表获取错误
        "InternalError" => "10003",      //内部操作错误
        "InvalidSessionKey" => "10004",  //key无效 注册失败
        "UnknownError" => "10005",       //未知错误
        "InvalidSign" => "10006",        //签名无效
        "SystemError" => "10007",        //系统错误
        "Expire" => "10008",             //过期
        "InvalidArgument" => "10009",    //上传错误
        "PasswdError" => "10010",        //密码错误
        "SendEmailerEeeor" => "10011",   //邮件错误
    ];


    /**
     * @param $state
     * @param $message
     * @param array $array
     *
     * @return array
     */
    public static function formatData($state, $message, $array = array())
    {
        if ($state == 0) {
            return [
                "state" => $state, "nowtime" => strval(time()),"message" => $message, "data" => $array
            ];
        }
        return [
            "state" => $state, "nowtime" => strval(time()), "message" => $message,"data" => ""
        ];
    }

    /**
     * 数据装换
     *
     * */
    public static function xml_to_data($xml)
    {

        if (!$xml) {
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $data;
    }

    /**
     * 列表页初始化为字符串
     */
    public static function messageToString($data) {
        foreach($data as $k => $v) {
            if (is_int($v)) {
                $data[$k] = strval($v);
            }
        }
        return $data;
    }

    /**
     * 微信数据返回转换
     *
     * */
    public static function data_to_xml($params)
    {

        if (!is_array($params) || count($params) <= 0) {
            return false;
        }
        $xml = "<xml>";
        foreach ($params as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}