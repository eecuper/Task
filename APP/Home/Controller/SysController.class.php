<?php

namespace Home\Controller;

class SysController extends BaseController {
	
	public function index(){
		$this->display();
	}
	

	//系统配置
	public function config(){
		if(IS_POST){
			$d = D('sysConfig');
			$config = $d->create();
			if(intval($config['id'])>0){
				$config['modify_date']=time();
				$re = $d->save($config);
				if($re){
					echo '<script>alert("更新成功");</script>';
				}
			}else{
				$this->error('系统配置初始化数据错误');
			}
		}
		//查询配置
		parent::sysConfig();
		$this->display();
	}


	//平台 店铺 消息配置
	public function configAction($del=0){
		if(IS_POST){
			$c    = D('typeConfig');
			$config=$c->create();
			$config['status']=1;
			if(!empty($config['id'])){
				$c->save($config);
			}else{
				$c->add($config);
			}
			
		}
		
		if(!($del==0)){
			$id = I('id');
			$c  = M('typeConfig');
			$c->delete($id);
		}
		 
		$this->redirect('config');
	}
	
}

?>