<?php
namespace app\common\application;
use Yii;
/**
 * 资讯配置文件.
 * User: luoning
 * Date: 2017/2/6
 * Time: 15:24
 */
class InfoConfig
{

    /**
     * 固定ID 和 名称
     */
    //热门ID 和 名称
    const CHANNEL_HOT_NO = '0';
    const CHANNEL_HOT_NAME = '热门';
    //首页ID 和 名称
    const CHANNEL_HOME_NO = '1';
    const CHANNEL_HOME_NAME = '首页';
    //开机ID 和 名称
    const CHANNEL_ON_NO = '2';
    const CHANNEL_ON_NAME = '开机画面';
    //海外医疗
    const CHANNEL_OVERSEAS_NO = '3';
    const CHANNEL_OVERSEAS_NAME = '海外医疗';

    /**
     * 素材状态
     */
    const MATERIAL_PRE = '0';
    const MATERIAL_COMMIT = '1';
    const MATERIAL_FINISH = '2';
    const MATERIAL_FAILED = '3';

    /**
     * 素材状态描述
     * @var array
     */
    static $materialDesc = [
        self::MATERIAL_PRE    => "待提交",
        self::MATERIAL_COMMIT => "已提交",
        self::MATERIAL_FINISH => "审核通过",
        self::MATERIAL_FAILED => "审核未通过",
    ];

    /**
     * news_type 资讯类型
     */
    //广告
    const NEWS_TYPE_AD = 0;
    //资讯
    const NEWS_TYPE_INFO = 1;

    /**
     * 列表类型展示类型
     */
    //banner
    const SHOW_TYPE_BANNER = 0;
    //列表单图
    const SHOW_TYPE_SINGLE = 2;
    //列表多图
    const SHOW_TYPE_MULTI = 3;
    //开机动画
    const SHOW_TYPE_ON = 4;

    /**
     * 资讯详情内容
     */
    //图文
    const MEDIA_TYPE_IMAGE_CONTENT = 0;
    //多图
    const MEDIA_TYPE_MULTI_IMAGE = 1;
    //视频
    const MEDIA_TYPE_MEDIA = 2;
    //音频
    const MEDIA_TYPE_VOICE = 3;

    /**
     * 广告条数限制
     */
    static $pageBannerLimit = [
        self::CHANNEL_HOME_NO => 3,
        self::CHANNEL_HOT_NO  => 3,
        self::CHANNEL_OVERSEAS_NO => 3,
        self::CHANNEL_ON_NO   => 1,
        'other'               => 1,
    ];

    /**
     * 内容限制
     */
    static $infoBackend = [
        ['channel_name' => self::CHANNEL_HOME_NAME, 'channel_no' => self::CHANNEL_HOME_NO, 'item' => [["0", "banner"],]],
        ['channel_name' => self::CHANNEL_HOT_NAME, 'channel_no' => self::CHANNEL_HOT_NO, 'item' => [["0", "banner"],]],
        ['channel_name' => self::CHANNEL_OVERSEAS_NAME, 'channel_no' => self::CHANNEL_OVERSEAS_NO, 'item' => [["0", "banner"],]],
    ];

    /**
     * 广告限制
     */
    static $adBackend = [
        ['channel_name' => self::CHANNEL_HOME_NAME, 'channel_no' => self::CHANNEL_HOME_NO, 'item' => [["0", "banner"],]],
        ['channel_name' => self::CHANNEL_HOT_NAME, 'channel_no' => self::CHANNEL_HOT_NO, 'item' => [["0", "banner"],]],
        ['channel_name' => self::CHANNEL_OVERSEAS_NAME, 'channel_no' => self::CHANNEL_OVERSEAS_NO, 'item' => [["0", "banner"],]],
    ];

    /**
     * 获取资讯的所有频道类型及show_type选项
     * @return mixed
     * @throws \yii\base\InvalidRouteException
     */
    public static function getInfoChannel()
    {
        $result = Yii::$app->getModule('article')->runAction('channel/getlistFront');
        foreach($result as $k => $v) {
            $result[$k]['item'] = [
                ['0', "banner"],
                ['2', "资讯列表"],
                ['3', "海外医疗"],
            ];
        }
        foreach(static::$infoBackend as $v) {
            array_unshift($result, $v);
        }
        return $result;
    }

    /**
     * 获取广告的所有频道类型及show_type选项
     * @return mixed
     * @throws \yii\base\InvalidRouteException
     */
    public static function getAdChannel() {
        $result = Yii::$app->getModule('article')->runAction('channel/getlistFront');
        foreach($result as $k => $v) {
            $result[$k]['item'] = [
                ['0', "banner"],
            ];
        }
        foreach(static::$adBackend as $v) {
            array_unshift($result, $v);
        }
        return $result;
    }
}