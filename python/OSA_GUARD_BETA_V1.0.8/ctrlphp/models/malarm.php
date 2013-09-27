<?php
class malarm extends osa_model{

	private $helper = '';
	
	public function __construct(){
	
		parent::__construct();
		
		$this->helper = $this->loadmodel('helper');
		
	}
	
	
	/**
	 * 已通知报警信息
	 * select osa_monitor_alarm info
	 */
	public function itemalarm_select_noticed_page($stime='',$etime='',$perpage,$offset){
	
		if(empty($stime)||empty($etime)){
			
			$sql = "select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =1 ";
			$sql .= " order by A.id desc";
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'malarm');
			$sql = "(select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from ".$table['oTableName']." as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select * from (".$sql.") as t order by oAlarmTime desc";
		}
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * noticed nums of alarm
	 */
	public function itemalarm_select_noticed_nums($stime,$etime){
	
		if(empty($stime)||empty($etime)){
			
			$sql = "select count(A.id) as num from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =1 ";
			
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'malarm');
			$sql = "(select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from ".$table['oTableName']." as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select count(*) as num from (".$sql.") as t ";
			
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	
	
	/**
	 * 未通知报警信息
	 * 
	 */
	public function itemalarm_select_not_notice_page($stime ,$etime,$perpage,$offset){
	
		if(empty($stime)||empty($etime)){
			
			$sql = "select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =0 ";
			$sql .= " order by A.id desc";
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'malarm');
			$sql = "(select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from ".$table['oTableName']." as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select * from (".$sql.") as t order by oAlarmTime desc";
		}
		$sql .= " limit $offset , $perpage";
		//return $sql ;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * noticed nums of alarm
	 */
	public function itemalarm_select_not_notice_nums($stime ,$etime){
	
	
		if(empty($stime)||empty($etime)){
			
			$sql = "select count(A.id) as num from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =0 ";
			
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'malarm');
			$sql = "(select B.id,B.oItemType,A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from osa_monitor_alarm as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oItemName,B.oItemObject from ".$table['oTableName']." as A right join osa_monitors as B on A.oItemid = B.id where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select count(*) as num from (".$sql.") as t ";
			
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	
	
	/**
	 * 报警信息处理
	 * 分为 今天 ，昨天 ，更早 三个级别划分
	 */
	public function alarm_data_deal($alarmdata){
	
		if(empty($alarmdata)){
			return $alarmdata;
		}
		$today = $yesterday = $earlier = array();
		foreach ($alarmdata as $alarm){
			$time = $alarm['oAlarmTime'];
			$date = $this->alarm_time_judgment($time);
			array_push($$date,$alarm);
		}
		$result = array('today'=>$today,'yesterday'=>$yesterday,'earlier'=>$earlier);
		return $result ;
	}
	
	/**
	 * 判断时间是今天还是昨天或者更早时间
	 */
	public function alarm_time_judgment($time){
	
		$today = date("Y-m-d H:i:s" ,strtotime('today'));
		$yesterday = date("Y-m-d H:i:s" ,strtotime('yesterday'));
		if($time >= $today){
			return 'today';
		}else if($time >= $yesterday && $time <$today){
			return 'yesterday';
		}else if($time < $yesterday){
			return 'earlier';
		}
	}
	
	
	/**
	 *  服务器监控报警 未通知信息
	 */
	public function serveralarm_select_not_notice_page($stime ,$etime,$perpage,$offset){
		
		if(empty($stime)||empty($etime)){
			
			$sql = "select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =0";
			$sql .= " order by A.id desc";
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'salarm');
			$sql = "(select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from ".$table['oTableName']." as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select * from (".$sql.") as t order by oAlarmTime desc ";
			
		}

		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 服务器监控报警 未通知信息数量
	 */
	public function serveralarm_select_not_notice_nums(){
		
		if(empty($stime)||empty($etime)){
			
			$sql = "select count(A.id) as num from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =0 ";
			
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'salarm');
			$sql = "(select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from ".$table['oTableName']." as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =0 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select count(*) as num from (".$sql.") as t ";
			
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	
	/**
	 *  服务器监控报警 已通知信息
	 */
	public function serveralarm_select_noticed_page($stime ,$etime,$perpage,$offset){
		
		
		if(empty($stime)||empty($etime)){
			
			$sql = "select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =1 ";
			$sql .= " order by A.id desc";
			
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'salarm');
			$sql = "(select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from ".$table['oTableName']." as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select * from (".$sql.") as t order by oAlarmTime desc ";
			
		}
		
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 服务器监控报警 已通知信息数量
	 */
	public function serveralarm_select_noticed_nums(){
	
		if(empty($stime)||empty($etime)){
			
			$sql = "select count(A.id) as num from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =1 ";
			
		}else{
			//兼容分表后的
			$dealtime = $this->helper->monitor_deal_etime($etime);
			$tables = $this->helper->monitor_union_table($stime,$dealtime,'salarm');
			$sql = "(select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from osa_collect_alarm as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql .="( union all select A.oAlarmText,A.oAlarmTime,oAlarmLevel,B.oDevName,B.oIp from ".$table['oTableName']." as A right join osa_device as B on A.oIpid = B.oIpid where A.oIsNotice =1 and A.oAlarmTime >'$stime' and A.oAlarmTime < '$etime')";
				}
			}
			$sql = "select count(*) as num from (".$sql.") as t ";
			
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/*
	public function alarm_init_isread(){
		
		$this->serveralarm_init_isread();
		$this->itemalarm_init_isread();
	}
	*/
	
	public function serveralarm_init_isread_isNotice(){
	
		$sql = "update osa_collect_alarm set oIsRead = 1 where oIsRead = 0 and oIsNotice = 1";
		$this->db->exec($sql);
	}
	
	public function serveralarm_init_isread_isNotNotice(){
	
		$sql = "update osa_collect_alarm set oIsRead = 1 where oIsRead = 0 and oIsNotice = 0";
		$this->db->exec($sql);
	}
	
	public function itemalarm_init_isread_isNotice(){
		$sql = "update osa_monitor_alarm set oIsRead = 1 where oIsRead = 0 and oIsNotice = 1";
		$this->db->exec($sql);
	}
	
	public function itemalarm_init_isread_isNotNotice(){
		$sql = "update osa_monitor_alarm set oIsRead = 1 where oIsRead = 0 and oIsNotice = 0";
		$this->db->exec($sql);
	}
	
	public function serveralarm_isnotread_isNotice_num(){
		$sql = "select count(*) as num from osa_collect_alarm where oIsRead =0 and oIsNotice = 1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	public function serveralarm_isnotread_isNotNotice_num(){
		$sql = "select count(*) as num from osa_collect_alarm where oIsRead =0 and oIsNotice = 0";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	public function itemalarm_isnotread_isNotice_num(){
		$sql = "select count(*) as num from osa_monitor_alarm where oIsRead =0 and oIsNotice = 1";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	public function itemalarm_isnotread_isNotNotice_num(){
		$sql = "select count(*) as num from osa_monitor_alarm where oIsRead =0 and oIsNotice = 0";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	
	/******************************  noticeset methond  ***************************************/
	
	public function notiset_select(){
	
		$sql = "select * from osa_notice_method";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs;
	}
	
	public function smsset_select(){
	
		$sql = "select * from osa_sms_config";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs;
	}
	
	public function gtalkset_select(){
	
		$sql = "select * from osa_gtalk_config";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs;
	}
	
	public function emailset_select(){
	
		$sql = "select * from osa_email_config";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs;
	}
	
	public function msnset_select(){
	
		$sql = "select * from osa_msn_config";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs;
	}
}