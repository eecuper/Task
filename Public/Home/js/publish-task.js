var errTip = {
	show:function($_this,err){
		var $par = $_this.parent();
		if($par.find('.error').length>0){
			$par.find('.error').text(err);	
		}else{
			$par.append($('<span class="error">'+err+'</span>'));	
		}
	},
	hide:function($_this){			
		var $par = $_this.parent();
		if($par.find('.error').length>0){
			$par.find('.error').remove();	
		}
	}
}

var collections_regs = {
	url:/^http:\/\/[a-z0-9-]+\.[a-z0-9-]{1,}/,
	num:/^(([1-9])|([1-9][0-9]{1,}))$/,
	price:/^(([1-9][0-9]{1,}\.[0-9][1-9]{1,})|([1-9][0-9]{1,}\.[0-9]{1,2})|([1-9][0-9]{1,})|([0-9]{1,})|([0-9]\.[0-9]{1,}))$/,
	lgprice:/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/,
	keyword:/^(([\u4E00-\u9FA5\x00-\xff]+)|([\u4E00-\u9FA5\x00-\xff]+\s+[\u4E00-\u9FA5\x00-\xff]+))$/,
	num09:/^[0-9]$/,
	name:/^[\u4E00-\u9FA5\x00-\xffa-zA-Z0-9-_]+$/,
	empty:/^\S+$/,
	every:function(val){
		if(val && $.trim(val)!=''){
			return true;
		}
		return false;
	},
	gt100:function(val){
		if(val>100){
			return true;	
		}
		return false;
	},
	lt300:function(val){
		if(val<300){
			return true;	
		}
		return false;
	}
}
	
var collections = {
	url:[{reg:collections_regs.url,error:'商品链接不正确'}],
	name:[{reg:collections_regs.every,error:'商品名称不能为空'}],
	mainpic:[{reg:collections_regs.every,error:'请上传商品主图'}],
	mess:[{reg:collections_regs.every,error:'留言内容不能为空'}],
	price:[{reg:collections_regs.lgprice,error:'商品金额不正确'}],
	keyword:[{reg:collections_regs.every,error:'商品关键字不能为空'}],
	newmtype:[{reg:collections_regs.every,error:'所在分类最少填写两个'}],
	mtype:[{reg:collections_regs.every,error:'请选择商品分类'}],
	marea:[{reg:collections_regs.every,error:'请选择商品所在地'}],
	number:[{reg:collections_regs.num,error:'请填写数字'}],
	empty:[{reg:collections_regs.every,error:'填写有误'}],
	dian:[{reg:collections_regs.empty,error:'！请填写点数'},
			{reg:collections_regs.gt100,error:'！符点必须大于100点'},
			{reg:collections_regs.lt300,error:'！最多可添加300符点'}]
}




	
function regForm(list)
{
	var $list = $(list),len = $list.length,_reg = null,_err = null;
	var res = [];
	for(var i=0;i<len;i++)
	{
		var $this = $list.eq(i),j=0;
		var tempreg = collections[$this.attr("reg")];
		//console.log(tempreg);
		for(;j<tempreg.length;j++){
			_reg = tempreg[j].reg;
			_err = tempreg[j].error;

			if(Object.prototype.toString.apply(_reg) == '[object Function]'){
				if(!_reg.call(null,$this.val()||'')){
					errTip.show($this,_err);
					res.push({index:i,err:_err});
				}else{
					errTip.hide($this);	
				}
			}else{
				if($this.attr("reg")=='url' && $this.val()){
					var _trade = $this.attr("trade");
					// console.log(_trade);
					$.ajax({    
						type: "POST",
						url: "/trade/get_goods_item",
						data : "url="+encodeURIComponent($this.val())+"&trade="+_trade,
						dataType:"json",
						async:false,
						success: function(tre) {
							if(tre.error){
								errTip.show($this,tre.msg);
								res.push({index:i,err:tre.msg});
								$this.siblings('.J_ITEM_INPUT').val('');
							}else{
								$this.siblings('.J_ITEM_INPUT').val(tre.item_id);
								errTip.hide($this);	
							}
						}
					});
				}else if(!_reg.test($this.val())){
					errTip.show($this,_err)
					res.push({index:i,err:_err});
				}else{
					errTip.hide($this);		
				}
			}
		}
	}

	return res;			
}


function regFormRerun($regList,_re){
	for(var i = 0;i<_re.length;i++){
		errTip.show($regList.eq(_re[i].index),_re[i].err);
	}	
}


$(function(){



	$('.J_compare_for').each(function(i){
		var $this = $(this),_reg = $this.attr('regtype'),_regfor = $this.attr('regfor');
		var $regfor = $(_regfor);
		$this.bind('change',function(){
			$regfor.attr('reg',_reg);
			$regfor.bind('keyup blur change',function(){
				regForm($regfor);
			});
		});
		$this.parents('div').bind('click',function(){
			if(!$this.is(':checked')){
				$regfor.removeAttr('reg');
				errTip.hide($regfor);
				$regfor.unbind();
			}
		});
	});




	$(".J_FIVE_CONTENT *[reg]").live('keyup blur change',function(){
		regForm($(this));
	});

	
	$('.publish-checklist .por-icon').click(function(){
		$(this).addClass('active').siblings().removeClass('active');	
	});
	
	$('.publish-st4-box li').click(function(){
		$(this).addClass('active').siblings().removeClass('active');	
	});
	
	function slideUpJMoreMater($this){
		var $regList = $this.find("*[reg]");
	     var _re = regForm($regList);
		 //console.log(_re);
	     if(_re.length>0)
	     {
		   return false;
	     }	
		$this.addClass('isconfirm');
		$this.find('.J_URL').text($this.find('.J_URL_INPUT').val());
		$this.find('.J_URL').attr('title',$this.find('.J_URL_INPUT').val());
		$this.find('.J_MONEY').text($this.find('.J_MONEY_INPUT').val());
		
		return true;
		
	}
	
	$('.J_up_JBUiLD').live('click',function(){
		var $par = $(this).parents('.J_fcopy');	
		slideUpJMoreMater($par);
	});
	
	
	var $insert = $('.J_fcopy_bak'),_addProLimit = parseInt($('.J_limit').attr('data-enable')||0);
	$('.J_publish_st5_add').click(function(){
		var $par = $(this).parent();
		var $cp = $par.prev('.J_fcopy');
		if(!slideUpJMoreMater($cp)){
			return;	
		}
	
		
		if($('.J_fcopy').length>=_addProLimit){return false;}
		var $clone = $insert.clone(true).removeClass('isconfirm J_fcopy_bak').removeAttr('style').addClass('J_fcopy');
		$clone.find('.J_nums').text($('.J_fcopy').length+1);
		$clone.insertBefore($par);
		
		$('.J_limit').text(_addProLimit-$('.J_fcopy').length);
	});
	
	$('.J_del').click(function(){
		$(this).parents('.J_fcopy').remove();	
		$('.J_limit').text(_addProLimit-$('.J_fcopy').length);
	});
	
	
	$('.J_reset').click(function(){
		$(this).parents('.J_fcopy').removeClass('isconfirm');	
	});
	
		
	$('.J_add_keyword').click(function(){
		var $par = $(this).parent();
		
		var $insert_keyword = $par.prev();
		var $regList = $insert_keyword.find("*[reg]");
	     var _re = regForm($regList);
	    if(_re.length>0)
	    {
		   regFormRerun($regList,_re);
		   return false;
	    }	
		 
		
		var $clone = $('<input type="text" class="w_5add" value="" reg="newmtype" />');
		$par.prev().append($clone);
		
		if($par.prev().find('*[reg]').length>4){
			$par.hide();
			return false;	
		}
	});

	
	
	
	
	var $J_allCC = $('.J_allCC');
	
	
	$('.J_FV_STATE label').click(function(){
		var $this = $(this),$par = $this.parent();
		if($('.J_FV_STATE').find('input:checked').length>0){
			$this.siblings().find('input').removeAttr('checked');
			var _zk = $this.find('input').attr('data-zk')||0;
			var _enable = $this.find('input').attr('data-enable')||0;
			if(_enable==0){
				_enable = $('.data-enable').val()||0;	
			}
			$('.J_total').text(_enable);
			$('.taocan_number').val(_enable);
			$('.J_rqf_zk').text(_zk);
			var _gy = parseFloat($('.J_rqf_count').text());
			$('.J_rqf_total').text((_enable*_zk*_gy).toFixed(2));
			$par.addClass('active').siblings().removeClass('active');	
		}else{
			$this.find('input').attr('checked','checked');
		}

	});
	$('.data-enable').live('blur',function(){
		var $this = $(this);										 									 
		if($this.val()<3){
			$this.val(3);		
		}
		if($this.val()>500){
			$this.val(500);		
		}
		_enable = $this.val();
		$('.J_total').text(_enable);
		$('.taocan_number').val(_enable);
		var _gy = parseFloat($('.J_rqf_count').text());
		$('.J_rqf_total').text((_enable*_gy).toFixed(2));
		if(!$('.custom:checked').length>0){
			$('.J_FV_STATE').find('input:checked').removeAttr('checked');
			$('.custom').attr('checked','checked');
		}
	});

	$('.data-enable').live('keyup',function(){
		var $this = $(this);										 									 
		if($this.val()>500){
			$this.val(500);		
		}
		_enable = $this.val();
		$('.J_total').text(_enable);
		$('.taocan_number').val(_enable);
		var _gy = parseFloat($('.J_rqf_count').text());
		$('.J_rqf_total').text((_enable*_gy).toFixed(2));
		if(!$('.custom:checked').length>0){
			$('.J_FV_STATE').find('input:checked').removeAttr('checked');
			$('.custom').attr('checked','checked');
		}
	});
	
																	 
	
	//$('.J_FV_STATE label input:checked').parent().trigger('click');
	
	$("input[name='issue_phone']").live('keyup blur change',function(){														 
		if($(this).val()>0){
			$(".ns-task-checking-area").removeClass('ns-task-60');
			$(".ns-task-checking-area p").eq(1).show();
			$(".J_rqf_fb_dan").text($(this).val());	
			$(".J_rqf_fb_total").text(($(this).val()*$('.J_rqf_fb_count').text()).toFixed(2));	
		}else{
			$(".ns-task-checking-area").addClass('ns-task-60');
			$(".ns-task-checking-area p").eq(1).hide();
		}
	});
	$("input[name='issue_phone']").trigger('blur');
	
	$J_allCC.each(function(){
		var _val = $.trim($(this).val()),$this = $(this);
		if($.trim(_val)==''){
			$this.val(0);	
		}
	});
	
	$J_allCC/*.bind('keyup blur',function(){
		var _enable = parseInt($('.tc-name:checked').attr('data-enable'));
		var _val = $.trim($(this).val()),$this = $(this);
		if($.trim(_val)==''){
			$this.val(0);	
		}
		$this.val(parseInt($this.val()));	
		var _total = 0,_stop = -1,$_other;
		for(var i=0;i<$J_allCC.length;i++){
			_total = _total + parseInt($J_allCC.eq(i).val());
			if(_total>_enable){
				_stop = i;
				break;	
			}
		}
		
		if(_stop!=-1){
			_total = 0;
			for(var j=0;j<$J_allCC.length;j++){
				if(j==_stop){
					break;
				}
				_total = _total + parseInt($J_allCC.eq(j).val());	
			}
			$this.val(_enable-_total)
			$('.J_allCC:gt('+_stop+')').val(0);
		}
		
		if(_total!=_enable){
			$('.J_regTotal').val('');	
		}else{
			$('.J_regTotal').val('ok');
		}
		regForm($('.J_regTotal'));
				
	})*/.bind('keyup blur',function(){
		
		var _enable = parseInt($('.tc-name:checked').attr('data-enable'));
		if(_enable==0){
			_enable = $('.data-enable').val();	
		}
		var price = 0;
		$('.J_allCC').each(function(){
		  price+=Number($(this).val())||0;
		});
		if(price==_enable){
		   errTip.hide($('.J_allCC:last'));
		}
				
	}).keydown(function(e){
		//alert(e.keyCode);
		if((e.keyCode > 95 && e.keyCode < 106) ||(e.keyCode>47 && e.keyCode <58) || e.keyCode==46 || e.keyCode==8 || e.keyCode==39 || e.keyCode==37){
			if(e.keyCode==46 || e.keyCode==8 || e.keyCode==39 || e.keyCode==37){}else{
				
			}					
		}else{
			return false;	
		}
	});
	
	
	$('.J_showShopList').toggle(function(){
		$('.J_shop_list').css({display:'block'});
	},function(){			
		$('.J_shop_list').css({display:'none'});
	});
	$('.J_tabs li:not(.not_li)').click(function(){
		var i = $(this).index();
		$(this).addClass('active').siblings().removeClass('active');
		if(i==0){
			$('input[name="trade_number"]').val(1);
			$('.J_addMOREBOX').fadeOut(0);	
			$('.J_fcopy').eq(0).siblings('.J_fcopy').hide();
			$('.J_fcopy').eq(0).removeClass('isconfirm');
			$('.J_fcopy').eq(0).find('.J_addMOREBOX').fadeOut(0);
			$('.J_fcopy').each(function(k){
				if(k>0)$('.J_fcopy').eq(k).hide();
			});
			
		}else{
			$('input[name="trade_number"]').val(2);
			$('.J_addMOREBOX').fadeIn(200);
			$('.J_fcopy').eq(0).siblings('.J_fcopy').show();
			if($('.J_fcopy').length>1){
				$('.J_fcopy').last().siblings('.J_fcopy').addClass('isconfirm');
			}
		}	
		
	});
	
	
/*	$('.J_NF_TAB div').click(function(){
		var $this = $(this),_index = $this.index();;
		$('.J_NF_TAB_CON div').eq(_index).css({display:'block'}).siblings().css({display:'none'});
	});	
	$('.J_NF_TAB div').eq(0).trigger('click');*/
	
	
	
    //添加评价
    $('#J-comments-add').click(function(event) {
        //$('.publish-st5-box-inner-input').find('.set_comments_txt').each(function(){
			
        //});
		var _length = $('.publish-st5-box-inner-input').find('.set_comments_txt').length+1;
        var $clone = $('.set_comments_txt_bak').find('p');
		$clone.find('em').text(_length);
        $('.publish-st5-box-inner-input').append($clone.clone(true));
        if($('.publish-st5-box-inner-input').find('.set_comments_txt').length==10) $(this).parent().hide();
    });
	
    $('.J-comments-del').click(function(event) {
		$(this).parent().remove();
        if($('.publish-st5-box-inner-input').find('.set_comments_txt').length<10) $('#J-comments-add').parent().show();
    });
	
	
});