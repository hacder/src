<?php
class mpanel extends osa_model{
	
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 搜索用户
	 */
	public function searchuser($keywork = ''){
		$sql = "select oUserName from osa_users where oUserName like '%$keyword%' or oRealName like '%$keyword%' or oNickName like '%$keyword%'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 插入记录到osa_sysconfig
	 */
	public function insertSysconfig($info){
		foreach ($info as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_sysconfig ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 插入记录到osa_alarms
	 */
	public function insertAlarms($info){
		foreach ($info as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_alarms ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 更新记录 osa_alarms
	 */
	public function updateAlarms($info,$id){
		foreach ($info as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_alarms set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 获取报警项目信息
	 */
	public function selectAlarmsInfo($perpage ,$offset ,$search ,$starttime ,$endtime){
		$sql = "select id ,oItemName ,oItemClass ,oItemType ,oServerList , oCheckRate ,oIsAllow from osa_alarms ";
		$sql .=" where oAddTime>'$starttime' and oAddTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oItemName like '%$search%' or oItemClass like '%$search%' or oItemType like '%$search%' or oServerList like '%$search%') ";
		}
		$sql .= " order by oAddTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);		
	}
	
	/**
	 * 统计报警项目数量
	 */
	public function getNumfromAlarms($search ,$starttime ,$endtime){
		$sql = "select count(id) as num from osa_alarms ";
		$sql .=" where oAddTime>'$starttime' and oAddTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oItemName like '%$search%' or oItemClass like '%$search%' or oItemType like '%$search%' or oServerList like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 开启报警项目
	 */
	public function startAlarms($id){
		$now = date('Y-m-d H:i:s' ,time());
		$sql ="update osa_alarms set oIsAllow = '1' , oUpdateTime = '$now' ,oStartTime = '$now' where id= ".$id ;
		$this->db->exec($sql);
	}
	
	/**
	 * 停止报警项目
	 */
	public function stopAlarms($id){
		$now = date('Y-m-d H:i:s' ,time());
		$sql ="update osa_alarms set oIsAllow = '0' , oUpdateTime = '$now' ,oStopTime = '$now' where id= ".$id ;
		$this->db->exec($sql);
	}
	
	/**
	 * 删除报警项目
	 */
	public function delAlarms($id){
		$sql = "delete from osa_alarms where id =".$id;
		$this->db->exec($sql);
	}
	
	/**
	 * 查询报警项目信息 by id
	 */
	public function selectAlarmsbyid($id){
		$sql ="select id ,oItemName ,oItemConfig ,oServerList , oCheckRate ,oAlarmNum ,oIsRemind ,oNotiObject from osa_alarms where id =$id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/***
	 * 查询osa_noticonfig表
	 */
	public function selectNotiConfig(){
		$sql ="select * from osa_noticonfig where id =1";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 插入 osa_noticonfig表
	 * 
	 */
	public function insertNotiConfig($info){
		foreach ($info as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_noticonfig ($keys) values($query)";
		$this->db->exec($sql);
	}
	
	/**
	 * 更新 osa_noticonfig表
	 */
	public function updateNotiConfig($info){
		foreach ($info as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_noticonfig set $query where id=1";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
}

