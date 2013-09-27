<?php
class mpaint extends osa_model{

	
	private $helper = '';
	public function __construct(){
	
		parent::__construct();
		
		$this->helper = $this->loadmodel('helper');
	}
	
	
	/** *******************  osa 画图数据分析模块    ****************** **/
	
	public function monitor_data_select_itemid($itemid ,$stime ,$etime){
		
		$dealtime = $this->helper->monitor_deal_etime($etime);
		$tables = $this->helper->monitor_union_table($stime,$dealtime,'mdata');
		$sql = "(select * from osa_monitor_record where oItemid = ".$itemid." and oMonTime > '$stime' and oMonTime < '$etime' )";
		if(!empty($tables)){
			
			foreach ($tables as $table){
				$sql .=" union all (select * from ".$table['oTableName']." where oItemid = ".$itemid." and oMonTime > '$stime' and oMonTime < '$etime')";
			}
		}
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function server_data_select_ipid($ipid ,$stime ,$etime){
	
		$dealtime = $this->helper->monitor_deal_etime($etime);
		$tables = $this->helper->monitor_union_table($stime,$dealtime,'sdata');
		$sql = "(select * from osa_collect_data where oIpid = ".$ipid." and oCollectTime > '$stime' and oCollectTime < '$etime' ) ";
		if(!empty($tables)){
			
			foreach ($tables as $table){
				$sql .=" union all (select * from ".$table['oTableName']." where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime')";
			}
		}
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function monitor_iteminfo_fetch($itemid){
	
		$sql = "select * from osa_monitors where id =".$itemid;
		$rs = $this->db->queryFetchAllAssoc($sql);
		if(!empty($rs[0])){
			return $rs[0];
		}
		return '';
	}
	
	
	public function server_ipinfo_fetch($ipid){
		
		$sql = "select * from osa_device where oIpid =".$ipid;	
		$rs = $this->db->queryFetchAllAssoc($sql);
		if(!empty($rs[0])){
			return $rs[0];
		}
		return '';
	}
	
	public function get_system_type($ipid){
		
		$sql = "select oOsType from osa_ipinfo where id =".$ipid;	
		$rs = $this->db->queryFetchAllAssoc($sql);
		if(!empty($rs[0])){
			return $rs[0];
		}
		return '';
	}
	
	public function get_all_iplist(){
		
		$sql = "select id,oIp from osa_ipinfo";	
		$rs = $this->db->queryFetchAllAssoc($sql);
		if(!empty($rs)){
			return $rs;
		}
		return '';
	}
	
	/********************************************* 监控项目通用方法,可用率统计   ***************************************/	
	/**
	 * 可用率统计  通用
	 * 分类 今天,昨天按每小时统计,最近7天 最近15天 选择时间按天统计
	 */
	public function monitor_available_analyze($itemid,$stime,$etime){
		
		$timeone = strtotime($etime);
		$timetwo = strtotime($stime);
		$second  = $timeone - $timetwo;
		if($second > 2*24*60*60){ //相隔超过两天（从三天开始）
			$type = 'days';
		}else{
			$type = 'hours';	
		}	
		$data = $this->monitor_available_analyze_data($itemid,$stime,$etime,$type);	
		if(!empty($data)){
			$totalarr = $data[0];
			$exceptarr = $data[1];
			foreach ($totalarr as $key =>$value){
				if($type == 'hours'){
					$time = $key.":00:00";
				}else{
					$time = $key;
				}
				$time = strtotime($time)*1000 ; //返回unix时间微妙数
				if(!isset($exceptarr[$key]))
					$exceptnum = 0;
				else 
					$exceptnum = $exceptarr[$key];
				$rate = $value == 0?1:1 - $exceptnum/$value ;
				$rate = number_format($rate,5,'.','');
				$series_data .="[$time,$rate],";	
			}
			$series_data = trim($series_data,',');
			$available = "{name:'可用率',data:[$series_data]}";
			return $available ;	
		}
	}
	
	// 可用率数据分段统计
	public function monitor_available_analyze_data($itemid,$stime ,$etime,$type){
		
		//兼容分表后的
		$dealtime = $this->helper->monitor_deal_etime($etime);
		$tables = $this->helper->monitor_union_table($stime,$dealtime,'mdata');
		if($type == 'days'){
			$sql1 = "(select count(*) as num ,substr(oMonTime,1,10) as times from osa_monitor_record where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,10))";
			$sql2 = "(select count(*) as num ,substr(oMonTime,1,10) as times from osa_monitor_record where oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,10))";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql1 .=" union all (select count(*) as num ,substr(oMonTime,1,10) as times from ".$table['oTableName']." where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,10)) ";
					$sql2 .=" union all (select count(*) as num ,substr(oMonTime,1,10) as times from ".$table['oTableName']." where oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,10))";
				}
			}
		}else if($type == 'hours'){
			$sql1 = "(select count(*) as num ,substr(oMonTime,1,13) as times from osa_monitor_record where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,13))";
			$sql2 = "(select count(*) as num ,substr(oMonTime,1,13) as times from osa_monitor_record where oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,13))";
			if(!empty($tables)){
				
				foreach ($tables as $table){
					$sql1 .=" union all (select count(*) as num ,substr(oMonTime,1,13) as times from ".$table['oTableName']." where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,13))";
					$sql2 .=" union all (select count(*) as num ,substr(oMonTime,1,13) as times from ".$table['oTableName']." where oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime' group by substr(oMonTime,1,13))";
				}
			}
		}	
		
		$sql1 = "select sum(num) as num,times from( ".$sql1." ) as t1 group by times";
		$sql2 = "select sum(num) as num,times from( ".$sql2." ) as t2 group by times";
		$result1 = $this->db->queryFetchAllAssoc($sql1);
		$result2 = $this->db->queryFetchAllAssoc($sql2);
		if(!$result1){
			return false ;
		}
		$arr1 = $arr2 = array();
		foreach ($result1 as $data){
			$arr1[$data['times']] = $data['num'];
		}
		foreach ($result2 as $data){
			$arr2[$data['times']] = $data['num'];
		}
		return array(0=>$arr1,1=>$arr2);		
	}
	
	// 可用率 饼状图数据来源
	public function monitor_available_analyze_pie($itemid,$stime,$etime){
	
		//$sql1 = "select count(*) as num from osa_monitor_record where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime' ";
		//$sql2 = "select count(*) as num from osa_monitor_record where oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime' ";
		
		//兼容分表后的
		$dealtime = $this->helper->monitor_deal_etime($etime);
		$tables = $this->helper->monitor_union_table($stime,$dealtime,'mdata');
		$sql1 = "(select * from osa_monitor_record where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime')";
		$sql2 = "(select * from osa_monitor_record where  oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime')";
		if(!empty($tables)){
			
			foreach ($tables as $table){
				$sql1 .=" union all (select * from ".$table['oTableName']." where oItemid=$itemid and oMonTime>'$stime' and oMonTime<'$etime')";
				$sql2 .=" union all (select * from ".$table['oTableName']." where oItemid=$itemid and oStatus='异常' and oMonTime>'$stime' and oMonTime<'$etime')";
			}
		}
		
		$result1 = $this->db->queryFetchAllAssoc($sql1);
		$result2 = $this->db->queryFetchAllAssoc($sql2);
		$totalnum = count($result1);
		$exceptnum = count($result2);
		if($totalnum == 0){
			$except = 0 ;
		}else{
			$except = number_format(($exceptnum/$totalnum),4,'.','');
		}
		$normal = number_format(1-$except,4,'.','') ;
		$ablepie = "['正常',$normal],['故障',$except]";
		return $ablepie;
	}
	
	
	/********************************************* 监控服务器通用方法,可用率统计   ***************************************/	
	/**
	 * 可用率统计  通用
	 * 分类 今天,昨天按每小时统计,最近7天 最近15天 选择时间按天统计
	 */
	public function server_available_analyze($ipid,$stime,$etime){
		
		$timeone = strtotime($etime);
		$timetwo = strtotime($stime);
		$second  = $timeone - $timetwo;
		if($second > 2*24*60*60){ //相隔超过两天（从三天开始）
			$type = 'days';
		}else{
			$type = 'hours';	
		}	
		$data = $this->server_available_analyze_data($ipid,$stime,$etime,$type);	
		if(!empty($data)){
			$totalarr = $data[0];
			$exceptarr = $data[1];
			foreach ($totalarr as $key =>$value){
				if($type == 'hours'){
					$time = $key.":00:00";
				}else{
					$time = $key;
				}
				$time = strtotime($time)*1000 ; //返回unix时间微妙数
				if(!isset($exceptarr[$key]))
					$exceptnum = 0;
				else 
					$exceptnum = $exceptarr[$key];
				$rate = $value == 0?1:1 - $exceptnum/$value ;
				$rate = number_format($rate,5,'.','');
				$series_data .="[$time,$rate],";	
			}
			$series_data = trim($series_data,',');
			$available = "{name:'可用率',data:[$series_data]}";
			return $available ;	
		}
	}
	
	// 可用率数据分段统计
	public function server_available_analyze_data($ipid,$stime ,$etime,$type){
		
		//兼容分表后的
		$dealtime = $this->helper->monitor_deal_etime($etime);
		$tables = $this->helper->monitor_union_table($stime,$dealtime,'sdata');
		if($type == 'days'){
			$sql1 = "(select count(*) as num ,substr(oCollectTime,1,10) as times from osa_collect_data where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,10))";
			$sql2 = "(select count(*) as num ,substr(oCollectTime,1,10) as times from osa_collect_data where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,10))";
			if(!empty($tables)){
			
				foreach ($tables as $table){
					$sql1 .=" union all (select count(*) as num ,substr(oCollectTime,1,10) as times from ".$table['oTableName']." where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,10))";
					$sql2 .=" union all (select count(*) as num ,substr(oCollectTime,1,10) as times from ".$table['oTableName']." where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,10))";
				}
			}
		
		}else if($type == 'hours'){
			$sql1 = "(select count(*) as num ,substr(oCollectTime,1,13) as times from osa_collect_data where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,13))";
			$sql2 = "(select count(*) as num ,substr(oCollectTime,1,13) as times from osa_collect_data where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,13))";
			if(!empty($tables)){
			
				foreach ($tables as $table){
					$sql1 .=" union all (select count(*) as num ,substr(oCollectTime,1,13) as times from ".$table['oTableName']." where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,13))";
					$sql2 .=" union all (select count(*) as num ,substr(oCollectTime,1,13) as times from ".$table['oTableName']." where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime' group by substr(oCollectTime,1,13))";
				}
			}
		}	
		$sql1 = "select sum(num) as num,times from( ".$sql1." ) as t1 group by times";
		$sql2 = "select sum(num) as num,times from( ".$sql2." ) as t2 group by times";
		$result1 = $this->db->queryFetchAllAssoc($sql1);
		$result2 = $this->db->queryFetchAllAssoc($sql2);
		if(!$result1){
			return false ;
		}
		$arr1 = $arr2 = array();
		foreach ($result1 as $data){
			$arr1[$data['times']] = $data['num'];
		}
		foreach ($result2 as $data){
			$arr2[$data['times']] = $data['num'];
		}
		return array(0=>$arr1,1=>$arr2);		
	}
	
	// 可用率 饼状图数据来源
	public function server_available_analyze_pie($ipid,$stime,$etime){
	
		//$sql1 = "select count(*) as num from osa_collect_data where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime' ";
		//$sql2 = "select count(*) as num from osa_collect_data where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime' ";
		
		//兼容分表后的
		$dealtime = $this->helper->monitor_deal_etime($etime);
		$tables = $this->helper->monitor_union_table($stime,$dealtime,'sdata');
		$sql1 = "(select * from osa_collect_data where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime')";
		$sql2 = "(select * from osa_collect_data where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime')";
		if(!empty($tables)){
			
			foreach ($tables as $table){
				$sql1 .=" union all (select * from ".$table['oTableName']." where oIpid=$ipid and oCollectTime>'$stime' and oCollectTime<'$etime')";
				$sql2 .=" union all (select * from ".$table['oTableName']." where oIpid=$ipid and oStatus='异常' and oCollectTime>'$stime' and oCollectTime<'$etime')";
			}
		}
		$result1 = $this->db->queryFetchAllAssoc($sql1);
		$result2 = $this->db->queryFetchAllAssoc($sql2);
		$totalnum = count($result1);
		$exceptnum = count($result2);
		if($totalnum == 0){
			$except = 0 ;
		}else{
			$except = number_format(($exceptnum/$totalnum),4,'.','');
		}
		$normal = number_format(1-$except,4,'.','') ;
		$ablepie = "['正常',$normal],['故障',$except]";
		return $ablepie;
	}
	
	
	//根据period 来计算开始时间
	public function monitor_get_stime($period){
		
		if($period == 'today'){
			return date("Y-m-d",strtotime('today'));
		}else if($period == 'yesterday'){
			return date("Y-m-d",strtotime('yesterday'));
		}else if($period == 'last7days'){
			return date("Y-m-d",strtotime('-7 day'));
		}else if($period == 'last15days'){
			return date("Y-m-d",strtotime('-15 day'));
		}
	}
	
	//根据period 来计算结束时间
	public function monitor_get_etime($period){
		
		if($period == 'today'){
			return date("Y-m-d H:i:s",time());
		}else if($period == 'yesterday'){
			return date("Y-m-d",strtotime('today'));
		}else if($period == 'last7days'){
			return date("Y-m-d H:i:s",time());
		}else if($period == 'last15days'){
			return date("Y-m-d H:i:s",time());
		}
		
	}
	

	
	/******************************** memcache data analyze ****************************************************/
	
	/**
	 * memcache 缓存命中率分析
	 */
	public function memcache_indexrate_analyze($monitordata){
		
		if(empty($monitordata)){ //定义series 格式
			return "{name:'Memcache 缓存命中率',data:[[null,null],[null,null]]}";
		}
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->indexrate ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$series = "{name:'Memcache缓存命中率',data:[$series_data]}";
		return $series ;
	}
	
	/**
	 * Memcache 空间使用率
	 */
	public function memcache_spacerate_analyze($monitordata){
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->spacerate ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$spacerate = "{name:'Memcache空间使用率',data:[$series_data]}";
		return $spacerate ;
	}
	
	/**
	 * Memcache 使用内存
	 */
	public function memcache_usedmem_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->used_mem/(1024*1024) ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$usedmem = "{name:'Memcache使用内存',data:[$series_data]}";
		return $usedmem ;
	}
	
	/**
	 * Memcache 当前连接数
	 */
	public function memcache_currconnects_analyze($monitordata){
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->curr_connects;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$currconnects = "{name:'Memcache当前连接数',data:[$series_data]}";
		return $currconnects ;
		
	}
	
	
	/**
	 * Memcache 当前条目数量
	 */
	public function memcache_curritems_analyze($monitordata){
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->curr_item ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$curritems = "{name:'Memcache当前条目数',data:[$series_data]}";
		return $curritems ;		
	}		

	
	/**
	 * memcache 读写每秒
	 */
	public function memcache_wrsecond_analyze($monitordata){
	
		$read_data = $write_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$readrate = $jsondata->read_rate ;
			$readrate = number_format($readrate,'4','.','');
			$writerate = $jsondata->write_rate ;
			$writerate = number_format($writerate,'4','.','');
			$read_data .="[$time,$readrate],";	
			$write_data .= "[$time,$writerate]," ;
		}
		$read_data = trim($read_data,',');
		$write_data = trim($write_data,',');
		$wrsecond = "{name:'读/每秒',data:[$read_data]},{name:'写/每秒',data:[$write_data]}";
		return $wrsecond ;		
	}
	
	
	/**
	 * memcache 连接数每秒
	 */
	public function memcache_consecond_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->connects_rate ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$consecond = "{name:'连接数/每秒',data:[$series_data]}";
		return $consecond ;	
	}
	
	
	/************************************   redis data analyze   ***************************************/
	
	//redis 使用内存
	public function redis_usedmem_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->used_mem/(1024*1024) ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$usedmem = "{name:'Redis使用内存',data:[$series_data]}";
		return $usedmem ;
		
	}
	
	// redis 连接客户数
	public function redis_clientcon_analyze($monitordata){
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->connected_clients ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$clientcon = "{name:'Redis连接客户数',data:[$series_data]}";
		return $clientcon ;
		
	}
	
	
	//redis 连接从库数
	public function redis_slavecon_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->connected_slave ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$slavecon = "{name:'Redis连接从库数',data:[$series_data]}";
		return $slavecon ;
	}
	
	
	//redis 阻塞客户数
	public function redis_clientblock_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->blocked_clients ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$clientblock = "{name:'Redis阻塞客户数',data:[$series_data]}";
		return $clientblock ;
	}
	
	// redis pubsub 通道数
	public function redis_channel_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->pubsub_channels ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$channels = "{name:'Redis pub/sub通道数',data:[$series_data]}";
		return $channels ;	
	}
	
	//redis pubsub 模式数
	public function redis_pattern_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->pubsub_patterns ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$patterns = "{name:'Redis pub/sub模式数',data:[$series_data]}";
		return $patterns ;	
	}
	
	//redis 命中率
	public function redis_hitrate_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->index_rate ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$hitrate = "{name:'Redis命中率',data:[$series_data]}";
		return $hitrate ;	
		
	}
	
	
	public function redis_connectmin_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->connects_rate ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$connectmin = "{name:'Redis连接数每分钟',data:[$series_data]}";
		return $connectmin ;
		
	}
	
	
	public function redis_commandmin_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->command_rate ;
			$rate = number_format($rate,'2','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$commandmin = "{name:'Redis执行命令数每分钟',data:[$series_data]}";
		return $commandmin ;
	}

	/*********************************   mongodb data analyze   ************************************************/
	
	//mongodb 锁时间比例
	public function mongodb_lockratio_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->lock_ratio ;
			$rate = number_format($rate,'6','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$lockratio = "{name:'MongoDB全局锁时间比例',data:[$series_data]}";
		return $lockratio ;
	}
	
	//mongodb 锁等待数
	public function mongodb_lockwaits_analyze($monitordata){
		
		$total_wait = $writer_wait = $reader_wait = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$total = $jsondata->lock_total ;
			$total = number_format($total,'4','.','');
			$total_wait .="[$time,$total],";
			$writer = $jsondata->lock_writers ;
			$writer = number_format($writer,'4','.','');
			$writer_wait .="[$time,$writer],";
			$reader = $jsondata->lock_readers ;
			$reader = number_format($total,'4','.','');
			$reader_wait .="[$time,$reader],";	
		}
		$total_wait = trim($total_wait,',');
		$writer_wait = trim($writer_wait,',');
		$reader_wait = trim($reader_wait,',');
		$lockwaits = "{name:'总锁等待数',data:[$total_wait]},{name:'写锁等待数',data:[$writer_wait]},{name:'读锁等待数',data:[$reader_wait]}";
		return $lockwaits ;
	}
	
	// mongodb 使用内存、使用磁盘空间
	public function mongodb_usedspace_analyze($monitordata){
		
		$used_mem = $used_space ='';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$mem = $jsondata->used_mem ;
			$mem = number_format($mem,'4','.','');
			$used_mem .="[$time,$mem],";
			$space = $jsondata->used_space ;
			$space = number_format($space,'4','.','');
			$used_space .="[$time,$space],";
		}
		$used_mem = trim($used_mem,',');
		$used_space = trim($used_space,',');
		$usedspace = "{name:'MongoDB使用磁盘空间',data:[$used_space]},{name:'MongoDB使用内存',data:[$used_mem]}";
		return $usedspace ;
	}
	
	// mongodb 分页次数
	public function mongodb_pagefault_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->page_faults ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$pagefault = "{name:'MongoDB分页次数',data:[$series_data]}";
		return $pagefault ;
	}
	
	// mongodb 索引命中率
	public function mongodb_btreeratio_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->indexrate ;
			$rate = number_format($rate,'6','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$btreeratio = "{name:'MongoDB索引命中率',data:[$series_data]}";
		return $btreeratio ;
		
	}
	
	//mongodb 索引访问次数每秒
	public function mongodb_accesses_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->access_rate ;
			$rate = number_format($rate,'4','.','');
			$series_data .="[$time,$rate],";	
		}
		$series_data = trim($series_data,',');
		$accesses = "{name:'MongoDB索引访问次数每秒',data:[$series_data]}";
		return $accesses ;
	}
	
	//mongodb 当前连接数
	public function mongodb_currconnect_analyze($monitordata){
	
		$curr_data = $able_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$curr = $jsondata->curr_connects ;
			$curr = number_format($curr,'4','.','');
			$curr_data .="[$time,$curr],";	
			$able = $jsondata->able_connects ;
			$able = number_format($able,'4','.','');
			$able_data .="[$time,$able],";	
		}
		$able_data = trim($able_data,',');
		$curr_data = trim($curr_data,',');
		$currconnect = "{name:'可用连接数',data:[$able_data]},{name:'当前连接数',data:[$curr_data]}";
		return $currconnect;
		
	}
	
	
	public function mongodb_opcounters_analyze($monitordata){
	
		$query_data = $insert_data = $update_data = $delete_data = $getmore_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$query = $jsondata->query_rate ;
			$query = number_format($query,'4','.','');
			$query_data .="[$time,$query],";
			$insert = $jsondata->insert_rate ;
			$insert = number_format($insert,'4','.','');
			$insert_data .="[$time,$insert],";
			$update = $jsondata->update_rate ;
			$update = number_format($update,'4','.','');
			$update_data .="[$time,$update],";	
			$delete = $jsondata->delete_rate ;
			$delete = number_format($delete,'4','.','');
			$delete_data .="[$time,$delete],";
			$getmore = $jsondata->getmore_rate ;
			$getmore = number_format($getmore,'4','.','');
			$getmore_data .="[$time,$getmore],";
		}
		$query_data = trim($query_data,',');
		$insert_data = trim($insert_data,',');
		$update_data = trim($update_data,',');
		$delete_data = trim($delete_data,',');
		$getmore_data = trim($getmore_data,',');
		$opcounters = "{name:'query',data:[$query_data]},{name:'insert',data:[$insert_data]},{name:'delete',data:[$delete_data]},{name:'getmore',data:[$getmore_data]},{name:'update',data:[$update_data]}";
		return $opcounters ;
	}
	
	
	/***************************************   apache data analyze  ***********************************************/
	
	
	//apache 吞吐率
	public function apache_opcounters_analyze($monitordata){
	
		$rate_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->rateNum ;
			$rate = number_format($rate,'4','.','');
			$rate_data .="[$time,$rate],";		
		}
		$rate_data = trim($rate_data,',');
		$opcounters = "{name:'Apache吞吐率',data:[$rate_data]}";
		return $opcounters;
	}
	
	public function apache_capacity_analyze($monitordata){
	
		$capacity_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->capacityNum ;
			$capacity = number_format($capacity,'4','.','');
			$capacity_data .="[$time,$capacity],";	
		}
		$capacity_data = trim($capacity_data,',');
		$capacity = "{name:'Apache吞吐量',data:[$capacity_data]}";
		return $capacity;
	}
	
	
	public function apache_connects_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->totalNum ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$connects = "{name:'Apache并发连接数',data:[$series_data]}";
		return $connects;	
	}
	
	
	public function apache_scoreboard_analyze($monitordata){
	
		$wait_data = $read_data = $write_data = $keep_data = $close_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$wait = $jsondata->waitNum ;
			$wait = number_format($wait,'4','.','');
			$wait_data .="[$time,$wait],";
			$read = $jsondata->readNum ;
			$read = number_format($read,'4','.','');
			$read_data .="[$time,$read],";
			$write = $jsondata->writeNum ;
			$write = number_format($write,'4','.','');
			$write_data .="[$time,$write],";	
			$keep = $jsondata->keepNum ;
			$keep = number_format($keep,'4','.','');
			$keep_data .="[$time,$keep],";
			$close = $jsondata->closeNum ;
			$close = number_format($close,'4','.','');
			$close_data .="[$time,$close],";
		}
		$wait_data = trim($wait_data,',');
		$close_data = trim($close_data,',');
		$write_data = trim($write_data,',');
		$read_data = trim($read_data,',');
		$keep_data = trim($keep_data,',');
		$scoreboard = "{name:'等待连接',data:[$wait_data]},{name:'关闭连接',data:[$close_data]},{name:'发送响应内容',data:[$write_data]},{name:'读取请求',data:[$read_data]},{name:'持久连接',data:[$keep_data]}";
		return $scoreboard ;
		
	}
	
	
	/****************************************** nginx data analyze **************************************************/
	
	// nginx 吞吐率
	public function nginx_opcounters_analyze($monitordata){
	
		$rate_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->rateNum ;
			$rate = number_format($rate,'4','.','');
			$rate_data .="[$time,$rate],";		
		}
		$rate_data = trim($rate_data,',');
		$opcounters = "{name:'Nginx吞吐率',data:[$rate_data]}";
		return $opcounters;
	}
	
	// nginx 并发连接数
	public function nginx_connects_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->totalNum ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$connects = "{name:'Nginx活动连接数',data:[$series_data]}";
		return $connects;
	}
	
	
	public function nginx_scoreboard_analyze($monitordata){
	
		$wait_data = $read_data = $write_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$wait = $jsondata->waitNum ;
			$wait = number_format($wait,'4','.','');
			$wait_data .="[$time,$wait],";
			$read = $jsondata->readNum ;
			$read = number_format($read,'4','.','');
			$read_data .="[$time,$read],";
			$write = $jsondata->writeNum ;
			$write = number_format($write,'4','.','');
			$write_data .="[$time,$write],";	
		}
		$wait_data = trim($wait_data,',');
		$write_data = trim($write_data,',');
		$read_data = trim($read_data,',');
		$scoreboard = "{name:'持久连接(waiting)',data:[$wait_data]},{name:'处理请求和发送响应(writing)',data:[$write_data]},{name:'读取请求(reading)',data:[$read_data]}";
		return $scoreboard ;
		
	}
	
	
	/***************************************   lighttpd data analyze  ***********************************************/
	
	// lighttpd 吞吐率
	public function lighttpd_opcounters_analyze($monitordata){
	
		$rate_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$rate = $jsondata->rate ;
			$rate = number_format($rate,'4','.','');
			$rate_data .="[$time,$rate],";		
		}
		$rate_data = trim($rate_data,',');
		$opcounters = "{name:'Lighttpd吞吐率',data:[$rate_data]}";
		return $opcounters;
	}
	
	// lighttpd 并发连接数
	public function lighttpd_connects_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->totalNum ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$connects = "{name:'Lighttpd并发连接数',data:[$series_data]}";
		return $connects;
	}
	
	
	// lighttpd 连接数详情
	public function lighttpd_scoreboard_analyze($monitordata){
	
		$handel_data = $read_data = $write_data = $connect_data = $close_data = $rpost_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$handel = $jsondata->handleNum ;
			$handel = number_format($handel,'4','.','');
			$handel_data .="[$time,$handel],";
			$read = $jsondata->readNum ;
			$read = number_format($read,'4','.','');
			$read_data .="[$time,$read],";
			$write = $jsondata->writeNum ;
			$write = number_format($write,'4','.','');
			$write_data .="[$time,$write],";	
			$connect = $jsondata->connectNum ;
			$connect = number_format($connect,'4','.','');
			$connect_data .="[$time,$connect],";
			$close = $jsondata->closeNum ;
			$close = number_format($close,'4','.','');
			$close_data .="[$time,$close],";
			$rpost = $jsondata->rpostNum ;
			$rpost = number_format($rpost,'4','.','');
			$rpost_data .="[$time,$rpost],";
		}
		$handel_data = trim($handel_data,',');
		$close_data = trim($close_data,',');
		$write_data = trim($write_data,',');
		$read_data = trim($read_data,',');
		$connect_data = trim($connect_data,',');
		$rpost_data = trim($rpost_data,',');
		$scoreboard = "{name:'handle-request',data:[$handel_data]},{name:'close',data:[$close_data]},{name:'write',data:[$write_data]},{name:'read',data:[$read_data]},{name:'connect',data:[$connect_data]},{name:'read-POST',data:[$rpost_data]}";
		return $scoreboard ;
	}
	
	
	/************************************   mysql data analyze　****************************************/
	
	public function mysql_scoreboard_analyz($monitordata){
	
		$max_data = $create_data = $con_data = $run_data = $cache_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$max = $jsondata->max_connections ;
			$max = number_format($max,'4','.','');
			$max_data .="[$time,$max],";
			$create = $jsondata->Threads_created ;
			$create = number_format($create,'4','.','');
			$create_data .="[$time,$create],";
			$con = $jsondata->Threads_connected ;
			$con = number_format($con,'4','.','');
			$con_data .="[$time,$con],";	
			$run = $jsondata->Threads_running ;
			$run = number_format($run,'4','.','');
			$run_data .="[$time,$run],";
			$cache = $jsondata->Threads_cached ;
			$cache = number_format($cache,'4','.','');
			$cache_data .="[$time,$cache],";
		}
		$max_data = trim($max_data,',');
		$create_data = trim($create_data,',');
		$con_data = trim($con_data,',');
		$run_data = trim($run_data,',');
		$cache_data = trim($cache_data,',');
		$scoreboard = "{name:'最大允许连接数',data:[$max_data]},{name:'实际最大连接数',data:[$create_data]},{name:'当前连接数',data:[$con_data]},{name:'活跃连接数',data:[$run_data]},{name:'缓存连接数',data:[$cache_data]}";
		return $scoreboard ;
	}
	
	
	// mysql 查询缓存利用率
	public function mysql_qcachespace_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->query_cache_rate ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$qcachespace = "{name:'Mysql查询缓存空间使用率',data:[$series_data]}";
		return $qcachespace;
	}
	
	
	// mysql 查询缓存命中率
	public function mysql_qcachehits_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->hits_cache_rate ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$qcachehite = "{name:'Mysql查询缓存命中率',data:[$series_data]}";
		return $qcachehite;
	}
	
	// mysql 查询缓存碎片率
	public function mysql_qcachescrap_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->scrap_cache_rate ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$qcachescrap = "{name:'Mysql查询缓存碎片率',data:[$series_data]}";
		return $qcachescrap;
	}
	
	// mysql 缓存访问率
	public function mysql_qcachevisite_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->visite_cache_rate ;
			$capacity = number_format($capacity,'6','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$qcachevisite = "{name:'Mysql缓存访问率',data:[$series_data]}";
		return $qcachevisite;
	}
	
	
	// mysql 查询吞吐率
	public function mysql_opcounters_analyze($monitordata){
	
		$change_data = $select_data = $delete_data = $update_data = $insert_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);
			$change = $jsondata->change_db_rate ;
			$change = number_format($change,'4','.','');
			$change_data .="[$time,$change],";
			$select = $jsondata->select_rate ;
			$select = number_format($select,'4','.','');
			$select_data .="[$time,$select],";
			$delete = $jsondata->delete_rate ;
			$delete = number_format($delete,'4','.','');
			$delete_data .="[$time,$delete],";	
			$update = $jsondata->update_rate ;
			$update = number_format($update,'4','.','');
			$update_data .="[$time,$update],";
			$insert = $jsondata->insert_rate ;
			$insert = number_format($insert,'4','.','');
			$insert_data .="[$time,$insert],";
		}
		$change_data = trim($change_data,',');
		$select_data = trim($select_data,',');
		$delete_data = trim($delete_data,',');
		$update_data = trim($update_data,',');
		$insert_data = trim($insert_data,',');
		$opcounters = "{name:'Change db',data:[$change_data]},{name:'Select',data:[$select_data]},{name:'Update',data:[$update_data]},{name:'Insert',data:[$insert_data]},{name:'Delete',data:[$delete_data]}";
		return $opcounters ;
	}
	
	
	// mysql 表锁定
	public function mysql_locktable_analyze($monitordata){
	
		$now_data = $wait_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$now = $jsondata->locks_immediate_rate ;
			$now = number_format($now,'4','.','');
			$now_data .="[$time,$now],";
			$wait = $jsondata->locks_waited_rate ;
			$wait = number_format($wait,'4','.','');
			$wait_data .="[$time,$wait],";	
		}
		$now_data = trim($now_data,',');
		$wait_data = trim($wait_data,',');
		$locktable = "{name:'Mysql等待的表锁数',data:[$wait_data]},{name:'Mysql立即释放的表锁数',data:[$now_data]}";
		return $locktable;		
	}
	
	
	// mysql 流量图
	public function mysql_flowchart_analyze($monitordata){
	
		$sent_data = $received_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$sent = $jsondata->bytes_sent_rate ;
			$sent = number_format($sent,'4','.','');
			$sent_data .="[$time,$sent],";
			$received = $jsondata->bytes_received_rate ;
			$received = number_format($received,'4','.','');
			$received_data .="[$time,$received],";	
		}
		$sent_data = trim($sent_data,',');
		$received_data = trim($received_data,',');
		$flowchart = "{name:'流入速率',data:[$received_data]},{name:'流出速率',data:[$sent_data]}";
		return $flowchart;		
	}

	
	//  mysql 缓存查询数 
	public function mysql_querycache_analyze($monitordata){
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->Qcache_query ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$querycache = "{name:'Mysql缓存查询数',data:[$series_data]}";
		return $querycache;
	}
	
	// mysql 失败连接数
	public function mysql_failedcon_analyze($monitordata){
	
		$clients_data = $connects_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$clients = $jsondata->Aborted_clients_rate ;
			$clients = number_format($clients,'4','.','');
			$clients_data .="[$time,$clients],";
			$connects = $jsondata->Aborted_connects_rate ;
			$connects = number_format($connects,'4','.','');
			$connects_data .="[$time,$connects],";	
		}
		$clients_data = trim($clients_data,',');
		$connects_data = trim($connects_data,',');
		$failedcon = "{name:'失败连接数',data:[$connects_data]},{name:'中断连接数',data:[$clients_data]}";
		return $failedcon;
	}
	
	
	// mysql 查询数量
	public function mysql_questions_analyze($monitordata){
		
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->questions_rate ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$questions = "{name:'Mysql查询数量',data:[$series_data]}";
		return $questions;
	}
	
	/**********************************   其他     data  analyze    ********************************************/
	
	// 监控项目  response time
	public function monitor_response_analyze($monitordata){
	
		$series_data = '';
		foreach ($monitordata as $data){
			
			$time = strtotime($data['oMonTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oMonResult']);	
			$capacity = $jsondata->responsetime ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$response = "{name:'响应时间',data:[$series_data]}";
		return $response;
	}
	
	
	/***********************************   server data analyze   *********************************************/
	
	// 服务器响应时间
	public function server_response_analyze($serverdata){
	
		$series_data = '';
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);	
			$capacity = $jsondata->responsetime ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$response = "{name:'响应时间',data:[$series_data]}";
		return $response;
	}
	
	
	public function server_memory_analyze($serverdata){
	
		$real_used = $real_total = $real_rate = $swap_used = $swap_total = $swap_rate = '';
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);
			$memory = $jsondata->memory;	
			$rused = number_format($memory->real_used,'4','.','' );
			$real_used .="[$time,$rused],";	
			$rtotal = number_format($memory->real_total,'4','.','' );
			$real_total .="[$time,$rtotal],";
			$rBuffer = number_format($memory->Buffer,'4','.','' );
			$r_Buffer .="[$time,$rBuffer],";
			$rCached = number_format($memory->Cached,'4','.','' );
			$r_Cached .="[$time,$rCached],";	
			
			
			
			$sused = number_format($memory->swap_used,'4','.','' );
			$swap_used .="[$time,$sused],";	
			$stotal = number_format($memory->swap_total,'4','.','' );
			$swap_total .="[$time,$stotal],";
			
			$rrate = $rtotal==0?0:number_format(($rused/$rtotal),'4','.','' );
			$real_rate .="[$time,$rrate],";
			$srate = $stotal==0?0:number_format(($sused/$stotal),'6','.','' );
			$swap_rate .="[$time,$srate],";		
		}
		$real_used = trim($real_used,',');
		$real_total = trim($real_total,',');
		
		$real = "{name:'total',data:[$real_total]},{name:'used',data:[$real_used]},
				{name:'Buffer',data:[$r_Buffer]},{name:'Cached',data:[$r_Cached]}
				";
		$swap_used = trim($swap_used,',');
		$swap_total = trim($swap_total,',');
		$swap = "{name:'swap total',data:[$swap_total]},{name:'swap used',data:[$swap_used]}";
		$real_rate = trim($real_rate,',');
		$swap_rate = trim($swap_rate,',');
		$rate = "{name:'内存使用率',data:[$real_rate]},{name:'SWAP内存使用率',data:[$swap_rate]}";	
		return array('real'=>$real,'swap'=>$swap,'rate'=>$rate);
	}
	
	public function server_logins_analyze($serverdata){
	
		$series_data = '';
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);	
			$capacity = $jsondata->user_login ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$logins = "{name:'logins',data:[$series_data]}";
		return $logins;
	}
	
	public function server_loadstat_analyze($serverdata){
	
		$one_data = $five_data = $fifteen_data =''; 
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);
			$loadstat = $jsondata->loadstat;	
			$one = number_format($loadstat->one,'4','.','' );
			$one_data .="[$time,$one],";	
			$five = number_format($loadstat->five,'4','.','' );
			$five_data .="[$time,$five],";
			$fifteen = number_format($loadstat->fifteen,'4','.','' );
			$fifteen_data .="[$time,$fifteen],";
		}
		$one_data = trim($one_data,',');
		$five_data = trim($five_data,',');
		$fifteen_data = trim($fifteen_data,',');
		$loadstat = "{name:'1分钟负载平均值',data:[$one_data]},{name:'5分钟负载平均值',data:[$five_data]},{name:'15分钟负载平均值',data:[$fifteen_data]}";
		return $loadstat;
	}
	
	public function server_processnum_analyze($serverdata){
	
		$series_data = '';
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);	
			$capacity = $jsondata->process_num ;
			$capacity = number_format($capacity,'4','.','');
			$series_data .="[$time,$capacity],";	
		}
		$series_data = trim($series_data,',');
		$processnum = "{name:'进程数量',data:[$series_data]}";
		return $processnum;
	}
	
	
	public function server_diskstat_analyze($serverdata){
		
		
		$disk = $stat = array();
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);	
			
			$diskstat = $jsondata->disk;
			
			if(!empty($diskstat)){
				foreach ($diskstat as $key =>$value){
	
					$use = number_format($value->use,'4','.','');
					$disk[$key]['use'] .= "[$time,$use]," ;
					$total = number_format($value->total,'4','.','');
					$disk[$key]['total'] .= "[$time,$total]," ;
				}	
			}
		}
		
		if(!empty($disk)){
			foreach($disk as $key =>$value){
				$use = trim($value['use'],',');
				$total = trim($value['total'],',');
				$stat[$key]="{name:'total',data:[$total]},{name:'used',data:[$use]}";
			}
		}
		return $stat;
	}
	
	
	public function server_constat_analyze($serverdata){
	
		$tcp_data = $udp_data = $all_data =''; 
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);
			$constat = $jsondata->constat;	
			$tcp = number_format($constat->tcp,'4','.','' );
			$tcp_data .="[$time,$tcp],";	
			$udp = number_format($constat->udp,'4','.','' );
			$udp_data .="[$time,$udp],";
			$all = number_format($constat->all,'4','.','' );
			$all_data .="[$time,$all],";
		}
		$tcp_data = trim($tcp_data,',');
		$udp_data = trim($udp_data,',');
		$all_data = trim($all_data,',');
		$constat = "{name:'all',data:[$all_data]},{name:'udp',data:[$udp_data]},{name:'tcp',data:[$tcp_data]}";
		return $constat;
	}
	
	
	public function server_network_analyze($serverdata){
	

		$nets = $stat = array();
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);	
			$network = $jsondata->network;
			if(!empty($network)){
				foreach ($network as $key =>$value){
	
					$inbond = number_format($value->inbond,'4','.','');
					$nets[$key]['inbond'] .= "[$time,$inbond]," ;
					$outbond = number_format($value->outbond,'4','.','');
					$nets[$key]['outbond'] .= "[$time,$outbond]," ;
				}
			}	
		}
		if(!empty($nets)){
			foreach($nets as $key =>$value){
				$inbond = trim($value['inbond'],',');
				$outbond = trim($value['outbond'],',');
				$stat[$key]="{name:'入口流量',data:[$inbond]},{name:'出口流量',data:[$outbond]}";
			}
		}
		return empty($stat)?'':$stat;
	}
	
	
	public function server_usedcpu_analyze($serverdata){
	
		$kernel_data = $user_data = $low_data =$win_data=$irq_data=$nice_data=$system_data=$wait_data=$softirq_data=$idle_data=''; 
		$win_cpu_data='';
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);
			$cpu = $jsondata->cpu;	
			
			if($cpu->win){
			
			$win = number_format($cpu->win,'4','.','' );
			$win_data .= "[$time,$win],";
			$win_cpu_total= number_format($cpu->win_cpu_total,'4','.','' );
			$win_cpu_data .= "[$time,$win_cpu_total],";			
			
			}else{
			
			$kernel = number_format($cpu->kernel,'4','.','' );
			$kernel_data .="[$time,$kernel],";	
			$user = number_format($cpu->user,'4','.','' );
			$user_data .="[$time,$user],";				
			$idle = number_format($cpu->idle,'4','.','' );
			$idle_data .="[$time,$idle],";
			$softirq = number_format($cpu->softirq,'4','.','' );
			$softirq_data .="[$time,$softirq],";
			$irq = number_format($cpu->irq,'4','.','' );
			$irq_data .="[$time,$irq],";
			$wait = number_format($cpu->wait,'4','.','' );
			$wait_data .="[$time,$wait],";
			$system = number_format($cpu->system,'4','.','' );
			$system_data .="[$time,$system],";
			$nice = number_format($cpu->nice,'4','.','' );
			$nice_data .="[$time,$nice],";
			}
							
		}
		if ($win_data !=''){
		
			$win_data = trim($win_data,',');
			$usedcpu ="{name:'win_cpu_total(%)',data:[$win_cpu_data]},{name:'win_cpu_usage(%)',data:[$win_data]}";
			return $usedcpu;
		}
		$kernel_data = trim($kernel_data,',');
		$user_data = trim($user_data,',');
		$idle_data = trim($idle_data,',');
		$softirq_data = trim($softirq_data,',');
		$irq_data = trim($irq_data,',');
		$wait_data = trim($wait_data,',');
		$system_data = trim($system_data,',');
		$nice_data = trim($nice_data,',');
		
		$usedcpu = "{name:'Idle(%)',data:[$idle_data]},{name:'Kernel(%)',data:[$kernel_data]},{name:'User(%)',data:[$user_data]}
					,{name:'Iowait(%)',data:[$wait_data]},{name:'Nice(%)',data:[$nice_data]}
					,{name:'System(%)',data:[$system_data]},{name:'Irq(%)',data:[$irq_data]},{name:'SoftIrq(%)',data:[$softirq_data]}";
		return $usedcpu;
	}
	
	
	public function server_diskio_analyze($serverdata){
	
		$io = $stat = array();
		foreach ($serverdata as $data){
			
			$time = strtotime($data['oCollectTime'])*1000 ; //返回unix时间微妙数
			$jsondata = json_decode($data['oCollectData']);	
			$diskio = $jsondata->io;
			if(!empty($diskio)){
				foreach ($diskio as $key =>$value){
	
					$read = number_format($value->read,'4','.','');
					$io[$key]['read'] .= "[$time,$read]," ;
					$write = number_format($value->write,'4','.','');
					$io[$key]['write'] .= "[$time,$write]," ;
				}	
			}
		}
		if(!empty($io)){
			foreach($io as $key =>$value){
				$read = trim($value['read'],',');
				$write = trim($value['write'],',');
				$stat[$key]="{name:'读取字节量',data:[$read]},{name:'写入字节量',data:[$write]}";
			}
		}
		return $stat;
	}
	
	
	
}