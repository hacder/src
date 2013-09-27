<?php
class account extends osa_controller{

	
	private $model = null;
	private $page = null;

	public function __construct(){
	
		parent::__construct();
		$this->model = $this->loadmodel('maccount');
		$this->page = $this->loadmodel('mpage');
		
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION['header'] = "account";
	}
	
	
	/*****************************start---  account views  ---start*************************************/
	
	/**
	 * users list views
	 */
	public function userlists(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		$search = '';
		if(isset($_GET['search'])){
			$search = $_GET['search'];
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['userinfo'] = $this->model->users_select_page($search ,$perpage ,$offset);
		$num = $this->model->users_select_num($search);
		$url = 'index.php?c=account&a=userlists';
		if(!empty($search)){
			$pageurl =$url."&search=".$search;
		}else{
			$pageurl = $url ;
		}
		$data['search'] = $search ;
		$data['url'] = $url;
		$data['ajaxurl'] = 'index.php?c=account&a=user_ajax';
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('account/userlist',$data);
	}
	
	
	public function user_ajax(){
	
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		$search = '';
		if(isset($_GET['search'])){
			$search = $_GET['search'];
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['userinfo'] = $this->model->users_select_page($search ,$perpage ,$offset);
		$num = $this->model->users_select_num($search);
		$url = 'index.php?c=account&a=userlists';
		if(!empty($search)){
			$pageurl =$url."&search=".$search;
		}else{
			$pageurl = $url ;
		}
		$data['search'] = $search ;
		$data['url'] = $url;
		$data['ajaxurl'] = 'index.php?c=account&a=user_ajax';
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('account/user_ajax',$data);
	}
	
	
	/**
	 * roles list views
	 */
	public function rolelists(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		$search = '';
		if(isset($_GET['search'])){
			$search = $_GET['search'];
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['roleinfo'] = $this->model->roles_select_page($search,$perpage ,$offset);
		$num = $this->model->roles_select_num($search);
		$url = 'index.php?c=account&a=rolelists';
		if(!empty($search)){
			$pageurl =$url."&search=".$search;
		}else{
			$pageurl = $url ;
		}
		$data['search'] = $search ;
		$data['url'] = $url;
		$data['ajaxurl'] = 'index.php?c=account&a=role_ajax';
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('account/rolelist',$data);
	}

	
	public function role_ajax(){
	
		if(isset($_GET['pagenum'])){
			$_SESSION['pagenum'] = $_GET['pagenum'];
		}
		$search = '';
		if(isset($_GET['search'])){
			$search = $_GET['search'];
		}
		$perpage = isset($_SESSION['pagenum'])?$_SESSION['pagenum']:10;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['roleinfo'] = $this->model->roles_select_page($search,$perpage ,$offset);
		$num = $this->model->roles_select_num($search);
		$url = 'index.php?c=account&a=rolelists';
		if(!empty($search)){
			$pageurl =$url."&search=".$search;
		}else{
			$pageurl = $url ;
		}
		$data['search'] = $search ;
		$data['url'] = $url;
		$data['ajaxurl'] = 'index.php?c=account&a=role_ajax';
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);	
		$this->loadview('account/role_ajax',$data);
	}
	
	/**
	 * users add views
	 */
	public function useradd(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['roleinfo'] = $this->model->roles_select();
		$this->loadview('account/useradd',$data);
	}
	
	
	/**
	 * users edit views
	 */
	public function useredit(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=account&a=userlists", TRUE, 302);
		}
		$id = $_GET['id'];
		$data['userinfo'] = $this->model->users_select_id($id);
		$data['roleinfo'] = $this->model->roles_select();
		$this->loadview('account/useredit',$data);
	}
	
	
	/**
	 * update passwd views
	 */
	public function passsave(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('account/passupdate');
	}
	
	
	/**
	 * roles add views
	 */
	public function roleadd(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('account/roleadd');
	}
	
	
	/**
	 * roles edit views
	 */
	public function roledit(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){
			header("Location: index.php?c=account&a=rolelists", TRUE, 302);
		}
		$id = $_GET['id'];
		$data['roleinfo'] = $this->model->roles_select_id($id);
		$this->loadview('account/roledit',$data);
	}
	
	
	/**
	 * shortcut views
	 */
	public function shortcut(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$userid = $_SESSION['user_id'];
		$data['shortcut'] = $this->model->shortcut_select_userid($userid);
		$this->loadview('account/shortcut',$data);
	}
	
	
	/**
	 * personset views
	 */
	public function personset(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$userid = $_SESSION['user_id'];
		$rs = $this->model->personset_select($userid);
		if($rs){
			$data['persondata'] = $rs ;
			$this->loadview('account/personedit',$data);
		}else{
			$this->loadview('account/personset');
		}
	}
	
	
	/*****************************end---  account views  ---end*************************************/
	
	
	/*****************************start---  account events  ---start*************************************/
	
	/**
	 * users add events
	 */
	public function user_add(){
		
		$roleid = $this->model->roles_select_name(trim($_POST['rolename']));
		$username = trim($_POST['username']);
		$password = osa_passwdhash(trim($_POST['passwd'])).substr(osa_passwdhash(mb_substr($username,0,2,'utf-8')),3,6);
		$users = array(
			'oUserName'=>$username,
			'oRealName'=>trim($_POST['realname']),
			'oPassword'=>$password,
			'oRoleid'=>$roleid,
			'oEmail'=>trim($_POST['email']),
			'oPhone'=>trim($_POST['phone'])
		);
		$this->model->users_insert($users);
		echo 'success'; return;
	}
	
	
	/**
	 * users edit events
	 */
	public function user_edit(){
	
		$roleid = $this->model->roles_select_name(trim($_POST['rolename']));
		$id = trim($_POST['id']);
		$users = array(
			'oUserName'=>trim($_POST['username']),
			'oRealName'=>trim($_POST['realname']),
			'oRoleid'=>$roleid,
			'oEmail'=>trim($_POST['email']),
			'oPhone'=>trim($_POST['phone'])
		);
		$this->model->users_update($id,$users);		
		echo 'success';return;
	}
	
	
	/**
	 * roles add events
	 */
	public function role_add(){

		$roles = array(
			'oRoleName'=>trim($_POST['rolename']),
			'oPerStr'=>trim($_POST['perstr']),
			'oRoleDes'=>trim($_POST['roledes']),
		);
		
		$this->model->roles_insert($roles);
		echo 'success';return ;
	}
	
	
	/**
	 * roles edit events
	 */
	public function role_edit(){
	
		$id = trim($_POST['id']);
		$roles = array(
			'oPerStr'=>trim($_POST['perstr']),
			'oRoleDes'=>trim($_POST['roledes']),
		);
		
		$this->model->roles_update($id,$roles);
		echo 'success';return ;
	}
	
	
	/**
	 * users del events
	 */
	public function user_del(){
	
		$id = trim($_POST['id']);
		$this->model->users_delete($id);
	}
	
	
	/**
	 * users stop events
	 */
	public function user_stop(){
		
		$id = trim($_POST['id']);
		$this->model->users_pause($id);
	}
	
	
	/**
	 * users open events
	 */
	public function user_open(){
	
		$id = trim($_POST['id']);
		$this->model->users_open($id);
	}
	
	
	/**
	 * roles delete events
	 */
	public function role_del(){
	
		$id = trim($_POST['id']);
		$this->model->roles_delete($id);
	}
	
	
	/**
	 * roles stop events
	 */
	public function role_stop(){
	
		$id = trim($_POST['id']);
		$this->model->roles_pause($id);
	}
	
	
	/**
	 * roles open events
	 */
	public function role_open(){
	
		$id = trim($_POST['id']);
		$this->model->roles_open($id);
	}
	
	
	/*****************************end---  account events  ---end*************************************/
	
	
	/*****************************start---  account batch events  ---start*************************************/
	
	/**
	 * users del batch
	 */
	public function user_del_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->users_delete($id);
		}
	}
	
	
	/**
	 * users stop batch
	 */
	public function user_stop_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->users_pause($id);
		}
	}
	
	
	/**
	 * users open batch
	 */
	public function user_open_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->users_open($id);
		}
	}
	
	
	/**
	 * roles del batch
	 */
	public function role_del_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->roles_delete($id);
		}
	}
	
	
	/**
	 * roles stop batch
	 */
	public function role_stop_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
			$this->model->roles_pause($id);
		}
	}
	
	
	/**
	 * roles open batch
	 */
	public function role_open_batch(){
	
		$arr = $_POST['idarr'];
		foreach ($arr as $id){
		
			$this->model->roles_open($id);
		}
	}
	
	
	/*****************************end---  account batch events  ---end*************************************/
	
	
	/*****************************start---  other events  ---start*************************************/
	
	/**
	 * modify password
	 */
	public function password_mod(){

		$id = $_SESSION['user_id'];
		$uname = $_SESSION['username'];
		$passwd = $this->model->passwd_select($id);
		$oldpasswd = osa_passwdhash(trim($_POST['oldpasswd'])).substr(osa_passwdhash(mb_substr($uname,0,2,'utf-8')),3,6);
		if($passwd == $oldpasswd){
			$newpasswd = osa_passwdhash(trim($_POST['newpasswd'])).substr(osa_passwdhash(mb_substr($uname,0,2,'utf-8')),3,6);
			$users = array(
				'oPassword'=>$newpasswd
			);
			$this->model->users_update($id,$users);
			echo 'success';return;
		}else{
			echo 'oldpass-error';return;
		}
		
	}
	
	
	/**
	 * shotcut set
	 */
	public function shortcut_set(){
	
		$id = $_SESSION['user_id'];
		$users = array(
			'oShortCut'=>trim($_POST['shortcut'])
		);
		$this->model->users_update($id,$users);
		echo 'success';	return;
	}
	
	
	/**
	 * username is exist
	 */
	public function username_isexist(){
	
		$name = trim($_POST['username']);
		$result = $this->model->users_select_name($name);
		if($result === true){
			echo 'exist';return ;
		}else{
			echo 'success';return ;
		}
	}
	
	
	/**
	 * email is exist
	 */
	public function email_isexist(){
	
		$email = trim($_POST['email']);
		$result = $this->model->users_select_email($email);
		if($result === true){
			echo 'exist';return;
		}else{
			echo 'success';return;
		}
	}
	
	
	/**
	 * phone is exist
	 */
	public function phone_isexist(){
	
		$phone = trim($_POST['phone']);
		$result = $this->model->users_select_phone($phone);
		if($result === true){
			return 'exist';
		}else{
			return 'no-exist';
		}
	}
	
	
	/**
	 * rolename is exist
	 */
	public function rolename_isexist(){
	
		$name = trim($_POST['rolename']);
		$result = $this->model->roles_select_name($name);
		if($result){
			echo 'exist'; return;
		}else{
			echo 'success';return;
		}
	}
	
	
	/**
	 * init passwd
	 */
	public function password_init(){
		
		$id = trim($_POST['id']);
		$users = $this->model->users_select_id($id);
		$uname = $users[0]['oUserName'];
		$initpass = osa_passwdhash('osapub').substr(osa_passwdhash(mb_substr($uname,0,2,'utf-8')),3,6);
		$userinfo = array(
			'oPassword'=>$initpass
		);
		$this->model->users_update($id,$userinfo);
		echo 'success';return ;
	}
	
	
	/**
	 * update personset
	 */
	public function personset_save(){
		
		$personinfo = array(
			'oEmailSet'=>$_POST['emailset'],
			'oInfoType'=>$_POST['infotype'],
			'oAcceptIp'=>$_POST['acceptip'],
			'oReportType'=>$_POST['reporttype'],
			'oCloseType'=>$_POST['closetype'],
			'oUserid'=>$_SESSION['user_id'],
			'oUserName'=>$_SESSION['username'],
		);
		$userid = $_SESSION['user_id'];
		$rs = $this->model->personset_select($userid);
		if($rs){
			$this->model->personset_update($personinfo,$userid);
		}else{
			$this->model->personset_insert($personinfo);
		}
		echo 'success' ;return ;
	}
	/*****************************end---  other events  ---end*************************************/
	
}