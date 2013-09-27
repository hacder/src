<?php 
/**
*程序初始化
*/

//包含配置文件
//require_once '../etc/ows_config.inc.php';

//关闭magic_quotes_gpc
if (get_magic_quotes_gpc()){
		ini_set ( 'magic_quotes_gpc' , 'off') ;        
}

//如果session是文件存储，则指定路径
if( ! ini_get('session.save_path') || ini_get('session.save_handler') == 'files' ){
	ini_set ( 'session.save_handler' , 'files');
	ini_set ( 'session.save_path' ,OSA_PHPSESSION_PATH) ;
	ini_set('session.gc_maxlifetime', OSA_SESSION_EXPIRE_TIME);
}

//如果session目录不存在则创建
if(!is_dir(OSA_PHPSESSION_PATH)){
    @mkdir(OSA_PHPSESSION_PATH);
	@chmod(OSA_PHPSESSION_PATH, 0777);
}
//data目录创建
if(!is_dir(OSA_PHPDATA_PATH)){
	@mkdir(OSA_PHPDATA_PATH);
	@chmod(OSA_PHPDATA_PATH, 0777);
}
//log目录创建
if(!is_dir(OSA_PHPLOG_PATH)){
	@mkdir(OSA_PHPLOG_PATH);
	@chmod(OSA_PHPLOG_PATH, 0777);
}

//指定编码
header("Content-type: text/html; charset=utf-8"); 

if (strnatcmp(phpversion(),'5.2.0') < 0)
{
	exit('请使用5.2.0以上的版本运行本程序！');
}

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

if(!iswriteable(OSA_PHPSESSION_PATH)){

	exit(OSA_PHPSESSION_PATH.'不可写,请创建并且设该目录为777权限再尝试！');

}
//data目录是否可写
if(!iswriteable(OSA_PHPDATA_PATH)){

	exit(OSA_PHPDATA_PATH.'不可写,请创建并且设该目录为777权限再尝试！');

}
//log目录是否可写
if(!iswriteable(OSA_PHPLOG_PATH)){

	exit(OSA_PHPLOG_PATH.'不可写,请创建并且设该目录为777权限再尝试！');

}

session_start();

//包含数据库文件
require_once OSA_PHPLIB_PATH.'./osa_db.class.php';

//系统核心函数
require_once OSA_PHPLIB_PATH.'./osa_system_function.php';

//系统帮助函数
require_once OSA_PHPLIB_PATH.'./osa_help_function.php';

//系统日志函数
require_once OSA_PHPLIB_PATH.'./osa_log_function.php';
////系统快捷菜单
require_once OSA_PHPETC_PATH.'./osa_config_shortcut.php';

//权限控制
require_once OSA_PHPLIB_PATH.'./osa_rose_function.php';

//参数安全过滤
require_once OSA_PHPLIB_PATH.'./osa_security.inc.php';

//require_once OSA_PHPLIB_PATH.'./ows_highcharts.class.php';

require_once OSA_PHPINCLUDE_PATH.'./osa_controllers.class.php';

require_once OSA_PHPINCLUDE_PATH.'./osa_model.class.php';

//包含server  已经整合到syetem_function 里面
//require_once OSA_PHPSERVER_PATH.'./ows_send_command.php';
