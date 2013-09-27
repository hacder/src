<?php 

/***
 * description : 封装画图过程中的数据获取等方法 
 * Author: ows开源团队
 * Date: 2011-10-15
 */

class highcharts extends osa_model{
	private $drawlist = array();  //图名数组
	private $linelist = array();  //曲线名数组
	private $drawtoline = array(); //图名对应曲线名数组
	private $ip ;//ip 
	private $linemax=array();   //最大值
	private $linemin=array();	//最小值
	private $lineavg=array();	//平均值
	private $Mindata ;
	public function __construct($id ){
		parent::__construct();
		//$this->initialize($id );	
	}
	
	/***
	 * 初始化数据
	 */	
	public function initialize($id){
		
		$this->ip = $this->db->ows_getIpById($id);	
		$data_list =$this->getDatalist($id );		
		$drawlist =  $linelist = $drawtoline = array(); 
		if(!$data_list){			
			return false;			
		}	
		foreach($data_list as $data_key => $data_value){	
			if(is_array($data_value)){				
				foreach($data_value as $dkey => $dvalue){		
					if(is_array($dvalue)){		
						$dname = $data_key."|".$dkey;	
						$drawlist[]  = 	$dname;																								
						foreach($dvalue as $dk => $dv){			
							$linelist[] = $dname."|".$dk;	
							$drawtoline[$dname][] = $dname."|".$dk;								
						}											
					}else{								
						//处理多线图
						$drawlist[]  = 	$data_key;
						$linelist[] = $data_key."|".$dkey;
						$drawtoline[$data_key][] = 	$data_key."|".$dkey;				
					}													
				}										
			}else{
				//处理单线图
				$drawlist[] = 	$data_key;
				$linelist[] =   $data_key;
				$drawtoline[$data_key][] = $data_key;					 
			}			
		}					
		$drawlist=(array_unique(array_values($drawlist)));
		$this->drawlist = $drawlist ;
		$this->linelist = $linelist ;
		$this->drawtoline = $drawtoline ;		
	}
	
	// 获取oMonText 数据用来后续分析
	public function getDatalist($id){
		$rdata=$this->db->select("select * from osa_monitor where oIpid = ".$id." order by id desc limit 1");
		$json_str=$rdata[0][oMonText];
		//print_r($json_str);
		if(empty($json_str))
		{ 
			return false ;
		}	
		$data_list=json_decode($json_str,true);
		if(!is_array($data_list)){
			$data_list = array();
		}
		$json_ex=					
			'{"memory":{"used":0,"total":0},"login":0,"process_num":0,"loadstat":{"fifteen":0,"five":0,"one":0},
				"network":{"eth0":{"outbond":0,"inbond":0}},"diskstat":{"/":{"used":0,"total":0},"/boot":{"used":0,"total":0},
					"/dev/shm":{"used":0,"total":0},"all":{"used":0,"total":0}},"constat":{"udp":0,"tcp":0,"allnum":0}}';
		$json_arr = json_decode($json_ex,true);
		return array_merge($json_arr ,$data_list);
	}
	
	//获取ip
	public function getIp(){		
		return $this->ip ;	
	}
	
	//获取 drawlist
	public function getDrawlist($type = ''){
		if($type == ''){
			return $this->drawlist ;
		}	
		$list = $this->drawlist ;
		$newlist = array();
		foreach ($list as $key) {
			if(($pos = strripos($key ,$type))!==false){
				array_push($newlist ,$key);
			}
		}
		return $newlist;		
	}
	
	//获取 drawtoline
	public function getDrawtoline(){
		return $this->drawtoline ;
	}
	
	//获取 linelist
	public function getLinelist(){
		return $this->linelist ;
	}
	
	// 获取所有数据
	public function getAlldata($id ,$starttime ,$endtime){
		$Qsql = "select * from osa_monitor where oIpid = ".$id." and oMonTime >= '".$starttime."' and oMonTime <= '".$endtime."'";
		return $this->db->select($Qsql);
	}
	
	// 
	public function getMindata(){	
		return $this->Mindata;
	}
	
	
	//补充数据，防止没有连续采集时出现Y轴数据与X轴时间不对应
	public function getNewalldata($id ,$starttime ,$endtime){
		
		$newAlldata = array();
		$allData = $this->getAlldata($id ,$starttime ,$endtime);
		$this->Mindata = $allData[0][oMonTime];
		$recordNum = count($allData);
		for($h=0;$h<$recordNum ;$h++){
			$ctime=strtotime($allData[$h][oMonTime]);
			$ntime=strtotime($allData[($h+1)][oMonTime]);
			if($ntime - $ctime > 305){				
				$num=floor(($ntime - $ctime)/300);
				for($g=0;$g<=$num;$g++){					
					$newAlldata[][oMonText] = 'null';
					$newAlldata[][oMonTime] = $allData[$h][oMonTime]+300*($g+1);					
				}				
			}else{			
				$newAlldata[][oMonText] = $allData[$h][oMonText];
				$newAlldata[][oMonTime] = $allData[$h][oMonTime];
			}
		}	
		return $newAlldata;	
	}
	
	//保存曲线图数据
	public function getLinedatalist($newAlldata){
		
		$linedatalist = array(); //保存曲线图数据
		$dataNum = count($newAlldata);
		$linelist = $this->linelist;
		for($j=0;$j<$dataNum;$j++){//外循环，遍历数据记录
			$json_str=$newAlldata[$j][oMonText];
			if($json_str=='null'){
				$json_str=					
				'{"memory":{"used":0,"total":0},"login":0,"process_num":0,"loadstat":{"fifteen":0,"five":0,"one":0},
					"network":{"eth0":{"outbond":0,"inbond":0}},"diskstat":{"/":{"used":0,"total":0},"/boot":{"used":0,"total":0},
						"/dev/shm":{"used":0,"total":0},"all":{"used":0,"total":0}},"constat":{"udp":0,"tcp":0,"allnum":0}}';
			}				
			$data_list=json_decode($json_str,true);	
			foreach($linelist as $lvalue){
				if(strpos($lvalue,'|')){				
					$llist=explode('|',$lvalue);							
					if(count($llist) == 2){
						$lone=$llist[0];
						$ltwo = $llist[1];							 
						$linedatalist[$lvalue] = trim($linedatalist[$lvalue],',').",".$data_list[$lone][$ltwo];							
					}else if(count($llist) == 3){	
						$linedatalist[$lvalue] = trim($linedatalist[$lvalue],',').",".$data_list["$llist[0]"]["$llist[1]"]["$llist[2]"];
					}						
				}else{
					$linedatalist[$lvalue] = trim($linedatalist[$lvalue],',').",".$data_list[$lvalue];				
				}				
			}	
			$linedatalist['datetime'] =	trim($linedatalist['datetime'],',').",".strtotime($newAlldata[$j]['oMonTime']);		
		}
		return $linedatalist;
	}
	
	// 根据$linedatalist求出曲线图最大值，最小值，平均值
	public function countDatalist($linedatalist){
		$linemax = $linemin = $lineavg = array();
		foreach($linedatalist as $line_k=>$line_value){	
			$line_value = substr($line_value,1,strlen($line_value));
			$larray = explode(',',$line_value);
			$linemax[$line_k] = max($larray);
			$linemin[$line_k] = min($larray);
			$lineavg[$line_k] = ceil(array_sum($larray)/count($larray));					
		}
		$this->linemax = $linemax ;
		$this->linemin = $linemin ;
		$this->lineavg = $lineavg ;
	}
	
	// 返回最大值
	public function getLinemax($name=''){
		if(empty($name)){
			return $this->linemax;
		}
		return $this->linemax[$name];
	}
	
	// 返回最小值
	public function getLinemin($name=''){
		if(empty($name)){
			return $this->linemin;
		}
		return $this->linemin[$name];
	}
	
	
	// 返回平均值
	public function getLineavg($name=''){
		if(empty($name)){
			return $this->lineavg;
		}
		return $this->lineavg[$name];
	}
	
	
	//根据图名获取Y轴数据
	public function getDrawdatalist($linedatalist , $flag = '0'){
		$drawdatalist = array();
		$lineavg = $this->lineavg;
		foreach($this->drawlist as $drawvalue){		
			$d_list=$this->drawtoline[$drawvalue]; //曲线名
			if(strpos('_'.$drawvalue,'network')||strpos('_'.$drawvalue,'diskstat')||strpos('_'.$drawvalue,'memory')){
				$danwei = $this->get_ytitle($drawvalue, $this->drawtoline, $lineavg);
			}
			//$timelist = explode($linedatalist['datetime'],',');	
			foreach($d_list as $d_k => $d_v){
				$name = array_reverse(explode('|',$d_v));
				$name = $name[0];			 
				//$data = substr($linedatalist[$d_v],1,strlen($linedatalist[$d_v]));
				$data = trim($linedatalist[$d_v],',');						
				// 单位转换
				if(strpos('_'.$d_v,'network') || strpos('_'.$d_v,'diskstat') || strpos('_'.$d_v,'memory')){							
					if(strpos('_'.$drawvalue,'network')){		
						$datalist = explode(',',$data);
						$datatemp = $temp = array();
						if($danwei == '千字节(KB)'){
							foreach($datalist as $datavalue){
								$datatemp[] = sprintf("%.2f", $datavalue/1024);									
							}
							$data = implode(',',$datatemp);
							
						}else if($danwei == '兆(MB)'){
							foreach($datalist as $datavalue){
								$datatemp[] = sprintf("%.2f", ($datavalue/1024)/1024);								
							}
							$data = implode(',',$datatemp);
						}								
					}else {						
						$datalist = explode(',',$data);
						$datatemp = $temp = array();
						if($danwei == '千兆字节(GB)'){
							foreach($datalist as $datavalue){
								$datatemp[] = sprintf("%.2f", $datavalue/1024);									
							}
							$data = implode(',',$datatemp);
						}else if($danwei == '(TB)'){
							foreach($datalist as $datavalue){	
								$datatemp[] = sprintf("%.2f", ($datavalue/1024)/1024);									
							}
							$data = implode(',',$datatemp);
						}							
					}						
				}
				if($flag == '1'){
					$datatemp = explode(',',$data);
					$max = max($datatemp);
					$min = min($datatemp);
					$avg = array_sum($datatemp)/count($datatemp);
					$max = empty($max)?number_format('0',2):number_format($max,2);
					$min = empty($min)?number_format('0',2):number_format($min,2);
					$avg = empty($avg)?number_format('0',2):number_format($avg,2);
					$name .= " 最大值:".$max." 最小值:".$min." 平均值:".$avg ;
				}							 
				$drawdatalist[$drawvalue] = "{name: '".$name."',data: [".$data."]},".$drawdatalist[$drawvalue];
			}	
			$drawdatalist[$drawvalue]=trim($drawdatalist[$drawvalue],',');
		}
		return $drawdatalist;
	}
	
	//获取xcategories
	public function get_xcategories($starttime='',$Mindata){
		/*
		 参数定义：		
			@starttime    起始时间	
			@Mindata	  数据库中最小时间
		*/
		//处理x轴坐标
		if($starttime == ''){
			//默认展示过去一天的数据		
			$starttime = strtotime("-7 day"); 
			$starttime=date("Y-m-d H:i:s",$starttime);		
		}		
		if(strtotime($starttime) >= strtotime($Mindata)){							
			$starttime = strtotime($starttime);
			$starttime = date("Y,m,d,H,i,s",strtotime("-1 month",$starttime));
		}else{			
			$starttime = strtotime($Mindata);
			$starttime = date("Y,m,d,H,i,s",strtotime("-1 month",$starttime));
		}
		$pointInterval = 300000;  //间隔为五分钟
		$xcategories = "pointInterval: ".$pointInterval.",pointStart: (new Date(".$starttime.")).getTime()";
		return $xcategories;		
	}
	
	//获取单位
	public function get_ytitle($phtoname,$drawtoline,$lineavg){
		$Ylist = array(
			'loadstat' => '数值',
			'memory' => '兆(MB)',
			'process_num' => '个',
			'diskstat' => '兆(MB)',
			'network' => '字节(bytes)',
			'constat' => '个',
			'login' => '用户'				
		);
		foreach($Ylist as $ykey => $yvalue){
			if(strpos("_".$phtoname,$ykey)){			
				$dd_list=$drawtoline[$phtoname]; //曲线名
				$average = 0;
				$tmpd_v = '';
				foreach($dd_list as $d_k => $d_v){					
				// 单位转换
					$average +=$lineavg[$d_v];
					$tmpd_v = $d_v;
				}
				$result = intval($average/count($dd_list));
				if(strpos('_'.$tmpd_v,'network') || strpos('_'.$tmpd_v,'diskstat') || strpos('_'.$tmpd_v,'memory')){		
					if( $result > 1024 && $result < (1024*1024)){	
						if(strpos('_'.$phtoname,'network')){		
							$danwei = '千字节(KB)';												
						}else{	
							$danwei =  '千兆字节(GB)';							
						}							
						return $danwei;							
					}else if( $result > (1024*1024)){	
						if(strpos('_'.$phtoname,'network')){
							$danwei = '兆(MB)';								
						}else{								
							$danwei = '(TB)';							
						}	
						return $danwei;						
					}else{
						return $Ylist[$ykey];					
					}													
				}else{
					return $Ylist[$ykey];				
				}					
			}		
		}	
	}	
}
?>
