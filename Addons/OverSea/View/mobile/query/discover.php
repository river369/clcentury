<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$servicesData= $_SESSION['servicesData'];
$serviceType = isset($_SESSION['servicetype'])? $_SESSION['servicetype'] : 1;
require("../common/locations.php");
$servicearea = '地球';
if (isset($_SESSION ['servicearea'])){
    $servicearea = $_SESSION ['servicearea'];
}
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
   
    <script>
        $(document).ready(function(){
            var options = {
                max_value: 5,
                step_size: 0.5,
                initial_value: 3.5,
            }
            $(".rate").rate(options);
        });
    </script>
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
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="#nav-panel"data-icon="home"><?php echo $servicearea; ?></a>
        <h1>发现</h1>
        <a href="../../../Controller/AuthUserDispatcher.php?c=searchMainPage" rel="external" data-icon="search">搜索</a>
    </div>

    <div id="discoverMain" role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/FreelookDispatcher.php?c=getServices&servicetype=1" rel="external" <?php if ($serviceType == 1) { ?> class="ui-btn-active" <?php } ?> >旅游</a></li>
                <li><a href="../../../Controller/FreelookDispatcher.php?c=getServices&servicetype=2" rel="external" <?php if ($serviceType == 2) { ?> class="ui-btn-active" <?php } ?>>留学</a></li>
            </ul>
        </div><!-- /navbar -->
            <?php
                foreach($servicesData as $key => $serviceData)
                {
            ?>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider"><?php echo $serviceData['stars']?>星服务 <span class="ui-li-count"><div class="rate"></div></li>
            <li>
                <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=<?php echo $serviceData['id']; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $serviceData['seller_id'];?>/head.png" alt="">
                    <h2><?php echo $serviceData['seller_name']?></h2>
                    <p style="white-space:pre-wrap;"><?php echo $serviceData['description']?></p>
                    <p class="ui-li-aside">￥<?php echo $serviceData['service_price']?>/小时</p>
                </a>
            </li>
            <li data-role="list-divider">
                <p>
            <?php $tags = $serviceData['tag'];
                $tagsArray = explode(',',$tags);
                foreach ($tagsArray as $tag){ ?>
                    <a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $tag;?> " rel="external"><?php echo $tag; ?></a>
            <?php } ?>
                </p>
            </li>
        </ul>
            <?php } ?>
    </div>
    <div data-role="content"class="endMsgString"></div>

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>

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
                            newstr = newstr + '<li data-role="list-divider">' +value.stars+ '星服务 <span class="ui-li-count"><div class="rate"></div></span></li>';
                            newstr = newstr + '<li> <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=' + value.id +'" rel="external">';
                            newstr = newstr + '<img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/' + value.seller_id + '/head.png" alt="">';
                            newstr = newstr + '<h2>'+ value.seller_name + '</h2>';
                            newstr = newstr + '<p style="white-space:pre-wrap;">' +value.description+ '</p>' ;
                            newstr = newstr + '<p class="ui-li-aside">￥' +value.service_price+ '/小时</p>' ;
                            newstr = newstr + '</a></li> ' ;
                            newstr = newstr + '<li data-role="list-divider"> <p> ';
                            var strs= new Array();
                            strs=value.tag.split(",");
                            for (i=0;i<strs.length ;i++ )
                            {
                                newstr = newstr + ' <a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=' + strs[i] + '" rel="external">'+ strs[i] +'</a>'  ;
                            }
                            newstr = newstr + '</p> </li> </ul>' ;
                            newstr=newstr+'</div>';
                            $('#discoverMain').append(newstr);
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

