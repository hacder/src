<?php
class maccount extends osa_model{


	public function __construct(){
	
		parent::__construct();
	}

	/************************************start--- Account insert ---start****************************************/
	
	/**
	 * osa_roles insert
	 */
	public function roles_insert($roles){
	
		foreach ($roles as $key => $value)
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
	 * osa_users insert
	 */
	public function users_insert($users){
	
		foreach ($users as $key => $value)
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
	 * 
	 */
	public function personset_insert($personinfo){
	
		foreach ($personinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_global_config ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	/************************************end--- Account insert ---end****************************************/
	
	
	/************************************start--- Account update ---start****************************************/
	
	/**
	 * osa_roles update
	 */
	public function roles_update($id,$roles){
	
		foreach ($roles as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_roles set $query where id=$id";
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_users update
	 */
	public function users_update($id,$users){
	
		foreach ($users as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_users set $query where id=$id";
		$this->db->exec($sql);
	}
	
	
	/**
	 * 
	 */
	public function personset_update($personinfo,$userid){
		foreach ($personinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_global_config set $query where oUserid=$userid";
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_roles pause
	 */
	public function roles_pause($id){
	
		$sql = "update osa_roles set oStatus='1' where id=".$id;
		$this->db->exec($sql);
		$sql = "update osa_users set oStatus='1' where oRoleid=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_roles open
	 */
	public function roles_open($id){
	
		$sql = "update osa_roles set oStatus='0' where id=".$id;
		$this->db->exec($sql);	
	}
	
	
	/**
	 * osa_roles pause
	 */
	public function users_pause($id){
	
		$sql = "update osa_users set oStatus='1' where id=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_roles open
	 */
	public function users_open($id){
	
		$sql = "update osa_users set oStatus='0' where id=".$id;
		$this->db->exec($sql);	
	}
	
	
	/************************************end--- Account update ---end****************************************/
	
	
	/************************************start--- Account delete ---start****************************************/

	/**
	 * osa_roles delete
	 */
	public function roles_delete($id){
	
		$sql = "delete from osa_roles where id=".$id;
		$this->db->exec($sql);	
		$sql = "delete from osa_users where oRoleid=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_users delete
	 */
	public function users_delete($id){
	
		$sql = "delete from osa_users where id=".$id;
		$this->db->exec($sql);
	}
	
	
	/************************************end--- Account delete ---end****************************************/
	
	
	/************************************start--- Account select ---start****************************************/

	/**
	 * osa_roles select
	 */
	public function roles_select(){
	
		$sql = "select * from osa_roles where oStatus='0' order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}

	
	/**
	 * osa_users select
	 */
	public function users_select(){
	
		$sql = "select * from osa_roles where oStatus='0' order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_users passwd select
	 */
	public function passwd_select($id){
	
		$sql = "select oPassword from osa_users  where id=".$id;
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return $rs[0]['oPassword'];
		}else{
			return false;
		}
	}
	
	
	/**
	 * osa_users select by id
	 */
	public function users_select_id($id){
	
		$sql = "select A.*,B.oRoleName from osa_users as A left join osa_roles as B on A.oRoleid = B.id where A.id=".$id;
		return $this->db->queryFetchAllAssoc($sql);
		
	}
	
	
	/**
	 * osa_roles select by id
	 */
	public function roles_select_id($id){
	
		$sql = "select * from osa_roles where id=".$id;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_roles select by name 
	 * return true or false
	 */
	public function roles_select_name($name){
	
		$sql = "select id from osa_roles where oRoleName='$name'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return $rs[0]['id'];
		}else{
			return false;
		}
	}
	
	
	/**
	 * osa_users select by name
	 * return true or false
	 */
	public function users_select_name($name){
	
		$sql = "select id from osa_users where oUserName='$name'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * osa_users select by email
	 * return true or false
	 */
	public function users_select_email($email){
		
		$sql = "select id from osa_users where oEmail='$email'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * osa_users select by phone
	 * return true or false
	 */
	public function users_select_phone($phone){
	
		$sql = "select id from osa_users where oPhone='$phone'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return true;
		}else{
			return false;
		}
	}
	
	
	public function shortcut_select_userid($userid){
	
		
		$sql = "select oShortCut from osa_users where id=".$userid;
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['oShortCut'];
	}
	
	
	/************************************end--- Account select ---end****************************************/

	
	/************************** page per list ********************************/
	
	/**
	 * select roles per page
	 */
	public function roles_select_page($search ,$perpage ,$offset){
		$sql = "select * from osa_roles where 1";
		if(!empty($search)){
			$sql .=" and oRoleName like '%$search%' ";
		}
		$sql .=" order by id desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * select roles num
	 */
	public function roles_select_num($search){
		$sql = "select count(id) as num from osa_roles where 1";
		if(!empty($search)){
			$sql .=" and oRoleName like '%$search%' ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	
	/**
	 * select users per page
	 */
	public function users_select_page($search ,$perpage,$offset){
	
		$sql = "select A.*,B.oRoleName from osa_users as A left join osa_roles as B on A.oRoleid = B.id";
		if(!empty($search)){
			$sql .=" where (A.oUserName like '%$search%' or A.oEmail like '%$search%') ";
		}
		$sql .= " order by A.id desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * select users num
	 */
	public function users_select_num($search){
		
		$sql = "select count(id) as num from osa_users where 1 ";
		if(!empty($search)){
			$sql .=" and (oUserName like '%$search%' or oEmail like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * select personset info
	 */
	public function personset_select($userid){
		
		$sql = "select * from osa_global_config where oUserid = $userid";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return $rs[0];
		}
		return false;
	}

}