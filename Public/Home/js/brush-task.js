
$(function(){
	// 取消任务单
	$('.J-cancel-task').click(function(){
		var _order = $(this).attr('name');							   
        var $dt = $(document).scrollTop();
        var $dh = $(window).height();
        var $bh=  $('.business-small-popup').outerHeight();
		if($bh>=$dh){
			itop = $dt+10;
		}else{
			itop = $dt+ ($dh-$bh)/2;
		}   
		$('.J_popBG').css({height:$(document).height()}).fadeIn(200);
		$('.J_poptradeCON').css({top:itop}).fadeIn(200);
		$('.J_popConfirm').attr('name',_order);
	});
	
  $('.J_poptradeCON .popup-close,.J_poptradeCON .buttons-blacklist-close').live("click",function(){
  		$('.J_popBG,.J_poptradeCON').fadeOut(200);
		$('.J_popConfirm').attr('name','');
  });
	
	$('.J_poptradeCON .J_popConfirm').click(function(){
	    var _order = $(this).attr('name');
		if(_order){
			$(this).attr('name','');
			$.post('/task_brush/brush_task_cancel', {order:_order}, function(data){
				var _data = eval('('+ data +')');
				if(_data.error=='0'){
					location.reload();
				}else{
					alert(_data.message);
				}
			});
		}
	});
	
	
})


function brushtashsx_popup(){
	// 取消任务单
		var _order = $(this).attr('name');
        var $dt = $(document).scrollTop();
        var $dh = $(window).height();
        var $bh=  $('.business-big-popup').outerHeight();
		//alert($bh);
		if($bh>=$dh){
			itop = $dt+10;
		}else{
			itop = $dt+ ($dh-$bh)/2;
		}
		$('.J_popBGs').css({height:$(document).height()}).fadeIn(300);
		$('.J_poptradeCONs').css({top:itop}).fadeIn(300);
		$('.J_popConfirms').attr('name',_order);
	
  $('.J_poptradeCONs .popup-closes,.J_poptradeCONs .buttons-blacklist-close').live("click",function(){
  		$('.J_popBGs,.J_poptradeCONs').fadeOut(300);
		$('.J_popConfirms').attr('name','');
  });
	
	$('.J_poptradeCONs .J_popConfirms').click(function(){
	    var _order = $(this).attr('name');
		if(_order){
			$(this).attr('name','');
			$.post('/task_brush/brush_task_cancel', {order:_order}, function(data){
				var _data = eval('('+ data +')');
				if(_data.error=='0'){
					location.reload();
				}else{
					alert(_data.message);
				}
			});
		}
	});
}
