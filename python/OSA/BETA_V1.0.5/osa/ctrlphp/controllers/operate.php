<?php
class operate extends osa_controller{
	
	private $model = null;
	private $page = null;
	private $date = array();
	/**构造函数**/
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('graph/mgraph');
		//$this->page = $this->loadmodel('mpage');
		$this->date = array(
			'today'        => date("Y-m-d H:i:s" ,strtotime('today')),
			'yesterday'    => date("Y-m-d H:i:s" ,strtotime('-1 day')),
			'lastweek'     => date("Y-m-d H:i:s" ,strtotime('-7 day')),
			'last2week'    => date("Y-m-d H:i:s" ,strtotime('-15 day')) 
		);
	}
	
	/**
	 * 图形分析中心
	 */
	public function graphcenter(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 120
		$type = isset($_GET['type'])?$_GET['type']:'memory';
		if(!osa_checkstr($_SESSION['login_role'],120)){
			header("Location: index.php?c=operate&a=permiterror&left=".$type."graph", TRUE, 302);
		}else{
			$data['menu'] = 'operate';//控制头部菜单栏。
			if(isset($_GET['date'])||isset($_GET['clean'])){
				unset($_SESSION['graphstart']);
				unset($_SESSION['graphend']);
			}
			//
			if(isset($_POST['serverid'])){
				$_SESSION['oipid'] = $_POST['serverid'];
			}
			//获取127.0.0.1 的ipid
			$ip_id = $this->model->getIdbyIp();
			$data['ipid']=$id = isset($_SESSION['oipid'])?$_SESSION['oipid']:$ip_id;
			$num = $this->model->getMonitorNums($id);
			if($num == 0){
				$errormsg = "该IP地址数据库中没有数据;<b>可能原因是：</b><font color=red>该IP地址暂无数据，
							 请检查后台采集数据进程是否正常！</font>";
				$data['errormsg'] = $errormsg;			
			}
			$data['ipinfo'] = $ipinfo = $this->model->selectIpinfo();
			$data['url'] = "index.php?c=operate&a=graphcenter&type=".$type;
			$highcharts = $this->loadmodel('graph/highcharts');
			$flag = '1'	;
			if(isset($_POST['starttime'])){
				$_SESSION['graphstart'] = $_POST['starttime'];
				$_SESSION['graphend'] = $_POST['endtime'];
			}
			if(isset($_SESSION['graphstart'])){ //说明是自定义搜索
				$starttime = $_SESSION['graphstart'];
				$endtimes = $_SESSION['graphend'];
				$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
			}else if(isset($_GET['date'])){ //不是自定义搜索
				if($_GET['date']== 'yesterday'){
					$starttime = date("Y-m-d",strtotime("-1 day"));
					$endtime = date("Y-m-d",time());
				}else{
					$datename = trim($_GET['date'],' ');
					$starttime = $this->date[$datename];
					$endtime = date("Y-m-d H:i:s" ,time());
				}
				$data['picker'] = $_GET['date'];
			}else{//默认
				//$starttime = OSA_DEFAULT_STARTTIME;
				$starttime = date("Y-m-d H:i:s",time()-7*24*3600);
				$endtime = date("Y-m-d H:i:s" ,time());
			}
			switch($type){
				case 'memory':
					$data['left'] = 'memorygraph';
					$typename = 'memory';
					break ;
				case 'load':
					$data['left'] = 'loadgraph';
					$typename = 'load';
					break ;
				case 'disk':
					$data['left'] = 'diskgraph';
					$typename = 'disk';
					break ;
				case 'process':
					$data['left'] = 'processgraph';
					$typename = 'process';
					break ;
				case 'user':
					$data['left'] = 'usergraph';
					$typename = 'login';
					break ;
				case 'network':
					$data['left'] = 'networkgraph';
					$typename = 'network';
					break ;
				case 'constat':
					$data['left'] = 'constatgraph';
					$typename = 'constat';
					break ;
				default:
					$data['left'] = 'memorygraph';
					$typename = 'memory';
					break ;
			}
			$highcharts->initialize($id);
			$drawlist = $highcharts->getDrawlist($typename);	
			$ip = $highcharts->getIp();
			$newAlldata = $highcharts->getNewalldata($id ,$starttime ,$endtime);
			$linedatalist = $highcharts->getLinedatalist($newAlldata);
			$highcharts->countDatalist($linedatalist);
			$lineavg = $highcharts->getLineavg();
			$drawdatalist = $highcharts->getDrawdatalist($linedatalist ,$flag);
			$Mindata = $highcharts->getMindata();
			$drawtoline = $highcharts->getDrawtoline();
			$xcategories =	$highcharts->get_xcategories($starttime,$Mindata);
			//x轴时间间隔	
			$ytitle = $highcharts->get_ytitle($divname,$drawtoline,$lineavg);
			
			foreach($drawlist as $draw_value){
			    $graphstr .= "<tr><td>";
			    $ytitle = $highcharts->get_ytitle($draw_value,$drawtoline,$lineavg);
				$graphstr .=$this->model->osa_draw($draw_value,$ip,$drawdatalist,$Mindata,$xcategories ,$ytitle,$starttime,$endtime);
				$graphstr .= "</td></tr>";
			}
			$data['graph'] = $graphstr;
			$data['starttime'] = date("Y-m-d",strtotime($starttime));
			$data['endtime'] = date("Y-m-d",strtotime($endtime));
			$this->loadview('operate/graphcenter' ,$data);
		}
	}
	
	/**
	 * 图形分析中心
	 */
	public function memorygraph(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'operate';//控制头部菜单栏。
		$data['left'] = 'memorygraph';
		$this->loadview('operate/memorygraph' ,$data);
	}
	
	/**
	 * 日常操作报表
	 */	
	public function dailyreport(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!osa_checkstr($_SESSION['login_role'],120)){
			header("Location: index.php?c=operate&a=permiterror&left=dailyreport", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'operate';//控制头部菜单栏。
		$data['left'] = 'dailyreport';
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['dailystart']);
			unset($_SESSION['dailyend']);
		}
		if(isset($_POST['starttime'])){
			$_SESSION['dailystart'] = $_POST['starttime'];
			$_SESSION['dailyend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['dailystart'])){ //说明是自定义搜索
			$starttime = $_SESSION['dailystart'];
			$endtimes = $_SESSION['dailyend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time());
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time());
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time());
		}
		$rs = $this->model->selectLogType();
		$data['xAjaxstr'] = $this->model->getLogType($rs);
		$data['yAjaxstr'] = $this->model->getTypeNum($rs ,$starttime ,$endtime);
		$data['starttime'] = date("Y-m-d",strtotime($starttime));
		$data['endtime'] = date("Y-m-d",strtotime($endtime));
		$data['url'] = "index.php?c=operate&a=dailyreport";
		$this->loadview('operate/dailyreport' ,$data);
	}
	
	/**
	 * 故障操作报表
	 */
	public function faultreport(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!osa_checkstr($_SESSION['login_role'],120)){
			header("Location: index.php?c=operate&a=permiterror&left=faultreport", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'operate';//控制头部菜单栏。
		$data['left'] = 'faultreport';
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['faultstart']);
			unset($_SESSION['faultend']);
		}
		if(isset($_POST['starttime'])){
			$_SESSION['faultstart'] = $_POST['starttime'];
			$_SESSION['faultend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['faultstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['faultstart'];
			$endtimes = $_SESSION['faultend'];
			$endtime = date('Y-m-d H:i:s',strtotime($endtimes)+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time());
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time());
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time());
		}
		$rs = $this->model->selectLogType();
		//$data['xAjaxstr'] = $this->model->getLogType($rs);
		$data['yAjaxstr'] = $this->model->getFaultNum($starttime ,$endtime);
		$data['starttime'] = date("Y-m-d",strtotime($starttime));
		$data['endtime'] = date("Y-m-d",strtotime($endtime));
		$data['url'] = "index.php?c=operate&a=faultreport";
		$this->loadview('operate/faultreport' ,$data);
	}
	
	/**
	 * 设备资费报表
	 */
	public function devicereport(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!osa_checkstr($_SESSION['login_role'],120)){
			header("Location: index.php?c=operate&a=permiterror&left=devicereport", TRUE, 302);
		}
		$data['menu'] = 'operate';//控制头部菜单栏。
		$data['left'] = 'devicereport';
		if(isset($_POST['selectyear'])){
			$_SESSION['selectyear'] = $_POST['selectyear'];
		}
		$year = isset($_SESSION['selectyear'])?$_SESSION['selectyear']:'2012';
		$datearr = $this->model->createDateArr($year);
		$tgprice = $this->model->getTgPrice($year);
		$seris1 = $seris2 ='';
		for($i=0;$i<12;$i++){
			$rs = $this->model->getDevPrice($datearr[$i] ,$datearr[$i+1]);
			$seris1 .=empty($rs[0]['oDevPrice'])?'0,':$rs[0]['oDevPrice'].',';
			$tgprice += empty($rs[0]['oDevTgPrice'])?0:$rs[0]['oDevTgPrice'];
			$seris2 .= $tgprice.',';
		}
		$data['seris1'] = trim($seris1,',');
		$data['seris2'] = trim($seris2,',');
		$data['year'] = $year ;
 		$data['url'] = "index.php?c=operate&a=devicereport";
		$this->loadview('operate/devicereport' ,$data);
	}
	
	/**
	 * 
	 */
	public function permiterror(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['menu'] = $_GET['c'];
		$data['left'] = $_GET['left'];
		$this->loadview("operate/permiterror",$data);
	}
}