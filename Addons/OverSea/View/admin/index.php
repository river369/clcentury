<?php
session_start();
$customerid = $_SESSION['signedUser'];
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../resource/style/themes/my-theme.min.css" />

</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>管理员</h1>
    </div>

    <div class="container" data-role="content">
        <h3>服务信息:</h3>
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="../mobile/users/signin.php" rel="external">
                    登录
                </a>
            </li>
            <li>
                <a href="../../Controller/AuthUserDispatcher.php?c=getUsers&status=20" rel="external">
                    审核实名认证
                </a>
            </li>
            <li>
                <a href="../../Controller/AuthUserDispatcher.php?c=getServices&status=20" rel="external">
                    审核新发布的服务
                </a>
            </li>
            <li>
                <a href="../../Controller/ClearSessions.php" rel="external">
                    退出登录
                </a>
            </li>
        </ul>
        
    </div>

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
</body>
</html>
