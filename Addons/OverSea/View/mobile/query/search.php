<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$queryHistories = $_SESSION['queryHistories'];

$servicearea = '地球';
if (isset($_SESSION ['servicearea'])){
    $servicearea = $_SESSION ['servicearea'];
} else if (isset($_SESSION ['userSetting'])){
    $userSetting = $_SESSION ['userSetting'];
    if (isset($userSetting['selected_service_area'])){
        $servicearea = $userSetting['selected_service_area'];
    }
}
$isDiscover = 1;
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
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style>
        .ui-btn { border: none !important; }
        a {
            outline:0;
        }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="../../../Controller/FreelookDispatcher.php?c=getCities" rel="external" data-icon="home" data-shadow="false"><?php echo $servicearea; ?></a>
        <h1>搜索</h1>
    </div>

    <div data-role="content">
        <form id="search-user"data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord">
            <input type="search" name="keyWord" id="keyWord" data-theme="c" autofocus>
        </form>
    </div>

    <div data-role="content">
        <?php if (count($queryHistories) >0) {?>
            <h5 style="color:#33c8ce">历史搜索:</h5>

        <ul data-role="listview" data-split-icon="delete"  data-inset="true">
        <?php foreach($queryHistories as $key => $queryHistory) {?>
            <li data-theme="e"><a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $queryHistory['key_word'];?> " rel="external"><p style="margin: 0px 0px 0px 0px"><?php echo $queryHistory['key_word'] ?></p></a>
                <a href="../../../Controller/AuthUserDispatcher.php?c=deleteKeyWordById&query_id=<?php echo $queryHistory['id'] ?>');">干掉</a>
            </li>
        <?php } ?>
        </ul>
        <?php } ?>
    </div>

    <?php include '../common/footer.php';?>

</div>

<script>
    var itemIdx = 0;
    var pageIdx = 0;
    $(function(){
        $(document).scrollstop(function (event) {
            if($(document).height() > $(window).height())
            {
                if($(window).scrollTop() == $(document).height() - $(window).height()){
                    itemIdx++;
                    pageIdx++;
                    getServiceInNextPages("");
                }
            }
        });
    });

    function getServiceInNextPages(serverIds) {
        var link = '../../../Controller/FreelookDispatcher.php?c=getServices&servicetype=' + <?php echo $serviceType;?> +  '&pageIndex=' + pageIdx;
        $.ajax({
            url:link,
            type:'GET',
            dataType:'json',
            async:false,
            cache: false,
            success:function(result) {

                if (result.status == 0){
                    //alert(result.status);
                    if (result.objLists.length > 0) {
                        $(".endMsgString").html('');
                        jQuery.each(result.objLists,function(key,value){
                            itemIdx++;
                            var newstr = '<div id="d'+itemIdx+'"> <ul data-role="listview" data-inset="true">';
                            newstr = newstr + '<li data-role="list-divider">' +value.stars+ '星服务 <span class="ui-li-count">6次咨询</span></li>';
                            newstr = newstr + '<li> <a href="../service/servicedetails.php?service_id=' + value.service_id +'" rel="external">';
                            newstr = newstr + '<img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/' + value.seller_id + '/head.png" alt="">';
                            newstr = newstr + '<h2>'+ value.seller_name + '</h2>';
                            newstr = newstr + '<p style="white-space:pre-wrap;">' +value.description+ '</p>' ;
                            newstr = newstr + '<p class="ui-li-aside">￥' +value.service_price+ '/小时</p>' ;
                            newstr = newstr + '</a></li> ' ;
                            newstr = newstr + '<li data-role="list-divider"> <p> <a href="javascript:alert(\'developing...\');">'+ value.tag +'</a>'  ;
                            newstr = newstr + '</p> </li> </ul>' ;
                            newstr=newstr+'</div>';
                            $('#discoverMain').append(newstr);
                            $('#d'+itemIdx).trigger('create');
                        })
                    } else {
                        $(".endMsgString").html('已经到达最底部,没有更多内容了...');
                    }
                } else {
                    alert( '内部错误:导入服务失败.' + result.msg);
                    //$(".errmsgstring").html();
                }
            },
            error:function(msg){
                alert( "Error:导入服务失败." + msg.toSource());
            }
        })
        return false;
    };
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

