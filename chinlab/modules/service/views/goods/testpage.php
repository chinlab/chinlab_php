<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>Title</title>
    <link rel="stylesheet" type="text/css" href="editor/resource/css/wangEditor.min.css">
</head>
<body style="padding: 10px">
<div id="div1" class="wangEditor-txt" contenteditable="false">
    <?php foreach($content as $k => $v): ?>
        <?php if (substr($v, 0, 1) != " ") { ?>
            <p style="margin:10px 0;text-align: left; color: #36b5f2;font-family: 微软雅黑; font-size: 18px;"><?= $v ?></p>
            <p style="margin:10px 0;border-bottom: 1px solid #dddddd;"></p>
        <?php } else { ?>
            <p style="margin:6px 0;text-align: left; font-family: 微软雅黑; font-size: 16px"><?= $v ?></p>
        <?php } ?>
        <?php if (isset($content[$k + 1]) && substr($content[$k+1], 0, 1) != " ") {?>
            <p style="margin:10px 0;border-bottom: 1px solid #dddddd;"></p>
        <?php } ?>
    <?php endforeach ?>
<div>
</body>
</html>