<?php
class account extends osa_controller{
	
	private $model = null;
	private $page = null;
	private $date = array();
	/**构造函数**/
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('maccount');
		$this->page = $this->loadmodel('mpage');
		$this->date = array(
			'today'        => date("Y-m-d H:i:s" ,strtotime('today')),
			'yesterday'    => date("Y-m-d H:i:s" ,strtotime('-1 day')),
			'lastweek'     => date("Y-m-d H:i:s" ,strtotime('-7 day')) ,
			'last2week'    => date("Y-m-d H:i:s" ,strtotime('-15 day')) 
		);
	}
	
	/********************************************用户列表********************************************************/
	
	/**
	 * 用户列表
	 */
	public function userlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 70
		if(!osa_checkstr($_SESSION['login_role'],70)){
			header("Location: index.php?c=account&a=permiterror&left=userlist", TRUE, 302);
		}
		if(isset($_GET['clean'])){
			unset($_SESSION['name']);
			unset($_SESSION['role']);
			unset($_SESSION['status']);
		}
		if(isset($_POST['keyword'])){
			$_SESSION['name'] = $_POST['keyword'];
		}
		if(isset($_POST['role'])){
			$_SESSION['role'] = $_POST['role'];
		}
		if(isset($_POST['status'])){
			$_SESSION['status'] = $_POST['status'];
		}
		$data['menu'] = 'account';//控制头部菜单栏。
		$data['left'] = 'userlist';
		$data['roles'] = $this->model->getRolesInfo();
		$name = isset($_SESSION['name'])?$_SESSION['name']:'';
		$role = isset($_SESSION['role'])?$_SESSION['role']:'';
		$status = isset($_SESSION['status'])?$_SESSION['status']:'';
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['userinfo'] = $this->model->selectUsersinfo($perpage ,$offset ,$name ,$role ,$status );
		$num = $this->model->getNumfromUsers($name ,$role ,$status);
		$url = 'index.php?c=account&a=userlist';
		//$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$data['url'] = $pageurl = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('account/userlist' ,$data);
	}
	
	/***
	 * 添加新用户
	 */
	public function useradd(){
		if(!isset($_POST['username'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 71
			if(!osa_checkstr($_SESSION['login_role'],71)){
				header("Location: index.php?c=account&a=permiterror&left=userlist", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'account';//控制头部菜单栏。
			$data['left'] = 'userlist';
			$data['roles'] = $this->model->getRolesInfo();
			$this->loadview('account/useradd' ,$data);
		}else{
			$passwd = osa_passwdhash($_POST['passwd']).substr(osa_passwdhash(mb_substr($_POST['username'],0,2,'utf-8')),3,6);
			$userinfo = array(
				'oUserName'=>$_POST['username'],
				'oRealName'=>$_POST['username'],
				'oPassword'=>$passwd,
				'oRoleid'=>$_POST['rid'],
				'oEmail'=>$_POST['email'],
				'oPhone'=>$_POST['phone'],
				'oDutyDate'=>$_POST['workdate'],
				'oDutyTime'=>$_POST['worktime'],
				'oNickName'=>$_POST['nickname'],
				'oSignature'=>$_POST['sign'],
				'oStatus'=>1
			);
			$rs = $this->model->insertUserInfo($userinfo);
			if($rs){
				echo 'success';return ;
				$title = "添加新用户";
				$info = $_SESSION['username']."添加新用户".$_POST['username']."成功,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
			}else{
				echo 'failure';return ;
				$title = "添加新用户";
				$info = $_SESSION['username']."添加新用户".$_POST['username']."失败，可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
			}
		}
	}
	
	/***
	 * 编辑用户
	 */
	public function useredit(){
		if(!isset($_POST['username'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 72
			if(!osa_checkstr($_SESSION['login_role'],72)){
				header("Location: index.php?c=account&a=permiterror&left=userlist", TRUE, 302);
			}
			if(!isset($_GET['id'])){
				header("Location: index.php?c=account&a=userlist", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'account';//控制头部菜单栏。
			$data['left'] = 'userlist';
			$data['hideurl'] = "index.php?c=account&a=useredit&id=".$_GET['id'];
			$data['user'] = $this->model->getUserByid($_GET['id']);
			$data['roles'] = $this->model->getRolesInfo();
			$this->loadview('account/useredit' ,$data);
		}else{
			$userinfo = array(
				'oRoleid'=>$_POST['rid'],
				'oEmail'=>$_POST['email'],
				'oPhone'=>$_POST['phone'],
				'oDutyDate'=>$_POST['workdate'],
				'oDutyTime'=>$_POST['worktime'],
				'oNickName'=>$_POST['nickname'],
				'oSignature'=>$_POST['sign']
			);
			$rs = $this->model->updateUserInfo($userinfo,$_GET['id']);
			$title = "编辑用户";
			$info = $_SESSION['username']."编辑用户".$_POST['username']."成功,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
			echo 'success';return ;
		}
	}
	/**
	 * 用户名验证：登录用保证唯一值
	 */
	public function checkname(){
		$username = $_POST['username'];
		$rs = $this->model->verifiUsername($username);
		if($rs){
			echo 'is_exists';return ;//说明已存在
		}
		echo 'success';return ;
	}
	
	/**
	 * 邮箱验证：登录用保证唯一值
	 */
	public function checkemail(){
		$email = $_POST['email'];
		$rs = $this->model->verifiEmail($email);
		if($rs){
			echo 'is_exists';return ;//说明已存在
		}
		echo 'success';return ;
	}
	
	/**
	 * 用户禁用
	 */
	public function stopUsers(){
		$uid = $_GET['id'];
		$status = 0;
		$this->model->setUserStatus($uid,$status);
		$title = "禁用用户";
		$info = $_SESSION['username']."禁用用户成功,用户id：$uid,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 用户开启
	 */
	public function openUsers(){
		$uid = $_GET['id'];
		$status = 1;
		$this->model->setUserStatus($uid,$status);
		$title = "启用用户";
		$info = $_SESSION['username']."启用用户成功,用户id：$uid,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 删除用户
	 */
	public function delUsers(){
		//权限判断 73
		if(!osa_checkstr($_SESSION['login_role'],73)){
			echo 'no_permissions';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key ){
			$this->model->delUserInfo($key);
		}
		$title = "删除用户";
		$info = $_SESSION['username']."删除用户成功,共删除用户".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 初始化密码
	 */
	public function initPasswd(){
		$uid = $_POST['id'];
		$uname = trim($_POST['uname']);
		$rs = $this->model->initPasswd($uid,$uname);
		$title = "初始化用户密码";
		$info = $_SESSION['username']."初始化用户密码成功,用户id：$uid,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	/****************************************************角色处理************************************************/
	/***
	 * 角色列表
	 */
	public function rolelist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 80
		if(!osa_checkstr($_SESSION['login_role'],80)){
			header("Location: index.php?c=account&a=permiterror&left=rolelist", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'account';//控制头部菜单栏。
		$data['left'] = 'rolelist';
		//分页处理
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['roleinfo'] = $this->model->selectRolesInfo($perpage ,$offset);
		$num = $this->model->getNumfromRoles();
		$url = 'index.php?c=account&a=rolelist';
		$data['url'] = $pageurl = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('account/rolelist' ,$data);
	}
	
	/**
	 * 添加角色
	 */
	public function roleadd(){
		if(!isset($_POST['rolename'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 81
			if(!osa_checkstr($_SESSION['login_role'],81)){
				header("Location: index.php?c=account&a=permiterror&left=rolelist", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'account';//控制头部菜单栏。
			$data['left'] = 'rolelist';
			$this->loadview('account/roleadd' ,$data);
		}else{
			$roleinfo = array(
				'oRoleName'=>$_POST['rolename'],
				'oDescription'=>$_POST['descript'],
				'oPerArr'=>$_POST['rolestr'],
				'oStatus'=>1
			);
			$rs = $this->model->insertRoleInfo($roleinfo);
			if($rs){
				$title = "添加角色";
				$info = $_SESSION['username']."添加角色成功,角色名称：".$_POST['rolename'].",时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
				echo 'success';return ;
			}else{
				$title = "添加角色";
				$info = $_SESSION['username']."添加角色失败,角色名称：".$_POST['rolename'].",可能原因：数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
				echo 'failure';return ;
			}
		}
	}
	
	/**
	 * 编辑角色
	 */
	public function roleedit(){
		if(!isset($_POST['rolename'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 82
			if(!osa_checkstr($_SESSION['login_role'],82)){
				header("Location: index.php?c=account&a=permiterror&left=rolelist", TRUE, 302);
			}
			if(!isset($_GET['id'])){
				header("Location: index.php?c=account&a=userlist", TRUE, 302);
			}
			$data['menu'] = 'account';//控制头部菜单栏。
			$data['left'] = 'rolelist';
			$data['hideurl'] = "index.php?c=account&a=roleedit&id=".$_GET['id'];
			$data['role'] = $this->model->getRoleByid($_GET['id']);
			$this->loadview('account/roleedit' ,$data);
		}else{
			$roleinfo = array(
				'oDescription'=>$_POST['descript'],
				'oPerArr'=>$_POST['rolestr'],
			);
			$rs = $this->model->updateRoleInfo($roleinfo,$_GET['id']);
			$title = "编辑角色";
			$info = $_SESSION['username']."编辑角色成功,角色名称：".$_POST['rolename'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
			echo 'success';return ;
		}
	}
	
	
	/**
	 * 验证角色名称是否唯一
	 */
	public function checkrname(){
		$rolename = $_POST['rolename'];
		$rs = $this->model->verifiRolename($rolename);
		if($rs){
			echo 'is_exists';return ;//说明已存在
		}
		echo 'success';return ;
	}
	/**
	 * 角色禁用
	 * 禁用角色是要同时禁用角色对应的用户
	 */
	public function stopRoles(){
		$roleid = $_GET['id'];
		$status = 0;
		$this->model->setRoleStatus($roleid,$status);
		$this->model->setUserStatusByRid($roleid);
		$title = "禁用角色";
		$info = $_SESSION['username']."禁用角色成功,角色id：$roleid,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 角色开启
	 */
	public function openRoles(){
		$roleid = $_GET['id'];
		$status = 1;
		$this->model->setRoleStatus($roleid,$status);
		$title = "启用角色";
		$info = $_SESSION['username']."启用角色成功,角色id：$roleid,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/**
	 * 删除roles
	 */
	public function delRoles(){
		//权限判断 83
		if(!osa_checkstr($_SESSION['login_role'],83)){
			echo 'no_permissions';
			return ;
		}
		$arr = $_POST['arr'];
		foreach ($arr as $key ){
			$this->model->delRoleInfo($key);
		}
		$title = "删除角色";
		$info = $_SESSION['username']."删除角色成功,共删除角色".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(5,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	public function permiterror(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['menu'] = $_GET['c'];
		$data['left'] = $_GET['left'];
		$this->loadview("account/permiterror",$data);
	}
}