
winWidth = $(window).width();

$(function(){
	$(window).scroll(function(){
		if($(document).scrollTop() != 0){
			$(".header-top").addClass('on')
		}else{
			$(".header-top").removeClass('on')
            if ($(window).width()<640) {
                $(".header-top").addClass("on");
            }
		}
	})

	var arrPlace =[]
    var $iptTxt = $("body").find(".input");
    for(var i=0;i<$iptTxt.length;i++){
        var iptPlace=$iptTxt.eq(i).attr("placeholder");
        arrPlace.push(iptPlace)
    }
    $iptTxt.each(function(i){
        $(this).on("focus",function(){
            $(this).attr("placeholder","")
        })
        $(this).on("blur",function(){
            $(this).attr("placeholder",arrPlace[i])
        })
    })
    if(winWidth<769){
        $(".header-top .header .nav > ul > li .dropdown").each(function(){
            $(this).height($(window).height()-$(".header-top").height());
        })
    }
    if(winWidth<1024){

        //给导航的一级菜单禁止点击
        $(".header-top .header .nav > ul > li > a").attr("href","javascript:");

        //点击菜单按钮，弹出二级菜单
    	toggle = 0;
    	$(".menu").on("click",function(){
    		if(toggle == 0){
    			toggle = 1;
                $(".header-top").addClass('on');
    			$(".header-top .header .nav").addClass('on');
	    		$(this).addClass('on');
    		}else if(toggle == 1){
    			toggle = 0;
                $(".header-top").removeClass('on');
                $(".header-top .header .nav > ul > li").removeClass('active');
    			$(".header-top .header .nav").removeClass('on');
	    		$(this).removeClass('on');
    		}
	    		
    	})

        //点击一级菜单，弹出三级菜单
        $(".header-top .header .nav > ul > li > a").on("click",function(){
            $(this).parent().toggleClass('active').siblings().removeClass('active on');
        })

        //产品菜单点击收缩
        $("li.nav-pro .dropdown .content .list .title").on("click",function(){
            $(this).next().slideToggle(150).parents(".list").siblings().find(".select-box").slideUp(150);
        })

        //尾部点击展开菜单
        $(".footer .left .list .tit").on("click",function(){
            $(this).toggleClass('on').parent().siblings().find(".tit").removeClass('on');
            $(this).next().slideToggle(150).parent().siblings().find("ul").slideUp(150);
        })

    }

    $(".workorder-content .item01 .list-con .list .box-con").each(function(){
        $(this).height($(this).parents(".list").height());
    })

	//产品分类展开效果
    $(".pro-list-content .pro-left > ul > li i").on("click",function(){
        $(this).parent().toggleClass('on').parent().siblings().find("span").removeClass('on');
    })
	
})

$(window).resize(function(){
    winWidth = $(window).width();
    if(winWidth>=1024){
        $(".header-top .header .nav > ul > li .dropdown").removeAttr('style');
    }
    $(".workorder-content .item01 .list-con .list .box-con").each(function(){
        $(this).height($(this).parents(".list").height());
    })
})