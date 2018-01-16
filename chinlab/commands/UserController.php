<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\common\application\StateConfig;
use app\common\data\Response;
use app\modules\doctor\models\Desease;
use app\modules\doctor\models\Dtdisease;
use app\modules\doctor\models\Dtdoctor;
use app\modules\doctor\models\Dthospital;
use app\modules\doctor\models\DthospitalSections;
use app\modules\doctor\models\Dtsection;
use app\modules\patient\models\User;
use yii\console\Controller;
use yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserController extends Controller
{
    /**
     * 导入用户数据
     */
    public function actionIndex()
    {
        foreach($this->tuser as $k => $v) {
            $model = User::findOne(['user_mobile' => $v['user_mobile']]);
            if (!$model) {
                $model = new User;
                $model->user_id = Yii::$app->DBID->getID('db.tuser');
            }
            $model->user_name = $v['user_name'];
            $model->user_pass = $v['user_pass'];
            $model->user_img = "";
            $model->user_mobile = $v['user_mobile'];
            $model->session_key = $v['session_key'];
            $model->user_regtime = $v['user_regtime'];
            $model->save();
        }
    }

private $tuser = array(
array('user_id' => '2','user_name' => 'wwwwwwww','user_pass' => '21232f297a57a5a743894a0e4a801fc3','user_img' => '/upload/user/head/021031813762ef59638b084855d76455.jpg','user_mobile' => '13632207391','session_key' => '','user_regtime' => '2016-08-29 00:00:00','role' => '0'),
array('user_id' => '3','user_name' => '兴民','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => '/upload/user/head/bb66b91377fef048fd29b23283a01a11.jpg','user_mobile' => '18312064159','session_key' => '5710817817b71973e0006ef1c19f4a79','user_regtime' => '2016-08-29 00:00:00','role' => '0'),
array('user_id' => '8','user_name' => 'Noah','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => '/upload/user/head/cc387fcefc6516fd720675ed61441fb5.jpg','user_mobile' => '13560040689','session_key' => '0ffe2afb45d9dc3006c8d064ac20b84c','user_regtime' => NULL,'role' => '0'),
array('user_id' => '14','user_name' => '18312064156','user_pass' => '39D068D119818F8999DBFD160D4698A3','user_img' => '/upload/user/cover/acfca3c18128b386fafcffd5a1b927d2.jpg','user_mobile' => '18312064156','session_key' => 'fa6bf822e8a4fa2c8b6c5cf111e6a863','user_regtime' => '2016-09-13 18:16:44','role' => '0'),
array('user_id' => '15','user_name' => '18312064158','user_pass' => '4E23F6548AA3AE97FB8BABE7F8CE9833','user_img' => '/upload/user/cover/acfca3c18128b386fafcffd5a1b927d2.jpg','user_mobile' => '18312064158','session_key' => '99d3148fac83c3ec9e33ae10ea8bc2e3','user_regtime' => '2016-09-13 18:36:10','role' => '0'),
array('user_id' => '16','user_name' => '18312064157','user_pass' => '4E23F6548AA3AE97FB8BABE7F8CE9833','user_img' => '/upload/user/cover/acfca3c18128b386fafcffd5a1b927d2.jpg','user_mobile' => '18312064157','session_key' => '9ca2d6620b065527085890112cb0fd24','user_regtime' => '2016-09-19 11:48:11','role' => '0'),
array('user_id' => '17','user_name' => '石东阿','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => '/upload/user/head/eeb45ee0ac1099c1b95dcc44c9199bf8.jpg','user_mobile' => '13267122380','session_key' => 'ffd169bc21b100055bc2b28136733c5b','user_regtime' => '2016-09-23 17:17:44','role' => '0'),
array('user_id' => '18','user_name' => 'Jeff','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => NULL,'user_mobile' => '13825045224','session_key' => '316f4c09c3b7ff9d1ade40b7ae54ccd5','user_regtime' => '2016-09-26 17:57:13','role' => '0'),
array('user_id' => '19','user_name' => '李先生','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => NULL,'user_mobile' => '18926164291','session_key' => '3415b85742c43c3778318e8b677331b7','user_regtime' => '2016-09-27 10:12:02','role' => '0'),
array('user_id' => '20','user_name' => '英妃杨家将','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => '/upload/user/head/a69098133007ab31df1749a9a2118de1.jpg','user_mobile' => '18620048075','session_key' => '37e1ba991902c6f55eeb013b834bf676','user_regtime' => '2016-09-27 16:10:41','role' => '0'),
array('user_id' => '21','user_name' => '15013098288','user_pass' => '7EBD1E6C3122177D5651AE143C3CE212','user_img' => NULL,'user_mobile' => '15013098288','session_key' => '8a3092716fc1bb58607cdb4bfc7acd8a','user_regtime' => '2016-09-28 17:09:35','role' => '0'),
array('user_id' => '23','user_name' => '简单快乐','user_pass' => '212C645F96ADC4C2AC65474ADD460E6A','user_img' => '','user_mobile' => '15989091938','session_key' => '6785ec6cc5872fc621b01fb9b6dc265c','user_regtime' => '2016-10-12 17:25:41','role' => '0'),
array('user_id' => '25','user_name' => '测试人员','user_pass' => '572A7F2A565220EB612FF32844552B08','user_img' => '','user_mobile' => '13888888888','session_key' => '04d9bdd7d3840f094a8009f32f357b70','user_regtime' => '2016-10-26 15:42:00','role' => '0'),
array('user_id' => '26','user_name' => '13661059620','user_pass' => '8565EC272311D8E6F568E3C2F1C18E78','user_img' => NULL,'user_mobile' => '13661059620','session_key' => '702285da4b71f0061becadbeb1f2aab1','user_regtime' => '2016-10-27 20:19:34','role' => '0'),
array('user_id' => '27','user_name' => '13911021095','user_pass' => '49712BEF75CBD74FD4C2438CED3A9F12','user_img' => NULL,'user_mobile' => '13911021095','session_key' => '99901fb29fdbd939fef5e5b62e2e8218','user_regtime' => '2016-10-28 08:15:02','role' => '0'),
array('user_id' => '28','user_name' => '13661051751','user_pass' => '3A819A0CE4F13960E0B6B1658CC0DE17','user_img' => NULL,'user_mobile' => '13661051751','session_key' => '9941fdb5a527237328593e3a45061445','user_regtime' => '2016-10-28 10:22:17','role' => '0'),
array('user_id' => '29','user_name' => 'Jerry','user_pass' => '53273C8A53081BD3B8BD6E87404C4624','user_img' => '/upload/user/head/0140cc59a786043c5a272074e554d49b.jpg','user_mobile' => '13632820778','session_key' => 'b7134933df68cef453b735397461d787','user_regtime' => '2016-10-28 10:26:39','role' => '0'),
array('user_id' => '30','user_name' => '18513516339','user_pass' => '54FEABC2F6FF7FE5C83F2DF8CB92539F','user_img' => NULL,'user_mobile' => '18513516339','session_key' => '3df73eab514e702daeeb4aaff487f41f','user_regtime' => '2016-10-28 10:35:58','role' => '0'),
array('user_id' => '31','user_name' => '13426330817','user_pass' => 'B9536F2795C40C14D28228282AEB265C','user_img' => NULL,'user_mobile' => '13426330817','session_key' => '84d6a2bf672523e27f50c09a3f8d1220','user_regtime' => '2016-10-28 13:05:13','role' => '0'),
array('user_id' => '32','user_name' => '13122122001','user_pass' => '974573242B576D7F4F4D0C811ECC4930','user_img' => NULL,'user_mobile' => '13122122001','session_key' => '21d94dda78bb5add902e6595dd4310f6','user_regtime' => '2016-10-28 14:31:59','role' => '0'),
array('user_id' => '33','user_name' => '18630283658','user_pass' => '3AA235EEFAD0355F36C84C9CAE49E291','user_img' => NULL,'user_mobile' => '18630283658','session_key' => '1e23168527d17e1a83ee7eafd0f7840d','user_regtime' => '2016-10-28 17:40:50','role' => '0'),
array('user_id' => '34','user_name' => '18682208666','user_pass' => '33083D3CF6DF6BE4582C3B6CAC6FA1E5','user_img' => NULL,'user_mobile' => '18682208666','session_key' => '196e57da9e632a92794c649b1fac6af9','user_regtime' => '2016-10-28 20:25:44','role' => '0'),
array('user_id' => '35','user_name' => '15515095088','user_pass' => '1CBD24199B3DBA8A02F3A5BB8B2BB196','user_img' => NULL,'user_mobile' => '15515095088','session_key' => '6ebeb7f3e6ab6e4f00a9064c971b840e','user_regtime' => '2016-10-28 20:49:36','role' => '0'),
array('user_id' => '36','user_name' => '15120040598','user_pass' => 'A56D2BF088BEFF42850EB85345C1A2E2','user_img' => NULL,'user_mobile' => '15120040598','session_key' => 'de5f7a959434bf21ce770598ca8aa953','user_regtime' => '2016-10-30 10:11:37','role' => '0'),
array('user_id' => '38','user_name' => '18611736919','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => NULL,'user_mobile' => '18611736919','session_key' => '647a02be8f442896d8ba7a9d85a31b68','user_regtime' => '2016-10-31 14:00:41','role' => '0'),
array('user_id' => '39','user_name' => '13552563610','user_pass' => 'B34FA023DFE65B548BD6643FB7E7F48A','user_img' => '/upload/user/head/e8abb8704bcf7133b6e8d4bc71a3be62.jpg','user_mobile' => '13552563610','session_key' => '21b0121a86433ffeaa0dbf6d7456364d','user_regtime' => '2016-10-31 14:38:46','role' => '0'),
array('user_id' => '40','user_name' => '13717790790','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => NULL,'user_mobile' => '13717790790','session_key' => 'd7a47f31b180d35d45500edea660c6c6','user_regtime' => '2016-11-01 11:31:37','role' => '0'),
array('user_id' => '41','user_name' => '13910780927','user_pass' => 'EC687DFDBDDD165850A3CFB76B3FBAB5','user_img' => NULL,'user_mobile' => '13910780927','session_key' => 'a4024e3d3f6e198992c593a023add438','user_regtime' => '2016-11-01 16:50:39','role' => '0'),
array('user_id' => '42','user_name' => '13926053099','user_pass' => '8748465187E34EE93A78FCC724FC99A4','user_img' => NULL,'user_mobile' => '13926053099','session_key' => 'e5ef8271cabfc7aea99963b83c65c5ad','user_regtime' => '2016-11-02 23:53:38','role' => '0'),
array('user_id' => '43','user_name' => '18611783522','user_pass' => 'C32BF76E5E73B5CA6F27CE4971288E09','user_img' => NULL,'user_mobile' => '18611783522','session_key' => '9172c8b28cfac8ce41104b899d5990b8','user_regtime' => '2016-11-03 09:12:12','role' => '0'),
array('user_id' => '44','user_name' => '少年派','user_pass' => '4BE2772F128F1BF7D79C6A20C49C0281','user_img' => '/upload/user/head/5dfa01922f9bda5e2d76142c4b85d37d.jpg','user_mobile' => '18848980280','session_key' => '3fc2eb895f9a5cafe26fad2d24c5d3aa','user_regtime' => '2016-11-03 10:29:39','role' => '0'),
array('user_id' => '45','user_name' => '18611498038','user_pass' => '41C13A61220A02EE5B68C87A6D0FF3A8','user_img' => '/upload/user/head/4ab90160d67a3f69ec0e22eebcfd5856.jpg','user_mobile' => '18611498038','session_key' => 'ca45a6c88928019541342d469bdfb0f4','user_regtime' => '2016-11-03 18:00:26','role' => '0'),
array('user_id' => '46','user_name' => '15910846778','user_pass' => '573FEFE852E1743957C6CB0FE47BE306','user_img' => NULL,'user_mobile' => '15910846778','session_key' => '193e2f057a7828e05d13cbdf091fd8da','user_regtime' => '2016-11-03 18:06:31','role' => '0'),
array('user_id' => '47','user_name' => '13381032112','user_pass' => 'BF9BA02AD8B01DC0F23025115A360B1E','user_img' => NULL,'user_mobile' => '13381032112','session_key' => '9addc32eada9759a55bf0b0dca249def','user_regtime' => '2016-11-03 21:37:46','role' => '0'),
array('user_id' => '48','user_name' => '13770512915','user_pass' => '9DDEDDD3DD7B60E919121ED983C7FFB3','user_img' => NULL,'user_mobile' => '13770512915','session_key' => '8ec89741ac232b6e1e6672e6acfd4fc7','user_regtime' => '2016-11-04 11:53:39','role' => '0'),
array('user_id' => '49','user_name' => '18610141301','user_pass' => '6E1FB226B651DA9B852EC2C3D5AF53DF','user_img' => NULL,'user_mobile' => '18610141301','session_key' => '76b651f76144c4b004ce82463ef6e0e9','user_regtime' => '2016-11-06 13:26:27','role' => '0'),
array('user_id' => '51','user_name' => 'test','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => '/upload/user/head/8eddaecd1ccdc387e7140d72d0c5712f.jpg','user_mobile' => '18210879145','session_key' => '8d200d4b484c1010a9de5f0e40b60e35','user_regtime' => '2016-11-07 15:02:20','role' => '0'),
array('user_id' => '52','user_name' => '18810802566','user_pass' => '5657D5BFE450C9E0F2A9431F7C1B3C7D','user_img' => NULL,'user_mobile' => '18810802566','session_key' => '6cd075562edc00daa3f4d38ce147cac7','user_regtime' => '2016-11-07 17:30:40','role' => '0'),
array('user_id' => '53','user_name' => 'Zheng','user_pass' => '6897C39F41956E7DCB0940007B607AF6','user_img' => '/upload/user/head/0fcb74041e5652210a770441f4a9e2ec.jpg','user_mobile' => '17710061838','session_key' => '4e258f87f30ce53e3dab3da031d04339','user_regtime' => '2016-11-07 21:42:47','role' => '0'),
array('user_id' => '54','user_name' => '18610754353','user_pass' => 'F4DEBD976C5BCD4655BAA1882D3A3DDF','user_img' => NULL,'user_mobile' => '18610754353','session_key' => 'fee0940fc97ee90d65111cc7c9bdcbbd','user_regtime' => '2016-11-08 09:48:57','role' => '0'),
array('user_id' => '55','user_name' => '18618348383','user_pass' => 'B5319F11B32BB998CCD86E71E354C34D','user_img' => NULL,'user_mobile' => '18618348383','session_key' => '03f2cbafdaf8dc3669c57df08f67aaa7','user_regtime' => '2016-11-08 13:45:14','role' => '0'),
array('user_id' => '56','user_name' => '13269728109','user_pass' => '30ECC8F4CC23664B5A3DCCEAA729CB2E','user_img' => '/upload/user/head/73f4c5e1f1f0f924e355ee045aff09da.jpg','user_mobile' => '13269728109','session_key' => '610ae5bb35ba83ff10a6471921c3fecc','user_regtime' => '2016-11-08 15:52:46','role' => '0'),
array('user_id' => '57','user_name' => '18311214614','user_pass' => 'CB0253A29A5DCA9DAAE9215F268A1B4B','user_img' => NULL,'user_mobile' => '18311214614','session_key' => 'b0b417bd061d85825d4ad386e58ebcd6','user_regtime' => '2016-11-10 10:24:25','role' => '0'),
array('user_id' => '58','user_name' => '13760446777','user_pass' => 'FE5CF88C0A60A9CE3F8B981301201A31','user_img' => NULL,'user_mobile' => '13760446777','session_key' => '3be14016d67d026fcda1fc86ed38e120','user_regtime' => '2016-11-11 13:21:12','role' => '0'),
array('user_id' => '59','user_name' => '发达的综上所述善始善终自作自受大','user_pass' => 'C16CCAFBFC1A738F7CDBB11524F0F927','user_img' => NULL,'user_mobile' => '18611624018','session_key' => 'f997e21ee2380e3686b090878b8116d6','user_regtime' => '2016-11-11 15:00:49','role' => '0'),
array('user_id' => '60','user_name' => '灯火乱了阑珊','user_pass' => 'C9FED68551D90B14D6FD5C5B2713C65B','user_img' => '/upload/user/head/233ee7b92500ddcc8b2e4bd8a0a4e70e.jpg','user_mobile' => '18515422930','session_key' => '64703466d3b5e3d892f7dbc974bed767','user_regtime' => '2016-11-12 12:08:04','role' => '0'),
array('user_id' => '61','user_name' => '18116268282','user_pass' => '48CB7AB518376380F7768EB5C672906C','user_img' => NULL,'user_mobile' => '18116268282','session_key' => '38c48e6ccdedb511f0517083d65c8739','user_regtime' => '2016-11-12 14:51:35','role' => '0'),
array('user_id' => '62','user_name' => '18515063072','user_pass' => '3ED745F12BB667812B4BAEBA5C2FD152','user_img' => '/upload/user/head/36406756544b79538d858c1f21875b53.jpg','user_mobile' => '18515063072','session_key' => 'e40a0003e54494c14bdd88a062631072','user_regtime' => '2016-11-14 14:19:13','role' => '0'),
array('user_id' => '63','user_name' => '15811178886','user_pass' => '85F25FAEF5AAEE7D9A0EBFD710602246','user_img' => NULL,'user_mobile' => '15811178886','session_key' => '04f28f6cb6cf67cbb700fb3dc31f4f8d','user_regtime' => '2016-11-16 19:24:39','role' => '0'),
array('user_id' => '64','user_name' => '13661323910','user_pass' => '9F4B0AFDD09DC0650A8DA809C723FD1D','user_img' => NULL,'user_mobile' => '13661323910','session_key' => '5fb258fc126f5f88d2d0b724113a2edc','user_regtime' => '2016-11-20 20:56:19','role' => '0'),
array('user_id' => '65','user_name' => '13301091695','user_pass' => 'C0E7A3029FE06C8B788815C92378027D','user_img' => NULL,'user_mobile' => '13301091695','session_key' => '72a0f4359008e9b87ece4bc962744a0a','user_regtime' => '2016-11-21 12:02:33','role' => '0'),
array('user_id' => '66','user_name' => '15810851163','user_pass' => '2B45E9EECFB5F5608A6921F7846E0F87','user_img' => NULL,'user_mobile' => '15810851163','session_key' => '1eb1874dbb68f4f56fa9b8884bc9076f','user_regtime' => '2016-11-21 12:39:47','role' => '0'),
array('user_id' => '67','user_name' => '13910701109','user_pass' => '4905D943FF39B7376A988EF6CDA1B74C','user_img' => NULL,'user_mobile' => '13910701109','session_key' => 'a464fa0047f36bfe19d8968aac7781e4','user_regtime' => '2016-11-21 13:25:12','role' => '0'),
array('user_id' => '68','user_name' => '13501006657','user_pass' => '02E5B540C6693D187EC4F659B1DBA19A','user_img' => NULL,'user_mobile' => '13501006657','session_key' => '26cbdc77fd88c1565ff1c6f93eb3e044','user_regtime' => '2016-11-22 00:59:17','role' => '0'),
array('user_id' => '69','user_name' => '18611732758','user_pass' => 'CF76FEB3C42939F06E2EA3DB7F6431F3','user_img' => NULL,'user_mobile' => '18611732758','session_key' => '9062ec42124ee53e133599ea1cdb4fec','user_regtime' => '2016-11-22 10:03:52','role' => '0'),
array('user_id' => '70','user_name' => '13811182835','user_pass' => '83F4C50C7284A067EACCA9D4B17398EA','user_img' => NULL,'user_mobile' => '13811182835','session_key' => '13c90125517053d1057ff74b280b5ab5','user_regtime' => '2016-11-22 10:49:45','role' => '0'),
array('user_id' => '71','user_name' => '13709853223','user_pass' => 'A5714A0364B9905455CEF0A4D8A279F4','user_img' => NULL,'user_mobile' => '13709853223','session_key' => 'a961893533b9957e28a02b2a80970b19','user_regtime' => '2016-11-22 14:13:13','role' => '0'),
array('user_id' => '72','user_name' => '18500241189','user_pass' => '7903DD5A4D853E25D1381DE37DA59643','user_img' => NULL,'user_mobile' => '18500241189','session_key' => 'b3bff45d085bf44346fa84eaab000235','user_regtime' => '2016-11-23 08:49:09','role' => '0'),
array('user_id' => '73','user_name' => '18291958218','user_pass' => 'B72A7C4C948EC70D7260764AA036A946','user_img' => NULL,'user_mobile' => '18291958218','session_key' => 'c276675cf97c1260d1a2dd9cd751929f','user_regtime' => '2016-11-23 11:53:27','role' => '0'),
array('user_id' => '74','user_name' => '13701380091','user_pass' => '3C5C8D94FC861D8A2531580BC67F9762','user_img' => '/upload/user/head/14e1119ef493a823824364c08f12136c.jpg','user_mobile' => '13701380091','session_key' => 'aff16601de8ef944c54c134e0195065c','user_regtime' => '2016-11-23 12:27:15','role' => '0'),
array('user_id' => '75','user_name' => '13917579894','user_pass' => 'C5A526A9C3C6105F342DCB06F4FE8FF6','user_img' => NULL,'user_mobile' => '13917579894','session_key' => 'e9678221381d5eb902e603435efe1c8a','user_regtime' => '2016-11-23 12:40:15','role' => '0'),
array('user_id' => '76','user_name' => '15801613277','user_pass' => '95EBC4E5110735CCB9BA3D21930A6668','user_img' => NULL,'user_mobile' => '15801613277','session_key' => '0adfac5a7659ea4a1ec215477713eac4','user_regtime' => '2016-11-23 12:55:52','role' => '0'),
array('user_id' => '77','user_name' => '13917733447','user_pass' => '9E2E7F6BDA934638ED9D8525E5C26748','user_img' => NULL,'user_mobile' => '13917733447','session_key' => '82601c93582206c543a2a05341eb73ca','user_regtime' => '2016-11-23 13:19:19','role' => '0'),
array('user_id' => '78','user_name' => '13858059444','user_pass' => '863A779CA36D2EC6C20B6376ABE25EC2','user_img' => NULL,'user_mobile' => '13858059444','session_key' => 'aec389df17d83982c12f52960e0f1f17','user_regtime' => '2016-11-23 13:28:59','role' => '0'),
array('user_id' => '79','user_name' => '13601818098','user_pass' => '97D66880A604ED78AA99F5F24D87CC0A','user_img' => NULL,'user_mobile' => '13601818098','session_key' => '1471584a07dc731ef4a6ea7bac64618a','user_regtime' => '2016-11-23 13:36:07','role' => '0'),
array('user_id' => '80','user_name' => '18600360519','user_pass' => 'BF5EE75106D9DDA0359519B62F1068D8','user_img' => NULL,'user_mobile' => '18600360519','session_key' => '309bcc99f5ee1d308c1e0ad3c7bc3e6f','user_regtime' => '2016-11-23 13:37:15','role' => '0'),
array('user_id' => '81','user_name' => '18181978161','user_pass' => '5769B4E74D8214A23140CC3653634376','user_img' => NULL,'user_mobile' => '18181978161','session_key' => 'eff5129aa39b41038cac0835aa0bc485','user_regtime' => '2016-11-23 13:52:30','role' => '0'),
array('user_id' => '83','user_name' => '13911826719','user_pass' => 'D117BF9C0C7E896EE9C37B339C71883B','user_img' => NULL,'user_mobile' => '13911826719','session_key' => '6ae5492d7ad409ad6fc90e1fb11c4078','user_regtime' => '2016-11-23 15:42:40','role' => '0'),
array('user_id' => '84','user_name' => '13510566081','user_pass' => '421A8EE70366347DEC98B267BF418406','user_img' => NULL,'user_mobile' => '13510566081','session_key' => 'e9987b4e12f6fb5fd8267618991a4e6d','user_regtime' => '2016-11-23 18:56:08','role' => '0'),
array('user_id' => '85','user_name' => '18601019729','user_pass' => 'CEF94FE5CB4EC9E4EFB3EBA316FDFCF3','user_img' => NULL,'user_mobile' => '18601019729','session_key' => '811953e21e13ac15bce329f32dc5422b','user_regtime' => '2016-11-23 23:56:19','role' => '0'),
array('user_id' => '86','user_name' => '18976645567','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => NULL,'user_mobile' => '18976645567','session_key' => '45de4f54fd7035af5e5e2323ae9f5a94','user_regtime' => '2016-11-24 01:13:02','role' => '0'),
array('user_id' => '87','user_name' => '13501166531','user_pass' => '6F3D3BA5CA7ECA5B55DC3D03AF3415D9','user_img' => NULL,'user_mobile' => '13501166531','session_key' => '393be6e3bddf13a1d1b3a9ab0f6c3b4d','user_regtime' => '2016-11-24 11:26:55','role' => '0'),
array('user_id' => '88','user_name' => '15907976618','user_pass' => '96378D75EAF409BD8A84D037B65237FD','user_img' => NULL,'user_mobile' => '15907976618','session_key' => '9b0be048371372b75fe32085da7aa175','user_regtime' => '2016-11-24 21:13:17','role' => '0'),
array('user_id' => '89','user_name' => '18101116728','user_pass' => '093439DB5F3A9F33F1BBB9E781C7F6A7','user_img' => NULL,'user_mobile' => '18101116728','session_key' => 'ce6df7821b23b9418af1b82f0b6c66de','user_regtime' => '2016-11-24 23:28:26','role' => '0'),
array('user_id' => '90','user_name' => '13922153576','user_pass' => '6A53D56F46E9A66C4B74982BB6E81034','user_img' => NULL,'user_mobile' => '13922153576','session_key' => '7de6ba0b3f727e5fd73cc35276ca1231','user_regtime' => '2016-11-25 07:17:30','role' => '0'),
array('user_id' => '91','user_name' => '18609859688','user_pass' => '32DF5227808219AD3B60DA1FFECBC988','user_img' => '/upload/user/head/f220a0411892635a5fa597f9fd59824d.jpg','user_mobile' => '18609859688','session_key' => 'b2f8cdf3684e8c7f7fc8567c43fa92f9','user_regtime' => '2016-11-25 08:58:52','role' => '0'),
array('user_id' => '92','user_name' => '17701259022','user_pass' => '2A8810E9FD6D95BC0E329579B6ED31D3','user_img' => NULL,'user_mobile' => '17701259022','session_key' => '37d21ff1cfc8519a37831928f2331be9','user_regtime' => '2016-11-25 10:36:36','role' => '0'),
array('user_id' => '93','user_name' => '18620136906','user_pass' => '6DD1DAF917B47DC34F3EFD26EBF0D760','user_img' => NULL,'user_mobile' => '18620136906','session_key' => 'd84c8da1fea918d2304bcf849ded3e45','user_regtime' => '2016-11-25 13:42:26','role' => '0'),
array('user_id' => '94','user_name' => '15298391106','user_pass' => '419F8DCB0B799FCB86623305C71D16E8','user_img' => NULL,'user_mobile' => '15298391106','session_key' => 'e597fce3641b117ed482c6baebf6c58b','user_regtime' => '2016-11-26 17:28:31','role' => '0'),
array('user_id' => '95','user_name' => '13819101999','user_pass' => '3E98A641F7D646545189BE867BDA13CA','user_img' => NULL,'user_mobile' => '13819101999','session_key' => '346e061eefa5dc9c162b6fc8cbebc9b2','user_regtime' => '2016-11-27 20:36:34','role' => '0'),
array('user_id' => '96','user_name' => '18501340269','user_pass' => '495158DD94AF82816E54C6FA6CF8F89F','user_img' => NULL,'user_mobile' => '18501340269','session_key' => '85fea166c0be51b14d7d687ba803eed0','user_regtime' => '2016-11-28 08:32:58','role' => '0'),
array('user_id' => '97','user_name' => '13758154080','user_pass' => 'EFECEE2A22ACE64AFAE0B72C44BB72BB','user_img' => NULL,'user_mobile' => '13758154080','session_key' => '722d1aae8c89d16c5198b95ff49f4d97','user_regtime' => '2016-11-28 09:02:13','role' => '0'),
array('user_id' => '98','user_name' => '13625003355','user_pass' => 'CF8C2A311C6B3E1D9432CD80CC98B26A','user_img' => NULL,'user_mobile' => '13625003355','session_key' => 'a615150f457623c5de7fb2d6ad6ac96d','user_regtime' => '2016-11-29 11:01:38','role' => '0'),
array('user_id' => '101','user_name' => '13817629923','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => NULL,'user_mobile' => '13817629923','session_key' => 'de577b74c4a955d9c61fa5f2cddccb8a','user_regtime' => '2016-12-11 23:23:08','role' => '0'),
array('user_id' => '102','user_name' => '15600013681','user_pass' => 'B894856D701A96468481CBA2FD414AFD','user_img' => NULL,'user_mobile' => '15600013681','session_key' => '2e59666434421ba8090278ddfc62d78b','user_regtime' => '2016-12-12 12:16:12','role' => '0'),
array('user_id' => '103','user_name' => '','user_pass' => '1040DDFFFA3471824936705E5096ED26','user_img' => '','user_mobile' => '','session_key' => '79f733d53176837ee130cdbf3187e07f','user_regtime' => '2016-12-12 16:09:21','role' => '0'),
array('user_id' => '105','user_name' => '测试','user_pass' => '335D0F051BA4D8B0E57DDC7F66EEB5D9','user_img' => '/upload/user/head/f7da8eb8a20d7e98c8bfd683dce3827b.jpg','user_mobile' => '18801173469','session_key' => '21030131c93db40dded11b3466f7c402','user_regtime' => '2016-12-12 18:22:10','role' => '0')
);
}
