<?php
class MpersonCenter extends osa_model{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function updatePassword($oldpwd,$newpwd){
		//echo $_SESSION['username'];
		$sqlpwd = "select oPassword from osa_users where id='".$_SESSION['id']."'";
		$dbpwd = $this->db->queryFetchAllAssoc($sqlpwd); 
		$password = $this->owsPasswordHash($oldpwd).substr($this->owsPasswordHash(mb_substr($_SESSION['username'],0,2,'utf-8')),3,6);
		//echo $dbpwd[0]['oPassword']."<br>".$password."<br>";
		//var_dump(trim($dbpwd[0]['oPassword']));
		//echo"<br>";
		//var_dump($password);
		//$password = $this->owsPasswordHash("111111").substr($this->owsPasswordHash(mb_substr("maxisheng",0,2,'utf-8')),3,6);
		
		$pwd = (string)trim($dbpwd[0]['oPassword']);
		$password = (string)$password;
		//echo $pwd."<br>".$password;exit;
		if($pwd == $password){
		   $newpassword = $this->owsPasswordHash($newpwd).substr($this->owsPasswordHash(mb_substr($_SESSION['username'],0,2,'utf-8')),3,6); 
		  $sqlupdate = "update osa_users set oPassword ='".$newpassword."' where id='".$_SESSION['id']."'";
		  $this->db->exec($sqlupdate);
		}else{
		      echo"原密码输入错误！";
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
	
	public function updateMyInfo($uid,$userinfo){
	    foreach ($userinfo as $key => $val)
		{
			$query .= "$key = '$val',";
		}
		$query = trim($query ,',');
		$sql = "update osa_users set $query where id=$uid";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	
	}
	
	public function getMyInfo($uid){
	   $sql = "select * from osa_users where id ='".$uid."'";
	   $db = $this->db->queryFetchAllAssoc($sql);
	   return $db[0];
	}
	
	/**
	 * 设置快捷菜单
	 */
	public function updateShortCut($uid,$shortcut){
		$sql = "update osa_users set oShortCut ='$shortcut' where id =".$uid;
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
	/**
	 * 获取告警信息
	 */
	public function getAlarmMsg($perpage ,$offset ,$starttime ,$endtime ){
		$sql = "select * from osa_alarmmsg where oAddTime > '$starttime' and oAddTime < '$endtime'";
		$sql .=" order by oAddTime desc" ;
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/** 
	 * 获取告警信息数量
	 */
	public function getAlarmmsgNum($starttime,$endtime){
		$sql = "select count(id) as num from osa_alarmmsg where oAddTime >'$starttime' and oAddTime < '$endtime'";
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];	
	}
	
	/**
	 * 删除报警信息
	 */
	public function delAlarms($id){
		$sql = "delete from osa_alarmmsg where id=$id";
		$this->db->exec($sql);
	}
	
	
}

