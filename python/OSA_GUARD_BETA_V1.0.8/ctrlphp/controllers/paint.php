<?php
class paint extends osa_controller{

	
	private $model = '';
	private $page = '';
	
	
	public function __construct(){
	
		parent::__construct();
		
		$this->model = $this->loadmodel('mpaint');
		$this->page = $this->loadmodel('mpage');
		
		if(!isset($_SESSION)){
		
			session_statt();
		}	
		$_SESSION['header'] = "monitor";
		if (isset($_GET['period'])){
			$_SESSION['period'] = $_GET['period'];
		}else{
			unset($_SESSION['period']);
		}
	}
	
	
	public function distribution(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$type = $_GET['type'] ;
		switch($type){
			case 'http':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'http');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=httpable&itemid=$itemid", TRUE, 302);
				break ;
			case 'tcp':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'tcp');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
							
				header("Location: index.php?c=paint&a=tcpable&itemid=$itemid", TRUE, 302);
				break ;	
			case 'udp':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'udp');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=udpable&itemid=$itemid", TRUE, 302);
				break ;
			case 'ftp':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'ftp');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=ftpable&itemid=$itemid", TRUE, 302);
				break ;	
			case 'ping':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'ping');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=pingable&itemid=$itemid", TRUE, 302);
				break ;
			case 'dns':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'dns');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=dnsable&itemid=$itemid", TRUE, 302);	
				break ;
			case 'apache':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'apache');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=apacheable&itemid=$itemid", TRUE, 302);
				break ;
			case 'nginx':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'nginx');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=nginxable&itemid=$itemid", TRUE, 302);
				break ;	
			case 'lighttpd':
			$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'lighttpd');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=lighttpdable&itemid=$itemid", TRUE, 302);
				break ;
			case 'redis':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'redis');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=redisable&itemid=$itemid", TRUE, 302);
				break ;	
			case 'memcache':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'memcache');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=memable&itemid=$itemid", TRUE, 302);
				break ;
			case 'mongodb':
				$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'mongodb');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=monable&itemid=$itemid", TRUE, 302);
				break ;
			case 'mysql':
			$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'mysql');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=mysqlable&itemid=$itemid", TRUE, 302);
				break ;
			case 'custom':
			$param = array('userKey'=>OSA_SYSTEM_KEY,'event'=>'visit','type'=>'custom');
				$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/item.php');
				header("Location: index.php?c=paint&a=customable&itemid=$itemid", TRUE, 302);
				break ;
			default :
				header("Location: index.php?c=paint&a=httpable&itemid=$itemid", TRUE, 302);
				break ;						
		}
		
	}
	
	
	/***************************************  memcache 图形分析中心   **************************************/
	//memcache 可用率
	public function memable(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/available',$data);
	}
	
	// memcache 缓存命中率
	public function memhitrate(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memhitrate&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['indexrate'] = $this->model->memcache_indexrate_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/hitrate',$data);
	}
	
	// memcache curr_connects
	public function memcurrcon(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memcurrcon&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['currconnects'] = $this->model->memcache_currconnects_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/currcon',$data);
	}
	
	
	//memcache 空间使用率
	public function memspacerate(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memspacerate&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['spacerate'] = $this->model->memcache_spacerate_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/spacerate',$data);
	}
	
	//memcache 使用内粗
	public function memusedmem(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memusedmem&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['usedmem'] = $this->model->memcache_usedmem_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/usedmem',$data);
	}
	
	// memcache 当前条目数量
	public function memcurritems(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memcurritems&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['curritem'] = $this->model->memcache_curritems_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/curritems',$data);
		
	}
	
	// memcache 读写速率
	public function memwrsecond(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memwrsecond&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['wrsecond'] = $this->model->memcache_wrsecond_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/wrsecond',$data);
		
	}
	
	// memcache 连接数每秒
	public function memconsecond(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memconsecond&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['consecond'] = $this->model->memcache_consecond_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/consecond',$data);
		
	}
	
	
	/**
	 * memcache  测试用列
	 */
	public function memgraph(){
	
		$monitordata = $this->model->monitor_data_select_itemid(10);
		$data['indexrate'] = $this->model->memcache_indexrate_analyze($monitordata);
		$data['spacerate'] = $spacerate = $this->model->memcache_spacerate_analyze($monitordata);
		$data['usedmem'] = $this->model->memcache_usedmem_analyze($monitordata);
		$data['currconnects'] = $spacerate = $this->model->memcache_currconnects_analyze($monitordata);
		$data['curritem'] = $this->model->memcache_curritems_analyze($monitordata);
		$data['wrsecond'] = $this->model->memcache_wrsecond_analyze($monitordata);
		$data['consecond'] = $this->model->memcache_consecond_analyze($monitordata);
		$this->loadview('paint/memcache/complex',$data);
		
	}
	
	public function memresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=memresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/memcache/response',$data);	
	}
	/**********************************    redis 图形分析中心                 *********************************/
	
	
	//redis 可用率  
	public function redisable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redisable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/redisable',$data);
	}
	
	// redis 使用内存
	public function redusedmem(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redusedmem&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['usedmem'] = $this->model->redis_usedmem_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/usedmem',$data);
	}
	
	// reids 连接客户数
	public function redclientcon(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redclientcon&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['clientcon'] = $this->model->redis_clientcon_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/clientcon',$data);
	}
	
	// reids 连接从库数
	public function redslavecon(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redslavecon&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['slavecon'] = $this->model->redis_slavecon_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/slavecon',$data);
	}
	
	// reids 阻塞客户数
	public function redclientblock(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redclientblock&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['clientblock'] = $this->model->redis_clientblock_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/clientblock',$data);
	}
	
	
	// reids pub/sub 通道数
	public function redchannel(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redchannel&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['channel'] = $this->model->redis_channel_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/channel',$data);
	}
	
	// redis pub/sub 模式数
	public function redpattern(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redpattern&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['pattern'] = $this->model->redis_pattern_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/pattern',$data);
	}
	
	// redis 命中率
	public function redhitrate(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redhitrate&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['hitrate'] = $this->model->redis_hitrate_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/hitrate',$data);
	}
	
	//redis 连接数每分钟
	public function redconnectmin(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redconnectmin&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['connectmin'] = $this->model->redis_connectmin_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/connectmin',$data);
	}
	
	//redis 执行命令数每分钟
	public function redcommandmin(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redcommandmin&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['commandmin'] = $this->model->redis_commandmin_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/commandmin',$data);
	}
	
	
	public function redresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=redresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/redis/response',$data);	
	}
	
	/***********************************  mongodb 绘图中心    ******************************************************/
	
	//mongodb 可用率  
	public function monable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/monable',$data);
	}
	
	public function monlockratio(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monlockratio&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['lockratio'] = $this->model->mongodb_lockratio_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/lockratio',$data);
	}
	
	
	public function monlockwaits(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monlockwaits&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['lockwaits'] = $this->model->mongodb_lockwaits_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/lockwaits',$data);
	}
	
	
	public function monusedspace(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monusedspace&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['usedspace'] = $this->model->mongodb_usedspace_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/usedspace',$data);
	}
	
	
	public function monpagefault(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monpagefault&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['pagefault'] = $this->model->mongodb_pagefault_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/pagefault',$data);
	}
	
	public function monbtreeratio(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monbtreeratio&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['btreeratio'] = $this->model->mongodb_btreeratio_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/btreeratio',$data);
	}
	
	
	public function monaccesses(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monaccesses&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['accesses'] = $this->model->mongodb_accesses_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/accesses',$data);
	}
	
		
	public function moncurrconnect(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=moncurrconnect&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['currconnect'] = $this->model->mongodb_currconnect_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/currconnect',$data);
	}
	
	
	public function monopcounters(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monopcounters&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['opcounters'] = $this->model->mongodb_opcounters_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/opcounters',$data);
	}	
	
	
	public function monresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=monresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mongodb/response',$data);	
	}

	/************************************  apache 图形分析中心      ***********************************************/
	
	//apache 可用率  
	public function apacheable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=apacheable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/apache/apacheable',$data);
	}
	
	
	public function apaopcounters(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=apaopcounters&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['opcounters'] = $this->model->apache_opcounters_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/apache/opcounters',$data);
	}
	
	
	public function apacapacity(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=apacapacity&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['capacity'] = $this->model->apache_capacity_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/apache/capacity',$data);
		
	}
	
	
	public function apaconnects(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=apaconnects&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['connects'] = $this->model->apache_connects_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/apache/connects',$data);
		
	}
	
	
	public function apascoreboard(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=apascoreboard&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['scoreboard'] = $this->model->apache_scoreboard_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/apache/scoreboard',$data);	
	}
	
	
	public function aparesponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=aparesponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/apache/response',$data);	
	}
	
	
	/***********************************  nginx 图形分析中心      ******************************************************/
	
	//nginx 可用率  
	public function nginxable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=nginxable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/nginx/nginxable',$data);
	}
	
	//nginx 吞吐率
	public function ngxopcounters(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ngxopcounters&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['opcounters'] = $this->model->nginx_opcounters_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/nginx/opcounters',$data);
	}
	
	// nginx 活动连接数
	public function ngxconnects(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ngxconnects&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['connects'] = $this->model->nginx_connects_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/nginx/connects',$data);
		
	}
	
	// nginx 连接数详情
	public function ngxscoreboard(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ngxscoreboard&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['scoreboard'] = $this->model->nginx_scoreboard_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/nginx/scoreboard',$data);	
	}
	
	public function ngxresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ngxresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/nginx/response',$data);	
	}
	
	/*************************************   lighttpd 画图中心         ******************************************/
	
	//lighttpd 可用率  
	public function lighttpdable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=lighttpdable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/lighttpd/lighttpdable',$data);
	}
	
	
	//lighttpd 吞吐率
	public function ligopcounters(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ligopcounters&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['opcounters'] = $this->model->lighttpd_opcounters_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/lighttpd/opcounters',$data);
	}
	
	// lighttpd 活动连接数
	public function ligconnects(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ligconnects&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['connects'] = $this->model->lighttpd_connects_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/lighttpd/connects',$data);
		
	}
	
	// lighttpd 连接数详情
	public function ligscoreboard(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ligscoreboard&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['scoreboard'] = $this->model->lighttpd_scoreboard_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/lighttpd/scoreboard',$data);	
	}
	
	public function ligresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ligresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/lighttpd/response',$data);	
	}
	
	/***************************  mysql 图形中心   **************************************************/
	
	// mysql 可用率  
	public function mysqlable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=mysqlable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/mysqlable',$data);
	}
	
	
	// mysql 连接数详情
	public function sqlscoreboard(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlscoreboard&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['scoreboard'] = $this->model->mysql_scoreboard_analyz($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/scoreboard',$data);	
	}
	
	// mysql 缓存空间利用率
	public function sqlqcachespace(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlqcachespace&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['qcachespace'] = $this->model->mysql_qcachespace_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/qcachespace',$data);	
	}
	
	// mysql 缓存命中率
	public function sqlqcachehits(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlqcachehite&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['qcachehits'] = $this->model->mysql_qcachehits_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/qcachehits',$data);	
	}
	
	// mysql 缓存碎片率
	public function sqlqcachescrap(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlqcachescrap&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['qcachescrap'] = $this->model->mysql_qcachescrap_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/qcachescrap',$data);	
	}
	
	// mysql 缓存访问率
	public function sqlqcachevisite(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlqcachevisite&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['qcachevisite'] = $this->model->mysql_qcachevisite_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/qcachevisite',$data);	
	}
	
	// mysql 吞吐率详情
	public function sqlopcounters(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlopcounters&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['opcounters'] = $this->model->mysql_opcounters_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/opcounters',$data);	
	}
	
	// mysql 表锁定
	public function sqllocktable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqllocktable&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['locktable'] = $this->model->mysql_locktable_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/locktable',$data);	
	}
	
	// mysql 流量图
	public function sqlflowchart(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlflowchart&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['flowchart'] = $this->model->mysql_flowchart_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/flowchart',$data);	
	}
	
	//  mysql 缓存查询数
	public function sqlquerycache(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlquerycache&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['querycache'] = $this->model->mysql_querycache_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/querycache',$data);	
	}
	
	// mysql 失败连接数
	public function sqlfailedcon(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlfailedcon&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['failedcon'] = $this->model->mysql_failedcon_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/failedcon',$data);	
	}
	
	// mysql 查询数量
	public function sqlquestions(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlquestions&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['questions'] = $this->model->mysql_questions_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/questions',$data);	
	}
	
	
	public function sqlresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=sqlresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/mysql/response',$data);	
	}

	
	
	/***************************************   Http 画图中心       *********************************************************/
	//http 可用率  
	public function httpable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=httpable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/http/httpable',$data);
	}
	
	public function httpresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=httpresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/http/response',$data);	
	}
	
	
	/*************************************  Ping 画图中心        **************************************************************/
	
	public function pingable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=pingable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/ping/pingable',$data);
		
	}
	
	
	public function pingresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=pingresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/ping/response',$data);	
	}
	
	
	/*************************************   Tcp 画图中心        **************************************************************/
	
	public function  tcpable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=tcpable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/tcp/tcpable',$data);
		
	}
	
	public function tcpresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=tcpresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/tcp/response',$data);	
	}
	
	/*************************************  Udp 画图中心        **************************************************************/
	
	public function udpable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=udpable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/udp/udpable',$data);
		
	}
	
	
	public function udpresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=udpresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/udp/response',$data);	
	}
	
	
	
	/*************************************  Ftp 画图中心        **************************************************************/
	
	public function ftpable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ftpable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/ftp/ftpable',$data);
		
	}
	
	public function ftpresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=ftpresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/ftp/response',$data);	
	}
	
	
	/*************************************  Dns 画图中心        **************************************************************/
	
	public function dnsable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=dnsable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/dns/dnsable',$data);
		
	}
	
	public function dnsresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=dnsresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/dns/response',$data);	
	}
	
	
	/*********************************   custom(自定义服务器) 画图中心     ****************************************/
	
	public function customable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=customable&itemid=".$itemid;
		$data['available'] = $this->model->monitor_available_analyze($itemid,$stime,$etime);
		$data['ablepie'] = $this->model->monitor_available_analyze_pie($itemid,$stime,$etime);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/custom/customable',$data);
		
	}
	
	public function cusresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['itemid'])){
			header("Location: index.php?c=monitor&a=monitorlist", TRUE, 302);
		}
		$itemid = $_GET['itemid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['itemid'] = $itemid ;
		$data['url']= "index.php?c=paint&a=cusresponse&itemid=".$itemid;
		$monitordata = $this->model->monitor_data_select_itemid($itemid,$stime,$etime);
		$data['response'] = $this->model->monitor_response_analyze($monitordata);
		$data['itemname'] = $this->model->monitor_iteminfo_fetch($itemid);
		$this->loadview('paint/custom/response',$data);	
	}
	
	
	/***********************************   server(ip)  画图中心        ***************************************/
	
	
	
	public function serverable(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serverable&ipid=".$ipid;
		$data['available'] = $this->model->server_available_analyze($ipid,$stime,$etime);
		$data['ablepie'] = $this->model->server_available_analyze_pie($ipid,$stime,$etime);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
 		$this->loadview('paint/server/serverable',$data);
		
	}
	
	public function serresponse(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serresponse&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['response'] = $this->model->server_response_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/response',$data);	
	}
	
	public function sermemory(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=sermemory&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['memory'] = $this->model->server_memory_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/memory',$data);	
	}
	
	
	public function serlogins(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serlogins&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['logins'] = $this->model->server_logins_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/logins',$data);
	}
	
	
	public function serloadstat(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serloadstat&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['loadstat'] = $this->model->server_loadstat_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/loadstat',$data);
	}
	
	
	public function serprocess(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serprocess&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['process'] = $this->model->server_processnum_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/process',$data);
	}
	
	
	public function serdiskstat(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serdiskstat&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['diskstat'] = $this->model->server_diskstat_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/diskstat',$data);		
	}
	
	
	
	public function serconstat(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serconstat&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['constat'] = $this->model->server_constat_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/constat',$data);
		
	}
	
	
	public function sernetwork(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=sernetwork&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['network'] = $this->model->server_network_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/network',$data);
	}
	
	
	public function serusedcpu(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serusedcpu&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['usedcpu'] = $this->model->server_usedcpu_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/usedcpu',$data);
	}
	
	public function serdiskio(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['ipid'])){
			header("Location: index.php?c=device&a=listindex", TRUE, 302);
		}
		$ipid = $_GET['ipid'] ;
		$stime = date("Y-m-d",strtotime('today'));
		$etime = date("Y-m-d H:i:s",time());	
		if(isset($_GET['period'])){
			$stime = $this->model->monitor_get_stime($_GET['period']);
			$etime = $this->model->monitor_get_etime($_GET['period']);
		}else if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
		}
		$data['time'] = $stime." 至  ".date("Y-m-d",strtotime($etime));
		$data['ipid'] = $ipid ;
		$data['url']= "index.php?c=paint&a=serdiskio&ipid=".$ipid;
		$serverdata = $this->model->server_data_select_ipid($ipid,$stime,$etime);
		$data['diskio'] = $this->model->server_diskio_analyze($serverdata);
		$data['ip'] = $this->model->server_ipinfo_fetch($ipid);
		$data['ostype'] = $this->model->get_system_type($ipid);
		$data['iplist'] = $this->model->get_all_iplist();
		$this->loadview('paint/server/diskio',$data);
	}
	
	
	/*****************************test paint deal time *******************/
	
	public function test(){
		
		$rs = $this->model->testaa();
		echo $rs ;
	}
}