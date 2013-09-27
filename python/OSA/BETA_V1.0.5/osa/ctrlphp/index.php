<?php
//Header("Location:page/index.php");
define('OSA_PHPROOT_PATH',str_replace('\\', '/', dirname(__FILE__)));
if(is_file(OSA_PHPROOT_PATH.'/etc/osa_config.inc.php')){
	$config = array(
		'url_model'=>'1',
		'control' =>'login',
		'action' => 'index'
	);
	require_once OSA_PHPROOT_PATH.'/etc/osa_config.inc.php';
	require_once OSA_PHPROOT_PATH.'/include/osa_system_init.php';
	if(!is_writable(OSA_PHPSESSION_PATH)){
		exit(OSA_PHPSESSION_PATH.'不可写,请创建并且设该目录为777权限再尝试！');
	}
	$control = new osa_controller();		//实例化控制器
	$control->run();
}else{
	Header("Location:./install/index.php");
}
