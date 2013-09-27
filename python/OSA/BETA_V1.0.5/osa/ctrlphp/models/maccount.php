<?php
class maccount extends osa_model{
	
	
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 用户列表信息获取
	 */
	public function selectUsersinfo($perpage ,$offset ,$name ,$role ,$status){
		$sql = "select A.id ,A.oUserName ,A.oEmail ,A.oStatus , B.oRoleName from osa_users as A left join osa_roles as B on A.oRoleid = B.id where 1";
		if(!empty($name)){
			$sql .= " and  (oUserName like '%$name%' or oRealName like '%$name%' or oNickName like '%$name%') ";
		}
		if(!empty($role)){
			$sql .= " and  oRoleid=$role ";
		}
		if($status !==''){
			$sql .= " and  A.oStatus=$status ";
		}
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 获取用户数量
	 */
	public function getNumfromUsers($name ,$role ,$status){
		$sql = "select count(id) as num from osa_users where 1";
		if(!empty($name)){
			$sql .= " and  (oUserName like '%$name%' or oRealName like '%$name%' or oNickName like '%$name%') ";
		}
		if(!empty($role)){
			$sql .= " and  oRoleid=$role ";
		}
		if($status !==''){
			$sql .= " and  oStatus=$status ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 获取角色表信息(角色必须是启用状态)
	 */	
	public function getRolesInfo(){
		$sql = "select id , oRoleName from osa_roles where oStatus = 1";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 修改用户的状态 启用|禁用
	 */
	public function setUserStatus($uid ,$status){
		$sql = "update osa_users set oStatus = $status where id=".$uid;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 删除用户
	 */
	public function delUserInfo($id){
		$sql = "delete from osa_users where id =".$id;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/***
	 * 初始化用户密码
	 */
	public function initPasswd($uid ,$uname){
		$initpasswd = osa_passwdhash('osapub').substr(osa_passwdhash(mb_substr($uname,0,2,'utf-8')),3,6);
		$sql = "update osa_users set oPassword ='$initpasswd' where id=".$uid;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 验证用户名
	 */
	public function verifiUsername($username){
		$sql = "select id from osa_users where oUserName='$username'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 验证邮箱
	 */
	public function verifiEmail($email){
		$sql = "select id from osa_users where oEmail='$email'";
		return $this->db->queryFetchAllAssoc($sql);
	}	
	
	/**
	 * 添加用户
	 */
	public function insertUserInfo($userinfo){
		foreach ($userinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_users ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 根据id获取用户信息
	 */
	public function getUserByid($id){
		$sql = "select * from osa_users where id=".$id;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据id修改用户信息
	 */
	public function updateUserInfo($userinfo,$uid){
		 foreach ($userinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_users set $query where id=$uid";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/*************************************************角色处理*************************************************/
	/**
	 * 角色列表信息获取
	 */
	public function selectRolesInfo($perpage ,$offset){
		$sql = "select * from osa_roles ";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 获取角色数量
	 */
	public function getNumfromRoles(){
		$sql = "select count(id) as num from osa_roles ";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 修改角色的状态 启用|禁用
	 */
	public function setRoleStatus($roleid ,$status){
		$sql = "update osa_roles set oStatus = $status where id=".$roleid;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 通过角色id修改用户的状态为禁用
	 */
	public function setUserStatusByRid($roleid){
		$sql ="update osa_users set oStatus = 0 where oRoleid =".$roleid ;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 删除角色
	 */
	public function delRoleInfo($id){
		$sql = "delete from osa_roles where id =".$id;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 验证角色名是否唯一
	 */
	public function verifiRolename($name){
		$sql = "select id from osa_roles where oRoleName='$name'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 添加角色信息
	 */
	public function insertRoleInfo($roleinfo){
		foreach ($roleinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_roles ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 
	 */
	public function getRoleByid($id){
		$sql = "select * from osa_roles where id=".$id;
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 
	 */
	public function updateRoleInfo($roleinfo ,$id){
		foreach ($roleinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_roles set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
}