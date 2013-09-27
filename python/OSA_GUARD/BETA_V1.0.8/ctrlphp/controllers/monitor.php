<?php
class monitor extends osa_controller{


	private $model = null;
	private $page = null;
	
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mmonitor');
		$this->page = $this->loadmodel('mpage');
		if(!isset($_SESSION)){
			session_start();
		}
		
		$_SESSION['header'] = "monitor";
	}

	
	/**************************************start---  monitor item view ----start******************************************/	
	
	
	/**
	 * monitor list view
	 */
	public function monitorlist(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		$type = ''; //默认为http类型
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}
		$search = $status = '';
		if(isset($_GET['search'])){
			$search = $_GET['search'];
		}
		if(isset($_GET['status'])){
			$status = $_GET['status'];
		}
		
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['iteminfo'] = $iteminfo = $this->model->monitor_select_page($type ,$search ,$status,$perpage ,$offset);
		// 计算可用率（默认为最新这张表的项目可用率）
		$data['itemrate'] = $this->model->monitor_item_rate($iteminfo);
		$num = $this->model->monitor_select_nums($type ,$search,$status);
		if(!empty($type)){
			$url = 'index.php?c=monitor&a=monitorlist&type='.$type;
			$ajaxurl = 'index.php?c=monitor&a=monitorlist_ajax&type='.$type ;
		}else{
			$url = 'index.php?c=monitor&a=monitorlist';
			$ajaxurl = 'index.php?c=monitor&a=monitorlist_ajax';
		}
		if(!empty($search)){
			$pageurl =$url."&search=".$search;
		}else{
			$pageurl = $url ;
		}
		if(!empty($status)){
			$pageurl .="&status=".$status ;
		}
		$data['status'] = $status ;
		$data['search'] = $search ;
		$data['url'] = $url;
		$data['ajaxurl'] = $ajaxurl;
		$data['numarr'] = $this->model->monitor_nums_itemtype();
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('monitor/monitorlist',$data);
		
	}
	
	
	public function monitorlist_ajax(){
	
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		$type = ''; //默认为http类型
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}
		$search = $status = '';
		if(isset($_GET['search'])){
			$search = $_GET['search'];
		}
		if(isset($_GET['status'])){
			$status = $_GET['status'];
		}
		
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['iteminfo'] = $iteminfo = $this->model->monitor_select_page($type ,$search ,$status,$perpage ,$offset);
		
		// 计算可用率（默认为最新这张表的项目可用率）
		$data['itemrate'] = $this->model->monitor_item_rate($iteminfo);
		$num = $this->model->monitor_select_nums($type ,$search,$status);
		if(!empty($type)){
			$url = 'index.php?c=monitor&a=monitorlist&type='.$type;
			$ajaxurl = 'index.php?c=monitor&a=monitorlist_ajax&type='.$type ;
		}else{
			$url = 'index.php?c=monitor&a=monitorlist';
			$ajaxurl = 'index.php?c=monitor&a=monitorlist_ajax' ;
		}
		if(!empty($search)){
			$pageurl =$url."&search=".$search;
		}else{
			$pageurl = $url ;
		}
		if(!empty($status)){
			$pageurl .="&status=".$status ;
		}
		$data['status'] = $status ;
		$data['search'] = $search ;
		$data['url'] = $url;
		$data['ajaxurl'] = $ajaxurl;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('monitor/monitorlist_ajax',$data);
	}
	
	
	/**
	 * item list view
	 */
	public function itemlist(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/itemlist');
	}
	
	
	
	/**
	 * monitor website view
	 */
	public function websiteindex(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/website');
		
	}
	
	
	/**
	 * monitor ping view
	 */
	public function pingindex(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/ping');
		
	}
	
	/**
	 * monitor tcp view
	 */
	public function tcpindex(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/tcp');
		
	}
	
	
	/**
	 * monitor udp view
	 */
	public function udpindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/udp');
		
	}
	
	
	/**
	 * monitor ftp view
	 */
	public function ftpindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/ftp');
		
	}
	
	
	/**
	 * monitor dns view
	 */
	public function dnsindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/dns');
		
	}
	
	
	
	/**
	 * apache view
	 */
	public function apacheindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/apache');
		
	}
	
	
	/**
	 * lighttpd view
	 */
	public function lighttpdindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/lighttpd');
		
	}
	
	
	/**
	 *nginx view 
	 */
	public function nginxindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/nginx');
	
	}
	
	
	/**
	 * mongodb view
	 */
	public function mongodbindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/mongodb');
		
	}
	
	
	
	/**
	 * redis view
	 */
	public function redisindex(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/redis');
		
	}
	
	
	/**
	 * memcache view
	 */
	public function memcacheindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/memcache');
		
	}
	
	
	/**
	 * mysql view
	 */
	public function mysqlindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/mysql');
		
	}
	
	
	
	/**
	 * custom view
	 */
	public function customindex(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/custom');
		
	}
	
	/**
	 * notiset view
	 */
	public function notiset(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('monitor/notiset');
	}
	
	
	/**************************************end---  monitor item view ----end******************************************/	
	
	/**************************************start---  monitor item method ----start******************************************/	
	/**
	 * website monitor
	 */
	public function website_monitor(){
		
		$keyword = trim($_POST['keyword']);
		$itemip = trim($_POST['itemip']);
		$httpstatus = trim($_POST['httpstatus']);
		$itemconfig = "{'alarmcmd':'http','ip':'$itemip','keywords':'$keyword','httpcode':'$httpstatus'}";
		$objectarr = explode(",",$object);
		
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'http',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'http');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	
	/**
	 * ping monitor
	 */
	public function ping_monitor(){
		
		//get the post data
		$itemconfig = "{'alarmcmd':'ping'}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['itemip']),
			'oItemType'=>'ping',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'ping');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
		
	}
	
	
	/**
	 * tcp monitor
	 */
	public function tcp_monitor(){
	
		$object = trim($_POST['itemip']);
		$port = trim($_POST['port']);
		$itemconfig = "{'alarmcmd':'tcp','port':'$port'}";
		$objectarr = explode(",",$object);
		foreach ($objectarr as $key){
			$iteminfo = array(
				'oItemName'=>trim($_POST['itemname']),
				'oItemObject'=>$key,
				'oItemType'=>'tcp',
				'oCheckRate'=>trim($_POST['checkrate']),
				'oAlarmNum'=>trim($_POST['alarmnum']),
				'oRepeatNum'=>trim($_POST['repeatnum']),
				'oIsRemind'=>trim($_POST['remind']),
				'oItemConfig'=>$itemconfig,
				'oNotiUsers'=>trim($_POST['notiusers']),
				'oAddTime'=>date("Y-m-d H:i:s",time())
			);
			$this->model->monitor_insert($iteminfo);
		}
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'tcp');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * udp monitor
	 */
	public function udp_monitor(){
		
		$object = trim($_POST['itemip']);
		$port = trim($_POST['port']);
		$itemconfig = "{'alarmcmd':'udp','port':'$port'}";
		$objectarr = explode(",",$object);
		foreach ($objectarr as $key){
			$iteminfo = array(
				'oItemName'=>trim($_POST['itemname']),
				'oItemObject'=>$key,
				'oItemType'=>'udp',
				'oCheckRate'=>trim($_POST['checkrate']),
				'oAlarmNum'=>trim($_POST['alarmnum']),
				'oRepeatNum'=>trim($_POST['repeatnum']),
				'oIsRemind'=>trim($_POST['remind']),
				'oItemConfig'=>$itemconfig,
				'oNotiUsers'=>trim($_POST['notiusers']),
				'oAddTime'=>date("Y-m-d H:i:s",time())
			);
			$this->model->monitor_insert($iteminfo);
		}
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'udp');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * ftp monitor
	 */
	public function ftp_monitor(){
		
		$conifg = $_POST['itemconfig'];
		$port = $_POST['ftpport'];
		$itemconfig = "{'alarmcmd':'ftp','port':'$port'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'$key':'$value'";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'ftp',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'ftp');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * dns monitor
	 */
	public function dns_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'dns'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'$key':'$value'";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'dns',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'dns');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * apache monitor
	 */
	public function apache_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'apache'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'apache',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'apache');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * lighttpd monitor
	 */
	public function lighttpd_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'lighttpd'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'lighttpd',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'lighttpd');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	
	/**
	 * nginx monitor
	 */
	public function nginx_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'nginx'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'nginx',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'nginx');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * mysql status monitor
	 */
	public function mysql_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$user = $_POST['mysqluser'];
		$passwd = $_POST['mysqlpass'];
		$port = $_POST['mysqlport'];
		$itemconfig = "{'alarmcmd':'mysql','user':'$user','passwd':'$passwd','port':'$port'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'mysql',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'mysql');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * mongodb monitor
	 */
	public function mongodb_monitor(){
		
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'mongodb'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'mongodb',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'mongodb');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * redis monitor
	 */
	public function redis_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'redis'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'redis',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'redis');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * memcache monitor
	 */
	public function memcache_monitor(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'memcache'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'memcache',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_insert($iteminfo);
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'memcache');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
	
	/**
	 * custom monitor
	 */
	public function custom_monitor(){
		
		$conifg = $_POST['itemconfig'];
		$name = $_POST['custom_name'];
		$itemconfig = "{'alarmcmd':'custom','name':'$name'";
		if($config == ''){
			$itemconfig .=",'indicators':{"	;	
			foreach ($conifg as $key=>$value) {
				$itemconfig .="'$key':{'condition':'".$value['condition']."','value':'".$value['value']."'},";
			}	
			$itemconfig = trim($itemconfig,',');
			$itemconfig .="}";		
		}
		$itemconfig = $itemconfig."}";
		$object = $_POST['itemip'];
		$objectarr = explode(",",$object);
		foreach ($objectarr as $key){
			$iteminfo = array(
				'oItemName'=>trim($_POST['itemname']),
				'oItemObject'=>$key,
				'oItemType'=>'custom',
				'oCheckRate'=>trim($_POST['checkrate']),
				'oAlarmNum'=>trim($_POST['alarmnum']),
				'oRepeatNum'=>trim($_POST['repeatnum']),
				'oIsRemind'=>trim($_POST['remind']),
				'oItemConfig'=>$itemconfig,
				'oNotiUsers'=>trim($_POST['notiusers']),
				'oAddTime'=>date("Y-m-d H:i:s",time())
			);
			$this->model->monitor_insert($iteminfo);
		}
		$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'create','type'=>'custom');
		$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
		echo "success";return ;
	}
	
		
	/**************************************end---  monitor item method ---end******************************************/
	
	/**************************************start--- other monitor event ---start**********************************/

	/**
	 * smtp email test
	 */	
	public function smtp_testemail(){
		
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
			$displaytext = '测试邮件发送失败！原因是：'.$mail->ErrorInfo;					
		}else{			
			$displaytext = '测试邮件发送成功！';		
		}
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		echo $displaytext;	
		return ;
	}
	
	
	/**
	 * notiset event
	 */
	public function notiset_save(){
		$configinfo = array(
			'oIsEmail'=>trim($_POST['isemail']),
			'oIsSms'=>trim($_POST['issms']),
			'oIsMsn'=>trim($_POST['ismsn']),
			'oIsGtalk'=>trim($_POST['isgtalk']),
			'oMnumItem'=>trim($_POST['mnumitem']),
			'oMnumIp'=>trim($_POST['mnumip']),
		);
		$stmpinfo = array(
			'oServerHost'=>$_POST['smtphost'],
			'oServerName'=>$_POST['smtpuser'],
			'oServerPort'=>$_POST['smtpport'],
			'oServerPass'=>$_POST['smtppass'],
			'oSendAddress'=>$_POST['sendemail'],
			'oSendName'=>$_POST['senduser'],
			'oReceiveAddress'=>$_POST['receivemail'],
		);
		$this->model->notiset_insert($configinfo);
		$this->model->smtp_insert($stmpinfo);
		if(trim($_POST['issms']) == 1){
			$this->model->sms_insert(trim($_POST['notisms']));
		}
		if(trim($_POST['ismsn']) == 1){
			$this->model->msn_insert(trim($_POST['notimsn']));
		}
		if(trim($_POST['isgtalk']) == 1){
			$this->model->gtalk_insert(trim($_POST['notigtalk']));
		}
		echo "success";return ;
	}
	
	
	/************************************ monitor pause ,open ,del events *********************************/
	
	public function monitor_stop_batch(){
		
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->monitor_pause($id);
		}
	}
	
	
	public function monitor_open_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->monitor_open($id);
		}
	}
	
	
	public function monitor_del_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->monitor_delete($id);
		}
	}
	
	
	public function monitor_stop(){
		
		$id = $_POST['id'];
		$this->model->monitor_pause($id);	
	}
	
	
	public function monitor_open(){
		
		$id = $_POST['id'];
		$this->model->monitor_open($id);	
	}
	
	
	public function monitor_del(){
		
		$id = $_POST['id'];
		$this->model->monitor_delete($id);	
	}
	
	
	/*********************************** monitor edit view *************************************************/
	
	public function monitoredit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'];
		$type = $_GET['type'] ;
		switch($type){
			case 'http':
				header("Location: index.php?c=monitor&a=httpedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'tcp':
				header("Location: index.php?c=monitor&a=tcpedit&itemid=$itemid", TRUE, 302);
				break ;	
			case 'udp':
				header("Location: index.php?c=monitor&a=udpedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'ftp':
				header("Location: index.php?c=monitor&a=ftpedit&itemid=$itemid", TRUE, 302);
				break ;	
			case 'ping':
				header("Location: index.php?c=monitor&a=pingedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'dns':
				header("Location: index.php?c=monitor&a=dnsedit&itemid=$itemid", TRUE, 302);	
				break ;
			case 'apache':
				header("Location: index.php?c=monitor&a=apachedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'nginx':
				header("Location: index.php?c=monitor&a=nginxedit&itemid=$itemid", TRUE, 302);
				break ;	
			case 'lighttpd':
				header("Location: index.php?c=monitor&a=lighttpdedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'redis':
				header("Location: index.php?c=monitor&a=redisedit&itemid=$itemid", TRUE, 302);
				break ;	
			case 'memcache':
				header("Location: index.php?c=monitor&a=memcachedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'mongodb':
				header("Location: index.php?c=monitor&a=mongodbedit&itemid=$itemid", TRUE, 302);
				break ;
			case 'mysql':
				header("Location: index.php?c=monitor&a=mysqledit&itemid=$itemid", TRUE, 302);
				break ;
			case 'custom':
				header("Location: index.php?c=monitor&a=customedit&itemid=$itemid", TRUE, 302);
				break ;
			default :
				header("Location: index.php?c=monitor&a=httpedit&itemid=$itemid", TRUE, 302);
				break ;						
		}
	}
	
	
	public function httpedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/website',$data);
	}
	
	
	public function pingedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/ping',$data);
	}
	
	
	public function tcpedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/tcp',$data);
	}
	
	
	
	public function udpedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/udp',$data);
	}
	
	
	
	public function ftpedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/ftp',$data);
	}
	
	
	
	public function dnsedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/dns',$data);
	}
	
	
	public function apachedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/apache',$data);
	}
	
	
	public function nginxedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/nginx',$data);
	}
	
	
	
	public function lighttpdedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/lighttpd',$data);
	}
	
	
	public function redisedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/redis',$data);
	}
	
	
	public function mongodbedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/mongodb',$data);
	}
	
	
	public function memcachedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/memcache',$data);
	}
	
	public function mysqledit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$this->loadview('monitor/edit/mysql',$data);
	}
	
	public function customedit(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$data['itemid'] = $itemid = $_GET['itemid'];
		$data['itemdata'] = $this->model->monitor_select_itemid($itemid);
		$data['customname'] = $this->model->monitor_select_customname($itemid);
		$this->loadview('monitor/edit/custom',$data);
	}
	
	/*************************************   monitor edit event   ****************************************/
	
	public function monitor_http_edit(){
		
		$itemid = trim($_POST['itemid']);
		$keyword = trim($_POST['keyword']);
		$itemip = trim($_POST['itemip']);
		$httpstatus = trim($_POST['httpstatus']);
		$itemconfig = "{'alarmcmd':'http','ip':'$itemip','keywords':'$keyword','httpcode':'$httpstatus'}";
		$objectarr = explode(",",$object);
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'http',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$rs = $this->model->monitor_update($itemid,$iteminfo);
		//echo $rs ;return ;
		echo "success";return ;
	}
	
	
	public function monitor_ping_edit(){
		
		$itemconfig = "{'alarmcmd':'ping'}";
		$itemid = trim($_POST['itemid']);
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['itemip']),
			'oItemType'=>'ping',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * tcp monitor edit
	 */
	public function monitor_tcp_edit(){
	
		$object = trim($_POST['itemip']);
		$port = trim($_POST['port']);
		$itemid = trim($_POST['itemid']);
		$itemconfig = "{'alarmcmd':'tcp','port':'$port'}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>$object,
			'oItemType'=>'tcp',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * udp monitor edit
	 */
	public function monitor_udp_edit(){
		
		$object = trim($_POST['itemip']);
		$port = trim($_POST['port']);
		$itemid = trim($_POST['itemid']);
		$itemconfig = "{'alarmcmd':'udp','port':'$port'}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>$object,
			'oItemType'=>'udp',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * ftp monitor edit
	 */
	public function monitor_ftp_edit(){
		
		$conifg = $_POST['itemconfig'];
		$port = $_POST['ftpport'];
		$itemid = trim($_POST['itemid']);
		$itemconfig = "{'alarmcmd':'ftp','port':'$port'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'$key':'$value'";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'ftp',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * dns monitor edit
	 */
	public function monitor_dns_edit(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'dns'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'$key':'$value'";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'dns',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * apache monitor edit
	 */
	public function monitor_apache_edit(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'apache'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'apache',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * lighttpd monitor edit
	 */
	public function monitor_lighttpd_edit(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'lighttpd'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'lighttpd',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	
	/**
	 * nginx monitor edit
	 */
	public function monitor_nginx_edit(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'nginx'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'nginx',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * mysql status monitor edit
	 */
	public function monitor_mysql_edit(){
	
		$conifg = $_POST['itemconfig'];
		$user = $_POST['mysqluser'];
		$passwd = $_POST['mysqlpass'];
		$port = $_POST['mysqlport'];
		$itemconfig = "{'alarmcmd':'mysql','user':'$user','passwd':'$passwd','port':'$port'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'mysql',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * mongodb monitor edit
	 */
	public function monitor_mongodb_edit(){
		
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'mongodb'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'mongodb',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * redis monitor edit
	 */
	public function monitor_redis_edit(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'redis'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'redis',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * memcache monitor edit
	 */
	public function monitor_memcache_edit(){
	
		$conifg = $_POST['itemconfig'];
		$itemconfig = "{'alarmcmd':'memcache'";
		if($config == ''){		
			foreach ($conifg as $key=>$value) {
				$itemconfig .=",'".$key."':{'condition':'".$value['condition']."','value':'".$value['value']."'}";
			}			
		}
		$itemconfig = $itemconfig."}";
		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>trim($_POST['urlname']),
			'oItemType'=>'memcache',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);
		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/**
	 * custom monitor edit
	 */
	public function monitor_custom_edit(){
		
		$conifg = $_POST['itemconfig'];
		$name = $_POST['custom_name'];
		$itemconfig = "{'alarmcmd':'custom','name':'$name'";
		if($config == ''){
			$itemconfig .=",'indicators':{"	;	
			foreach ($conifg as $key=>$value) {
				$itemconfig .="'$key':{'condition':'".$value['condition']."','value':'".$value['value']."'},";
			}	
			$itemconfig = trim($itemconfig,',');
			$itemconfig .="}";		
		}
		$itemconfig = $itemconfig."}";
		$object = $_POST['itemip'];

		$iteminfo = array(
			'oItemName'=>trim($_POST['itemname']),
			'oItemObject'=>$object,
			'oItemType'=>'custom',
			'oCheckRate'=>trim($_POST['checkrate']),
			'oAlarmNum'=>trim($_POST['alarmnum']),
			'oRepeatNum'=>trim($_POST['repeatnum']),
			'oIsRemind'=>trim($_POST['remind']),
			'oItemConfig'=>$itemconfig,
			'oNotiUsers'=>trim($_POST['notiusers']),
			'oAddTime'=>date("Y-m-d H:i:s",time())
		);

		$itemid = trim($_POST['itemid']);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
	
	/***
	 * 监控项目 监控频率修改
	 */
	public function monitor_timeset(){
		
		$itemid = trim($_POST['itemid']);
		$iteminfo = array(
			'oCheckRate'=>trim($_POST['checkrate']),
		);
		$this->model->monitor_update($itemid,$iteminfo);
		echo "success";return ;
	}
	
}