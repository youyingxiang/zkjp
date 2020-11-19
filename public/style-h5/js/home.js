(function($) {
	var isInit = true;
	var _slideAutoChange = null;
	$('.wk_slide-wrap').on("click", ".playVideo", function() {
		var videoUrl = $(this).attr("data-url");
		if($(document.body).find('video')) {
			$(document.body).find('video').remove();
		}
		var url = $(this).attr('data-video');
		var html = '<video controls preload="auto" width="1" height="1" src="' + videoUrl + '" ></video>';
		$(document.body).append(html);
		var video = $(document.body).find('video')[0];
		video.play();
		//		var videoUrl = $(this).attr("data-url");
		////		$(this).closest(".wk_slide-wrap").find(".wk_banner24").hide();
		//		$(this).closest(".wk_slide-wrap").find(".wk_banner32").show();
		//		isInit = true;
		//		if(_slideAutoChange){
		//			clearInterval(_slideAutoChange);
		//		}
		//		_slideAutoChange = setInterval(function(){
		//			if(isInit){
		////				$('.wk_slide-wrap').find(".wk_banner24").show();
		//				$('.wk_slide-wrap').find(".wk_banner32").hide();
		//			}
		//		},30000);
	})

	$(".wk_navwp").on("mouseenter", ".chanping-li", function() {
		$(".div-chanping-xilie-content").css({
			height: "0"
		}).animate({
			height: "120px"
		}, 100).show();
	})

	$(".div-chanping-xilie-content").on("mouseleave", function() {
		$(this).css({
			height: "120px"
		}).animate({
			height: "0"
		}, 100).hide();
	})

	$(".xilie-ul").on("mouseenter", ".xilie-title", function() {
		var index = $(this).index();
		$(".xilie-ul .xilie-title").removeClass("active");
		$(".xilie-ul .xilie-title").eq(index).addClass("active");
		$(".child-ul").hide();
		$(".child-ul").eq(index).show();
	})

	$(".div-more-btn").on("click", function() {
		$(".navbar-nav").addClass("active");
		$(".div-mask").show();
	})

	$(".div-mask").on("click", function() {
		$(".navbar-nav").removeClass("active");
		$(".div-mask").hide();
	})

})(jQuery)