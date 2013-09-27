<?php
class mdevice extends osa_model{
	
	//构造函数
	public function __construct(){
		parent::__construct();
	}
	
	/********************************************添加信息*****************************************/
	//添加设备分组信息,返回插入的id值
	public function insertDeviceGroup($groupname,$description = ''){
		$time = date("Y-m-d H:i:s",time());
		$sql = "insert into osa_devgroup values(NULL ,'$groupname' ,'$description','$time',NULL)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	//添加设备类型信息,返回插入的id值
	public function insertDeviceType($typename ,$description=''){
		$sql = "insert into osa_devtype values(NULL ,'$typename' ,'$description')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	//添加ip组信息
	public function insertIpinfo($ip){
		$sql="insert into osa_ipinfo (oIp,oStatus ,oIsAlive,oIsAliveNum) values('$ip','正常' ,'0',0)";	
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	//添加设备信息
	public function insertDeviceInfo($devinfo){
		foreach ($devinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_devinfo ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**************************************查询信息**********************************************/
	
	//查询设备分组信息
	public function selectDeviceGroup(){
		$sql = "select * from osa_devgroup ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	//查询设备类型信息
	public function selectDeviceType(){
		$sql = "select * from osa_devtype ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	//查询ip信息
	public function selectIpinfo($ip){
		$sql = "select * from osa_ipinfo where oIp='$ip'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	//查询设备名是否已存在
	public function getInfoBydevname($devname){
		$sql = "select id from osa_devinfo where oDevName='$devname'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	//查询设备信息
	public function selectDeviceInfo($perpage ,$offset ,$search='' ,$starttime ,$endtime ,$region='' , $typeid='' ){
		$sql = "select A.* ,C.oTypeName from osa_devinfo as A left join osa_devtype as C on A.oTypeid=C.id";
		$sql .=" where A.oCreateTime>'$starttime' and A.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (A.oDevName like '%$search%' or A.oIp like '%$search%') ";
		}
		if(!empty($region)){
			$sql .= " and  A.oPlace='$region' ";
		}
		if(!empty($typeid)){
			$sql .= " and  A.oTypeid=$typeid ";
		}
		$sql .=" order by A.oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	//统计查询数量
	public function getNumfromDevinfo($search='' ,$starttime ,$endtime ,$region='' , $typeid='' ){
		$sql = "select count(A.id) as num from osa_devinfo as A left join osa_devtype as C on A.oTypeid=C.id";
		$sql .=" where A.oCreateTime>'$starttime' and A.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (A.oDevName like '%$search%' or A.oIp like '%$search%') ";
		}
		if(!empty($region)){
			$sql .= " and  A.oPlace='$region' ";
		}
		if(!empty($typeid)){
			$sql .= " and  A.oTypeid=$typeid ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];	
	}
	
	//查询设备信息by id
	public function getDevinfoByid($id){
		$sql = "select A.* ,C.oTypeName from osa_devinfo as A left join osa_devtype as C on A.oTypeid=C.id where A.id=$id";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	//查询设备地区信息
	public function getRegionfromDevinfo(){
		$sql = "select id , oPlace from osa_devinfo group by oPlace";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**************************************修改信息***************************************/
	//编辑设备信息
	public function updateDeviceInfo($id ,$devinfo){
		foreach ($devinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_devinfo set $query where id=$id";
		$this->db->exec($sql);
		//return $this->db->lastInsertId();
	}
	
	public function delDeviceInfo($id){
		$sql = "delete from osa_devinfo where id=$id";
		$this->db->exec($sql);
	}
	
	public function delIpInfo($id){
		$sql = "delete from osa_ipinfo where id=$id";
		$this->db->exec($sql);
	}
	
	/******************************************设备分组管理相关函数**************************************/
	/**
	 * 根据条件获取分组信息
	 */
	public function getGroupInfo($perpage ,$offset ,$search,$starttime ,$endtime){
		$sql = "select * from osa_devgroup where oAddTime > '$starttime' and oAddTime < '$endtime' ";
		if(!empty($search)){
			$sql .=" and oGroupName like '%$search%'";
		}
		$sql .= " order by oAddTime desc";
		$sql .=" limit $offset ,$perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据条件获取分组信息数量
	 */
	public function getGrpinfoNum($search ,$starttime ,$endtime){
		$sql = "select count(id) as num from osa_devgroup where oAddTime > '$starttime' and oAddTime < '$endtime' ";
		if(!empty($search)){
			$sql .=" and oGroupName like '%$search%'";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	/**
	 * 设备分类
	 */
	public function selectServerType(){
		$sql = "select * from osa_devtype ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	/**
	 * 设备分组
	 */
	public function selectServerGroup(){
		$sql = "select * from osa_devgroup ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据分组名获取信息
	 */
	public function getInfoBygname($name){
		$sql = "select id from osa_devgroup where oGroupName='$name'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据设备分类名获取信息
	 */
	public function getInfoBytname($name){
		$sql = "select id from osa_devtype where oTypeName='$name'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据日志分类名获取信息
	 */
	public function getLogTypeByname($name){
		$sql = "select id from osa_syslog_cfg where oTypeText='$name'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据知识分类名获取信息
	 */
	public function getKnowTypeByname($name){
		$sql = "select id from osa_repository_type where oTypeName='$name'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 根据文件分类名获取信息
	 */
	public function getFileTypeByname($name){
		$sql = "select id from osa_filetype where oTypeName='$name'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	//添加设备分组信息
	public function insertDevGroup($groupinfo){
		foreach ($groupinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_devgroup ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 根据id获取分组信息
	 */
	public function getGroupByid($id){
		$sql = "select * from osa_devgroup where id=".$id;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 编辑分组信息
	 */
	public function updateDevGroup($groupinfo,$id){
		foreach ($groupinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_devgroup set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 删除设备分组
	 */
	public function delDevGroup($id){
		$sql = "delete from osa_devgroup where id=".$id ;
		$this->db->exec($sql);
	}
	
	/**
	 * 更新分组的serverlist
	 */
	public function setServerList($id ,$ip){
		$sql = "select oServerList from osa_devgroup where id=".$id;
		$rs = $this->db->queryFetchAllAssoc($sql);
		$slist = $rs[0]['oServerList'];
		$result = $this->check_iplist($slist,$ip);
		if(!$result){//ip不在oServerList 里面更新分组表
			$slist .='|'.$ip;
			$slist = trim($slist,'|');
			$newsql = "update osa_devgroup set oServerList = '$slist' where id=".$id;
			$this->db->exec($newsql);
		}
	}
	
	/**
	 * 验证ip是否存在在一个字符串中
	 */
	public function check_iplist($serverlist ,$ip){
		if(empty($serverlist)){
			return false ;
		}
		$perarr = explode('|',$serverlist);
		foreach ($perarr as $key){
			if($key == $ip)
				return true ;
		}
		return false;
	}
	
	/**
	 * 删除设备时 分组表处理
	 */
	public function dealDevGroup($iparr){
		//首先遍历出分组序列
		$sql = "select id ,oServerList from osa_devgroup";
		$result = $this->db->queryFetchAllAssoc($sql);
		$updatearr = array();
		foreach ($result as $key){
			$info = array();
			$slist = $key['oServerList'];
			$slist = $this->dealiplist($iparr,$slist);
			if($slist != $key['oServerList']){//有变化的才更新
				$info['id']=$key['id'];
				$info['oServerList'] = $slist ;
				array_push($updatearr,$info);
			}
		}
		if(!empty($updatearr)){//说明有变化，要更新。
			foreach ($updatearr as $key) {
				$serverlist = $key['oServerList'];
				$newsql = "update osa_devgroup set oServerList='$serverlist' where id=".$key['id'];
				$this->db->exec($newsql);
			}
		}	
	}
	
	/**
	 * ip 如果存在 ip字符串中就删除它让后合成新的ip字符串
	 */
	public function dealiplist($iparr ,$iplist){
		$listarr = explode('|',$iplist);
		$result = array_diff($listarr , $iparr);
		return implode('|',$result);
	}
}