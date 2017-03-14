
$(function(){
    var page = 1;
    var i = 1;
    var $parent = $('.picFocus-a');
    var $picWap = $parent.find('div.picWap');
    var $scorll = $parent.find('div.picWap ul');
    var w_picWap = $picWap.width();
    var li_len = $scorll.find('li').length;
    var count = Math.ceil(li_len/i);
    var $btnL = $('.btnL');
    var $btnR = $('.btnR');
    var inter = setInterval(fn,3000)
    function fn(){
        $btnR.click();
    }
    $btnR.click(function(){
        $('div.picWap').addClass('picWapa');
        if(!$scorll.is(':animated')){
            if(page == count){
                $scorll.animate({left:'0'},'slow');
                page = 1;
            }else{
                $scorll.animate({left:'-='+w_picWap + 'px'},'slow');
                page++;
            }
        }
        $('.subBtn a').eq(page-1).addClass('cur').siblings('a').removeClass('cur');
    }).hover(function(){
        clearInterval(inter)
    },function(){
        inter = setInterval(fn,3000);
    });
    $btnL.click(function(){
        $('div.picWap').addClass('picWapa');
        if(!$scorll.is(':animated')){
            if(page == 1){
                $scorll.animate({left:'-=' + w_picWap * (count -1) + 'px'},'slow')
                page = count;
            }else{
                $scorll.animate({left:'+=' + w_picWap + 'px'},'slow');
                page--;
            }
        }
        $('.subBtn a').eq(page-1).addClass('cur').siblings('a').removeClass('cur');
    }).hover(function(){
        clearInterval(inter)
    },function(){
        inter = setInterval(fn,3000);
    });
    $('.subBtn a').click(function(){
        clearInterval(inter)
        var index = $('.subBtn a').index(this);
        $(this).addClass('cur').siblings('a').removeClass('cur');

        var scrollL = $scorll.css('left');
        $scorll.css({left:-w_picWap*(index),opacity:'0.5'}).animate({opacity:'1'},'slow');
        page = (index+1);
        return false;
    }).hover(function(){
        clearInterval(inter)
    },function(){
        inter = setInterval(fn,3000);
    });
});
