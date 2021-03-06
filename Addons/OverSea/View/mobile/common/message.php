<?php
session_start();
$status = $_SESSION['status'];
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
$message = $_SESSION['message'];
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
$link_name =  $_SESSION['link_name'];
if (isset($_GET['link_name'])) {
    $goto = $_GET['link_name'];
}

$goto = $_SESSION['goto'];
if (isset($_GET['goto'])) {
    $goto = $_GET['goto'];
}

if (isset($_GET['goto_type'])){
    if ($_GET['goto_type'] == 'service_list'){
        $goto = '../../../Controller/FreelookDispatcher.php?c=getServices';
    } else if ($_GET['goto_type'] == 'order_list') {
        $goto = '../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&status=0,10' ;
    } else if ($_GET['goto_type'] == 'order_detail') {
        $goto = '../../../Controller/AuthUserDispatcher.php?c=queryOrderDetails&order_id='.$_GET['order_id'];
    }
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/cropper.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/main.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style>
        h5{ color:#33c8ce}
        h4{ color:#33c8ce}
    </style>
</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>提示</h1>
    </div>
    <br><br>
    
    <div data-role="content">
        <div style="text-align:center">
            <img src="../../resource/images/<?php echo ($status == 's') ? "success.jpg" : "fail.png";  ?>" width="25%" />
        </div>
    </div>

    <br>
    <div data-role="content" style="text-align:center">
        <h4><?php echo $message; ?></h4>
        <a href="<?php echo $goto; ?>" rel="external" data-theme="d"  data-role="button"><?php echo isset($link_name)? $link_name : '返回'; ?></a>
    </div>

    <?php include '../common/footer.php';?>
</div>
</body>
</html>
