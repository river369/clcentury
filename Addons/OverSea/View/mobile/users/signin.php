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
unset($_SESSION['existedUserPhoneReigon'], $_SESSION['existedUserPhoneNumber'], $_SESSION['$signInErrorMsg'] );

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
    <style>
        label.error {
            color: red;
            font-size: 16px;
            font-weight: normal;
            line-height: 1.4;
            margin-top: 0.5em;
            width: 100%;
            float: none;
        }
        em {
            color: red;
            font-weight: bold;
            padding-right: .25em;
        }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>登陆</h1>
    </div>

    <?php if($existedUserPhoneReigon != null){ ?>
        <div class="errmsgstring1" style="color:red" data-role="content">
            <?php echo $existedUserPhoneReigon.$existedUserPhoneNumber; ?>已经存在,请直接登陆.
        </div>
    <?php } ?>

    <?php if($signInErrorMsg != null){ ?>
        <div class="errmsgstring2" style="color:red" data-role="content">
            <?php echo $signInErrorMsg; ?>
        </div>
    <?php } ?>


    <div id="page1" data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/SignIn.php">
            <label for="phone_reigon" >请选择地区号:</label>
            <select name="phone_reigon" id="phone_reigon">
                <option value="+86" <?php echo $existedUserPhoneReigon=='+86'? 'selected = "selected"' : ''; ?> >中国 +86</option>
                <option value="+1" <?php echo $existedUserPhoneReigon=='+1'? 'selected = "selected"' : ''; ?>>美国 +1</option>
            </select>
            </br>
            <label for="phone_number" >请输入手机号码:</label>
            <input type="number" name="phone_number" id="phone_number" value="<?php echo $existedUserPhoneNumber; ?>">
            </br>
            <label for="password" >请输入密码:</label>
            <input type="password" name="password" id="password">
            </br>
            <input type="submit" name="signinsubmit" id="signinsubmit" value="登陆">
        </form>
    </div>

    <div data-role="content">
        <a href="#nav-panel" rel="external"  data-icon="home">忘记密码</a>
        <a href="./signup.php" rel="external" data-icon="home">立即注册</a>
    </div>

    <?php include '../common/footer.php';?>
</div>

<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#submityz" ).validate({
            rules: {
                phone_number: {
                    required: true
                },
                password: {
                    required: true
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

