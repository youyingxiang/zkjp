
(function ($) {
	var isInit = true;
	var _slideAutoChange = null;
	$('.wk_slide-wrap').on("click",".playVideo",function(){
		var videoUrl = $(this).attr("data-url");
		$(this).closest(".wk_slide-wrap").find(".wk_banner24").hide();
		$(this).closest(".wk_slide-wrap").find(".wk_banner32").show();
		isInit = true;
		if(_slideAutoChange){
			clearInterval(_slideAutoChange);
		}
		_slideAutoChange = setInterval(function(){
			if(isInit){
				$('.wk_slide-wrap').find(".wk_banner24").show();
				$('.wk_slide-wrap').find(".wk_banner32").hide();
			}
		},30000);
	})
	
	$(".wk_navwp").on("mouseenter",".chanping-li",function(){
		$(".div-chanping-xilie-content").css({height:"0"}).animate({height:"220px"},100).show();
	})
	
	$(".div-chanping-xilie-content").on("mouseleave",function(){
		$(this).css({height:"120px"}).animate({height:"0"},100).hide();
	})
	
	$(".xilie-ul").on("mouseenter",".xilie-title",function(){
		var index = $(this).index();
		$(".xilie-ul .xilie-title").removeClass("active");
		$(".xilie-ul .xilie-title").eq(index).addClass("active");
		$(".child-ul").hide();
		$(".child-ul").eq(index).show();
	})
	
})(jQuery)
