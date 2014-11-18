
$(function(){
	/*pubilsh*/
	$('.J_Select .rqfSelect-list').click(function(){
		if($(this).hasClass('disabled')||$(this).hasClass('active')) return;
		$(this).addClass('active').siblings().removeClass('active');	
	});

	$('.J_TAB_Select .rqfSelect-list').click(function(){
		if($(this).hasClass('disabled')) return;
		var name = $(this).attr('name')||'other';
		$('.J_TAB_Main div').hide();
		$('.J_TAB_Main .'+name).show();	
	});	
	// function meal(){
	// 	$('.J_TAB_Select .rqfSelect-list').each(function(){
	// 		if($(this).hasClass('active')){
	// 			var name = $(this).attr('name')||'other';
	// 			$('.J_TAB_Main div').hide();
	// 			$('.J_TAB_Main .'+name).show();				
	// 		}
	// 	}) 
	// }
	// meal();
	$('.trade-notice-close').click(function(){
		$(this).parents('.trade-notice').remove();
	})
})

