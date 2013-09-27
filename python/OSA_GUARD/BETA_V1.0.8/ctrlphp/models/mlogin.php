<?php
class mlogin extends osa_model{
	
	
	public function __construct(){
		
		parent::__construct();
	}
	
	/**
	 * 判断登录状态
	 * 0：成功、1：失败
	 * Enter description here ...
	 * @param unknown_type $username
	 * @param unknown_type $password
	 */
	public function login_status($username, $password){
		if ( $username != "" && $password != ""){
			if ($rs = $this->check_login_info($username,$password)){
				
				$_SESSION['username']  = $rs[0]['oUserName'];	
				$_SESSION['user_id'] = $rs[0]['id'];
				//$_SESSION['login_role'] = $this->getRoleByUid($rs[0]['id']);
				$_SESSION['users_num'] = $rs[0]['oUsersNum']+1;
				//$_SESSION['login_num'] = 0;											
				return 'success';	
								
			}else{					
										
				return 'login_error';											
			}
		}
	}
	
    
    /**
     * 登录验证
     * Enter description here ...
     * @param $u
     * @param $p
     * @param $db
     */
    public function check_login_info($u='',$p=''){

		$name = ! empty($u) ? $u : '';
		$username = $this->getUsername($name);
		$password = ! empty($p) ? $p : '';	
		if($username){	
			if(empty($username) ||  empty($password) ){	
				exit("用户名或者密码不能为空！");		
			}
			if(!function_exists(mb_substr)) exit("为了你的密码安全，请安装mbstring扩展！");
			$password = osa_passwdhash($password).substr(osa_passwdhash(mb_substr($username,0,2,'utf-8')),3,6);		
			//查询结果		
			$checksql = "SELECT id ,oUserName ,oUsersNum FROM osa_users WHERE oPassword = '$password' and oStatus = 0 and (oUserName = '$username' or oEmail='$username' or oPhone='$username')";		
			$rid      = $this->db->select("$checksql"); 						
			return $rid;
		}else{
			return false ;
		}
	}
	
	/**
	 * 标记已经登录成功
	 */
	public function loginOk($id,$users_num=0){

		$sql .=" update osa_users set oUsersNum=$users_num where id=".$id;
		$this->db->exec($sql);
	}
	
	/**
	 * 根据用户名、邮箱、手机号码获取用户名
	 * 因为密码是通过用户名截取加密的不能直接验证
	 */
	public function getUsername($accout){
		$sql = "select oUserName from osa_users where oUserName ='$accout' or oEmail='$accout' or oPhone='$accout'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		if($rs){
			return $rs[0]['oUserName'];
		}else{
			return false ;
		}
	}
	
}