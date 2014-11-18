<?php

namespace Home\Widget;
use Think\Controller;

class BPUserWidget extends Controller{

	public function users($uid){
		if(empty($uid)){
			$uid = session("tjr_id");
		}
		
		$this->assign("uid",$uid);
		
		$us = M("UserInfo");
		$ustr =  '';
		if(!empty($uid)){
			$userInfo = $us->find($uid);
			if($userInfo){
				$ustr.="<option value='".$userInfo['id']."'>".$userInfo['nc']."</option>";
			}
		}else{
			$userList = $us->where("status=1")->select();
			if(!empty($userList)){
				$ustr.="<option></option>";
				foreach ($userList as $key=>$u){
					$ustr.="<option value='".$u['id']."'>".$u['nc']."</option>";
				}
			}
		}
		 
		$this->assign("users",$ustr);
		$this->display("User:users");
		
	}
	
}

?>