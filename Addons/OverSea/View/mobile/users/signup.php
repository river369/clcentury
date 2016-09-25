<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
require '../common/locations.php';
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
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <style>
        label{ color:#01A4B5}
    </style>
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
        <label for="phone_reigon" style="font-size:12px; color:#01A4B5">提示:保护您个人信息的隐私和安全是易知海外的头等大事。我们加密存储、传输您的个人信息。在未经您书面授权的情况下，我们不会将您的个人资料透露给第三方。</label>
        <br/>
        <form id="signupform" data-ajax="false" method="post" action="../../../Controller/SignUp.php">
            <label for="phone_reigon" style="font-size:12px; color:#33c8ce">请选择地区号:</label>
            <a href="#nav-page" class="ui-btn ui-corner-all ui-shadow ui-btn-mini" data-transition="pop"><h5 id="regionDesc"><?php echo getDescByPhoneCode ($region_phone_country, $existedUserPhoneReigon) ?></h5></a>
            <input type="hidden" name="phone_reigon" id="phone_reigon" value="<?php echo getPhoneCodeByPhoneCode ($existedUserPhoneReigon);?>">
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

<div data-role="page" id="nav-page">
    <ul data-role="listview">
        <li data-role="list-divider" data-icon="delete"><a href="#" data-rel="close">返回</a></li>
        <li><a href="../../../Controller/FreelookDispatcher.php?c=getServices&servicearea=地球" rel="external" data-rel="close"> 地球</a></li>
        <?php
        foreach ($region_phone_country as $region => $phone_country) {
            ?>
            <li data-role="list-divider"><?php echo $region; ?></li>
            <?php
            foreach ($phone_country as $phone => $country) {?>
                <li><a onclick="setRegion('<?php echo $country. ' ' .$phone; ?>', '<?php echo $phone; ?>');" data-rel="back"><label style="font-size:12px;"><?php echo $country. ' ' .$phone; ?></label></a></li>
            <?php } ?>
        <?php } ?>
    </ul>
</div><!-- page -->

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
    function setRegion(regionDesc, gegionId) {
        $('#regionDesc').html(regionDesc);
        $('#phone_reigon').val(gegionId);
    };
</script>
</body>
</html>

