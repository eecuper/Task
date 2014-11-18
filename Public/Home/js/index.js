$(document).ready(function(){
	$("#slider3").Xslider({
		affect:'fade',
		ctag: 'div'
	});
});

var i =0;
function login(){
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "userreg/login",
			//data: "username=" + $('#username').val() + "&password=" + $('#password').val(),
			data:{"username":document.getElementById("username").value,"password":document.getElementById("password").value},
			success: function(login){
				//三次登陆信息错误显示验证码
				if(login.error == 1){
					i = i+1;
					if(i == 3){
						document.getElementById('code').style.display="";
						document.getElementById('button').style.display="none";
						document.getElementById('Button1').style.display="";
					}
				}
				//登陆成功
				if(login.error == 0) {
					parent.document.location.href = login.url; //如果登录成功则跳到管理界面
				}
				//登陆错误，信息提示
				else{
					document.getElementById("login-tpis").style.display="";
					document.getElementById('login-tpis').innerHTML =login.msg;
				}
				
			}
		})
}


//登陆首页图片切换
(function($){
	$.fn.Xslider = function(options){var settings ={
			affect: 'scrollx', //效果  有scrollx|scrolly|fade|none
			speed: 1200, //动画速度
			space: 5000, //时间间隔
			auto: true, //自动滚动
			trigger: 'mouseover', //触发事件 注意用mouseover代替hover
			conbox: '.conbox', //内容容器id或class
			ctag: 'a', //内容标签 默认为<a>
			switcher: '.switcher', //切换触发器id或class
			stag: 'a', //切换器标签 默认为a
			current:'cur', //当前切换器样式名称
			rand:false //是否随机指定默认幻灯图片
		};
		settings = $.extend({}, settings, options);
		var index = 1;
		var last_index = 0;
		var $conbox = $(this).find(settings.conbox),$contents = $conbox.find(settings.ctag);
		var $switcher = $(this).find(settings.switcher),$stag = $switcher.find(settings.stag);
		if(settings.rand) {index = Math.floor(Math.random()*$contents.length);slide();}
		if(settings.affect == 'fade'){$.each($contents,function(k, v){(k === 0) ? $(this).css({'position':'absolute','z-index':9}):$(this).css({'position':'absolute','z-index':1,'opacity':0});
			});
		}
		function slide(){if (index >= $contents.length) index = 0;
			$stag.removeClass(settings.current).eq(index).addClass(settings.current);
			switch(settings.affect){case 'scrollx':
					$conbox.width($contents.length*$contents.width());
					$conbox.stop().animate({left:-$contents.width()*index},settings.speed);
					break;
				case 'scrolly':
					$contents.css({display:'block'});
					$conbox.stop().animate({top:-$contents.height()*index+'px'},settings.speed);
					break;
				case 'fade':
					$contents.eq(last_index).stop().animate({'opacity': 0}, settings.speed/2).css('z-index',1)
							 .end()
							 .eq(index).css('z-index',9).stop().animate({'opacity': 1}, settings.speed/2)
					break;
				case 'none':
					$contents.hide().eq(index).show();
					break;
			}
			last_index = index;
			index++;
		};
		if(settings.auto) var Timer = setInterval(slide, settings.space);
		$stag.bind(settings.trigger,function(){_pause()
			index = $(this).index();
			slide();
			_continue()
		});
		$conbox.hover(_pause,_continue);
		function _pause(){
			clearInterval(Timer);
		}
		function _continue(){
			if(settings.auto)Timer = setInterval(slide, settings.space);
		}	
	}
})(jQuery);

//验证码
var code;
function createCode(){ 
	code = new Array();
	var codeLength = 4;//验证码的长度
	var checkCode = document.getElementById("checkCode");
	checkCode.value = "";
	
	var selectChar = new Array(2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z');
	
	for(var i=0;i<codeLength;i++) {
	   var charIndex = Math.floor(Math.random()*32);
	   code +=selectChar[charIndex];
	}
	if(code.length != codeLength){
	   createCode();
	}
	checkCode.value = code;
}

function validate () {
	var inputCode = document.getElementById("input1").value.toUpperCase();
	
	if(inputCode.length <=0) {
	   document.getElementById("login-tpis").style.display="";
	   document.getElementById("login-tpis").innerHTML="请输入验证码";
	   return false;
	}
	else if(inputCode != code ){
	   document.getElementById("login-tpis").style.display="";
	   document.getElementById("login-tpis").innerHTML="验证码输入错误，请重新输入！";
	   createCode();
	   return false;
	}
	else {
	   login();
	}
}