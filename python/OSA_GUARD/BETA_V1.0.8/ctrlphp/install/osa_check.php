<?php
/*
	OWS环境检查文件，主要用来检查OWS运行的软件环境，在安装时候使用，也可以作为OWS迁移后的一个环境检查和拍错程序。
*/

$OSA_PATH=trim(preg_replace("/ctrlphp.*/",' ',str_replace('\\', '/', dirname(__FILE__))));
require_once $OSA_PATH.'ctrlphp/etc/osa_config.inc.sample.php';
$ctrlphp_dir=1; //ctrlphp/是否可写
$ctrlphp_etc=0; //ctrlphp/etc/是否可写
$ctrlpy_config=0; //ctrlpy/config.py是否可写
//$ctrlpy_service_sh=0; //ctrlpy/ows_services.sh是否可写
//$unctrlpy_config=0; //unctrlpy/etc/config.py是否可写
//$unctrlpy_service_sh=0; //unctrlpy_service.sh是否可写
$phpsession_path=0; //ctrlphp/etc/session/是否可写
$php_version=0; //php版本
$php_pdo_mysql=0; //php是否支持pdo_mysql
$php_mbstring=0; //php是否支持mbstring	
$php_json=0; //php是否支持pdo_mysql
$php_curl=0; //php是否支持mbstring	
$osa_disk_check=0; //检查安装目录是否有足够磁盘空间
$check_python=0; //检查python相关模块是否安装

function iswriteable($file){
	clearstatcache();
	if(is_dir($file)){
		$dir=$file;
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		}else{
			$writeable = 0;
		}
		
	}else{
	
		if($fp = @fopen($file, 'a+')) {
			@fclose($fp);
			$writeable = 1;
		}else {
			$writeable = 0;
		}
	}
	
return $writeable;
}

//检查修改路径的几个文件是否可写

		if (iswriteable($OSA_PATH . 'ctrlphp/etc')){
			$ctrlphp_etc=1;
		}else{
			$ctrlphp_etc=0;
		}


		if (iswriteable($OSA_PATH . 'ctrlpy/etc/config.py')){
			$ctrlpy_config=1;
		}else{
			$ctrlpy_config=0;
		}
	

		
	
//如果session使用file保存，检查保存session目录的权限
	if( ! ini_get('session.save_path') || ini_get('session.save_handler') == 'files' ){
		if(!is_dir($OSA_PATH . 'ctrlphp/session/')){
			@mkdir($OSA_PATH . 'ctrlphp/session/');
			@chmod($OSA_PATH . 'ctrlphp/session/',0777);
		}
	}
	if(iswriteable($OSA_PATH . 'ctrlphp/session')){
		$phpsession_path=1;
	}else{
		$phpsession_path=0;
	}



//检查python相关是否安装成功
	if(is_readable('/tmp/.check_py')){
		$file=file_get_contents('/tmp/.check_py');
		if(strlen($file)==155){
			$check_python=1;
		}
	}



//检查PHP环境
//检查PHP版本
	if (strnatcmp(phpversion(),'5.2.0') >= 0){
			$php_version=1;
	}else{
			$php_version=0;	
	}
//检查php支持
	foreach(get_loaded_extensions() as $val){
		if($val == 'pdo_mysql'){
			$php_pdo_mysql=1;
		}
		if($val == 'mbstring'){
			$php_mbstring=1;
		}
		if($val == 'json'){
			$php_json=1;
		}
		if($val == 'curl'){
			$php_curl=1;
		}
	}

//硬盘空间检测
$osa_disk_free=round((disk_free_space($OSA_PATH) / 1048576), 0);
if($osa_disk_free>20){
	$osa_disk_check=1;
}

$check_sucess=1;

$check_arg=array($ctrlphp_etc,$ctrlpy_config,$phpsession_path,$php_version,$php_pdo_mysql,$php_mbstring,$php_json,$php_curl,$osa_disk_check,$check_python,$ctrlphp_dir);
foreach($check_arg as $var){
	if($var==0){
		$check_sucess=0;
	}
}
?>
