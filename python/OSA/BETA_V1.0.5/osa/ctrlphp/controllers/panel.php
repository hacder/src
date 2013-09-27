<?php
class panel extends osa_controller{
	
	private $model = null;
	private $page = null;
	private $date = array();
	
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mpanel');
		$this->page = $this->loadmodel('mpage');
		$this->date = array(
			'today'        => date("Y-m-d H:i:s" ,strtotime('today')),
			'yesterday'    => date("Y-m-d H:i:s" ,strtotime('-1 day')),
			'lastweek'     => date("Y-m-d H:i:s" ,strtotime('-7 day')) ,
			'last2week'    => date("Y-m-d H:i:s" ,strtotime('-15 day')) 
		);
	}
	
	/**
	 * 配置设置页面控制 
	 */
	public function configset(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'configset';
		$this->loadview('panel/configset' ,$data);
	}
	
	
	/**
	 * 系统功能设置页面控制
	 */
	public function sysfeatureset(){
		if(!isset($_POST['oIsOpen'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
		    //权限判断 90
			if(!osa_checkstr($_SESSION['login_role'],90)){
				header("Location: index.php?c=panel&a=permiterror&left=sysfeatureset", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'panel';//控制头部菜单栏。
			$data['left'] = 'sysfeatureset';
			$this->loadview('panel/sysfeatureset' ,$data);
		}else{
			//这部分还需要修改
			$info = array(
				'oIsOpen'=>$_POST['oIsOpen'],
				'oIsSnmp'=>$_POST['oIsSnmp'],
				'oSnmpConfig'=>$_POST['oSnmpConfig'],	
			);
			$rs = $this->model->insertSysconfig($info);
			$title = "系统功能设置";
			$info = $_SESSION['username']."系统功能设置成功,时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			if($rs){
				$title = "系统功能设置";
				$info = $_SESSION['username']."系统功能设置成功,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "系统功能设置";
				$info = $_SESSION['username']."系统功能设置失败,可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}
		}
	}
	
	/**
	 * 安全密钥设置页面控制
	 */
	public function securityset(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 91
		if(!osa_checkstr($_SESSION['login_role'],91)){
			header("Location: index.php?c=panel&a=permiterror&left=securityset", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'securityset';
		$this->loadview('panel/securityset' ,$data);
	}
	
	/********************************************通知方式 ************************************************************/
	/**
	 * 通知方式设置页面控制
	 */
	public function notiset(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 92
		if(!osa_checkstr($_SESSION['login_role'],92)){
			header("Location: index.php?c=panel&a=permiterror&left=notiset", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'notiset';
		$data['notinfo'] = $this->model->selectNotiConfig();
		$this->loadview('panel/notiset' ,$data);
	}
	
	/**
	 * 测试邮件ajax处理
	 */
	public function testemail(){
		
		$subject = '这是来自osa的一封测试邮件！';
		$content = '当您收到这封邮件时，恭喜你的邮件配置成功通过！';
		$mail = $this->loademail();
		$mail->CharSet = "utf8";                
		$mail->IsSMTP();                         
		$mail->Host = $_POST['smtphost'];      
		$mail->Port = $_POST['smtpport'];                          
		$mail->From     = $_POST['sendemail'];             
		$mail->FromName = $_POST['senduser'];                       
		$mail->SMTPAuth = true;                         
		$mail->Username=$_POST['smtpuser'];         
		$mail->Password = $_POST['smtppass'];       
		$mail->Subject = $subject;                                
		$mail->Body = $content;                
		$mail->AddReplyTo("openwebsa@163.com","osa");     
		$address=$_POST['receivemail']; //设置收件的地址
		$mail->AddAddress($address); 
		if(!$mail->Send()) {           //发送邮件		
			//$display = "\'\'";
			$displaytext = '测试邮件发送失败！原因是：'.$mail->ErrorInfo;					
		}else{			
			//$display = "\'\'";
			$displaytext = '测试邮件发送成功！';		
		}
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		echo $displaytext;	
		return ;
	}
	
	/***
	 * 保存smtp设置
	 */
	public function savesmtpset(){
//		$configfile=OSA_PUBETC_PATH.'/ows_Global_Alarm_Config.ini';
//		$configfile_content=file_get_contents($configfile);
//		$configlist=parse_ini_file($configfile,'true');
//		$smtplist = array(
//			 'SMTP_SERVER = '.$configlist['smtpinfo']['SMTP_SERVER'] => 'SMTP_SERVER = '.$_POST['smtphost'] ,
//			 'FROM = '.$configlist['smtpinfo']['FROM'] => 'FROM = '.$_POST['sendemail'] ,
//			 'USER = '.$configlist['smtpinfo']['USER'] => 'USER = '.$_POST['smtpuser'] ,
//			 'PASSWORD = '.$configlist['smtpinfo']['PASSWORD'] => 'PASSWORD = '.$_POST['smtppass'] ,
//			 'oEmailPort = '.$configlist['smtpinfo']['oEmailPort'] => 'oEmailPort = '.$_POST['smtpport'] ,
//			 'oEmailName = '.$configlist['smtpinfo']['oEmailName'] => 'oEmailName = '.$_POST['senduser'] ,
//			 'oEmailTest = '.$configlist['smtpinfo']['oEmailTest'] => 'oEmailTest = '.$_POST['receivemail'] ,	
//		);
//	
//		$configfile_content = strtr($configfile_content ,$smtplist);	
//	
//		if(file_put_contents($configfile,$configfile_content)){		
//			$display = "\'\'";
//			$displaytext = '配置文件保存成功！';	
//			$title = "通知方式设置";
//			$info = $_SESSION['username']."通知方式设置成功,时间：".date("Y-m-d H:i:s",time());
//			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
//		}else{			
//			$display = "\'\'";
//			$displaytext = '配置文件保存失败！';
//			$title = "系统功能设置";
//			$info = $_SESSION['username']."系统功能设置失败,可能原因:配置文件不可写,时间：".date("Y-m-d H:i:s",time());
//			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);		
//		}
//		echo $displaytext;
//		return ;
		$stmpinfo = array(
			'oServerHost'=>$_POST['smtphost'],
			'oServerName'=>$_POST['smtpuser'],
			'oServerPort'=>$_POST['smtpport'],
			'oServerPass'=>$_POST['smtppass'],
			'oSendAddress'=>$_POST['sendemail'],
			'oSendName'=>$_POST['senduser'],
			'oReceiveAddress'=>$_POST['receivemail'],
		);
		$rs = $this->model->selectNotiConfig();
		if($rs){
			$this->model->updateNotiConfig($stmpinfo);
		}else{
			$this->model->insertNotiConfig($stmpinfo);
		}
		
	}
	
	/**************************************************项目监控列表控制**********************************************/
	/**
	 * 项目监控列表页面控制
	 */
	public function monitorlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 93
		if(!osa_checkstr($_SESSION['login_role'],93)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['monitorsearch']);
			unset($_SESSION['monitorstart']);
			unset($_SESSION['monitorend']);
		}	
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['monitorsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['monitorsearch'])?$_SESSION['monitorsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['monitorstart'] = $_POST['starttime'];
			$_SESSION['monitorend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['monitorstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['monitorstart'];
			$endtimes = $_SESSION['monitorend'];
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
		
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['monitorinfo'] = $this->model->selectAlarmsInfo($perpage ,$offset ,$search ,$starttime ,$endtime );
		$num = $this->model->getNumfromAlarms($search ,$starttime ,$endtime );
		$url = "index.php?c=panel&a=monitorlist";
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('panel/monitorlist' ,$data);
	}
	
	/**
	 * 开启报警项目
	 */
	public function startAlarms(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=panel&a=monitorlist", TRUE, 302);
		}
		$id = $_GET['id'];
		$this->model->startAlarms($id);
		$title = "开启监控项目";
		$info = $_SESSION['username']."开启监控项目成功,项目id:$id ,时间：".date("Y-m-d H:i:s",time());
		$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
		exit();
	}
	
	/**
	 * 停止报警项目
	 */
	public function stopAlarms(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=panel&a=monitorlist", TRUE, 302);
		}
		$id = $_GET['id'];
		$this->model->stopAlarms($id);
		$title = "停止监控项目";
		$info = $_SESSION['username']."停止监控项目成功,项目id:$id ,时间：".date("Y-m-d H:i:s",time());
		$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
		exit();
	}
	
	/**
	 * 删除报警项目
	 */
	public function delAlarms(){
		//权限判断 95
		if(!osa_checkstr($_SESSION['login_role'],95)){
			//header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
			echo 'no_permissions';return;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key) {
			$this->model->delAlarms($key);
		}
		$title = "删除监控项目";
		$info = $_SESSION['username']."删除监控项目成功,共删除项目".count($arr)."个,时间：".date("Y-m-d H:i:s",time());
		$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 报警类型页面控制
	 */
	public function alarmlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$this->loadview('panel/alarmlist' ,$data);
	}
	
	/***************************************************网页存活报警***********************************************/
	/**
	 * 网页存活报警页面控制
	 */
	public function webalarm(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$this->loadview('panel/webalarm' ,$data);
	}
	
	/**
	 * 添加网页存活页面
	 */
	public function savewebalarm(){
		$url = $_POST['urlname'];$keyword = $_POST['prokey'];$httpcode = $_POST['httpstatus'];
		$oItemConfig = '{"alarmcmd":"webisalive","url":"'.$url.'","keywords":"'.$keyword.'","httpcode":"'.$httpcode.'"}';	
		//$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'网页存活',
			'oItemType'=>'webisalive',
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑网页存活报警项目";
			$info = $_SESSION['username']."编辑网页存活报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加网页存活报警项目";
				$info = $_SESSION['username']."添加网页存活报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加网页存活报警项目";
				$info = $_SESSION['username']."添加网页存活报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/**
	 * 搜索用户ajax
	 */
	public function searchUser(){
		$keyword = trim($_POST['keyword'],"");
		$result = $this->model->searchuser($keyword);
		$html = '';
		if($result){
			foreach ($result as $key) {
				$html .=$key['oUserName']."|";
				//$html .='<span class="style8"><input type="checkbox" class="style11 username"  name="username" value="'.$key['oUserName'].'"/>'.$key['oUserName'].'</span>';
			}
			$html = trim($html ,'|');
		}
		exit($html);
	}
	
	/************************************************磁盘空间******************************************************/
	/**
	 * 磁盘空间报警页面控制
	 */
	public function diskalarm(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$manage = $this->loadmodel('maintain/manage');
		$data['servertype'] = $manage->selectServerType();
		$data['servergroup'] = $manage->selectServerGroup();
		$this->loadview('panel/diskalarm' ,$data);
	}
	
	/**
	 * 添加磁盘空间报警记录
	 */
	public function savediskalarm(){
		$oItemConfig = array(
			'alarmcmd'=>'diskspacecheck',
			'percentage'=>$_POST['threshold'],
		);
		$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'磁盘空间',
			'oItemType'=>'diskspacecheck',
			'oServerList'=>$_POST['serverlist'],
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑磁盘空间报警项目";
			$info = $_SESSION['username']."编辑磁盘空间报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加磁盘空间报警项目";
				$info = $_SESSION['username']."添加磁盘空间报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加磁盘空间报警项目";
				$info = $_SESSION['username']."添加磁盘空间报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/*************************************************负载*************************************************/
	/**
	 * 负载报警页面控制
	 */
	public function loadalarm(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$manage = $this->loadmodel('maintain/manage');
		$data['servertype'] = $manage->selectServerType();
		$data['servergroup'] = $manage->selectServerGroup();
		$this->loadview('panel/loadalarm' ,$data);
	}
	
	/**
	 * 保存负载
	 */
	public function saveloadalarm(){
		$oItemConfig = array(
			'alarmcmd'=>'topstatcheck',
			'topvalue'=>$_POST['threshold'],
		);
		$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'负载状态',
			'oItemType'=>'topstatcheck',
			'oServerList'=>$_POST['serverlist'],
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑负载状态报警项目";
			$info = $_SESSION['username']."编辑负载状态报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加负载状态报警项目";
				$info = $_SESSION['username']."添加负载状态报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加负载状态报警项目";
				$info = $_SESSION['username']."添加负载状态报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/**********************************************端口***********************************************************/
	/**
	 * 端口存活报警页面控制
	 */
	public function portalarm(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$manage = $this->loadmodel('maintain/manage');
		$data['servertype'] = $manage->selectServerType();
		$data['servergroup'] = $manage->selectServerGroup();
		$this->loadview('panel/portalarm' ,$data);
	}
	
	/***
	 * 保存端口存活
	 */
	public function saveportalarm(){
		$oItemConfig = array(
			'alarmcmd'=>'portstatcheck',
			'portlist'=>$_POST['port'],
		);
		$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'端口存活',
			'oItemType'=>'portstatcheck',
			'oServerList'=>$_POST['serverlist'],
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑端口存活报警项目";
			$info = $_SESSION['username']."编辑端口存活报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加端口存活报警项目";
				$info = $_SESSION['username']."添加端口存活报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加端口存活报警项目";
				$info = $_SESSION['username']."添加端口存活报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/***********************************************************数据库服务*******************************************/
	/**
	 * 数据库服务监控页面控制
	 */
	public function databasealarm(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$manage = $this->loadmodel('maintain/manage');
		$data['servertype'] = $manage->selectServerType();
		$data['servergroup'] = $manage->selectServerGroup();
		$this->loadview('panel/databasealarm' ,$data);
	}
	
	/***
	 * 保存数据库服务
	 */
	public function savadatalarm(){
		$oItemConfig = array(
			'alarmcmd'=>'databasecheck',
			'user'=>$_POST['user'],
			'password'=>$_POST['password'],
			'port'=>$_POST['port'],
			'pvalue'=>$_POST['linknum'],
			'tvalue'=>$_POST['threadnum']
		);
		$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'数据库',
			'oItemType'=>'databasecheck',
			'oServerList'=>$_POST['serverlist'],
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑数据库服务报警项目";
			$info = $_SESSION['username']."编辑数据库服务报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加数据库服务报警项目";
				$info = $_SESSION['username']."添加数据库服务报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加数据库服务报警项目";
				$info = $_SESSION['username']."添加数据库服务报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/***************************************************登录用户数量************************************************/
	/**
	 * 登录用户数量报警页面控制
	 */
	public function usersalarm(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$manage = $this->loadmodel('maintain/manage');
		$data['servertype'] = $manage->selectServerType();
		$data['servergroup'] = $manage->selectServerGroup();
		$this->loadview('panel/usersalarm' ,$data);
	}
	
	/**
	 * 保存用户登录数量报警
	 */
	public function savausersalarm(){
		$oItemConfig = array(
			'alarmcmd'=>'loginusercheck',
			'usernum'=>$_POST['usernum'],
		);
		$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'登录用户',
			'oItemType'=>'loginusercheck',
			'oServerList'=>$_POST['serverlist'],
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑登录用户数报警项目";
			$info = $_SESSION['username']."编辑登录用户数报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加登录用户数报警项目";
				$info = $_SESSION['username']."添加登录用户数报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加登录用户数报警项目";
				$info = $_SESSION['username']."添加登录用户数报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/****************************************************网络流量**************************************************/
	/**
	 * 网络流量报警页面控制
	 */
	public function networktraffic(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 94
		if(!osa_checkstr($_SESSION['login_role'],94)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		$manage = $this->loadmodel('maintain/manage');
		$data['servertype'] = $manage->selectServerType();
		$data['servergroup'] = $manage->selectServerGroup();
		$this->loadview('panel/networktraffic' ,$data);
	}
	
	/**
	 * 保存网络流量报警
	 */
	public function savatrafficalarm(){
		$oItemConfig = array(
			'alarmcmd'=>'networkcheck',
			'topvalue'=>$_POST['topvalue'],
		);
		$oItemConfig = json_encode($oItemConfig);
		$info = array(
			'oItemName'=>$_POST['proname'],
			'oItemClass'=>'网络流量',
			'oItemType'=>'networkcheck',
			'oServerList'=>$_POST['serverlist'],
			'oItemConfig'=>$oItemConfig,
			'oCheckRate'=>$_POST['checkrate'],
			'oAlarmNum'=>$_POST['checknum'],
			'oIsRemind'=>$_POST['remind'],
			'oNotiObject'=>$_POST['notiobject'],
			'oAddTime' =>date('Y-m-d H:i:s',time()),
		);
		if(isset($_GET['id'])){
			$this->model->updateAlarms($info,$_GET['id']);
			$title = "编辑网络流量报警项目";
			$info = $_SESSION['username']."编辑网络流量报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
			$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}else{
			$rs = $this->model->insertAlarms($info);
			if($rs){
				$title = "添加网络流量报警项目";
				$info = $_SESSION['username']."添加网络流量报警项目成功,项目名称：".$_POST['proname'].",时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}else{
				$title = "添加网络流量报警项目";
				$info = $_SESSION['username']."添加网络流量报警项目失败,项目名称：".$_POST['proname'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s",time());
				$this->model->saveSysLog(6,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
		}
	}
	
	/******************************************报警项目编辑*******************************************************/
	
	/**
	 * 编辑页面按类型分发中心
	 */
	public function editAlarms(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 96
		if(!osa_checkstr($_SESSION['login_role'],96)){
			header("Location: index.php?c=panel&a=permiterror&left=monitorlist", TRUE, 302);
		}
		$id = $_GET['id'];
		$type = $_GET['type'];
		$alarminfo = $this->model->selectAlarmsbyid($id);
		$data['menu'] = 'panel';//控制头部菜单栏。
		$data['left'] = 'monitorlist';
		if($alarminfo){
			$data['alarminfo'] = $alarminfo[0];
			$data['itemconfig'] = (array)json_decode($alarminfo[0]['oItemConfig']);
			$manage = $this->loadmodel('maintain/manage');
			switch ($type){
				case 'webisalive':
					$this->loadview("panel/webalarmedit",$data);
					break ;
				case 'diskspacecheck':
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/diskalarmedit",$data);
					break ;
				case 'topstatcheck':
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/loadalarmedit",$data);
					break ;
				case 'portstatcheck':
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/portalarmedit",$data);
					break ;
				case 'loginusercheck':
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/useralarmedit",$data);
					break ;
				case 'networkcheck':
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/netalarmedit",$data);
					break ;
				case 'databasecheck':
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/datalarmedit",$data);
					break ;
				default:
					$data['servertype'] = $manage->selectServerType();
					$data['servergroup'] = $manage->selectServerGroup();
					$this->loadview("panel/webalarmedit",$data);
					break ;
			}
		}else{
			header("Location: index.php?c=panel&a=monitorlist", TRUE, 302);
		}
	}
	
	public function permiterror(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['menu'] = $_GET['c'];
		$data['left'] = $_GET['left'];
		$this->loadview("panel/permiterror",$data);
	}
}	