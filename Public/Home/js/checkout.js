
$(function(){
  
    $('.step-Message-input-box input,#J-step3-add_bak input').blur(function(event) {
        if($(this).val()==''){
            errTip.show($(this),'留言不能为空！');
        }else{
             errTip.hide($(this));
        }        
    });
    //添加留言
    $('#J-step3-add').click(function(event) {
        $('.step-Message-input-box').find('.step-Message-p').each(function(){
            if($(this).find('input').val()==''){
                errTip.show($(this).find('input'),'留言不能为空！');
                return false;
            }else{
                 errTip.hide($(this).find('input'));
            }

        });
        if($('.step-Message-input-box').find('span.error').length>0) return;
        if($('.step-Message-input-box').find('input').length>3) return;
        var $clone = $('#J-step3-add_bak').find('.step-Message-p');
        $('.step-Message-input-box').append($clone.clone(true));
        if($('.step-Message-input-box').find('input').length==Messagenum) $(this).parent().hide();
    });
	
    // 添加多个关键字
    $('#J_stepKey-box-add').click(function(event) {
		var StepKeynum = $('.stepKey-box-TB .stepKey-box-list').length;
        if(StepKeynum>=maxStepKeynum) return;
        var $clone = $('#J_stepKey-box-add_bak').find('.stepKey-box-list');
        $('.stepKey-box-TB .stepKey-box-list-inner').append($clone.clone(true));
        //StepKeynum++;
        $('.stepKey-box-TB .stepKey-box-list-inner').find('.stepKey-box-list').eq(1).find('em.xunhao').text(2);
        if((StepKeynum+1)>=maxStepKeynum) $(this).parents('.stepKey-box-add').hide();
    });
	
    // 添加天猫多个关键字
    $('#J_stepKey-box-add-tm').click(function(event) {
		var StepKeynum = $('.stepKey-box-TM .stepKey-box-list').length;									  
        if(StepKeynum==maxStepKeynum) return;
        var $clone = $('#J_stepKey-box-add-tm_bak').find('.stepKey-box-list');
        $('.stepKey-box-TM .stepKey-box-list-inner').append($clone.clone(true));
        //StepKeynum++;
        $('.stepKey-box-TM .stepKey-box-list-inner').find('.stepKey-box-list').eq(1).find('em.xunhao').text(2);
        if((StepKeynum+1)>=maxStepKeynum) $(this).parents('.stepKey-box-add').hide();
    });
	
    //天猫搜索	
    $('.stepKey-TM label').click(function(){
        var $inp = $(this).find('input');
			if($inp.is(':checked')){
				var $clone = $('#J_stepKey-box-add-tm_bak').find('.stepKey-box-list');
				$('.stepKey-box-TM .stepKey-box-list-inner').append($clone.clone(true));
				$('.stepKey-box-TM .stepKey-box-list-inner').find('.stepKey-box-list').eq(0).find('em.xunhao').text(1);
				$('.stepKey-box-TM .stepKey-box-list-inner').find('.stepKey-box-list').eq(0).find('.J_del').remove();
				//天猫主图
				$('.stepKey-box-TM .stepKey-title-type').after($('.show-img-bak').html());
				$('.J_upfile_10_cf').val('');
				$('.stepKey-box-TM').show();
			}else{
				$('.J_upfile_10_cf').val('ok');
				$('.stepKey-box-TM').hide();
				$('.stepKey-box-TM .stepKey-box-list-inner').find('.stepKey-box-list').remove();
				$('.stepKey-box-TM  .show-img').remove();
			}
    });
	
    //淘宝搜索
    $('.stepKey-TB label').click(function(){
        var $inp = $(this).find('input');
			if($inp.is(':checked')){
				var $clone = $('#J_stepKey-box-add_bak').find('.stepKey-box-list');
				$('.stepKey-box-TB .stepKey-box-list-inner').append($clone.clone(true));
				$('.stepKey-box-TB .stepKey-box-list-inner').find('.stepKey-box-list').eq(0).find('em.xunhao').text(1);
				$('.stepKey-box-TB .stepKey-box-list-inner').find('.J_del').remove();
				$('.J_upfile_1_cf').val('');
				$('.stepKey-box-TB').show();
			}else{
				$('.J_upfile_1_cf').val('ok');
				$('.stepKey-box-TB').hide();
				$('.stepKey-box-TB .stepKey-box-list-inner').find('.stepKey-box-list').remove();
				$('.stepKey-box-TB .show-img').remove();
			}
    });
	
    //删除关键词
    $('.J_del').click(function(event) {
        $('.stepKey-box-add').show();
        //StepKeynum--;
        $(this).parents('.stepKey-box-list').remove();
        if($('.step-complete-main-1').find('.stepKey-box-list').length==2){
           $('.step-complete-main-1').find('.stepKey-box-list').eq(1).find('em.xunhao').text(2);          
        }
    }); 


    //
    $('#J_setprice').click(function(){
        var $par = $('.J_setprice_show');
        if($(this).hasClass('on')){
            $par.hide();
			$par.find('input[name="item_price[]"]').val($('input[name="item_original_price[]"]').val());
			$par.find('input[name="item_price[]"]').removeAttr('reg');
            $(this).removeClass('on');
        }else{
			$par.find('input[name="item_price[]"]').attr('reg','price');
            $(this).addClass('on')
            $par.show();
        }
    });
    //添加商品
    $('#J_step-list-add').click(function(event) {
        if($('#step-2').hasClass('step-complete-cur')){
           alert('请先确认置商品收取运费的方式！');
           return false;
        }else if($('#step-3').hasClass('step-complete-cur')){
           alert('请先确认指定留言！');
           return false;
        }else{
          $('#step-1').addClass('step-complete-cur');
        }; 
        if(Listnum==maxListnum) return;
        var $clone = $('#J_step-list-add_bak').find('.step-complete-list');
        $('.step-complete-main-1').append($clone.clone(true));
        $('.step-complete-main-1').find('.step-complete-list').eq(1).find('.business-notice').show();
        $('.step-complete-main-1').find('.step-complete-list').eq(1).find('em.xunhao').text(2);
        Listnum++;
        if(Listnum==maxListnum) $(this).parents('.step-list-add').hide();
    });
    //删除商品
    $('.J_complete-del').click(function(event) {
        $('.step-list-add').show();
        Listnum--;
        $(this).parents('.step-complete-list').remove();
        //console.log($('.step-complete-list').length);
        if($('.step-complete-main-1').find('.step-complete-list').length==2){
           $('.step-complete-main-1').find('.step-complete-list').eq(1).find('.business-notice').show();
           $('.step-complete-main-1').find('.step-complete-list').eq(1).find('em.xunhao').text(2);          
        }
    });
	
    //删除留言
    $('.J_Message-del').click(function(event) {
		$('.step-Message-add').show();
    	$(this).parents('p').remove();    
    });
	
	
    //修改商品
    $('#J_reset').click(function(event) {
		/*						 
        if($('#step-2').hasClass('step-complete-cur')){
           alert('请先确认置商品收取运费的方式！');
           return false;
        }else if($('#step-3').hasClass('step-complete-cur')){
           alert('请先确认指定留言！');
           return false;
        }else{*/
          $('#step-1').addClass('step-complete-cur');
		  $('#step-2').removeClass('step-complete-cur')
		  $('#step-3').removeClass('step-complete-cur')
		  $('.J_FIVE_NEXT').addClass('disabled');
       // } 
    });
	
    //修改包邮
    $('#J_reset_step-2').click(function(event) {
          $('#step-1').removeClass('step-complete-cur');
		  $('#step-2').addClass('step-complete-cur')
		  $('#step-3').removeClass('step-complete-cur')
		  $('.J_FIVE_NEXT').addClass('disabled');
		  $('#J_reset_step-2').hide();
       // } 
    });
	
	//修改包邮
    $('#J_reset_step-3').click(function(event) {
          $('#step-1').removeClass('step-complete-cur');
		  $('#step-2').removeClass('step-complete-cur')
		  $('#step-3').addClass('step-complete-cur')
		  $('.J_FIVE_NEXT').addClass('disabled');
		  $('#J_reset_step-3').hide();
       // } 
    });
	
    //第一步提交信息
    $('#step-1').find('a.checkout-btn').click(function(event) {
		$('.stepKey-box-list').find('input.J_typelist_input').trigger('blur');										   											   
		var $regList = $(".J_FIVE_CONTENT *[reg]");
		var _re = regForm($regList);
		//alert($('input[name="search"]:checked').length);
		
		if($('input[name="search"]:checked').length==0){
			alert('请选择想要的搜索类型');
			return false;	
		}
		if(_re.length>0)
		{
			regFormRerun($regList,_re)
			return false;
		}
		/*
		$(".keywords_info").each(function(){
														 
			var _category = [];
			_category.push($(this).find('input[name="key_words[]"]').val());
			$(this).find('.J_typelist_input').each(function(i){
				if($(this).val()){										
					_category.push($(this).val());	
				}
			});
			if(_category){
				$(this).find(".item_category").val($.toJSON(_category));
			}
			
			console.log($.toJSON(_category));
			//return false;
		});*/

          //验证成功赋值
          $('#step-1').removeClass('step-complete-cur');
          var html = tmhtml = '';
		  var _key_cate = '';
		  
          $('#step-1').find('.stepKey-box-TB .keywords_info').each(function() {
              var $this = $(this);
              $this.find('.show-num').text($(this).index()+1);
              //$this.find('span.show-work').text($this.find('input[name="key_words[]"]').val());
				var _category = [];
				_key_words = $(this).find('input[name="key_words[]"]').val();
				_category.push(_key_words);
				$(this).find('.J_typelist_input').each(function(i){
					if($(this).val()){										
						_category.push($(this).val());
						_key_cate += (_key_cate)?' | '+$(this).val():$(this).val();
					}
				});
				if(_category){
					$(this).find(".item_category").val($.toJSON(_category));
				}
				
				$this.find('.step-table em.show-work').text(_key_words);
				$this.find('.step-table em.show-cate').text(_key_cate);
              	html += '<div class="step-table clearfix">'+$this.find('.step-table').html()+'</div>';
			  	_key_cate = '';
          });
		  
		  if(html){
		  		$('.step-table-box-mian').html(html);
				$('.show-tb').show();
		  }else{
				$('.show-tb').hide();  
		  }
		  
          $('#step-1').find('.stepKey-box-TM .keywords_info').each(function() {
              var $this = $(this);
              $this.find('.show-num').text($(this).index()+1);
              //$this.find('span.show-work').text($this.find('input[name="key_words[]"]').val());
				var _category = [];
				_key_words = $(this).find('input[name="key_words[]"]').val();
				_category.push(_key_words);
				$(this).find('.J_typelist_input').each(function(i){
					if($(this).val()){										
						_category.push($(this).val());
						_key_cate += (_key_cate)?' | '+$(this).val():$(this).val();
					}
				});
				if(_category){
					$(this).find(".item_category").val($.toJSON(_category));
				}
				
				$this.find('.step-table em.show-work').text(_key_words);
				$this.find('.step-table em.show-cate').text(_key_cate);
              	tmhtml += '<div class="step-table clearfix">'+$this.find('.step-table').html()+'</div>';
			  	_key_cate = '';
          });
		  
		  if(tmhtml){
		  		$('.step-table-box-mian-tm').html(tmhtml);
				$('.show-tm').show();
		  }else{
				$('.show-tm').hide();  
		  }
          
          $('.step-complete-list').each(function() {
              var $this = $(this);
              $this.find('span.show-title').text($this.find('input[name="item_title[]"]').val());
              $this.find('span.spansize em').text($this.find('input[name="item_color[]"]').val()+(($this.find('input[name="item_size[]"]').val())?' | ':'')+$this.find('input[name="item_size[]"]').val());
			  if($this.find('input[name="item_keyword[]"]').val()){
			      $this.find('.step-table em.show-work').text($this.find('input[name="item_keyword[]"]').val());
			  }
			  if($this.find('input[name="item_price[]"]').val()){
                  $this.find('span.spanprice b.price').text($this.find('input[name="item_price[]"]').val());
			  }
			  /*
			  if($this.find('input[name="item_original_price[]"]').val()){
                  $this.find('span.spanprice b.price').text($this.find('input[name="item_original_price[]"]').val());
			  }*/
              $this.find('span.spannum b.num').text($this.find('input[name="item_number[]"]').val());
			  
              $this.find('span.step-table-3 em.show-price-l').text($this.find('input[name="price_low[]"]').val());
              $this.find('span.step-table-3 em.show-price-h').text($this.find('input[name="price_high[]"]').val());
              $this.find('span.step-table-4 em.show-city').text($this.find('input[name="item_position[]"]').val());
              
          });

          $('#step-2').addClass('step-complete-cur');
		  $('.J_FIVE_NEXT').addClass('disabled');
		  
    });
    //第二步提交信息
    $('#step-2').find('a.checkout-btn').click(function(event) {
        if(!$('input.transport:checked').length){
            alert('请设置商品收取运费的方式');
        }else{
           $('#step-2').removeClass('step-complete-cur');
           $('#step-2').find('.step-complete-show').text($('input.transport:checked').parent('label').text());
           $('#step-3').addClass('step-complete-cur');
		   $('.J_FIVE_NEXT').addClass('disabled');
		   $('#J_reset_step-2').show();
        }
    });
    //第三步提交信息
    $('#step-3').find('a.checkout-btn').click(function(event) {
        if($('.step-Message-input-box').find('span.error').length>0) return;
		if($('#step-3').find('input[name="postscript[]"]').val()){
			_show_text = '';
			$('#step-3').find('input[name="postscript[]"]').each(function(){
				_show_text += (_show_text)?'<p>'+$(this).val()+'<p>':$(this).val();
			});
			$('#step-3').find('.step-complete-show').html(_show_text);
			$('#step-3').removeClass('step-complete-cur');
			$('.J_FIVE_NEXT').removeClass('disabled');
			$('#J_reset_step-3').show();
		}
        
		

    }); 
	
	/*
	//关键词
	$('.J_typelist').each(function(){
		var $this = $(this),$inputs = $this.find('.J_typelist_input'),$hidden = $this.find('.J_typelist_hidden');
		//console.log($this);	
		$inputs.bind('keyup blur',function(){
			var _right = 0;
			for(var i = 0;i<$inputs.length;i++){
				if($.trim($inputs.eq(i).val()) != ''){
					_right ++;	
				}	
			}
			if(_right>1){
				$hidden.val('ok');
			}else{
				$hidden.val('');
			}
			regForm($hidden);
		});//.trigger('blur');		
	});
*/

	// alert('1');
	//商品金额校验
	// $('.J_price_low').bind('keyup blur change',function(){
	// 	var $this = $(this);
	// 	var gprice = $('.J_MONEY_INPUT').val();
	// 	if($this.val() >= gprice){
	// 		errTip.show($this,'最小金额不能大于商品金额');
	// 	}else{
	// 		errTip.hide($this);	
	// 	}	
	// });

	// $('.J_price_high').bind('keyup blur change',function(){
	// 	var $this = $(this);
	// 	var gprice = $('.J_MONEY_INPUT').val();
	// 	if($this.val() <= gprice){
	// 		errTip.show($this,'最大金额不能小于商品金额');
	// 	}else{
	// 		errTip.hide($this);	
	// 	}	
	// });
	$('.J_total_money').bind('keyup blur change',function(){
		var _total_money = 1;
		var $this = $(this).parent();
		$this.find('.J_total_money').each(function(){
			//console.log($(this).val());
			if($(this).val()){												   
				_total_money = _total_money * $(this).val();
			}
		})
		$this.find('.total_money em').text(_total_money.toFixed(2));														
	});
	
	
	
	//所在地
    $('ul.selected').click(function(e){
        e.stopPropagation();
        var $pr = $(this).parents('.sel-loc-box').find('.toselect');
        if($pr.is(':visible')){
            $pr.hide();
        }else{
            $pr.show();
        }

    });
    $('.toselect a').click(function(e){
        e.stopPropagation();
        e.preventDefault();
        var set = $(this).parents('.sel-loc-box').find('ul.selected a');
        var _val = $(this).text();
        set.html($(this).text())
        set.attr('data-value',_val);
		$(this).parents('.sel-loc-box').find('.position').val(_val);
		
		/*if($.trim(_val) !="所在地"){
			$(set.attr('checkfor')).val(_val);	
		}else{
			$(set.attr('checkfor')).val(_val);	
		}
		regForm($(set.attr('checkfor')));*/
        $('div.toselect').hide();
    });
    $(document).click(function(){
        $('div.toselect').hide();

    })
	
});
