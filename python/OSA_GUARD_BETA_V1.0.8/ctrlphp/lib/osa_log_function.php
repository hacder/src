<?php

/**
 * osa sava log
 */

function osa_logs_error($content=''){
	
	if(empty($content)){
		return ;
	}	
	$today=date("Y-m-d",time()); 
	$filename = OSA_PHPLOG_PATH.$today.'.log';
	$msg = "时间：".date("Y-m-d H:i:s",time());
	$content = $msg." ".$content;
	
	if(is_writable($filename)){
		@file_put_contents($filename,$content,FILE_APPEND);
	}else{
		 $f=fopen($filename,'a+');
         fwrite($f,$content);
         fclose($f);
                
	}	
}


function osa_log_debug($content){

	if(empty($content)){
		return ;
	}
	$filepath = OSA_PHPLOG_PATH.'debug.log';
	
	if(is_writable($filepath)){
		@file_put_contents($filepath,$content,FILE_APPEND);
	}else{
		exit($filepath.'不可写！');
	}	
}