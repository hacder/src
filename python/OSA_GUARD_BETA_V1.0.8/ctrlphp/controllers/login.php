<?php
class login extends osa_controller{
	
	private $model = null;
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mlogin');
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
			setcookie('username',$username,time()+86400);
  			setcookie('remember',$remember,time()+8640);
		}else{
			setcookie('username','',time()+86400);
  			setcookie('remember',0,time()+86400);
		}

		$login_status =  $this->model->login_status($username, $password);
		if($login_status === 'success'){
			//标记已经登录
			$this->model->loginOk($_SESSION['user_id'],$_SESSION['users_num']);			
			$param = array('userKey'=>OSA_SYSTEM_KEY);
			
			$as = osa_restaction('POST',$param,OSA_WEBSERVER_DOMAIN.'/osa/login.php');
			
			//进入正确登录页面		
			
			exit('success');
		}else if($login_status == 'num_error'){
			$num = OSA_TRY_LOGIN_NUM;
			echo $num.'次';
			return ;
		}else if($login_status == 'login_error'){
			exit('login_error');
		}
	}
	
	
	public function loginout(){
		if(!isset($_SESSION)){
			session_start();
		}
		session_unset();//释放$_SESSION变量
		session_destroy();//删除session文件,释放session_id
		header("Location: index.php?c=login&a=index", TRUE, 302);
	}
}