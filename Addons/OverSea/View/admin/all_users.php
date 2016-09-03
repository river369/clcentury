<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$allUsers= $_SESSION['allUsers'];
$isMine = 1;
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
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>审核实名认证</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <?php
        foreach($allUsers as $key => $user)
        {
            $userid= $user['user_id'];
            $status = $user['status'];
        ?>
        <ul data-role="listview" data-inset="true" data-theme="f">
            <li data-role="list-divider">用户编号: <span class="ui-li-count"><?php echo $userid;?></span></li>
            <li>
                <a href="../../Controller/AuthUserDispatcher.php?c=publishRealName&userid=<?php echo $userid; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $userid;?>/head.png" alt="">
                    <h2> <?php echo $user['real_name'];?> </h2>
                    <p style="white-space:pre-wrap;"><?php echo $user['certificate_no'];?> </p>
                </a>
            </li>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a"><a href="#checkDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="checkPopup('<?php echo $userid; ?>', 0)">批准认证</a></div>
                    <div class="ui-block-b"><a href="#checkDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="checkPopup('<?php echo $userid; ?>', 1)">拒绝认证</a></div>
                </div>
            </li>
        </ul>
        <?php } ?>

        <div data-role="popup" id="checkDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>检查认证</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="checkString">.</h3>
                <form id="checkService" data-ajax="false" method="post" action="../../Controller/AuthUserDispatcher.php?c=checkUser&status=20">
                    <input type="hidden" name="userId" id="userId" value="">
                    <input type="hidden" name="checkaction" id="checkaction" value="">
                    <label for="reason">原因:</label>
                    <textarea cols="30" rows="8" name="checkreason" id="checkreason" data-mini="true"></textarea>
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="确定">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>

    </div>

    <?php include './footer.php';?>
</div>
<script>
    function checkPopup(userId, type) {
        var messages = "确定";
        if (type == 0){
            messages = messages + "批准";
        } else {
            messages = messages + "拒绝";
        }
        messages = messages + "认证" + userId + "?";
        //alert(messages);
        $('#checkString').html(messages);
        $('#userId').val(userId);
        $('#checkaction').val(type);
        $('#checkDialog').popup('open');
    };
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

