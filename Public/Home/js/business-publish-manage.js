;(function($) {
    function getHi() {
        return $(window).scrollTop() + $(window).height();
    }
    
    $(function() {
        var $bar = $('#my-bigbar'), limit = $('.business-task-manage-content').outerHeight() + 170;
        (getHi() > limit) && $bar.addClass('oh-my-bigbar');
        $(window).scroll(function(e) {
            $bar[(getHi() > limit ? 'add' : 'remove') + 'Class']('oh-my-bigbar');
        });
    })
})(jQuery);

$(function(){

	var _intd_probox_inpost = false;
	$('.J_show_intd-probox').click(function(){
		var $this = $(this),$par = $this.parent().parent(),_isPost = $this.attr('ispost') || false;
		var _trade = $this.attr('data-trade') || 0;

		$this.hide();


		if(_isPost){
			$par.siblings('.intd-probox.none').show();
			$par.siblings().find('.J_hide_intd-probox').show();
		}else{
			if(_intd_probox_inpost){
				return false;
			}
			$this.attr('ispost','true');
			_intd_probox_inpost = true;
			$.post('/openapi/get_lave_goods_info',{trade:_trade},function(data){
				var html = [];
				var _data = eval(data);
				for(var i = 0;i<_data.length;i++){
					if(_data[i].img_url==''){
						html.push('<div class="intd-probox none" style="display:block;"><p>'+_data[i].item_title+'</p></div>');
					}else{
						html.push('<div class="intd-probox none" style="display:block;"><img src="'+_data[i].img_url+'" class="img" /><p  class="text">'+_data[i].item_title+'</p></div>');
					}
				}
				$(html.join('')).insertAfter($par);	
				_intd_probox_inpost = false;	
				$par.siblings().find('.J_hide_intd-probox').show();
			});
		}

	});	
	
	$('.J_hide_intd-probox').click(function(){
		var $this = $(this),$par = $this.parent();
		$par.siblings('.intd-probox.none').hide();	
		$this.hide();
		$par.siblings().find('.J_show_intd-probox').show();
	});	
	
	
	
	
	
	
});