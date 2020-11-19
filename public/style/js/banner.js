PTM(function(){
//	_slideAutoChange = setInterval("PTM.slideAutoChange()",30000);
	var isInit = true;
	var _slideAutoChange = null;
	PTM('#wk_slide-nav li.wk_nav-bullet-container').click(function(){
		isInit = true;
		if(_slideAutoChange){
			clearInterval(_slideAutoChange);
		}
		PTM('#wk_slide-nav li.wk_nav-bullet-container').removeClass('active').eq(PTM(this).index()).addClass('active');
		
		PTM('.wk_slide-wrap li').removeClass('wk_selected').eq(PTM(this).data('index')).addClass('wk_selected');
		_slideAutoChange = setInterval("PTM.slideAutoChange()",30000);
		if(PTM(this).data('index')==0){
			PTM('.wk_slide-wrap #wk_s1').find(".wk_banner24").show();
			PTM('.wk_slide-wrap #wk_s1').find(".wk_banner32").hide();
		}

	})
	PTM.extend({
		slideAutoChange:function(){
			if(isInit){
				curr = PTM('.wk_slide-wrap li.wk_selected');
				if(curr.next().size()>0){
	        		next = curr.next(); 
	        	}
				else{
					next = PTM('.wk_slide-wrap li:first');
				}
	    		curr.removeClass('wk_selected');
	        	next.addClass('wk_selected');
	        	
	    		PTM('#wk_slide-nav li.wk_nav-bullet-container').removeClass('active').eq(next.index()).addClass('active');
				isInit = false;
				if(_slideAutoChange){
					clearInterval(_slideAutoChange);
				}
			}
			
		}
	})
	//_slideAutoChange = setInterval("PTM.slideAutoChange()",5000);
		
});