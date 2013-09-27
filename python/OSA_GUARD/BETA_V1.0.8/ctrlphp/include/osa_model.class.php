<?php
class osa_model{
	
	var $db = null;
	
	public function __construct(){
		$this->db = new osa_db(OSA_MYSQL_CONN_DNS,OSA_MYSQL_CONN_USER,OSA_MYSQL_CONN_PASSWD,OSA_MYSQL_CONN_CHARSET,OSA_MYSQL_CONN_PRIFIX);
	}
	
	/**
	 * 通过用户id获取用户的权限列表
	 */
	public function getRoleByUid($id){
		$sql = "select oPerArr from osa_roles as A right join osa_users as B on A.id = B.oRoleid where B.id =".$id;
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['oPerArr'];
	}
	
	
	/**
	 * 加载其他模型
	 */
	public function loadmodel($modelname,$param =''){
		$model_file = OSA_PHPMODEL_PATH.$modelname.'.php';
		if(!file_exists($model_file)) 
		{ 
			$data['error'] = '模型不存在' . $model_file  ;
			$this->loadview('error',$data);
			exit('');
		}		
		include($model_file);//存在, 则引入
		if(stripos($modelname ,'/')){
			$model = explode('/',$modelname);
			$n = count($model);
			$class = $model[$n-1];
		}
		else{
			$class = $modelname;
		}//获得模型类名
		if(!class_exists($class))
		{
			$data['error'] = '未定义的模型' . $class  ;
			$this->loadview('error',$data);
			exit('');
		} 	
		$model = new $class($param);	//实例化模型类
		return $model;	
	}
}