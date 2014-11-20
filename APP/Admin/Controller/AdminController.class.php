<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AdminController extends Controller {

	/**
	 * 空处理
	 */
	public function _empty(){
		$this->display(notFound());
	}
	
    /**
     * 后台控制器初始化
     */
    protected function _initialize(){ }
    
    
    //是否登录
    protected function isLogin(){
    	if(session('?user_auth')){
    		return session('user_auth');
    	}else{
    		return false;
    	}
    }
    
    //登录
    protected function login(){
    	if(IS_POST){
    		$user = D("userInfo");
    		$map['nc']=array('eq',I("post.nc"));
            $pwd = I('post.pwd');
    		$map['pwd']=array('eq',md5($pwd));
    		$userInfo = $user->where($map)->find();
    		if($userInfo){
    			return $userInfo;
    		}else{
    			return false;
    		}
    	}
    }
    
    //分页sql
    protected function listsBySql ($sql=''){
    	if(is_string($sql)){
    		$m = new Model();
    		$total = $m->query("select count(*) from (".$sql.")");
    		
    		if( isset($REQUEST['r']) ){
    			$listRows = (int)$REQUEST['r'];
    		}else{
    			$listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
    		}
    		$page = new \Think\Page($total, $listRows, $REQUEST);
    		if($total>$listRows){
    			$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    		}
    		$p =$page->show();
    		$this->assign('_page', $p? $p: '');
    		$this->assign('_total',$total);
    		
    		return $m->query("select * from (".$sql.") limit ".$page->firstRow.",".$page->listRows);
    	}
    }
    
    //分页查询
	protected function lists ($model,$table='',$where=array(),$order='',$field=true,$base='1=1'){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( (array)$base, /*$REQUEST,*/ (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        
        if(is_string($table) && isset($table)){
        	$total  =   $model->table($table)->where($options['where'])->count();
        }else{
        	$total  =   $model->where($options['where'])->count();
        }
        

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;
        
        $model->setProperty('options',$options);
		
        if(is_string($table) && isset($table)){
        	return $model->table($table)->field($field)->select();
        }
        return $model->field($field)->select();
    }
 
    public function yzm(){
    	$_GPC = array();
    	$_GPC = array_merge($_GET, $_POST, $_GPC);
    	$_GPC = ihtmlspecialchars($_GPC);
    	
    	$loginBill=empty($_GPC['loginBill'])?$_COOKIE['loginBill']:$_GPC['loginBill'];
    	$curlPost ="mobile=".$loginBill;
    	 
    	$ch = curl_init();
    	curl_setopt($ch,CURLOPT_URL,"http://jl.lscity.net/wap/hw/mysl/sendCode.jsp");
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch,CURLOPT_POST, 1);
    	curl_setopt($ch,CURLOPT_POSTFIELDS, $curlPost);
    	
    	$data =curl_exec($ch);
    	$data =str_replace("\r\n\r\n","",$data);
    	//传递过来的callback 作为返回
    	curl_close($ch);
    	$json['success']=true;
    	$json['msg']=$data;
    	$this->ajaxReturn($json);
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
            }   else{
                $list   = $config->where('status=1 and LENGTH(NAME)>0 and user_id='.$_SESSION['user_auth']['id'])->select();
            }
        }
        
        $this->assign('shops',$list);
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
}
