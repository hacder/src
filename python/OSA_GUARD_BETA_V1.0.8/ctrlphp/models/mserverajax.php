<?php
class mserverajax extends osa_model{
	
	public function __construct(){
	
		parent::__construct();
	}
	
	
	public function devtype_select(){
		
		$sql = "select * from osa_devtype ";
		$sql .= " order by id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function devroom_select(){
		
		$sql = "select * from osa_devroom";
		$sql .= " order by id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function serverip_select($ipvalue){
	
		$sql = "select oIp from osa_ipinfo where oIsStop =0";
		if(!empty($ipvalue)){
			$sql .=" and oIp like '%$ipvalue%' ";
		}
		$sql .= " order by id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function users_select(){
	
		$sql = "select oUserName from osa_users where 1";
		$sql .=" order by id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function serverip_select_bytypes($typeid){
	
		$sql = "select oIp from osa_device where oIsStop = 0";
		if(!empty($typeid)){
			$sql .= " and oTypeid = $typeid";
		}
		$sql .= " order by id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function serverip_select_byrooms($roomid){
	
		$sql = "select oIp from osa_device where oIsStop = 0";
		if(!empty($roomid)){
			$sql .= " and oRoomid = $roomid";
		}
		$sql .= " order by id";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	public function osa_upload_file($file){ //还需文件名去重复
		
		$filename = $file['name'];
		$filesize = $file['size'];
		$rootpath = OSA_PHPDATA_PATH;
		$realpath = rtrim($rootpath,'/').'/upload/'.$filename;
		$file_exist = $this->file_isexist($filename);
		if(!$file_exist){
			$fileinfo = array(
				'oFileName' =>$filename,
				'oFileSize' =>$filesize,
				'oRealPath'=>$realpath,
				'oUploadUser'=>$_SESSION['username'],
				'oCreateTime' => date('Y-m-d H:i:s' ,time())
			);
			$absolutepath = iconv('utf-8', 'gb2312', $realpath);
			//$absolutepath = $realpath;
			osa_mkdirs(dirname($absolutepath));
			if(@move_uploaded_file($file['tmp_name'],$absolutepath)){
				$fileid = $this->upload_file_insert($fileinfo);
				$upload_file = "{'realpath':'$realpath','fileid':'$fileid','filename':'$filename','filesize':'$filesize'}";
				return $upload_file;
			}else{
				return false ;
			}
		}else{
			return 'exists';
		}
			
	}
	
	
	public function file_isexist($filename){
	
		$sql = "select * from osa_upload_file where oFileName='$filename'";
		$result = $this->db->queryFetchAllAssoc($sql);
		if(!empty($result))
			return true;
		else 
			return false;
	}
	
	
	public function upload_file_insert($fileinfo){
	
		foreach ($fileinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_upload_file ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
}