<?php
namespace app\common\application;
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

    //7天过期
    const LIMIT_INQUIRY  =  1;
    //30分钟取消订单
    const CANNAL_ORDER   =  1800;
    /*
     * 问诊类型
     */
    static $inquiryStatus = [
        'inquiry_state_no'     => ['val' => 1, 'name' => '未回复'],
        'inquiry_state_yes'    => ['val' => 2, 'name' => '已回复'],
        'inquiry_state_cancel' => ['val' => 3, 'name' => '已取消'],
    ];

    /**
     * 费用描述
     * @var array
     */
    static $priceInfo = [
        'ordertype12' => [
            'type1' => [
                'id'          => '1',
                'price'       => '399.00',
                'type'        => '0',
                'commit_type' => 'B',
                'name'        => '指定医院、科室就医陪诊',
                'image_url'   => 'http://files.huobanys.com/group1/M00/00/22/ChvHbFjYwEOAfwtGAAA-xyPcvE4326.jpg',
                'desc'        => '指定一家医院和科室，由我方根据病情描述推荐擅长本病种相应专家，并协助挂号，诊前指导意见，专业医护人员全程陪同就诊，协助取号、诊疗陪同、检查引导、院内取药、医院和医生情况介绍等服务。',
            ],
            'type2' => [
                'id'          => '2',
                'price'       => '999.00',
                'type'        => '0',
                'commit_type' => 'C',
                'name'        => '指定副主任医师就医陪诊',
                'image_url'   => 'http://files.huobanys.com/group1/M00/00/22/ChvHbFjYwGeANTMhAABSoaiM_9w757.jpg',
                'desc'        => '指定的副主任医师职称的医生就医+专业陪诊，诊前指导意见，专业医护人员全程陪同就诊，协助取号、诊疗陪同、检查引导、院内取药、医院和医生情况介绍等服务。',
            ],
            'type3' => [
                'id'          => '3',
                'price'       => '1599.00',
                'type'        => '0',
                'name'        => '指定主任医师就医陪诊',
                'commit_type' => 'C',
                'image_url'   => 'http://files.huobanys.com/group1/M00/00/22/ChvHbFjYwJCAYSKxAABhDTieDlI760.jpg',
                'desc'        => '指定的主任医师职称的医生就医+专业陪诊，诊前指导意见，专业医护人员全程陪同就诊，协助取号、诊疗陪同、检查引导、院内取药、医院和医生情况介绍等服务。',
            ],
            'type4' => [
                'id'          => '4',
                'price'       => '2999.00',
                'type'        => '0',
                'name'        => '指定权威高端诊疗协助',
                'commit_type' => 'C',
                'image_url'   => 'http://files.huobanys.com/group1/M00/00/22/ChvHbFjYwL6AJySbAABYePqT0Kw361.jpg',
                'desc'        => '指定三甲医院科室正副主任等权威专家的高端面诊协助+专业陪诊，诊前指导意见，专业医护人员全程陪同就诊，协助取号、诊疗陪同、检查引导、院内取药、医院和医生情况介绍等服务。',
            ],
        ],
        'ordertype20' => [
            'type1' => [
                'id'          => '1',
                'price'       => '399.00',
                'name'        => '协助就医',
                'image_url'   => 'https://mainapp.huobanys.com/img/xiezhujiuyi.png',
                'detail_url'   => 'https://mainapp.huobanys.com/assistSeeDoctor.html',
                'desc'        => '陪诊,待诊,挂号',
            ],
            'type2' => [
                'id'          => '2',
                'price'       => '500.00',
                'name'        => '快速检查',
                'image_url'   => 'https://mainapp.huobanys.com/img/kuaisujiancha.png',
                'detail_url'   => 'https://mainapp.huobanys.com/quickCheck.html',
                'desc'        => '权威医院,精准聚焦',
            ],
            'type3' => [
                'id'          => '3',
                'price'       => '500.00',
                'name'        => '住院加急',
                'image_url'   => 'https://mainapp.huobanys.com/img/zhuyuanjiaji.png',
                'detail_url'   => 'https://mainapp.huobanys.com/hasteHospitalization.html',
                'desc'        => '快速,一站式入院',
            ],
            'type4' => [
                'id'          => '4',
                'price'       => '500.00',
                'name'        => '代诊服务',
                'image_url'   => 'https://mainapp.huobanys.com/img/daizhenfuwu.png',
                'detail_url'   => 'https://mainapp.huobanys.com/forMedical.html',
                'desc'        => '专业，真实有效',
            ],
        ],
    ];
    /*
      *  医生vip就医价格
      */
    static $doctorVipPosition = [
        [
            "doctor_position" => "99",
            "district_desc" => "不指定医师",
            'price'=>'399.00',
        ],
        [
            "doctor_position" => "6",
            "district_desc" => "副主任医师",
            'price'=>'999.00',
        ],
        [
            "doctor_position" => "10",
            "district_desc" => "主任医师",
            'price'=>'1599.00',
        ],
    ];
    /**
     * 医院类型描述
     * @var array
     */
    static $districtInfo = [
        [
            "district_id"   => "110000",
            "district_name" => "北京",
            "district_desc" => "63家三甲医院",
        ],
        [
            "district_id"   => "310000",
            "district_name" => "上海",
            "district_desc" => "38家三甲医院",
        ],
        [
            "district_id"   => "440100",
            "district_name" => "广州",
            "district_desc" => "39家三甲医院",
        ],
    ];

    /**
     * vip服务类型描述
     * @var array
     */
    static $selectVipDesc = [
        "type1" => "预约就医陪诊服务（套餐一）",
        "type2" => "预约就医陪诊服务（套餐二）",
        "type3" => "预约就医陪诊服务（套餐三）",
        "type4" => "预约就医陪诊服务（套餐四）",
    ];

    /**
     * 支付类型描述
     * @var array
     */
    static $canPayStatus = [
        'pay1' => ['val' => '0', 'name' => '不可支付'],
        'pay2' => ['val' => '1', 'name' => '可支付'],
    ];

    /**
     * 搜索ListMap
     * @var array
     */
    static $orderListMap = [
        "t1"  => 'app\modules\patient\models\OrderSurgery',
        "t9"  => 'app\modules\patient\models\OrderTreat',
        "t13" => 'app\modules\patient\models\OrderCommonweal',
        "t10" => 'app\modules\patient\models\OrderOverseas',
        "t12" => 'app\modules\patient\models\OrderAccompany',
    	"t16" => 'app\modules\patient\models\OrderGroupCustomer',
        "t17" => 'app\modules\patient\models\OrderGroupCustomer',
        "t58" => 'app\modules\patient\models\OrderCardService',
    	"t19" => 'app\modules\patient\models\OrderInquiryOnline',
        "t20" => 'app\modules\patient\models\OrderAccompany',
        "t21" => 'app\modules\patient\models\OrderAccompany',
        "t999" => 'app\modules\patient\models\OrderMultiBackend',
    ];

    /**
     * 搜索payMap
     * @var array
     */
    static $orderPayMap = [
        "t1"  => 'app\modules\patient\models\PaySurgery',
        "t9"  => 'app\modules\patient\models\PayTreat',
        "t13" => 'app\modules\patient\models\PayCommonweal',
        "t10" => 'app\modules\patient\models\PayOverseas',
        "t12" => 'app\modules\patient\models\PayAccompany',
    	"t16" => 'app\modules\patient\models\PayGroupCustomer',
        "t17" => 'app\modules\patient\models\PayGroupCustomer',
        "t58" => 'app\modules\patient\models\PayCardService',
    	"t19" => 'app\modules\patient\models\PayInquiryOnline',
        "t20" => 'app\modules\patient\models\PayAccompany',
        "t21" => 'app\modules\patient\models\PayAccompany',
        "t999" => 'app\modules\patient\models\PayMultiBackend',
    ];

    /**
     * 当前配置版本号
     * @var string
     */
    static $nowOrderVersion = "2";

    /**
     * 订单类型描述
     */
    static $orderType = [
        "type1"  => [
            'val'        => '1',
            'name'       => '手术预约',
            'alias'      => '科室',
            'orderTable' => 'app\modules\patient\models\OrderSurgery',
            'payTable'   => 'app\modules\patient\models\PaySurgery',
            'page'       =>  '0',
            'list'       => '101',
        ],
        "type2"  => [
            'val'        => '2',
            'name'       => '精准预约',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCommonweal',
            'payTable'   => 'app\modules\patient\models\PayCommonweal',
            'page'       =>  '0',
            'list'       => '113',
        ],
        "type3"  => [
            'val'        => '3',
            'name'       => '海外医疗',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        "type4"  => [
            'val'        => '4',
            'name'       => '绿色通道',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCommonweal',
            'payTable'   => 'app\modules\patient\models\PayCommonweal',
            'page'       =>  '0',
            'list'       => '113',
        ],
        "type5"  => [
            'val'        => '5',
            'name'       => '预约诊疗',
            'alias'      => '医生',
            'orderTable' => 'app\modules\patient\models\OrderTreat',
            'payTable'   => 'app\modules\patient\models\PayTreat',
            'page'       =>  '0',
            'list'       => '109',
        ],
        "type6"  => [
            'val'        => '6',
            'name'       => '健康体检',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        "type7"  => [
            'val'        => '7',
            'name'       => '生育辅助',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        "type8"  => [
            'val'        => '8',
            'name'       => '膝关节手术',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        "type9"  => [
            'val'        => '9',
            'name'       => '医疗抗衰',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        "type10" => [
            'val'        => '10',
            'name'       => '第二诊疗意见',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        "type11" => [
            'val'        => '11',
            'name'       => '重症转诊',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderOverseas',
            'payTable'   => 'app\modules\patient\models\PayOverseas',
            'page'       =>  '0',
            'list'       => '110',
        ],
        'type12' => [
            'val'        => '12',
            'name'       => 'vip服务',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderAccompany',
            'payTable'   => 'app\modules\patient\models\PayAccompany',
            'page'       =>  '1',
            'list'       => '112',
        ],
        'type13' => [
            'val'        => '13',
            'name'       => '慈善公益',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCommonweal',
            'payTable'   => 'app\modules\patient\models\PayCommonweal',
            'page'       =>  '0',
            'list'       => '113',
        ],
        'type14' => [
            'val'        => '14',
            'name'       => 'vip服务',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderAccompany',
            'payTable'   => 'app\modules\patient\models\PayAccompany',
            'page'       =>  '1',
            'list'       => '112',
        ],
        'type15' => [
            'val'        => '15',
            'name'       => '商品购买',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderAccompany',
            'payTable'   => 'app\modules\patient\models\PayAccompany',
            'page'       =>  '0',
            'list'       => '112',
        ],
    	'type16' => [
    		'val'        => '16',
    		'name'       => '集团客户',
    		'alias'      => '',
    		'orderTable' => 'app\modules\patient\models\OrderGroupCustomer',
    		'payTable'   => 'app\modules\patient\models\PayGroupCustomer',
    		'page'       =>  '0',
    		'list'       => '116',
    	],
        'type17' => [
            'val'        => '17',
            'name'       => '解读报告',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderGroupCustomer',
            'payTable'   => 'app\modules\patient\models\PayGroupCustomer',
            'page'       =>  '0',
            'list'       => '117',
        ],
        'type18' => [
            'val'        => '18',
            'name'       => '解读报告',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderGroupCustomer',
            'payTable'   => 'app\modules\patient\models\PayGroupCustomer',
            'page'       =>  '0',
            'list'       => '117',
        ],
        //会员卡服务
        'type58' => [
            'val'        => '58',
            'name'       => '预约挂号-其他地区',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type59' => [
            'val'        => '59',
            'name'       => '预约挂号-北京',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type60' => [
            'val'        => '60',
            'name'       => '体检',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type61' => [
            'val'        => '61',
            'name'       => '报告解读',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type62' => [
            'val'        => '62',
            'name'       => '健康档案管理',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type63' => [
            'val'        => '63',
            'name'       => '线上分诊',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type64' => [
            'val'        => '64',
            'name'       => '陪诊',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type65' => [
            'val'        => '65',
            'name'       => '带诊',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type66' => [
            'val'        => '66',
            'name'       => '医疗保险',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type67' => [
            'val'        => '67',
            'name'       => '第二诊疗意见',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
        'type68' => [
            'val'        => '68',
            'name'       => '海外预约转诊',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
    	'type69' => [
    		'val'        => '69',
    		'name'       => '在线咨询',
    		'alias'      => '',
    		'orderTable' => 'app\modules\patient\models\OrderCardService',
    		'payTable'   => 'app\modules\patient\models\PayCardService',
    		'page'       =>  '0',
    		'list'       => '158',
    	],
        'type70' => [
            'val'        => '70',
            'name'       => '国内高端体检',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderCardService',
            'payTable'   => 'app\modules\patient\models\PayCardService',
            'page'       =>  '0',
            'list'       => '158',
        ],
    	'type19' => [
    		'val'        => '19',
    		'name'       => '在线问诊',
    		'alias'      => '',
    		'orderTable' => 'app\modules\patient\models\OrderInquiryOnline',
    		'payTable'   => 'app\modules\patient\models\PayInquiryOnline',
    		'page'       =>  '0',
    		'list'       => '119',
    	],
        'type20' => [
            'val'        => '20',
            'name'       => 'vip就医',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderAccompany',
            'payTable'   => 'app\modules\patient\models\PayAccompany',
            'page'       =>  '0',
            'list'       => '120',
        ],
        'type21' => [
            'val'        => '21',
            'name'       => '热卖商品',
            'alias'      => '',
            'orderTable' => 'app\modules\patient\models\OrderAccompany',
            'payTable'   => 'app\modules\patient\models\PayAccompany',
            'page'       =>  '0',
            'list'       => '121',
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
        "position1"  => ['val' => '1', 'name' => '科室主任','price'=>'0'],
        "position2"  => ['val' => '2', 'name' => '科室副主任','price'=>'0'],
        "position3"  => ['val' => '3', 'name' => '住院医师','price'=>'0'],
        "position4"  => ['val' => '4', 'name' => '二级教授','price'=>'0'],
        "position5"  => ['val' => '5', 'name' => '副教授','price'=>'0'],
        "position6"  => ['val' => '6', 'name' => '副主任医师','price'=>'50'],
        "position7"  => ['val' => '7', 'name' => '教授','price'=>'0'],
        "position8"  => ['val' => '8', 'name' => '神经外科主任医师','price'=>'0'],
        "position9"  => ['val' => '9', 'name' => '医学博士','price'=>'0'],
        "position10" => ['val' => '10', 'name' => '主任医师','price'=>'80'],
        "position11" => ['val' => '11', 'name' => '专科主任','price'=>'0'],
        'position12' => ['val' => '12', 'name' => '主治医师','price'=>'25'],
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
        'no'  => ['val' => 2, 'name' => '失效'],
    ];

    /*
     * vipType Desc
     */
    static $payVipTypeDesc = [
        "s0" => "零",
        "s1" => "一",
        "s2" => "二",
        "s3" => "三",
        "s4" => "四",
    ];

    static  $coupon_use_rule = [
        '1'=>['25','10000'],
        '2'=>['50','10000'],
        '3'=>['80','10000'],
    ];

    /*
     * 优惠卷信息
     */
    static $orderCoupon = [
        'c1'=>['coupon_type'=>1,'coupon_name'=>'问诊券','order_money'=>'10.00','order_type'=>'19','order_desc'=>'限在线问诊使用','use_rule'=>'1','rule_desc'=>'满25元可用','expiry_time'=>30*3600],
    	'c2'=>['coupon_type'=>2,'coupon_name'=>'问诊券','order_money'=>'20.00','order_type'=>'19','order_desc'=>'限在线问诊使用','use_rule'=>'2','rule_desc'=>'满50元可用','expiry_time'=>30*3600],
    	'c3'=>['coupon_type'=>3,'coupon_name'=>'问诊券','order_money'=>'30.00','order_type'=>'19','order_desc'=>'限在线问诊使用','use_rule'=>'3','rule_desc'=>'满80元可用','expiry_time'=>30*3600],
    ];
    
    /*
     *  新用户注册优惠卷
     */
    static $registerCoupon = [
    	'c1'=>['coupon_type'=>1,'coupon_name'=>'问诊券','order_money'=>'10.00','order_type'=>'19','order_desc'=>'限在线问诊使用','use_rule'=>'1','rule_desc'=>'满25元可用','expiry_time'=>30*3600],
    	'c2'=>['coupon_type'=>2,'coupon_name'=>'问诊券','order_money'=>'20.00','order_type'=>'19','order_desc'=>'限在线问诊使用','use_rule'=>'2','rule_desc'=>'满50元可用','expiry_time'=>30*3600],
    	'c3'=>['coupon_type'=>3,'coupon_name'=>'问诊券','order_money'=>'30.00','order_type'=>'19','order_desc'=>'限在线问诊使用','use_rule'=>'3','rule_desc'=>'满80元可用','expiry_time'=>30*3600],
    ];


    static $goodsType  = [
        "1" => [
            "type"=>"15",
            "class"=>"orderaccompanydetail/create",
        ],
        "2" => [
            "type"=>"21",
            "class"=>"orderaccompanydetail/create",
        ],
    ];
     //快递信息
     static $express = [
        ['id'=>'0','express_company'=>'顺丰','express_com'=>'shunfeng'],
        ['id'=>'1','express_company'=>'申通','express_com'=>'shentong'],
        ['id'=>'2','express_company'=>'圆通快递','express_com'=>'yuantong'],
        ['id'=>'3','express_company'=>'中通快递','express_com'=>'zhongtong'],
        ['id'=>'4','express_company'=>'韵达快递','express_com'=>'yunda'],
        ['id'=>'5','express_company'=>'ems快递','express_com'=>'ems'],
        ['id'=>'6','express_company'=>'汇通快运','express_com'=>'huitongkuaidi'],
        ['id'=>'7','express_company'=>'全峰快递','express_com'=>'quanfengkuaidi'],
        ['id'=>'8','express_company'=>'宅急送','express_com'=>'zhaijisong'],
    ];
    /**
     * 获取订单状态配置
     * @param $orderVersion
     */
    public static function getOrderPriceItem($orderVersion = 1)
    {
        $class = 'app\common\application\orderversion\OrderStatus_' . $orderVersion;
        return $class::$priceItemInfo;
    }

    /**
     * 获取订单状态配置
     * @param $orderVersion
     */
    public static function getOrderStatus($orderVersion = 1)
    {
        $class = 'app\common\application\orderversion\OrderStatus_' . $orderVersion;
        return $class::$orderStatus;
    }

    /**
     * 获取订单查询条件配置
     * @param int $orderVersion
     * @return mixed
     */
    public static function getOrderStatusIng($orderVersion = 1)
    {
        $class = 'app\common\application\orderversion\OrderStatus_' . $orderVersion;
        return $class::$orderStatusIng;
    }

    /**
     * 获取订单支付流程配置
     * @param int $orderVersion
     * @return mixed
     */
    public static function getOrderProcess($orderVersion = 1)
    {
        $class = 'app\common\application\orderversion\OrderStatus_' . $orderVersion;
        return $class::$process;
    }

    /**
     * 获取支付描述
     * @param $template
     * @param $orderTypeDesc
     * @param $vipType
     * @param $sectionName
     * @return mixed
     */
    public static function getPayDesc($template, $orderTypeDesc, $vipType, $sectionName)
    {
        $vipDesc = isset(static::$priceInfo['ordertype12']["type" . $vipType]) ? static::$priceInfo['ordertype12']["type" . $vipType]['name'] : "";
        $template = str_replace("vip_type", $vipDesc, $template);
        $template = str_replace("order_type_desc", $orderTypeDesc, $template);
        return str_replace("section_name", $sectionName, $template);
    }

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