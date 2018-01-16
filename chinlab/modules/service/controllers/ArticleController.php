<?php

namespace app\modules\service\controllers;

use app\common\application\InfoConfig;
use app\common\application\RabbitConfig;
use app\common\components\AppRedisKeyMap;
use app\common\data\Response as UResponse;
use yii\web\Response;
use Yii;

class ArticleController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            [
                'class' => 'app\common\filters\ArticleDetailFilter',
                'only' => ['detail'],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取频道
     * @return array
     * @throws \yii\base\InvalidRouteException
     */
    public function actionChannel()
    {

        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getChannel();
        $result = json_decode(strval($redis->get($redisKey)), true);
        if (!is_array($result)) {
            $result = Yii::$app->getModule('article')->runAction('channel/getlistFront');
            array_unshift($result, ["channel_no" => InfoConfig::CHANNEL_HOT_NO, "channel_name" => InfoConfig::CHANNEL_HOT_NAME]);
            $redis->set($redisKey, json_encode($result));
            $redis->expire($redisKey, 3600);
        }
        return UResponse::formatData("0", "文件上传成功", $result);
    }

    /**
     * 获取banner
     */
    public function actionBanner()
    {

        $channel = intval(Yii::$app->getParams->get("channel_no"));
        $newsType = InfoConfig::NEWS_TYPE_AD;
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getNewsInfoList($channel . $newsType);
        $redisInfoKey = AppRedisKeyMap::getNewsInfoDetail($channel . $newsType);
        $limit = isset(InfoConfig::$pageBannerLimit[$channel]) ? InfoConfig::$pageBannerLimit[$channel] : InfoConfig::$pageBannerLimit['other'];
        $tmpInfo = $redis->executeCommand('ZREVRANGEBYSCORE', [$redisKey, 9999999999999, 0, 'withscores', 'LIMIT', 0, $limit]);
        if (!$tmpInfo) {
            Yii::$app->queue->send([
                'info' => ['channel_no' => $channel, "show_type" => $newsType],
            ], RabbitConfig::NEWS_MORE_CACHE);
        }

        $listInfo = [];
        foreach ($tmpInfo as $k => $v) {
            if (!($k % 2)) {
                $listInfo[] = [$v, $tmpInfo[$k + 1]];
            }
        }
        $result = [];
        if ($listInfo) {
            $recordIds = [$redisInfoKey];
            foreach ($listInfo as $aInfo) {
                $recordIds[] = $aInfo[0];
            }
            $res = $redis->executeCommand("hmget", $recordIds);
            foreach ($res as $k => $v) {
                $res[$k] = json_decode(strval($v), true);
                if (!is_array($res[$k])) {
                    unset($res[$k]);
                    continue;
                } else {
                    $res[$k]['news_photo'] = json_decode($res[$k]['news_photo'], true);
                    $result[$k]['news_photo'] = $res[$k]['news_photo']['banner_image'][0];
                    $result[$k]['multi_news_photo'] = $res[$k]['news_photo']['banner_image'];
                    $result[$k]['material_id'] =  $result[$k]['material_id'] = $res[$k]['channel_no'] . '|' . $res[$k]['news_type'] . '|' . $res[$k]['material_id'];
                    $result[$k]['news_id'] = $res[$k]['material_id'];
                    $result[$k]['news_url'] = $res[$k]['news_url'];
                    $result[$k]['desc'] = $res[$k]['author'] ? : "伙伴医生";
                    $result[$k]['news_title'] = $res[$k]['title'];
                    $result[$k]['publish_time'] = $res[$k]['publish_time'] ? strval($res[$k]['publish_time']) : strval(time() - rand(1, 9999999));
                    $result[$k]['channel_name'] = $res[$k]['channel_name'];
                    $result[$k]['news_type'] = $res[$k]['news_type'];
                    $result[$k]['width'] = '100';
                    $result[$k]['height'] = '100';
                    $result[$k] = UResponse::messageToString($result[$k]);
                }
            }
        }
        $result = array_values($result);
        //开机动画需要宽和高
        if ($result && $channel == InfoConfig::CHANNEL_ON_NO) {
            $str = parse_url($result[0]['news_photo']);
            $str = trim($str['path'], '/');
            $group = substr($str, 0, strpos($str, '/'));
            $path = substr($str, strpos($str, '/') + 1);
            $result[0]['width'] = '100';
            $result[0]['height'] = '100';
            if (!is_file('/tmp/' . $path)) {
                $dirPath = pathinfo($path);
                if (!is_dir('/tmp/' . $dirPath['dirname'])) {
                    mkdir('/tmp/' . $dirPath['dirname'], 0777, true);
                }
                $res = Yii::$app->fdfs->download_to_file($group, $path, "/tmp/" . $path);
                if ($res) {
                    $imgInfo = getimagesize('/tmp/' . $path);
                    if ($imgInfo) {
                        $result[0]['width'] = strval($imgInfo[0]);
                        $result[0]['height'] = strval($imgInfo[1]);
                    }
                }
            } else {
                $imgInfo = getimagesize('/tmp/' . $path);
                if ($imgInfo) {
                    $result[0]['width'] = strval($imgInfo[0]);
                    $result[0]['height'] = strval($imgInfo[1]);
                }
            }
        }
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 获取list
     */
    public function actionList()
    {
        $channel = intval(Yii::$app->getParams->get("channel_no"));
        $nodeNo = Yii::$app->getParams->get("node_no");

        if (intval($nodeNo) <= 0) {
            $nodeNo = 9999999999999;
        }

        $limit = 9;
        if ($channel == InfoConfig::CHANNEL_HOME_NO) {
            $channel = InfoConfig::CHANNEL_HOT_NO;
        }
        $newsType = InfoConfig::NEWS_TYPE_INFO;
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getNewsInfoList($channel . $newsType);
        $redisInfoKey = AppRedisKeyMap::getNewsInfoDetail($channel . $newsType);
        $tmpInfo = $redis->executeCommand('ZREVRANGEBYSCORE', [$redisKey, $nodeNo, 0, 'withscores', 'LIMIT', 0, $limit]);
        if (!$tmpInfo) {
            Yii::$app->queue->send([
                'info' => ['channel_no' => $channel, "show_type" => $newsType],
            ], RabbitConfig::NEWS_MORE_CACHE);
        }

        $listInfo = [];
        foreach ($tmpInfo as $k => $v) {
            if (!($k % 2)) {
                $listInfo[] = [$v, $tmpInfo[$k + 1]];
            }
        }
        $result = [];
        $nextPage = 0;
        if ($listInfo) {
            $recordIds = [$redisInfoKey];
            foreach ($listInfo as $aInfo) {
                $recordIds[] = $aInfo[0];
            }
            $res = $redis->executeCommand("hmget", $recordIds);
            foreach ($res as $k => $v) {
                $res[$k] = json_decode(strval($v), true);
                if (!is_array($res[$k])) {
                    unset($res[$k]);
                    continue;
                } else {
                    $res[$k]['news_photo'] = json_decode($res[$k]['news_photo'], true);
                    $result[$k]['news_photo'] = $res[$k]['news_photo']['list_image'][0];
                    $result[$k]['multi_news_photo'] = $res[$k]['news_photo']['list_image'];
                    $result[$k]['material_id'] = $res[$k]['channel_no'] . '|' . $res[$k]['news_type'] . '|' . $res[$k]['material_id'];
                    $result[$k]['news_id'] = $res[$k]['material_id'];
                    $result[$k]['news_url'] = $res[$k]['news_url'];
                    $result[$k]['desc'] = $res[$k]['author'] ? : "伙伴医生";
                    $result[$k]['news_title'] = $res[$k]['title'];
                    $result[$k]['publish_time'] = $res[$k]['publish_time'] ? strval($res[$k]['publish_time']) : strval(time() - rand(1, 9999999));
                    $result[$k]['channel_name'] = $res[$k]['channel_name'];
                    $result[$k]['news_type'] = $res[$k]['news_type'];
                    $result[$k]['width'] = '100';
                    $result[$k]['height'] = '100';
                    $result[$k] = UResponse::messageToString($result[$k]);
                    $nextPage = $res[$k]['sort_no'];
                }
            }
        }
        $result = ["node_no" => strval($nextPage - 1), "list" => $result];
        return UResponse::formatData("0", "获取列表成功", $result);
    }

    /**
     * 获取详情内容
     */
    public function actionDetail()
    {

        $materialId = Yii::$app->getParams->get("material_id");
        list($channel, $newsType, $materialId) = explode("|", $materialId);

        $redis = Yii::$app->redis;
        $redisInfoKey = AppRedisKeyMap::getNewsInfoDetail($channel . $newsType);
        $info = json_decode(strval($redis->hget($redisInfoKey, $materialId)), true);
        if (!is_array($info)) {
            $info = Yii::$app->getModule('article')->runAction('infonews/getInfoById', ['id' => $materialId]);
        }

        $userInfo = Yii::$app->runData->get("userInfo");
        $isCollection = 0;
        if ($userInfo) {
            $userCollectionInfo = Yii::$app->getModule('patient')->runAction('usercollection/getIsCollection', ['userId' => $userInfo['user_id'], 'articleId' => $materialId]);
            if ($userCollectionInfo) {
                $isCollection = 1;
            }
        }
        $tmpImage = json_decode($info['news_photo'], true);
        $detail = [
            "news_title"         => $info['title'],
            "news_id"    => $materialId,
            "news_url"      => $info['news_url'],
            "news_photo"    => $tmpImage['list_image'][0],
            'multi_news_photo' => $tmpImage['list_image'],
            'desc' => $info['author'] ? : "伙伴医生",
            "is_collection" => strval($isCollection),
            'material_id' => $info['channel_no'] . '|' . $info['news_type'] . '|' . $info['material_id'],
            'channel_name' => $info['channel_name'],
            'news_type' => $info['news_type'],
            'width' => '100',
            'height' => '100',
        ];
        $detail = UResponse::messageToString($detail);

        //获取相关资讯
        if ($newsType == InfoConfig::NEWS_TYPE_AD) {
            $newsType = InfoConfig::NEWS_TYPE_INFO;
        }
        if ($channel == InfoConfig::CHANNEL_HOME_NO || $channel == InfoConfig::CHANNEL_ON_NO) {
            $channel = InfoConfig::CHANNEL_HOT_NO;
        }
        $redisKey = AppRedisKeyMap::getNewsInfoList($channel . $newsType);
        $redisInfoKey = AppRedisKeyMap::getNewsInfoDetail($channel . $newsType);
        $nextPage = $info['sort_no'] - 1;
        $tmpInfo = $redis->executeCommand('ZREVRANGEBYSCORE', [$redisKey, $nextPage, 0, 'withscores', 'LIMIT', 0, 3]);
        if (!$tmpInfo) {
            $tmpInfo = $redis->executeCommand('ZREVRANGEBYSCORE', [$redisKey, 9999999999999, 0, 'withscores', 'LIMIT', 0, 3]);
        }
        $listInfo = [];
        foreach ($tmpInfo as $k => $v) {
            if (!($k % 2)) {
                $listInfo[] = [$v, $tmpInfo[$k + 1]];
            }
        }
        $result = [];
        if ($listInfo) {
            $recordIds = [$redisInfoKey];
            foreach ($listInfo as $aInfo) {
                $recordIds[] = $aInfo[0];
            }
            $res = $redis->executeCommand("hmget", $recordIds);
            foreach ($res as $k => $v) {
                $res[$k] = json_decode(strval($v), true);
                if (!is_array($res[$k])) {
                    unset($res[$k]);
                    continue;
                } else {
                    $res[$k]['news_photo'] = json_decode($res[$k]['news_photo'], true);
                    $result[$k]['news_photo'] = $res[$k]['news_photo']['list_image'][0];
                    $result[$k]['multi_news_photo'] = [$res[$k]['news_photo']['list_image'][0]];
                    $result[$k]['material_id'] = $res[$k]['channel_no'] . '|' . $res[$k]['news_type'] . '|' . $res[$k]['material_id'];
                    $result[$k]['news_title'] = $res[$k]['title'];
                    $result[$k]['publish_time'] = $res[$k]['publish_time'] ? strval($res[$k]['publish_time']) : strval(time() - rand(1, 9999999));
                    $result[$k]['channel_name'] = $res[$k]['channel_name'];
                    $result[$k]['news_id'] = $res[$k]['material_id'];
                    $result[$k]['news_url'] = $res[$k]['news_url'];
                    $result[$k]['desc'] = $res[$k]['author'] ? : "伙伴医生";
                    $result[$k]['news_type'] = $res[$k]['news_type'];
                    $result[$k]['width'] = '100';
                    $result[$k]['height'] = '100';
                    $result[$k] = UResponse::messageToString($result[$k]);
                }
            }
        }
        return UResponse::formatData("0", "获取列表成功", ['detail' => $detail, 'relate' => $result]);
    }

    /**
     * 点赞文章
     * @return array
     */
    public function actionGoodnews() {

        $newsId = Yii::$app->getParams->get("news_id");
        $redis = Yii::$app->redis;
        $redisKey = AppRedisKeyMap::getGoodsNews();
        $redis->executeCommand("ZINCRBY", [$redisKey, 1, $newsId]);
        $redis->expire($redisKey, 86400 * 15);
        return UResponse::formatData("0", "点赞成功", (object)[]);
    }

    /**
     * article_pagecontent_id_time.html
     * 获取文章详情
     */
    public function actionPagecontent() {

        Yii::$app->response->format = Response::FORMAT_HTML;
        $newsId = Yii::$app->getParams->get("news_id");
        $flag = Yii::$app->getParams->get("flag");
        if (!$newsId) {
            echo "没找到啊";
            exit;
        }
        $redisKey = AppRedisKeyMap::getPageContent($newsId.'.'.$flag);
        $redis = Yii::$app->redis;
        $content = $redis->get($redisKey);
        if (!$content) {
            $info = Yii::$app->getModule('article')->runAction('detail/getById', ['id' => $newsId]);
            $otherInfo = Yii::$app->getModule('article')->runAction('material/getById', ['id' => $newsId]);
            $title = $otherInfo['title'];
            if ($info) {
                $prefix = <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>$title</title>
    <link rel="stylesheet" type="text/css" href="/editor/resource/css/wangEditor.min.css">
    <style>
        img {
            width:100%;
        }
        p {
            letter-spacing: 1px;
        }
        body {
    		padding: 0 12px;
    	}
    </style>
</head>
<body>
<div id="div1" class="wangEditor-txt" contenteditable="false">
EOF;
                $end = <<<EOF
</div>
</body>
</html>
EOF;
                $content = $prefix . $info['news_content'] . $end;
                $redis->set($redisKey, $content);
                $redis->expire($redisKey, 300);
            }
        }
        if (!$content) {
            echo "没找到啊";
            exit;
        }
        echo $content;
        exit;
    }
}
