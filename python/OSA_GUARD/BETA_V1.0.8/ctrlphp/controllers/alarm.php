<?php
class alarm extends osa_controller{

	
	private $model = '';
	private $page = null;
	
	public function __construct(){
		
		parent::__construct();
		$this->model = $this->loadmodel('malarm');
		$this->page = $this->loadmodel('mpage');
		if (!isset($_SESSION)){
			session_start();
		}
		$_SESSION['header'] = 'alarm';
	}
		
	
	/**
	 * 未通知的监控项目报警信息页面
	 */
	public function itemalarm(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->itemalarm_init_isread_isNotNotice();
		
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		
		$stime = $etime = '';
		if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
			$today = date("Y-m-d");
			if($etime == $today){
				$etime = date("Y-m-d H:i:s",time());
			}
			if($stime == $etime){
				$etime = date("Y-m-d H:i:s",strtotime($etime)+24*60*60-1);
			}
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$alarmdata = $this->model->itemalarm_select_not_notice_page($stime ,$etime ,$perpage ,$offset);
		//echo $alarmdata ;return ;
		$data['alarminfo'] = $this->model->alarm_data_deal($alarmdata);
		$num = $this->model->itemalarm_select_not_notice_nums($stime ,$etime);
		
		$url = 'index.php?c=alarm&a=itemalarm';
		if(!empty($stime)&&!empty($etime)){
			$pageurl =$url."&stime=".$stime."&etime=".$etime ;
		}else{
			$pageurl = $url ;
		}
		$data['itemalarm_num'] = $this->model->itemalarm_isnotread_isNotNotice_num();		
		$data['itemalarmd_num'] =$this->model->itemalarm_isnotread_isNotice_num();
		$data['serveralarm_num'] =$this->model->serveralarm_isnotread_isNotNotice_num();
		$data['serveralarmd_num'] =$this->model->serveralarm_isnotread_isNotice_num();
		
		$data['stime'] = $stime ;
		$data['etime'] = $etime ;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('alarm/itemalarm',$data);	
	}
	
	
	/**
	 * 已通知监控项目报警信息
	 */
	public function itemalarmed(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->itemalarm_init_isread_isNotice();
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		
		$stime = $etime = '';
		if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
			$today = date("Y-m-d");
			if($etime == $today){
				$etime = date("Y-m-d H:i:s",time());
			}
			if($stime == $etime){
				$etime = date("Y-m-d H:i:s",strtotime($etime)+24*60*60-1);
			}
			
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$alarmdata = $this->model->itemalarm_select_noticed_page($stime ,$etime ,$perpage ,$offset);
		$data['alarminfo'] = $this->model->alarm_data_deal($alarmdata);
		$num = $this->model->itemalarm_select_noticed_nums($stime ,$etime);
		$url = 'index.php?c=alarm&a=itemalarmed';
		if(!empty($stime)&&!empty($etime)){
			$pageurl =$url."&stime=".$stime."&etime=".$etime ;
		}else{
			$pageurl = $url ;
		}
		$data['itemalarm_num'] = $this->model->itemalarm_isnotread_isNotNotice_num();		
		$data['itemalarmd_num'] =$this->model->itemalarm_isnotread_isNotice_num();
		$data['serveralarm_num'] =$this->model->serveralarm_isnotread_isNotNotice_num();
		$data['serveralarmd_num'] =$this->model->serveralarm_isnotread_isNotice_num();
		$data['stime'] = $stime ;
		$data['etime'] = $etime ;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('alarm/itemalarmed',$data);
	}
	
	
	/**
	 * 未通知 服务器监控报警信息
	 */
	public function serveralarm(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->serveralarm_init_isread_isNotNotice();
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		
		$stime = $etime = '';
		if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
			$today = date("Y-m-d");
			if($etime == $today){
				$etime = date("Y-m-d H:i:s",time());
			}
			if($stime == $etime){
				$etime = date("Y-m-d H:i:s",strtotime($etime)+24*60*60-1);
			}
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$alarmdata = $this->model->serveralarm_select_not_notice_page($stime ,$etime ,$perpage ,$offset);
		$data['alarminfo'] = $this->model->alarm_data_deal($alarmdata);
		$num = $this->model->serveralarm_select_not_notice_nums($stime ,$etime);
		$url = 'index.php?c=alarm&a=serveralarm';
		if(!empty($stime)&&!empty($etime)){
			$pageurl =$url."&stime=".$stime."&etime=".$etime ;
		}else{
			$pageurl = $url ;
		}
		$data['itemalarm_num'] = $this->model->itemalarm_isnotread_isNotNotice_num();		
		$data['itemalarmd_num'] =$this->model->itemalarm_isnotread_isNotice_num();
		$data['serveralarm_num'] =$this->model->serveralarm_isnotread_isNotNotice_num();
		$data['serveralarmd_num'] =$this->model->serveralarm_isnotread_isNotice_num();	
		$data['stime'] = $stime ;
		$data['etime'] = $etime ;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('alarm/serveralarm',$data);
	}
	
	
	/**
	 * 已通知服务器监控报警信息
	 */
	public function serveralarmed(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->model->serveralarm_init_isread_isNotice();		
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		
		$stime = $etime = '';
		if(isset($_GET['stime'])&&isset($_GET['etime'])){
			$stime = $_GET['stime'];
			$etime = $_GET['etime'];
			$today = date("Y-m-d");
			if($etime == $today){
				$etime = date("Y-m-d H:i:s",time());
			}
			if($stime == $etime){
				$etime = date("Y-m-d H:i:s",strtotime($etime)+24*60*60-1);
			}
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$alarmdata = $this->model->serveralarm_select_noticed_page($stime ,$etime ,$perpage ,$offset);
		$data['alarminfo'] = $this->model->alarm_data_deal($alarmdata);
		$num = $this->model->serveralarm_select_noticed_nums($stime ,$etime);
		$url = 'index.php?c=alarm&a=serveralarmed';
		if(!empty($stime)&&!empty($etime)){
			$pageurl =$url."&stime=".$stime."&etime=".$etime ;
		}else{
			$pageurl = $url ;
		}
		$data['itemalarm_num'] = $this->model->itemalarm_isnotread_isNotNotice_num();		
		$data['itemalarmd_num'] =$this->model->itemalarm_isnotread_isNotice_num();
		$data['serveralarm_num'] =$this->model->serveralarm_isnotread_isNotNotice_num();
		$data['serveralarmd_num'] =$this->model->serveralarm_isnotread_isNotice_num();
		$data['stime'] = $stime ;
		$data['etime'] = $etime ;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('alarm/serveralarmed',$data);
	}
	
	/**
	 * notiset view
	 */
	public function notiset(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['notiset'] = $this->model->notiset_select();
		$data['emailset'] = $this->model->emailset_select();
		$data['smsset'] = $this->model->smsset_select();
		$data['msnset'] = $this->model->msnset_select();
		$data['gtalkset'] = $this->model->gtalkset_select();			
		$this->loadview('alarm/notiset',$data);
	}
}