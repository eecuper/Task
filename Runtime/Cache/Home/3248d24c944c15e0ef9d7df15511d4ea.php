<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="description" content="x">
<meta name="keywords" content="x">
<title>

商家登录

</title>


<link rel="stylesheet" href="/task/Public/static/validForm/css/style.css">


<link rel="stylesheet" href="/task/Public/Home/css/common.css">
<link rel="stylesheet" href="/task/Public/Home/css/release.css">
<link rel="stylesheet" href="/task/Public/Home/css/login.css">
<link rel="stylesheet" href="/task/Public/Home/css/publish.css">
<link rel="stylesheet" href="/task/Public/Home/css/business.css">

<script type="text/javascript" charset="utf-8" async src="/task/Public/Home/js/crmqq.php">
</script>
<script type="text/javascript" charset="utf-8" async src="/task/Public/Home/js/contains.js">
</script>
<script type="text/javascript" charset="utf-8" async src="/task/Public/Home/js/localStorage.js">
</script>
<script type="text/javascript" charset="utf-8" async src="/task/Public/Home/js/Panel.js">
</script>
<script language="javascript" src="/task/Public/Home/js/jquery-1.7.2.js">
</script>
<script language="javascript" src="/task/Public/Home/js/common.js">
</script>
<script language="javascript" src="/task/Public/Home/js/jquery.json-2.4.min.js">
</script>
<script language="javascript" src="/task/Public/Home/js/publish-task.js">
</script>
<script language="javascript" src="/task/Public/Home/js/checkout.js">
</script>
<script language="javascript" src="/task/Public/Home/js/user_main.js">
</script>

<script type="text/javascript" src="/task/Public/static/validForm/js/Validform_v5.3.2_min.js"></script>

<script type="text/javascript">
$(function(){
	$(".theForm").Validform({
		tiptype:2
	});
})
</script>

<link rel="stylesheet" href="/task/Public/Home/css/index.css">


</head>
<body>



<!-- 用户登录状态 -->
	<div class="wrap">
		 
	</div>
	<!-- 用户登录状态 -->
	
	<!-- top 开始 -->
        <div class="header">
            <div class="wrap">
                <a class="logo fl" href="#">
                    <!-- <img src="/task/Public/Home/images/logo.jpg"> -->
                </a>
                 
          </div>
    </div>
 	<!-- top 结束 -->






<form class="theForm" action="<?php echo U('User/login');?>" method="post">

<DIV class=renqifu_con>
	<DIV class=renqifu_login>
		<DIV class=imglist>
			<DIV id=slider3 class=slider>
				<DIV class=conbox>
					<DIV style="Z-INDEX: 9; POSITION: absolute; opacity: 1">
						<A href="#"><IMG alt=""
							src="/task/Public/Home/images/imglist1.jpg" width=631 height=329></A>
					</DIV>
				</DIV>
				<!--div class="switcher">
                       <a href="#" class="cur"></a>
                       <a class="" href="#"></a>
                       <a class="" href="#"></a>
                  </div-->
			</DIV>
		</DIV>
		
		<DIV class=login>
			<DIV class=logintop></DIV>
			<DIV class=logincen>
				<H3>商家登录</H3>
				<!-- 用户名或密码错误提示 -->
				<SPAN style="DISPLAY: none" id=login-tpis class=login-tpis></SPAN>
				<DIV class=login_input>
					<INPUT onKeyDown="javascript: enterPress(event);" id=username
						class="txt placebox" name=nc regname="usernameno"
						cname="one">
				</DIV>
				<DIV class=login_input>
					<INPUT onKeyDown="javascript: enterPress(event);" id=password
						class="txt placebox" name=pwd type=password
						regname="loginpasswordno" cname="one" emptyerr="密码不能为空"
						autocomplete="off">
				</DIV>
				<!-- <p id="dianjicishu"><p> -->
				<!-- 验证码 三次错误提示后需要验证码显示此验证码div 并且将button显示下面的隐藏上面的 -->
				<DIV style="DISPLAY: none" id=code class=code>
					<DIV class=code_input>
						<INPUT onKeyDown="javascript: enterPressCode(event);" id=input1>
					</DIV>
					<INPUT id=checkCode class=codes onclick=createCode() type=button>
				</DIV>
				<DIV class=login_button>
					<INPUT id=button class=loginbtn  name=button
						value=登录 type=submit>
					<!-- 需要验证码时换为此button  上面的button隐藏 -->
					<INPUT style="DISPLAY: none" id=Button1 ;
						name=button value=登录 type=submit>

					<DIV>
						<A href="<?php echo U('User/register');?>">注册账号</A>
						<P></P>
						<A href="#">忘记密码</A>
					</DIV>
				</DIV>
			</DIV>
			<DIV class=loginbott></DIV>
		</DIV>
	</DIV>
</DIV>
</form>





	<div class="footer">
		<div>
		<center>Copyright (c) 2014 juqu8.com Inc. All Rights.浙ICP备14016079号-1</center>	
		</div>
	</div>

</body>


<script type="text/javascript" charset="utf-8" async src="/task/Public/Home/js/index.js">
</script>

</html>