<?php

namespace Home\Model;

use Think\Model;

class TaskListModel extends Model {
	
	protected $_validate = array();
	
	protected $_auto = array(
			array('state','1'),
			array('create_date','time',1,'function')
	);
	
}

?>