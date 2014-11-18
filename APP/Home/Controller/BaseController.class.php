<?php

namespace Home\Controller;

use Admin\Controller\AdminController;

class BaseController extends AdminController {
	
	/**
	 * 基类控制器初始化
	 */
	protected function _initialize(){ 
		$CONTROLLER_NAME = $Think.CONTROLLER_NAME;
		$ACTION_NAME  = $Think.ACTION_NAME;
		$NO_LOGIN_CONTROLLER = C('NO_LOGIN_CONTROLLER');
		$NO_LOGIN_METHOD = C('NO_LOGIN_METHOD');
		
		if(!(in_array($CONTROLLER_NAME,$NO_LOGIN_CONTROLLER) 
		  	 || in_array($ACTION_NAME,$NO_LOGIN_METHOD))){
			$userInfo = $this->isLogin();
			if($userInfo){
				//此处不能直接重定向到一个集成本类的控制类方法 否则死循环
				//$this->redirect('Task/main');
				$this->assign('user',$userInfo);
			}else{
				$this->display('User:login');
				exit();
			}
		}
		
		//加载系统配置
		$this->sysConfig();
		
		//配置信息加载 并且缓存起来
		$this->typeConfig();

		//加载店铺信息
		$this->shopConfig();

	    //用户DB查询
	    $this->dbQuery();
	}

	//用户DB查询
	public function dbQuery(){
		if(session('?user_auth')){
			$t = M('user_charge');
			$w['user_id']=$_SESSION['user_auth']['id'];
			$user_db = $t->where($w)->find();
			$this->assign('user_db',$user_db);
			return $user_db;
		}
	}
	
	//指定是否已经完成
	public function isComplete($id=0){
		if(!(empty($id) || $id===0)){
			$task = M('TaskInfo');
			$tk   = $task->where('is_complete=1 and id='.$id)->find();
			if($tk['check']==1){
				$this->redirect('Task/main',null,3,'指定任务为成功发布任务，不继续执行重复操作');
				exit();
			}else{
				return true;
			}
		}
	}

	//是否审核
	public function isCheck($check=0){
		if(intval($check)==0){
			return false;
		}else{
			return true;
		}
	}
	
	//加载系统配置
	public function sysConfig(){
		$config = M('sysConfig');
		$sys   = $config->where('status=1')->find();
		$this->assign('sysConfig',$sys);
	}

	//平台 店铺 任务类型
	protected function typeConfig(){
		$config = M('typeConfig');
		$list   = $config->where('status=1 and length(name)>0')->select();
		$this->assign('configs',$list);
	} 
	
	protected function shopConfig($flag=false){
		$config = M('shopConfig');
		
		if($flag){
			$list   = $config->where('status=1 and LENGTH(NAME)>0 and user_id='.$_SESSION['user_auth']['id'])->select();	
		}else{
			if($_SESSION['user_auth']['manager']==C('IS_ADMIN')){
				$list   = $config->where('status=1 and LENGTH(NAME)>0')->select();	
			}	else{
				$list   = $config->where('status=1 and LENGTH(NAME)>0 and user_id='.$_SESSION['user_auth']['id'])->select();
			}
		}
		
		$this->assign('shops',$list);
	}
	
	//任务列表
	protected function taskList(){
		$task  = M('taskInfo');
		$list  = $task->where('status=1')->select();
		$this->assign('tasks',$list);
	}
	
	//查询指定业务
	protected  function task($id=0){
		$task  = M('taskInfo');
		$tk    = $task->where('status=1')->find($id);
		$this->assign('tk',$tk);
		session('session_tk',$tk);
		return $tk;
	}
	
	//查询指定配置
	protected  function config($id=0){
		$type  = M('typeConfig');
		$tc    = $type->where('status=1')->find($id);
		$this->assign('config',$tc);
		session('session_tc',$tc);
		return $tc;
	}
	
	//增加
	protected  function add($d){
		$task  = M('taskInfo');
		return $task->add($d);
	}
	
	//修改
	protected  function save($id=0,$d,$url){
		$task  = M('taskInfo');
		return $task->where('id='.$id)->save($d);
	}
	
	//删除
	protected  function del($id=0,$url){
		$task  = M('taskInfo');
		return $task->delete($id);
	}
	
	protected  function edit($model,$where=array(),$data=array()){
		if(is_string($model)){
			$m = M($model);
			return $m->where($where)->save($data);
		}
	}
	
	//订单退一步 $flag true: 进一步 false 后退一步
	protected  function actionState($id=0,$flag=true){
		$task  = D('taskList');
		$tk    = $task->find($id);
		if($tk){
			if($flag){
				$tk['is_send']=intval($tk['is_send'])+1;
			}else{
				if($tk['state']==0){
					$tk['is_send']=0;
				}else{
					$tk['is_send']=intval($tk['is_send'])-1;
				}
			}
			return $this->edit('taskList',array('list_id'=>$id),$tk);
 		}
	}
	
	//其他设计state 设置
	protected  function setState($tk,$flag=true){
		if($tk){
			if($flag){
				$tk['state']=intval($tk['state'])+1;
			}else{
				if(intval($tk['state'])<=1){
					$tk['state']=1;
				}else{
					$tk['state']=intval($tk['state'])-1;
				}
			}
		}
		return $tk;
	}
 
 	// mg ===1 表示前台可管理员可回退 其他任何人不可操作回退动作
 	// 流程必须逐个进行跳跃调用返回false
 	//接单
 	protected  function doAction($list_id=0,$action_user_name='',$action_user_id=''){
 		$mg    = I('mg');
		$task  = D('taskList');
		$tk    = $task->find($list_id);
		if($tk){
			$tk['action_user_id']=$action_user_id;
			$tk['action_user_name']=(empty($action_user_name)?I('action_user_name'):$action_user_name) ;
			return $this->edit('taskList',array('list_id'=>$list_id),$tk);
		}
		return false;
	}

	//待付款
	protected  function doFlag(){
		$list_id=I('list_id');
		$mg    = I('mg');
		$task  = D('taskList');
		$tk    = $task->find($list_id);
		if($tk){
			if(intval($tk['state'])==2 && intval($mg)==1){
					$tk['state']=1;
					$tk['action_create_date']=null;
			}else if(intval($tk['state'])==1 && !empty($tk['action_user_name'])){
				$tk['state']=2;
				$tk['action_create_date']=time();
			}
			return $this->edit('taskList',array('list_id'=>$list_id),$tk);
		}
		return false;
	}
	
	//待货状态更改
	protected  function sendFlag(){
		$list_id=I('list_id');
		$mg    = I('mg');
		$task  = D('taskList');
		$tk    = $task->find($list_id);
		if($tk){
			if(intval($tk['state'])==3  && intval($mg)==1){
				$tk['is_send']=0;
				$tk['send_date']=null;
				$tk['state']=2;
			}else if(intval($tk['state'])==2){
				$tk['is_send']=1;
				$tk['send_date']=time();
				$tk['state']=3;
			}
			return $this->edit('taskList',array('list_id'=>$list_id),$tk);
		}
		return false;
	}
	
	//确认状态
	protected  function sureFlag(){
		$list_id=I('list_id');
		$mg    = I('mg');
		$task  = D('taskList');
		$tk    = $task->find($list_id);
		if($tk){
			if(intval($tk['state'])==4  && intval($mg)==1){
				$tk['is_sure']=0;
				$tk['sure_date']=null;
				$tk['comp_date']=null;
				$tk['state']=3;
			}else if(intval($tk['state'])==3){
				$tk['is_sure']=1;
				$tk['sure_date']=time();
				$tk['comp_date']=time();
				//$tk['transport_name']=I('transport_name');
				//$tk['transport_id']=I('transport_id');
				$tk['state']=4;
			}
			return $this->edit('taskList',array('list_id'=>$list_id),$tk);
		}
		return false;
	}
	
	//是否完成
	protected  function compFlag(){
		$list_id=I('list_id');
		$mg    = I('mg');
		$task  = D('taskList');
		$tk    = $task->find($list_id);
		if($tk){
			if(intval($tk['state'])==5 && intval($mg)==1){
				$tk['is_comp']=0;
				$tk['comp_date']=null;
				$tk['state']=4;
			}else if(intval($tk['state'])==4){
				$tk['is_comp']=1;
				$tk['comp_date']=time();
				$tk['state']=5;
			}
			return $this->edit('taskList',array('list_id'=>$list_id),$tk);
		}
		return false;
	}
	
	//任务查询
	protected  function taskCnt($table='',$where=array()){
		$tk = M('taskInfo');
		if(isset($table)){
			$tk->table($table);
		}
		if(!empty($where)){
			$tk->where($where);
		}
		$cnt = $tk->count();
		if($cnt){
			return $cnt;
		}else{
			return false;
		}
	}

	//快递名字
	public function transPortName($transport_name=''){
		$name = '';
		if(!empty($transport_name)){
			$wls = C('WL_INFO');
			$name = $wls[$transport_name];
		}
		echo $name;
	}

	//快递名字
	public function getTransPortName($transport_name=''){
		$name = '';
		if(!empty($transport_name)){
			$wls = C('WL_INFO');
			$name = $wls[$transport_name];
		}
		return $name;
	}
}	
?>