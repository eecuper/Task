<?php
header('content-type: text/html; charset=utf-8');
error_reporting(0);
//隐藏警告信息
error_reporting(E_ALL^E_NOTICE);
//启动session
session_start();

//隐藏错误信息
//ini_set("display_errors", "Off");

/*
 * @Description 易宝支付B2C在线支付接口范例 
 * @V3.0
 * @Author rui.xin
 */
 
include 'yeepayCommon.php';	
	
#	只有支付成功时易宝支付才会通知商户.
##支付成功回调有两次，都会知到在线支付通请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	以上代码和变量不需要修改.
	 	
#	校验码正确.
if($bRet){
	if($r1_Code=="1"){
		
		#	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
		#	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.      	  	
			
		$r6_Order = $_REQUEST['r6_Order']; //商品编号
		$r0_Cmd		= $_REQUEST['r0_Cmd']; 
		$r1_Code	= $_REQUEST['r1_Code'];
		$r2_TrxId	= $_REQUEST['r2_TrxId'];
		$r3_Amt		= $_REQUEST['r3_Amt']; //金额
		$r4_Cur		= $_REQUEST['r4_Cur'];
		$r5_Pid		= $_REQUEST['r5_Pid']; //商品名称
		$r6_Order	= $_REQUEST['r6_Order'];
		$r7_Uid		= $_REQUEST['r7_Uid'];
		$r8_MP		= $_REQUEST['r8_MP']; //拓展信息
		$r9_BType	= $_REQUEST['r9_BType']; //返回类型

		//返回地址
		$re_url = "http://dpweb.juqu8.com/Pay/log/type_id/2";
		$arr = array('host'=>'localhost',
				      'user'=>'root',
				      'pwd' =>'ldyd',
				      'db' =>'rwxt'
	      );

		//获取数据库连接
		$con = mysql_connect($arr['host'],$arr['user'],$arr['pwd']);
		//选择指定目标库
	 	mysql_select_db($arr['db'], $con);


	 	if(empty($r6_Order)){
			echo '交易成功订单返回失败，请联系管理员';
			die;
		} 


		$sql = "insert into task_action_log (log,log_date) values ('调用".$r6_Order."',".time().")";
		mysql_query($sql);


		if($r9_BType=="1"){
				$sql = "insert into task_action_log (log,log_date) values ('返回商户点击调用".$r6_Order."',".time().")";
				mysql_query($sql);

				//判断重复工单
			 	$sql = "select * from task_charge_log where ext_id='".$r6_Order."'";
			 	$re = mysql_query($sql);
			 	$flag = 0;
			 	$flag = mysql_num_rows($re);
			 
 

				if($flag==0){

			 	 //更新用户押金
			 	 $sql = "update task_user_charge set cg_deposit=cg_deposit+".floatval($r3_Amt)." where user_id=".$r8_MP;
			 	 $type_name='押金充值';

				 //echo '$sql : '.$sql;
				 //更新金额
		 		 $up_db = mysql_query($sql);

				 if($up_db){

				 		//插入充值记录
				 	 	$sql="insert into task_charge_log 
					 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
					 	 						values 
					 	 						(3,".$r3_Amt.",".time().",".$r8_MP.",'充值单号:".$r6_Order."',".$r6_Order.",1)";

			 		 	$cz_log = mysql_query($sql);
			 		 	
				 		if($cz_log){
				 			echo '<center>';
						 	echo '<h2>充值【'.$r6_Order.'】支付交易成功</h2>';
							echo '<a href="javascript:window.close();">关闭窗口</a>';
							echo '<center>';
						}else{
							echo '<center>';
						 	echo '<h3>充值记录同步【'.$r6_Order.'】更新失败,请联系管理员</h3><br>';
						 	echo '<a href="javascript:window.close();">关闭窗口</a>';
						 	echo '</center>';
						}
				 }else{
				 	echo '<center>';
				 	echo '<h3>系统同步【'.$r6_Order.'】充值状态更新失败,请联系管理员</h3><br>';
				 	echo '<a href="javascript:window.close();">关闭窗口</a>';
				 	echo '</center>';
				 }

			}else{
				echo '<center>';
				echo '<h3>会话操作已经存在更新</h3>';
				echo '<a href="javascript:window.close();">关闭窗口</a>';
				echo '</center>';
			}
			die;
		}elseif($r9_BType=="2"){

				$sql = "insert into task_action_log (log,log_date) values ('服务端调用".$r6_Order."',".time().")";
				mysql_query($sql);

				//判断重复工单
			 	$sql = "select * from task_charge_log where ext_id='".$r6_Order."'";
			 	$flag = 0;
			 	$re = mysql_query($sql);
			 	$flag = mysql_num_rows($re);

				if($flag==0){

			 	 if($r5_Pid=='yjpay'){
				 	 //更新用户押金
				 	$sql = "update task_user_charge set cg_deposit=cg_deposit+".floatval($r3_Amt)." where user_id=".$r8_MP;
				 	$type_name='押金充值';

		 		 }else{
					//更新用户DB值
				 	$sql = "update task_user_charge set cg_amount=cg_amount+".floatval($r3_Amt)." where user_id=".$r8_MP;
				 	$type_name='DB充值';				 	 
		 		 }

				 //echo '$sql : '.$sql;
				 //更新金额
		 		 $up_db = mysql_query($sql);

				 if($up_db){
				 	 
					 	if($r5_Pid=='yjpay'){
					 		//插入充值记录
					 	 	$sql="insert into task_charge_log 
						 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
						 	 						values 
						 	 						(3,".$r3_Amt.",".time().",".$r8_MP.",'充值单号:".$r6_Order."',".$r6_Order.",1)";
				 		 }else{
						 	//插入充值记录
						 	$sql="insert into task_charge_log 
						 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
						 	 						values 
						 	 						(1,".$r3_Amt.",".time().",".$r8_MP.",'充值单号:".$r6_Order."',".$r6_Order.",1)";
				 		 }
			 		 	$cz_log = mysql_query($sql);
				 		if($cz_log){
				 			echo 'success';
				 			echo '<center>';
						 	echo '<h2>充值【'.$r6_Order.'】支付交易成功</h2>';
							echo '<a href="javascript:window.close();">关闭窗口</a>';
							echo '<center>';
						}else{
							echo '<center>';
						 	echo '<h3>充值记录同步【'.$r6_Order.'】更新失败,请联系管理员</h3><br>';
						 	echo '<a href="javascript:window.close();">关闭窗口</a>';
						 	echo '</center>';
						}
				 }else{
				 	echo '<center>';
				 	echo '<h3>系统同步【'.$r6_Order.'】充值状态更新失败,请联系管理员</h3><br>';
				 	echo '<a href="javascript:window.close();">关闭窗口</a>';
				 	echo '</center>';
				 }

			}else{
				echo '<center>';
				echo '<h3>会话操作已经存在更新</h3>';
				echo '<a href="javascript:window.close();">关闭窗口</a>';
				echo '</center>';
			}
		}

		//数据库连接关闭
		mysql_close($con);
	}
	
}else{
	echo "交易信息被篡改";
}
   
?>
<html>
<head>
<title>Return from YeePay Page</title>
</head>
<body>
</body>
</html>