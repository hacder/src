<?php
class mmonitor extends osa_model{


	public function __construct(){
	
		parent::__construct();
	}
	
	
	
	/******************************start---- monitor item insert ----start********************************/
	
	/**
	 * osa_monitor insert
	 */
	public function monitor_insert($monitors){
	
		foreach ($monitors as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= '"'.$value.'"'.",";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_monitors ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
		
	}
	
	/**
	 * osa_notice_method insert or update
	 */
	public function notiset_insert($configinfo){
		
		$sql = "select * from osa_notice_method where id=1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){
			foreach ($configinfo as $key => $value)
			{
				$query .= "$key = ".'"'.$value.'"'.",";
			}
			$query = trim($query ,',');
			$sql = "update osa_notice_method set $query where id=1";
			$this->db->exec($sql);
		}else{
			foreach ($configinfo as $key => $value)
			{
				$keys 	.= "$key,";
				$query 	.= '"'.$value.'"'.",";
			}
			$keys = trim($keys,',');
			$query = trim($query ,',');
			$sql = "insert into osa_notice_method (id ,$keys) values(1,$query)";
			$this->db->exec($sql);
		}
	}
	
	
	/**
	 * osa_email_config insert
	 */
	public function smtp_insert($smtpinfo){
		
		$sql = "select * from osa_email_config where id=1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){//update
			foreach ($smtpinfo as $key => $value)
			{
				$query .= "$key = ".'"'.$value.'"'.",";
			}
			$query = trim($query ,',');
			$sql = "update osa_email_config set $query where id=1";
			$this->db->exec($sql);
		}else{
			foreach ($smtpinfo as $key => $value)
			{
				$keys 	.= "$key,";
				$query 	.= '"'.$value.'"'.",";
			}
			$keys = trim($keys,',');
			$query = trim($query ,',');
			$sql = "insert into osa_email_config (id ,$keys) values(1,$query)";
			$this->db->exec($sql);
		}
		
	}
	
	
	/**
	 * osa_sms_config insert
	 */
	public function sms_insert($notisms){
		
		$sql = "select * from osa_sms_config where id =1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){
			$sql = "update osa_sms_config set oNoticeUsers = '$notisms' where id =1";
			$this->db->exec($sql);
		}else{
			$sql = "insert into osa_sms_config values(1,'$notisms')";
			$this->db->exec($sql);
		}
	}
	
	
	/**
	 * osa_msn_config insert
	 */
	public function msn_insert($notimsn){
		
		$sql = "select * from osa_msn_config where id =1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){
			$sql = "update osa_msn_config set oNoticeMsn = '$notimsn' where id =1";
			$this->db->exec($sql);
		}else{
			$sql = "insert into osa_msn_config values(1,'$notimsn')";
			$this->db->exec($sql);
		}
		
	}
	
	
	/**
	 * osa_gtalk_config insert
	 */
	public function gtalk_insert($notigtalk){
			
		$sql = "select * from osa_gtalk_config where id =1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){
			$sql = "update osa_gtalk_config set oNoticeGtalk = '$notigtalk' where id =1";
			$this->db->exec($sql);
		}else{
			$sql = "insert into osa_gtalk_config values(1,'$notigtalk')";
			$this->db->exec($sql);
		}
	}
	
	
	/******************************end---- monitor item insert ----end********************************/
	
	
	/******************************start---- monitor item update ----start********************************/
	
	/**
	 * osa_monitor update
	 */
	public function monitor_update($id,$moniotrs){
	
		foreach ($moniotrs as $key => $val)
		{
			$query .= "$key = ".'"'.$val.'"'.",";
		}
		$query = trim($query ,',');
		$sql = "update osa_monitors set $query where id=$id";
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_monitors pause
	 */
	public function monitor_pause($id){
	
		$time = date("Y-m-d H:i:s",time());
		$sql = "update osa_monitors set oStopTime='$time',oStartTime='',oIsStop=1 where id=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_monitors open
	 */
	public function monitor_open($id){
	
		$time = date("Y-m-d H:i:s",time());
		$sql = "update osa_monitors set oStopTime='',oStartTime='$time',oIsStop=0 where id=".$id;
		$this->db->exec($sql);
	}
	
	/******************************end---- monitor item update ----end********************************/
	
	
	/******************************start---- monitor item delete ----start********************************/
	
	/**
	 * osa_monitor delete
	 */
	public function monitor_delete($id){
	
		$sql = "delete from osa_monitors where id=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_monitor_alarm delete
	 */
	public function alarm_delete($id){
		
		$sql = "delete from osa_monitor_alarm where oItemid=".$id;
		$this->db->exec($sql);
	}
	
	
	/**
	 * osa_monitor_record delete
	 */
	public function record_delete($id){
	
		$sql = "delete from osa_monitor_record where oItemid=".$id;
		$this->db->exec($sql);
	}
	
	/******************************end---- monitor item delete ----end********************************/
	
	
	/******************************start---- monitor item select ----start********************************/
	
	/**
	 * 根据itemlist 计算每个项目的项目可用率
	 */
	public function monitor_item_rate($iteminfo){
	
		$itemrate = array();
		if(empty($iteminfo)){
			return $itemrate ;
		}
		foreach ($iteminfo as $item){	
			$sql1 = "select count(*) as num from osa_monitor_record where oItemid =".$item['id'];
			$sql2 = "select count(*) as num from osa_monitor_record where oItemid =".$item['id']." and oStatus='异常'";
			$res1 = $this->db->queryFetchAllAssoc($sql1);
			if(empty($res1)||empty($res1[0]['num'])){
				$itemrate[$item['id']] = '100%';
			}else{
				$res2 = $this->db->queryFetchAllAssoc($sql2);
				$rate = (1-$res2[0]['num']/$res1[0]['num'])*100 ;
				$rate = substr($rate,0,4)."%";
				$itemrate[$item['id']] = $rate;
			}
		}
		return $itemrate ;
	}
	
	
	
	/**
	 * osa_monitors selete
	 */
	public function monitor_select_page($type='http', $search='',$status='',$perpage ,$offset){
		
		$sql = "select * from osa_monitors where 1";
		if(!empty($type)){
			$sql .=" and oItemType = '$type'";
		}
		if(!empty($search)){
			$sql .= " and oItemName like '%$search%'";
		}
		if(!empty($status)&&$status!="所有"){
			if($status == "正常"){
				$sql .= " and oStatus = 1 ";
			}else if($status == "异常"){
				$sql .= " and oStatus = 0 ";
			}
		}
		$sql .=" order by id desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function monitor_select_nums($type ,$search='',$status=''){
	
		$sql = "select count(id) as num from osa_monitors where 1";
		if(!empty($type)){
			$sql .=" and oItemType = '$type'";
		}
		if(!empty($search)){
			$sql .=" and oItemName like '%$search%'";
		}
		if(!empty($status)&&$status!="所有"){
			if($status == "正常"){
				$sql .= " and oStatus = 1 ";
			}else if($status == "异常"){
				$sql .= " and oStatus = 0 ";
			}
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	public function monitor_select_customname($itemid){
	/**
	 *
	 *
	 */
		if(empty($itemid) || $itemid == ''){
			return 'loadstat';
		}
		
		$sql = "select oItemConfig from osa_monitors where id = $itemid";
		$rs = $this->db->select($sql);
		$jsonstr = $rs[0]['oItemConfig'];
        $jsonstr = strtr($jsonstr,"'","\"");
        $jsondata = json_decode($jsonstr,true);		
		return $jsondata['name'];
	
	}
	/**
	 * osa_monitor_alarm selete
	 */
	public function alarm_select($itemid = ''){
		
		$sql = "select * from osa_monitor_alarm where 1";
		if(!empty($itemid)){
			$sql .= " and oItemid=".$itemid;
		}
		$sql .= " order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_monitor_record select
	 */
	public function record_select($itemid){
		
		$sql = "select * from osa_monitor_record where 1";
		if(!empty($itemid)){
			$sql .= " and oItemid=".$itemid;
		}
		$sql .= " order by id desc";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/***
	 * osa_monitors select by id
	 */
	public function monitor_select_itemid($itemid){
	
		$sql = "select * from osa_monitors where id =".$itemid ;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * osa_monitors select by oItemType
	 */
	public function monitor_nums_itemtype(){
	
		$sql = "select oItemType ,count(*) as num from osa_monitors group by oItemType";
		$result = $this->db->queryFetchAllAssoc($sql);
		$arr = array();
		if($result){
			foreach ($result as $key){
				$arr[$key['oItemType']] = $key['num']; 
			}
		}
		return $arr ;
	} 
	
	/******************************end---- monitor item select ----end********************************/
}