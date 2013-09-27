<?php
class PersonCenter extends osa_controller{
	
	private $model = null;
	private $page = null;
	private $devdate = array();
	
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mpersoncenter');
		$this->page = $this->loadmodel('mpage');
		$this->date = array(
			'today'        => date("Y-m-d H:i:s" ,strtotime('today')),
			'yesterday'    => date("Y-m-d H:i:s" ,strtotime('-1 day')),
			'lastweek'     => date("Y-m-d H:i:s" ,strtotime('-7 day')) ,
			'last2week'    => date("Y-m-d H:i:s" ,strtotime('-15 day')) 
		);
	}
	
	public function index(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 100
		if(!osa_checkstr($_SESSION['login_role'],100)){
			header("Location: index.php?c=personcenter&a=permiterror&left=list", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['msgstart']);
			unset($_SESSION['msgend']);
		}
		if(isset($_POST['starttime'])){
			$_SESSION['msgstart'] = $_POST['starttime'];
			$_SESSION['msgend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['msgstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['msgstart'];
			$endtime = date('Y-m-d H:i:s',strtotime($_SESSION['msgend'])+86399);
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
		$data['menu'] = 'personcenter';//控制头部菜单栏。
		$data['left'] = 'list';
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['msginfo'] = $this->model->getAlarmMsg($perpage ,$offset ,$starttime ,$endtime );
		$num = $this->model->getAlarmmsgNum($starttime ,$endtime );
		$url = 'index.php?c=personcenter&a=index';
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('personcenter/list',$data);
	}
	
	/**
	 * 修改密码
	 */
	public function changePassword(){
	   if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
	    //权限判断 101
		if(!osa_checkstr($_SESSION['login_role'],101)){
			header("Location: index.php?c=personcenter&a=permiterror&left=changepasswd", TRUE, 302);
		}
	   $oldpwd = trim($_POST['oldpwd']);
	   $newpwd = $_POST['newpwd'];
	   $re_newpwd = $_POST['re_newpwd'];
	   if(!empty($oldpwd) && !empty($newpwd)){
		   if($newpwd==$re_newpwd){
			  $this->model->updatePassword($oldpwd,$newpwd);
			  $title = "修改密码";
			  $info = $_SESSION['username']."修改密码成功,时间：".date("Y-m-d H:i:s");
			  $this->model->saveSysLog(7,$title,$info,$_SESSION['username']);
			  header("Location:index.php?c=login&a=logout",TRUE,302);
		   }else{
			  echo "新密码输入不一致！";
		   }
	   }
	   $data['menu'] = 'personcenter';//控制头部菜单栏。
	   $data['left'] = 'changepasswd';
	   $this->loadview('personcenter/changepassword',$data);
	}
	
	/**
	 * 关于我
	 */
	public function aboutMe(){
	    if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 102
		if(!osa_checkstr($_SESSION['login_role'],102)){
			header("Location: index.php?c=personcenter&a=permiterror&left=aboutme", TRUE, 302);
		}
		if($_POST){
			$userinfo=array();
			$userinfo['oRealName'] = trim($_POST['username']);
			$userinfo['oPhone'] = trim($_POST['tel']);
			$userinfo['oEmail'] = trim($_POST['email']);
			$this->model->updateMyInfo($_SESSION['id'],$userinfo);
			$title = "个人信息编辑";
			$info = $_SESSION['username']."个人信息编辑成功,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(7,$title,$info,$_SESSION['username']);
		 }
	    $data['menu'] = 'personcenter';//控制头部菜单栏。
	    $data['left'] = 'aboutme';
	    $data['personinfo']=$this->model->getMyInfo($_SESSION['id']);
	    $this->loadview('personcenter/aboutme',$data); 
	}
	
	/**
	 * 快捷菜单
	 */
	public function shortCut(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 103
		if(!osa_checkstr($_SESSION['login_role'],103)){
			header("Location: index.php?c=personcenter&a=permiterror&left=shortcut", TRUE, 302);
		}
	    $data['menu'] = 'personcenter';//控制头部菜单栏。
	    $data['left'] = 'shortcut';
	    $data['config'] = $this->loadconfig('osa_config_shortcut');
	    $data['user'] = $this->model->getMyInfo($_SESSION['id']);
	    $this->loadview('personcenter/shortcut',$data);
	}
	
	/**
	 * 快捷菜单设置
	 */
	public function setShortCut(){
		$shortcut = $_POST['shortcut'];
		$this->model->updateShortCut($_SESSION['id'],$shortcut);
		$title = "快捷菜单设置";
		$info = $_SESSION['username']."快捷菜单设置成功,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(7,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 删除告警信息
	 */
	public function delalarm(){
		//权限判断
		if(!osa_checkstr($_SESSION['login_role'],104)){
			//header("Location: index.php?c=device&a=permiterror&left=devlist", TRUE, 302);
			echo 'no_permissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key){
			$this->model->delAlarms($key);
		}
		$title = "删除告警信息";
		$info = $_SESSION['username']."删除告警信息成功，共删除告警信息".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(7,$title,$info,$_SESSION['username']);
		echo 'success';
	}

	
	/**
	 * 权限错误
	 */
	public function permiterror(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['menu'] = $_GET['c'];
		$data['left'] = $_GET['left'];
		$this->loadview("personcenter/permiterror",$data);
	}
	
}