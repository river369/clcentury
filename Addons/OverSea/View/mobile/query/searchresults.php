<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$keyWord = $_SESSION['keyWord'];
require("../common/locations.php");
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
    <script src="../../resource/js/rater/rater.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style>
        .rate-base-layer
        {
            color: #aaa;
        }
        .rate-hover-layer
        {
            color: orange;
        }
        .rate-select-layer
        {
            color:orange;
        }
        .ui-btn { border: none !important; }
        a {
            outline:0;
        }
        div.headimage {
            height: 65px;
            width: 65px;
        }
        h5{ color:#33c8ce}
        p{ font-size:14px;}
        table{ table-layout : fixed; width:100% }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="../../../Controller/FreelookDispatcher.php?c=getCities" rel="external" data-icon="home" data-shadow="false"><?php echo $servicearea; ?></a>
        <h1>发现</h1>
        <a href="../../../Controller/AuthUserDispatcher.php?c=searchMainPage" rel="external" data-icon="search" data-shadow="false">搜索</a>
    </div>

    <div id="discoverMain" role="main" class="ui-content jqm-content jqm-fullwidth" style="margin: 0px -5px 0px -5px">
    </div>
    <div data-role="content"class="endMsgString"></div>

    <?php include '../common/footer.php';?>

    <div data-role="panel" data-position-fixed="true" data-display="push" data-theme="o" id="nav-panel">
        <ul data-role="listview">
            <li data-role="list-divider" data-icon="delete"><a href="#" data-rel="close">返回</a></li>
            <li><a href="../../../Controller/FreelookDispatcher.php?c=getServices&servicearea=地球" rel="external" data-rel="close"> 地球</a></li>
            <?php
                foreach ($country_city as $key => $value) {
            ?>
                    <li data-role="list-divider"><?php echo $key; ?></li>
                    <?php
                        foreach ($value as $city) {
                    ?>
                        <li><a href="../../../Controller/FreelookDispatcher.php?c=getServices&servicearea=<?php echo $city; ?>" rel="external" data-rel="close" ><?php echo $city; ?></a></li>
                    <?php } ?>
            <?php } ?>
        </ul>
    </div><!-- /panel -->
</div>

<script>
    var itemIdx = 0;
    var pageIdx = -1;
    $(document).ready(function(){
        getServiceInNextPages();
    });

    $(function(){
        $(document).scrollstop(function (event) {
            if($(document).height() > $(window).height())
            {
                if($(window).scrollTop() == $(document).height() - $(window).height()){
                    getServiceInNextPages();
                }
            }
        });
    });

    function setRate(index, star) {
        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: star,
            readonly:true,
        }
        $(".rate" + index).rate(options);
    };

    function getServiceInNextPages() {
        itemIdx++;
        pageIdx++;
        var link = '../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $keyWord;?>&pageIndex=' + pageIdx;
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
                            var newstr = '<div id="d'+itemIdx+'" style="margin: -5px 0px 0px 0px "> <ul data-role="listview" data-inset="true" data-theme="f">';
                            var servicetypeDesc = value.service_type ==1 ? '旅游' : '留学';
                            newstr = newstr + '<li data-role="list-divider"><p style="margin: -5px 0px -3px 0px;font-size:14px;" >【' + servicetypeDesc + ':' + value.service_area + '】' + value.service_name +'</p> <span class="ui-li-count"><div class="rate' + itemIdx +'"></div></span></li>';
                            newstr = newstr + '<li style="margin: -5px 0px -5px 0px"> <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=' + value.service_id +'" rel="external">';
                            newstr = newstr + '<table style="margin: -8px 0px -8px 0px" border="0"><tr><td style="width:27%"><div class="headimage">';
                            newstr = newstr + '<img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/' + value.seller_id + '/' +value.service_id + '/main.png" height="100%">';
                            newstr = newstr + '</div></td> <td style="width:73%">';
                            newstr = newstr + '<p style="white-space:pre-wrap;word-break:break-all">卖家:'+ value.seller_name + '</p>';
                            newstr = newstr + '<p style="white-space:pre-wrap;word-break:break-all">简介:' +value.service_brief+ '</p>' ;
                            newstr = newstr + '</td></tr></table>';
                            newstr = newstr + '<p class="ui-li-aside">￥' +value.service_price+ '/小时</p>' ;
                            newstr = newstr + '</a></li>';
                            newstr=newstr+'</ul></div>';
                            //alert(newstr);
                            $('#discoverMain').append(newstr);
                            setRate(itemIdx, value.stars);
                            $('#d'+itemIdx).trigger('create');
                            $("img").error(function () {
                                $(this).attr("src", "../../resource/images/head_default.jpg");
                            });
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

