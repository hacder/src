<?php
class Login extends osa_controller{
	private $model = null;
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('logins');
		if(!isset($_SESSION)){
			session_start();
		}
	}
	
	public function index(){
		
		if(isset($_SESSION['username'])){
			
			//表示已经登录
			header("Location: index.php?c=home&a=index", TRUE, 302);
		}
		$this->loadview('login');//进入登录页面
	}
	
	public function checklogin(){
		
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$remember = $_POST['remember'];
		if($remember == 1){//说明要记住用户名
			setcookie('username',$username,time()+3600);
  			setcookie('remember',$remember,time()+3600);
		}
		$block_status = $this->model->is_blockIp();
		if($block_status === 'block'){
			exit('ip_block');
		}
		$login_status =  $this->model->login_status($username, $password);
		if($login_status === 'success'){
			//标记已经登录
			$this->model->loginOk($_SESSION['id'],1,$_SESSION['users_num']);
			$params = array('key'=>OSA_SYSTEM_KEY,'type'=>'users');
			$as = osa_restaction('POST',$params,OSA_WEBSERVER_DOMAIN.'/interface.php');
			//进入正确登录页面		
			//header("Location: index.php?c=home&a=index", TRUE, 302);
			exit('success');
		}else if($login_status == 'num_error'){
			$num = OSA_TRY_LOGIN_NUM;
			echo $num.'次';
			return ;
		}else if($login_status == 'login_error'){
			exit('login_error');
		}
	}
	
	public function loginfailure(){
		echo 'login error';
	}
	
	public function loginsuccess(){
		echo 'login success';
	}
	
	public function logout(){
		if(!isset($_SESSION)){
			session_start();
		}
		$this->model->loginOk($_SESSION['id'],0);
		$title = "用户注销";
		$info = $_SESSION['username']."用户注销成功,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(1,$title,$info,$_SESSION['username']);
		session_unset();//释放$_SESSION变量
		session_destroy();//删除session文件,释放session_id
		header("Location: index.php?c=login&a=index", TRUE, 302);
	}
}