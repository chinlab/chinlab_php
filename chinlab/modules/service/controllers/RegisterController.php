<?php

namespace app\modules\service\controllers;
require dirname(__DIR__) . "/../../vendor/qiniu/php-sdk/autoload.php";
use yii\log\Logger;
use yii\base\Exception;
use app\common\data\Encrypt;
use app\common\data\GetParams;
use app\common\data\Particle;
use yii\web\UploadedFile;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use app\common\application\orderversion\UserInfor;
use app\common\data\Response as UResponse;
use Yii;

class RegisterController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return array
     *
     * 查询用户是否注册过
     *
     */
    public function actionGetUserPwd () {
        try {

            $user_name = Yii::$app->getParams->post("user_name");
            if(!$user_name){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱不能为空");
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/GetInfoByName", ['user_name' => $user_name]);

            if ($userInfo) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户已注册");
            }

            return UResponse::formatData("0", "用户可以注册");
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }
    /**
     * @return array|bool
     * @throws \app\common\data\Exception
     * @throws \yii\base\InvalidRouteException
     *
     * 用户注册
     *
     */
    public function actionRegisterUser () {

        try {

            $user_name = Yii::$app->getParams->post("user_name");
            $user_passwd = Yii::$app->getParams->post("user_passwd");
            if(!$user_name || !$user_passwd){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱或者密码不能为空");
            }

            $user_passwd = Encrypt::mymd5_4($user_passwd);
            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/GetInfoByName", ['user_name' => $user_name]);

            if ($userInfo) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户已注册,请登录");
            }

            $userInfo = [
                "user_id"          => Particle::generateParticle(),
                "user_name"        => $user_name,
                "user_passwd"      => $user_passwd,
                "create_time"      => time()
            ];

            $module = Yii::$app->getModule("subscriber");
            $result = $module->runAction("user/createUser", ['info' => $userInfo]);

            if(!$result){
                return UResponse::formatData(UResponse::$code['InternalError'], "注册失败");
            }

            $outputdata = [
                "user_id" => strval($userInfo["user_id"]),
                "user_name" => $userInfo["user_name"],
                "user_passwd" => $userInfo["user_passwd"],
                "user_image"       => "",
                "country_id"       => "",
                "lang"             => "",
                "interest"         => "",
                "user_initlevel"   => "",
                "user_curlevel"    => "",
                "user_targetlevel" => "",
                "learn_time"       => "",
                "user_age"         => "",
                "user_agent"       => "",
                "user_nickname"    => "",
                "create_time"      => ""
            ];
            return UResponse::formatData("0", "用户注册成功", $outputdata);
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['AccessDeny'], "系统错误");
        }
    }

    /**
     * @return array
     *
     * 用户画像
     *
     */
    public function actionUserPortrait () {

        try {
            $user_id = Yii::$app->getParams->post('user_id');
            if (!$user_id) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户ID不能为空");
            }
            $country_id = Yii::$app->getParams->post("country_id");
            if(!$country_id){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "国家不能为空");
            }

            $lang = Yii::$app->getParams->post("lang");
            if(!$lang){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "母语不能为空");
            }

            $interest = Yii::$app->getParams->post("interest");
            if(!$interest){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "兴趣不能为空");
            }
            //处理数据  转换为json入库
            $result = UserInfor::$objectives;
            $exist = explode(',',$interest);
            $count = count($exist);
            $arr_slice = array_slice($result,1,$count);

            $user_initlevel = Yii::$app->request->post("user_initlevel");
            if(is_null($user_initlevel)){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户初始级别不能为空");
            }

            $user_curlevel = Yii::$app->request->post("user_curlevel");
            if(is_null($user_curlevel)){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户当前级别不能为空");
            }

            $user_targetlevel = Yii::$app->getParams->post("user_targetlevel");
            if(!$user_targetlevel){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户目标级别不能为空");
            }

            $learn_time = Yii::$app->getParams->post("learn_time");
            if(!$user_targetlevel){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户设置学习时间不能为空");
            }
            
            $user_age = Yii::$app->getParams->post("user_age");
            if(!$user_age){
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户生日不能为空");
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoById", ['user_id' => $user_id]);

            if ($userInfo) {
                $userData = [
                    "country_id"       => $country_id,
                    "lang"             => $lang,
                    "interest"         => json_encode($arr_slice),
                    "user_initlevel"   => $user_initlevel,
                    "user_curlevel"    => $user_curlevel,
                    "user_targetlevel" => $user_targetlevel,
                    "learn_time"       => $learn_time,
                    "user_age"         => $user_age,
                ];

                $module = Yii::$app->getModule("subscriber");
                $usersInfo = $module->runAction("user/updateUserInfo", ['user_id' => $userInfo['user_id'], 'info' => $userData
                ]);

                if ($usersInfo) {

                    $result = UserInfor::$learnTime;
                    $res = UserInfor::$motherTongue;
                    $arr_time = $result[$usersInfo['learn_time']-1]['name'];
                    $arr_lang = $res[$usersInfo['lang']-1]['name'];

                    $outputdata = [
                        "user_id"          => $usersInfo['user_id'],
                        "user_name"        => $usersInfo['user_name'],
                        "user_passwd"      => $usersInfo['user_passwd'],
                        "user_image"       => $usersInfo['user_image'],
                        "country_id"       => $usersInfo['country_id'],
                        "lang"             => $arr_lang,
                        "interest"         => $interest,
                        "user_initlevel"   => $usersInfo['user_initlevel'],
                        "user_curlevel"    => $usersInfo['user_curlevel'],
                        "user_targetlevel" => $usersInfo['user_targetlevel'],
                        "learn_time"       => $arr_time,
                        "user_age"         => $usersInfo['user_age'],
                        "user_agent"       => $usersInfo['user_agent'],
                        "user_nickname"    => $usersInfo['user_nickname'],
                        "create_time"      => ""
                    ];
                    return UResponse::formatData("0", "登录成功",$outputdata);
                } else {

                    return UResponse::formatData(UResponse::$code['InternalError'], "登录失败");
                }

            } else {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }

        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * @return array
     *
     * 获取用户信息
     *
     */
    public function actionGetUserMassage () {
        try {

            $user_id = Yii::$app->getParams->post("user_id");
            if (!$user_id) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户ID不能为空");
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoById", ['user_id' => $user_id]);

            if(!$userInfo){
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "该用户未注册");
            } else {
                if($userInfo['lang'] !== "0" && $userInfo['learn_time'] !== "0"){
                    //匹配出字符串
                    $result = UserInfor::$learnTime;
                    $res = UserInfor::$motherTongue;
                    $arr_time = $result[$userInfo['learn_time']-1]['name'];
                    $arr_lang = $res[$userInfo['lang']-1]['name'];

                    $outputdata = [
                        "user_id"          => $userInfo['user_id'],
                        "user_name"        => $userInfo['user_name'],
                        "user_passwd"      => $userInfo['user_passwd'],
                        "user_image"       => $userInfo['user_image'],
                        "country_id"       => $userInfo['country_id'],
                        "lang"             => $arr_lang,
                        "user_initlevel"   => $userInfo['user_initlevel'],
                        "user_curlevel"    => $userInfo['user_curlevel'],
                        "user_targetlevel" => $userInfo['user_targetlevel'],
                        "learn_time"       => $arr_time,
                        "user_age"         => $userInfo['user_age'],
                        "user_agent"       => $userInfo['user_agent'],
                        "user_nickname"    => $userInfo['user_nickname'],
                        "create_time"      => ""
                    ];
                    return UResponse::formatData("0", "登录成功", $outputdata);
                } else {
                    return UResponse::formatData("0", "登录成功", $userInfo);
                }
            }
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['SystemError'], "系统错误");
        }
    }

    /**
     * @return array
     *
     * 用户登录
     *
     */
    public function actionUserLogin (){
        try {

            $user_name = Yii::$app->getParams->post("user_name");
            $user_passwd = Yii::$app->getParams->post("user_passwd");
            if (!$user_name || !$user_passwd) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱或者密码不能为空");
            }
            $user_passwd = Encrypt::mymd5_4($user_passwd);

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoByName", ['user_name' => $user_name]);

            if (!$userInfo) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }

            //验证登录
            $module = Yii::$app->getModule("subscriber");
            $userInfo = $module->runAction("user/getUserInfor", ['user_name' => $user_name, 'user_passwd' => $user_passwd]);

            if(!$userInfo){
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "邮箱或密码错误");
            } else {
                if($userInfo['lang'] !== "0" && $userInfo['learn_time'] !== "0"){
                    //匹配出字符串
                    $result = UserInfor::$learnTime;
                    $res = UserInfor::$motherTongue;
                    $arr_time = $result[$userInfo['learn_time']-1]['name'];
                    $arr_lang = $res[$userInfo['lang']-1]['name'];

                    $outputdata = [
                        "user_id"          => $userInfo['user_id'],
                        "user_name"        => $userInfo['user_name'],
                        "user_passwd"      => $userInfo['user_passwd'],
                        "user_image"       => $userInfo['user_image'],
                        "country_id"       => $userInfo['country_id'],
                        "lang"             => $arr_lang,
                        "user_initlevel"   => $userInfo['user_initlevel'],
                        "user_curlevel"    => $userInfo['user_curlevel'],
                        "user_targetlevel" => $userInfo['user_targetlevel'],
                        "learn_time"       => $arr_time,
                        "user_age"         => $userInfo['user_age'],
                        "user_agent"       => $userInfo['user_agent'],
                        "user_nickname"    => $userInfo['user_nickname'],
                        "create_time"      => ""
                    ];
                    return UResponse::formatData("0", "登录成功", $outputdata);
                } else {
                    return UResponse::formatData("0", "登录成功", $userInfo);
                }
            }
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['SystemError'], "系统错误");
        }
    }

    /**
     *
     * @return array
     *
     * 忘记密码
     *
     */
    /*public function actionUpdateUserPasswd () {
        try {
            $user_name = Yii::$app->getParams->post('user_name');
            if (!$user_name) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱不能为空");
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoByName", ['user_name' => $user_name]);

            if ($userInfo) {
                $randNums = Yii::$app->getParams->post('randnum');
                if (!$randNums) {
                    return UResponse::formatData(UResponse::$code['AccessDeny'], "验证码不能为空");
                }
                $modules = Yii::$app->getModule("subscriber");
                $userInfos = $modules->runAction("user/seekpass", ['user_name' => $user_name, 'user_nickname' => $userInfo['user_nickname'], 'randnum' => $randNums]);
                if ($userInfos) {
                    $user_passwd = Yii::$app->getParams->post("user_passwd");
                    if (!$user_passwd) {
                        return UResponse::formatData(UResponse::$code['AccessDeny'], "密码不能为空");
                    }
                    $user_passwd = Encrypt::mymd5_4($user_passwd);
                } else {
                    return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "邮箱验证失败");
                }
            } else {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }

            $module = Yii::$app->getModule("subscriber");
            $usersInfo = $module->runAction("user/updatePasswd", ['user_id' => $userInfo['user_id'], 'info' => ['user_passwd' => $user_passwd]]);

            if ($usersInfo) {
                return UResponse::formatData("0", "重置密码成功");
            }

            return UResponse::formatData(UResponse::$code['UnknownError'], "重置密码失败");
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }*/

    /**
     *
     * @return array
     *
     * 修改密码
     *
     */
    public function actionUpdatePasswd () {

        try {
            $user_id = Yii::$app->getParams->post('user_id');
            $user_passwd = Yii::$app->getParams->post('user_passwd');
            $new_passwd = Yii::$app->getParams->post('new_passwd');
            //判断用户和密码不能为空
            if (!$user_id || !$user_passwd || !$new_passwd) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱或者密码不能为空");
            }
            $user_passwd = Encrypt::mymd5_4($user_passwd);
            $new_passwd = Encrypt::mymd5_4($new_passwd);

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoById", ['user_id' => $user_id]);
            if ($userInfo) {
                $module = Yii::$app->getModule("subscriber");
                $userPwd = $module->runAction("user/getUserPasswd", ['user_id' => $userInfo['user_id'], 'user_passwd' => $user_passwd]);

                if(!$userPwd){
                    return UResponse::formatData(UResponse::$code['PasswdError'], "原始密码不正确");
                } else {
                    $module = Yii::$app->getModule("subscriber");
                    $usersInfo = $module->runAction("user/updateUserInfo", ['user_id' => $userInfo['user_id'], 'info' => ['user_passwd' => $new_passwd]]);

                    if (!$usersInfo) {
                        return UResponse::formatData(UResponse::$code['InternalError'], "修改密码失败");

                    }
                }
            } else {

                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }
            return UResponse::formatData("0", "修改密码成功");
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * @return array
     *
     * 头像上传
     *
     */
    public function actionUploadImage () {
        try{

            $user_id = Yii::$app->getParams->post('user_id');
            if(!$user_id) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "用户ID不能为空");
            }

            $filetype = Yii::$app->getParams->post("filetype");

            if (!$filetype) {
                $filetype = '0';
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoById", ['user_id' => $user_id]);
            if (!$userInfo) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }

            $file = UploadedFile::getInstanceByName('fileName');

            if (!$file) {
                return UResponse::formatData(UResponse::$code['InvalidArgument'], "提交信息有误");
            }
            $fileName = $file->tempName;
            $fileExt = $file->getExtension();

            // 需要填写你的 Access Key 和 Secret Key
            $accessKey ="zpY92ZZHmsSYOBhZ0QtGMhS5iJPQQ7DXvpaxhCKG";
            $secretKey = "zVghaYhiBSQ-G0iBp4T1irWE2KOOsrgDIledBh9a";
            $bucket = "chinlab-image-head";
            $domain = "http://p2dih97r8.bkt.clouddn.com/";

            // 构建鉴权对象;
            $auth = new Auth($accessKey, $secretKey);

            $key = 'my-logo-'.substr($user_id,0,-3).substr(time(),0,-3).'.'.$fileExt;
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = $fileName;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err !== null) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "数据为空");
            }

            if ($ret) {
                //图片路径
                $ImagePath = $domain.$ret['key'];
                $module = Yii::$app->getModule("subscriber");
                $usersInfo = $module->runAction("user/updateUserInfo", ['user_id' => $userInfo['user_id'], 'info' => ['user_image' => $ImagePath]]);

                if($usersInfo){
                    $outputdata = [
                        "user_id"          => $usersInfo['user_id'],
                        "user_name"        => "",
                        "user_passwd"      => "",
                        "user_image"       => $usersInfo['user_image'],
                        "country_id"       => "",
                        "lang"             => "",
                        "interest"         => "",
                        "user_initlevel"   => "",
                        "user_curlevel"    => "",
                        "user_targetlevel" => "",
                        "learn_time"       => "",
                        "user_age"         => "",
                        "user_agent"       => "",
                        "user_nickname"    => "",
                        "create_time"      => ""
                    ];
                    return UResponse::formatData("0", "上传图片成功",$outputdata);
                }
            }
            return UResponse::formatData(UResponse::$code['InternalError'], "上传图片失败");
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }

    }

    /**
     * @return array
     *
     * 修改用户信息
     *
     */
    public function actionUpdateUserInfo () {

        try {
            $user_id = Yii::$app->getParams->post('user_id');
            $learn_time = Yii::$app->getParams->post('learn_time');
            $user_age = Yii::$app->getParams->post('user_age');
            $user_agent = Yii::$app->getParams->post('user_agent');
            $user_nickname = Yii::$app->getParams->post('user_nickname');
            //判断用户不能为空
            if (!$user_id) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱不能为空");
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoById", ['user_id' => $user_id]);

            $userData = [
                "learn_time"       => $learn_time,
                "user_age"         => $user_age,
                "user_agent"       => $user_agent,
                "user_nickname"    => $user_nickname
            ];

            foreach ( $userData as $k => $v) {
                if( !$v )
                    unset( $userData[$k] );
            }

            if ($userInfo) {

                $module = Yii::$app->getModule("subscriber");
                $usersInfo = $module->runAction("user/updateUserInfo", ['user_id' => $userInfo['user_id'], 'info' => $userData]);

                if ($usersInfo) {
                    $outputdata = [
                        "user_id"          => $usersInfo['user_id'],
                        "user_name"        => "",
                        "user_passwd"      => "",
                        "user_image"       => "",
                        "country_id"       => "",
                        "lang"             => "",
                        "interest"         => "",
                        "user_initlevel"   => "",
                        "user_curlevel"    => "",
                        "user_targetlevel" => "",
                        "learn_time"       => $usersInfo['learn_time'],
                        "user_age"         => $usersInfo['user_age'],
                        "user_agent"       => $usersInfo['user_agent'],
                        "user_nickname"    => $usersInfo['user_nickname'],
                        "create_time"      => ""
                    ];
                    return UResponse::formatData("0", "修改信息成功",$outputdata);
                } else {

                    return UResponse::formatData(UResponse::$code['InternalError'], "修改信息失败");
                }

            } else {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }

        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return [];
        }
    }

    /**
     * @return array
     *
     * 用户留言
     *
     */
    public function actionUserEmailMessage () {

        try{
            require dirname(__DIR__) . "/../../common/components/PhpMailer.php";
            $user_name = Yii::$app->getParams->post('user_name');
            $user_contents = Yii::$app->getParams->post('user_contents');

            if (!$user_name || !$user_contents) {
                return UResponse::formatData(UResponse::$code['AccessDeny'], "邮箱或内容不能为空");
            }

            //是否有用户
            $modules = Yii::$app->getModule("subscriber");
            $userInfo = $modules->runAction("user/getInfoByName", ['user_name' => $user_name]);

            if (!$userInfo) {
                return UResponse::formatData(UResponse::$code['InvalidSessionKey'], "当前用户未注册");
            }

            $mail = new \PHPMailer(true); //建立邮件发送类
            $mail->CharSet = "UTF-8";//设置信息的编码类型
            $mail->IsSMTP(); // 使用SMTP方式发送
            $mail->Host = "smtp.163.com"; //使用163邮箱服务器
            $mail->SMTPAuth = true; // 启用SMTP验证功能
            $mail->Username = "chinlab2018@163.com"; //你的163服务器邮箱账号
            $mail->Password = "chinlab2018"; // 163邮箱密码
            $mail->Port = 25;//邮箱服务器端口号
            $mail->From = "chinlab2018@163.com"; //邮件发送者email地址
            $mail->FromName = "ChinLab";//发件人名称
            $mail->AddAddress("yao_han613@163.com","ChinLab"); //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
//  $mail->AddAttachment("D:\abc.txt"); // 添加附件(注意：路径不能有中文)
            $mail->IsHTML(true);//是否使用HTML格式
            $mail->Subject = $user_name."发来的留言"; //邮件标题
            $mail->Body = "$user_contents"; //邮件内容，上面设置HTML，则可以是HTML

            if(!$mail->send()) {
                return UResponse::formatData(UResponse::$code['SendEmailerEeeor'], "留言失败");
            }
            return UResponse::formatData("0", "您的留言是给我们最大的帮助，谢谢！！！");
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            return UResponse::formatData(UResponse::$code['SystemError'], "系统错误");
        }

    }
    /**
     * @return array
     *
     * 获取国家列表
     *
     */
    public function actionUserCountry(){
        try {

            $result = UserInfor::$country;

            if(!is_array($result)){
                return UResponse::formatData(UResponse::$code['ListGainError'], "获取国家列表失败");
            } else {
                return UResponse::formatData("0", "获取国家列表成功", $result);
            }
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }


    /**
     * @return array
     *
     * 获取母语列表
     *
     */
    public function actionUserMotherTongue(){

        try {
            $result = UserInfor::$motherTongue;

            if(!is_array($result)){
                return UResponse::formatData(UResponse::$code['ListGainError'], "获取母语列表失败");
            } else {
                return UResponse::formatData("0", "获取母语列表成功", $result);
            }
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    /**
     * @return array
     *
     * 获取兴趣列表
     *
     */
    public function actionUserObjective(){
        try {

            $result = UserInfor::$objective;

            if(!is_array($result)){
                return UResponse::formatData(UResponse::$code['ListGainError'], "获取兴趣列表失败");
            } else {
                return UResponse::formatData("0", "获取兴趣列表成功", $result);
            }
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }

    /**
     * @return array
     *
     * 获取学习时间列表
     *
     */
    public function actionUserLearnTime(){
        try {
            $result = UserInfor::$learnTime;

            if(!is_array($result)){
                return UResponse::formatData(UResponse::$code['ListGainError'], "获取学习时间列表失败");
            } else {
                return UResponse::formatData("0", "获取学习时间列表成功", $result);
            }
        } catch (Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);

            return [];
        }
    }
}
