<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
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

    <script src="../../resource/js/camera/jquery.min.js"></script>
    <script src="../../resource/js/camera/jquery.easing.1.3.js"></script>
    <script src="../../resource/js/camera/camera.min.js"></script>
    <script src="../../resource/js/camera/jquery.mobile.customized.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/camera/camera.css" type="text/css" media="all">

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
        div.headimag {
            height: 65px;
            width: 65px;
        }
    </style>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        a {
            color: #09f;
        }
        a:hover {
            text-decoration: none;
        }
        #back_to_camera {
            clear: both;
            display: block;
            height: 80px;
            line-height: 40px;
            padding: 20px;
        }
        .fluid_container {
            margin: 0 auto;
            max-width: 1000px;
            width: 100%;
        }
    </style>

    <script>
        jQuery(function(){
            jQuery('#camera_wrap_1').camera({
                thumbnails: false,
                loader: 'none',
                portrait :false,
                pagination : false,
                height: '120px',
                navigation : false,
                playPause : false,
                transPeriod: 1000,
                fx:'scrollHorz',
                loaderPadding: '10px',
                onEndTransition: function(){
                    var ind = $('.camera_target .cameraSlide.cameracurrent').index();
                    //alert(ind);
                }

            });
        });


    </script>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="../../../Controller/FreelookDispatcher.php?c=getCities" rel="external" data-icon="home" data-shadow="false"><?php echo $servicearea; ?></a>
        <h1>发现</h1>
        <a href="../../../Controller/AuthUserDispatcher.php?c=searchMainPage" rel="external" data-icon="search" data-shadow="false">搜索</a>
    </div>

    <div id="discoverMain" role="main" class="ui-content jqm-content jqm-fullwidth">

        <div data-role="navbar" style="margin: -5px 0px 10px 0px ">
            <ul>
                <li><a href="#" rel="external" class="ui-btn-active" data-theme="e" onclick="setServiceType(1)">旅游</a></li>
                <li><a href="#" rel="external" data-theme="e" onclick="setServiceType(2)">留学</a></li>
            </ul>
        </div><!-- /navbar -->

        <div class="fluid_container" onclick="alert();">
            <div class="camera_wrap camera_azure_skin" id="camera_wrap_1" style="margin: 0px 0px 5px 0px" >
                <div data-src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/57790fb728713607/578d004e91059576/20160719001406_1.jpg" data-fx='mosaicReverse' onclick="alert(1);">
                </div>
                <div data-src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/57790fb728713607/578d004e91059576/20160719001407_2.jpg" data-fx='mosaicReverse'>
                </div>
            </div><!-- #camera_wrap_1 -->
        </div><!-- .fluid_container -->

        <div id="serviceType1"></div>
        <div id="serviceType2"></div>
    </div>

    <div data-role="content"class="endMsgString"></div>

    <?php include '../common/footer.php';?>
</div>

<script>
    var itemIdx = 0;
    var pageIdx = new Array();
    pageIdx[0]=-1;
    pageIdx[1]=-1;
    var serviceType = 1;

    $(document).ready(function(){
        $('#serviceType1').show();
        getServiceInNextPages(serviceType);
    });

    function setServiceType(type) {
        serviceType = type;
        if (type == 1){
            $('#serviceType1').show()
            $('#serviceType2').hide();
        } else {
            $('#serviceType2').show()
            $('#serviceType1').hide();
        }
        if (pageIdx[serviceType - 1] == -1){
            getServiceInNextPages(serviceType);
        }
    };

    $(function(){
        $(document).scrollstop(function (event) {
            if($(document).height() > $(window).height())
            {
                if($(window).scrollTop() == $(document).height() - $(window).height()){
                    getServiceInNextPages(serviceType);
                }
            }
        });
    });

    function setRate(index, star) {
        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: star,
        }
        $(".rate" + index).rate(options);
    };

    function getServiceInNextPages(type) {
        itemIdx++;
        pageIdx[serviceType-1]++;
        var link = '../../../Controller/FreelookDispatcher.php?c=getServices&servicetype=' + serviceType +  '&pageIndex=' + pageIdx[serviceType-1];
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
                            var servicetypeDesc = value.service_type ==1 ? '【旅游】' : '【留学】';
                            newstr = newstr + '<li data-role="list-divider">' + servicetypeDesc + value.service_area + '<span class="ui-li-count"><div class="rate' + itemIdx +'"></div></span></li>';
                            newstr = newstr + '<li> <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=' + value.service_id +'" rel="external">';
                            newstr = newstr + '<table style="table-layout:fixed;width:260px;" border="0"><tr><td style="width:80px"><div class="headimag">';
                            newstr = newstr + '<img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/' + value.seller_id + '/head.png" height="100%">';
                            newstr = newstr + '</div></td> <td style="width:180px">';
                            newstr = newstr + '<h3>'+ value.seller_name + '</h3>';
                            newstr = newstr + '<p style="white-space:pre-wrap;">' +value.service_name+ '</p>' ;
                            newstr = newstr + '</td></tr></table>';
                            newstr = newstr + '<p class="ui-li-aside">￥' +value.service_price+ '/小时</p>' ;
                            newstr = newstr + '</a></li> ' ;
                            /*
                            newstr = newstr + '<li data-role="list-divider"> <p> ';
                            var strs= new Array();
                            strs=value.tag.split(",");
                            for (i=0;i<strs.length ;i++ )
                            {
                                newstr = newstr + ' <a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=' + strs[i] + '" rel="external">'+ strs[i] +'</a>'  ;
                            }
                            newstr = newstr + '</p> </li> ' ;
                            */
                            newstr=newstr+'</ul></div>';
                            $('#serviceType'+type).append(newstr);
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

