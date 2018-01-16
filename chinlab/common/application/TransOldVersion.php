<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/1/17
 * Time: 10:57
 */
class TransOldVersion
{

    static $apiVersion = [
        "IOS1.0",
        "ANDROID1.1.0",
        "ANDROID1.0",
    ];

    public static function transArticleHtml()
    {

        if (strpos($_SERVER['REQUEST_URI'], "article_pagecontent_") !== false) {
            $arr = parse_url($_SERVER['REQUEST_URI']);
            $path = pathinfo($arr["path"]);
            list(, , $_GET['news_id'], $_GET['flag']) = explode("_", $path['filename']);
            $_SERVER['REQUEST_URI'] = "/userApi/article_pagecontent.php";
            $_SERVER['PATH_INFO'] = "/userApi/article_pagecontent.php";
            $_SERVER['PHP_SELF'] = "/userApi/article_pagecontent.php";
        }

        if (strpos($_SERVER['REQUEST_URI'], "goods_detail_") !== false) {
            $arr = parse_url($_SERVER['REQUEST_URI']);
            $path = pathinfo($arr["path"]);
            list(, , $_GET['id'], $_GET['flag']) = explode("_", $path['filename']);
            $_SERVER['REQUEST_URI'] = "/userApi/goods_detail.php";
            $_SERVER['PATH_INFO'] = "/userApi/goods_detail.php";
            $_SERVER['PHP_SELF'] = "/userApi/goods_detail.php";
        }

        if (strpos($_SERVER['REQUEST_URI'], "goods_info_") !== false) {
            $arr = parse_url($_SERVER['REQUEST_URI']);
            $path = pathinfo($arr["path"]);
            list(, , $_GET['id'], $_GET['flag']) = explode("_", $path['filename']);
            $_SERVER['REQUEST_URI'] = "/userApi/goods_info.php";
            $_SERVER['PATH_INFO'] = "/userApi/goods_info.php";
            $_SERVER['PHP_SELF'] = "/userApi/goods_info.php";
        }

        if (strpos($_SERVER['REQUEST_URI'], "goods_testpage_") !== false) {
            $arr = parse_url($_SERVER['REQUEST_URI']);
            $path = pathinfo($arr["path"]);
            list(, , $_GET['id'], $_GET['flag']) = explode("_", $path['filename']);
            $_SERVER['REQUEST_URI'] = "/userApi/goods_testpage.php";
            $_SERVER['PATH_INFO'] = "/userApi/goods_testpage.php";
            $_SERVER['PHP_SELF'] = "/userApi/goods_testpage.php";
        }


        if (strpos($_SERVER['REQUEST_URI'], "process_orderdetail_") !== false) {
            $arr = parse_url($_SERVER['REQUEST_URI']);
            $path = pathinfo($arr["path"]);
            list(, , $_GET['id'], $_GET['flag']) = explode("_", $path['filename']);
            $_SERVER['REQUEST_URI'] = "/userApi/process_orderdetail.php";
            $_SERVER['PATH_INFO'] = "/userApi/process_orderdetail.php";
            $_SERVER['PHP_SELF'] = "/userApi/process_orderdetail.php";
        }


        if (strpos($_SERVER['REQUEST_URI'], "overseasgoodinfo_detail_") !== false) {
            $arr = parse_url($_SERVER['REQUEST_URI']);
            $path = pathinfo($arr["path"]);
            list(, , $_GET['id'], $_GET['flag']) = explode("_", $path['filename']);
            $_SERVER['REQUEST_URI'] = "/userApi/overseasgoodinfo_detail.php";
            $_SERVER['PATH_INFO'] = "/userApi/overseasgoodinfo_detail.php";
            $_SERVER['PHP_SELF'] = "/userApi/overseasgoodinfo_detail.php";
        }
    }

    public static function trans()
    {

        //旧版本入口
        if (isset($_SERVER['HTTP_APIVERSION'])
            && in_array($_SERVER['HTTP_CLIENTTYPE'] . $_SERVER['HTTP_APIVERSION'], static::$apiVersion)
        ) {
            return true;
        }
        //支付信息
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '_oldversion') !== false) {
            $_SERVER['REQUEST_URI'] = str_replace("_oldversion", "", $_SERVER['REQUEST_URI']);
            if (isset($_SERVER['PATH_INFO'])) {
                $_SERVER['PATH_INFO'] = str_replace("_oldversion", "", $_SERVER['PATH_INFO']);
            }
            if (isset($_SERVER['PHP_SELF'])) {
                $_SERVER['PHP_SELF'] = str_replace("_oldversion", "", $_SERVER['PHP_SELF']);
            }
            return true;
        }
        return false;
    }
}