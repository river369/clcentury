<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
//session_start();
//$userId = $_SESSION['signedUser'];
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

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/cropper.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/main.css" />
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />
    <style>
        label{ color:#33c8ce;}
        table{ table-layout : fixed; width:100% }
        hr{border:0;background-color:#2c2c2c;height:1px;}
        div.headimage {
            height: 75px;
            width: 75px;
        }
    </style>
    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外" data-theme="a">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>个人信息</h1>
    </div>

    <div data-role="content">
<!--        <input type="hidden" id="uid" value="--><?php //echo $userId;?><!--">-->
        <form id="myinfoForm" data-ajax="false" method="post" action="../../../Controller/FreelookDispatcher.php?c=submitUserSuggestion">
            <table>
                <tr>
                    <td>
                        <label for="suggestion" style="font-size:14px;">反馈意见</label>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <textarea cols="30" rows="8" name="suggestion" id="suggestion" data-mini="true"></textarea>
                    </td>
                </tr>
            </table>
            <input type="submit" name="yzsubmit" id="yzsubmit" value="提交" data-theme="c" >
        </form>
    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#myinfoForm" ).validate({
            rules: {
                description: {
                    required: true,
                    minlength: 5
                }
                
            },
            messages: {
                description: {
                    required: "反馈意见不能为空",
                    minlength: "反馈意见长度不能小于 5 个字"
                    
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

