<?php
class logins extends osa_model{
	
	private $type="";
	private $info="";
	private $title="";
	
	public function __construct(){
		parent::__construct();
	}
	
	function getType(){
		return $this->type;
	}
	
	function getInfo(){
		return $this->info;
	}
	
	
	function getTitle(){
		return $this->title;
	}
	
	
	/**
	 * 判断是否 ip被锁定
	 * Enter description here ...
	 */
	public function is_blockIp(){
		$ip = $this->db->getip();
		if(OSA_BLOCK_IP){		
			if(file_exists(OSA_BLOCK_FILE)){
				$iplist =  file(OSA_BLOCK_FILE);				
				foreach($iplist as $ipvalue){			
					if(strpos('_'.$ipvalue,$ip)){
						$title = '用户登录失败';
						$info="此IP被锁定！IP地址：".$ip;
						$this->saveSysLog(1,$title,$info ,$_POST['username']);
						return 'block' ;							
					}					
				}
				return 'unblock';
			}
			return 'unblock';					
		}
		return 'unblock';
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
			$ip = $this->db->getip();	
			$midtime = time() - $_SESSION['t'];	 
			if($_SESSION['login_num'] > OSA_TRY_LOGIN_NUM && $midtime < 300){
				$f = fopen(OSA_BLOCK_FILE,'a+');		
				fwrite($f,$ip."\n");			
				fclose($f);	
				$title = '用户登录失败';			
				$info='尝试登录失败超过'.OSA_TRY_LOGIN_NUM.'次，请稍后再试！IP:'.$ip.'用户：'.$username;															
				$this->saveSysLog(1,$title,$info,$username);
				return 'num_error';											
			}
			if ($rs = $this->check_login_info($username,$password)){
				
				$_SESSION['username']  = $rs[0]['oUserName'];	
				$_SESSION['id'] = $rs[0]['id'];
				$_SESSION['login_time'] = date("Y-m-d H:i:s",time());
				$_SESSION['login_ip'] = $ip ;
				$_SESSION['login_role'] = $this->getRoleByUid($rs[0]['id']);
				$_SESSION['users_num'] = $rs[0]['oUsersNum']+1;
				$_SESSION['login_num'] = 0;
					
				$title = '用户登录成功';			
				$info='用户：'.$username."成功登录,IP:".$ip;
				$this->saveSysLog(1,$title,$info,$username);								
				return 'success';					
			}else{					
						
				if(empty($_SESSION['login_num'])){
					$_SESSION['login_num']=0;
				}				
				$_SESSION['login_num'] ++;				
				$_SESSION['t'] = time();			
										
				$title = '用户登录失败';
				$info='用户名或者密码不正确,或者用户被禁止！IP:'.$ip.'用户：'.$username;
				$this->saveSysLog(1,$title,$info,$username);
				return 'login_error';											
			}
		}
	}
	
	private function owsPasswordHash($password) 
    {         
        
		$prefix = OSA_PASSWORD_PREFIX ? OSA_PASSWORD_PREFIX : 'osa_prefix' ;
		$ows_salt = sha1($prefix); 
        $ows_salt = substr($ows_salt, 0, 4); 
        $hash = base64_encode( $ows_salt . sha1($ows_salt . $password , true) ); 
        return $hash; 
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
			$password = $this->owsPasswordHash($password).substr($this->owsPasswordHash(mb_substr($username,0,2,'utf-8')),3,6);		
			//查询结果		
			$checksql = "SELECT id ,oUserName ,oUsersNum FROM osa_users WHERE oPassword = '$password' and oStatus = 1 and (oUserName = '$username' or oEmail='$username' or oPhone='$username')";		
			$rid      = $this->db->select("$checksql"); 						
			return $rid;
		}else{
			return false ;
		}
	}
	
	/**
	 * 标记已经登录成功
	 */
	public function loginOk($id,$value,$users_num=0){
		//$sql = "update osa_users set oIsLogin = $value where id=".$id;
		$sql = "update osa_users set oIsLogin = $value ";
		if(!empty($users_num)){
			$sql .=" ,oUsersNum=$users_num ";
		}
		$sql .=  " where id=".$id ;
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