<?php
/**
 * 队列服务类型配置文件
 *
 * User: Administrator
 * Date: 2016/11/13 0013
 * Time: 下午 8:31
 */
namespace app\common\application;

class RabbitConfig {

    /**
     * 发送短信验证码
     */
    const SMS_PHONE_CODE = "sms_phone_code";
    /**
     * 更新用户信息
     */
    const USER_INFO_UPINSERT = "user_info_upinsert";
    /**
     * 系统取消订单
     */
    const SYSTEM_CANCEL_ORDER = "system_cancel_order";
    /**
     * 取消7天订单
     */
    const SYSTEM_CANCEL_FIRST_ORDER = "system_cancel_first_order";
    /**
     *  用户端订单消息推送
     */
    const USER_PUSH_FROM_SYS = "user_push_from_sys";
    /**
     *  用户端待支付订单短信
     */
    const USER_SMS_FROM_SYS = "user_sms_from_sys";
    /**
     *  用户端订单临近过期短信发送
     */
    const ORDER_SMS_EXPIRE_SYS = "order_sms_expire_sys";
    /**
     *  用户端订单付款后短信发送给运营人员
     */
    const ORDER_SMS_FROM_PAY  = "order_sms_from_pay";
    /**
     *  更新一条新闻缓存
     */
    const NEWS_ONE_CACHE   = "news_one_cache";
    /**
     *  更新一千条新闻缓存
     */
    const  NEWS_MORE_CACHE =  "news_more_cache";
    /**
     *  新闻延迟推送
     */
    const NEWS_PUSH_INFO  = "news_push_info";
    /**
     * 根据结束时间清除缓存
     */
    const NEWS_SHOW_END  = "news_show_end";
    /**
     * 创建健康卡
     */
    const NEWS_CARD_CREATE  = "news_card_create";
    /**
     * 重建商品缓存
     */
    const GOODS_INFO_CACHE  = "goods_info_cache";
    /**
     * 系统完成订单 咨询
     */
    const SYSTEM_FINISH_ORDER = "system_finish_order";
    
    /**
     *  问诊超时订单退款
     */
    const SYSTEM_REFUND_ORDER = "system_refund_order";
    
    
    /**
     * 延时队列需要加到该数组
     * @var array
     */
    static $DDLMap = [
        self::SYSTEM_CANCEL_ORDER => 86400,
        self::SYSTEM_CANCEL_FIRST_ORDER => 86400,
        self::SYSTEM_FINISH_ORDER => 86400,
    	self::ORDER_SMS_EXPIRE_SYS => 86400,
    	self::NEWS_ONE_CACHE => 86400, //新闻后台可以指定发布时间
    	self::NEWS_PUSH_INFO => 86400, //新闻后台可以指定推送时间
    	self::NEWS_SHOW_END  => 86400, //根据结束时间清除缓存
    	self::SYSTEM_REFUND_ORDER=>86400, //问诊超时订单退款
    ];

    static $configMap = [
        self::NEWS_CARD_CREATE => "card/createcard",
        self::SMS_PHONE_CODE => "send/phonesend",
        self::USER_INFO_UPINSERT => "user/updateuserinfo",
        self::SYSTEM_CANCEL_ORDER => "user/cancelorder",
        self::SYSTEM_FINISH_ORDER => "user/finishorder",
        self::SYSTEM_CANCEL_FIRST_ORDER => "user/cancelfirstorder",
    	self::USER_PUSH_FROM_SYS => "push/orderpush",
    	self::USER_SMS_FROM_SYS  => "push/smspush",
    	self::ORDER_SMS_EXPIRE_SYS => "push/orderexpirepush",
    	self::ORDER_SMS_FROM_PAY  => "push/orderpaypush",
    	self::NEWS_ONE_CACHE     => 'news/updatenewscacheone',
    	self::NEWS_MORE_CACHE    => 'news/updatenewscachemore',
    	self::NEWS_PUSH_INFO     => 'news/pushlishpush',
    	self::NEWS_SHOW_END      => 'news/delcache',
    	self::GOODS_INFO_CACHE   => 'card/goodscache',
    	self::SYSTEM_REFUND_ORDER => 'card/refundorder',
    ];
}