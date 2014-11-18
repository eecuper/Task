
$(function(){
	
	var _timeout = false,_timeout_limit = 6,$b_l = null;
	
	$('.J_show_zz').live("click",function(){
		$b_l = $(this).parents('li');						   
		var trade_id = $(this).attr('i_id');
        $.get('/task_business/appreciation?trade_id=' +trade_id, function (data) {
        	$('.J_popCON').html(data);
			$('.J_pay_cut').each(function(){
				var _en = $(this).find('.J_pay_cut_price').attr('data-enable');
				if(_en=='0' || _en ==''){
					$(this).addClass('disabled');
					$(this).find('.J_pay_cut_check').attr('disabled','disabled');
				}else{
					$(this).removeClass('disabled');
				}
			});
			$('.J_popBG').css({height:$(document).height()}).fadeIn(200);
			var _top = ($(window).height()-$('.J_popCON').height())*.5 + $(window).scrollTop();
			$('.J_popCON').css({top:_top,'margin-top':0}).fadeIn(200);
        });
			   
		/*						   
		$b_l = $(this).parents('li');
		$('.J_popBG').css({height:$(document).height()}).fadeIn(200);
		$('.J_popCON').fadeIn(200,function(){
			var _top = ($(window).height()-$('.J_popCON').height())*.5 + $(window).scrollTop();
			//alert(($(window).height()-$('.J_popCON').height())*.5 );
			$('.J_popCON').css({top:_top,'margin-top':0});
		});*/
	});
	
	$('.J_popCON .J_popClose').live("click",function(){
		_timeout_limit = 6;
		$('.J_popBG,.J_popCON').fadeOut(200,function(){			
			$('.J_black_confirm').css({display:'block'});
			$('.J_popConfirm_content').css({display:'none'});	
			$('.J_box_timeout').html(_timeout_limit);
		});
	});
	
	
	//_timeout = setInterval(function(){
//		_timeout_limit--;
//		if(_timeout_limit==-1){
//			$('.J_popCON .J_popClose').trigger('click');
//			clearInterval(_timeout);	
//		}
//		$('.J_box_timeout').text(_timeout_limit);	
//	},1*1000);
	
	$('.J_CANCEL').live("click",function(){
		$('.J_popCON .J_popClose').trigger('click');	
	});
	
	
	
	$('.J_moreBox_btn').live("click",function(){
		var $this = $(this),$par = $this.parent();
		if($par.hasClass('active')){
			$par.removeClass('active');
		}else{
			$par.addClass('active');				
		}
	});
	

	
	//step
	
	$('.J_fbtn').live("click",function(){
		var $regList = $(".J_zz_first *[reg]");
		var _re = regForm($regList);
		if(_re.length>0)
		{
		  return false;
		}
		$.getScript('/static/js/vip-renew.js?v=2',function(){
			$('.J_zz_first,.J_zz_three').hide();	
			$('.J_zz_second').show();	
		});
		
	});
	
	
	//step
	
	$('.J_prevbtn').live("click",function(){
		$('.J_zz_second,.J_zz_three').hide();	
		$('.J_zz_first').show();	
	});
	
	
	
});