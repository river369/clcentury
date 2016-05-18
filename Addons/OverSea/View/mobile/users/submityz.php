<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$existedUser = $_SESSION['signedUserInfo'] ;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed">
        <h1>发个易知</h1>
    </div>

    <div data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/SubmitYZ.php">
            <label for="name">您的姓名:</label>
            <input type="text" name="name" id="name" value="<?php echo isset($existedUser['name']) ? $existedUser['name']: ''; ?> ">
            <label for="weixin">您的微信号:</label>
            <input type="text" name="weixin" id="weixin" value="<?php echo isset($existedUser['weixin']) ? $existedUser['weixin']: ''; ?> ">

            <fieldset data-role="controlgroup">
                <legend>你的服务类型:</legend>
                <input name="service-1" id="service-1" checked="" type="checkbox">
                <label for="service-1">旅游</label>
                <input name="service-2" id="service-2" type="checkbox">
                <label for="service-2">留学</label>
            </fieldset>

            <label for="servicearea">您的服务地点:</label>
            <select name="servicearea" id="servicearea">
                <optgroup label="美国">
                    <option value="西雅图">西雅图</option>
                    <option value="旧金山">旧金山</option>
                    <option value="纽约">纽约</option>
                    <option value="洛杉矶">洛杉矶</option>
                </optgroup>
                <optgroup label="中国">
                    <option value="北京市">北京市</option>
                    <option value="上海市">上海市</option>
                    <option value="天津市">天津市</option>
                    <option value="重庆市">重庆市</option>
                </optgroup>
            </select>

            <label for="serviceprice">您的服务价格(￥/小时):</label>
            <input type="text" name="serviceprice" id="serviceprice" value="<?php echo isset($existedUser['serviceprice']) ? $existedUser['serviceprice']: ''; ?>" >

            <label for="description">自我介绍:</label>
            <textarea cols="30" rows="8" name="description" id="description" data-mini="true">
                <?php echo isset($existedUser['description']) ? $existedUser['description']: ''; ?>
            </textarea>
            <input type="submit" name="yzsubmit" id="yzsubmit" value="发布">
        </form>
    </div>

    <div data-role="footer" data-position="fixed">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
</body>
</html>

