<?php
class mdevice extends osa_model{
	
	public function __construct(){
		
		parent::__construct();
	}
	
	/*************************************start--- 记录添加业务  ---start*******************************/
	/**
	 * osa_device insert info
	 * $devinfo type array
	 */
	public function device_insert($devinfo){
		
		foreach ($devinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_device ($keys) values($query)";
		//osa_log_debug($sql);
		$this->db->exec($sql);
		return $this->db->lastInsertId();
		
	}
	
	/**
	 * osa_ipinfo insert info
	 */
	public function ipinfo_insert($ip){
		
		$sql = "insert into osa_ipinfo (oIp,oStatus ,oIsAlive) values('$ip','正常' ,'1')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	
	}
	
	
	/**
	 * osa_devtype insert info
	 */
	public function devtype_insert($typename,$typedes){
		
		$sql = "insert into osa_devtype (oTypeName,oTypeDes) values('$typename','$typedes')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	
	/**
	 * osa_devroom insert info
	 */
	public function devroom_insert($roomname,$roomdes){
	
		$sql = "insert into osa_devtype (oTypeName,oTypeDes) values('$roomname','$roomdes')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
		
	}
	
	
	public function devlabel_insert($labelname){
		
		$sql = "insert into osa_devlabel (oLabelName,oLabelRate) values('$labelname',0)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/*************************************end--- 记录添加业务  ---end*******************************/
	
	
	
	/*************************************start--- 修改编辑业务  ---start*******************************/
	
	public function device_update($id ,$devinfo){
	
		foreach ($devinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_device set $query where id=$id";
		$this->db->exec($sql);
		
	}
	
	
	public function device_pause($id){
	
		$sql = "update osa_device set oIsStop =1 where id=".$id;
		$this->db->exec($sql);
	}
	
	
	public function ip_pause($id){
	
		$sql = "update osa_ipinfo set oIsStop =1 where id=".$id;
		$this->db->exec($sql);
	}
	
	
	public function ip_open($id){
		
		$sql = "update osa_ipinfo set oIsStop =0 where id=".$id;
		$this->db->exec($sql);
	}
	
	
	public function device_open($id){
		
		$sql = "update osa_device set oIsStop =0 where id=".$id;
		$this->db->exec($sql);
	}
	
	
	/*************************************end--- 修改编辑业务  ---end*******************************/
	
	/*************************************start--- 记录删除业务  ---start*******************************/
	
	/**
	 * osa_device delete
	 */
	public function device_delete($id){
		
		$sql = "delete from osa_device where id=".$id;
		$this->db->exec($sql);
	}
	
	/**
	 * osa_ipinfo delete
	 */
	public function ipinfo_delete($id){
		
		$sql = "delete from osa_ipinfo where id=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_collect_alarm delete
	 */
	public function alarm_delete($id){
	
		$sql = "delete from osa_collect_alarm where oIpid=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_collect_data delete
	 */
	public function data_delete($id){
	
		$sql = "delete from osa_collect_data where oIpid=".$id;
		$this->db->exec($sql);
	}
	
	
	/*************************************end--- 记录删除业务  ---end*******************************/
	
	
	/*************************************start--- 查询业务  ---start*******************************/
	
	/**
	 * osa_devtype search
	 */
	public function devtype_select(){
		
		$sql = "select * from osa_devtype order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_devroom search
	 */
	public function devroom_select(){
		
		$sql = "select * from osa_devroom order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_devlabel search
	 */
	public function devlabel_select(){
		$sql = "select * from osa_devlabel order by oLabelRate desc limit 10";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_devipinfo search
	 */
	public function ipinfo_select(){
	
		$sql = "select id,oIp from osa_ipinfo order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
		
	}
	
	
	/**
	 * ajax 验证ip是否存在
	 */
	public function ip_isexist($ip){
		
		$sql = "select id from osa_ipinfo where oIp='$ip'";
		return $this->db->queryFetchAllAssoc($sql);
		
	}
	
	
	/**
	 * device search
	 */
	public function device_select($room, $search='' ,$status=''){
	
		$sql = "select A.* ,B.oStatus from osa_device as A left join osa_ipinfo as B on A.oIpid=B.id where 1";
		if(!empty($room)){
			$sql .= " and A.oEngineRoom='$room' ";
		}
		if(!empty($search)){
			$sql .= " and (A.oDevName like '%$search%' or A.oIp like '%$search%' or A.oTypeName like '%$search%' or A.oDevLabel like '%$search%') ";
		}
		if(!empty($status)){
			$sql .=" and B.oStatus = '$status'";
		}
		$sql .=" order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * device retrieve
	 */
	public function device_retrieve($room='',$type='',$label=''){
		
		$sql = "select id from osa_device where 1";
		if(!empty($room)){
			$sql .= " and oEngineRoom='$room' ";
		}
		if(!empty($type)){
			$sql .= " and oTypeName='$type' ";
		}
		if(!empty($label)){
			$sql .=" and oDevLabel like '%$label%' ";
		}
		$sql .=" order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
		
	}
	
	
	
	/**
	 * device search by id
	 * @param  $id
	 */
	public function device_select_id($id){
		
		$sql = "select * from osa_device where id=".$id;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * device search by room
	 */
	public function device_select_room($roomname){
		
		$sql = "select * from osa_device where oRoomName = '$roomname'";
		return $this->db->queryFetchAllAssoc($sql);
		
	}
	
	
	/*************************************end--- 查询业务  ---end*******************************/
	
	
	/************************************start--  其他业务  --start*****************************/
	
	/**
	 * get devtype id
	 */
	public function devtype_getid($typename){
	
		$sql = "select id from osa_devtype where oTypeName = '$typename'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return $rs[0]['id'];
		}else{
			$sql = "insert into osa_devtype(oTypeName,oTypeDes) values('$typename','')";
			$this->db->exec($sql);
			return $this->db->lastInsertId();
		}
	}
	
	
	/**
	 * get devroom id
	 */
	public function devroom_getid($roomname){
	
		$sql = "select id from osa_devroom where oRoomName = '$roomname'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]){
			return $rs[0]['id'];
		}else{
			$sql = "insert into osa_devroom (oRoomName) values('$roomname')";
			$this->db->exec($sql);
			return $this->db->lastInsertId();
		}
	}
	
	
	/**
	 * device label hots compute
	 */
	public function devlabel_hots($labels){
	
		$label = explode(',',$labels);
		foreach ($label as $key){
			$sql = "select * from osa_devlabel where oLabelName = '$key'";
			$rs = $this->db->queryFetchAllAssoc($sql);
			if($rs[0]){
				$num = $rs[0]['oLabelRate']+1;
				$sql = "update osa_devlabel set oLabelRate= $num where id=".$rs[0]['id'];
				$this->db->exec($sql);				
			}else{
				$sql = "insert into osa_devlabel (oLabelName,oLabelRate) values('$key',1)";
				$this->db->exec($sql);
			}
		}
	}
	
	/************************************end--  其他业务  --end*****************************/
	
}