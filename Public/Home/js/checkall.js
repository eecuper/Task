function checkedAllchange(eachname,allname,allallname,checkednum){
    function checkedAll(p,a,b){
        var $this = p.find('input[name="'+a+'"]');
        var $thisall = p.find('input[name="'+b+'"]');
       
		if( p.find('input[name="'+a+'"]:not(:checked)').length<1){
            $thisall.attr('checked','checked');
        }else{
            $thisall.removeAttr('checked');
        }
    }
    
    $('input[name="'+allallname+'"]').click(function(){
        if($(this).is(':checked')){
            $('input[name="'+allallname+'"]').attr('checked','checked');
            $('input[name="'+allname+'"]').attr('checked','checked');
            $('input[name="'+eachname+'"]').attr('checked','checked');
        }else{
            $('input[name="'+allallname+'"]').removeAttr('checked');
            $('input[name="'+allname+'"]').removeAttr('checked');
            $('input[name="'+eachname+'"]').removeAttr('checked');
        } 
		checkednum.call(null,eachname);
    });    
    $('input[name="'+eachname+'"]').click(function(){
        checkedAll($(this).parents('table'),eachname,allname);
		if($('input[name="'+eachname+'"]:not(:checked)').length<1){
			$('input[name="'+allallname+'"]').attr('checked','checked');
		}else{
			$('input[name="'+allallname+'"]').removeAttr('checked');	
		}
		checkednum.call(null,eachname);  
    });
    $('input[name="'+allname+'"]').click(function(){
        checkedAll($(this).parents('.business-task-manage-content'),allname,allallname);
        if($(this).is(':checked')){
            $(this).parents('table').find('input[name="'+eachname+'"]').attr('checked','checked');
        }else{
            $(this).parents('table').find('input[name="'+eachname+'"]').removeAttr('checked');
        } 
		checkednum.call(null,eachname);      
    }); 
	
	 checkednum.call(null,eachname);    
}  




	
var _timeout = false,_timeout_limit = 6,popup={};
$(function(){
	popup = {
		show:function(e){
			var x = e.pageX;
			var y = e.pageY;
			//$b_l = $(this).parents('li');
			$('.J_popBG').css({height:$(document).height()}).fadeIn(200);
			$('.J_popCON').css({left:x-369,top:y-134}).fadeIn(200);	
		},
		hide:function(fn){
			_timeout && location.reload(true);
			$('.J_popBG,.J_popCON').fadeOut(200,function(){			
				$('.J_mg_confirm').css({display:'block'});
				$('.J_popConfirm_content').css({display:'none'});	
				_timeout_limit = 6;	
				$('.J_box_timeout').text(_timeout_limit);	
				fn && fn.call(null)
			});
		},
		confirmShow:function(fn){
			$('.J_mg_confirm').fadeOut(200,function(){
			$('.J_popConfirm_content').fadeIn(200);
				_timeout = setInterval(function(){
					_timeout_limit--;
					if(_timeout_limit==-1){
						popup.hide(fn);	
					}else{
						$('.J_box_timeout').text(_timeout_limit);
					}					
				},1*1000);
			});		
		}	
	}
	
	
	$('.J_popCON .J_popClose').click(function(){
		popup.hide();
	});
		
});


















