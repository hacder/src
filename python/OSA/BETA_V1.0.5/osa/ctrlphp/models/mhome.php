<?php
class mhome extends osa_model{
	
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取快捷菜单
	 */
	public function getShortCut($uid){
		$sql = "select oShortCut from osa_users where id=".$uid;
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){
			return $rs[0]['oShortCut'];
		}else{
			return 'error';
		}
	}
	
	/**
	 * 获取登录的用户
	 */
	public function getLoginUser(){
		$sql = "select oUserName from osa_users where oIsLogin = 1";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * 获取登录的ip
	 */
	function getLoginIp(){
		$ip = $this->db->getip();
		return $ip;
	}
	
	
	/**
	 * 获取最近操作记录
	 */
	public function getLastOperate(){
		$username = $_SESSION['username'];
		$sql = "select max(oLogAddTime) ,oLogTitle from osa_syslog where oUserName='$username' GROUP BY id ORDER BY oLogAddTime DESC";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['oLogTitle'];
	}
	
	/**
	 * 获取最新的补丁 key
	 */
	public function getLastPatch(){
		$sql = "select max(oNumkey) as numkey from osa_patch ";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs[0]['numkey']){
			return $rs[0]['numkey'];
		}
		return 0;
	}
	
	/**
	 * 获取数据库版本
	 */
	public function getMysqlVersion(){
		$sql = "select VERSION()";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['VERSION()'];
	}
}