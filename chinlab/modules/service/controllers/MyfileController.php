<?php

namespace app\modules\service\controllers;

use Yii;
use yii\web\UploadedFile;
use app\common\data\Response as UResponse;

class MyfileController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;
    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\PatientFilter',
                'only' => ['uploadfile'],
            ],
            [
                'class' => 'app\common\filters\DoctorFilter',
                'only' => ['uploaddoctorfile'],
            ],
        ];
    }

    public function actionUploadfile()
    {
        $userInfo = Yii::$app->runData->get("userInfo");
        $filetype = Yii::$app->getParams->get("filetype");
        if (!$filetype) {
            $filetype = '0';
        }
        $file = UploadedFile::getInstanceByName('fileName');
        if (!$file) {
            return UResponse::formatData(UResponse::$code['InvalidArgument'], "提交信息有误");
        }
        $fileName = $file->tempName;
        $fileExt = $file->getExtension();
        $info = Yii::$app->fdfs->upload($fileName, $fileExt);
        if (!$info) {
            return UResponse::formatData(UResponse::$code['InvalidArgument'], "提交信息有误");
        }
        $condition = true;
        if ($filetype) {
            $result = Yii::$app->getModule('patient')->runAction('userssdb/updateuserinfo',
                ['id' => $userInfo['user_id'], 'info' => ['user_img' => $info['url']]]);
            if (!$result) {
                $condition = false;
            }
        }
        if ($condition) {
            $data = ["fileurl" => $info['url'], "fileid" => $info['path']];
            return UResponse::formatData("0", "文件上传成功", $data);
        }
        return UResponse::formatData(UResponse::$code['InvalidArgument'], "文件类型暂不支持");
    }

    public function actionUploaddoctorfile()
    {
        $userInfo = Yii::$app->runData->get("doctorUserInfo");
        $filetype = Yii::$app->getParams->get("filetype");
        if (!$filetype) {
            $filetype = '0';
        }
        $file = UploadedFile::getInstanceByName('fileName');
        if (!$file) {
            return UResponse::formatData(UResponse::$code['InvalidArgument'], "提交信息有误");
        }
        $fileName = $file->tempName;
        $fileExt = $file->getExtension();
        $info = Yii::$app->fdfs->upload($fileName, $fileExt);
        if (!$info) {
            return UResponse::formatData(UResponse::$code['InvalidArgument'], "提交信息有误");
        }
        $condition = true;
        if ($filetype) {
            $result = Yii::$app->getModule('doctor')->runAction('user/updateuserinfo',
                ['id' => $userInfo['user_id'], 'info' => ['user_img' => $info['url']]]);
            if (!$result) {
                $condition = false;
            }
        }
        if ($condition) {
            $data = ["fileurl" => $info['url'], "fileid" => $info['path']];
            return UResponse::formatData("0", "文件上传成功", $data);
        }
        return UResponse::formatData(UResponse::$code['InvalidArgument'], "文件类型暂不支持");
    }
}
