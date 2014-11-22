<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Admin;

class UserController extends BaseController {

	//管理员更新信息
	public function modify(){
		$flag = false;
		if(IS_POST){
			$user = D("UserInfo");
			$userInfo['id']=I('id');
			$userInfo['nc']=I('nc');
			$userInfo['ext']=I('pwd');
			$userInfo['pwd']=md5(I('pwd'));
			$userInfo['phone']=I('phone');
			$userInfo['email']=I('email');
			$userInfo['status']=I('status');
			$userInfo['manager']=I('manager');
			if($userInfo['id']){
				$re = $user->where('id='.$userInfo['id'])->save($userInfo);
				//echo $user->getLastSql();
				if($re){
					$flag=true;
				}
			}
		}
		if($flag){
			echo '更新成功';
			$this->redirect('users');
		}else{
			$this->error('更新失败');
		}
	}
	
	//删除
	public function delete(){
		$t = D('userInfo');
		$id = I('id');
		$t->delete($id);
		$this->redirect('users');
	}

	//用户列表
	public function users(){
		C('TOKEN_ON',false);
		$t = M('userInfo');
		$nc = I('post.nc');
		$email = I('post.email');
		$phone= I('post.phone');
		if(!empty($nc)){
			$w['nc']=array('like','%'.$nc.'%');
		}
		if(!empty($email)){
			$w['email']=$email;
		}
		if(!empty($phone)){
			$w['phone']=$phone;
		}
		$list = $t->where($w)->select();
		$this->assign('users',$list);
		$this->display();
	}

	//基本信息
	public function info(){
		$this->display();
	}

	public function login(){
		$userInfo = Admin\Controller\AdminController::login();
		if($userInfo){
			if($userInfo['status']==0){
				$this->error('登录失败,失效用户不允许登陆,请联系我们');
			}else if($userInfo['manager']==0){
				$this->error('该账号为刷客号，无商家主页权限');
			}else{
				session('user_auth',$userInfo);
				$this->assign('user',$userInfo);
				$this->redirect('Task/main');	
			}
		}else{
			$this->error('登录失败,用户名或密码错误');
		}
	}
	
	//注册
	public function register(){
		if(IS_POST){
			$user = D("UserInfo");
			$userInfo=$user->create();

			$userInfo['manager']=0;
			$users = $user->where("nc='".$userInfo['nc']."' or phone='".$userInfo['phone']."'")->select();
			if(!empty($users)){
				$this->error('用户名或手机号码已经被注册,请重新填写');
			}

			if($userInfo){
				if(empty($userInfo['id'])){
					$userInfo['ext']=$userInfo['pwd'];
					$userInfo['pwd']=md5($userInfo['pwd']);
					$id = $user->add($userInfo);
					if($id){
						//添加账户
						$t = M('userCharge');
						$acc['user_id']=$id;
						$acc['cg_amount']=0;
						$acc['status']=1;
						$t->add($acc);

						$userInfo['id']=$id;
						session('user_auth',$userInfo);
						$this->assign('user',$userInfo);
						$this->redirect('Task/main');
						//$this->success('注册成功,请重新登录',U('login'),3);
					}else{
						$this->error('注册失败');
					}
				}else{
					if($user->save()){
						$this->success("信息更新成功,请重新登录",U('login'),3);
					}else{
						$this->error("更新失败");
					}
				}
			}else{
				$this->error("注册失败:".$user->getError());
			}
			exit();
		}
		$this->display('register');
		
	}
	 
	
	public function loginOut(){
		session('user_auth',null);
		session(null);
		$this->display('login');
	}
	
	
	//店铺管理
	public function shop($del=0){
		$c    = D('shopConfig');
		if(IS_POST){
			if(IS_POST){
				$config=$c->create();
				$config['status']=1;
				$config['user_id']=$_SESSION['user_auth']['id'];
				if(!empty($config['id'])){
					if(!($c->save($config))){
						$this->error('编辑失败'.$c->getError());
					}
				}else{
				if(!($c->add($config))){
						$this->error('添加失败'.$c->getError());
					}
				}
			}
		}
		
		if(!($del==0)){
			$id = I('id');
			$d['status']=0;
			$c->where('id='.$id)->save($d);
		}
		
		$this->shopConfig();
		$this->display();
	}
 
}

?>