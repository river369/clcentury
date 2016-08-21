

var itemIdx = 10;
var pageIdx = new Array();
pageIdx[0]=0;
pageIdx[1]=-1;
var serviceType = 1;

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
        getServiceInNextPages();
    }
};

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
                        var newstr = '<div id="d'+itemIdx+'" style="margin: -5px 0px -5px 0px "> <ul data-role="listview" data-inset="true" data-theme="f">';
                        //var servicetypeDesc = value.service_type ==1 ? '【旅游】' : '【留学】';
                        //newstr = newstr + '<li data-role="list-divider">' + servicetypeDesc + value.service_area + '<span class="ui-li-count"><div class="rate' + itemIdx +'"></div></span></li>';
                        newstr = newstr + '<li data-role="list-divider"><p style="margin: -5px 0px -3px 0px;font-size:14px;" >【' + value.service_area + '】'+ value.service_name +'</p><span class="ui-li-count"><div class="rate' + itemIdx +'"></div></span></li>';
                        newstr = newstr + '<li style="margin: -5px 0px -5px 0px"> <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=' + value.service_id +'" rel="external">';
                        newstr = newstr + '<table border="0" style="margin: -8px 0px -8px 0px"><tr><td style="width:27%"><div class="headimage">';
                        newstr = newstr + '<img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/' + value.seller_id + '/' + value.service_id + '/main.png" height="100%">';
                        newstr = newstr + '</div></td> <td style="73%">';
                        newstr = newstr + '<p style="white-space:pre-wrap;word-break:break-all">卖家:'+ value.seller_name + '</p>';
                        var brief = (value.service_brief==null)?'':value.service_brief;
                        newstr = newstr + '<p style="white-space:pre-wrap;word-break:break-all">简介:' +brief+ '</p>' ;
                        newstr = newstr + '</td></tr></table>';
                        newstr = newstr + '<p class="ui-li-aside">￥' +value.service_price+ '/小时</p>' ;
                        newstr = newstr + '</a></li>';
                        newstr=newstr+'</ul></div>';
                        $('#serviceType'+serviceType).append(newstr);
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
