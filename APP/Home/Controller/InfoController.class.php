<?php

namespace Home\Controller;

class InfoController extends BaseController {
	
	public function index(){
		$this->display('main');
	}

	//首页
	public function main(){
		$this->display();
	}

	//我的订单
	public function order_list(){
		$state = I('state');
		$this->assign('state',intval($state));
		
		$m   = 'TaskList';
		$t   = 'v_task_list_ext_user as a';
		$f   = 'a.*';
		$w   = '1=1 '; //and list_status=1
		
		$web_id= I('web_id');
		if(!empty($web_id)){$w=$w.' and a.web_id='.$web_id;}
		$shop_id= I('shop_id');
		if(!empty($shop_id)){$w=$w.' and a.shop_id='.$shop_id;}
		
		$search_keys= I('search_keys');
		$search_words= I('search_words');
		
		if(!empty($search_words)){
			$this->assign('search_words',$search_words);
			if($search_keys=='list_id'){
				$w=$w.' and list_id='.intval($search_words);
			}else if($search_keys=='action_user_nc'){
				$w=$w.' and action_nc=\''.$search_words.'\'';
			}else{
				$w=$w.' and tid='.intval($search_words);
			}
		}
		
		if($_SESSION['user_auth']['manager']!=C('IS_ADMIN')){
			$w=$w.' and a.action_user_id='.$_SESSION['user_auth']['mid'];
		}
		
		if(intval($state)>0){
			$w.=' and a.state='.intval($state);
		}else{
			$w.='';
		}
		
		//全部订单
		$tks = $this->lists($m,$t,$w,'',$f);
		$this->assign('tks',$tks);
		$this->display();
	}

	//保证金充值
	public function bao_amt(){
		$this->display();
	}

	//提现
	public function ti_amt(){
		$this->display();
	}

	//资金记录
	public function pay_log(){
		$this->display();
	}

	//基本信息
	public function basicInfo(){
		$this->display();
	}

	//收款账户绑定
	public function pay_bind(){
		$t = D('userAcc');

		//查询是否已经绑定
		$w['user_id']=$_SESSION['user_auth']['id'];
		$q = $t->where($w)->find();

		if(IS_POST){
			if(empty($q)){
				$acc = $t->create();
				$acc['status']=1;
				$acc['use_type']=1;
				$acc['create_date']=time();
				$acc['user_id']=$_SESSION['user_auth']['id'];
				if($acc['acc_id']){
					$acc_id=$t->save($acc);
				}else{
					$acc_id = $t->add($acc);
					$acc['acc_id']=$acc_id;
				}
				if($acc_id){
					$this->redirect('pay_bind',3,'绑定成功');
				}
			}else{
				$this->error('该用户已经绑定过提现账户，不允许多次绑定，详细请咨询管理员');
			} 
		}

		$this->assign('accInfo',$q);
		$this->display();
	}
}

?>