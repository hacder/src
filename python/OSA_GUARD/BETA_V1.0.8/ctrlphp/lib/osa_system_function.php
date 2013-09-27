<?php
/**
 * 时间展示函数（为了批量操作中时间显示）
 * $cycle ,$date ,$time
 */
function showTimes($cycle ,$date ,$time){
	$weekarr = array(
		'Mon'=>'星期一',
		'Tue'=>'星期二',
		'Wed'=>'星期三',
		'Thu'=>'星期四',
		'Fri'=>'星期五',
		'Sat'=>'星期六',
		'Sun'=>'星期天'	
	);
	switch($cycle){
		case 'Monthly':
			echo '每月'.$date.'号:'.$time ;
			break ;
		case 'Weekly':
			$week = explode('|',$date);
			$rundate = '';
			foreach ($week as $key){
				$rundate .=$weekarr[$key].',';
			}
			echo '每周'.trim($rundate ,',').':'.$time ;
			break ;
		case 'Every-day':
			echo '每天:'.$time ;
			break ;
		case 'One-time':
			echo $time ;
			break ;	
	}
}

/***
 * 发送指令函数
 */
function osa_fSockopen($ip='',$sendstr='',$port='',$lenth='2048',$timeout=''){
	
	$errno="";   	
	$errstr=""; 
	if(empty($ip)){
		exit('IP地址不能为空!');
	} 

	if(empty($sendstr)){
		exit('发送字符串不能为空!');
	}

	$port = empty($port)?OSA_PHPSOCKET_PORT:"$port";
	$timeout = empty($timeout)?OSA_PHPSOCKET_TIMEOUT:"$timeout";
	set_time_limit($timeout);   //设定程式所允许执行的秒数	
	if (! $fp=@fsockopen($ip, $port, $errno, $errstr, $timeout)) {	
		osa_save_fsocket_log("$errstr ($errno)\n");						
		return false;	
	}else{
 		fwrite($fp, $sendstr);
 		while (!feof($fp)) {
			$result.=fgets($fp, $lenth);
 		}
		fclose($fp);
		osa_save_fsocket_log("发送指令：$sendstr");
		return $result;
	}
}

/**
 * 保存日志
 */
function osa_save_fsocket_log($logstr='',$logpath='',$logfilename=''){
	
	if(empty($logstr)){
		exit('日志内容不能为空！');
	}	
	$LogTime=date("D M j G:i:s T Y"); 
	$LogStr=$LogTime." ".$logstr."\n";
	$LogPath= empty($logpath)?OSA_PHPLOG_PATH:"$logpath";	
	$LogFileName= empty($logfilename)?OSA_PHPFSOCKET_LOGFILENAME:$logfilename;		
	if(is_writable($LogPath)){	
		$fw=fopen($LogPath.$LogFileName,'a+');
		fwrite($fw,$LogStr) or die('写入失败！');		
		fclose($fw);
		return true;	
	}else{
		exit(OSA_PHPFSOCKET_LOGFILE.'不存在或者无权限写入！'.$LogPath);	
		return false;		
	}
}

/**
* function osa_system_rum_cmd 发送系统指令函数
* 参数：$remote_ip 远程IP地址  $cmdavg 远程脚本需要的参数
* 返回类型：数组 返回值：脚本的输出结果 
* write by:OWS PHP开发团队
*/
function osa_system_rum_cmd($remote_ip='',$cmdavg='{"runall":""}'){ //执行单个脚本方法

	$ip = defined('OSA_PHPSOCKET_IP') ? OSA_PHPSOCKET_IP : '127.0.0.1' ; //连接本机socket
	empty($remote_ip) ? exit('远程IP地址不能为空！') : $sendip=$remote_ip;
	$sendavg=$cmdavg;
	$sendcmd='SYSTEM_RUN_COMMAND'; //指令
	$sendstr = $sendcmd."!".$sendavg."!".$sendip;
	$revstr='RETURN_SYSTEM_RUN_COMMAND';
	$count=0;//初始化重试次数
	while( $count <= OSA_PHPSOCKET_RECONN_STATUS ){
		if($restr=osa_fSockopen($ip,$sendstr)){
			$onelist=explode('!',$restr); //第一次分解
			if($onelist[0] === "$revstr" && $onelist[2] === "$sendip"){
				$twolist=explode('||',$onelist[1]); //第二次分解,分解结果集
				if(!empty($twolist)){
					for($i=0;$i<count($twolist);$i++){
						$threelist[$i]=explode('=>',$twolist[$i]);					
						$jsonstr=substr($threelist[$i][1],1,strlen($threelist[$i][1]) - 2 );//截取,号
						$key=str_replace('\'','',$threelist[$i][0]);
						$jsonlist[$key]=json_decode($jsonstr,true);
					}	
				}
			}
			break;
		}
		$count++;
	}
	return $jsonlist;
}


/**
 * function filter_cmd 指令过滤
 * 根据配置文件里的进行对部分命令过滤
 */
function filter_cmd($sendcmdstr){
	$cfile = OSA_PUBETC_PATH.'/osa_controlcenter_cmd.ini';
	$configfile=parse_ini_file($cfile,'true');
	 /*  处理alias*/
	$alist = $configfile['alias'];
	foreach($alist as $akey => $avalue){				
		$s = explode(' ',$sendcmdstr);
		if(strpos("_".$sendcmdstr,$akey) &&$s[0] == $akey){
			$sendcmdstr = 	str_replace($akey,$avalue,$sendcmdstr);
		}	
	}
	$dennystr = $configfile['notallowcmdlist']['USERLIST'].','.$configfile['notallowcmdlist']['DEFAULTLIST'];
	$dlist = array_unique(explode(',',$dennystr));
	foreach($dlist as $dvalue){
		$sendcmdstr = strtolower($sendcmdstr);
		$dvalue = strtolower($dvalue);
		if(strpos("_".$sendcmdstr,$dvalue) && strpos("_".$sendcmdstr,'&&') ||$sendcmdstr == $dvalue){
			return 1;
		}
	}
	return 0;
}

/**
 * 立即执行任务指令通讯   BATCH_CMD_OK!1
 */
function osa_sendcmd_plannow($cmd){
	$ip = defined('OSA_PHPSOCKET_IP') ? OSA_PHPSOCKET_IP : '127.0.0.1' ; //连接本机socket
	if(empty($cmd)){
		exit('指令不能为空');
	}
	$res=osa_fSockopen($ip,$cmd);		
	return $res;
}

/**
 * 生成文件路径
 */
function osa_datapath($type ,$file='', $defaultpath=''){
	if(empty($defaultpath)){
		$defaultpath = OSA_PHPDATA_PATH ;
	}
	if(!is_writable($defaultpath)){
		return false; //没有权限，不可写
	}
	$ex_path = $_SESSION['username'].'/'.date("Y/m").'/'.ltrim($file,'/') ;
	switch($type){
		case 'config':
			$dir = rtrim($defaultpath,'/').'/config/'.$ex_path;
			break ;
		case 'script':
			$dir = rtrim($defaultpath,'/').'/script/'.$ex_path;
			break ;
		case 'upload':
			$dir = rtrim($defaultpath,'/').'/script/'.$ex_path;
			break ;
		case 'img':
			$dir = rtrim($defaultpath,'/').'/upload/'.$ex_path;
			break ;
	}
	return $dir ;
}

/**
 * 文件存放
 */
function osa_savafile($dir ,$content){
	osa_mkdirs(dirname($dir));
	$handle = fopen($dir, 'w+');
	fwrite($handle, $content);
	fclose($handle);
	return true;
}

/***
 *  递归创建目录文件
 */
function osa_mkdirs($dir){
	if(!is_dir($dir)) { 
	 	if(!osa_mkdirs(dirname($dir))){ 
	 		 return false;
	  	} 
	 	if(!mkdir($dir,0777)){
	  		return false; 
	  	}
    }
  	return true; 
}


/**
 * function osa_create_shortcut
 * 生成快捷菜单
 */
function osa_create_shortcut($shortcut ,$shortlist){
	if(empty($shortcut)&&$shortcut!=='0'){
		return '';	 
	}
	$shortcut = explode(',',$shortcut);
	$homeleft = '';
	foreach ($shortcut as $key) {
		$homeleft .="<li><a href='".$shortlist[$key]['link']."'>".$shortlist[$key]['name']."</a></li>";
	}
	return $homeleft;
}

/**
 * 加密函数
 */
function osa_passwdhash($password){
	$prefix = OSA_PASSWORD_PREFIX ? OSA_PASSWORD_PREFIX : 'osa_prefix' ;
	$osa_salt = sha1($prefix); 
    $osa_salt = substr($osa_salt, 0, 4); 
    $hash = base64_encode( $osa_salt . sha1($osa_salt . $password , true) ); 
    return $hash; 
}

/**
 * 字符判断是否在字符串中
 */
function osa_checkstr($perstr ,$value){
	$perarr = explode(',',$perstr);
	foreach ($perarr as $key){
		if($key == $value)
			return true ;
	}
	return false;
}

/**
 * output status
 */
function osa_showstatus($type){
	switch($type){
		case 0:
			echo '恢复正常';
			break;
		case 1:
			echo '服务器不可达';
			break;
		case 2:
			echo '服务异常';
			break;
		case 3:
			echo '其他异常';
			break;
	}
}

/**
 * 展示批量操作类型
 */
function osa_showbatch($type){
	switch($type){
		case 'BATCH_DOCUMENT_DISTRIBUTION':
			echo '批量文件分发';
			break;
		case 'BATCH_FILE_CLEANER':
			echo '批量文件清理';
			break;
		case 'BATCH_SERVICE_RESTART':
			echo '批量服务操作';
			break;
		case 'BATCH_COMMAND':
			echo '批量指令执行';
			break;
		case 'BATCH_INSTALLATION':
			echo '批量安装程序';
			break;
		case 'BATCH_DISKSPACE_CHECK':
			echo '批量磁盘空间';
			break;
		case 'BATCH_LOADSTATE_CHECK':
			echo '批量负载状态';
			break;
		case 'BATCH_CONFIG_UPDATE':
			echo '配置文件更新';
			break;
		case 'BATCH_CONFIG_BACKUP':
			echo '配置文件备份';
			break;
		case 'BATCH_DATABASE_BACKUP':
			echo '数据库备份';
			break;
	}
}

/**
 * 解析字符传
 * @param $data
 */
function jsonDecode($data) {  
    if (is_string($data)) {  
        $data=str_replace(  
            array('{','[',']','}',':','null'),  
            array('array(','array(',')',')','=>','NULL')  
            ,$data); 
        @$data=eval("return $data;");   
        return $data;  
    }else{
    	return false;
    }  
}
/**
 * 批量操作结果显示
 * @param $data
 */
function osa_show_bresult($data,$type=''){
	$bresult = jsonDecode($data);
	if($bresult['status'] =='ERROR'){
		$rs = '失败';
	}else{
		$rs = '成功';
	}
	$outmsg = str_replace('=>',':',$bresult['result']);
	$outmsg = str_replace('@@@@',"\t",$outmsg);
	$outmsg = trim($outmsg,'');
	$quoat = '';
	if($type == ''){
		if(strlen($outmsg)>50){
			$quoat = '<span style="color:#3C9D40;matgin-left:2px;">...</span>';
		}
		return '<span class="types_knowledge">['.$rs.']</span><span class="left" title="'.$outmsg.'">'.cu_substr($outmsg,0,50).$quoat.'</span>';
	}else{
		return '<p><label class="label5">状态：</label>'.$rs.'</p><p><label class="label5 left">结果：</label><div style="width:640px;float:left;padding-top:5px;">'.$outmsg.'</div></p>';
	}
}


/**
 * 报警通知显示
 */
function osa_show_alarms($data){
	if(strlen($data)>30){
		$quoat = '<span style="color:#3C9D40;matgin-left:2px;">...</span>';
	}
	if($pos = strpos($data,'文件名为')){
		$tmp = substr($data,$pos);
		$res = substr($tmp ,strpos($tmp,'/'));
		$copy = '<span class="alarm_copy pointer" id="'.$data.'" style="color:rgb(45,150,15);">查看异常</span>';
	}else{
		$copy = '';
	}
	return '<span class="left" title="'.$data.'">'.cu_substr($data,0,25).$quoat.'</span>'.$copy;
}

function osa_show_info($data,$length = 30){
	if(empty($data)){
		return '';
	}
	if(strlen($data)>$length){
		$quoat = '<span style="color:#3C9D40;matgin-left:2px;">...</span>';
	}
	return '<span title="'.$data.'">'.cu_substr($data,0,$length).$quoat.'</span>';
}

/**
 * 截取字符传
 */
function cu_substr($str , $start=0 ,$length=null ,$charset='utf-8'){
	
	$rs['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$rs['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$rs['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	preg_match_all($rs[$charset] ,$str ,$match);
	$result = implode("",array_slice($match[0],$start,$length));
	return $result;
}
/**
 * 反向解析批量操作指令
 */
function osa_reverse_command($type ,$command){
	$cmdinfo = jsonDecode($command);
	$iparr = explode('|',$cmdinfo['iparr']);
	$iphtml = '';
	foreach ($iparr as $key=>$ip){
		$iphtml .= "<span class='left mr10'><label class='ip_tips'>".$ip."</label></span>";
	}
	$html = '' ;
	switch($type){
		case 'BATCH_DOCUMENT_DISTRIBUTION':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>源文件：</label>".$cmdinfo['config_items']['sourcefile']."</p>";
			$html .="<p class='pheight1'><label class='label5'>目标路径：</label>".$cmdinfo['config_items']['targetpath']."</p>";
			if(strpos($cmdinfo['config_items']['advance'],'cut')!==false){
				$advance = "备份并覆盖同名文件";
			}else if(strpos($cmdinfo['config_items']['advance'],'copy')!==false){
				$advance = "逃过同名文件";
			}
			if(strpos($cmdinfo['config_items']['advance'],'document_integrity')!==false){
				$advance .= ',验证文件完整性' ;
			}
			$html .="<p class='pheight1'><label class='label5'>高级选项：</label>".trim($advance,',')."</p>";
			$html .="<p class='pheight1'><label class='label5'>命令或脚本：</label>".$cmdinfo['config_items']['distribution_script']."</p>";
			break;
		case 'BATCH_FILE_CLEANER':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>清理目录：</label>".$cmdinfo['config_items']['cleaner_sourcefile']."</p>";
			$html .="<p class='pheight1'><label class='label5'>目标路径：</label>".$cmdinfo['config_items']['cleaner_targetpath']."</p>";
			$html .="<p class='pheight1'><label class='label5'>高级选项：</label>".$cmdinfo['config_items']['cleaner_advance']."</p>";
			break;
		case 'BATCH_SERVICE_RESTART':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			if(isset($cmdinfo['config_items']['service_name'])){
				$html .="<p class='pheight1'><label class='label5'>服务类型：</label>".$cmdinfo['config_items']['service_name']."</p>";
				$html .="<p class='pheight1'><label class='label5'>操作类型：</label>".$cmdinfo['config_items']['service_type']."</p>";
			}else{
				$html .="<p class='pheight1'><label class='label5'>命令或脚本：</label>".$cmdinfo['config_items']['service_scriptfile']."</p>";
			}
			break;
		case 'BATCH_COMMAND':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>命令或脚本：</label>".$cmdinfo['config_items']['command_scriptfile']."</p>";
			break;
		case 'BATCH_INSTALLATION':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>命令或脚本：</label>".$cmdinfo['config_items']['install_scriptfile']."</p>";
			break;
		case 'BATCH_DISKSPACE_CHECK':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>分区阀值：</label>".$cmdinfo['config_items']['diskspace_threshold'].$cmdinfo['config_items']['unit']."</p>";
			break;
		case 'BATCH_LOADSTATE_CHECK':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$slinx = $cmdinfo['config_items']['topstate_scriptfile'] == 'default'?'使用默认的OSA脚本判断方法返回结果':$cmdinfo['config_items']['topstate_scriptfile'];
			$html .="<p class='pheight1'><label class='label5'>命令或脚本：</label>".$slinx."</p>";
			break;
		case 'BATCH_CONFIG_UPDATE':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>源文件：</label>".$cmdinfo['config_items']['config_update_sourcefile']."</p>";
			$html .="<p class='pheight1'><label class='label5'>目标路径：</label>".$cmdinfo['config_items']['config_update_targetpath']."</p>";
			if(strpos($cmdinfo['config_items']['config_update_advance'],'backup')!==false){
				$advance = "备份原配置文件";
			}
			if(strpos($cmdinfo['config_items']['config_update_advance'],'document_integrity')!==false){
				$advance .= ',验证文件完整性' ;
			}
			$html .="<p class='pheight1'><label class='label5'>高级选项：</label>".trim($advance,',')."</p>";
			$html .="<p class='pheight1'><label class='label5'>命令或脚本：</label>".$cmdinfo['config_items']['config_update_scriptfile']."</p>";
			break;
		case 'BATCH_CONFIG_BACKUP':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>源文件：</label>".$cmdinfo['config_items']['config_backup_sourcefile']."</p>";
			$html .="<p class='pheight1'><label class='label5'>备份目录：</label>".$cmdinfo['config_items']['config_backup_dir']."</p>";
			$rule = $cmdinfo['config_items']['config_backup_rule'] == '1'?'文件名+后缀':'文件名+后缀+时间';
			$html .="<p class='pheight1'><label class='label5'>备份规则：</label>".$rule."</p>";
			break;
		case 'BATCH_DATABASE_BACKUP':
			$html ="<div class='selected'><div style='float:left;'><label class='label5'>服务器：</label></div><div style='width:630px;float:left;'>".$iphtml."</div></div>";
			$html .="<p class='pheight1'><label class='label5'>备份脚本：</label>".$cmdinfo['config_items']['database_backup_scriptfile']."</p>";
			break;
	}
	echo $html;
}

/**
 * 处理ckeditor 图片显示
 */
function mkhtml($fn,$fileurl,$message)
{
	$str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$fn.', \''.$fileurl.'\', \''.$message.'\');</script>';
	exit($str);
}

/**
 * 请求接口
 */

function osa_restaction($method ,$params ,$url){
	$ch = curl_init();
	$timeout = 10;
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	switch($method){
		case 'DELETE': 
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); 
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::DELETE); 
            break; 
        case 'POST': 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
			
            break; 
        case 'GET': 
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); 
            break; 
	}
	$file_contents = curl_exec($ch);//获得返回值
	curl_close($ch);
	return $file_contents;
}


/**
 * 获取操作系统
 */
function getSystem(){  
    $sys = $_SERVER['HTTP_USER_AGENT'];  
    if(stripos($sys, "NT 6.1"))  
       $os = "Windows 7";  
    elseif(stripos($sys, "NT 6.0"))  
       $os = "Windows Vista";  
    elseif(stripos($sys, "NT 5.1"))  
       $os = "Windows XP";  
    elseif(stripos($sys, "NT 5.2"))  
       $os = "Windows Server 2003";  
    elseif(stripos($sys, "NT 5"))  
       $os = "Windows 2000";  
    elseif(stripos($sys, "NT 4.9"))  
       $os = "Windows ME";  
    elseif(stripos($sys, "NT 4"))  
       $os = "Windows NT 4.0";  
    elseif(stripos($sys, "98"))  
       $os = "Windows 98";  
    elseif(stripos($sys, "95"))  
       $os = "Windows 95";  
    elseif(stripos($sys, "Mac"))  
       $os = "Mac";  
    elseif(stripos($sys, "Linux"))  
       $os = "Linux";  
    elseif(stripos($sys, "Unix"))  
       $os = "Unix";  
    elseif(stripos($sys, "FreeBSD"))  
       $os = "FreeBSD";  
    elseif(stripos($sys, "SunOS"))  
       $os = "SunOS";  
    elseif(stripos($sys, "BeOS"))  
       $os = "BeOS";  
    elseif(stripos($sys, "OS/2"))  
       $os = "OS/2";  
    elseif(stripos($sys, "PC"))  
       $os = "Macintosh";  
    elseif(stripos($sys, "AIX"))  
       $os = "AIX";  
    else  
       $os = "未知操作系统";   
    return $os;  
} 

/**
 * 获取服务器软件
 */
function getServerSoft(){
	$soft = $_SERVER['SERVER_SOFTWARE'];
	$soft = explode('/',$soft);
	return $soft[0];	
}


function osa_randomkey(){
	$str = "1,2,3,4,5,6,7,8,9,a,b,c,d,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z";
	$list = explode(",", $str);
	$cmax = count($list) - 1;
	$key = '';
	for ( $i=0; $i < 10; $i++ ){
	    $randnum = mt_rand(0, $cmax);
	    $key .= $list[$randnum]; 
	}
	$key .=sha1(date('YmdHis'));
	return base64_encode($key);
}
?>
