<?php

namespace Home\Controller;

class IndexController extends BaseController {
 
	public function index(){
		 $m   = 'TaskInfo';
		 $t   = 'v_task_info_ext_cnt as a';
		 $f   = '*';
		 $w   = 'a.status=1';
		 
		 $web_id= I('web_id');
		 if(!empty($web_id)){$w=$w.' and a.web_id='.$web_id;}
		 $shop_id= I('shop_id');
		 if(!empty($shop_id)){$w=$w.' and a.shop_id='.$shop_id;}
		 $msg_id= I('msg_id');
		 if(!empty($msg_id)){$w=$w.' and a.msg_id='.$msg_id;}
		 $action_user_id= I('action_user_id');
		 if(!empty($action_user_id)){$w=$w.' and a.action_user_id='.$action_user_id;}
		 $zd = I('zd');
		 if(!empty($zd)){
		 	switch ($zd){
		 		case 1: $w=$w.' and a.issue_pc>0'; break;
		 		case 2: $w=$w.' and a.issue_phone>0'; break;
		 	}
		 }
		 
		 $query_key= I('query_key');
		 $query_detail=I('query_detail');
		 if(!empty($query_key) && !empty($query_detail)){
		 	switch ($query_key){
		 		//case 1: $w=$w.' and a.list_id='.$query_detail; break;
		 		case 2: $w=$w.' and a.id='.$query_detail; break;
		 	}
		 }
		 
		 if($_SESSION['user_auth']['manager']!=C('IS_ADMIN')){
		 	$w=$w.' and a.create_user_id='.$_SESSION['user_auth']['id'];
		 }
		 $tks = $this->lists($m,$t,$w,'',$f);
		 
// 		 $tk = M('TaskList');
// 		 $tks= $tk->table('task_task_list as a , 
// 		 				   task_task_info as b,
// 		 				   task_type_config as c,
// 		 				   task_type_config as d,
// 		 				   task_type_config as e')
// 		 		  ->field('a.*,b.*,c.name as web_name,d.name as shop_name,e.name as msg_name')
// 		 		  ->where('a.tid=b.id 
// 		 		  		   and b.status=1 
// 		 		  		   and b.step=6
// 		 		  		   and b.web_id=c.id
// 		 		  		   and b.shop_id=d.id
// 		 		  		   and b.shop_id=e.id')
// 		 		  //->limit(1,2)
// 		 		  ->select();
		 $this->assign('tks',$tks);
		 
		 //所有任务
		 $where['status']=1;
		 if($_SESSION['user_auth']['manager']!=C('IS_ADMIN')){
		 	$where['create_user_id']=$_SESSION['user_auth']['id'];
		 }
		 $allTaskCnt = $this->taskCnt('',$where);
		 $this->assign('allTaskCnt',$allTaskCnt);
		 
		 //成功发布任务
		 $where1['status']=1;
		 $where1['is_complete']=1;
		 if($_SESSION['user_auth']['manager']!=C('IS_ADMIN')){
		 	$where1['create_user_id']=$_SESSION['user_auth']['id'];
		 }
		 $compTaskCnt = $this->taskCnt('',$where1);
		 $this->assign('compTaskCnt',$compTaskCnt);
		 
		 //所有清单
		 $where2['status']=1;
		 if($_SESSION['user_auth']['manager']!=C('IS_ADMIN')){
		 	$where2['create_user_id']=$_SESSION['user_auth']['id'];
		 }
		 $allListCnt = $this->taskCnt('v_task_list_ext_user',$where2);
		 $this->assign('allListCnt',$allListCnt);
		 
		 $t = M('taskInfo');
		 $sql = "select count(id) tasks,sum(dfk) dfk,sum(dfh) dfh,sum(dqr) dqr,sum(wc) wc from v_task_info_cnt";
		 if($_SESSION['user_auth']['manager']!=C('IS_ADMIN')){
		 	$sql=$sql.' where create_user_id='.$_SESSION['user_auth']['id'];
		 }
		 $taskInfoCnt = $t->query($sql);
		 $this->assign('taskInfoCnt',$taskInfoCnt);
		 $this->display('task:main');
	}
	
}

?>