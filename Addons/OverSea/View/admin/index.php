<?php
session_start();
$customerid = $_SESSION['signedUser'];
$isMine = 1;
$existedUser = $_SESSION['signedUserInfo'] ;
$nocheck = isset($GET['nocheck']);
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
        <?php if ($existedUser['user_type'] == 1 || $nocheck ) {?>
            <h5>Admin</h5>
            <div style="margin: -20px 0px -20px 0px">
                <ul data-role="listview" data-inset="true" data-theme="e">
                    <li>
                        <a href="../../Controller/AuthUserDispatcher.php?c=getUsers&status=20" rel="external">
                            <p style="margin: 0px 0px 0px 0px">审核实名认证</p>
                        </a>
                    </li>
                    <li>
                        <a href="../../Controller/AuthUserDispatcher.php?c=getServices&status=20" rel="external">
                            <p style="margin: 0px 0px 0px 0px">审核新发布的服务</p>
                        </a>
                    </li>
                    <li>
                        <a href="../../Controller/AuthUserDispatcher.php?c=prepareAdvertise" rel="external">
                            <p style="margin: 0px 0px 0px 0px">发布广告</p>
                        </a>
                    </li>
                    <li>
                        <a href="../../Controller/AuthUserDispatcher.php?c=getAdvertiseList" rel="external">
                            <p style="margin: 0px 0px 0px 0px">广告列表</p>
                        </a>
                    </li>
                    <li>
                        <a href="../mobile/users/signin.php" rel="external"><p style="margin: 0px 0px 0px 0px">登录</p></a>
                    </li>
                    <li>
                         <a href="../../Controller/ClearSessions.php" rel="external"><p style="margin: 0px 0px 0px 0px">退出登录</p></a>
                     </li>
                </ul>
            </div>
        <?php } ?>
    </div>

    <?php include './footer.php';?>
</div>
</body>
</html>
