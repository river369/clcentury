<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$signInErrorMsg=$_SESSION['$signInErrorMsg'];
$isMine = 1;
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
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>
    <script src="../../resource/js/validation/localization/messages_zh.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>修改密码</h1>
    </div>
    <?php if($signInErrorMsg != null){ ?>
        <div class="errmsgstring2" style="color:red" data-role="content">
            <?php echo $signInErrorMsg; ?>
        </div>
    <?php } ?>
    <div data-role="content">
        <form id="passform" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=changePassword">
            <label for="password" style="font-size:12px; color:#33c8ce">请输入<?php echo isset($_SESSION['tempCode'])? "临时登陆":"原" ?>密码:</label>
            <input type="password" name="orig" id="orig">
            </br>
            <label for="new1"  style="font-size:12px; color:#33c8ce">请输入新密码:</label>
            <input type="password" name="new1" id="new1">
            </br>
            <label for="new2"  style="font-size:12px; color:#33c8ce">请再次输入新密码:</label>
            <input type="password" name="new2" id="new2">
            </br>
            <input type="submit" name="signinsubmit" id="signinsubmit" data-theme="c" value="更改密码">
        </form>
    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#passform" ).validate({
            rules: {
                orig: {
                    required: true,
                    minlength:6
                },
                new1: {
                    required: true,
                    minlength:6
                },
                new2: {
                    required: true,
                    equalTo: "#new1"
                }
            },
            messages: {
                orig: {
                    required: "原密码不能为空",
                    minlength: "密码长度不能小于 6 个字母"
                },
                new1: {
                    required: "新密码不能为空",
                    minlength: "密码长度不能小于 6 个字母"
                },
                new2: {
                    required: "新密码不能为空",
                    minlength: "密码长度不能小于 6 个字母",
                    equalTo: "两次输入密码不一致"
                }
            },
            errorPlacement: function( error, element ) {
                error.insertAfter( element.parent() );
            }
        });
    });
</script>
</body>
</html>

