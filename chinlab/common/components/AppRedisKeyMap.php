<?php
namespace app\common\components;
/**
 * redisKey 管理
 *
 * 功能1：统一管理 避免key冲突
 *
 * @author luoning<lniftt@163.com>
 */
class AppRedisKeyMap
{

    /**
     * @var string
     */
    static $idPrefix = 'table.';
    static $idMap = [
        'id' => 'id.',
        "primary_id" => "primary.id.",
        "card" => "card.",
        "order" => "order.",
    ];

    static $userPrefix = 'user.';
    static $userMap = [
        'phone'           => 'phone.',
        'cardserviceconfig' => 'cardserviceconfig',
        'alipay'          => 'alipay',
        'weipay'          => 'weipay',
        'phoneAlias'      => 'phonealias.',
        'sessionAlias'    => 'sessionAlias.',
        'userInfo'        => 'userinfo.',
        'linkpage'        => 'linkpage',
        'collection_list' => 'collection.list.',
        'card' => 'card.',
        'checkrepeat' => 'checkrepeat.',
        'ordertip' => 'ordertip',
        'overseasDependency' => 'overseas.dependency',
        'expireorder' => 'expireorder.',
    ];

    static $doctorPrefix = 'doctor.';
    static $doctorMap = [
        'allsection'            => 'allsection',
        'hospitalSection'       => 'hospitalsection.',
        'hospital'              => 'hospital.',
        'doctor'                => 'doctor.',
        'disease'               => 'disease.',
        'commonSection'         => 'commonsection',
        'commonDisease'         => 'commondisease',
        'hospitalSectionDetail' => 'hospitalsectiondetail',
        'getfamousdoctor'       => 'getfamousdoctor',
        //地区信息
        'district'              => 'district',
        'phoneAlias'      => 'phonealias.',
        'sessionAlias'    => 'sessionAlias.',
        'userInfo'        => 'userinfo.',
    ];

    static $ssdbPrefix = 'ssdb.';
    static $ssdbMap = [
        'phone'      => 'phone.',
        'sessionKey' => 'sessionkey.',
        'uid'        => 'uid.',
    ];

    static $newsPrefix = 'news.';
    static $newsMap = [
        'type'            => 'type.',
        'ad'              => 'ad.',
        'channel'         => 'channel',
        'info_list'       => 'info.list.',
        'info_detail'     => 'info.detail.',
        'info_collection' => 'info.collection.',
        'info_doctor_collection' => 'info.doctor.collection.',
        'goodsnews' => 'info.goods.news.',
        'page_content' => 'page.content.',
    	'publish'=>'publish.',
    ];
	
    //健康卡
    static $cardPrefix = 'card.';
    static $cardMap = [
    		'goods_list'         => 'goods.list.',  //商品列表
    		'good_detail'        => 'good.detail.', //商品详情
    ];

    public static function getOrderExpire($id) {
        return self::$userPrefix.self::$userMap['expireorder'] . $id;
    }

    public static function getCardServiceConfig() {
        return self::$userPrefix.self::$userMap['cardserviceconfig'];
    }

    public static function getOverseasDependency() {
        return self::$userPrefix . self::$userMap['overseasDependency'];
    }

    /**
     * 获取ordertipKey
     *
     * @param $userId
     * @return string
     */
    public static function getOrderTipKey($userId = "") {
        if (!$userId) {
            return self::$userPrefix . self::$userMap['ordertip'];
        }
        return self::$userPrefix . self::$userMap['ordertip'] .".". $userId;
    }
    
    /**
     * 获取主键key
     */
    public static function getPrimaryKey() {

        return self::$idPrefix . self::$idMap["primary_id"]. date("Ymd");
    }

    /**
     * 获取卡密卡号key
     */
    public static function getCardKey($nowTime) {

        return self::$idPrefix . self::$idMap["card"]. $nowTime;
    }

    /**
     * 获取卡密卡号key
     */
    public static function getOrderKey($nowTime) {

        return self::$idPrefix . self::$idMap["order"]. $nowTime;
    }



    /**
     * 检测请求是否重复
     */
    public static function getUserRepeatKey($key) {
        return self::$userPrefix . self::$userMap['checkrepeat'] . $key;
    }

    /**
     * 生成卡密
     * @param $id
     * @return string
     */
    public static function getCardIds($id) {
        return self::$userPrefix . self::$userMap['card'] . $id;
    }

    /**
     * 网页内容缓存
     * @param $id
     * @return string
     */
    public static function getPageContent($id) {

        return self::$newsPrefix . self::$newsMap['page_content']. $id;
    }

    /**
     * 文章点赞功能
     */
    public static function getGoodsNews() {
        return self::$newsPrefix . self::$newsMap['goodsnews']. date("Ymd");
    }

    /**
     * 文章点赞功能(前一天)
     */
    public static function getGoodsNewsPre() {
        return self::$newsPrefix . self::$newsMap['goodsnews']. date("Ymd", time() - 86400);
    }

    public static function getChannel() {
        return self::$newsPrefix . self::$newsMap['channel'];
    }

    /**
     * 用户收藏时使用 (一天后过期)
     */
    public static function getInfoCollection($userId)
    {
        return self::$newsPrefix . self::$newsMap['info_collection'] . $userId;
    }

    /**
     * 用户收藏医生时使用 (一天后过期)
     */
    public static function getInfoDoctorCollection($userId)
    {
        return self::$newsPrefix . self::$newsMap['info_doctor_collection'] . $userId;
    }

    /**
     * 资讯获取列表页 (不过期 只保留一千条)
     */
    public static function getNewsInfoList($type)
    {

        return self::$newsPrefix . self::$newsMap['info_list'] . $type;
    }

    /**
     * 资讯获取详情页 (不过期 只保留一千条)
     */
    public static function getNewsInfoDetail($type)
    {

        return self::$newsPrefix . self::$newsMap['info_detail'] . $type;
    }

    /**
     * 获取地区信息key
     */
    public static function getDistrictKey()
    {
        return self::$doctorPrefix . self::$doctorMap['district'];
    }

    /**
     * 获取引导页缓存Key
     */
    public static function getLinkPage()
    {
        return self::$userPrefix . self::$userMap['linkpage'];
    }

    /**
     * 微信限制key
     */
    public static function getWeipayKey($id)
    {
        return self::$userPrefix . self::$userMap['weipay'] . $id;
    }

    /**
     *   news user
     * @param  $news
     * @return string
     */
    public static function getNewsTypeKey($type)
    {
        return self::$newsPrefix . self::$newsMap['type'] . $type;
    }

    /**
     * @param unknown $type
     *  ad
     */
    public static function getAdTypeKey($type)
    {
        return self::$newsPrefix . self::$newsMap['ad'] . $type;
    }


    /**
     * phone alias
     * @param $phone
     * @return string
     */
    public static function getDoctorUserPhoneAliasKey($phone)
    {
        return self::$doctorPrefix . self::$doctorMap['phoneAlias'] . $phone;
    }

    /**
     * session Alias
     * @param $session
     * @return string
     */
    public static function getDoctorUserSessionAliasKey($session)
    {
        return self::$doctorPrefix . self::$doctorMap['sessionAlias'] . $session;
    }

    /**
     * 用户信息缓存
     * @param $uid
     * @return string
     */
    public static function getDoctorUserInfoKey($uid)
    {
        return self::$doctorPrefix . self::$doctorMap['userInfo'] . $uid;
    }


    /**
     * phone alias
     * @param $phone
     * @return string
     */
    public static function getUserPhoneAliasKey($phone)
    {
        return self::$userPrefix . self::$userMap['phoneAlias'] . $phone;
    }

    /**
     * session Alias
     * @param $session
     * @return string
     */
    public static function getUserSessionAliasKey($session)
    {
        return self::$userPrefix . self::$userMap['sessionAlias'] . $session;
    }

    /**
     * 用户信息缓存
     * @param $uid
     * @return string
     */
    public static function getUserInfoKey($uid)
    {
        return self::$userPrefix . self::$userMap['userInfo'] . $uid;
    }

    /**
     * ssdb Phone Key
     * @param $phone
     * @return string
     */
    public static function getSsdbPhoneKey($phone)
    {
        return self::$ssdbPrefix . self::$ssdbMap['phone'] . $phone;
    }

    /**
     * ssdb Session Key
     * @param $sessionKey
     * @return string
     */
    public static function getSsdbSessionKey($sessionKey)
    {
        return self::$ssdbPrefix . self::$ssdbMap['sessionKey'] . $sessionKey;
    }

    /**
     * 用户ssdb信息缓存Key
     * @param $uid
     * @return string
     */
    public static function getSsdbUidKey($uid)
    {
        return self::$ssdbPrefix . self::$ssdbMap['uid'] . $uid;
    }

    /**
     * 推荐医生
     * @return string
     */
    public static function getFamousDoctorKey()
    {
        return self::$doctorPrefix . self::$doctorMap['getfamousdoctor'];
    }

    /**
     * 常见疾病
     * @return string
     */
    public static function getCommonDiseaseKey()
    {
        return self::$doctorPrefix . self::$doctorMap['commonDisease'];
    }

    /**
     * 常见医院科室
     */
    public static function getHospitalSectionDetailKey($id)
    {
        return self::$doctorPrefix . self::$doctorMap['hospitalSectionDetail'] . $id;
    }

    /**
     * 常见科室
     */
    public static function getCommonSectionKey()
    {
        return self::$doctorPrefix . self::$doctorMap['commonSection'];
    }

    /**
     * 获取医院详情key
     */
    public static function getDiseaseKey($id)
    {
        return self::$doctorPrefix . self::$doctorMap['disease'] . $id;
    }

    /**
     * 获取医院详情key
     */
    public static function getDoctorKey($doctorId)
    {
        return self::$doctorPrefix . self::$doctorMap['doctor'] . $doctorId;
    }


    /**
     * 获取医院详情key
     */
    public static function getHospitalKey($hospitalId)
    {
        return self::$doctorPrefix . self::$doctorMap['hospital'] . $hospitalId;
    }

    /**
     * 获取医院科室key
     */
    public static function getHospitalSectionKey($hospitalKey)
    {
        return self::$doctorPrefix . self::$doctorMap['hospitalSection'] . $hospitalKey;
    }

    /**
     * 支付宝限制key
     */
    public static function getAlipayKey($id)
    {
        return self::$userPrefix . self::$userMap['alipay'] . $id;
    }

    /**
     * 获取科室信息
     * @return string
     */
    public static function getSectionKey()
    {
        return self::$doctorPrefix . self::$doctorMap['allsection'];
    }

    /**
     * table自增key
     * @param $componentsKeyTable
     *
     * @return string
     */
    public static function getIdKey($componentsKeyTable)
    {
        return self::$idPrefix . self::$idMap['id'] . $componentsKeyTable;
    }

    /**
     * 获取用户手机验证码
     * @param $phone
     *
     * @return string
     */
    public static function getUserPhoneKey($phone)
    {
        return self::$userPrefix . self::$userMap['phone'] . $phone;
    }
    
    /**
     * 发布重复提交验证
     */
    public static function adminPublish($key) {
    	return self::$newsPrefix . self::$newsMap['publish'].$key;
    }
    
    /**
     * 获取商品列表
     * @param $goodId
     *
     * @return string
     */
    public static function getGoodsListKey($goodId)
    {
    	return self::$cardPrefix . self::$cardMap['goods_list'] . $goodId;
    }
    
    
    /**
     * 获取商品详情
     * @param $goodId
     *
     * @return string
     */
    public static function getGoodsDetailKey($goodId)
    {
    	return self::$cardPrefix . self::$cardMap['good_detail'] . $goodId;
    }
    
}