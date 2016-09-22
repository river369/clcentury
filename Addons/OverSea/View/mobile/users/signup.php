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
        <label for="phone_reigon" style="font-size:12px; color:#01A4B5">提示:保护您个人信息的隐私和安全是易知海外的头等大事。我们加密存储、传输您的个人信息。在未经您书面授权的情况下，我们不会将您的个人资料透露给第三方。</label>
        <br/>
        <form id="signupform" data-ajax="false" method="post" action="../../../Controller/SignUp.php">
            <label for="phone_reigon" style="font-size:12px; color:#33c8ce">请选择地区号:</label>
            <select name="phone_reigon" id="phone_reigon">
                <option value="+86" <?php echo $existedUserPhoneReigon=='+86'? 'selected = "selected"' : ''; ?> >中国大陆 +86</option>
                <option value="+1" <?php echo $existedUserPhoneReigon=='+1'? 'selected = "selected"' : ''; ?>>美国 +1</option>
                <optgroup label="欧洲">
                    <option value="+353" <?php echo $existedUserPhoneReigon=='+353'? 'selected = "selected"' : ''; ?> >爱尔兰 +353</option>
                    <option value="+43" <?php echo $existedUserPhoneReigon=='+43'? 'selected = "selected"' : ''; ?> >奥地利 +43</option>
                    <option value="+32" <?php echo $existedUserPhoneReigon=='+32'? 'selected = "selected"' : ''; ?> >比利时 +32</option>
                    <option value="+48" <?php echo $existedUserPhoneReigon=='+48'? 'selected = "selected"' : ''; ?> >波兰 +48</option>
                    <option value="+354" <?php echo $existedUserPhoneReigon=='+354'? 'selected = "selected"' : ''; ?> >冰岛 +354</option>
                    <option value="+45" <?php echo $existedUserPhoneReigon=='+45'? 'selected = "selected"' : ''; ?> >丹麦 +45</option>
                    <option value="+49" <?php echo $existedUserPhoneReigon=='+49'? 'selected = "selected"' : ''; ?> >德国 +49</option>
                    <option value="+7" <?php echo $existedUserPhoneReigon=='+7'? 'selected = "selected"' : ''; ?> >俄罗斯 +7</option>
                    <option value="+33" <?php echo $existedUserPhoneReigon=='+33'? 'selected = "selected"' : ''; ?> >法国 +33</option>
                    <option value="+358" <?php echo $existedUserPhoneReigon=='+358'? 'selected = "selected"' : ''; ?> >芬兰 +358</option>
                    <option value="+379" <?php echo $existedUserPhoneReigon=='+379'? 'selected = "selected"' : ''; ?> >梵蒂冈 +379</option>
                    <option value="+31" <?php echo $existedUserPhoneReigon=='+31'? 'selected = "selected"' : ''; ?> >荷兰 +31</option>
                    <option value="+420" <?php echo $existedUserPhoneReigon=='+420'? 'selected = "selected"' : ''; ?> >捷克 +420</option>
                    <option value="+423" <?php echo $existedUserPhoneReigon=='+423'? 'selected = "selected"' : ''; ?> >列支敦士登 +423</option>
                    <option value="+370" <?php echo $existedUserPhoneReigon=='+370'? 'selected = "selected"' : ''; ?> >立陶宛 +370</option>
                    <option value="+352" <?php echo $existedUserPhoneReigon=='+352'? 'selected = "selected"' : ''; ?> >卢森堡 +352</option>
                    <option value="+40" <?php echo $existedUserPhoneReigon=='+40'? 'selected = "selected"' : ''; ?> >罗马尼亚 +40</option>
                    <option value="+377" <?php echo $existedUserPhoneReigon=='+377'? 'selected = "selected"' : ''; ?> >摩纳哥 +377</option>
                    <option value="+47" <?php echo $existedUserPhoneReigon=='+47'? 'selected = "selected"' : ''; ?> >挪威 +47</option>
                    <option value="+351" <?php echo $existedUserPhoneReigon=='+351'? 'selected = "selected"' : ''; ?> >葡萄牙 +351</option>
                    <option value="+46" <?php echo $existedUserPhoneReigon=='+46'? 'selected = "selected"' : ''; ?> >瑞典 +46</option>
                    <option value="+41" <?php echo $existedUserPhoneReigon=='+41'? 'selected = "selected"' : ''; ?> >瑞士 +41</option>
                    <option value="+421" <?php echo $existedUserPhoneReigon=='+421'? 'selected = "selected"' : ''; ?> >斯洛伐克 +421</option>
                    <option value="+90" <?php echo $existedUserPhoneReigon=='+90'? 'selected = "selected"' : ''; ?> >土耳其 +90</option>
                    <option value="+380" <?php echo $existedUserPhoneReigon=='+380'? 'selected = "selected"' : ''; ?> >乌克兰 +380</option>
                    <option value="+34" <?php echo $existedUserPhoneReigon=='+34'? 'selected = "selected"' : ''; ?> >西班牙 +34</option>
                    <option value="+30" <?php echo $existedUserPhoneReigon=='+30'? 'selected = "selected"' : ''; ?> >希腊 +30</option>
                    <option value="+44" <?php echo $existedUserPhoneReigon=='+44'? 'selected = "selected"' : ''; ?> >英国 +44</option>
                    <option value="+39" <?php echo $existedUserPhoneReigon=='+39'? 'selected = "selected"' : ''; ?> >意大利 +39</option>
                </optgroup>
                <optgroup label="亚洲">
                    <option value="+971" <?php echo $existedUserPhoneReigon=='+971'? 'selected = "selected"' : ''; ?> >阿联酋 +971</option>
                    <option value="+975" <?php echo $existedUserPhoneReigon=='+975'? 'selected = "selected"' : ''; ?> >不丹 +975</option>
                    <option value="+63" <?php echo $existedUserPhoneReigon=='+63'? 'selected = "selected"' : ''; ?> >菲律宾 +63</option>
                    <option value="+82" <?php echo $existedUserPhoneReigon=='+82'? 'selected = "selected"' : ''; ?> >韩国 +82</option>
                    <option value="+855" <?php echo $existedUserPhoneReigon=='+855'? 'selected = "selected"' : ''; ?> >柬埔寨 +855</option>
                    <option value="+960" <?php echo $existedUserPhoneReigon=='+960'? 'selected = "selected"' : ''; ?> >马尔代夫 +960</option>
                    <option value="+880" <?php echo $existedUserPhoneReigon=='+880'? 'selected = "selected"' : ''; ?> >孟加拉国 +880</option>
                    <option value="+60" <?php echo $existedUserPhoneReigon=='+60'? 'selected = "selected"' : ''; ?> >马来西亚 +60</option>
                    <option value="+81" <?php echo $existedUserPhoneReigon=='+81'? 'selected = "selected"' : ''; ?> >日本 +81</option>
                    <option value="+853" <?php echo $existedUserPhoneReigon=='+853'? 'selected = "selected"' : ''; ?> >澳门特别行政区 +853</option>
                    <option value="+966" <?php echo $existedUserPhoneReigon=='+966'? 'selected = "selected"' : ''; ?> >沙特阿拉伯 +966</option>
                    <option value="+66" <?php echo $existedUserPhoneReigon=='+66'? 'selected = "selected"' : ''; ?> >泰国 +66</option>
                    <option value="+886" <?php echo $existedUserPhoneReigon=='+886'? 'selected = "selected"' : ''; ?> >台湾 +886</option>
                    <option value="+673" <?php echo $existedUserPhoneReigon=='+673'? 'selected = "selected"' : ''; ?> >文莱 +673</option>
                    <option value="+852" <?php echo $existedUserPhoneReigon=='+852'? 'selected = "selected"' : ''; ?> >香港特别行政区 +852</option>
                    <option value="+65" <?php echo $existedUserPhoneReigon=='+65'? 'selected = "selected"' : ''; ?> >新加坡 +65</option>
                    <option value="+91" <?php echo $existedUserPhoneReigon=='+91'? 'selected = "selected"' : ''; ?> >印度 +91</option>
                    <option value="+62" <?php echo $existedUserPhoneReigon=='+62'? 'selected = "selected"' : ''; ?> >印尼 +62</option>
                    <option value="+84" <?php echo $existedUserPhoneReigon=='+84'? 'selected = "selected"' : ''; ?> >越南 +84</option>
                    <option value="+972" <?php echo $existedUserPhoneReigon=='+972'? 'selected = "selected"' : ''; ?> >以色列 +972</option>
                </optgroup>
                <optgroup label="大洋洲">
                    <option value="+61" <?php echo $existedUserPhoneReigon=='+61'? 'selected = "selected"' : ''; ?> >澳大利亚 +61</option>
                    <option value="+64" <?php echo $existedUserPhoneReigon=='+64'? 'selected = "selected"' : ''; ?> >新西兰 +64</option>
                </optgroup>
                <optgroup label="非洲">
                    <option value="+20" <?php echo $existedUserPhoneReigon=='+20'? 'selected = "selected"' : ''; ?> >埃及 +20</option>
                    <option value="+248" <?php echo $existedUserPhoneReigon=='+248'? 'selected = "selected"' : ''; ?> >塞舌尔 +248</option>
                </optgroup>
                <optgroup label="美洲">
                    <option value="+55" <?php echo $existedUserPhoneReigon=='+55'? 'selected = "selected"' : ''; ?> >巴西 +55</option>
                    <option value="+52" <?php echo $existedUserPhoneReigon=='+52'? 'selected = "selected"' : ''; ?> >墨西哥 +52</option>
                    <option value="+1" <?php echo $existedUserPhoneReigon=='+1'? 'selected = "selected"' : ''; ?> >加拿大 +1</option>
                </optgroup>
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

