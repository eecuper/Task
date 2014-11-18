<?php
// 本类由系统自动生成，仅供测试用途
namespace Mobile\Controller;
use Admin\Controller\AdminController;

class BaseController extends AdminController{
	
	protected function _initialize(){
		if(IS_GET){
			//上级推荐人
			$uid = I('get.uid');
			$uid = empty($uid)?session('tjr_id'):$uid;
			
			$user = D("userInfo");
			if(!empty($uid)){
				$userInfo = $user->find($uid);
				if($userInfo){
					session("tjr",$userInfo);
					session("tjr_id",$userInfo['id']);
				}
			}
			//顶级推荐人
			$suid = I('get.suid');
			$suid = empty($suid)?session('stjr_id'):$suid;
			if(!empty($suid)){
				$userInfo = $user->find($suid);
				if($userInfo){
					session("stjr",$userInfo);
					session("stjr_id",$userInfo['id']);
				}
			}
			$url = str_replace('.html','',__SELF__).'/uflag/1/suid/'.$suid.'/uid/'.$uid;
			//判断链接中是否有uid 没有就添加参数并重定向
			$tmp_uid = I('get.uid');
			$tmp_suid =I('get.suid');
			$uflag   = I('get.uflag');
			
			if(empty($tmp_uid) && empty($tmp_suid) && empty($uflag) ){
				header('location:'.$url);
			}
		}
	}
}