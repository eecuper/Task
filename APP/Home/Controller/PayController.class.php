<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Admin;

class PayController extends BaseController {

	 //银行卡绑定
	public function card($del=0){
		$t = D('userAcc');
		if(IS_POST){
			$acc = $t->create();
			$acc['status']=1;
			$acc['create_date']=time();
			$acc['user_id']=$_SESSION['user_auth']['id'];
			if($acc['acc_id']){
				$t->save($acc);
			}else{
				$acc_id = $t->add($acc);
				$acc['acc_id']=$acc_id;
			}
		} 

		if($del==1){
			$acc_id = I('acc_id');
			$t->delete($acc_id);
		}

		$aw['task_user_acc.user_id']=$_SESSION['user_auth']['id'];
		$aw['task_user_acc.status']=1;
		$aw['task_user_acc.use_type']=0;
		$acces = $t->where($aw)
				   ->order('task_user_acc.create_date desc')
				   ->join('task_type_config on task_user_acc.acc_type=task_type_config.id')
				   ->select();
		$this->assign("acces",$acces);

		$this->display();
	}

	//充值
	public function charge($ctype='dbpay'){
		$ctype= empty($ctype)?'dbpay':$ctype;
		$this->assign('ctype',$ctype);

		$id = I('id');
		$this->assign('id',$id);

		if($ctype=='dbpay'){
			$this->display();	
		}else{
			$yj_pay = $_REQUEST['yj_pay'];
			if(floatval($yj_pay)>0){
				$this->assign('yj_pay',$yj_pay);	
			}else{
				$this->error('获取垫付押金数据错误，请联系管理员');
			}
			$this->display('charge_yj');
		}
	}


	//交易记录
	public function log(){
		$m   = 'chargeLog';
		$t   = '';
		$f   = '*';

		$typeId = I('type_id');
		if(intval($typeId)>0){
			switch ($typeId) {
				case 1:
					$w['type_id']=array('in',array(1,10,6,60,601));
					break;
				case 2:
					$w['type_id']=array('in',array(1,3));
					break;
				case 3:
					$w['type_id']=array('in',array(3,30,5,50,501));
					break;
				default:
					break;
			}
		}
		$w['oper_id']=$_SESSION['user_auth']['id'];
		$logs = $this->lists($m,$t,$w,'charge_date desc',$f);
		$this->assign('type_id',$typeId);
		$this->assign("logs",$logs);
		$this->display();
	}

	//支付完成验证
	public function validPay(){
		$ext_id = I('ext_id');
		$msg['success']=false;
		$msg['msg']='操作失败';
		if(!empty($ext_id)){
			$t = M('chargeLog');
			$w['ext_id']=$ext_id;
			$log = $t->where($w)->select();
			if($log){
				$msg['success']=true;
				$msg['msg']='同步支付已经完成';
			}else{
				$msg['msg']='充值进行中..请稍重试,后台暂未找到订单【'.$ext_id.'】充值记录,请确认充值是否成功或联系系统管理员';
			}
		}else{
			$msg['msg']='订单编号获取失败';
		}
		$this->ajaxReturn($msg,'json');
	}

}

?>