<?php

namespace app\modules\patient\controllers;

use app\common\application\CardConfig;
use app\modules\patient\models\CardInfo;
use app\modules\patient\models\CardOrderService;
use Yii;
use yii\base\Exception;
use yii\log\Logger;

class CardorderserviceController extends \app\common\controller\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetdetail($card_order_no) {

        if (!$card_order_no) {
            return [];
        }
        try {
            $result = CardOrderService::find()
                ->where(['card_order_id' => $card_order_no])
                ->limit(100)->orderBy('create_time desc')
                ->asArray()->all();
            $map = [
                "current_hospital_id",
                "current_hospital_name",
                "current_hospital_section_id",
                "current_section_name",
                "current_doctor_name",
                "current_order_date",
            ];
            $oldMap = [
                "hospital_id",
                "hospital_name",
                "hospital_section_id",
                "section_name",
                "doctor_name",
                "order_date",
            ];

            $modules = Yii::$app->getModule('patient');
            $payMap = [
                    "1" => "支付宝",
                    "2" => "微信",
                    "3" => "银联",
            ];
            foreach($result as $k => $v) {

                $desc = $this->getCardServiceCount($v['card_order_id'], $v['goods_service_type']);
                if ($v['goods_service_type'] == "61") {
                    $result[$k]['image_order_id'] = "158" . ($v['goods_service_type'] + 100) . $v['card_order_id'];
                } else {
                    $result[$k]['image_order_id'] = "";
                }

                $payInfo = $modules->runAction('pay/getInfoByUidOrderIdCard',
                    ["orderId" => $v['card_order_id']]);
                $result[$k]["pay_method"] = "去医院支付";
                if ($v['goods_service_type'] == "59") {
                    $result[$k]["pay_method"] = "待定";
                }
                if ($payInfo) {
                    $result[$k]["pay_method"] = $payMap[$payInfo['pay_type']];
                }
                $result[$k]['desc'] = $desc;
                $result[$k]['service_status_desc'] = CardConfig::$service["t".$v['goods_service_type']]['status']["s".$v['service_status']]['name'];
                $tmpInfo = json_decode(strval($v['user_other_info']), true);
                //todo 临时处理
                if ($v['goods_service_type'] == "59" && $v['service_status'] == 1) {
                    $result[$k]['order_id'] = "158159".$v['card_order_id'];
                    $result[$k]['pay_money'] = CardConfig::getDoctorMoney($tmpInfo['doctor_level_id']);
                } else {
                    $result[$k]['order_id'] = "";
                    $result[$k]['pay_money'] = "0";
                }
                unset($result[$k]['user_other_info']);
                $categoryType = CardConfig::$service['t'.$v['goods_service_type']]['type'];
                $tmpFlag = false;
                foreach(CardConfig::$serviceExtra[$categoryType] as $kk => $vv) {
                    if (isset($tmpInfo[$kk])) {
                        $result[$k][$kk] = is_int($tmpInfo[$kk]) ? strval($tmpInfo[$kk]) : $tmpInfo[$kk];
                    } else {
                        $result[$k][$kk] = "";
                    }
                    if ($kk == "order_file" && !is_array($result[$k][$kk])) {
                        $result[$k][$kk] = [];
                    }
                    if (in_array($kk, $map) && $result[$k][$kk]) {
                        $tmpFlag = true;
                    }
                }
                if (isset($result[$k]['order_file'])) {
                    foreach ($result[$k]['order_file'] as $ok => $ov) {
                        if (strpos($ov, "http") === false) {
                            unset($result[$k]['order_file'][$ok]);
                        }
                    }
                    $result[$k]['order_file'] = array_values($result[$k]['order_file']);
                }
                //格式化retry——info
                if (isset($result[$k]['retry_info'])) {
                    $result[$k]['retry_info'] = explode(",", trim($result[$k]['retry_info']));
                    if (!is_array($result[$k]['retry_info'])) {
                        $result[$k]['retry_info'] = [];
                    }
                    foreach ($result[$k]['retry_info'] as $kk => $vv) {
                        if (!is_numeric($vv)) {
                            unset($result[$k]['retry_info'][$kk]);
                        }
                    }
                    $result[$k]['retry_info'] = array_values($result[$k]['retry_info']);

                    $tmpRetryInfo = [];
                    foreach($result[$k]["retry_info"] as $kk => $vv) {
                        if (isset($result[$k]["order_file"][intval($vv)])) {
                            $tmpRetryInfo[] = $result[$k]["order_file"][intval($vv)];
                        }
                    }
                    $result[$k]['retry_info'] = $tmpRetryInfo;
                }
                if (isset($result[$k]['retry_status']) && $result[$k]['retry_status']) {
                    $result[$k]['image_order_id'] = "158" . ($v['goods_service_type'] + 100) . $v['card_order_id'];
                } else {
                    $result[$k]['image_order_id'] = "";
                }
                if (isset($result[$k]['current_order_date']) && strlen($result[$k]['current_order_date'])) {
                    //$result[$k]['current_order_date'] .= " —— " . date("H:i", strtotime($result[$k]['current_order_date']) + 3600);
                }
                if (isset($result[$k]['order_date']) && strlen($result[$k]['order_date'])) {
                    //$result[$k]['order_date'] .= " —— " . date("H:i", strtotime($result[$k]['order_date']) + 3600);
                }
                if (isset($result[$k]['disease_desc'])) {
                    $result[$k]['disease_des'] = $result[$k]['disease_desc'];
                }
                if ($tmpFlag) {
                    foreach($map as $kk => $vv) {
                        $result[$k][$oldMap[$kk]] = $result[$k][$vv];
                    }
                }
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    public function getCardServiceCount($card, $service) {

        $customer = CardInfo::findOne($card);
        if (!$customer) {
            return "";
        }
        $result = $customer->toArray();
        $result['goods_service'] = json_decode($result['goods_service'], true);
        foreach($result["goods_service"] as $k => $v) {
            if ($v['id'] == $service) {
                return str_replace("{count}", $v["count"], CardConfig::$service["t".$v['id']]['desc'][0]);
            }
        }
        return "";
    }

    public function actionGetlist($card, $service) {

        if (!$card || !$service) {
            return [];
        }
        try {

            $map = [
                "current_hospital_id",
                "current_hospital_name",
                "current_hospital_section_id",
                "current_section_name",
                "current_doctor_name",
                "current_order_date",
            ];
            $oldMap = [
                "hospital_id",
                "hospital_name",
                "hospital_section_id",
                "section_name",
                "doctor_name",
                "order_date",
            ];

            $desc = $this->getCardServiceCount($card, $service);

            if ($service == "58") {
                $result = CardOrderService::find()
                    ->where(['card_no' => $card, ])
                    ->andWhere(['in', 'goods_service_type', ["58", "59"]])
                    ->limit(100)->orderBy('create_time desc')
                    ->asArray()->all();
            } else {
                $result = CardOrderService::find()
                    ->where(['card_no' => $card, 'goods_service_type'=>$service])
                    ->limit(100)->orderBy('create_time desc')
                    ->asArray()->all();
            }


            foreach($result as $k => $v) {

                $result[$k]['service_status_desc'] = CardConfig::$service["t".$v['goods_service_type']]['status']["s".$v['service_status']]['name'];
                if ($v['goods_service_type'] == "61") {
                    $result[$k]['image_order_id'] = "158" . ($v['goods_service_type'] + 100) . $v['card_order_id'];
                } else {
                    $result[$k]['image_order_id'] = "";
                }
                $result[$k]['desc'] = $desc;
                $tmpInfo = json_decode(strval($v['user_other_info']), true);
                unset($result[$k]['user_other_info']);
                $result[$k]["pay_method"] = "去医院支付";
                //todo 临时处理
                if ($v['goods_service_type'] == "59" && $v['service_status'] == 1) {
                    $result[$k]['order_id'] = "158159".$v['card_order_id'];
                    $result[$k]['pay_money'] = CardConfig::getDoctorMoney($tmpInfo['doctor_level_id']);
                } else {
                    $result[$k]['order_id'] = "";
                    $result[$k]['pay_money'] = "0";
                }
                $categoryType = CardConfig::$service['t'.$service]['type'];
                $tmpFlag = false;
                foreach(CardConfig::$serviceExtra[$categoryType] as $kk => $vv) {
                    if (isset($tmpInfo[$kk])) {
                        $result[$k][$kk] = is_int($tmpInfo[$kk]) ? strval($tmpInfo[$kk]) : $tmpInfo[$kk];
                    } else {
                        $result[$k][$kk] = "";
                    }
                    if ($kk == "order_file" && !is_array($result[$k][$kk])) {
                        $result[$k][$kk] = [];
                    }
                    if (in_array($kk, $map) && $result[$k][$kk]) {
                        $tmpFlag = true;
                    }
                }
                if (isset($result[$k]['retry_status']) && $result[$k]['retry_status']) {
                    $result[$k]['image_order_id'] = "158" . ($v['goods_service_type'] + 100) . $v['card_order_id'];
                } else {
                    $result[$k]['image_order_id'] = "";
                }
                if (isset($result[$k]['order_file'])) {
                    foreach ($result[$k]['order_file'] as $ok => $ov) {
                        if (strpos($ov, "http") === false) {
                            unset($result[$k]['order_file'][$ok]);
                        }
                    }
                    $result[$k]['order_file'] = array_values($result[$k]['order_file']);
                }
                //格式化retry——info
                if (isset($result[$k]['retry_info'])) {
                    $result[$k]['retry_info'] = explode(",", trim($result[$k]['retry_info']));
                    if (!is_array($result[$k]['retry_info'])) {
                        $result[$k]['retry_info'] = [];
                    }
                    foreach ($result[$k]['retry_info'] as $kk => $vv) {
                        if (!is_numeric($vv)) {
                            unset($result[$k]['retry_info'][$kk]);
                        }
                    }
                    $result[$k]['retry_info'] = array_values($result[$k]['retry_info']);
                    $tmpRetryInfo = [];
                    foreach($result[$k]["retry_info"] as $kk => $vv) {
                        if (isset($result[$k]["order_file"][intval($vv)])) {
                            $tmpRetryInfo[] = $result[$k]["order_file"][intval($vv)];
                        }
                    }
                    $result[$k]['retry_info'] = $tmpRetryInfo;
                }

                if (isset($result[$k]['current_order_date']) && strlen($result[$k]['current_order_date'])) {
                    //$result[$k]['current_order_date'] .= " —— " . date("H:i", strtotime($result[$k]['current_order_date']) + 3600);
                }
                if (isset($result[$k]['order_date']) && strlen($result[$k]['order_date'])) {
                    //$result[$k]['order_date'] .= " —— " . date("H:i", strtotime($result[$k]['order_date']) + 3600);
                }
                if (isset($result[$k]['disease_desc'])) {
                    $result[$k]['disease_des'] = $result[$k]['disease_desc'];
                }
                if ($tmpFlag) {
                    foreach($map as $kk => $vv) {
                        $result[$k][$oldMap[$kk]] = $result[$k][$vv];
                    }
                }
            }
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    public function actionCreate($info)
    {

        try {
            $user = new CardOrderService();
            foreach ($info as $k => $v) {
                $user->$k = $v;
            }
            $user->save();
            $result = $user->toArray();
            return $result;
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }
}
