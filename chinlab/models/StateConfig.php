<?php
namespace app\models;
/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class StateConfig
{

    /*
     * 问诊类型
     */
    static $inquiryStatus = [
        'inquiry_state_no' => ['val' => 1, 'name' => '未回复'],
        'inquiry_state_yes' => ['val' => 2, 'name' => '已回复'],
        'inquiry_state_cancel' => ['val' => 3, 'name' => '已取消'],
    ];

    /**
     * 费用描述
     * @var array
     */
    static $priceInfo = [
        'ordertype1' => [
            'type0' => [
                'id' => '0',
                'price' => '300',
                'desc' => '资讯费用',
            ],
        ],
        'ordertype12' => [
            'type1' => [
                'id' => '1',
                'price' => '299',
                'desc' => '指定科室预约就医，推荐三甲医院副主任医师以上级分诊预约，专业医护人员全程陪同就诊。',
            ],
            'type2' => [
                'id' => '2',
                'price' => '699',
                'desc' => '指定医院科室预约就医，推荐副主任以上医师分诊预约，专业医护人员全程陪同就诊。',
            ],
            'type3' => [
                'id' => '3',
                'price' => '1099',
                'desc' => '点名专家预约就医，推荐相近资历的专家分诊预约，DIY定制诊疗方案，全程专属客服对接服务、专业医护人员全程陪同就诊。',
            ],
        ],
    ];

    static $districtInfo = [
        [
            "district_id" => "110000",
            "district_name" => "北京",
            "district_desc" => "63家三甲医院",
        ],
        [
            "district_id" => "310000",
            "district_name" => "上海",
            "district_desc" => "38家三甲医院",
        ],
        [
            "district_id" => "440100",
            "district_name" => "广州",
            "district_desc" => "39家三甲医院",
        ],
    ];

    /**
     * vip服务类型描述
     * @var array
     */
    static $selectVipDesc = [
        "type1" => "指定病种、科室预约就医",
        "type2" => "指定医院、科室、副主任及主任医生就医",
        "type3" => "指定医院、科室、点名预约专家（非权威）就医",
    ];

    static $canPayStatus = [
        'pay1' => ['val' => '0', 'name' => '不可支付'],
        'pay2' => ['val' => '1', 'name' => '可支付'],
    ];

    /**
     * 订单类型描述
     */
    static $orderType = [
        "type1" => ['val' => '1', 'name' => '手术预约', 'alias' => '科室'],
        "type2" => ['val' => '2', 'name' => '精准预约', 'alias' => ''],
        "type3" => ['val' => '3', 'name' => '海外医疗', 'alias' => ''],
        "type4" => ['val' => '4', 'name' => '绿色通道', 'alias' => ''],
        "type5" => ['val' => '5', 'name' => '手术预约', 'alias' => '医生'],
        "type6" => ['val' => '6', 'name' => '健康体检', 'alias' => ''],
        "type7" => ['val' => '7', 'name' => '生育辅助', 'alias' => ''],
        "type8" => ['val' => '8', 'name' => '膝关节手术', 'alias' => ''],
        "type9" => ['val' => '9', 'name' => '医疗抗衰', 'alias' => ''],
        "type10" => ['val' => '10', 'name' => '第二诊疗意见', 'alias' => ''],
        "type11" => ['val' => '11', 'name' => '重症转诊', 'alias' => ''],
        'type12' => ['val' => '12', 'name' => 'vip服务', 'alias' => ''],
        'type13' => ['val' => '13', 'name' => '慈善公益', 'alias' => ''],
    ];
    /**
     * 订单状态描述
     */
    static $orderStatus = [
        'ordertype1' => [
            'type1' => ['val' => 1, 'name' => '咨询服务费未支付', 'canPay' => '1', 'field' => 'advise_price', 'payDesc' => '资讯费用'],
            'type2' => ['val' => 2, 'name' => '咨询服务费已支付', 'canPay' => '0'],
            'type3' => ['val' => 3, 'name' => '待安排', 'canPay' => '0'],
            'type4' => ['val' => 4, 'name' => '手术费未支付', 'alias' => '待确认', 'canPay' => '1', 'field' => 'order_price', 'payDesc' => '手术费用'],
            'type5' => ['val' => 5, 'name' => '手术费已支付', 'canPay' => '0'],
            'type6' => ['val' => 6, 'name' => '已完成', 'canPay' => '0'],
            'type7' => ['val' => 7, 'name' => '咨询服务取消', 'canPay' => '0'],
            'type8' => ['val' => 8, 'name' => '手术服务取消', 'canPay' => '0'],
        ],
        'ordertype12' => [
            'type1' => ['val' => 1, 'name' => 'vip服务未支付', 'canPay' => '1', 'field' => 'order_price', 'payDesc' => '预约就诊陪诊服务'],
            'type2' => ['val' => 2, 'name' => 'vip服务已支付', 'canPay' => '0'],
        ],
        'ordertype0' => [
            'type1' => ['val' => 1, 'name' => '订单已记录', 'canPay' => '0'],
        	'type2' => ['val' => 2, 'name' => '订单已完成', 'canPay' => '0'],
        ],
    ];


    /*
     * order_comment_handle
     */
    static $order_comment_handle = [
        'comment1' => ['val' => '1', 'name' => '确认'],
        'comment2' => ['val' => '2', 'name' => '取消'],
        'comment3' => ['val' => '3', 'name' => '评论'],
    ];

    /*
     * news_typea
     */
    static $newsTypea = [
        'type1' => ['val' => '1', 'name' => '企业文章'],
        'type2' => ['val' => '2', 'name' => '常见问题'],
        'type3' => ['val' => '3', 'name' => '关于我们'],
        'type4' => ['val' => '4', 'name' => '海外医疗'],
        'type5' => ['val' => '5', 'name' => '绿色通道'],
    ];

    static $tUserRole = [
        'role0' => ['val' => '0', 'name' => '用户'],
        'role1' => ['val' => '1', 'name' => '医生'],
        'role2' => ['val' => '2', 'name' => '医院管理者'],
    ];

    /*
     * 医生分类
     */
    static $doctorPosition = [
        "position1" => ['val' => '1', 'name' => '科室主任'],
        "position2" => ['val' => '2', 'name' => '副主任'],
        "position3" => ['val' => '3', 'name' => '主医医生'],
        "position4" => ['val' => '4', 'name' => '二级教授'],
        "position5" => ['val' => '5', 'name' => '副教授'],
        "position6" => ['val' => '6', 'name' => '副主任医师'],
        "position7" => ['val' => '7', 'name' => '教授'],
        "position8" => ['val' => '8', 'name' => '神经外科主任医师'],
        "position9" => ['val' => '9', 'name' => '医学博士'],
        "position10" => ['val' => '10', 'name' => '主任医师'],
        "position11" => ['val' => '11', 'name' => '专科主任'],
    	"position12" => ['val' => '12', 'name' => '主治'],
    ];

    static $doctorPositionFormat = [];

    //医院等级划分
    static $hospitalLevel = [
        "h1" => ['val' => '1', 'name' => '三级甲等'],
        "h2" => ['val' => '2', 'name' => '三级乙等'],
        "h3" => ['val' => '3', 'name' => '三级丙等'],
        "h4" => ['val' => '4', 'name' => '二级甲等'],
        "h5" => ['val' => '5', 'name' => '二级乙等'],
        "h6" => ['val' => '6', 'name' => '二级丙等'],
        "h7" => ['val' => '7', 'name' => '一级甲等'],
        "h8" => ['val' => '8', 'name' => '一级乙等'],
        "h9" => ['val' => '9', 'name' => '一级丙等'],
    ];


    static $hospitalFormat = [];

    /*
     * 资讯分类
     */
    static $newsType = [
        'health' => ['val' => '0', 'name' => '健康资讯'],
    ];

    /*
     * 是否生效
     */
    static $activeFlag = [
        'yes' => ['val' => 1, 'name' => '生效'],
        'no' => ['val' => 2, 'name' => '失效'],
    ];

    //获取医生职称
    public static function getDoctorPosition($position)
    {
        if (!self::$doctorPositionFormat) {
            foreach (self::$doctorPosition as $k => $v) {
                self::$doctorPositionFormat[$v['val']] = $v['name'];
            }
        }
        return isset(self::$doctorPositionFormat[$position]) ? self::$doctorPositionFormat[$position] : "科室主任";
    }

    //获取医院等级
    public static function getHospitalLevel($level)
    {
        if (!self::$hospitalFormat) {
            foreach (self::$hospitalLevel as $k => $v) {
                self::$hospitalFormat[$v['val']] = $v['name'];
            }
        }
        return isset(self::$doctorPositionFormat[$level]) ? self::$doctorPositionFormat[$level] : "三级甲等";
    }
}
