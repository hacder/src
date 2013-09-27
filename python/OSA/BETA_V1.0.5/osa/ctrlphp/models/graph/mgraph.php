<?php

class mgraph extends osa_model{
	
	
	//
	public function __construct(){
		parent::__construct();
	}
	
	/***
	 * 获取 osa_ipinfo表信息
	 */
	
	public function selectIpinfo(){
		$sql = "select id ,oIp from osa_ipinfo";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 判断 osa_monitor 中对应ip是否有数据
	 */
	public function getMonitorNums($id){
		$sql = "select count(id) as num from osa_monitor where oIpid = $id ";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	/**
	*功能:绘图
	*参数：
	*@phtoname             应用到的层名
	*@ip	                  IP地址
	*@drawdatalist         Y轴数据，数组格式式,键名为:@phtoname  
	*@Mindata              数据库中记录监控数据最初时间
	*@starttime			  x轴起点时间
	*@drawtoline			  图名对应的曲线，数组格式,键名为:@phtoname  
	*@linemin			  Y轴曲线的最小值,数组格式,键名为:曲线名
	*/
	public function osa_draw($phtoname,$ip,$drawdatalist,$Mindata,$xcategories ,$ytitle,$starttime,$endtime){

		$entocn = array(
					'diskstat'    => '磁盘状态',
					'login'       => '登录用户',
					'loadstat'    => '负载状态',
					'process_num' => '进程数量',
					'memory' 	  => '内存状态',
					'network' 	  => '网络信息',
					'constat'	  => '连接数量'			
		
		);		
		foreach($entocn as $en_k=>$en_v){			
			if(strpos('_'.$phtoname,$en_k)){
				$titlename = str_replace($en_k,$en_v,$phtoname);
				break;
			}
		}
		$graphstr='';
		$divname = $phtoname; //应用到的层名
		$charttype = 'area'; //图形类型
		$charttitle = $ip.'_'.$titlename; //标题	
		$yseries = $drawdatalist[$divname]; //Y轴数据
		$starttime = date("Y,m,d,H,i,s",strtotime($starttime));
		$endtime = date("Y,m,d,H,i,s",strtotime($endtime));
		$maxtime = "(new Date(".$endtime.")).getTime()";
		$mintime = "(new Date(".$starttime.")).getTime()";		 
		$Hlist = array('divname','charttype','charttitle','xcategories','ytitle','yseries','maxtime','mintime');
		$contents = file_get_contents(OSA_PHPROOT_PATH.'/views/operate/highcharts.html');
		foreach($Hlist as $Hvalue){
			$rstr = "#".$Hvalue."#";
			$contents = str_replace($rstr,$$Hvalue,$contents);
		}
		$graphstr .= $contents;	
		$graphstr .= "<div id=\"$divname\" style=\"width: 700px; height: 400px;float:left; margin-bottom:10px;\"> </div>";	
		return $graphstr;
	}
	
	/**
	 * 获取日志类型
	 */
	public function selectLogType(){
		$sql = "select * from osa_syslog_cfg ";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 获取日常操作报表需要的xAjax值
	 */
	public function getLogType($rs){
		$xAjaxstr = '';
		foreach ($rs as $key){
			$xAjaxstr .="'".$key['oTypeText']."',";
		}
		return trim($xAjaxstr,',');
	}
	
	/**
	 * 获取日常操作报表需要的yAjax值
	 */
	public function getTypeNum($rs ,$starttime ,$endtime){
		$yAjaxstr = '';
		foreach ($rs as $key){
			$sql = "select count(id) as num from osa_syslog where oTypeid = ".$key['id'];
			$sql .= " and oLogAddTime > '$starttime' and oLogAddTime < '$endtime' ";
			$result =  $this->db->queryFetchAllAssoc($sql);
			$yAjaxstr .=  $result[0]['num'].',';
		}
		return trim($yAjaxstr,',');
	}
	
	
	/**
	 * 故障报表分析 yAjax 的值
	 */
	function getFaultNum($starttime ,$endtime){
		$faultarr = array(0,1,2,3);
		$yAjaxstr = '';
		foreach ($faultarr as $key){
			$sql = "select count(id) as num from osa_alarmmsg where oType = ".$key;
			$sql .= " and oAddTime > '$starttime' and oAddTime < '$endtime' ";
			$result =  $this->db->queryFetchAllAssoc($sql);
			$yAjaxstr .=  $result[0]['num'].',';
		}
		return trim($yAjaxstr,',');
	}
	/**
	 * 根据年份来生成数组
	 */
	public function createDateArr($year){
		$datearr = array();
		for($i=1;$i<=12;$i++){
			if($i<10){
				array_push($datearr,"$year-0$i-01");
			}else{
				array_push($datearr,"$year-$i-01");
			}
		}
		array_push($datearr,"$year-12-31");
		return $datearr;
	}
	
	/**
	 * 获取指定年份一月份钱的托管费用
	 */
	public function getTgPrice($year){
		$time = "$year-01-01";
		$sql = "select sum(oDevTgPrice) as oDevTgPrice from osa_devinfo where oCreateTime < '$time'";
		$result =  $this->db->queryFetchAllAssoc($sql);
		return empty($result[0]['oDevTgPrice'])?0:$result[0]['oDevTgPrice'];
	}
	
	/**
	 * 获取设备费用信息
	 */
	public function getDevPrice($starttime ,$endtime){
		$sql = "select sum(oDevTgPrice) as oDevTgPrice ,sum(oDevPrice) as oDevPrice from osa_devinfo where oCreateTime < '$endtime' and oCreateTime>'$starttime'";
		$result =  $this->db->queryFetchAllAssoc($sql);
		return $result;
	}
	
	/**
	 * 根据ip获取ipid
	 */
	public function getIdbyIp($ip = '127.0.0.1'){
		$sql = "select id from osa_ipinfo where oIp ='$ip'";
		$result =  $this->db->queryFetchAllAssoc($sql);
		return $result[0]['id'];
	}
}