<?php
class mupload extends osa_model{
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 检查是否路径重名
	 */
	public function checkSamePath($path){
		$sql = "select id from osa_configfile where oSavePath = '$path'";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	
	/**
	 * 处理上传文件
	 */
	public function uploadFile($file){
		$filename = $file['name'];
		$filepath = 'upload/'.date("mis").$file['name'];//加上年月日保证文件名唯一
		$savepath = osa_datapath('upload',$filepath);
		if(!empty($savepath)){
			$oFileName = substr($filename ,0 ,strpos($filename ,'.'));
			$configinfo = array(
				'oFileName' =>$oFileName,
				'oTypeid' => 1,
				'oSavePath' =>$savepath,
				'oCreateTime' => date('Y-m-d H:i:s' ,time())
			);
			$this->insertConfigFile($configinfo);
			$absolutepath = $savepath;
			osa_mkdirs(dirname($absolutepath));
			@move_uploaded_file($file['tmp_name'],$absolutepath);
			return $savepath ;
		}else{
			return 'writable_error';
		}
	}
	
	/**
	 * 插入数据库
	 */
	public function insertConfigFile($configinfo){
		foreach ($configinfo as $key => $value)
		{
			$keys 	.= "$key,";
			$query 	.= "'$value',";
		}
		$keys = trim($keys,',');
		$query = trim($query ,',');
		$sql = "insert into osa_configfile ($keys) values($query)";
		$this->db->exec($sql);
		return $this->db->lastInsertId();
	}
	
}