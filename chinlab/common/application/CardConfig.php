<?php

namespace app\common\application;
/**
 * 卡片配置文件
 * User: user
 * Date: 2017/2/24
 * Time: 17:54
 */

class CardConfig
{

    //是否默认
    //默认
    const IS_DEFAULT = 1;
    //其他
    const NO_DEFAULT = 0;

    //卡项目是否生效
    //生效
    const SERVICE_PROCESS_STATUS_ACTIVE = 1;
    //时效
    const SERVICE_PROCESS_STATUS_NO_ACTIVE = 2;

    //商品是否上架
    //不上架
    const ONSALT_NO = 0;
    //上架
    const ONSALT_YES = 1;

    //卡激活状态
    //未激活
    const SECRET_NO_ACTIVE = 0;
    //已激活
    const SECRET_YES_ACTIVE = 1;

    //卡激活方式
    //为激活时状态
    const SECRET_ACTIVE_DEFAULT = 0;
    //手机激活
    const SECRET_ACTIVE_PHONE = 1;
    //卡密激活
    const SECRET_ACTIVE_SECRET = 2;

    //激活卡用户类型
    //普通用户
    const ACTIVE_USER_NORMAL = 0;
    //后台用户
    const ACTIVE_USER_BACKEND = 1;

    //使用卡用户分类
    //自己使用
    const ACTIVE_APPLY_OWN = 0;
    //给他人使用
    const ACTIVE_APPLY_OTHER = 1;

    //受益人增加类型
    //app添加
    const PERSON_TYPE_APP = 1;
    //后台添加
    const PERSON_TYPE_BACKEND = 2;

    //服务添加类型
    //app添加
    const PERSON_ADD_TYPE_APP = 1;
    //后台添加
    const PERSON_ADD_TYPE_BACKEND = 2;

    //服务归类
    const SERVICE_TYPE_ONE = 1; //不指定医院  指定科室
    const SERVICE_TYPE_TWO = 2; //指定医院科室、不指定专家
    const SERVICE_TYPE_THREE = 3; //指定专家，注：医院非必填
    const SERVICE_TYPE_FOUR = 4; //健康体检
    const SERVICE_TYPE_FIVE = 5; //医疗保险
    const SERVICE_TYPE_SIX = 6; //电话服务
    const SERVICE_TYPE_SEVEN = 7; //健康档案类
    const SERVICE_TYPE_EIGHT = 8; //解读报告
    const SERVICE_TYPE_NINE = 9; //健康档案管理
    //后台归类
    const SERVICE_TYPE_ONE_BACK = 1; //预约挂号
    const SERVICE_TYPE_TWO_BACK = 2; //体检类
    const SERVICE_TYPE_THREE_BACK = 3;//保险类
    const SERVICE_TYPE_FOUR_BACK = 4;//电话服务
    const SERVICE_TYPE_FIVE_BACK = 5;//健康档案类
    const SERVICE_TYPE_SIX_BACK = 6;//线上分诊
    const SERVICE_TYPE_SEVEN_BACK = 7;//在线咨询
    const SERVICE_TYPE_EIGHT_BACK = 8;//陪诊
    const SERVICE_TYPE_NINE_BACK = 9;//代诊
    const SERVICE_TYPE_TEN_BACK = 10;//第二诊疗意见
    const SERVICE_TYPE_ELEVEN_BACK = 11;//海外预约转诊
    const SERVICE_TYPE_TWELVE_BACK = 12;//报告解读
    //服务类额外添加字段公共项
    static $serviceExtra = [
        //挂号
        self::SERVICE_TYPE_ONE => [
            //用户提交的信息
            "hospital_id" => ["field" => "hospital_id", "name" => "医院ID"],
            "hospital_name" => ["field" => "hospital_name", "name" => "医院名称"],
            "hospital_section_id" => ["field" => "hospital_section_id", "name" => "科室ID"],
            "section_name" => ["field" => "section_name", "name" => "科室名称"],
            "doctor_name" => ["field" => "doctor_name", "name" => "医生姓名"],
            "disease_name" => ["field" => "disease_name", "name" => "疾病名称"],
            "disease_des" => ["field" => "disease_des", "name" => "疾病描述"],
            "order_file" => ["field" => "order_file", "name" => "病情图片"],
            "order_date" => ["field" => "order_date", "name" => "期望就诊时间"],
            "user_name" => ["field" => "user_name", "name" => "医保卡号"],
            "user_card_no" => ["field" => "user_card_no", "name" => "用户身份证号"],
            "user_phone" => ["field" => "user_phone", "name" => "医保卡号"],
            "medical_insurance_number" => ["field" => "medical_insurance_number", "name" => "医保卡号"],
            "doctor_level_id" => ["field" => "doctor_level_id", "name" => "医生等级"],
            "doctor_level_desc" => ["field" => "doctor_level_desc", "name" => "医生等级描述"],
            "order_city_name" => ["field" => "order_city_name", "name" => "地区名称"],
            //服务人员最终确认的信息
            "current_hospital_id" => ["field" => "current_hospital_id", "name" => "医院ID"],
            "current_hospital_name" => ["field" => "current_hospital_name", "name" => "医院名称"],
            "current_hospital_section_id" => ["field" => "current_hospital_section_id", "name" => "科室ID"],
            "current_section_name" => ["field" => "current_section_name", "name" => "科室名称"],
            "current_doctor_name" => ["field" => "current_doctor_name", "name" => "医生姓名"],
            "current_order_date" => ["field" => "current_order_date", "name" => "就诊时间"],
            "current_order_fee" => ["field" => "current_order_fee", "name" => "挂号费用"],
            "current_order_fee_type" => ["field" => "current_order_fee_type", "name" => "支付方式"],
            "current_order_area" => ["field" => "current_order_area", "name" => "取号地点"],
            "current_outpatient_type" => ["field" => "current_outpatient_type", "name" => "门诊类型"],
            "current_order_design" => ["field" => "current_order_design", "name" => "预约备注"],
        ],
        self::SERVICE_TYPE_TWO => [
            //用户提交的信息
            "hospital_id" => ["field" => "hospital_id", "name" => "医院ID"],
            "hospital_name" => ["field" => "hospital_name", "name" => "医院名称"],
            "hospital_section_id" => ["field" => "hospital_section_id", "name" => "科室ID"],
            "section_name" => ["field" => "section_name", "name" => "科室名称"],
            "doctor_name" => ["field" => "doctor_name", "name" => "医生姓名"],
            "disease_name" => ["field" => "disease_name", "name" => "疾病名称"],
            "disease_des" => ["field" => "disease_des", "name" => "疾病描述"],
            "order_file" => ["field" => "order_file", "name" => "病情图片"],
            "order_date" => ["field" => "order_date", "name" => "期望就诊时间"],
            //服务人员最终确认的信息
            "current_hospital_id" => ["field" => "current_hospital_id", "name" => "医院ID"],
            "current_hospital_name" => ["field" => "current_hospital_name", "name" => "医院名称"],
            "current_hospital_section_id" => ["field" => "current_hospital_section_id", "name" => "科室ID"],
            "current_section_name" => ["field" => "current_section_name", "name" => "科室名称"],
            "current_doctor_name" => ["field" => "current_doctor_name", "name" => "医生姓名"],
            "current_order_date" => ["field" => "current_order_date", "name" => "就诊时间"],
            "current_order_fee" => ["field" => "current_order_fee", "name" => "挂号费用"],
            "current_order_fee_type" => ["field" => "current_order_fee_type", "name" => "支付方式"],
            "current_order_area" => ["field" => "current_order_area", "name" => "取号地点"],
            "current_outpatient_type" => ["field" => "current_outpatient_type", "name" => "门诊类型"],
            "current_order_design" => ["field" => "current_order_design", "name" => "预约备注"],
        ],
        self::SERVICE_TYPE_THREE => [
            //用户提交的信息
            "hospital_id" => ["field" => "hospital_id", "name" => "医院ID"],
            "hospital_name" => ["field" => "hospital_name", "name" => "医院名称"],
            "hospital_section_id" => ["field" => "hospital_section_id", "name" => "科室ID"],
            "section_name" => ["field" => "section_name", "name" => "科室名称"],
            "doctor_name" => ["field" => "doctor_name", "name" => "医生姓名"],
            "disease_name" => ["field" => "disease_name", "name" => "疾病名称"],
            "disease_des" => ["field" => "disease_des", "name" => "疾病描述"],
            "order_file" => ["field" => "order_file", "name" => "病情图片"],
            "order_date" => ["field" => "order_date", "name" => "期望就诊时间"],
            //服务人员最终确认的信息
            "current_hospital_id" => ["field" => "current_hospital_id", "name" => "医院ID"],
            "current_hospital_name" => ["field" => "current_hospital_name", "name" => "医院名称"],
            "current_hospital_section_id" => ["field" => "current_hospital_section_id", "name" => "科室ID"],
            "current_section_name" => ["field" => "current_section_name", "name" => "科室名称"],
            "current_doctor_name" => ["field" => "current_doctor_name", "name" => "医生姓名"],
            "current_order_date" => ["field" => "current_order_date", "name" => "就诊时间"],
            "current_order_fee" => ["field" => "current_order_fee", "name" => "挂号费用"],
            "current_order_fee_type" => ["field" => "current_order_fee_type", "name" => "支付方式"],
            "current_order_area" => ["field" => "current_order_area", "name" => "取号地点"],
            "current_outpatient_type" => ["field" => "current_outpatient_type", "name" => "门诊类型"],
            "current_order_design" => ["field" => "current_order_design", "name" => "预约备注"],
        ],
        //体检
        self::SERVICE_TYPE_FOUR => [
            "company" => ["field" => "company", "name" => "体检机构"],
            "user_email" => ["field" => "user_email", "name" => "体检人邮箱"],
            "select_item" => ["field" => "select_item", "name" => "选择的体检项目"],
            "order_date" => ["field" => "order_date", "name" => "期望就诊时间"],
            "order_city_name" => ["field" => "order_city_name", "name" => "地区名称"],
            "select_sex" => ["field" => "select_sex", "name" => "选择的体检项目性别"],
            "select_name" => ["field" => "select_name", "name" => "选择的体检项目名称"],
            "select_detail_url" => ["field" => "select_detail_url", "name" => "选择体检项目详情url"],
            "current_order_design" => ["field" => "current_order_design", "name" => "备注"],
        ],
        self::SERVICE_TYPE_FIVE => [
            "company" => ["field" => "company", "name" => "保险机构"],
            "user_email" => ["field" => "user_email", "name" => "受保人邮箱"],
            "current_order_design" => ["field" => "current_order_design", "name" => "备注"],
        ],
        self::SERVICE_TYPE_SIX => [

        ],
        self::SERVICE_TYPE_SEVEN => [

        ],
        self::SERVICE_TYPE_EIGHT => [
            "report_check_time" => ["field" => "report_check_time", "name" => "检查时间"],
            "check_organization" => ["field" => "check_organization", "name" => "检查机构"],
            "order_file" => ["field" => "order_file", "name" => "上传资料"],
            "user_email" => ["field" => "user_email", "name" => "受保人邮箱"],
            "retry_status" => ["field" => "retry_status", "name" => "是否需要重新上传"],
            "retry_info" => ["field" => "retry_info", "name" => "需要重新上传的图片"],
            "is_supply" => ["field" => "is_supply", "name" => "是否已补充报告"],
            "current_order_design" => ["field" => "current_order_design", "name" => "备注"],
        ],
        self::SERVICE_TYPE_NINE => [
            "hospital_name" => ["field" => "hospital_name", "name" => "就诊医院"],
            "report_check_time" => ["field" => "report_check_time", "name" => "检查时间"],
            "disease_des" => ["field" => "disease_des", "name" => "检查机构"],
            "retry_status" => ["field" => "retry_status", "name" => "是否需要重新上传"],
            "retry_info" => ["field" => "retry_info", "name" => "需要重新上传的图片"],
            "order_file" => ["field" => "order_file", "name" => "上传资料"],
            "is_supply" => ["field" => "is_supply", "name" => "是否已补充报告"],
            "user_email" => ["field" => "user_email", "name" => "受保人邮箱"],
            "current_order_design" => ["field" => "current_order_design", "name" => "备注"],
        ],
    ];
    //后台
    static $serviceBackExtra = [
        self::SERVICE_TYPE_ONE_BACK => [
            //用户提交的信息
            "hospital_id" => ["field" => "hospital_id", "name" => "医院ID"],
            "hospital_name" => ["field" => "hospital_name", "name" => "医院名称"],
            "hospital_section_id" => ["field" => "hospital_section_id", "name" => "科室ID"],
            "section_name" => ["field" => "section_name", "name" => "科室名称"],
            "doctor_name" => ["field" => "doctor_name", "name" => "医生姓名"],
            "disease_name" => ["field" => "disease_name", "name" => "疾病名称"],
            "disease_des" => ["field" => "disease_des", "name" => "疾病描述"],
            "order_file" => ["field" => "order_file", "name" => "病情图片"],
            "order_date" => ["field" => "order_date", "name" => "期望就诊时间"],
            "medical_insurance_number" => ["field" => "medical_insurance_number", "name" => "医保卡号"],
            "order_city" => ["field" => "order_city", "name" => "地区ID"],
            "order_city_name" => ["field" => "order_city_name", "name" => "地区名称"],
            //服务人员最终确认的信息
            "current_hospital_id" => ["field" => "current_hospital_id", "name" => "医院ID"],
            "current_hospital_name" => ["field" => "current_hospital_name", "name" => "医院名称"],
            "current_hospital_section_id" => ["field" => "current_hospital_section_id", "name" => "科室ID"],
            "current_section_name" => ["field" => "current_section_name", "name" => "科室名称"],
            "current_doctor_name" => ["field" => "current_doctor_name", "name" => "医生姓名"],
            "current_order_date" => ["field" => "current_order_date", "name" => "就诊时间"],
            "current_order_fee" => ["field" => "current_order_fee", "name" => "挂号费用"],
            "current_order_fee_type" => ["field" => "current_order_fee_type", "name" => "支付方式"],
            "current_order_area" => ["field" => "current_order_area", "name" => "取号地点"],
            "current_outpatient_type" => ["field" => "current_order_area", "name" => "门诊类型"],
            "current_order_design" => ["field" => "current_order_design", "name" => "预约备注"],
        ],
        self::SERVICE_TYPE_TWO_BACK => [
            "company" => ["field" => "company", "name" => "体检机构"],
            "user_email" => ["field" => "user_email", "name" => "体检人邮箱"],
            "select_item" => ["field" => "select_item", "name" => "选择的体检项目"],
            "select_sex" => ["field" => "select_sex", "name" => "选择的体检项目性别"],
            "select_name" => ["field" => "select_name", "name" => "选择的体检项目名称"],
            "order_date" => ["field" => "order_date", "name" => "期望就诊时间"],
            "select_detail_url" => ["field" => "select_detail_url", "name" => "选择体检项目详情url"],
            "current_order_design" => ["field" => "current_order_design", "name" => "预约备注"],
        ],
        self::SERVICE_TYPE_THREE_BACK => [
            "company" => ["field" => "company", "name" => "保险机构"],
            "user_email" => ["field" => "user_email", "name" => "受保人邮箱"],
            "current_order_design" => ["field" => "current_order_design", "name" => "预约备注"],
        ],
        self::SERVICE_TYPE_FOUR_BACK => [
            "current_order_design" => ["field" => "current_order_design", "name" => "备注"],
        ],
        self::SERVICE_TYPE_FIVE_BACK => [
        	"hospital_name" => ["field" => "hospital_name", "name" => "就诊医院"],
        	"report_check_time" => ["field" => "report_check_time", "name" => "就诊时间"],
        	"disease_des" => ["field" => "disease_des", "name" => "病情描述"],
        	"order_file" => ["field" => "order_file", "name" => "上传资料"],
        	"current_order_design" => ["field" => "current_order_design", "name" => "备注"],	 
        ],
    	self::SERVICE_TYPE_SIX_BACK => [
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
        ],
    	self::SERVICE_TYPE_SEVEN_BACK => [
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
    	],
    	self::SERVICE_TYPE_EIGHT_BACK => [
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
    	],
    	self::SERVICE_TYPE_NINE_BACK => [
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
    	],
    	self::SERVICE_TYPE_TEN_BACK => [
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
    	],
    	self::SERVICE_TYPE_ELEVEN_BACK => [
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
    	],
    	self::SERVICE_TYPE_TWELVE_BACK => [
    		"hospital_name" => ["field" => "hospital_name", "name" => "就诊医院"],
    		"report_check_time" => ["field" => "report_check_time", "name" => "检查时间"],
    		"disease_des" => ["field" => "disease_des", "name" => "检查机构"],
    		"order_file" => ["field" => "order_file", "name" => "上传资料"],
    		"user_email" => ["field" => "user_email", "name" => "受保人邮箱"],
    		"current_order_design" => ["field" => "current_order_design", "name" => "备注"],
    		"retry_status" => ["field" => "retry_status", "name" => "是否需要重新上传"],
    		"retry_info" => ["field" => "retry_info", "name" => "需要重新上传的图片"],
    		"is_supply" => ["field" => "is_supply", "name" => "是否已补充报告"],
    	],
    ];

    //服务类型定义
    //不指定医院  指定科室   t1-t3
    //指定医院科室、不指定专家  t4-t8
    //指定专家  t9
    //体检   t10-t11
    //保险   t12-t20
    //电话服务   t21-t26
    //健康档案管理   t27
    static $service = [
        "t1" => [
            'name' => '三甲医院检查',
            'val' => '1',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助安排三甲医院（不指定医院）特殊检查{count}次，含挂号费 '
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_ONE,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t2" => [
            'name' => '专家会诊',
            'index' => '1',
            'val' => '2',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助安排专家会诊号{count}次，含北京陪诊，含非特需挂号费 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_ONE,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t3" => [
            'name' => '三甲医院常规检查',
            'val' => '3',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助安排三甲医院（不指定医院）常规检查{count}次，含挂号费',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_ONE,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t4" => [
            'name' => '专家号不指定专家',
            'val' => '4',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助挂三甲医院专家号（北京排名前五医院科室、不指定专家）{count}次，含非特需挂号费 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_TWO,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t5" => [
            'name' => '专家号不指定专家',
            'index' => '1',
            'val' => '5',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助挂专家号（指定医院和科室，不点名专家）{count}次，含北京陪诊，含非特需挂号费 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_TWO,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t6" => [
            'name' => '专家号不指定专家',
            'index' => '1',
            'val' => '6',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助挂三甲医院专家号{count}次（北京排名前五医院科室、不指定专家），不含挂号费',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_TWO,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t7" => [
            'name' => '专家号不指定专家',
            'index' => '1',
            'val' => '7',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助挂专家号{count}次（指定医院和科室，不指定专家），不含挂号费  ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_TWO,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t8" => [
            'name' => '专家号不指定专家',
            'index' => '1',
            'val' => '8',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助挂专家号{count}次（指定医院和科室，不指定专家），含北京陪诊，不含挂号费',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_TWO,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t9" => [
            'name' => '专家号指定专家',
            'index' => '1',
            'val' => '9',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助安排主任医师级专家高端诊疗{count}次，含北京陪诊',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t10" => [
            'name' => '健康体检',
            'index' => '1',
            'val' => '10',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '健康体检{count}次（女性、男性任选）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FOUR,
            "back_type" => self::SERVICE_TYPE_TWO_BACK,
            "extra" => [],
        ],
        "t11" => [
            'name' => '健康体检',
            'index' => '1',
            'val' => '11',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '健康体检{count}次（女性、男性任选）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FOUR,
            "back_type" => self::SERVICE_TYPE_TWO_BACK,
            "extra" => [],
        ],
        "t99" => [
            'name' => '健康体检',
            'val' => '99',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '健康体检{count}次（女性、男性任选）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FOUR,
            "back_type" => self::SERVICE_TYPE_TWO_BACK,
            "extra" => [],
        ],
        "t12" => [
            'name' => '医疗保险',
            'is_show' => 0,
            'index' => '1',
            'val' => '12',
            //服务描述信息
            'desc' => [
                '大病保险{count}份（保额10万，42种疾病，45周岁以下赠送，50周岁补费600元，50周岁以上不适用） ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t13" => [
            'name' => '医疗保险',
            'is_show' => 0,
            'index' => '1',
            'val' => '13',
            //服务描述信息
            'desc' => [
                '人身意外保险{count}份（残故20万，意外医疗2万，100免赔，80%赔付）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t14" => [
            'name' => '医疗保险',
            'val' => '14',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                ' 人身意外保险{count}份（残故10万，意外医疗1万，100免赔，80%赔付）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t15" => [
            'name' => '大病保险',
            'val' => '15',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '大病保险{count}份（保额10万，42种疾病，45周岁以下赠送，50周岁补费600元，50周岁以上不适用）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t16" => [
            'name' => '大病保险',
            'is_show' => 0,
            'index' => '1',
            'val' => '16',
            //服务描述信息
            'desc' => [
                '大病保险{count}份（保额10万，42种疾病，35周岁以下赠送，40周岁补费100元，45周岁补费280元，50周岁补费600元，50周岁以上不适用）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t17" => [
            'name' => '大病保险',
            'val' => '17',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '赠送意外保险{count}份（残故5万，意外医疗0.5万，100免赔，80%赔付）两份 注：保险公司评估后，如不适用，将取消赠送。',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t18" => [
            'name' => '大病保险',
            'index' => '1',
            'val' => '18',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '赠送意外保险{count}份（残故2万，意外医疗0.2万，100免赔，80%赔付）注：保险公司评估后，如不适用，将取消赠送。 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t19" => [
            'name' => '大病保险',
            'val' => '19',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '大病保险{count}份（保额10万，42种疾病，50周岁以下赠送，50周岁以上个案定制）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t20" => [
            'name' => '意外保险',
            'val' => '20',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '赠送意外保险{count}份（残故1万，意外医疗0.1万，100免赔，80%赔付）注：保险公司评估后，如不适用，将取消赠送。',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t21" => [
            'name' => '大病保险',
            'val' => '17',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '大病保险{count}份（保额10万，42种疾病，40周岁以下赠送，45周岁补费280元，50周岁补费600元，50周岁以上不适用） ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t22" => [
            'name' => '大病保险',
            'val' => '22',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '大病保险{count}份（保额10万，42种疾病，35周岁以下赠送，40周岁补费100元，45周岁补费280元，50周岁补费600元，50周岁以上不适用）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FIVE,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t23" => [
            'name' => '咨询服务',
            'index' => '1',
            'val' => '23',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '私人医生电话服务{count}次，（含客服分诊，北京三甲医院医生电话回复）',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SIX,
            "back_type" => self::SERVICE_TYPE_FOUR_BACK,
            "extra" => [],
        ],
        "t24" => [
            'name' => '咨询服务',
            'index' => '1',
            'val' => '24',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '工作日9:00—21:00客服电话咨询、分诊，不限次 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SIX,
            "back_type" => self::SERVICE_TYPE_FOUR_BACK,
            "extra" => [],
        ],
        "t25" => [
            'name' => '咨询服务',
            'val' => '25',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '工作日9:00—21:00专属健康医疗私人客服线上服务，不限次 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SIX,
            "back_type" => self::SERVICE_TYPE_FOUR_BACK,
            "extra" => [],
        ],
        "t26" => [
            'name' => '咨询服务',
            'val' => '26',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '工作日9:00—21:00客服电话咨询、分诊，每月{count}次',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SIX,
            "back_type" => self::SERVICE_TYPE_FOUR_BACK,
            "extra" => [],
        ],
        "t27" => [
            'name' => '健康档案管理',
            'val' => '27',
            'index' => '1',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '工作日9:00—21:00健康档案管理及更新、诊后随访，不限次 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已提交', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已安排', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '已完成', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_FIVE_BACK,
            "extra" => [],
        ],
        "t58" => [
            'name' => '协助就医(预约挂号)',
            'val' => '58',
            'is_show' => 1,
            'index' => 1,
            //服务描述信息
            'desc' => [
                '协助就医(预约挂号)',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '预约中', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已完成', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_ONE,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t59" => [
            'name' => '协助就医(预约挂号)',
            'val' => '59',
            'is_show' => 0,
            //服务描述信息
            'desc' => [
                '协助就医(预约挂号)',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '医事服务费待支付', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '医事服务费已支付', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s3" => ['name' => '预约中', 'val' => 3, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s4" => ['name' => '已完成', 'val' => 4, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_ONE,
            "back_type" => self::SERVICE_TYPE_ONE_BACK,
            "extra" => [],
        ],
        "t60" => [
            'name' => '国内高端体检',
            'val' => '60',
            'index' => '3',
            'is_show' => 1,
            //服务描述信息
            'desc' => [
                '国内高端体检 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '预约中', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已完成', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FOUR,
            "back_type" => self::SERVICE_TYPE_TWO_BACK,
            "extra" => [],
        ],
        "t61" => [
            'name' => '解读体检报告',
            'val' => '61',
            'is_show' => 1,
            'index' => '4',
            //服务描述信息
            'desc' => [
                '解读体检报告',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_EIGHT,
            "back_type" => self::SERVICE_TYPE_TWELVE_BACK,
            "extra" => [],
        ],
        "t62" => [
            'name' => '健康档案管理',
            'val' => '62',
            'is_show' => 1,
            'index' => 2,
            //服务描述信息
            'desc' => [
                '健康档案管理 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_NINE,
            "back_type" => self::SERVICE_TYPE_FIVE_BACK,
            "extra" => [],
        ],
        "t63" => [
            'name' => '线上分诊',
            'val' => '63',
            'is_show' => 1,
            'index' => '6',
            //服务描述信息
            'desc' => [
                '线上分诊 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_SIX_BACK,
            "extra" => [],
        ],
        "t64" => [
            'name' => '陪诊服务',
            'val' => '64',
            'index' => '7',
            'is_show' => 1,
            //服务描述信息
            'desc' => [
                '陪诊服务 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_EIGHT_BACK,
            "extra" => [],
        ],
        "t65" => [
            'name' => '代诊服务',
            'val' => '65',
            'index' => '8',
            'is_show' => 1,
            //服务描述信息
            'desc' => [
                '代诊服务 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_NINE_BACK,
            "extra" => [],
        ],
        "t66" => [
            'name' => '医疗保险',
            'val' => '66',
            'is_show' => 1,
            'index' => '9',
            //服务描述信息
            'desc' => [
                '医疗保险',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_THREE_BACK,
            "extra" => [],
        ],
        "t67" => [
            'name' => '海外权威专家第二诊疗意见',
            'val' => '67',
            'is_show' => 1,
            'index' => '10',
            //服务描述信息
            'desc' => [
                '海外权威专家第二诊疗意见 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_TEN_BACK,
            "extra" => [],
        ],
        "t68" => [
            'name' => '海外权威医院预约转诊',
            'val' => '68',
            'is_show' => 1,
            'index' => '11',
            //服务描述信息
            'desc' => [
                '海外权威医院预约转诊 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_ELEVEN_BACK,
            "extra" => [],
        ],
        "t69" => [
            'name' => '在线咨询',
            'val' => '69',
            'is_show' => 1,
            'index' => 5,
            //服务描述信息
            'desc' => [
                '在线咨询 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '已完成', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_SEVEN,
            "back_type" => self::SERVICE_TYPE_SEVEN_BACK,
            "extra" => [],
        ],
        "t70" => [
            'name' => '国内高端体检',
            'val' => '70',
            'index' => '3',
            'is_show' => 1,
            //服务描述信息
            'desc' => [
                '国内高端体检 ',
            ],
            //服务进行状态描述
            "status" => [
                "s1" => ['name' => '预约中', 'val' => 1, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s2" => ['name' => '已完成', 'val' => 2, 'process_status' => self::SERVICE_PROCESS_STATUS_ACTIVE],
                "s99" => ['name' => '已取消', 'val' => 99, 'process_status' => self::SERVICE_PROCESS_STATUS_NO_ACTIVE],
            ],
            "type" => self::SERVICE_TYPE_FOUR,
            "back_type" => self::SERVICE_TYPE_TWO_BACK,
            "extra" => [],
        ],
    ];

    /**
     * 体检项目新
     */
    static $testNewInfo = [
        "s1" => [
            "name" => "价值6000元高端男性体检",
            "val" => "1",
            "sex" => "1",
            "desc" => "价值6000元高端男性体检",
        ],
        "s2" => [
            "name" => "价值6000元高端女性体检",
            "val" => "2",
            "sex" => "2",
            "desc" => "价值6000元高端女性体检",
        ],
    ];

    /**
     * 体检选项
     * @var array
     */
    static $testInfo = [
        "s1" => [
            "name" => "体检项目一（男）",
            "val" => "1",
            "sex" => "1",
            "desc" => "高清彩色多普勒B超",
        ],
        "s2" => [
            "name" => "体检项目二（男）",
            "val" => "2",
            "sex" => "1",
            "desc" => "肿瘤标志检测",
        ],
        "s3" => [
            "name" => "体检项目一（女）",
            "val" => "3",
            "sex" => "2",
            "desc" => "妇科（已婚项目）",
        ],
        "s4" => [
            "name" => "体检项目二（女）",
            "val" => "4",
            "sex" => "2",
            "desc" => "肿瘤标志检测",
        ],
        "s5" => [
            "name" => "体检项目一（男）",
            "val" => "5",
            "sex" => "1",
            "desc" => "高清彩色多普勒B超",
        ],
        "s6" => [
            "name" => "体检项目二（男）",
            "val" => "6",
            "sex" => "1",

            "desc" => "肿瘤标志检测",
        ],
        "s7" => [
            "name" => "体检项目一（女）",
            "val" => "7",
            "sex" => "2",
            "desc" => "妇科（已婚项目）",
        ],
        "s8" => [
            "name" => "体检项目二（女）",
            "val" => "8",
            "sex" => "2",
            "desc" => "肿瘤标志检测",
        ],
        "s9" => [
            "name" => "体检项目一（男）",
            "val" => "9",
            "sex" => "1",
            "desc" => "高清彩色多普勒B超",
        ],
        "s10" => [
            "name" => "体检项目二（男）",
            "val" => "10",
            "sex" => "1",
            "desc" => "肿瘤标志检测",
        ],
        "s11" => [
            "name" => "体检项目一（女）",
            "val" => "11",
            "sex" => "2",
            "desc" => "妇科（已婚项目）",
        ],
        "s12" => [
            "name" => "体检项目二（女）",
            "val" => "12",
            "sex" => "2",
            "desc" => "肿瘤标志检测",
        ],
        "s13" => [
            "name" => "体检项目一（男）",
            "val" => "13",
            "sex" => "1",
            "desc" => "高清彩色多普勒B超",
        ],
        "s14" => [
            "name" => "体检项目二（男）",
            "val" => "14",
            "sex" => "1",
            "desc" => "肿瘤标志检测",
        ],
        "s15" => [
            "name" => "体检项目一（女）",
            "val" => "15",
            "sex" => "2",
            "desc" => "妇科（已婚项目）",
        ],
        "s16" => [
            "name" => "体检项目二（女）",
            "val" => "16",
            "sex" => "2",
            "desc" => "肿瘤标志检测",
        ],
        "s17" => [
            "name" => "体检项目一（男）",
            "val" => "17",
            "sex" => "1",
            "desc" => "高清彩色多普勒B超",
        ],
        "s18" => [
            "name" => "体检项目二（男）",
            "val" => "18",
            "sex" => "1",
            "desc" => "肿瘤标志检测",
        ],
        "s19" => [
            "name" => "体检项目一（女）",
            "val" => "19",
            "sex" => "2",
            "desc" => "妇科（已婚项目）",
        ],
        "s20" => [
            "name" => "体检项目二（女）",
            "val" => "20",
            "sex" => "2",
            "desc" => "肿瘤标志检测",
        ],
    ];

    //商品类型分类
    static $cardInfoStatus = [
        "t1" => [
            'name' => '健康卡',
            'val' => 1,
        ],
        "t2" => [
            'name' => '保健药品',
            'val' => 2,
        ],
    ];

    //体检地区
    static $testArea = [
        [
            "name" => "慈铭",
            "area_info" => ["北京市", "上海市", "广州市", "深圳市", "东莞市", "武汉市", "南京市", "成都市", "天津市", "大连市", "青岛市", "山东省", "金华市", "临沂市", "长春市", "杭州市", "宁波市"],
        ],
        [
            "name" => "美年大健康",
            "area_info" => ["重庆市", "杭州市", "临海市", "宁波市", "义乌市", "楚雄市", "昆明市", "哈密市", "库尔勒市", "乌鲁木齐市", "伊犁市",
                "天津市", "成都市", "绵阳市", "上海市", "西安市", "晋城市", "吕梁市", "太原市", "运城市", "东营市", "济南市", "临沂市",
                "青岛市", "日照市", "泰安市", "潍坊市", "烟台市", "鄂尔多斯市", "呼和浩特市", "大连市", "锦州市", "沈阳市", "辽阳市", "南昌市",
                "常熟市", "昆山市", "南京市", "苏州市", "无锡市", "南通市", "泰州市", "长春市", "吉林市", "松原市", "延吉市", "长沙市", "大冶市", "武汉市", "襄阳市", "哈尔滨市", "安阳市", "济源市", "焦作市", "洛阳市", "南阳市", "濮阳市", "新乡市", "许昌市", "郑州市", "周口市", "信阳市", "石家庄市", "唐山市", "海口市", "贵阳市", "南宁市", "桂林市", "东莞市", "广州市", "深圳市", "兰州市", "厦门市", "北京市", "合肥市",
            ],
        ],
    ];

    static $registerArea = [
        ["name" => "北京", "parent_id" => "110100"],
        ["name" => "上海", "parent_id" => "110100"],
        ["name" => "广州", "parent_id" => "440100"],
        ["name" => "其他", "parent_id" => "888888"],
    ];

    static $doctorLevelMoney = [
        ["doctor_level_id" => "1", "doctor_level_desc" => "特需门诊", "money" => "300"],
        ["doctor_level_id" => "2", "doctor_level_desc" => "主任医师", "money" => "100"],
        ["doctor_level_id" => "3", "doctor_level_desc" => "副主任医师", "money" => "60"],
        ["doctor_level_id" => "4", "doctor_level_desc" => "主治医师", "money" => "50"],
    ];

    //后台集团服务分类
    static $serveType = [
        self::SERVICE_TYPE_ONE_BACK => ['val' => self::SERVICE_TYPE_ONE_BACK, 'name' => '预约挂号'],
        //self::SERVICE_TYPE_TWO_BACK   => ['val'=>self::SERVICE_TYPE_TWO_BACK,'name'=>'体检类'],
        //self::SERVICE_TYPE_THREE_BACK => ['val'=>self::SERVICE_TYPE_THREE_BACK,'name'=>'保险类'],
        //self::SERVICE_TYPE_FOUR_BACK  => ['val'=>self::SERVICE_TYPE_FOUR_BACK,'name'=>'电话服务'],
        //self::SERVICE_TYPE_FIVE_BACK  => ['val'=>self::SERVICE_TYPE_FIVE_BACK,'name'=>'健康档案类'],
    ];

    public static function getDoctorMoney($doctorLevelId) {
        $money = 0;
        foreach(self::$doctorLevelMoney as $k => $v) {
            if ($doctorLevelId == $v['doctor_level_id']) {
                $money = $v['money'];
                break;
            }
        }
        return $money;
    }

    static  $goodsTag = [
        's1'=>'热卖',
        's2'=>'特卖',
        's3'=>'推荐',
        's4'=>'新品上线',
    ];

}