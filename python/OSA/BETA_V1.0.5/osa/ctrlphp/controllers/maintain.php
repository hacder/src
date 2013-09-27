<?php
class maintain extends osa_controller{

	private $model = null;
	private $page = null;
	private $date = array();
	public function __construct(){
		parent::__construct();
		if (! isset ( $_SESSION )) {
			@session_start ();
		}
		$this->model = $this->loadmodel('maintain/manage');
		$this->page = $this->loadmodel('mpage');
		$this->date = array(
			'today'        => date("Y-m-d H:i:s" ,strtotime('today')),
			'yesterday'    => date("Y-m-d H:i:s" ,strtotime('-1 day')),
			'lastweek'     => date("Y-m-d H:i:s" ,strtotime('-7 day')),
			'last2week'    => date("Y-m-d H:i:s" ,strtotime('-15 day')) 
		);
	}
	
	/**
	 * 单机管理 服务器列表页面
	 */
	public function serverlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 130
		if(!osa_checkstr($_SESSION['login_role'],130)){
			header("Location: index.php?c=maintain&a=permiterror&left=serverlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'maintain';//控制头部菜单栏。
		$data['left'] = 'serverlist'; //控制左边栏
		if(isset($_GET['clean'])){
			unset($_SESSION['devname']);
			unset($_SESSION['dev_ip']);
			unset($_SESSION['dev_status']);
		}
		//还需修改
		if(isset($_POST['devname'])){				
			$_SESSION['devname'] = $devname = $_POST['devname'] ;		
		}else{
			$devname = isset($_SESSION['devname'])?$_SESSION['devname']:'';
		}
		if(isset($_POST['ip'])){
			$_SESSION['dev_ip'] = $ip = $_POST['ip'];
		}else{
			$ip = isset($_SESSION['dev_ip'])?$_SESSION['dev_ip']:'';
		}
		if(isset($_REQUEST['status'])){
			$_SESSION['dev_status'] = $status = $_REQUEST['status'];
		}else{
			$status = isset($_SESSION['dev_status'])?$_SESSION['dev_status']:'';
		}
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['devinfo'] = $this->model->selectIpinfo($perpage ,$offset ,$devname ,$ip ,$status);
		$num = $this->model->getNumfromIpinfo($devname ,$ip ,$status);
		$url = 'index.php?c=maintain&a=serverlist';
		//$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($url,$num ,$perpage ,$offset);
		$this->loadview('maintain/serverlist',$data);
	}
	
	/**
	 * 单机管理 服务器详细信息页面
	 */
	public function devinfo(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
	    //权限判断 131
		if(!osa_checkstr($_SESSION['login_role'],131)){
			header("Location: index.php?c=maintain&a=permiterror&left=serverlist", TRUE, 302);
		}else{
			if(!isset($_GET['id'])){
				header("Location: index.php?c=maintain&a=serverlist", TRUE, 302);
			}
			$mon_detail = $this->model->getDevinfoByid($_GET['id']);
			$sendip=$mon_detail[0]['oIp'];
	
			$rlist=osa_system_rum_cmd($sendip);
			if(! is_array($rlist) || empty($rlist)||$rlist == ''){
				$msg = "获取监控信息失败,请重试一次或者检查服务端程序";
				$data['msg'] = $msg;
				//echo $msg;
				//return ;
			}else{
				$data['detail_list'] = $detail_list = $this->model->getDetailinfo($rlist);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'serverlist'; //控制左边栏
			$data['mon_detail'] = $mon_detail;
			$this->loadview('maintain/devinfo',$data);
		}
	}
	
	/**
	 * controlcenter 页面
	 */
	public function controlcenter(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 130
		if(!osa_checkstr($_SESSION['login_role'],132)){
			header("Location: index.php?c=maintain&a=permiterror&left=serverlist", TRUE, 302);
		}else{
			if(!isset($_GET['id'])){
				header("Location: index.php?c=maintain&a=serverlist", TRUE, 302);
			}
			$mon_detail = $this->model->getDevinfoByid($_GET['id']);
			$data['ip']=$ip=$mon_detail[0]['oIp'];
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'serverlist'; //控制左边栏
			$controlcenter = $this->loadmodel('maintain/controlcenter',$_GET['id']);
			$data['history'] = $controlcenter->getHistory();
			$controlcenter->init_returninfo($ip);
			$controlcenter->init_sendcmdstr();
			if(isset($_POST) &&$_POST !=""){
				$controlcenter->combinCmd();
				$rlist = $controlcenter->executionCmd($ip);
			}
			$data['returninfo'] = $returninfo = $controlcenter->getReturninfo($rlist,$ip);
			$data['cmddoinfo'] = $controlcenter->writeFile($returninfo,$rlist);
			$data['shellpath'] = $controlcenter->getShellpath();
	
			$data['sevinfo'] = $controlcenter->getSevinfo();
			$data['systeminfo'] = $controlcenter->getSysteminfo();
			$data['updatesinfo'] = $controlcenter->getUpdatesinfo();
			$data['configinfo'] = $controlcenter->getConfiginfo();
			$data['otherinfo'] = $controlcenter->getOtherinfo();
			$data['url'] = 'index.php?c=maintain&a=controlcenter&id='.$_GET['id'];
			$data['id'] = $_GET['id'];
			$this->loadview('maintain/controlcenter',$data);
		}
	}
	
	public function getCmdinfo(){
		if(isset($_GET['cmdstr'])){
			$cmdstr="adduser,addgroup,awk,bash,cat,cd,chmod,chown,cp,chattr,chkconfig,chpasswd,chroot,crond,crontab,date,df,dd,dir,du,dirname,find,ftp,grep,groupadd,groupdel,gzip,history,head,host,init,ifconfig,iptables,top,touch,ls,rm,mv,scp,ssh,jbos,kill,pkill,sh,lsattr,ln,ldconfig,lsof,ntpd,nmap,netstat,snmpd,service,mkdir,make,mii-tool,mount,md5sum,reboot,rename,shutdown,zip,unzip,tar,unrar,mysql,mysqldump";
			$cmdarr=explode(',',$cmdstr);
			foreach($cmdarr as $value){
				if(strpos("_".$value,$_GET['cmdstr'])){
					$newvalue=str_replace($_GET['cmdstr'],"<font color='red'>".$_GET['cmdstr']."</font>",$value);
					$str="<a onclick=Change('".$value."'); herf='#'>".$newvalue."</a>  ".$str;
				}
			}
			if($str){
				echo $str;
			}else{
				echo $_GET['cmdstr'];
			}
		}
	}
	/**
	 * xiazai history
	 */
	public function getHistoryText(){
	
		$file_dir = OSA_PHPLOG_PATH;	
		$file_name = 'history.log'.$_SESSION[username]; 
		// 输入文件标签
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".filesize($file_dir . $file_name));
		Header("Content-Disposition: attachment; filename=" . $file_name);
		readfile($file_dir . $file_name);
	}
	
	/**
	 * serverconfig 页面
	 */
	public function serverconfig(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=maintain&a=serverlist", TRUE, 302);
		}
		$mon_detail = $this->model->getDevinfoByid($_GET['id']);
		$data['ip']=$ip=$mon_detail[0]['oIp'];
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'serverlist'; //控制左边栏
		if(isset($_GET['name'])){	
			$cname = $_GET['name'];			
		}
		$data['ctitle'] = $ctitle = $ip.'_'.$cname;
		$sendstr = 'getconfigfile';
		$avgstr = "{\"$sendstr\":\"$cname\"}";
		$r_list = @array_keys(osa_system_rum_cmd($ip,$avgstr));
		if(empty($r_list)){
			$data['msg']=$msg = "配置文件获取失败";		
		}else{
			$flist = explode('|',$r_list[0]);
			$data['fname'] = $fname = str_replace('[','',$flist[0]);
			$data['ctext'] = $ctext = file_get_contents($fname);
		}
		$data['id'] = $_GET['id'];
		$this->loadview('maintain/serverconfig',$data);
	}
	
	/**
	 * saveconfigfile 交互
	 */
	public function saveconfig(){
		$mon_detail = $this->model->getDevinfoByid($_GET['id']);
		$data['ip']=$ip=$mon_detail[0]['oIp'];
		if(isset($_POST['ctext']) && isset($_POST['cfilename'])){
		
			$sendstr = 'saveconfigfile';		
			$cname = $_POST['cfilename'];	
			$avgstr = "{\"$sendstr\":\"$cname\"}";	
			file_put_contents($cname,$_POST['ctext']);
			$r_list = osa_system_rum_cmd($ip,$avgstr);	
			if(is_array($r_list)){
				$r_list=array_keys($r_list);	
			}else{						
				$info = "配置保存失败！";
//				$detail = "接下来建议您进行的操作：<a href=\"ows.php?menu=ControlCenter&id=".$_GET['id']."&lmenu=devinfo\" target=\"_self\">返回操作控制中心</a>或者<a href=\"#\" onClick=\"history.go(-1)\">重试一次</a>";
//				$type="修改配置文件";
//				$content="修改配置文件失败。IP：".$ip.",配置文件类型是：".$_GET['name'];
//				$db->savelog($info,$content,$type);	
				//return info_output($info,$detail); 
				exit($info);									
			}						
			if(!empty($r_list[0]) &&$r_list[0] != "[]" && $r_list[0] != ""){				
				$info = "配置保存成功！";
//				$detail = "接下来建议您进行的操作：<a href=\"ows.php?menu=ControlCenter&id=".$_GET['id']."&lmenu=devinfo\" target=\"_self\">返回操作控制中心</a>";
//				$type="修改配置文件";
//				$content="成功修改一个配置文件。IP：".$ip.",配置文件类型是：".$_GET['name'];
//				$db->savelog($info,$content,$type);	
				//return info_output($info,$detail);
				exit($info); 		
			}	
		}
	}
	
	/**
	 * cmdshell 页面
	 */
	public function cmdshell(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=maintain&a=serverlist", TRUE, 302);
		}
		$mon_detail = $this->model->getDevinfoByid($_GET['id']);
		$data['ip']=$ip=$mon_detail[0]['oIp'];
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'serverlist'; //控制左边栏
		$controlcenter = $this->loadmodel('maintain/controlcenter',$_GET['id']);
		$data['history'] = $controlcenter->getHistory();
		$controlcenter->init_returninfo($ip);
		$controlcenter->init_sendcmdstr();
		if(isset($_POST) &&$_POST !=""){
		
			$controlcenter->combinCmd();
			$rlist = $controlcenter->executionCmd($ip);
		}
		$data['returninfo'] = $returninfo = $controlcenter->getReturninfo($rlist,$ip);
		$data['cmddoinfo'] = $controlcenter->writeFile($returninfo,$rlist);
		$data['shellpath'] = $controlcenter->getShellpath();
		$data['id'] = $_GET['id'];
		$this->loadview('maintain/cmdshell',$data);
	}
	
	/**
	 * cmdshellajax 交互
	 */
	public function cmdshellajax(){
		$mon_detail = $this->model->getDevinfoByid($_GET['id']);
		$data['ip']=$ip=$mon_detail[0]['oIp'];
		$controlcenter = $this->loadmodel('maintain/controlcenter',$_GET['id']);
		$controlcenter->getHistory();
		$controlcenter->init_returninfo($ip);
		$controlcenter->init_sendcmdstr();
		if(isset($_POST) &&$_POST !=""){
		
			$controlcenter->combinCmd();
			$rlist = $controlcenter->executionCmd($ip);
		}
		 $returninfo = $controlcenter->getReturninfo($rlist,$ip);
		 $controlcenter->writeFile($returninfo,$rlist);
		 $shellpath = $controlcenter->getShellpath();
		 $arr = array(
		 	'shellpath'=>$shellpath,
		 	'returninfo'=>$returninfo
		 );
		 exit(json_encode($arr));
		
	}
	
	/********************************************脚本库**********************************************/
	/**
	 * online script views
	 */
	public function onlinescript(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 20
		if(!osa_checkstr($_SESSION['login_role'],20)){
			header("Location: index.php?c=maintain&a=permiterror&left=onlinescript", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['scriptsearch']);
			unset($_SESSION['scriptstart']);
			unset($_SESSION['scriptend']);
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['scriptsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['scriptsearch'])?$_SESSION['scriptsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['scriptstart'] = $_POST['starttime'];
			$_SESSION['scriptend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['scriptstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['scriptstart'];
			$endtimes = $_SESSION['scriptend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'scriptlist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['scriptinfo'] = $this->model->selectScriptInfo($perpage ,$offset ,$search ,$starttime ,$endtime );
		$num = $this->model->getNumfromScript($search ,$starttime ,$endtime );
		$url = 'index.php?c=maintain&a=onlinescript';
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/onlinescript' , $data);
	}
	
	/**
	 * add script views
	 */
	public function addscript(){
		if(!isset($_POST['oScriptName'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 21
			if(!osa_checkstr($_SESSION['login_role'],21)){
				header("Location: index.php?c=maintain&a=permiterror&left=onlinescript", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'scriptlist'; //控制左边栏
			$this->loadview('maintain/addscript' , $data);
		}else{
			$savapath = osa_datapath('script',$_POST['oScriptPath']);
			if(!empty($savapath)){
				$scriptcontent = htmlspecialchars_decode(stripslashes($_POST['oScriptContent']));
				osa_savafile($savapath,$scriptcontent);				
				$scriptinfo = array(
					'oScriptName'=>$_POST['oScriptName'],
					'oScriptLabel'=>$_POST['oScriptLabel'],
					'oScriptPath' =>$savapath,
					'oIsShare'=>0,
					'oCreateTime'=>date('Y-m-d H:i:s',time()),
					'oUpdateTime'=>date('Y-m-d H:i:s',time())								
				);
				$rs = $this->model->insertScript($scriptinfo);
				if($rs){
					$title = "添加脚本";
					$info = $_SESSION['username']."添加脚本成功,脚本名称：".$_POST['oScriptName']."时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'success';
					return ;
				}else{
					$title = "添加脚本";
					$info = $_SESSION['username']."添加脚本失败,脚本名称：".$_POST['oScriptName'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'failure';
					return ;
				}
			}else{
				$title = "添加脚本";
				$info = $_SESSION['username']."添加脚本失败,脚本名称：".$_POST['oScriptName'].",可能原因：文件不可写、权限问题,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'writable_error';
				return ;
			}
		}
	}
	
	/**
	 * edit script views
	 */
	public function	editscript(){
		if(!isset($_POST['oScriptName'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 22
			if(!osa_checkstr($_SESSION['login_role'],22)){
				header("Location: index.php?c=maintain&a=permiterror&left=onlinescript", TRUE, 302);
			}
			if(!isset($_GET['id'])){
				header("Location: index.php?c=maintain&a=onlinescript", TRUE, 302);
			}
			$id = $_GET['id'];
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'scriptlist'; //控制左边栏
			$data['scriptinfo'] = $scriptinfo = $this->model->getScriptByid($id);
			$data['scriptcontent'] = $scriptcontent = $this->model->getFileContent($scriptinfo[0]['oScriptPath']);
			$this->loadview('maintain/editscript' , $data);
		}else{
			$id = $_GET['id'];
			$scriptinfo = array(
					'oScriptName'=>$_POST['oScriptName'],
					'oScriptLabel'=>$_POST['oScriptLabel'],
					'oUpdateTime'=>date('Y-m-d H:i:s',time())								
				);
			$rs = $this->model->updateScript($id,$scriptinfo);
			$scriptcontent = htmlspecialchars_decode(stripslashes($_POST['oScriptContent']));
			osa_savafile($_POST['oScriptPath'],$scriptcontent);
			$title = "编辑脚本";
			$info = $_SESSION['username']."编辑脚本成功,脚本名称：".$_POST['oScriptName'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;			
		}
		
	}
	
	/**
	 * copy script views
	 */
	public function copyscript(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 24
		if(!osa_checkstr($_SESSION['login_role'],24)){
			header("Location: index.php?c=maintain&a=permiterror&left=onlinescript", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=maintain&a=onlinescript", TRUE, 302);
		}
		$id = $_GET['id'];
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'scriptlist'; //控制左边栏
		$data['scriptinfo'] = $scriptinfo = $this->model->getScriptByid($id);
		$data['scriptcontent'] = $scriptcontent = $this->model->getFileContent($scriptinfo[0]['oScriptPath']);
		$this->loadview('maintain/copyscript' , $data);
	}
	
	/**
	 * checkscriptname
	 */		
	public function checkscriptname(){
		$scriptname = $_POST['scriptname'];
		$rs = $this->model->getInfoByscriptname($scriptname);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * delscript views
	 */
	public function delscript(){
		//权限判断 23
		if(!osa_checkstr($_SESSION['login_role'],23)){
			//header("Location: index.php?c=maintain&a=permiterror&left=loglist", TRUE, 302);
			echo 'no_permissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key) {
			$this->model->delScript($key);
		}
		$title = "删除脚本";
		$info = $_SESSION['username']."删除脚本成功,共删除脚本".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 分享script
	 */
	public function sharescript(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->setScriptShare($_GET['id']);
		$title = "分享脚本";
		$info = $_SESSION['username']."分享脚本成功,脚本id：".$_GET['id'].",时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		header("Location: index.php?c=maintain&a=onlinescript", TRUE, 302);
	}
	
	/****************************************操作记录***********************************************/
	/**
	 * logslist views
	 */
	public function loglist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 30
		if(!osa_checkstr($_SESSION['login_role'],30)){
			header("Location: index.php?c=maintain&a=permiterror&left=loglist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['logsearch']);
			unset($_SESSION['logstart']);
			unset($_SESSION['logend']);
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['logsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['logsearch'])?$_SESSION['logsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['logstart'] = $_POST['starttime'];
			$_SESSION['logend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['logstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['logstart'];
			$endtimes = $_SESSION['logend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'loglist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['loginfo'] = $this->model->selectLogInfo($perpage ,$offset ,$search ,$starttime ,$endtime );
		$num = $this->model->getNumfromLog($search ,$starttime ,$endtime );
		$url = 'index.php?c=maintain&a=loglist';
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/loglist' , $data);
	}
	
	/**
	 * delscript views
	 */
	public function delsyslog(){
		//权限判断 33
		if(!osa_checkstr($_SESSION['login_role'],33)){
			//header("Location: index.php?c=maintain&a=permiterror&left=loglist", TRUE, 302);
			echo 'no_permissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key) {
			$this->model->delSyslog($key);
		}
		$title = "删除系统日志";
		$info = $_SESSION['username']."删除系统日志成功,共删除日志".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * add log views
	 */
	public function addlog(){
		if(!isset($_POST['oLogTitle'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 31
			if(!osa_checkstr($_SESSION['login_role'],31)){
				header("Location: index.php?c=maintain&a=permiterror&left=loglist", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'loglist'; //控制左边栏
			$data['logtype'] = $this->model->selectLogType();
			$this->loadview('maintain/addlog' , $data);
		}else{
			$loginfo = array(
					'oTypeid' => $_POST['oTypeid'],
					'oUserName' =>$_SESSION['username'],
					'oLogTitle'=>$_POST['oLogTitle'],
					'oLogText' => $_POST['oLogText'],
					'oLogAddTime'=>date('Y-m-d H:i:s',time()),
					'oIsShare'=>0,
					'oLogLabel'=>$_POST['oLogLabel'],							
				);
			$rs = $this->model->insertLogInfo($loginfo);
			if($rs){
				$title = "添加系统日志";
				$info = $_SESSION['username']."添加系统日志成功,日志标题：".$_POST['oLogTitle'].",时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加系统日志";
				$info = $_SESSION['username']."添加系统日志失败,日志标题：".$_POST['oLogTitle'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/***
	 * add logtype ajax
	 */
	public function addlogtype(){
		if(!isset($_POST['oTypeText'])){
			echo 'failure';
			return ;
		}
		$name = $_POST['oTypeText'];
		$insertid = $this->model->insertLogtype($name );
		if($insertid){
			$title = "添加日志类型";
			$info = $_SESSION['username']."添加日志类型成功,日志类型名称：".$_POST['oTypeText'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo $insertid;
			return ;
		}else{
			$title = "添加日志类型";
			$info = $_SESSION['username']."添加日志类型失败,日志类型名称：".$_POST['oTypeText'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;
		}
	}
	
	/**
	 * edit log views
	 */
	public function editlog(){
		if(!isset($_POST['oLogTitle'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 32
			if(!osa_checkstr($_SESSION['login_role'],32)){
				header("Location: index.php?c=maintain&a=permiterror&left=loglist", TRUE, 302);
			}
			if(!isset($_GET['id'])){
				header("Location: index.php?c=maintain&a=loglist", TRUE, 302);
			}
			$id = $_GET['id'];
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'loglist'; //控制左边栏
			$data['loginfo'] = $loginfo = $this->model->getLoginfoByid($id);
			$data['logtype'] = $this->model->selectLogType();
			$this->loadview('maintain/editlog' , $data);
		}else{
			$id = $_GET['id'];
			$loginfo = array(
					'oTypeid' => $_POST['oTypeid'],
					'oUserName' =>$_SESSION['username'],
					'oLogTitle'=>$_POST['oLogTitle'],
					'oLogText' => $_POST['oLogText'],
					'oLogLabel'=>$_POST['oLogLabel'],							
				);
			$rs = $this->model->updateLoginfo($id,$loginfo);
			$title = "编辑系统日志";
			$info = $_SESSION['username']."编辑系统日志成功,日志标题：".$_POST['oLogTitle'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;			
		}
	}
	
	/**
	 * 分享日志
	 */
	public function sharelog(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->setLogShare($_GET['id']);
		$title = "分享系统日志";
		$info = $_SESSION['username']."分享系统日志成功,日志id：".$_GET['id'].",时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		header("Location: index.php?c=maintain&a=loglist", TRUE, 302);
	}
	
	/***********************************知识库**********************************************/
	/**
	 * knowlist views
	 */
	public function knowlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 50
		if(!osa_checkstr($_SESSION['login_role'],50)){
			header("Location: index.php?c=maintain&a=permiterror&left=knowlist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['knowsearch']);
			unset($_SESSION['knowstart']);
			unset($_SESSION['knowend']);
		}
		$be_url = '';
		if(isset($_GET['belong'])){
			$bid = $_GET['belong'];
			$be_url = "&belong=".$_GET['belong'];
			$data['tabli'] = '#tab_li'.$_GET['belong'];
		}else{
			$data['tabli'] = '#tab_li0';
			$bid = 0;
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['knowsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['knowsearch'])?$_SESSION['knowsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['knowstart']  = $_POST['starttime'];
			$_SESSION['knowend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['knowstart'])){
			$starttime = $_SESSION['knowstart'];
			$endtimes = $_SESSION['knowend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'knowlist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['knowinfo'] = $this->model->selectRepository($perpage ,$offset ,$search ,$starttime ,$endtime ,$bid);
		$num = $this->model->getNumfromRepository($search ,$starttime ,$endtime ,$bid );
		$url = 'index.php?c=maintain&a=knowlist'.$be_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['belong'] = $bid ;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/knowlist' , $data);
	}
	
	/**
	 * add know views
	 */
	public function addknow(){
		if(!isset($_POST['oRepositoryTitle'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 51
			if(!osa_checkstr($_SESSION['login_role'],51)){
				header("Location: index.php?c=maintain&a=permiterror&left=knowlist", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'knowlist'; //控制左边栏
			$data['knowtype'] = $this->model->selectKnowType();
			$this->loadview('maintain/addknow' , $data);
		}else{
			$knowinfo = array(
					'oTypeid' => $_POST['oTypeid'],
					'oUserName' =>$_SESSION['username'],
					'oRepositoryTitle'=>$_POST['oRepositoryTitle'],
					'oRepositoryText' => $_POST['oRepositoryText'],
					'oCreateTime'=>date('Y-m-d H:i:s',time()),
					'oIsShare'=>0,
					'oRepositoryLabel'=>$_POST['oRepositoryLabel'],						
				);
			$rs = $this->model->insertKnowInfo($knowinfo);
			if($rs){
				$title = "添加运维知识";
				$info = $_SESSION['username']."添加运维知识成功,知识标题：".$_POST['oRepositoryTitle'].",时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加运维知识";
				$info = $_SESSION['username']."添加运维知识失败,知识标题：".$_POST['oRepositoryTitle'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/**
	 * 
	 */
	public function editknow(){
		if(!isset($_POST['oRepositoryTitle'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 52
			if(!osa_checkstr($_SESSION['login_role'],52)){
				header("Location: index.php?c=maintain&a=permiterror&left=knowlist", TRUE, 302);
			}
			if(!isset($_GET['id'])){
				header("Location: index.php?c=maintain&a=knowlist", TRUE, 302);
			}
			$id = $_GET['id'];
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'knowlist'; //控制左边栏
			$data['knowinfo'] = $this->model->getKnowinfoByid($id);
			$data['knowtype'] = $this->model->selectKnowType();
			$this->loadview('maintain/editknow' , $data);
		}else{
			$id = $_GET['id'];
			$knowinfo = array(
					'oTypeid' => $_POST['oTypeid'],
					'oUserName' =>$_SESSION['username'],
					'oRepositoryTitle'=>$_POST['oRepositoryTitle'],
					'oRepositoryText' => $_POST['oRepositoryText'],
					'oRepositoryLabel'=>$_POST['oRepositoryLabel']					
				);
			$rs = $this->model->updateKnowinfo($id,$knowinfo);
			$title = "编辑运维知识";
			$info = $_SESSION['username']."编辑运维知识成功,知识标题：".$_POST['oRepositoryTitle'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;			
		}
	}
	
	/**
	 * 分享知识
	 */
	public function shareknow(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->setKnowShare($_GET['id']);
		$title = "分享运维知识";
		$info = $_SESSION['username']."分享运维知识成功,知识id：".$_GET['id'].",时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		header("Location: index.php?c=maintain&a=knowlist", TRUE, 302);
	}
	
	/**
	 * add knowtype ajax
	 */
	public function addknowtype(){
		if(!isset($_POST['oTypeText'])){
			echo 'failure';
			return ;
		}
		$name = $_POST['oTypeText'];
		$insertid = $this->model->insertKnowtype($name );
		if($insertid){
			$title = "添加知识类型";
			$info = $_SESSION['username']."添加知识类型成功,类型名称：".$_POST['oTypeText'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo $insertid;
			return ;
		}else{
			$title = "添加知识类型";
			$info = $_SESSION['username']."添加知识类型失败,类型名称：".$_POST['oTypeText'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;
		}
	}
	
	/**
	 * del knowinfo ajax
	 */
	public function delknow(){
		//权限判断 53
		if(!osa_checkstr($_SESSION['login_role'],53)){
			//header("Location: index.php?c=maintain&a=permiterror&left=configfilelist", TRUE, 302);
			echo 'no_permissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key) {
			$this->model->delKnowinfo($key);
		}
		$title = "删除运维知识";
		$info = $_SESSION['username']."删除运维知识成功,共删除知识：".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/************************************配置文件**************************************************/
	/*
	 *configfile list views 
	 */
	public function configfilelist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 60
		if(!osa_checkstr($_SESSION['login_role'],60)){
			header("Location: index.php?c=maintain&a=permiterror&left=configfilelist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['configsearch']);
			unset($_SESSION['configstart']);
			unset($_SESSION['configend']);
		}
		$be_url = '';
		if(isset($_GET['belong'])){
			$bid = $_GET['belong'];
			$be_url = "&belong=".$_GET['belong'];
			$data['tabli'] = '#tab_li'.$_GET['belong'];
		}else{
			$data['tabli'] = '#tab_li0';
			$bid = 0;
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['configsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['configsearch'])?$_SESSION['configsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['configstart'] = $_POST['starttime'];
			$_SESSION['configend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['configstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['configstart'];
			$endtimes = $_SESSION['configend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'configfilelist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['configinfo'] = $this->model->selectConfigfile($perpage ,$offset ,$search ,$starttime ,$endtime ,$bid);
		$num = $this->model->getNumfromConfigfile($search ,$starttime ,$endtime ,$bid );
		$url = 'index.php?c=maintain&a=configfilelist'.$be_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/configfilelist' , $data);
	}
	
	/**
	 * add configfile views
	 */
	public function addconfigfile(){
		if(!isset($_POST['oFileName'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 61
			if(!osa_checkstr($_SESSION['login_role'],61)){
				header("Location: index.php?c=maintain&a=permiterror&left=configfilelist", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'configfilelist'; //控制左边栏
			$data['filetype'] = $this->model->selectFileType();
			$this->loadview('maintain/addconfigfile' , $data);
		}else{
			$savapath = osa_datapath('config',$_POST['oSavePath']);
			
			if(!empty($savapath)){
				$configcontent = htmlspecialchars_decode(stripslashes($_POST['oConfigContent']));
				osa_savafile($savapath,$configcontent);
				$configfileinfo = array(
					'oFileName' => $_POST['oFileName'],
					'oFileLabel' =>$_POST['oFileLabel'],
					'oFileSign'=>$_POST['oFileSign'],
					'oSavePath' => $savapath,
					'oCreateTime'=>date('Y-m-d H:i:s',time()),
					'oIsShare'=>0,
					'oTypeid'=>$_POST['oTypeid'],	
					'oIsBelong' => 0						
				);
				$rs = $this->model->insertConfigfile($configfileinfo);
				if($rs){
					$title = "添加配置文件";
					$info = $_SESSION['username']."添加配置文件成功,文件名称：".$_POST['oFileName'].",时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'success';
					return ;
				}else{
					$title = "添加配置文件";
					$info = $_SESSION['username']."添加配置文件失败,文件名称：".$_POST['oFileName'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'failure';
					return ;
				}
			}else{
				$title = "添加配置文件";
				$info = $_SESSION['username']."添加配置文件失败,文件名称：".$_POST['oFileName'].",可能原因：文件不可写、权限问题,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'writalbe_error_sdf';
			}
			
		}
	}
	
	/**
	 * 
	 */
	public function checkfilename(){
		$filename = $_POST['filename'];
		$rs = $this->model->getInfoByfilename($filename);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * edit configfile view
	 */
	public function editconfigfile(){
		if(!isset($_POST['oFileName'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 62
			if(!osa_checkstr($_SESSION['login_role'],62)){
				header("Location: index.php?c=maintain&a=permiterror&left=configfilelist", TRUE, 302);
			}
			if(!isset($_GET['id'])){
				header("Location: index.php?c=maintain&a=configfilelist", TRUE, 302);
			}
			$id = $_GET['id'];
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'configfilelist'; //控制左边栏
			$data['configinfo'] = $configinfo = $this->model->getConfigfileByid($id);
			$data['filetype'] = $this->model->selectFileType();
			$data['configfilecontent'] = $this->model->getFileContent($configinfo[0]['oSavePath']);
			$this->loadview('maintain/editconfigfile' , $data);
		}else{
			$id = $_GET['id'];
			$configfileinfo = array(
					'oFileName' => $_POST['oFileName'],
					'oFileLabel' =>$_POST['oFileLabel'],
					'oFileSign'=>$_POST['oFileSign'],
					'oSavePath' => $_POST['oSavePath'],
					'oTypeid'=>$_POST['oTypeid']						
				);
			$rs = $this->model->updateConfigfile($id,$configfileinfo);
			$configcontent = htmlspecialchars_decode(stripslashes($_POST['oConfigContent']));
			//$this->model->saveFile($_POST['oSavePath'],$configcontent,'configlib');
			$result = osa_savafile($_POST['oSavePath'],$configcontent);
			$title = "编辑配置文件";
			$info = $_SESSION['username']."编辑配置文件成功,文件名称：".$_POST['oFileName'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;			
		}
	}
	
	/**
	 * 分享文件
	 */
	public function sharefile(){

		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->setFileShare($_GET['id']);
		$title = "分享配置文件";
		$info = $_SESSION['username']."分享配置文件成功,文件id：".$_GET['id'].",时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		header("Location: index.php?c=maintain&a=configfilelist", TRUE, 302);
	}
	
	/**
	 * add knowtype ajax
	 */
	public function addfiletype(){
		if(!isset($_POST['oTypeText'])){
			echo 'failure';
			return ;
		}
		$name = $_POST['oTypeText'];
		$insertid = $this->model->insertFiletype($name );
		if($insertid){
			$title = "添加文件类型";
			$info = $_SESSION['username']."添加文件类型成功,类型名称：".$_POST['oTypeText'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo $insertid;
			return ;
		}else{
			$title = "添加文件类型";
			$info = $_SESSION['username']."添加文件类型失败,类型名称：".$_POST['oTypeText'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;
		}
	}
	
	/**
	 * del knowinfo ajax
	 */
	public function delconfigfile(){
		//权限判断 63
		if(!osa_checkstr($_SESSION['login_role'],63)){
			//header("Location: index.php?c=maintain&a=permiterror&left=databackuplist", TRUE, 302);
			echo 'no_perrmissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key) {
			$this->model->delConfigfile($key);
		}
		$title = "删除配置文件";
		$info = $_SESSION['username']."删除配置文件成功,共删除文件：".count($arr).",时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/******************************************************数据库备份**********************************************/
	
	/**
	 * 
	 */
	public function databackuplist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 40
		if(!osa_checkstr($_SESSION['login_role'],40)){
			header("Location: index.php?c=maintain&a=permiterror&left=databackuplist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['datasearch']);
			unset($_SESSION['datastart']);
			unset($_SESSION['dataend']);
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['datasearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['datasearch'])?$_SESSION['datasearch']:'';
		}
		if(isset($_POST['starttime'])){//说明是自定义搜索中时间不为空
			$_SESSION['datastart'] = $_POST['starttime'];
			$_SESSION['dataend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['datastart'])){ 
			$starttime = $_SESSION['datastart'];
			$endtimes = $_SESSION['dataend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'databackuplist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['datainfo'] = $this->model->selectDataBackup($perpage ,$offset ,$search ,$starttime ,$endtime );
		$num = $this->model->getNumfromDataBackup($search ,$starttime ,$endtime );
		$url = 'index.php?c=maintain&a=databackuplist';
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/databackuplist' , $data);
	}
	
	/**
	 *  数据库备份
	 */
	public function adddatabackup(){
		if(!isset($_POST['oBackupName'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 41
			if(!osa_checkstr($_SESSION['login_role'],41)){
				header("Location: index.php?c=maintain&a=permiterror&left=databackuplist", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'databackuplist'; //控制左边栏
			$data['servertype'] = $this->model->selectServerType();
			$data['servergroup'] = $this->model->selectServerGroup();
			$this->loadview('maintain/adddatabackup' , $data);
		}else{	
			$plantime = $_POST['plantime'];
			$planinfo = array(
				'oRunCycle'=>$plantime[0],
				'oRunDate'=>$plantime[1],
				'oRuntime'=>$plantime[2],
				'oCmdType'=>'BATCH_DATABASE_BACKUP',
				'oCreateTime'=>date('Y-m-d H:i:s',time()),
				'oStatus' => '未开始'
			);
			$rsid = $this->model->insertTaskPlan($planinfo);
			$iparr = $_POST['oBackupIp'];$oBackupName = $_POST['oBackupName'];
			$oScriptFile = $_POST['oScriptFile'];
			$oCombinCmd = "{'command':'BATCH_DATABASE_BACKUP','iparr':'$iparr','config_items':{'database_backup_name':'$oBackupName','database_backup_scriptfile':'$oScriptFile'},'id':'$rsid'}";
			$backupinfo = array(
				'oBackupName' =>$_POST['oBackupName'],
				'oTaskplanid' => $rsid, 
				'oBackupIp' =>$_POST['oBackupIp'],
				'oCreateTime'=>date('Y-m-d H:i:s',time()),	
				'oScriptFile'=>$_POST['oScriptFile'],
				'oCombinCmd'=> $oCombinCmd
			);
			$rs = $this->model->insertDataBackup($backupinfo);
			if($rs){
				$title = "添加数据库备份";
				$info = $_SESSION['username']."添加数据库备份成功,备份名称：".$_POST['oBackupName'].",时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加数据库备份";
				$info = $_SESSION['username']."添加数据库备份失败,备份名称：".$_POST['oBackupName'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}
		}
		
	}
	
	/**
	 * search ip server ajax
	 */
	public function searchIp(){
		$typeid = $groupid = $keyword = '';
		if(isset($_POST['typeid'])){
			$typeid = $_POST['typeid'];
		}
		if(isset($_POST['groupid'])){
			$groupid = $_POST['groupid'];
		}
		if(isset($_POST['keyword'])){
			$keyword = $_POST['keyword'];
		}
		$result = $this->model->searchIpinfo($typeid , $groupid ,$keyword);
		$html = '';
		if($result){
			foreach ($result as $key) {
				$html .=$key['oIp']."|";
				//$html .='<span class="style8"><input type="checkbox" class="style11 checkip"  value="'.$key['oIp'].'"/>'.$key['oIp'].'</span>';
			}
			$html = trim($html ,'|');
		}
		exit($html);
	}
	
	/**
	 * search script ajax
	 */
	public function searchScript(){
		$keyword = $_POST['keyword'];
		$result = $this->model->searchScriptinfo($keyword);
		$html = '';
		if($result){
			$json = array();
			foreach ($result as $key) {
				//$html .='<span class="style8"><input type="radio" class="style11 scriptname"  name="scriptname" value="'.$key['oScriptPath'].'"/>'.$key['oScriptName'].'</span>';
				$tmp = array('scriptpath'=>$key['oScriptPath'],'scriptname'=>$key['oScriptName']);
				array_push($json,$tmp);
			}
		}
		exit(json_encode($json));
	}
	
	/**
	 *  search config file
	 */
	public function searchConfigFile(){
		$filetypeid = '';
		if(isset($_POST['fileid'])){
			$filetypeid = $_POST['fileid'];
		}
		if(isset($_POST['keyword'])){
			$keyword = $_POST['keyword'];
		}
		$result = $this->model->searchConfiginfo($filetypeid , $keyword);
		$html = '';
		if($result){
			$json = array();
			foreach ($result as $key) {
				//$html .='<span class="style8"><input type="radio" class="style11 filename"  name="filename" value="'.$key['oSavePath'].'"/>'.$key['oFileName'].'</span>';
				$tmp = array('filepath'=>$key['oSavePath'],'filename'=>$key['oFileName']);
				array_push($json,$tmp);
			}
		}
		exit(json_encode($json));
	}
	
	/**
	 * 批量计划任务 详情
	 */
	public function batchplaninfo(){
		$id = $_GET['id'];
		$rs = $this->model->getTaskplanByid($id);
		$data['baseinfo'] = $rs ;
		$data['exinfo'] = $this->model->getBatchInfoByid($id ,$rs[0]['oCmdType'],'plan');
		$data['result'] = $this->model->getBatchResultByid($id ,0);
		$data['type'] = 'taskplan';
		$this->loadview('maintain/batchinfo',$data);
	}
	
	/**
	 * 批量立即任务 详情
	 */
	public function batchnowinfo(){
		$id = $_GET['id'];
		$rs = $this->model->getTasknowByid($id);
		$data['baseinfo'] = $rs ;
		$data['exinfo'] = $this->model->getBatchInfoByid($id ,$rs[0]['oCmdType'],'now');
		$data['result'] = $this->model->getBatchResultByid($id ,1);
		$data['type'] = 'tasknow';
		$this->loadview('maintain/batchinfo',$data);
	}
	/**
	 * 终止计划任务
	 */
	public function stoptaskplan(){
		if(!isset($_POST['arr'])){
			$id = $_GET['id'];
			$rs = $this->model->getTaskplanByid($id);
			$info = array(
				'oRunCycle' =>$rs[0]['oRunCycle'],
				'oRunDate' =>$rs[0]['oRunDate'],
				'oRunTime' =>$rs[0]['oRunTime'],
				'oCmdType' =>$rs[0]['oCmdType'],
				'oTaskplanid' =>$rs[0]['id']
			);
			$this->model->insertComplantask($info);
			$this->model->delTaskplanByid($id);
			$title = "终止计划任务";
			$info = $_SESSION['username']."终止计划任务成功,计划任务id：".$rs[0]['id'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$arr = $_POST['arr'];
			foreach ($arr as $key){
				$rs = $this->model->getTaskplanByid($key);
				$info = array(
					'oRunCycle' =>$rs[0]['oRunCycle'],
					'oRunDate' =>$rs[0]['oRunDate'],
					'oRunTime' =>$rs[0]['oRunTime'],
					'oCmdType' =>$rs[0]['oCmdType'],
					'oTaskplanid' =>$rs[0]['id']
				);
				$this->model->insertComplantask($info);
				$this->model->delTaskplanByid($key);
			}
			$title = "终止计划任务";
			$info = $_SESSION['username']."终止计划任务成功,共终止计划任务：".count($arr)."个,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 终止立即执行任务(有没有必要呢)
	 */
	public function stoptasknow(){
		if(!isset($_POST['arr'])){
			$id = $_GET['id'];
			$rs = $this->model->getTasknowByid($id);
			$info = array(
				'oRunTime' =>date('Y-m-d H:i:s',time()),
				'oCmdType' =>$rs[0]['oCmdType'],
				'oTasknowid' =>$rs[0]['id']
			);
			$this->model->insertComnowtask($info);
			$this->model->delTasknowByid($id);
			$title = "终止立即任务";
			$info = $_SESSION['username']."终止立即任务成功,立即任务id：".$rs[0]['id'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$arr = $_POST['arr'];
			foreach ($arr as $key){
				$rs = $this->model->getTasknowByid($key);
				$info = array(
					'oRunTime' =>date('Y-m-d H:i:s',time()),
					'oCmdType' =>$rs[0]['oCmdType'],
					'oTasknowid' =>$rs[0]['id']
				);
				$this->model->insertComnowtask($info);
				$this->model->delTasknowByid($key);
			}
			$title = "终止立即任务";
			$info = $_SESSION['username']."终止立即任务成功,共终止立即任务：".count($arr)."个,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}
	}
	
	/*****************************************************批量配置更新********************************************/
	
	public function configupdatelist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 46
		if(!osa_checkstr($_SESSION['login_role'],46)){
			header("Location: index.php?c=maintain&a=permiterror&left=configupdatelist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['cupdatesearch']);
			unset($_SESSION['cupdatestart']);
			unset($_SESSION['cupdateend']);
		}
		$be_url = '';
		if(isset($_GET['belong'])){
			$tasktype = $_GET['belong'];
			$be_url = "&belong=".$_GET['belong'];
			$data['tabli'] = '#tab_li'.$_GET['belong'];
		}else{
			$data['tabli'] = '#tab_li0';
			$tasktype = 0;
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['cupdatesearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['cupdatesearch'])?$_SESSION['cupdatesearch']:'';
		}
		if(isset($_POST['starttime'])){//说明是自定义搜索中时间不为空
			$_SESSION['cupdatestart']  = $_POST['starttime'];
			$_SESSION['cupdateend']  = $_POST['endtime'];
		}
		if(isset($_SESSION['cupdatestart'])){ 
			$starttime = $_SESSION['cupdatestart'];
			$endtimes = $_SESSION['cupdateend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'configupdatelist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['cupdateinfo'] = $this->model->selectConfigUpdate($perpage ,$offset ,$search ,$starttime ,$endtime ,$tasktype );
		$num = $this->model->getNumfromConfigUpdate($search ,$starttime ,$endtime ,$tasktype);
		$url = 'index.php?c=maintain&a=configupdatelist'.$be_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['tasktype'] = $tasktype;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/configupdatelist' , $data);
	}
	
	public function addconfigupdate(){
		if(!isset($_POST['sourcefile'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 47
			if(!osa_checkstr($_SESSION['login_role'],47)){
				header("Location: index.php?c=maintain&a=permiterror&left=configupdatelist", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'configupdatelist'; //控制左边栏
			$data['servertype'] = $this->model->selectServerType();
			$data['servergroup'] = $this->model->selectServerGroup();
			$data['filetype'] = $this->model->selectFileType();
			$this->loadview('maintain/addconfigupdate' , $data);
		}else{	
			if(empty($_POST['plantime'])){//立即执行
				//echo '立即执行';return ;
				$rs = $this->model->configupdate_now();
				if($rs){
					$title = "添加立即任务";
					$info = $_SESSION['username']."添加立即任务成功,类型：配置文件更新,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'success';return ;
				}else{
					$title = "添加立即任务";
					$info = $_SESSION['username']."添加立即任务失败,类型：配置文件更新,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'now_error';return ;
				}
			}else{ //计划任务
				//echo '计划任务';return ;
				$rs = $this->model->configupdate_plan();
				if($rs){
					$title = "添加计划任务";
					$info = $_SESSION['username']."添加计划任务成功,类型：配置文件更新,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'success';return ;
				}else{
					$title = "添加计划任务";
					$info = $_SESSION['username']."添加计划任务失败,类型：配置文件更新,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'failure';return ;
				}			
			}
		}
	}
	
	/***********************************************批量配置备份*************************************************/
	
	public function configbackuplist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 44
		if(!osa_checkstr($_SESSION['login_role'],44)){
			header("Location: index.php?c=maintain&a=permiterror&left=configbackuplist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['cbackupsearch']);
			unset($_SESSION['cbackupstart']);
			unset($_SESSION['cbackupend']);
		}
		$be_url = '';
		if(isset($_GET['belong'])){
			$tasktype = $_GET['belong'];
			$be_url = "&belong=".$_GET['belong'];
			$data['tabli'] = '#tab_li'.$_GET['belong'];
		}else{
			$data['tabli'] = '#tab_li0';
			$tasktype = 0;
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['cbackupsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['cbackupsearch'])?$_SESSION['cbackupsearch']:'';
		}
		if(isset($_POST['starttime'])){//说明是自定义搜索中时间不为空
			$_SESSION['cbackupstart']  = $_POST['starttime'];
			$_SESSION['cbackupend']  = $_POST['endtime'];
		}
		if(isset($_SESSION['cbackupstart'])){ 
			$starttime = $_SESSION['cbackupstart'];
			$endtimes = $_SESSION['cbackupend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'configbackuplist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['cbackupinfo'] = $this->model->selectConfigBackup($perpage ,$offset ,$search ,$starttime ,$endtime ,$tasktype );
		$num = $this->model->getNumfromConfigBackup($search ,$starttime ,$endtime ,$tasktype);
		$url = 'index.php?c=maintain&a=configbackuplist'.$be_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['tasktype'] = $tasktype;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/configbackuplist' , $data);
	}
	
	public function addconfigbackup(){
		if(!isset($_POST['sourcefile'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 45
			if(!osa_checkstr($_SESSION['login_role'],45)){
				header("Location: index.php?c=maintain&a=permiterror&left=configbackuplist", TRUE, 302);
			}
			$data['menu'] = 'maintain';//控制头部菜单栏
			$data['left'] = 'configbackuplist'; //控制左边栏
			$data['servertype'] = $this->model->selectServerType();
			$data['servergroup'] = $this->model->selectServerGroup();
			$this->loadview('maintain/addconfigbackup' , $data);
		}else{	
			if(empty($_POST['plantime'])){//立即执行
				//echo '立即执行';return ;
				$rs = $this->model->configbackup_now();
				if($rs){
					$title = "添加立即任务";
					$info = $_SESSION['username']."添加立即任务成功,类型：配置文件备份,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'success';return ;
				}else{
					$title = "添加立即任务";
					$info = $_SESSION['username']."添加立即任务失败,类型：配置文件备份,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'now_error';return ;
				}
			}else{ //计划任务
				//echo '计划任务';return ;
				$rs = $this->model->configbackup_plan();
				if($rs){
					$title = "添加计划任务";
					$info = $_SESSION['username']."添加计划任务成功,类型：配置文件备份,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'success';return ;
				}else{
					$title = "添加计划任务";
					$info = $_SESSION['username']."添加计划任务失败,类型：配置文件备份,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
					echo 'failure';return ;
				}			
			}
		}
	}
	
	
	/**
	 * 文件上传
	 */
	public function uploadfile(){
		if ($_POST["PHPSESSID"]) {
			session_id ( $_POST["PHPSESSID"] );
		}	
		//上传文件的父结点
		if (! isset ( $_FILES ["Filedata"] ) || $_FILES ["Filedata"] ["error"] != 0) {
			exit ( 'Nothing Upload' );
		}
		
		if(!isset($_SESSION['username'])){
			$_SESSION['username'] = $_POST['user'];
			if(empty($_SESSION['username'])){
				exit ( 'Invalid Request');
			}
		}
		
		$upload_file = $_FILES ["Filedata"];
		$uploadmodel = $this->loadmodel('maintain/mupload');
		$rs = $uploadmodel->uploadFile($upload_file);
		echo $rs ;

	}
	
	
	/*****************************************************批量处理***************************************************/
	public function batchlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 42
		if(!osa_checkstr($_SESSION['login_role'],42)){
			header("Location: index.php?c=maintain&a=permiterror&left=batchlist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['batchsearch']);
			unset($_SESSION['batchstart']);
			unset($_SESSION['batchend']);
		}
		$be_url = '';
		if(isset($_GET['belong'])){
			$tasktype = $_GET['belong'];
			$be_url = "&belong=".$_GET['belong'];
			$data['tabli'] = '#tab_li'.$_GET['belong'];
		}else{
			$data['tabli'] = '#tab_li0';
			$tasktype = 0;
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['batchsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['batchsearch'])?$_SESSION['batchsearch']:'';
		}
		if(isset($_POST['starttime'])){//说明是自定义搜索中时间不为空
			$_SESSION['batchstart']  = $_POST['starttime'];
			$_SESSION['batchend']  = $_POST['endtime'];
		}
		if(isset($_SESSION['batchstart'])){ 
			$starttime = $_SESSION['batchstart'];
			$endtimes = $_SESSION['batchend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'batchlist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['batchinfo'] = $this->model->selectOperations($perpage ,$offset ,$search ,$starttime ,$endtime ,$tasktype );
		$num = $this->model->getNumfromOperations($search ,$starttime ,$endtime ,$tasktype);
		$url = 'index.php?c=maintain&a=batchlist'.$be_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['tasktype'] = $tasktype;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/batchlist' , $data);
	}
	
	public function addbatchtask(){	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 43
		if(!osa_checkstr($_SESSION['login_role'],43)){
			header("Location: index.php?c=maintain&a=permiterror&left=addbatchtask", TRUE, 302);
		}
		$data['menu'] = 'maintain'; //控制头部菜单栏
		$data['left'] = 'addbatchtask'; //控制左边栏
		$data['servertype'] = $this->model->selectServerType();
		$data['servergroup'] = $this->model->selectServerGroup();
		$data['filetype'] = $this->model->selectFileType();
		$this->loadview('maintain/addbatchtask' , $data);	
	}
	
	/**
	 * 处理文件分发
	 */
	public function distribution(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->distribution_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量文件分发,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量文件分发,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->distribution_plan();
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量文件分发,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量文件分发,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}			
		}
	}
	
	/**
	 * 处理文件清理
	 */
	public function cleaner(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->cleaner_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量文件清理,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量文件清理,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->cleaner_plan();	
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量文件清理,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量文件清理,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}	
		}
	}
	
	/**
	 * 服务处理
	 */
	public function serverdeal(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->serverdeal_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量服务处理,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量服务处理,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->serverdeal_plan();
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量服务处理,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量服务处理,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}		
		}
	}
	
	/**
	 * 批量指令
	 */
	public function command(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->command_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量指令执行,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量指令执行,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->command_plan();	
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量指令执行,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量指令执行,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}		
		}
	}
	
	/**
	 * 批量安装
	 */
	public function installation(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->installation_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量安装程序,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量安装程序,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}	
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->installation_plan();
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量安装程序,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量安装程序,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}			
		}
	}
	
	/**
	 * 批量批量磁盘空间
	 */
	public function diskspace(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->diskspace_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量磁盘空间,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量磁盘空间,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->diskspace_plan();
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量磁盘空间,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量磁盘空间,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}			
		}
	
	}
	
	/**
	 * 批量负载状态
	 */
	public function loadstate(){
		if(empty($_POST['plantime'])){//立即执行
			//echo '立即执行';return ;
			$rs = $this->model->loadstate_now();
			if($rs){
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务成功,类型：批量负载状态,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加立即任务";
				$info = $_SESSION['username']."添加立即任务失败,类型：批量负载状态,可能原因：数据库操作失败、python端通信失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'now_error';return ;
			}
		}else{ //计划任务
			//echo '计划任务';return ;
			$rs = $this->model->loadstate_plan();
			if($rs){
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务成功,类型：批量负载状态,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加计划任务";
				$info = $_SESSION['username']."添加计划任务失败,类型：批量负载状态,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}			
		}
	}
	
	/**
	 * 批量操作结果显示页
	 */
	public function bresultlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['bresultstart']);
			unset($_SESSION['bresultend']);
		}
		$be_url = '';
		if(isset($_GET['belong'])){
			$tasktype = $_GET['belong'];
			$be_url = "&belong=".$_GET['belong'];
			$data['tabli'] = '#tab_li'.$_GET['belong'];
		}else{
			$data['tabli'] = '#tab_li0';
			$tasktype = 0;
		}
	
		if(isset($_POST['starttime'])){//说明是自定义搜索中时间不为空
			$_SESSION['bresultstart']  = $_POST['starttime'];
			$_SESSION['bresultend']  = $_POST['endtime'];
		}
		if(isset($_SESSION['bresultstart'])){ 
			$starttime = $_SESSION['bresultstart'];
			$endtimes = $_SESSION['bresultend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'maintain';//控制头部菜单栏
		$data['left'] = 'bresultlist'; //控制左边栏
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['rsinfo'] = $this->model->getBatchResult($perpage ,$offset ,$starttime ,$endtime ,$tasktype );
		$num = $this->model->getNumfromResult($starttime ,$endtime ,$tasktype);
		$url = 'index.php?c=maintain&a=bresultlist'.$be_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['tasktype'] = $tasktype;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('maintain/bresultlist' , $data);
	}
	
	public function permiterror(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['menu'] = $_GET['c'];
		$data['left'] = $_GET['left'];
		$this->loadview("maintain/permiterror",$data);
	}
	
	public function delbresult(){
		//权限判断 63
		if(!osa_checkstr($_SESSION['login_role'],48)){
			//header("Location: index.php?c=maintain&a=permiterror&left=databackuplist", TRUE, 302);
			echo 'no_perrmissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$tasktype = $_POST['tasktype'];
		$arr = $_POST['arr'];
		foreach ($arr as $key) {
			$this->model->delBatchResult($key,$tasktype);
		}
		$title = "删除批量结果";
		$info = $_SESSION['username']."删除批量结果成功,共删除记录：".count($arr)."条,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(3,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
}