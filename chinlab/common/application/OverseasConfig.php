<?php
namespace app\common\application;
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/6/7
 * Time: 15:04
 */
class OverseasConfig
{

    static $bigCategory = [
        [
            "val"      => "1",
            "desc"     => "海外转诊",
            "img_url"  => "http://files.huobanys.com/group2/M00/00/00/Chlnz1lDegaAIH14AABtD_-1OMw898.png",
            "category" => [
                ["name" => "肿瘤", "val" => "1"],
                ["name" => "血液疾病", "val" => "2"],
                ["name" => "心血管疾病", "val" => "3"],
                ["name" => "儿童重大疾病", "val" => "4"],
                ["name" => "肾脏疾病", "val" => "5"],
                ["name" => "运动康复", "val" => "7"],
                ["name" => "神经科疾病", "val" => "6"],
            ],
            //是否前端显示
            "is_list" => "1",
            //标题在上
            "show_type" => "0",
        ],
        [
            "val"     => "2",
            "desc"    => "专科治疗",
            "img_url" => "http://files.huobanys.com/group2/M00/00/00/Chlnz1lDegaAAw81AABNzIc2VpA969.png",
            "category" => [],
            "is_list" => "1",
            //图片在上
            "show_type" => "1",
        ],
        [
            "val"     => "3",
            "desc"    => "海外体检",
            "img_url" => "http://files.huobanys.com/group2/M00/00/00/Chlnz1lDegaABtpFAABFjVHHyTk985.png",
            "category" => [],
            "is_list" => "1",
            //图片在上
            "show_type" => "0",
        ],
        [
            "val"     => "4",
            "desc"    => "医疗美容",
            "img_url" => "http://files.huobanys.com/group2/M00/00/00/Chlnz1lDegaAU5W_AABfPbpVR6Y375.png",
            "category" => [],
            "is_list" => "1",
            //图片在上
            "show_type" => "1",
        ],
        [
            "val"     => "5",
            "desc"    => "健康抗衰",
            "img_url" => "http://files.huobanys.com/group2/M00/00/00/Chlnz1lDegaAcXq4AABN0RwQK-Q607.png",
            "category" => [],
            "is_list" => "1",
            //图片在上
            "show_type" => "1",
        ],
        [
            "val"     => "6",
            "desc"    => "生育辅助",
            "img_url" => "http://files.huobanys.com/group2/M00/00/00/Chlnz1lDegaALXhmAABkddaNKb0635.png",
            "category" => [],
            "is_list" => "1",
            //图片在上
            "show_type" => "1",
        ],
        [
            "val"     => "7",
            "desc"    => "推荐项目 ",
            "img_url" => "http://files.huobanys.com/group1/M00/00/39/ChvHbFk3p0WAfgCWAAA20xG4j9o645.jpg",
            "category" => [],
            "is_list" => "0",
            //图片在上
            "show_type" => "1",
        ],
    ];

    static $goodness = [
        [
            'title' => '顶级医疗资源',
            'desc' => '整合全球顶级医疗资源，为客户提供国际最高水准的诊疗服务',
        ],
        [
            'title' => '就医绿色通道',
            'desc' => '国内三甲医院快速预约的VIP服务，以及国外顶尖医疗机构快速预约通道',
        ],
        [
            'title' => '专业医疗团队',
            'desc' => '根据个人情况，预约最权威的科室及专家团队，多学科会诊后选择最适合的方案进行治疗',
        ],
        [
            'title' => '贴心服务优势',
            'desc' => '全程提供专业的医疗咨询，协助办理全部手续，并配有专业的海外医疗陪同人员',
        ],
        [
            'title' => '亲民服务价格',
            'desc' => '有利的价格优势：国内性价比最高的海外就医服务平台，配有最专业的海外就医服务团队',
        ],
        [
            'title' => '健康档案管理',
            'desc' => '与国内三甲医院合作，承接患者归国后的后续治疗和健康管理，保障患者治疗的连续性',
        ],
    ];
}
