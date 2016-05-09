<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
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
        <?php $status= $_SESSION['submityzstatus'];
        echo $status;
               $userData= $_SESSION['userdata'];
        ?>
        <p><?php echo $userData['name']; ?> 发送易知 <?php echo $status; ?> ,谢谢!</p>
    </div>

    <div data-role="footer" data-position="fixed">
        <h4>Copyright (c) 2016 .</h4>
    </div>

</div>
</body>
</html>
