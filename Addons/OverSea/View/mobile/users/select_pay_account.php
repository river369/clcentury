<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/9/1
 * Time: 23:19
 */
session_start();
$errorMsg = $_SESSION['errorMsg'] ;
$sellerPayAccounts = $_SESSION['sellerPayAccounts'] ;
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />
    <style>
        h5{ color:#33c8ce}
        h3{ color:#33c8ce}
        p{ font-size:18px;}
    </style>

</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" data-theme="a" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>确认账号</h1>
    </div>

    <?php if($errorMsg != null){ ?>
        <div class="$errorMsg" style="color:red" data-role="content">
            <?php echo $errorMsg; ?>
        </div>
    <?php } ?>

    <div data-role="content">
        <h3>请选择卖家收款账号</h3>
        <h5>使用微信零钱收款</h5>
        <form id="selectAccountForm" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=updateSellerPayInfo">
            <fieldset data-role="controlgroup">
                <?php
                foreach($sellerPayAccounts as $key => $account)
                { ?>
                <input type="radio" name="seller_pay_account" id="radio-choice-<?php echo $account['id'];?>" value="<?php echo $account['account_id'];?>" <?php echo $account['status']==1? "checked='checked'" :"" ?> />
                <label for="radio-choice-<?php echo $account['id'];?>"><?php echo $account['nick_name'];?></label>
                <?php } ?>
            </fieldset>
            <input type="submit" name="yzsubmit" id="yzsubmit" value="确定" data-theme="c">
        </form>
    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#selectAccountForm" ).validate({
            rules: {
                seller_pay_account: {
                    required: true,
                }
            },
            messages: {
                seller_pay_account: {
                    required: "卖家收款账号为必选项",
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
