<?php
class manage extends osa_model{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取表osa_ipinfo跟表osa_devinfo中的信息
	 * input 设备名 |ip|服务器状态
	 * return array(0=>array('id'=>$id ,)) 
	 */
	public function selectIpinfo($perpage ,$offset ,$devname ,$ip ,$status){
		$sql = "select A.* ,B.oDevName from osa_ipinfo as A left join osa_devinfo as B on A.id = B.oIpid where 1";
		if(!empty($devname)){
			$sql .= " and B.oDevName like '%$devname%'";
		}
		if(!empty($ip)){
			$sql .= " and A.oIp like '%$ip%'";
		}
		if(!empty($status)){
			$sql .= " and A.oStatus = '$status'";
		}
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 根据条件获取表osa_ipinfo中表的记录条数
	 * input 设备名 |ip|服务器状态
	 * return int num 
	 */
	public function getNumfromIpinfo($devname ,$ip ,$status){
		$sql = "select count(A.id) as num from osa_ipinfo as A left join osa_devinfo as B on A.id = B.oIpid where 1";
		if(!empty($devname)){
			$sql .= " and B.oDevName like '%$devname%'";
		}
		if(!empty($ip)){
			$sql .= " and A.oIp like '%$ip%'";
		}
		if(!empty($status)){
			$sql .= " and A.oStatus = '$status'";
		}
		//$sql .= " limit $offset , $perpage";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 根据osa_ipinfo 表中id 来获取 设备信息
	 * input id
	 * return array()
	 */
	public function getDevinfoByid($id){
		$sql = "select * from osa_devinfo as A left join osa_ipinfo as B on A.oIpid = B.id left join osa_devtype as C on A.oTypeid = C.id where B.id=$id";
		return $rs = $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 获取服务器的详细信息(CPU信息、内存状态、硬盘状态、网络状态、负载状态、运行时间、登录人数等)
	 * input array() $rlist 服务器端执行指令返回结果数组
	 * retutn array()
	 */
	public function getDetailinfo($rlist){
		$detail_rlist = array();
		foreach($rlist as $r_key => $r_value){	//数组分解
			$r_key='_'.$r_key;
			if(strpos($r_key,'check_disk_')){					
				$d_key=substr($r_key,strlen($r_key) -1 ,1);
				$disk_list[$d_key]=$r_value;				
			}else if(strpos($r_key,'get_bandwidth_')){		
				$n_key=substr($r_key,strlen($r_key) -1 ,1);
				$net_list[$n_key]=$r_value;	
			}else if(strpos($r_key,'get_10_of_cpu_')){
				$c_key=substr($r_key,strlen($r_key) -1 ,1);
				$cpu_list[$c_key]=$r_value;	
			}else if(strpos($r_key,'get_10_of_mem_')){	
				$m_key=substr($r_key,strlen($r_key) -1 ,1);
				$mem_list[$m_key]=$r_value;	
			}else if(strpos($r_key,'check_cpuinfo')){		
				$detail_rlist['cpuinfo']=$r_value;	
			}else if(strpos($r_key,'check_meminfo')){
				$detail_rlist['meminfo']=$r_value;	
			}else if(strpos($r_key,'check_load')){					
				$detail_rlist['topinfo']=$r_value;	
			}else if(strpos($r_key,'getonlineuser')){
				$detail_rlist['logininfo']=$r_value;	
			}else if(strpos($r_key,'getonlinetime')){
				$detail_rlist['uptimeinfo']=$r_value;	
			}else if(strpos($r_key,'check_service')){
				$detail_rlist['seviceinfo']=$r_value;
			}		
		}
		$detail_rlist['disklist'] = $disk_list;
		$detail_rlist['cpulist'] = $cpu_list;
		$detail_rlist['netlist'] = $net_list;
		$detail_rlist['memlist'] = $mem_list;
		return $detail_rlist;
	}
	
	/**************************************************脚本处理函数**********************************************************/
	/**
	 * 获取脚本库表信息
	 * input $offset ,$perpage ,$search ,$startime ,$endtime
	 * return array()
	 */
	public function selectScriptInfo($perpage ,$offset ,$search='' ,$starttime ,$endtime ){
		$sql = "select * from osa_script ";
		$sql .=" where oCreateTime>'$starttime' and oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oScriptName like '%$search%' or oScriptLabel like '%$search%' or oScriptPath like '%$search%') ";
		}
		$sql .= " order by oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 按条件统计script信息数量
	 * input $search ,$startime ,$endtime
	 * return $num
	 */
	public function getNumfromScript($search='' ,$starttime ,$endtime ){
		$sql = "select count(id) as num from osa_script ";
		$sql .=" where oCreateTime>'$starttime' and oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oScriptName like '%$search%' or oScriptLabel like '%$search%' or oScriptPath like '%$search%') ";
		}
		
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];	
	}
		
	/**
	 * 根据脚本名字获取信息
	 * input $scriptname
	 * return array()
	 */
	public function getInfoByscriptname($scriptname){
		$sql = "select * from osa_script where oScriptName = '$scriptname'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 脚本添加记录
	 * input array('oScriptName'=>$_POST['oScriptName'],'oScriptLabel'=>$_POST['oScriptLabel'],'oScriptPath' => $_POST['oScriptPath'],'oIsShow'=>0,'oCreateTime'=>date('Y-m-d H:i:s',time()),'oUpdateTime'=>date('Y-m-d H:i:s',time()));
	 * return 
	 */
	public function insertScript($scriptinfo){
		foreach ($scriptinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_script ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	
	/**
	 * 删除脚本信息
	 */
	public function delScript($id){
		$sql = "delete from osa_script where id=$id";
		$this->db->exec($sql);
	}
	
	/**
	 * 根据id来获取osa_script表信息
	 */
	public function getScriptByid($id){
		$sql = "select * from osa_script where id=$id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * 更新 script 
	 * input $id ,$scriptinfo
	 * return str
	 */
	public function updateScript($id,$scriptinfo){
		foreach ($scriptinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_script set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 分享script
	 * @param unknown_type $id
	 */
	public function setScriptShare($id){
		$sql ="update osa_script set oIsShare=1 where id=".$id;
		$this->db->exec($sql);
	}
	
	/******************************************************操作日志（记录）处理函数**********************************************/
	/***
	 * 获取系统日志表信息
	 * input $offset ,$perpage ,$search ,$startime ,$endtime
	 * return array()
	 */
	public function selectLogInfo($perpage ,$offset ,$search ,$starttime ,$endtime ){
		$sql = "select A.* ,oTypeText from osa_syslog as A left join osa_syslog_cfg as B on A.oTypeid = B.id ";
		$sql .=" where oLogAddTime>'$starttime' and oLogAddTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oLogTitle like '%$search%' or oLogLabel like '%$search%') ";
		}
		$sql .= " order by oLogAddTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 按条件统计系统日志表信息
	 * input $search ,$startime ,$endtime
	 * return $num
	 */
	public function getNumfromLog($search ,$starttime ,$endtime ){
		$sql = "select count(id) as num from osa_syslog ";
		$sql .=" where oLogAddTime>'$starttime' and oLogAddTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oLogTitle like '%$search%' or oLogLabel like '%$search%') ";
		}
		//$sql .= " limit $offset , $perpage";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 *  删除syslog 记录
	 */
	public function delSyslog($id){
		$sql = "delete from osa_syslog where id=$id";
		$this->db->exec($sql);
	}
	
	/**
	 * 获取系统日志类型
	 */
	public function selectLogType(){
		$sql = "select * from osa_syslog_cfg";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 插入日志类型
	 */
	public function insertLogtype($name){
		$sql = "insert into osa_syslog_cfg values(NULL ,'$name')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 插入日志信息
	 */
	public function insertLogInfo($loginfo){
		foreach ($loginfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_syslog ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 根据id获取日志信息
	 */
	public function getLoginfoByid($id){
		$sql = "select * from osa_syslog where id = $id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 更新日志信息
	 */
	public function updateLoginfo($id,$loginfo){
		foreach ($loginfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_syslog set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 分享日志
	 * @param unknown_type $id
	 */
	public function setLogShare($id){
		$sql ="update osa_syslog set oIsShare=1 where id=".$id;
		$this->db->exec($sql);
	}
	
	/********************************************************知识库处理函数*******************************************/
	/**
	 * 
	 */
	public function selectRepository($perpage ,$offset ,$search ,$starttime ,$endtime ,$bid=0){
		if($bid == 0){//说明是个人知识库
			$sql = "select A.* , oTypeName from osa_repository as A left join osa_repository_type as B on A.oTypeid = B.id where oIsPrivate = 1 ";
		}else if($bid == 1){//说明是内部知识库
			$sql = "select A.* , oTypeName from osa_repository as A left join osa_repository_type as B on A.oTypeid = B.id where oIsProtect = 1 ";
		}
		$sql .=" and oCreateTime>'$starttime' and oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oRepositoryTitle like '%$search%' or oRepositoryLabel like '%$search%') ";
		}
		$sql .= " order by oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 
	 */
	public function getNumfromRepository($search ,$starttime ,$endtime ,$bid=0 ){
		if($bid == 0){//说明是个人知识库
			$sql = "select count(id) as num from osa_repository where oIsPrivate = 1";
		}else if($bid == 1){//说明是内部知识库
			$sql = "select count(id) as num from osa_repository where oIsProtect = 1";
		}
		$sql .=" and oCreateTime>'$starttime' and oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oRepositoryTitle like '%$search%' or oRepositoryLabel like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 
	 */
	public function selectKnowType(){
		$sql = "select * from osa_repository_type ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 
	 */
	public function insertKnowtype($name){
		$sql = "insert into osa_repository_type values(NULL ,'$name')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 插入知识库
	 */
	public function insertKnowInfo($knowinfo){
		foreach ($knowinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_repository ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 根据id 获取知识记录信息
	 */
	public function getKnowinfoByid($id){
		$sql = "select * from osa_repository where id=$id ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 *  编辑修改知识记录
	 */
	public function updateKnowinfo($id,$knowinfo){
		foreach ($knowinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_repository set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 
	 */
	public function delKnowinfo($id){
		$sql = "delete from osa_repository where id=$id";
		$this->db->exec($sql);
	}
	
	/**
	 * 分享知识
	 */
	public function setKnowShare($id){
		$sql ="update osa_repository set oIsShare=1 ,oIsProtect=1 where id=".$id;
		$this->db->exec($sql);
	}
	
	/*************************************************配置文件处理函数************************************************/
	/**
	 * 
	 */
	public function selectConfigfile($perpage ,$offset ,$search ,$starttime ,$endtime ,$bid){
		$sql = "select A.* ,oTypeName from osa_configfile as A left join osa_filetype as B on A.oTypeid = B.id where oIsBelong = $bid ";
		$sql .=" and oCreateTime>'$starttime' and oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oFileName like '%$search%' or oFileLabel like '%$search%') ";
		}
		$sql .= " order by oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	/**
	 * 
	 */
	public function getNumfromConfigfile($search ,$starttime ,$endtime ,$bid ){
		$sql = "select count(id) as num from osa_configfile where oIsBelong = $bid";
		$sql .=" and oCreateTime>'$starttime' and oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (oFileName like '%$search%' or oFileLabel like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 
	 */
	public function insertFiletype($name ){
		$sql = "insert into osa_filetype values(NULL ,'$name')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 
	 */
	public function delConfigfile($id){
		$sql = "delete from osa_configfile where id=$id";
		$this->db->exec($sql);
	}
	
	/**
	 * 
	 */
	public function selectFileType(){
		$sql = "select * from osa_filetype ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 
	 */
	public function getConfigfileByid($id){
		$sql = "select * from osa_configfile where id=$id ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 
	 */
	public function insertConfigfile($configinfo){
		foreach ($configinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_configfile ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 更新配置文件
	 */
	public function updateConfigfile($id ,$configinfo){
		foreach ($configinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_configfile set $query where id=$id";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 根据文件名获取配置文件信息
	 */
	public function getInfoByfilename($filename){
		$sql = "select * from osa_configfile where oFileName = '$filename'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 分享文件
	 */
	public function setFileShare($id){
		$sql = "update osa_configfile set oIsShare=1 where id=".$id;
		$this->db->exec($sql);
	}
	
	/****************************************************数据库备份****************************************/
	public function selectServerType(){
		$sql = "select * from osa_devtype ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function selectServerGroup(){
		$sql = "select * from osa_devgroup ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * search ip
	 */
	public function searchIpinfo($typeid='' , $groupid='' ,$keyword=''){
		$sql = "select oIp from osa_devinfo where 1";
		if(!empty($typeid)){
			$sql .= " and oTypeid = $typeid ";
		}
		if(!empty($groupid)){
			$sql .= " and oGroupid = $groupid ";
		}
		if(!empty($keyword)){
			$sql .= " and (oDevName like '%$keyword%' or oPlace like '%$keyword%' or oIp like '%$keyword%')";
		}
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * search scriptfile
	 */
	public function searchScriptinfo($keyword){
		$sql = "select * from osa_script where oScriptName like '%$keyword%' or oScriptLabel like '%$keyword%' ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * search configfile
	 */
	public function searchConfiginfo($typeid ,$keyword){
		$sql = "select * from osa_configfile where 1";
		if(!empty($typeid)){
			$sql .= " and oTypeid = $typeid ";
		}
		if(!empty($keyword)){
			$sql .= " and (oFileName like '%$keyword%' or oFileLabel like '%$keyword%')";
		}
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function insertTaskPlan($planinfo){
		foreach ($planinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_taskplan ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	public function insertDataBackup($backupinfo){
		foreach ($backupinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= '"'.$value.'"'.",";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_databackup ($keys) values($query)";
		$this->db->exec($sql);
		$this->addlogmsg('BATCH_DATABASE_BACKUP',$backupinfo['oCombinCmd'],$sql);
		return $this->db->lastInsertId();
	}
	
	public function selectDataBackup($perpage ,$offset ,$search ,$starttime ,$endtime ){
		$sql = "select A.* ,B.oBackupName ,B.oBackupIp from osa_taskplan as A inner join osa_databackup as B on A.id = B.oTaskplanid where 1 ";
		$sql .=" and A.oCreateTime>'$starttime' and A.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oBackupName like '%$search%') ";
		}
		$sql .=" order by A.oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);	
	}
	
	public function getNumfromDataBackup($search ,$starttime ,$endtime ){
		$sql = "select count(A.id) as num from osa_taskplan as A inner join osa_databackup as B on A.id = B.oTaskplanid where 1 ";
		$sql .=" and A.oCreateTime>'$starttime' and A.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oBackupName like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];	
	}
	
	/********************************************添加configUpdate 任务***************************************/
	
	public function selectConfigUpdate($perpage ,$offset ,$search ,$starttime ,$endtime ,$tasktype ){
		if($tasktype == 0){
			$sql = "select A.* ,B.oIpArr from osa_taskplan as A inner join osa_configupdate as B on A.id = B.oTaskplanid where 1 ";
		}else if($tasktype == 1){
			$sql = "select A.* ,B.oIpArr ,B.oCreateTime from osa_tasknow as A inner join osa_configupdate as B on A.id = B.oTasknowid where 1 ";
		}
		$sql .=" and B.oCreateTime>'$starttime' and B.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oIpArr like '%$search%') ";
		}
		$sql .= " order by B.oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function getNumfromConfigUpdate($search ,$starttime ,$endtime ,$tasktype){
		if($tasktype == 0){
			$sql = $sql = "select count(A.id) as num from osa_taskplan as A inner join osa_configupdate as B on A.id = B.oTaskplanid where 1 ";
		}else if($tasktype == 1){
			$sql = "select count(A.id) as num from osa_tasknow as A inner join osa_configupdate as B on A.id = B.oTasknowid where 1 ";
		}
		$sql .=" and B.oCreateTime>'$starttime' and B.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oIpArr like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 立即执行configupdate
	 */
	public function configupdate_now(){
		$cmdtype = 'BATCH_CONFIG_UPDATE';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$filesize = $this->getfilesize($_POST['sourcefile']);
		if($filesize == false){
			$errormsg = '文件大小获取失败，文件可能不存在';
			$this->addlogmsg($cmdtype,$combincmd='',$resql='' ,$errormsg);
			return false ;
		}
		$iparr = $_POST['iparr'];$sourcefile = $_POST['sourcefile'];
		$targetdir=$_POST['targetdir'];$advance = $_POST['advance'];
		$scriptfile = $_POST['scriptfile'];$md5 = md5(file_get_contents($sourcefile));
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'config_update_sourcefile':'$sourcefile','config_update_targetpath':'$targetdir','config_update_advance':'$advance','config_update_scriptfile':'$scriptfile'},'id':'$taskid','md5':'$md5','filesize':'$filesize'}";
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oIpArr' => $_POST['iparr'],
				'oSourceFile' => $_POST['sourcefile'],
				'oTargetPath' => $_POST['targetdir'],
				'oAdvance' => $_POST['advance'],
				'oScriptFile' => $_POST['scriptfile'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql=$this->insertConfigUpdate($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	/**
	 * 计划任务 configupdate
	 */
	public function configupdate_plan(){
		$cmdtype = 'BATCH_CONFIG_UPDATE';
		$plantime = $_POST['plantime'];
		$filesize = $this->getfilesize($_POST['sourcefile']);
		if($filesize == false){
			$errormsg = '文件大小获取失败，文件可能不存在';
			$this->addlogmsg($cmdtype,$combincmd='',$resql='' ,$errormsg);
			return false ;
		}
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$sourcefile = $_POST['sourcefile'];
		$targetdir=$_POST['targetdir'];$advance = $_POST['advance'];
		$scriptfile = $_POST['scriptfile'];$md5 = md5(file_get_contents($sourcefile));
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'config_update_sourcefile':'$sourcefile','config_update_targetpath':'$targetdir','config_update_advance':'$advance','config_update_scriptfile':'$scriptfile'},'id':'$taskid','md5':'$md5','filesize':'$filesize'}";
		
		$updateinfo = array(
			'oIpArr' => $_POST['iparr'],
			'oSourceFile' => $_POST['sourcefile'],
			'oTargetPath' => $_POST['targetdir'],
			'oAdvance' => $_POST['advance'],
			'oScriptFile' => $_POST['scriptfile'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertConfigUpdate($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	public function insertTasknow($cmdtype , $status){
		$sql = "insert into osa_tasknow values(NULl ,'$cmdtype' ,'$status')";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	public function getfilesize($file){
		//使用绝对路径
		//$basepath = rtrim(OSA_PHPROOT_PATH,'/')."/configlib/" ;
		$filepath = $file;
		if(file_exists($filepath)){
			return filesize($filepath);
		}else{
			return false;
		}
	}
	
	public function insertConfigUpdate($updateinfo){
		foreach ($updateinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= '"'.$value.'"'.",";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_configupdate ($keys) values($query)";
		$this->db->exec($sql);
		//return $this->db->lastInsertId();
		return $sql ;
	}
	
	/***************************************config backup************************************************/
	
	public function selectConfigBackup($perpage ,$offset ,$search ,$starttime ,$endtime ,$tasktype ){
		if($tasktype == 0){
			$sql = $sql = "select A.* ,B.oIpArr from osa_taskplan as A inner join osa_configbackup as B on A.id = B.oTaskplanid where 1 ";
		}else if($tasktype == 1){
			$sql = "select A.* ,B.oIpArr ,B.oCreateTime from osa_tasknow as A inner join osa_configbackup as B on A.id = B.oTasknowid where 1 ";
		}
		$sql .=" and B.oCreateTime>'$starttime' and B.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oIpArr like '%$search%') ";
		}
		$sql .= " order by B.oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function getNumfromConfigBackup($search ,$starttime ,$endtime ,$tasktype){
		if($tasktype == 0){
			$sql = $sql = "select count(A.id) as num from osa_taskplan as A inner join osa_configbackup as B on A.id = B.oTaskplanid where 1 ";
		}else if($tasktype == 1){
			$sql = "select count(A.id) as num from osa_tasknow as A inner join osa_configbackup as B on A.id = B.oTasknowid where 1 ";
		}
		$sql .=" and B.oCreateTime>'$starttime' and B.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oIpArr like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	/**
	 * 立即执行configbackup
	 */
	public function configbackup_now(){
		$cmdtype = 'BATCH_CONFIG_BACKUP';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$iparr = $_POST['iparr'];$sourcefile = $_POST['sourcefile'];
		$backupdir=$_POST['backupdir'];$backuprule = $_POST['backuprule'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'config_backup_sourcefile':'$sourcefile','config_backup_dir':'$backupdir','config_backup_rule':'$backuprule',},'id':'$taskid',}";
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$backupinfo = array(
				'oIpArr' => $_POST['iparr'],
				'oSourceFile' => $_POST['sourcefile'],
				'oBackupDir' => $_POST['backupdir'],
				'oBackupRule' => $_POST['backuprule'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertConfigBackup($backupinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	/**
	 * 计划任务 configbackup
	 */
	public function configbackup_plan(){
		$cmdtype = 'BATCH_CONFIG_BACKUP';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$sourcefile = $_POST['sourcefile'];
		$backupdir=$_POST['backupdir'];$backuprule = $_POST['backuprule'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'config_backup_sourcefile':'$sourcefile','config_backup_dir':'$backupdir','config_backup_rule':'$backuprule',},'id':'$taskid',}";

		$backupinfo = array(
			'oIpArr' => $_POST['iparr'],
			'oSourceFile' => $_POST['sourcefile'],
			'oBackupDir' => $_POST['backupdir'],
			'oBackupRule' => $_POST['backuprule'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertConfigBackup($backupinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/**
	 * 添加配置文件备份记录
	 */
	public function insertConfigBackup($backupinfo){
		foreach ($backupinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= '"'.$value.'"'.",";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_configbackup ($keys) values($query)";
		$this->db->exec($sql);
		//return $this->db->lastInsertId();
		return $sql;
	}
	
	
	/***************************************文件分发**********************************************************/
	
	/**
	 * 批量操作表记录插入
	 */
	public function insertOperations($info){
		foreach ($info as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= '"'.$value.'"'.",";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_operations ($keys) values($query)";
		$this->db->exec($sql);
		//return $this->db->lastInsertId();
		return $sql;
	}
	/**
	 * 文件分发--立即执行
	 */
	public function distribution_now(){
		$cmdtype = 'BATCH_DOCUMENT_DISTRIBUTION';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$filesize = $this->getfilesize($_POST['sourcefile']);
		if($filesize == false){
			$errormsg = '文件大小获取失败，文件可能不存在';
			$this->addlogmsg($cmdtype,$combincmd='',$resql='' ,$errormsg);
			return false ;
		}
		$iparr = $_POST['iparr']; $sourcefile = $_POST['sourcefile'];
		$advance = $_POST['advance']; $targetpath = $_POST['targetdir'];
		$scriptfile = $_POST['scriptfile'];$md5 = md5(file_get_contents($sourcefile));
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'sourcefile':'$sourcefile','targetpath':'$targetpath','advance':'$advance','distribution_script':'$scriptfile'},'id':'$taskid','md5':'$md5','filesize':'$filesize'}";
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 1,
				'oTypename' => '批量文件分发',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function distribution_plan(){
		$cmdtype = 'BATCH_DOCUMENT_DISTRIBUTION';
		$plantime = $_POST['plantime'];
		$filesize = $this->getfilesize($_POST['sourcefile']);
		if($filesize == false){
			$errormsg = '文件大小获取失败，文件可能不存在';
			$this->addlogmsg($cmdtype,$combincmd='',$resql='' ,$errormsg);
			return false ;
		}
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr']; $sourcefile = $_POST['sourcefile'];
		$advance = $_POST['advance']; $targetpath = $_POST['targetdir'];
		$scriptfile = $_POST['scriptfile'];$md5 = md5(file_get_contents($sourcefile));
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'sourcefile':'$sourcefile','targetpath':'$targetpath','advance':'$advance','distribution_script':'$scriptfile'},'id':'$taskid','md5':'$md5','filesize':'$filesize'}";
		//$combincmd = json_encode($combincmd);
		$updateinfo = array(
			'oTypeid'=> 1,
			'oTypename' => '批量文件分发',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/*********************************文件清理***************************************************************/
	 
	public function cleaner_now(){
		$cmdtype = 'BATCH_FILE_CLEANER';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$iparr = $_POST['iparr'];$sourcefile = $_POST['sourcefile'];
		$advance=$_POST['advance'];$targetdir = $_POST['targetdir'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'cleaner_sourcefile':'$sourcefile','cleaner_targetpath':'$targetdir','cleaner_advance':'$advance'},'id':'$taskid'}";
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 2,
				'oTypename' => '批量文件清理',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function cleaner_plan(){
		$cmdtype = 'BATCH_FILE_CLEANER';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$sourcefile = $_POST['sourcefile'];
		$advance=$_POST['advance'];$targetdir = $_POST['targetdir'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'cleaner_sourcefile':'$sourcefile','cleaner_targetpath':'$targetdir','cleaner_advance':'$advance'},'id':'$taskid'}";
		
		$updateinfo = array(
			'oTypeid'=> 2,
			'oTypename' => '批量文件清理',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/*****************************************批量文件服务器处理*********************************************/
	
	public function serverdeal_now(){
		$cmdtype = 'BATCH_SERVICE_RESTART';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		if(isset($_POST['scriptfile'])){
			$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
			$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'service_scriptfile':'$scriptfile'},'id':$taskid}";
		}else{
			$iparr = $_POST['iparr'];$name = $_POST['name'];$type = $_POST['type'];
			$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'service_name':'$name','service_type':'$type'},'id':'$taskid'}";
		}
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 3,
				'oTypename' => '批量文件服务器处理',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function serverdeal_plan(){
		$cmdtype = 'BATCH_SERVICE_RESTART';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		if(isset($_POST['scriptfile'])){
			$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
			$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'service_scriptfile':'$scriptfile'},'id':$taskid}";
		}else{
			$iparr = $_POST['iparr'];$name = $_POST['name'];$type = $_POST['type'];
			$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'service_name':'$name','service_type':'$type'},'id':'$taskid'}";
		}
		$updateinfo = array(
			'oTypeid'=> 3,
			'oTypename' => '批量文件服务器处理',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/*************************************批量指令执行******************************************************/
	
	public function command_now(){
		$cmdtype = 'BATCH_COMMAND';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'command_scriptfile':'$scriptfile'},'id':'$taskid'}";
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 4,
				'oTypename' => '批量指令执行',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function command_plan(){
		$cmdtype = 'BATCH_COMMAND';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'command_scriptfile':'$scriptfile'},'id':'$taskid'}";

		$updateinfo = array(
			'oTypeid'=> 4,
			'oTypename' => '批量指令执行',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/*********************************************批量安装程序****************************************/
	
	public function installation_now(){
		$cmdtype = 'BATCH_INSTALLATION';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'install_scriptfile':'$scriptfile'},'id':'$taskid'}";
		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 5,
				'oTypename' => '批量安装程序',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function installation_plan(){
		$cmdtype = 'BATCH_INSTALLATION';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'install_scriptfile':'$scriptfile'},'id':'$taskid'}";

		$updateinfo = array(
			'oTypeid'=> 5,
			'oTypename' => '批量安装程序',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/****************************批量磁盘空间***********************************************************/
	
	public function diskspace_now(){
		$cmdtype = 'BATCH_DISKSPACE_CHECK';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$iparr = $_POST['iparr'];$threshold = $_POST['threshold'];$unit = $_POST['unit'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'diskspace_threshold':'$threshold','unit':'$unit'},'id':'$taskid'}";

		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 6,
				'oTypename' => '批量磁盘空间',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function diskspace_plan(){
		$cmdtype = 'BATCH_DISKSPACE_CHECK';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$threshold = $_POST['threshold'];$unit = $_POST['unit'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'diskspace_threshold':'$threshold','unit':'$unit'},'id':'$taskid'}";

		$updateinfo = array(
			'oTypeid'=> 6,
			'oTypename' => '批量磁盘空间',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/*******************************批量负载状态*******************************************************/
	public function loadstate_now(){
		$cmdtype = 'BATCH_LOADSTATE_CHECK';
		$status = '运行中';
		$taskid = $this->insertTasknow($cmdtype ,$status);
		$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'topstate_scriptfile':'$scriptfile'},'id':'$taskid'}";

		//传送指令   -----暂时不写
		$cmdres = osa_sendcmd_plannow($combincmd);
		if($cmdres == 'BATCH_CMD_OK!1'){
			$updateinfo = array(
				'oTypeid'=> 7,
				'oTypename' => '批量负载状态',
				'oIpArr' => $_POST['iparr'],
				'oCreateTime' => date("Y-m-d H:i:s" ,time()),
				'oCombinCmd' => $combincmd,
				'oTasknowid' => $taskid
			);
			$resql = $this->insertOperations($updateinfo);
			$this->addlogmsg($cmdtype,$combincmd,$resql);
			return true ;
		}else{
			$errormsg = "立即执行任务指令发送失败，返回信息:".$cmdres;
			$this->addlogmsg($cmdtype,$combincmd,$resql='' ,$errormsg);
			return false ;
		}
	}
	
	public function loadstate_plan(){
		$cmdtype = 'BATCH_LOADSTATE_CHECK';
		$plantime = $_POST['plantime'];
		$planinfo = array(
				'oRunCycle'=> $plantime[0],
				'oRunDate'=> $plantime[1],
				'oRuntime'=> $plantime[2],
				'oCmdType'=> $cmdtype,
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
		$taskid = $this->insertTaskPlan($planinfo);
		$iparr = $_POST['iparr'];$scriptfile = $_POST['scriptfile'];
		$combincmd = "{'command':'$cmdtype','iparr':'$iparr','config_items':{'topstate_scriptfile':'$scriptfile'},'id':'$taskid'}";

		$updateinfo = array(
			'oTypeid'=> 7,
			'oTypename' => '批量负载状态',
			'oIpArr' => $_POST['iparr'],
			'oCreateTime' => date("Y-m-d H:i:s" ,time()),
			'oCombinCmd' => $combincmd,
			'oTaskplanid' => $taskid
		);
		$resql = $this->insertOperations($updateinfo);
		$this->addlogmsg($cmdtype,$combincmd,$resql);
		return true ;
	}
	
	/**************************************批量处理管理************************************************/
	
	public function selectOperations($perpage ,$offset ,$search ,$starttime ,$endtime ,$tasktype =0){
		if($tasktype == 0){
			$sql = $sql = "select A.* ,B.oTypeName ,B.oIpArr from osa_taskplan as A inner join osa_operations as B on A.id = B.oTaskplanid where 1 ";
		}else if($tasktype == 1){
			$sql = "select A.* ,B.oTypeName ,B.oIpArr ,B.oCreateTime from osa_tasknow as A inner join osa_operations as B on A.id = B.oTasknowid where 1 ";
		}
		$sql .=" and B.oCreateTime>'$starttime' and B.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oIpArr like '%$search%') ";
		}
		$sql .= " order by B.oCreateTime desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function getNumfromOperations($search ,$starttime ,$endtime ,$tasktype=0){
		if($tasktype == 0){
			$sql = "select count(A.id) as num from osa_taskplan as A inner join osa_operations as B on A.id = B.oTaskplanid where 1 ";
		}else if($tasktype == 1){
			$sql = "select count(A.id) as num from osa_tasknow as A inner join osa_operations as B on A.id = B.oTasknowid where 1 ";
		}
		$sql .=" and B.oCreateTime>'$starttime' and B.oCreateTime<'$endtime' ";
		if(!empty($search)){
			$sql .= " and  (B.oIpArr like '%$search%') ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/***********************************终止计划任务***************************************************************/
	
	public function getTaskplanByid($id){
		$sql = "select * from osa_taskplan where id = $id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function delTaskplanByid($id){
		$sql = "delete from osa_taskplan where id = $id";
		$this->db->exec($sql);
	}
	
	public function insertComplantask($info){
		foreach ($info as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_complantask ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/***********************************终止立即执行任务***************************************************************/
	public function getTasknowByid($id){
		$sql = "select * from osa_tasknow where id = $id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function delTasknowByid($id){
		$sql = "delete from osa_tasknow where id = $id";
		$this->db->exec($sql);
	}
	
	public function insertComnowtask($info){
		foreach ($info as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_comnowtask ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/***************************************写入日志***************************************************************/
	
	/**
	 * 写入 日志信息
	 */
	public function addlogmsg($type,$json,$sql='' ,$errormsg=''){
		$filepath = OSA_PHPLOG_PATH.'/bitch/bitch.txt';
		$msg ="执行时间:".date('Y-m-d H:i:s',time());
		$msg.="批量操作类型：".$type."\n";
		if(!empty($json)){
			$msg.="json数据:".$json."\n";;
		}
		if(!empty($sql)){
			$msg.="执行sql操作:".$sql."\n";
		}
		if(!empty($errormsg)){
			$msg.="错误信息:".$errormsg."\n";
		}
		$handle = fopen($filepath ,'a+');
		fwrite($handle, $msg);
		fclose($handle);	
	}
	
	
	/****************************************文件保存与读取********************************************************/		
	
	/**
	 * 获取文件信息
	 */
	public function getFileContent($filepath){
		return @file_get_contents($filepath);
	}
	
	public function getBatchResultByid($id ,$tasktype =0){
		if($tasktype == 0){
			$sql = "select * from osa_taskplan_result where oTaskPlanid=$id ";
		}else if($tasktype == 1){
			$sql = "select * from osa_tasknow_result where oTaskNowid=$id";
		}
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/***
	 * 批量结果查询
	 */
	public function getBatchResult($perpage ,$offset ,$starttime ,$endtime ,$tasktype =0){
		if($tasktype == 0){
			$sql = $sql = "select * from osa_taskplan_result where 1 ";
		}else if($tasktype == 1){
			$sql = "select * from osa_tasknow_result where 1";
		}
		$sql .=" and oRunTime>'$starttime' and oRunTime<'$endtime' ";
		$sql .= " order by id desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 批量结果数量统计
	 */
	public function getNumfromResult($starttime ,$endtime ,$tasktype =0){
		if($tasktype == 0){
			$sql = $sql = "select count(id) as num from osa_taskplan_result where 1 ";
		}else if($tasktype == 1){
			$sql = "select count(id) as num from osa_tasknow_result where 1";
		}
		$sql .=" and oRunTime>'$starttime' and oRunTime<'$endtime' ";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	 * 删除批量结果
	 */
	public function delBatchResult($id ,$tasktype){
		if($tasktype == 0){
			$sql = $sql = "delete from osa_taskplan_result where id=".$id;
		}else if($tasktype == 1){
			$sql = "delete from osa_tasknow_result where id=".$id;
		}
		$this->db->exec($sql);
	}
	
	/**
	 * 详情
	 */
	public function getBatchInfoByid($id , $type ,$tasktype){
		if($tasktype == 'plan'){
			if($type == 'BATCH_DATABASE_BACKUP'){
				$sql = 'select * from osa_databackup where oTaskplanid ='.$id;
			}else if($type == 'BATCH_CONFIG_BACKUP'){
				$sql = 'select * from osa_configbackup where oTaskplanid ='.$id;
			}else if($type == 'BATCH_CONFIG_UPDATE'){
				$sql = 'select * from osa_configupdate where oTaskplanid ='.$id;
			}else{
				$sql = 'select * from osa_operations where oTaskplanid ='.$id;
			}
		}else{
			if($type == 'BATCH_DATABASE_BACKUP'){
				$sql = 'select * from osa_databackup where oTasknowid ='.$id;
			}else if($type == 'BATCH_CONFIG_BACKUP'){
				$sql = 'select * from osa_configbackup where oTasknowid ='.$id;
			}else if($type == 'BATCH_CONFIG_UPDATE'){
				$sql = 'select * from osa_configupdate where oTasknowid ='.$id;
			}else{
				$sql = 'select * from osa_operations where oTasknowid ='.$id;
			}
		}
		return $this->db->queryFetchAllAssoc($sql);
	}
}