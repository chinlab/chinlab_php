<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/12/23
 * Time: 18:21
 */
namespace app\modules\patient\data;
use app\common\application\StateConfig;

class PayOutput {

    public static function formatData($data)
    {
        $idPrefix = 100 + $data['order_type'];
        //list.ordertype.id
        $data['pay_id'] = StateConfig::$orderType['type' . $data['order_type']]['list'] . $idPrefix . $data['pay_id'];
        return $data;
    }
}