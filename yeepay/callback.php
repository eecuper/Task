<?php

error_reporting(0);
//���ؾ�����Ϣ
error_reporting(E_ALL^E_NOTICE);
//����session
session_start();

//���ش�����Ϣ
//ini_set("display_errors", "Off");

/*
 * @Description �ױ�֧��B2C����֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 
include 'yeepayCommon.php';	
	
#	ֻ��֧���ɹ�ʱ�ױ�֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪������֧��ͨ��������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	���ϴ���ͱ�������Ҫ�޸�.
	 	
#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){
		
		#	��Ҫ�ȽϷ��صĽ�����̼����ݿ��ж����Ľ���Ƿ���ȣ�ֻ����ȵ�����²���Ϊ�ǽ��׳ɹ�.
		#	������Ҫ�Է��صĴ������������ƣ����м�¼�������Դ����ڽ��յ�֧�����֪ͨ���ж��Ƿ���й�ҵ���߼�������Ҫ�ظ�����ҵ���߼�������ֹ��ͬһ�������ظ��������������.      	  	
			
		$r6_Order = $_REQUEST['r6_Order']; //��Ʒ���
		$r0_Cmd		= $_REQUEST['r0_Cmd']; 
		$r1_Code	= $_REQUEST['r1_Code'];
		$r2_TrxId	= $_REQUEST['r2_TrxId'];
		$r3_Amt		= $_REQUEST['r3_Amt']; //���
		$r4_Cur		= $_REQUEST['r4_Cur'];
		$r5_Pid		= $_REQUEST['r5_Pid']; //��Ʒ����
		$r6_Order	= $_REQUEST['r6_Order'];
		$r7_Uid		= $_REQUEST['r7_Uid'];
		$r8_MP		= $_REQUEST['r8_MP']; //��չ��Ϣ
		$r9_BType	= $_REQUEST['r9_BType']; //��������

		//���ص�ַ
		$re_url = "http://dpweb.juqu8.com/Pay/log/type_id/2";
		$arr = array('host'=>'localhost',
				      'user'=>'root',
				      'pwd' =>'ldyd',
				      'db' =>'rwxt'
	      );

		//��ȡ���ݿ�����
		$con = mysql_connect($arr['host'],$arr['user'],$arr['pwd']);
		//ѡ��ָ��Ŀ���
	 	mysql_select_db($arr['db'], $con);


	 	if(empty($r6_Order)){
			echo '���׳ɹ���������ʧ�ܣ�����ϵ����Ա';
			die;
		} 


		$sql = "insert into task_action_log (log,log_date) values ('����".$r6_Order."',".time().")";
		mysql_query($sql);


		if($r9_BType=="1"){
				$sql = "insert into task_action_log (log,log_date) values ('�����̻��������".$r6_Order."',".time().")";
				mysql_query($sql);

				//�ж��ظ�����
			 	$sql = "select * from task_charge_log where ext_id='".$r6_Order."'";
			 	$re = mysql_query($sql);
			 	$flag = 0;
			 	$flag = mysql_num_rows($re);
			 
 

				if($flag==0){

			 	 if($r5_Pid=='yjpay'){
				 	 //�����û�Ѻ��
				 	$sql = "update task_user_charge set cg_deposit=cg_deposit+".floatval($r3_Amt)." where user_id=".$r8_MP;
				 	$type_name='Ѻ���ֵ';

		 		 }else{
					//�����û�DBֵ
				 	$sql = "update task_user_charge set cg_amount=cg_amount+".floatval($r3_Amt)." where user_id=".$r8_MP;
				 	$type_name='DB��ֵ';				 	 
		 		 }

				 //echo '$sql : '.$sql;
				 //���½��
		 		 $up_db = mysql_query($sql);

				 if($up_db){
				 	 
					 	if($r5_Pid=='yjpay'){
					 		//�����ֵ��¼
					 	 	$sql="insert into task_charge_log 
						 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
						 	 						values 
						 	 						(3,".$r3_Amt.",".time().",".$r8_MP.",'��ֵ����:".$r6_Order."',".$r6_Order.",1)";
				 		 }else{
						 	//�����ֵ��¼
						 	$sql="insert into task_charge_log 
						 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
						 	 						values 
						 	 						(1,".$r3_Amt.",".time().",".$r8_MP.",'��ֵ����:".$r6_Order."',".$r6_Order.",1)";
				 		 }
			 		 	$cz_log = mysql_query($sql);
				 		if($cz_log){
				 			echo '<center>';
						 	echo '<h2>��ֵ��'.$r6_Order.'��֧�����׳ɹ�</h2>';
							echo '<a href="javascript:window.close();">�رմ���</a>';
							echo '<center>';
						}else{
							echo '<center>';
						 	echo '<h3>��ֵ��¼ͬ����'.$r6_Order.'������ʧ��,����ϵ����Ա</h3><br>';
						 	echo '<a href="javascript:window.close();">�رմ���</a>';
						 	echo '</center>';
						}
				 }else{
				 	echo '<center>';
				 	echo '<h3>ϵͳͬ����'.$r6_Order.'����ֵ״̬����ʧ��,����ϵ����Ա</h3><br>';
				 	echo '<a href="javascript:window.close();">�رմ���</a>';
				 	echo '</center>';
				 }

			}else{
				echo '<center>';
				echo '<h3>�Ự�����Ѿ����ڸ���</h3>';
				echo '<a href="javascript:window.close();">�رմ���</a>';
				echo '</center>';
			}
			die;
		}elseif($r9_BType=="2"){

				$sql = "insert into task_action_log (log,log_date) values ('����˵���".$r6_Order."',".time().")";
				mysql_query($sql);

				//�ж��ظ�����
			 	$sql = "select * from task_charge_log where ext_id='".$r6_Order."'";
			 	$flag = 0;
			 	$re = mysql_query($sql);
			 	$flag = mysql_num_rows($re);

				if($flag==0){

			 	 if($r5_Pid=='yjpay'){
				 	 //�����û�Ѻ��
				 	$sql = "update task_user_charge set cg_deposit=cg_deposit+".floatval($r3_Amt)." where user_id=".$r8_MP;
				 	$type_name='Ѻ���ֵ';

		 		 }else{
					//�����û�DBֵ
				 	$sql = "update task_user_charge set cg_amount=cg_amount+".floatval($r3_Amt)." where user_id=".$r8_MP;
				 	$type_name='DB��ֵ';				 	 
		 		 }

				 //echo '$sql : '.$sql;
				 //���½��
		 		 $up_db = mysql_query($sql);

				 if($up_db){
				 	 
					 	if($r5_Pid=='yjpay'){
					 		//�����ֵ��¼
					 	 	$sql="insert into task_charge_log 
						 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
						 	 						values 
						 	 						(3,".$r3_Amt.",".time().",".$r8_MP.",'��ֵ����:".$r6_Order."',".$r6_Order.",1)";
				 		 }else{
						 	//�����ֵ��¼
						 	$sql="insert into task_charge_log 
						 	 						(type_id,charge,charge_date,oper_id,remark,ext_id,status) 
						 	 						values 
						 	 						(1,".$r3_Amt.",".time().",".$r8_MP.",'��ֵ����:".$r6_Order."',".$r6_Order.",1)";
				 		 }
			 		 	$cz_log = mysql_query($sql);
				 		if($cz_log){
				 			echo 'success';
				 			echo '<center>';
						 	echo '<h2>��ֵ��'.$r6_Order.'��֧�����׳ɹ�</h2>';
							echo '<a href="javascript:window.close();">�رմ���</a>';
							echo '<center>';
						}else{
							echo '<center>';
						 	echo '<h3>��ֵ��¼ͬ����'.$r6_Order.'������ʧ��,����ϵ����Ա</h3><br>';
						 	echo '<a href="javascript:window.close();">�رմ���</a>';
						 	echo '</center>';
						}
				 }else{
				 	echo '<center>';
				 	echo '<h3>ϵͳͬ����'.$r6_Order.'����ֵ״̬����ʧ��,����ϵ����Ա</h3><br>';
				 	echo '<a href="javascript:window.close();">�رմ���</a>';
				 	echo '</center>';
				 }

			}else{
				echo '<center>';
				echo '<h3>�Ự�����Ѿ����ڸ���</h3>';
				echo '<a href="javascript:window.close();">�رմ���</a>';
				echo '</center>';
			}
		}

		//���ݿ����ӹر�
		mysql_close($con);
	}
	
}else{
	echo "������Ϣ���۸�";
}
   
?>
<html>
<head>
<title>Return from YeePay Page</title>
</head>
<body>
</body>
</html>