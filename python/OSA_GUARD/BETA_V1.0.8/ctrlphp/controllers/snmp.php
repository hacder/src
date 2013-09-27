<?php
class snmp extends osa_controller{

	
	private $model = '';
	
	public function __construct(){
	
		parent::__construct();
		$this->model = $this->loadmodel('msnmp');
		if (!isset($_SESSION)){
			session_start();
		}
	}
	
	/************************************views *******************************************/
	
	/***
	 * snmp set view
	 */
	public function snmpset(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['snmpinfo'] = $this->model->snmp_select();
		$this->loadview('snmp/snmpset',$data);
	}
	
	
	
	/**
	 * search ip server view
	 */
	public function autosearch(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('snmp/autosearch');
	}
	
	
	
	
	/*******************************ajax event*********************************************/
	/**
	 * snmp info update
	 */
	public function snmp_edit(){
		
		$snmpinfo = array(
			'oSnmpName'=>trim($_POST['snmpname']),
			'oSnmpPort'=>trim($_POST['snmpport']),
			'oSnmpkey'=>trim($_POST['snmpkey'])
		);
		
		$this->model->snmp_update($snmpinfo);
		echo 'success';return;
	}
	
	
	
	public function error(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		
		$this->loadview('error');
	}
	
	
	
	public function download(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$file = isset($_GET['file'])?$_GET['file']:'';
		if($file == 'redis'){
			$filepath = 'data/download/redis_status.zip';
			$filename = 'redis_status.zip';
		}else if($file == 'memcache'){
			$filepath = 'data/download/memcacheStatus.zip';
			$filename = 'memcacheStatus.zip';
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$msg ='下载文件不能为空!';
			exit($msg);
		}
		if(file_exists($filepath)){
			header('Content-type:application/zip');
			header('Content-Disposition: attachment; filename='.$filename);
			readfile($filepath);
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$msg ='下载文件不能为空!';
			exit($msg);
		}
	}
}