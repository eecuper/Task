<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    //'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Home',
    //'MODULE_DENY_LIST'   => array('Common', 'User'),
    //'MODULE_ALLOW_LIST'  => array('Home','Admin'),
	'TMPL_L_DELIM'=>'{', //配置左定界符
	'TMPL_R_DELIM'=>'}', //配置右定界符
	//'TMPL_TEMPLATE_SUFFIX'=>'',//更改模板文件后缀名
	//'TMPL_FILE_DEPR'=>'_',//修改模板文件目录层次

	//默认编码
	DEFAULT_CHARSET=>'utf-8',

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => 'eo~bcx01lS@6K_94aO|BH{QrJ=u2Z-?<%}&/":zi', //默认数据加密KEY

    /* 调试配置 */
    'SHOW_PAGE_TRACE' => false,
    'SHOW_ERROR_MSG'  =>  false,    // 显示错误信息
		
	'DEFAULT_CHARSET'=>'utf-8', // 默认输出编码

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID
    'IS_ADMIN' => 100, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 2, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
    
    'URL_HTML_SUFFIX' => '', //伪静态
    //'URL_DENY_SUFFIX' => 'pdf|ico|png|gif|jpg', //禁止拓展名
    


	//异常处理
	//'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
	//'ERROR_MESSAGE'  =>    '发生错误！', //SHOW_ERROR_MSG=false 时显示错误信息
	//'TMPL_EXCEPTION_FILE' => APP_PATH.'/Public/exception.tpl',//异常信息显示模板
	
	//日志记录
	//'LOG_RECORD' => true, // 部署模式下 开启日志记录
	//'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', //部署模式下  只记录EMERG ALERT CRIT ERR 错误

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
	'DB_TYPE'	 =>'mysql', //设置数据可类型
	'DB_HOST'	 =>'localhost', //设置数据库主机
	'DB_NAME'	 =>'rwxt', //设置数据库名
	'DB_USER'	 =>'root', //设置用户名
	'DB_PWD'	 =>'ldyd', //设置密码
	'DB_PORT'	 =>'3306', //设置端口号
	'DB_PREFIX'	 =>'task_', //设置表前缀
	//'DB_DSN'	 =>'mysql://root:root@localhost:3306/vcard', //DSN方式配置数据库信息
	//'DB_CONFIG2' =>'mysql://root:root@localhost:3306/vfan',

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),
    
    /* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
			'__UPLOAD__' => __ROOT__ . '/Uploads/Picture/',
			'__PUBLIC__' => __ROOT__ . '/Public',
			'__STATIC__' => __ROOT__ . '/Public/static',
			'__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
			'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
			'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
			'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
			'__MOBILE__' => __ROOT__ . '/Public/Mobile',
	),
	//短信获取地址
	'YZM_URL'=>'http://jl.lscity.net/wap/hw/mysl/sendCode.jsp',
			
	//TOKEN 重复提交设置
	'TOKEN_ON'      =>    false,  // 是否开启令牌验证 默认关闭
	'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
	'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
	
	'LIST_ROWS'		=>    10, //每页显示行数
	
	//路由定义
	// 开启路由
//  	'URL_ROUTER_ON'   => true,
//  	'URL_ROUTE_RULES'=>array(
//  			'/index.php' => '/Task/index.php?s=',
//   			'task'=>'/Task/task_info',
//  	),
	
);
