<?php
namespace app\common\application\orderversion;
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/4/6
 * Time: 10:49
 */
class OrderStatus_1 {

    /**
     * 订单查询条件
     */
    static $orderStatusIng = [
        //手术预约
        "t1" => [
            't0' => "",
            //已完成
            't60' => 'order_state = 4',
            //进行中
            't70' => 'order_state < 4',
            //已取消
            't80' => 'order_state = 99',
        ],
        //预约诊疗
        "t9" => [
            't0' => "",
            //待支付
            't60' => 'order_state = 6',
            //进行中
            't70' => 'order_state < 6',
            //已取消
            't80' => 'order_state = 99',
        ],
        //公益
        "t13" => [
            't0' => "",
            //待支付
            't60' => 'order_state = 2',
            //进行中
            't70' => 'order_state < 2',
            //已取消
            't80' => 'order_state = 99',
        ],
        //海外医疗
        "t10" => [
            't0' => "",
            //待支付
            't60' => 'order_state = 4',
            //进行中
            't70' => 'order_state < 4',
            //已取消
            't80' => 'order_state = 99',
        ],
        //vip陪诊
        "t12" => [
            't0' => "order_type != 15",
            //待支付
            't60' => 'order_type != 15 and order_state = 6',
            //进行中
            't70' => 'order_type != 15 and order_state < 6',
            //已取消
            't80' => 'order_type != 15 and order_state = 99',
        ],
        //健康卡购买
        "t15" => [
            't0' => "order_type = 15",
            //待支付
            't60' => 'order_type = 15 and order_state = 2',
            //进行中
            't70' => 'order_type = 15 and order_state < 2',
            //已取消
            't80' => 'order_type = 15 and order_state = 99',
        ],
        //集团用户
        "t16" => [
            't0' => "order_type = 16",
            //待支付
            't60' => 'order_type = 16 and order_state = 2',
            //进行中
            't70' => 'order_type = 16 and order_state < 2',
            //已取消
            't80' => 'order_type = 16 and order_state = 99',
        ],
        //报告
        "t17" => [
            't0' => "(order_type = 17 or order_type = 18)",
            //待支付
            't60' => '(order_type = 17 or order_type = 18) and order_state = 3',
            //进行中
            't70' => '(order_type = 17 or order_type = 18) and order_state < 3',
            //已取消
            't80' => '(order_type = 17 or order_type = 18) and order_state = 99',
        ],
    ];

    /**
     * 订单状态描述
     */
    static $orderStatus = [
        //手术预约 状态值  描述信息 费用来源  收钱比例 系统是否可取消 用户是否可取消 后台显示描述 是否可支付  支付金额 后台是否可设置价格 后台是否可设置就诊时间 旧版本字段 支付显示信息 是否继续 vip金额item 过期时间 是否短信通知 前段显示的通知信息  后台是否可操作
        'ordertype1'  => [
            'type1'  => ['val' => 1, 'name' => '咨费待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '资讯费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 1, 'small_tip' => '服务咨询费尚未支付， 7 天后订单自动取消','sys_operable'=>'0','out_desc'=>'伙伴医生-手术预约服务(section_name)'],
            'type2'  => ['val' => 2, 'name' => '咨费已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '资讯费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '三个工作日内完成专家匹配，非我方原因不予退款','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '安排中', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '医学部正在为您匹配专家，请保持电话畅通','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '服务已完成，祝你早日康复！','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        //预约诊疗
        'ordertype5'  => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '30', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，7天后订单自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-预约诊疗服务（定金）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '您预约诊疗定金已支付，匹配专家后，定金不可退','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '安排中', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '1', 'configTime' => '1', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在为您匹配专家，工作日2小时内给您答复','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '预约服务费待支付', 'moneySource' => 't100', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '1', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '预约诊疗费用', 'continue' => '1', 'item' => '', 'expire' => 48, 'notice' => 1,'small_tip' => '请在定金支付后48小时内完成支付，过后无法继续','sys_operable'=>'0', 'out_desc' => '伙伴医生-预约诊疗服务（服务费）'],
            'type5'  => ['val' => 5, 'name' => '预约服务费已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约服务费已支付，请按照约定时间就医','sys_operable'=>'0'],
            'type6'  => ['val' => 6, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '服务已完成，祝你早日康复！','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        //vip套餐4
        'ordertype14'  => [
            'type1'  => ['val' => 1, 'name' => 'vip定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '699', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => 'vip预约定金尚未支付， 7天后订单自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-vip预约服务（套餐vip_type）定金'],
            'type2'  => ['val' => 2, 'name' => 'vip定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '定金已支付，匹配成功后，预约定金不退。','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '安排中', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '1', 'configTime' => '1', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在为您匹配专家，工作日2小时内给您答复','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => 'vip套餐费待支付', 'moneySource' => 't100', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '1', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '预约诊疗费用', 'continue' => '1', 'item' => '', 'expire' => 3, 'notice' => 1,'small_tip' => '确认信息后需2小时内完成支付；过后不可再支付，','sys_operable'=>'0', 'out_desc' => '伙伴医生-vip预约服务（套餐vip_type）服务费'],
            'type5'  => ['val' => 5, 'name' => 'vip套餐费已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => 'vip套餐费已支付，请保持电话畅通；做好就诊准备','sys_operable'=>'0'],
            'type6'  => ['val' => 6, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '服务已完成，祝你早日康复！','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        //购买商品
        'ordertype15'  => [
            'type1'  => ['val' => 1, 'name' => '未支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '商品名称', 'continue' => '1', 'item' => 't99', 'expire' => 0, 'notice' => 0, 'small_tip' => '商品未支付， 7天后订单自动取消','sys_operable'=>'0', 'out_desc' => '商品名称：order_type_desc'],
            'type2'  => ['val' => 2, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '支付成功，请查看我的服务','sys_operable'=>'0'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        //vip陪诊服务 套餐1,2,3
        'ordertype12' => [
            'type1'  => ['val' => 1, 'name' => 'vip定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => 't1', 'expire' => 0, 'notice' => 0, 'small_tip' => 'vip预约定金未支付 7 天后自动取消订单','sys_operable'=>'0', 'out_desc' => '伙伴医生-vip预约服务（套餐vip_type）定金'],
            'type2'  => ['val' => 2, 'name' => 'vip定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '定金已支付，匹配成功后，预约定金不退','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '安排中', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在为您匹配专家，工作日2小时内给您答复','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => 'vip套餐费待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '1', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => 'vip套餐费', 'continue' => '1', 'item' => 't2', 'expire' => 3, 'notice' => 1,'small_tip' => '确认信息后需2小时内完成支付；过后不可再支付','sys_operable'=>'1', 'out_desc' => '伙伴医生-vip预约服务（套餐vip_type）服务费'],
            'type5'  => ['val' => 5, 'name' => 'vip套餐费已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => 'vip套餐费已支付，请保持电话畅通；做好就诊准备','sys_operable'=>'0'],
            'type6'  => ['val' => 6, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '服务已完成，祝你早日康复！','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        //海外医疗
        'ordertype3'  => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        'ordertype6'  => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        'ordertype7'  => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        'ordertype8'  => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        'ordertype9'  => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        'ordertype10' => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        'ordertype11' => [
            'type1'  => ['val' => 1, 'name' => '预约定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '500', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约定金尚未支付，订单7天后自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-海外就医预约服务 -（order_type_desc）'],
            'type2'  => ['val' => 2, 'name' => '预约定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '海外就医定金已支付，正在为您建立海外就医通道','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '待安排', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在安排中，请保持好沟通，以便为您更好服务','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '祝你：海外就医体验愉快','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
        //其他
        'ordertype2'  => [
            'type1'  => ['val' => 1, 'name' => '订单已记录', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '30', 'configMoney' => '1', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'0'],
            'type2'  => ['val' => 2, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
        ],
        'ordertype4'  => [
            'type1'  => ['val' => 1, 'name' => '订单已记录', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '30', 'configMoney' => '1', 'configTime' => '1', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'0'],
            'type2'  => ['val' => 2, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
        ],
        'ordertype13'  => [
            'type1'  => ['val' => 1, 'name' => '订单已记录', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'0'],
            'type2'  => ['val' => 2, 'name' => '安排中', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
            'type3'  => ['val' => 3, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '0', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '定金费用支付', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '100', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '','sys_operable'=>'1'],
        ],
        //vip就医服务
        'ordertype20'  => [
            'type1'  => ['val' => 1, 'name' => '服务定金待支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '1', 'cancel' => '1', 'alias' => '', 'canPay' => '1', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'advise_price', 'payDesc' => '预约定金', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '定金尚未支付，7天后订单自动取消','sys_operable'=>'0', 'out_desc' => '伙伴医生-VIP就医服务（定金）'],
            'type2'  => ['val' => 2, 'name' => '服务定金已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '您VIP就医定金已支付','sys_operable'=>'0'],
            'type3'  => ['val' => 3, 'name' => '安排中', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'cancel' => '1', 'alias' => '', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '1', 'configTime' => '1', 'field' => '', 'payDesc' => '', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '正在为您匹配专家，工作日2小时内给您答复','sys_operable'=>'1'],
            'type4'  => ['val' => 4, 'name' => '服务费待支付', 'moneySource' => 't100', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '1', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '预约服务费', 'continue' => '1', 'item' => '', 'expire' => 48, 'notice' => 1,'small_tip' => '请在定金支付后48小时内完成支付，过后无法继续','sys_operable'=>'0', 'out_desc' => '伙伴医生-VIP就医服务（服务费）'],
            'type5'  => ['val' => 5, 'name' => '服务费已支付', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '1', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '1', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '预约服务费已支付，请按照约定时间就医','sys_operable'=>'0'],
            'type6'  => ['val' => 6, 'name' => '已完成', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '服务已完成，祝你早日康复！','sys_operable'=>'1'],
            'type99' => ['val' => 99, 'name' => '已取消', 'moneySource' => '', 'moneyRate' => '100', 'sysCancel' => '0', 'alias' => '', 'cancel' => '0', 'canPay' => '0', 'payMoney' => '0', 'configMoney' => '0', 'configTime' => '0', 'field' => 'order_price', 'payDesc' => '手术费用', 'continue' => '0', 'item' => '', 'expire' => 0, 'notice' => 0, 'small_tip' => '订单已取消','sys_operable'=>'1'],
        ],
    ];

    /**
     * 支付流程信息
     */
    static $process = [
        //购买商品
        'ordertype15' => ['1'],
        //手术预约
        'ordertype1'  => ['1'],
        //预约诊疗
        'ordertype5'  => ['1', '4'],
        //vip服务
        'ordertype12' => ['1', '4'],
        'ordertype14' => ['1', '4'],
        //海外医疗
        'ordertype3' => ['1', '4'],
        'ordertype6' => ['1', '4'],
        'ordertype7' => ['1', '4'],
        'ordertype8' => ['1', '4'],
        'ordertype9' => ['1', '4'],
        'ordertype10' => ['1', '4'],
        'ordertype11' => ['1', '4'],
    ];

    /**
     * 分类型分次付款
     * @var array
     */
    static $priceItemInfo = [
        'ordertype12' => [
            'type1' => [
                't1' => '199',
                't2' => '200',
            ],
            'type2' => [
                't1' => '399',
                't2' => '600',
            ],
            'type3' => [
                't1' => '599',
                't2' => '1000',
            ],
        ],
    ];
}