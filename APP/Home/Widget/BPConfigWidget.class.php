<?php

namespace Home\Widget;

use Think\Controller;

class BPConfigWidget extends Controller {
	
	public function configs($cid){
		$cs = M("BusiConfig");
		$cstr =  '';
		
		$cstr.="<option></option>";
		$configList = $cs->where("status=1")->order('code,sort,create_date desc')->select();
		if(!empty($configList)){
			foreach ($configList as $key=>$u){
				if(!empty($cid) && $u['id']==$cid){
					$cstr.="<option selected value='".$u['id']."'>".$u['name']."</option>";
				}else{
					$cstr.="<option value='".$u['id']."'>".$u['name']."</option>";
				}
			}
		}
		
		$this->assign("configs",$cstr);
		$this->display("Config:configs");
	
	}
	
}

?>