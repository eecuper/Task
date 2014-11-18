<?php

namespace Home\Widget;

use Think\Controller;

class BPTypeWidget extends Controller {
	
	public function types($tid){
		$cs = M("BusiType");
		$cstr =  '';
		$cstr.="<option></option>";
			$configList = $cs->where("status=1")->select();
			if(!empty($configList)){
				foreach ($configList as $key=>$u){
					if(isset($tid)){
						if($tid==$u['id']){
							$cstr.="<option value='".$u['id']."' selected>".$u['name']."</option>";
						}else{
							$cstr.="<option value='".$u['id']."'>".$u['name']."</option>";
						}
					}
				}
			}
		$this->assign("types",$cstr);
		$this->display("Config:types");
	
	}
	
}

?>