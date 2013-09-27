<?php
class osa_model{
	
	var $db = null;
	
	public function __construct(){
		$this->db = new osa_db(OSA_MYSQL_CONN_DNS,OSA_MYSQL_CONN_USER,OSA_MYSQL_CONN_PASSWD,OSA_MYSQL_CONN_CHARSET,OSA_MYSQL_CONN_PRIFIX);
	}
	
	/**
	 * 添加系统日志
	 */
	public function saveSysLog($type=1 ,$title ,$text ,$username){
		$time = date("Y-m-d H:i:s",time());
		$sql = "INSERT INTO osa_syslog(oTypeid,oUserName,oLogTitle,oLogText,oLogAddTime) VALUES ('$type','$username','$title','$text','$time')";
		$this->db->exec($sql);
	}
	/**
	 * 通过用户id获取用户的权限列表
	 */
	public function getRoleByUid($id){
		$sql = "select oPerArr from osa_roles as A right join osa_users as B on A.id = B.oRoleid where B.id =".$id;
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['oPerArr'];
	}
}