$(function(){
    //第一步   
    $('.register-business-form').Validform({
        btncls : '.loginbtn',
        ifok : true,
        disbtncls : 'disabled',
        regback : {
            username : function(o,v,c){
             $.ajax({
                 type: "POST",
                 url: "/userreg/checkuser",
                 data:"username="+v,
                 dataType:"json",
                 async:true,
                 success: function(re) {
                    if(re.error){
                     rqf.tip.showError(re.msg,o.parents('.inp'));
                     o.removeAttr('pass'); 
                    }else{
                        c(o);
                    } 
                }                            
             });

            },
            email : function(o,v,c){
            $.ajax({
                 type: "POST",
                 url: "/userreg/checkuser",
                 data:"useremail="+v,
                 dataType:"json",
                 async:true,
                 success: function(re) {
                    if(re.error){
                     rqf.tip.showError(re.msg,o.parents('.inp'));
                     o.removeAttr('pass'); 
                    }else{
                         c(o);
                    }                            
                }                           
             });
            },
			/*
            qq : function(o,v,c){
            $.ajax({    
                 type: "POST",
                 url: "/userreg/checkuser",
                 data:"userqq="+v,
                 dataType:"json",
                 async:true,
                 success: function(re) {
                    if(re.error){
                     rqf.tip.showError(re.msg,o.parents('.inp'));
                     o.removeAttr('pass'); 
                    }else{
                         c(o);
                    }  
                }                           
             });
            },
            mobile : function(o,v,c){
            $.ajax({    
                 type: "POST",
                 url: "/userreg/checkuser",
                 data:"usertel="+v,
                 dataType:"json",
                 async:true,
                 success: function(re) {
                    if(re.error){
                     rqf.tip.showError(re.msg,o.parents('.inp'));
                     o.removeAttr('pass'); 
                    }else{
                         c(o);
                    }  
                }                           
             });
            },
			*/
            recommend_chk : function(o,v) {
                if(!v){
                    $('#submit_btn').addClass('disabled');

                }else{
                    $('#submit_btn').removeClass('disabled'); 
                }
            },
            code : function(o,v,c){
              if($('input[name="user_type"]:checked').val()=="1"){
                $('#rqftype').find('input').removeAttr('ispass');
                $.ajax({
                     type: "POST",
                     url: "/userreg/checkcode",
                     data:"code="+v,
                     dataType:"json",
                     async:true,
                     success: function(re) {
                        if(re.error){
                         rqf.tip.showError(re.msg,o.parents('.inp'));
                         o.removeAttr('pass'); 
                        }else{
                             c(o);
                        }                            
                    }                           
                 });
              }else{
                $('#rqftype').find('input').attr('ispass', '1');
              } 
            },
            usertype  :  function(o,v){
              if(v){
                var e = $('input[name='+o.attr('name')+']:checked').val();
                if(e==1){
                  $('#rqftype').show();
                  $('#rqftype').find('input').removeAttr('ispass');
                }else{
                  $('#rqftype').hide();
                  $('#rqftype').find('input').attr('ispass', '1');
				  $('#code').val(''); 
                }
              }else{
                 rqf.tip.showError('请先选择角色！',o.parents('.inp'));
                 o.removeAttr('pass');             
              }
            }                              
        },
        callback : function(){
            //return false;
        }                   
    });
    //第二步
     function connection(o){
          var obj = o.parents('ul').find('input');
          var a = o,
              b = obj.filter(function(index) {
                   return index!==obj.index(a);
                  }),
              $a = a.val(),
              $b = b.val();
          if($a&&$b){
              obj.attr('canempty', true);
              obj.attr('pass', '1');
          }else if(!$a&&!$b){
              obj.parents('.inp').find('span.error').remove();
              obj.attr('canempty', true);
              obj.attr('pass', '1');
          }else{
              if($a!==''){
                b.removeAttr('canempty');
                b.removeAttr('pass');
                rqf.tip.showError(b.attr('emptyerr'),b.parents('.inp'))
              }else{
                a.removeAttr('pass');
                a.removeAttr('canempty');
              }          
          };
      };    
    $('.register-tabs-two li').click(function() {
       if($(this).hasClass('cur')) return;
       var i = $(this).index();
       $('.register-tabs-two li').removeClass('cur');
       $(this).addClass('cur');
       $('.register-Binding-shop').find('.register-Binding-shop-list').hide();
       $('.register-Binding-shop').find('.register-Binding-shop-list').eq(i).show();
    });

      $('.register-Binding-shop').Validform({
        btncls : '.Bindingshopbtn', 
        regback : {
          shop_id :function(o,v){
              connection(o);
          },
          shop_name :function(o,v){
              connection(o);   
          }      
        },          
        callback : function(){
            var l = 0;
            $('.register-Binding-shop-list').each(function(){
                if($(this).find('input').eq(0).val()!==''&&$(this).find('input').eq(1).val()!==''){
                    l++;
                }
            });           
            if(l<1){
                alert('请至少绑定1个平台');
            }else{
                $("#binding").submit();
            }
        }   
      });

    // resizeValidform();
    // var _html =$('.register-Binding-shop-list').html(); 
    // var maxLength = 3;
    // var n = 1;
    // $('.business-bind-btn a.nbtn').live('click', function() {
    //     if($(this).hasClass('disabled')) return;
    //     if(n+1<=maxLength){
    //         $('.register-Binding-shop-list').append(_html); 
    //         $(".Bindingshopbtn").unbind("click");
    //         resizeValidform();
    //         n = n+1;
    //         if(n==maxLength){
    //             $(this).addClass('disabled').show();
    //         }
    //     }
    // });
    //第三步
    $('b.arrange').click(function() {
        if($('.register-vip').is(':hidden')){
            $('.register-vip').show();
            $(this).html('收起');
        }else{
            $('.register-vip').hide();
            $(this).html('展开');
        }
    });  
    $('#openviptip').click(function(){
        var $dt = $(document).scrollTop();
        var $dh = $(window).height();
        var $bh=  $('.business-popup-rank').outerHeight();
        if($bh>=$dh){
            itop = $dt+10;
        }else{
            itop = $dt+ ($dh-$bh)/2;
        }
        $('.business-popup-rank').css({top:itop}).fadeIn(200); 
        $('.business-popup-under').css({height:$(document).height()}).fadeIn(200);               
    }); 
    $('.popup-close').click(function() {
        $('.business-popup-under').fadeOut(200);
        $('.business-popup-rank').fadeOut(200);   
    });     
})  