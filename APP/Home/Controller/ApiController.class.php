<?php

namespace Home\Controller;

class ApiController extends BaseController { 
	
	public function test(){
		$this->display();
	}
	
	public function server(){
		
		$list_id = I('list_id');
		$this->assign('list_id',$list_id);
		
		if(IS_POST){
			$action_user_name = I('POST.action_user_name');
			$this->assign('action_user_name',$action_user_name);
			
			$total = I('POST.total');
			$this->assign('total',$total);
			
			$id = I('POST.id');
			$this->assign('id',$id);
			
			$transport_name = I('POST.transport_name');
			$this->assign('transport_name',$transport_name);
			
			$transport_id = I('POST.transport_id');
			$this->assign('transport_id',$transport_id);
		}
		
		$l = M('taskList');
		$list = $l->table('v_task_list_ext_user')
				  ->where('list_id='.$list_id)
				  ->limit(0,1)
				  ->select();
		$this->assign('list',$list[0]);
		$this->display();
	}

	
	
	//更改接单
	public function jd(){
		$list_id = I('list_id');
		$list = $this->doAction($list_id);
		if($list){
			echo 'a|1';
			// $this->redirect('server&list_id='.$list_id);
		}else{
			// $this->error('操作失败');
			echo 'a|0';
		}
	}

	//更改待付款
	public function dfk(){
		$list_id = I('list_id');
		$list = $this->doFlag($list_id);
		if($list){
			echo 'a|1';
			// $this->redirect('server&list_id='.$list_id);
		}else{
			// $this->error('操作失败');
			echo 'a|0';
		}
	}
	
	//更改更改小号
	public function modify(){
		$list_id = I('list_id');
		$action_user_name=I('action_user_name');
		if(intval($list_id)>0){
			if(!empty($action_user_name)){
					$data['action_user_name'] = $action_user_name;
					$t = D('taskList');
					$res = $t->where('list_id='.$list_id)->save($data);
					//dump($res);
					//echo '<br>';
					//echo $t->getLastSql();
					if($res){
						echo 'a|1';
						exit;
					}
			}
		}
		echo 'a|0';
		exit;
	}
	
	//更改待发货
	public function dfh(){
		//$list_id = I('list_id');
		//$list = $this->sendFlag($list_id);
		//if($list){
		//	echo 'a|1';
			// $this->redirect('server&list_id='.$list_id);
		//}else{
			echo 'a|0';
			// $this->error('操作失败');
		//}
		exit;
	}
	
	//更改待确认
	public function dqr(){
		$list_id = I('list_id');
		$list = $this->sureFlag($list_id);
		if($list){
			echo 'a|1';
			// $this->redirect('server&list_id='.$list_id);
		}else{
			echo 'a|0';
			// $this->error('操作失败');
		}
		exit;
	}
	
	
	//更改确认
	public function wc(){
		$list_id = I('list_id');
		$list = $this->compFlag($list_id);
		if($list){
			echo 'a|1';
			// $this->redirect('server&list_id='.$list_id);
		}else{
			echo 'a|0';
			// $this->error('操作失败');
		}
		exit;
	}
	
	//随机取订单列表
	public function randList(){
		$t = M();
		$sql = 'SELECT * FROM v_task_list_ext_user WHERE is_complete=1 and status=1 and state=1 and action_user_name is null ORDER BY RAND() LIMIT '.C('RAND_DATA_NUMBER');
		$list = $t->query($sql);
		$i=1;
		foreach ($list as $key => $value) {
			if(is_array($value)){
				echo $i.'|'.$value['tid'].'|'.$value['list_id'].'|'.$value['web_id'].'|'.$value['shop_name'].'|'.$this->array2string(json_decode($value['name'])).'|'.$this->array2string(json_decode($value['url'])).'|'.$this->array2string(json_decode($value['pro_id'])).'|'.$this->array2string(json_decode($value['img'])).'|'.$this->array2string(json_decode($value['set_comments_txt']),'$').'|'.$value['transport'].'|'.$value['total'].'|'.$value['key_words'].'|'.$value['list_type'];
			}
			echo '<br/>';
			$i++;
		}
		die;
	}
	
	//待接单数据
	public function djd($page=0){
		$p = intval($page);
		$min = ($p-1)*20;
		$max = $min+19;
			
		$t = M();
		$sql = 'SELECT * FROM v_task_list_ext_user WHERE status=1 and state=1 order by list_id limit '.$min.','.$max;
		$list = $t->query($sql);
		$i=1;
		foreach ($list as $key => $value) {
			if(is_array($value)){
				echo $i.'|'.$value['tid'].'|'.$value['list_id'].'|'.$value['web_id'].'|'.$value['shop_name'].'|'.$this->array2string(json_decode($value['name'])).'|'.$this->array2string(json_decode($value['url'])).'|'.$this->array2string(json_decode($value['pro_id'])).'|'.$this->array2string(json_decode($value['img'])).'|'.$this->array2string(json_decode($value['set_comments_txt']),'$').'|'.$value['transport'].'|'.$value['total'].'|'.$value['key_words'].'|'.$value['list_type'];
			}
			echo '<br/>';
			$i++;
		}
		//die;
	}
 
 	private function array2string($arr=null,$sp=','){
 		$tmp = '';
 		if(is_array($arr)){
 			$i=0;
 			foreach ($arr as $key => $value) {
 				if(!empty($value)){
 					if($i<count($arr)){
 						$tmp.= $value.$sp;
	 				}else{
	 					$tmp.= $value;
	 				}
 				}
 				++$i;
 			}
 		}
 		return $tmp;
 	}

 	//任务详情页
 	public function details($id=0){
 		if(intval($id)>0){
 			$tk = M('taskInfo');
 			$info = $tk->table('v_task_list_ext_user')->where('list_id='.$id)->find();
 			if(!$info){
 				$this->error('订单不存在');
 			}
 			$this->assign('tk',$info);
 			$this->display();
 		}else{
 			echo '任务编号不准确';
 		}
 	}

 	//任务列表
 	public function tasks(){
 		$m   = 'TaskInfo';
		 $t   = 'v_task_info_ext_cnt as a';
		 $f   = '*';
		 $w   = 'a.status=1 and is_complete=1 and djd>0 and `check`=1';
		 $o   = ' id desc ';
	 
		 $tks = $this->lists($m,$t,$w,$o,$f);
		 $this->assign('tks',$tks);
		
		 //获取MID 和 GID 
		 $mid = I('MID'); //2014001
		 $gid = I('GID'); //22

		 $this->assign('mid',$mid);
		 $this->assign('gid',$gid);
		 $this->display();
 	}

 	//随机获取指定任务中未接单状态订单
 	public function task_rand_list($id=0){
 	    header('content-type: text/html; charset=gb2312');
 		$mid = I('MID'); //2014001
		$gid = I('GID'); //22

		$userCharge = $this->dbQuery();
		if(floatval($userCharge['cg_baozj'])<0){
			$this->error('未缴纳保证金，请去个人中心充值,<a href="'.U('Info/bao_amt').'" target="_bank"><font color=red>进入充值保证金?</font></a>');
		}

		if(intval($mid)>0 && intval($gid)>0){
			if(intval($id)>0){
	 			$t = M();
				$sql = 'SELECT * FROM v_task_list_ext_user WHERE tid='.$id.' and is_complete=1 and `check`=1 and status=1 and state=1 and action_user_name is null ORDER BY RAND() LIMIT 1';
				//echo $sql;
				//die;
				$list = $t->query($sql);
				if($list){
					$vo = $list[0];
					$u ='http://dp.juqu8.com/update/GetOrder.asp?MID='.$mid.'&GID='.$gid;
					$u.='&productid='.$this->array2string(json_decode($vo['pro_id']));
					$u.='&AllMoney='.$vo['total'];
					$u.='&SEACRCHDOOR='.urlencode(mb_convert_encoding('自然搜索', "gb2312","UTF-8"));
					$u.='&KEYWORD='.urlencode(mb_convert_encoding($vo['key_words'], "gb2312","UTF-8") );
					$u.='&PT='.urlencode(mb_convert_encoding('淘宝', "gb2312","UTF-8"));
					$u.='&Pnum='.$vo['cnt'];
					$u.='&device='.urlencode(mb_convert_encoding($vo['list_type']=='PC'?'电脑':'手机', "gb2312","UTF-8"));
					$u.='&WZ='.urlencode(mb_convert_encoding($vo['shop_name'], "gb2312","UTF-8"));
					//$u.='&PIC='.$this->array2string(mb_convert_encoding(json_decode($vo['img']), "gb2312","UTF-8"),'|');
					$pys = json_decode($vo['set_comments_txt']);
					if(is_array($pys)){
						$size = count($pys);
						$rd   = rand(0,$size-2);
					}
					if(!is_array($pys) || empty($pys[$rd])){
						$u.='&PY='.urlencode(mb_convert_encoding('好', "gb2312","UTF-8"));
					}else{
						$u.='&PY='.urlencode(mb_convert_encoding($pys[$rd], "gb2312","UTF-8"));
					}
					$u.='&suptime=';
					$u.='&tid='.$id;
					$u.='&list_id='.$vo['list_id'];
					$u.='&qq=';
					//echo '执行链接:'. $u .'<br>';
					$re = https_request($u,null,'gb2312');
					//echo '返回结果:' . $re.'<br>';
					$flag = substr($re,0,1);
					//echo '判断:' .$flag.'<br>';

					$action_user_name=substr($re,2);
					//echo '买号:' . $action_user_name.'<br>';
					//echo 'DP端状态:'.$flag.',买号:'.$action_user_name.'订单号：'.$vo['list_id'].'<br>';
					//die;

					if($flag==1){
						//正常接单
						$re = $this->doAction($vo['list_id'],$action_user_name,$mid);
						if($re){
							$this->redirect('api/tasks', array('MID' => $mid,'GID'=>$gid),3, '提示： 买号：'.$action_user_name.' 已经接受任务，订单号：'.$vo['list_id'].',页面跳转中..');
						}else{
							$this->error('<font color=red>【'.$vo['list_id'].'】接单人信息更新失败</font>');
						}
					}else{
						echo '已经接单';
						//DP已经有接单 ， 本地接单数据为空则同步即可不做新接单人信息更新
						//$re = false; //$this->doAction_process($vo['list_id'],$action_user_name,$mid);
						//if($re){
						//	$this->redirect('api/tasks', array('MID' => $mid,'GID'=>$gid),3, '提示： 【'.$vo['list_id'].'】已接单，任务系统同步买号：'.$action_user_name.'，订单号：'.$vo['list_id'].',页面跳转中..');
						//}else{
						//	$this->error('【'.$vo['list_id'].'】已接单,任务系统同步失败');
						//}
					}
				}else{
					$this->error('获取随机订单失败，请重试');
				}
	 		}
		}else{
			$this->error('获取DP客服端参数失败，请重试');
		}
 		die;
 	}

 	//同步DP端删除订单
 	public function delList($list_id=0){
 		if(intval($list_id)>0){
 			$list_id = intval($list_id);
 			$url = 'http://dp.juqu8.com/update/delorder.asp?list_id='.$list_id;
 			$re = https_request($url);
 			if($re==0){
 				$this->error('DP:格式错误');
 			}else{
	 			if($re==2){
	 				$msg = 'DP:无记录';
	 				$this->error($msg);
	 			}elseif($re==1){
	 				$msg = 'DP:删除成功';
	 				$d['status']=0;
	 				$w['list_id']=$list_id;
	 				$t = M('taskList');
	 				$re = $t->where($w)->save($d);
	 				if($re){
	 					$this->redirect('task/order_mg',$msg.'本地同步删除成功');
	 				}else{
	 					$this->error($msg.'本地同步删除失败');
	 				}
	 			}
 			}
 		}else{
 			$this->error('订单参数不准确');
 		}
 	}

 	public function f_del_list($list_id=0){
 		$flag = false;
 		if(intval($list_id)>0){
 			$list_id = intval($list_id);
 			$url = 'http://dp.juqu8.com/update/delorder.asp?list_id='.$list_id;
 			$re = https_request($url);
 			if($re==0){
 				$flag = false;
 			}else{
	 			if($re==2){
	 				$msg = 'DP:无记录';
	 				$flag = false;
	 			}elseif($re==1){
	 				$msg = 'DP:删除成功';
	 				$d['status']=0;
	 				$w['list_id']=$list_id;
	 				$t = M('taskList');
	 				$re = $t->where($w)->save($d);
	 				if($re){
	 					$flag=true;
	 				}else{
	 					$flag = false;
	 				}
	 			}
 			}
 		}else{
 			$flag = false;
 		}
 		return $flag;
 	}
 
 	//订单管理
	public function order_mg(){
		$m   = 'TaskList';
		$t   = 'v_task_list_ext_user as a';
		$f   = 'a.*';
		$w   = '1=1 and list_status=1';
		
		
		//批量删除
		if(IS_POST){
			$del_datas = I('del_datas');
			if($del_datas=='1'){
				$list_ids = I('list_ids');
				if(is_array($list_ids) && count($list_ids)>0){
					$mm = D('taskList');
					$del_cnt = 0;
					foreach ($list_ids as $key => $val) {
						$flag = $this->f_del_list($val);
						if($flag){$del_cnt++;}
					}
					$this->assign('delMsg','成功删除 '.$del_cnt.' 条数据,未删除数据可能原因是DP端不同步,详情可删除单独数据查看');
				}
			}
		}
		 
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
			$w=$w.' and a.create_user_id='.$_SESSION['user_auth']['id'];
		}
	 
		//全部订单
		$tks = $this->lists($m,$t,$w,'',$f);
		$this->assign('tks',$tks);

		//店铺信息
		$this->shopConfig();

		$this->display('task/order_mg');
	}


	//同步DP用户
	public function register(){
			$user = D("UserInfo");
			$userInfo['nc']=I('nc');
			$userInfo['phone']=I('phone');
			$userInfo['mid']=I('mid');
			$userInfo['create_date']=time();
			$pwd =I('pwd');
			$userInfo['pwd']=md5($pwd);
		 
			if(empty($userInfo) || empty($userInfo['nc']) || empty($userInfo['pwd']) || empty($userInfo['phone']) || empty($userInfo['mid'])){
				echo '0|请确保昵称、密码、手机、mid不为空';
				die;
			}

			$userInfo['manager']=0;
			$w['mid']=$userInfo['mid'];
			$w['nc'] =$userInfo['nc'];
			$w['_logic'] = 'or';
			$map['_complex'] = $w;
			$users = $user->where($map)->find();
			 
			if(!empty($users)){
				echo '0|mid:'.$userInfo['mid'].'或昵称:'.$userInfo['nc'].'已经被注册,请重新填写';
				die;
			}
			if($userInfo){
				if(empty($userInfo['id'])){
					$userInfo['ext']=$pwd;
					$userInfo['pwd']=md5($pwd);

					$id = $user->add($userInfo);
					if($id){
						//添加账户
						$t = M('userCharge');
						$acc['user_id']=$id;
						$acc['cg_amount']=0;
						$acc['status']=1;
						$t->add($acc);
 
						echo '1|注册成功,账户初始化成功.';
					}else{
						echo '0|注册失败';
					}
				}else{
					if($user->save()){
						echo '1|信息更新成功';
					}else{
						echo '0|更新失败';
					}
				}
			}else{
				echo "0|注册失败:".$user->getError();
			}
			exit();
	}
}?>