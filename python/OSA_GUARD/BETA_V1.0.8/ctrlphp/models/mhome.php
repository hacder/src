<?php
class mhome extends osa_model{


	public function __construct(){
	
		parent::__construct();
	}
	
	
	public function home_server_isalivenum(){
	
		$sql = "select count(*) as num from osa_ipinfo where oIsAlive = 1 and oIsStop = 0";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];
	}
	
	
	public function home_server_isdienum(){
	
		$sql = "select count(*) as num from osa_ipinfo where oIsAlive = 0 and oIsStop = 0";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];
		
	}
	
	
	public function home_server_isstopnum(){
	
		$sql = "select count(*) as num from osa_ipinfo where oIsStop = 1";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];
	}
	
	
	public function home_server_graph(){
	
		$alivenum = $this->home_server_isalivenum();
		$dienum = $this->home_server_isdienum();
		$isstopnum = $this->home_server_isstopnum();
		$total = $alivenum + $dienum + $isstopnum ;
		$serpie = "['正常',$alivenum],['故障',$dienum],['暂停',$isstopnum]";
		return array('total'=>$total,'serpie'=>$serpie);
	}
	
	
	public function home_item_isalivenum(){
	
		$sql = "select count(*) as num from osa_monitors where oStatus =1 and oIsStop = 0";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];	
	}
	
	public function home_item_isdienum(){
	
		$sql = "select count(*) as num from osa_monitors where oStatus =0 and oIsStop = 0";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];	
	}
	
	public function home_item_isstopnum(){
	
		$sql = "select count(*) as num from osa_monitors where oIsStop = 1";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];	
	}
	
	
	public function home_item_graph(){
	
		$alivenum = $this->home_item_isalivenum();
		$dienum = $this->home_item_isdienum();
		$isstopnum = $this->home_item_isstopnum();
		$total = $alivenum + $dienum + $isstopnum ;
		
		$itempie = "['正常',$alivenum],['故障',$dienum],['暂停',$isstopnum]";
		return array('total'=>$total,'itempie'=>$itempie);
	}
	
	
	public function home_server_alarmnum(){
	
		$sql = "select count(*) as num from osa_collect_alarm where oIsRead = 0";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];
	}
	
	
	public function home_item_alarmnum(){
	
		$sql = "select count(*) as num from osa_monitor_alarm where oIsRead = 0";
		$result = $this->db->queryFetchAllAssoc($sql);
		return $result[0]['num'];
	}
}