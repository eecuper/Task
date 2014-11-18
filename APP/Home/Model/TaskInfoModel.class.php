<?php

namespace Home\Model;

use Think\Model;

class TaskInfoModel extends Model {
	
	protected $_validate = array();
	
	protected $_auto = array(
			array('status','1'),
			array('create_date','time',1,'function')
	);
	
	
	
}

?>