<?php
class helper extends osa_model{

	
	public function __construct(){
	
		parent::__construct();
	}
	
	
	public function monitor_deal_etime($etime){
		
		$arr = explode("-",$etime);
		$year = $arr[0];
		$month = $arr[1];
		$spring = "01,02,03";
		$summer = "04,05,06";
		$autumn = "07,08,09";
		$winter = "10,11,12";
		if(strpos($spring,$month) !== false){
			$months = '04';
		}else if(strpos($summer,$month) !== false){
			$months = '07';
		}else if(strpos($autumn,$month) !== false){
			$months = '10';
		}else if(strpos($winter,$month) !== false){
			$year = $year +1 ;
			$months = '01';
		}
		$etime = $year."-".$months."-02";
		return $etime ;
	}
	
	
	//根据osa_table_manage表中查找出需要union all 的表
	public function monitor_union_table($stime ,$etime,$type){
	
		$sql = "select * from osa_table_manage where oCreateTime >'$stime' and oCreateTime<'$etime' and oTableType='$type'";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result;
	}
	
}