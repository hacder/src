<?php
class msnmp extends osa_model{


	public function __construct(){
		
		parent::__construct();
	}
	
	
	/***************************select info *********************************************/
	/**
	 * select form osa_snmp
	 */
	public function snmp_select(){
		
		$sql = "select * from osa_snmp where id=1";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**************************update info ***********************************************/
	
	/**
	 * update osa_snmp
	 */
	public function snmp_update($snmpinfo){
		
		foreach ($snmpinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_snmp set $query where id=1";
		$this->db->exec($sql);
	}
	
	
	
	
}