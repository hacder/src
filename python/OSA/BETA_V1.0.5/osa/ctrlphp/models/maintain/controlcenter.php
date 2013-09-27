<?php
/***
 * description : 封装画图过程中的数据获取等方法 
 * Author: ows开源团队
 * Date: 2012-1-4
 */

class controlcenter extends osa_model{
	
	//ows_ControlCenter.php view页面必须参数
	private $ip ='';
	private $sevinfo ='';
	private $systeminfo ='';
	private $updatesinfo ='';
	private $configinfo ='';
	private $otherinfo = '';
	private $shellpath='';
	private $returninfo='';
	private $history='';
	private $cmdinfourl='';
	private $cmddoinfo='';
	
	//中间处理过程全局变量
	public $configfile='';
	private $cmddohistory='';
	private $h_file='';
	private $sendcmdstr='';
	private $dtag='';
	private $pathfile = '';
	private $oldpathfile='';
	private $path='';
	private $oldpath='';
	private $systemtag='';
	private $tag = '';
	private $sendlavg = '';
	private $sendstr = '';
	
	public function __construct($id){
		
		parent::__construct();
		
		//解析配置文件
		$cfile = OSA_PUBETC_PATH.'/osa_controlcenter_cmd.ini';

		$this->configfile=parse_ini_file($cfile,'true');
		
		$this->cmddohistory = OSA_PHPLOG_PATH.'/docmdhistory.log'.$_SESSION[username];
		
		$this->h_file = OSA_PHPLOG_PATH.'/history.log'.$_SESSION[username];
		
		//组合指令全局变量
		$this->pathfile = OSA_PHPLOG_PATH.'/path.log.'.$id.'.'.$_SESSION[username];

		$this->oldpathfile = OSA_PHPLOG_PATH.'/oldpath.log.'.$id.'.'.$_SESSION[username];
		
		$pctent = file_exists($this->pathfile) ? file_get_contents($this->pathfile) : false;
		
		$this->path = $pctent && ! empty($pctent)? $pctent : '/root';
		
		$this->oldpath = file_exists($this->oldpathfile)?file_get_contents($this->oldpathfile):'/root';
			
	
	}
	
	//获取history参数
	function getHistory(){
		
		//$h_file = osa_PHPLOG_PATH.'./history.log'.$_SESSION[username];
		$h = file_exists($this->h_file)?array_reverse(file($this->h_file)):'暂无历史记录！'."\n";
		
		if(is_array($h) &&isset($_POST[cmd])){
			foreach($h as $hv){		
				$history.=$hv;					
			}				
			$history ='时间：'.date('Y-m-d H:i:s').' 执行了命令：'.$_POST[cmd]."\n".$history;
			
		}else if(isset($_POST[cmd])){	
			$history = '时间：'.date('Y-m-d H:i:s').' 执行了命令：'.$_POST[cmd]."\n";
	
		}else if(is_array($h) && !empty($h)){
			$history .="\n";		
			foreach($h as $hv){	
				$history.=$hv;				
			}		
		}else{
			$history = '暂无历史记录！'."\n"; 
		}
		
		return trim($history,'');
	}
	
	//初始话returninfo
	function init_returninfo($ip){
		if(file_exists($this->cmddohistory)){
			$cdh_list = file_get_contents($this->cmddohistory);
		}
		$this->returninfo = isset($cdh_list)?$cdh_list."\n":"\n[root@osa_".$ip."]#";
	}
	
	//初始化sendcmdstr
	function init_sendcmdstr(){
		if(isset($_POST[sevsubmit]) &&$_POST[sev] !='请选择' && $_POST[dotype] !='请选择'){
	
			$sendcmdstr = $_POST[sev].'_'.$_POST[dotype];
		
		}else if(isset($_POST['midsubmit'])){
			
			foreach($_POST as $pk => $pv){
					
				if($pk != 'midsubmit' && $pk != 'CjumpMenu'){
							
					$sendcmdstr = $pk;
							
				}
					
			}
			
		}else if(isset($_POST['cmd'])){
				
			$sendcmdstr .= $_POST['cmd'];
				
		}
				
		//替换还原＆
		$sendcmdstr = str_replace("\"amp;",'&',$sendcmdstr);
					
		/*处理过滤命令*/	
		$dtag = filter_cmd($sendcmdstr);	
		
		$sendcmdstr = ($dtag == 1) ? $sendcmdstr."\n".'This command is denny for admin!' : $sendcmdstr ;
		
		$this->sendcmdstr=$sendcmdstr;
		$this->dtag = $dtag;
	}
	
	public function gettest(){
		return $this->sendcmdstr;
	}
	
	
	// 获取sevinfo信息
	function getSevinfo(){
		
		$sevstr = $this->configfile['sevcmdlist']['USERLIST'];
	
		$sevlist = explode(',',$sevstr);
				
//		$sevinfo .="<option>请选择</option>";
//		
//		foreach($sevlist as $sev_value){			
//			$sevinfo .="<option>$sev_value</option>";
//			
//		}
		return $sevlist;
	}
	
	//获取systeminfo 信息
	function getSysteminfo(){
		
		$systemlist = $this->configfile['systemlist'];
	
//		foreach($systemlist as $sys_key => $sys_value){
//		
//			$systeminfo .= "<input type=\"submit\" name=\"$sys_key\" value=\"$sys_value\" class=\"b-type2\">  ";
//		
//		}
		return $systemlist;
	}
	
	//获取updatesinfo 信息
	function getUpdatesinfo(){
		
		$updateslist = $this->configfile['updateslist'];

//		foreach($updateslist as $updates_key => $updates_value){
//	
//			$updatesinfo .= "<input type=\"submit\" name=\"$updates_key\" value=\"$updates_value\" class=\"b-type2\">  ";
//	
//		}
		return $updateslist;
	}
	
	//获取 otherinfo 信息
	function getOtherinfo(){
		
		$otherlist = $this->configfile['otherlist'];
	
//		foreach($otherlist as $other_key => $other_value){
//		
//			$otherinfo .= "<input type=\"submit\" name=\"$other_key\" value=\"$other_value\" class=\"b-type2\">  ";
//		
//		}
		return $otherlist;
	}
	
	//获取configinfo 信息
	function getConfiginfo(){
	
		$configlist = $this->configfile['configlist'];

//		foreach($configlist as $config_key => $config_value){
//		
//			$configinfo .= "<option value=\"ows.php?menu=serverconfig&lmenu=ControlCenter&name=$config_key&id=".$id."\">$config_key</option>";
//		
//		}
		return $configlist;
	}
	
	//组合指令
	function combinCmd(){
		
		$sendcmdstr=$this->sendcmdstr;
		$path = $this->path;
		$pathfile = $this->pathfile;
		$oldpath = $this->oldpath;
		$oldpathfile = $this->oldpathfile;
		$configfile = $this->configfile;
		
		if(!strpos('_'.$sendcmdstr,'cd') &&$sendcmdstr!=""){ //如果没有包含cd命令,分情况处理	
			
			$findlist =explode('_',$sendcmdstr); 
			$findstr = $findlist[0];
			
			foreach($configfile as $c_key => $c_value)
			{			
				if(is_array($c_value) &&$c_key  != 'alias')
				{					
					foreach($c_value as $cc_k => $cc_v)
					{							
						if(strpos("_".$cc_k,$findstr) || strpos("_".$cc_v,$findstr) &&$cc_k != 'alias')
						{							
							$systemtag = 1;
							break;						
						}							
					}										
				}				
			}
								
			if($systemtag == 1){
				
				$sendstr = 'doall';
				$sendlavg=	$sendcmdstr;				
			}else{								
				$sendstr = 'cd';					
				$sendlavg=$path.'&&'.preg_replace ("/(\s+)/", ' ', $sendcmdstr);													
			}				
			$tag = 0;				
		}else{  //如果包含了CD命令，判断是否包含&&，如果有则判断CD是在&&前面还是后面，分情况处理
		
			if(strpos($sendcmdstr,'&&'))
			{				
				$slist = explode('&&',$sendcmdstr);
				foreach($slist as $sv)
				{					
					if(strpos($sv,'cd'))
					{							
						$p = explode(' ',$sv);
						$path = $p[1];
						$f = fopen($pathfile,'w');
						fwrite($f,$path);
						fclose($f);							
					}					
				}					
				$sendstr = 'cd';
				$sendlavg=$path.'&&'.preg_replace ("/(\s+)/", ' ', $sendcmdstr);
				$tag = 0;
			}else{			
				$f = fopen($oldpathfile,'w');
				fwrite($f,$path);
				fclose($f);				
				$p = explode(' ',$sendcmdstr);
				if(trim($p[1]) ==  '-')
				{														
					$path=$oldpath;					
				}else if(trim($p[1]) ==  '..')
				{							
					$pplist=explode('/',$path);	
								
					if(count(array_filter($pplist)) == 1)
					{								
						$path = '/';																
					}else if($path == '/')
					{
						$path = '/';									
					}else{
								
						$path="";
						for($i=0;$i<(count($pplist) - 1);$i++)
						{								
							$path .= $pplist[$i].'/';							
						}																										
					}																					
				}else if(trim($p[1]) ==  '/')
				{								
					$path = '/';																			
				}else{									
					$path = $p[1];																					
				}				
					
				
				$f = fopen($pathfile,'w');
				fwrite($f,$path);
				fclose($f);
				$tag = 1;
				$rlist = $path; 
			}		
		}
		$this->path = $path;
		$this->systemtag=$systemtag;
		$this->tag = $tag;
		$this->sendlavg = $sendlavg;
		$this->sendstr = $sendstr;
	}
	
	//服务器端执行命令
	function executionCmd($ip){
		
		$tag = $this->tag;
		$dtag = $this->dtag;
		$sendstr = $this->sendstr;
		$sendlavg = $this->sendlavg;
		
		if(!empty($sendstr) && $tag == 0 && $dtag != 1)
		{			
			$avgstr = "{\"$sendstr\":\"$sendlavg\"}";
			//接收来自服务端的数据

			$rlist=osa_system_rum_cmd($ip,$avgstr);

			$rlist=is_array($rlist)?array_keys($rlist):$rlist; 				
		}
		return $rlist;
	}
	
	//获取服务器端cmd执行结果
	function getReturninfo($rlist,$ip){
		
		$systemtag=$this->systemtag;
		$sendcmdstr = $this->sendcmdstr;
		$strcmd=$this->sendstr;		
		$returninfo = $this->returninfo ;
		if(is_array($rlist) && $rlist[0] !="[]"&& !empty($rlist) &&$systemtag != 1)
		{		
			foreach($rlist as $rk)
			{				
				if($rk)
				{					
					$returninfo.= "\n[root@osa_".$ip."]#".$sendcmdstr."\n";
					$r = array(
						'@@@@'=>"\n",
						'['=>'',
						']'=>''
					);
					$rk = strtr($rk,$r);
					$returninfo.=$rk;											
				}			
			}		
		}else if($rlist!=""&&!is_array($rlist) &&$systemtag != 1 && $this->sendcmdstr!='' || $this->tag == 1)
		{						 			
			$returninfo.= "\n[root@osa_".$ip."]#".$sendcmdstr."\nCommand is executed successfully!";			
		}else if($sendcmdstr &&$systemtag != 1)
		{				
			$returninfo.= "\n[root@osa_".$ip."]#".$sendcmdstr."\nCommand is executed fail!";				
		}			
		return $returninfo ;
	}
	
	//各种写进文件操作
	function writeFile($returninfo,$rlist){
		
		$sendcmdstr = $this->sendcmdstr;
		$cmddohistory = $this->cmddohistory;
		$systemtag = $this->systemtag;
		$h_file = $this->h_file;
		if($sendcmdstr)
		{
			 file_put_contents($cmddohistory,$returninfo);
			/*提示信息 */
			if($rlist!=""&&is_array($rlist) &&$rlist[0]!="[]" && $systemtag == 1)
			{		
				$cmddoinfo="<div id=\"userinfo\" style=\"text-align:center;\"><font color=\"#FF0000\">命令：".$sendcmdstr."执行情况:".$rlist[0]."</font></div>";	  
			}				
							
				$info = $_SESSION[username]." 执行命令：".$sendcmdstr."！";
		  
				$tesxt = $info."执行情况:".$rlist[0];
		  
				$type =  "控制中心指令";
		  
				$this->db->savelog($info,$tesxt,$type); 
				
				
				  //处理历史命令				  	  
				if(!file_exists($h_file))
				{					  
					if(is_writeable(OSA_PHPLOG_PATH))
					{						  
						touch($h_file);				  				 
						chmod($h_file,0777);						  						  
					}else{							  
						exit($h_file.'日志目录不可写！');							  
					}					  
				}	  
					  
				$t=date('Y-m-d H:i:s');
				$hstr='时间：'. $t.' 执行了命令：'.$sendcmdstr."\n";		  
				$ff=fopen($h_file,'a+');
				fwrite($ff,$hstr);
				fclose($ff);
				//处理历史命令 end				
		}
		return $cmddoinfo;
	}
	
	//获取shellpath
	function getShellpath(){
		
		return $shellpath = isset($this->path)?$this->path:'/root';
		
	}
	
}