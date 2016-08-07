<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$existedUserPhoneReigon=$_SESSION['existedUserPhoneReigon'];
$existedUserPhoneNumber=$_SESSION['existedUserPhoneNumber'];
$signInErrorMsg=$_SESSION['$signInErrorMsg'];
unset($_SESSION['$signInErrorMsg'] );

//$callbackurl = $_GET ['callbackurl'];

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
        <h1>注册</h1>
    </div>
    <?php if($signInErrorMsg != null){ ?>
        <div class="errmsgstring2" style="color:red" data-role="content">
            <?php echo $signInErrorMsg; ?>
        </div>
    <?php } ?>
    <div data-role="content">
        <form id="signupform" data-ajax="false" method="post" action="../../../Controller/SignUp.php">
            <label for="phone_reigon" style="font-size:12px; color:#33c8ce">请选择地区号:</label>
            <select name="phone_reigon" id="phone_reigon">
                <option value="+86" <?php echo $existedUserPhoneReigon=='+86'? 'selected = "selected"' : ''; ?> >中国 +86</option>
                <option value="+1" <?php echo $existedUserPhoneReigon=='+1'? 'selected = "selected"' : ''; ?>>美国 +1</option>
            </select>
            </br>
            <label for="phone_number" style="font-size:12px; color:#33c8ce">请输入手机号码:</label>
            <input type="number" name="phone_number" id="phone_number" value="<?php echo $existedUserPhoneNumber; ?>">
            </br>
            <label for="password" style="font-size:12px; color:#33c8ce">请输入密码:</label>
            <input type="password" name="password" id="password" >
            </br>
            <label for="new2" style="font-size:12px; color:#33c8ce">请再次输入密码:</label>
            <input type="password" name="new2" id="new2">
            </br>
            <label for="verifycode" style="font-size:12px; color:#33c8ce">请输入验证码:</label>
            <input type="number" name="verifycode" id="verifycode">
            <button type="button" name="getvcode" id="getvcode" onclick="getSMS(this);" data-theme="c"> 免费获取验证码</button>
            <div class="errmsgstring" style="color:red"></div>
            </br>
            <input type="submit" name="signinsubmit" id="signinsubmit" value="注册" data-theme="c">
        </form>
    </div>

    <?php include '../common/footer.php';?>
</div>

<script>
    function getSMS(button) {
        $.ajax({
            url:'../../../Controller/FreelookDispatcher.php?c=sendRegistrationPassword',
            type:'POST',
            data : $('#signupform').serialize(),
            dataType:'json',
            async:false,
            success:function(result) {
                //alert(result.status);
                if (result.status == 0){
                    settime(button);
                } else {
                    $(".errmsgstring").html('Error:短信发送失败. ' + result.msg);
                }
            },
            error:function(msg){
                $(".errmsgstring").html('Error:'+msg.toSource());
            }
        })
        return false;
    }

    var countdown=90;
    function settime(button) {
        if (countdown == 0) {
            countdown = 90;
            $("#getvcode").html("免费获取验证码");
            button.removeAttribute('disabled');
            return;
        } else {
            button.setAttribute("disabled", true);
            $("#getvcode").html("验证码已发送," + countdown+ "秒后重发...");
            countdown--;
        }
        setTimeout(function() {
            settime(button);
        },1000)
    };

    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#signupform" ).validate({
            rules: {
                phone_number: {
                    required: true,
                    minlength: 6
                },
                password: {
                    required: true,
                    minlength: 6
                },
                new2: {
                    required: true,
                    equalTo: "#password"
                },
                verifycode: {
                    required: true,
                }
            },
            messages: {
                phone_number: {
                    required: "手机号码不能为空",
                    minlength: "手机号码位数不足"
                },
                password: {
                    required: "密码不能为空",
                    minlength: "密码长度不能小于 6 个字母"
                },
                new2: {
                    required: "新密码不能为空",
                    minlength: "密码长度不能小于 6 个字母",
                    equalTo: "两次输入密码不一致"
                },
                verifycode: {
                    required: "验证码不能为空",
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

