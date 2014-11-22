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
        $this->assign('_num',$page->firstRow);
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
            $t = M('userCharge');
            $w['user_id']=$_SESSION['user_auth']['id'];
            $user_db = $t->where($w)->find();
            $this->assign('user_db',$user_db);
            return $user_db;
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

    //用户MID  对应 user_id 
    public function get_info_by_mid($mid=0){
        $userInfo = M('userInfo');
        if(!empty($mid)){
            $user = $userInfo->where("mid='".$mid."'")->find();
            if($user){
                return $user;
            }
        }
        return false;
    }

    //获取用户资金信息
    public function get_user_charge($user_id=0){
        $cg = M('userCharge');
        if(!empty($user_id)){
            $userCharge = $cg->where('user_id='.$user_id)->find();
            if($userCharge){
                return $userCharge;
            }
        }
        return false;
    }

    //提现对账确认完成
    public function modify_charge_ti_comp($amtLog=array()){
        $arr['flag']=false;
        $arr['msg']='参数类型错误';
        if(is_array($amtLog) && intval($amtLog['user_id'])>0 && intval($amtLog['id'])>0 && intval($amtLog['amt_oper_id'])>0){
            $amt = M('amtLog');
            $data['amt_state']=1;
            $data['amt_comp_date']=time();
            $data['amt_oper_id']=$amtLog['amt_oper_id'];
            $re = $amt->where('id='.$amtLog['id'])->save($data);
            $arr['data']=$data;
            if($re){
                $userCharge = m('userCharge');
                //更新账号信息冻结提现金额
                if($amtLog['amt_type']==0){
                    $data2['cg_dong_db']=array('exp','cg_dong_db-'.floatval($amtLog['amt_num']));
                }else{
                    $data2['cg_dong_yj']=array('exp','cg_dong_yj-'.floatval($amtLog['amt_num']));
                }
                $data2['last_date']=time();
                $arr['data2']=$data2;
                $re = $userCharge->where('user_id='.$amtLog['user_id'])->save($data2);
                if($re){
                    $arr['flag']=true;
                    $arr['msg']='对账完成';
                }else{
                    $arr['msg']='账号资金更新失败';
                }
            }else{
                $arr['msg']='确认状态更新失败';
            }
        }
        return $arr;
    }

    //提现冻结
    public function modify_charge_ti($amtLog=array()){
        $arr['flag']=false;
        $arr['msg']='参数类型错误';
        $code = 0;
        $flag = false;
        if(is_array($amtLog) && intval($amtLog['user_id'])>0){
            $amt = M('amtLog');
            //判断提现工单是否已经存在
            $re = $amt->where("amt_code='".$amtLog['amt_code']."'")->find();

            if($re){
                $code=2;
            }else{
                //添加提现工单
                $re = $amt->add($amtLog);
                $arr['amtLog']=$amtLog;
                if($re){
                    //更新账号信息冻结提现金额
                    if($amtLog['amt_type']==0){
                        $data['cg_amount']=array('exp','cg_amount-'.floatval($amtLog['amt_num']));
                        $data['cg_dong_db']=array('exp','cg_dong_db+'.floatval($amtLog['amt_num']));
                    }else{
                        $data['cg_deposit']=array('exp','cg_deposit-'.floatval($amtLog['amt_num']));
                        $data['cg_dong_yj']=array('exp','cg_dong_yj+'.floatval($amtLog['amt_num']));
                    }
                    $data['last_date']=time();
                    $arr['data']=$data;
                    $userCharge = M('userCharge');
                    $re = $userCharge->where('user_id='.$amtLog['user_id'])->save($data);
                    if($re){
                        $arr['flag']=true;
                        $arr['msg']='申请成功';
                    }else{
                        $code=3;
                    }
                }else{
                    $code=1;
                }
            }
        }

        switch ($code) {
            case 1:
                $arr['msg']='提现记录失败';
                break;
            case 2:
                $arr['msg']='交易号['.$amtLog['amt_code'].']已经存在';
                break;
            case 3:
                $arr['msg']='账号资金更新失败';
                break;
            default:
                break;
        }

        return $arr;
    }

    //发货完成跟新刷客 押金 和DB
    public function modify_charge_bySend($charge=array(),$userInfo=array()){
        $arr['flag']=false;
        $arr['msg']='参数类型错误';
        $code = 0;
        $flag = true;

        if(is_array($charge) && is_array($userInfo)){
            //判断用户参数和操作人参数是否齐全
            if(!(isset($userInfo['mid']) && isset($userInfo['oper_id']))){
                $code=1;
                $flag=false;
            }

            //mid 获取用户信息
            $user = $this->get_info_by_mid($userInfo['mid']);
            if(empty($user)){
                $code=3;
            }
            $arr['obj']=$user;

            //判断是否已经存在资金记录
            $chargeLog = M('chargeLog');
            $log = $chargeLog->where("list_id='".$charge['list_id']."'")->find();
            if($log){
                $code=2;
                $flag=false;
            }

            if($flag){
                //添加资金记录
                //返佣金
                $log[]= array('type_id'=>6,
                              'list_id'=>$charge['list_id'],
                              'charge'=>floatval($charge['db_yongjin']),
                              'oper_id'=>$userInfo['oper_id'],
                              'oper_user_id'=>$user['id'],
                              'remark'=>'返佣金:'.$charge['ext_id'],
                              'ext_id'=>$charge['ext_id'],
                              'charge_date'=>time(),
                              'status'=>1 );
                //返押金
                $log[]= array('type_id'=>5,
                              'list_id'=>$charge['list_id'],
                              'charge'=>floatval($charge['db_yj']),
                              'oper_id'=>$userInfo['oper_id'],
                              'oper_user_id'=>$user['id'],
                              'remark'=>'返押金:'.$charge['ext_id'],
                              'ext_id'=>$charge['ext_id'],
                              'charge_date'=>time(),
                              'status'=>1 );

                //保存
                $re = $chargeLog->addAll($log); 
                $arr['log']=$log;   

                if($re){
                    //更新账户信息
                    $userCharge = D('userCharge');
                    $data['cg_amount']=array('exp','cg_amount+'.floatval($charge['db_yongjin']));  
                    $data['cg_deposit']=array('exp','cg_deposit+'.floatval($charge['db_yj']));  
                    $data['last_date']=time();

                    $arr['data']=$data;
                    $re = $userCharge->where('user_id='.$user['id'])->save($data);
                    if($re){
                        $arr['flag']=true;
                        $arr['msg']='反充成功';
                    }else{
                        $code=5;
                    }
                }else{
                    $code=4;
                }
            }
        }

        switch ($code) {
            case 1:
                $arr['msg']='操作人或目标客户参数不全';
                break;
            case 2:
                $arr['msg']='返充记录已经存在';
                break;
            case 3:
                $arr['msg']='MID 无对应账户信息';
                break;
            case 4:
                $arr['msg']='反充记录添加失败';
                break;
            case 5:
                $arr['msg']='账户信息更新失败';
                break;
            default:
                break;
        }

        //返回结果
        return $arr;
    }
}
