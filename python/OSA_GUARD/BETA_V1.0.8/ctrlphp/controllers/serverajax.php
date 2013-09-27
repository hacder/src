<?php
class serverajax extends osa_controller{

	private $model = null;
	private $page = null;
	
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mserverajax');
		$this->page = $this->loadmodel('mpage');
		if(!isset($_SESSION)){
			session_start();
		}
	}
	
	
	public function devtype_inquiry(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$devtype = $this->model->devtype_select();
		echo json_encode($devtype);	return ;
	}
	
	
	public function devroom_inquiry(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$devroom = $this->model->devroom_select();
		echo json_encode($devroom);	return ;
	}
	
	
	public function serverip_inquiry(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$ipvalue = '';
		if(isset($_POST['ipvalue'])){
			$ipvalue = $_POST['ipvalue'];
		}
		$server = $this->model->serverip_select($ipvalue);
		echo json_encode($server);	return ;
	}
	
	
	public function serverip_inquiry_bytypes(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$typeid = $_POST['typeid'];
		$server = $this->model->serverip_select_bytypes($typeid);
		echo json_encode($server);	return ;
	}
	
	
	public function serverip_inquiry_byrooms(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$roomid = $_POST['roomid'];
		$server = $this->model->serverip_select_byrooms($roomid);
		echo json_encode($server);	return ;
	}
	
	
	public function userinfo_inquiry(){
		
		$users = $this->model->users_select();
		echo json_encode($users);	return ;
	}
	
	
	public function osafile_upload(){
	
		if ($_POST ["PHPSESSID" ]) {
			session_id ( $_POST ["PHPSESSID" ] );	
		}
		session_start();
		//上传文件的父结点
		if (! isset ( $_FILES ["Filedata"] ) || $_FILES ["Filedata"] ["error"] != 0) {
			exit ( 'Nothing Upload' );
		}
		
		//权限检测
		if ( ! isset ( $_SESSION ['username'] )) {
			//exit ( session_id() );
			print_r($_SESSION);
			return ;
		}
		
		$upload_file = $_FILES ["Filedata"];
		session_write_close ();
		
		//将上传文件存进cloud归档目录，以及完成插入数据库操作
		//加载HTTP文件上传模型类
		$json_ret = $this->model->osa_upload_file($upload_file);
		
		if (! $json_ret) {
			exit ( 'Upload Failed' );
		}else if($json_ret == 'exists'){
			exit('exists');
		}
		
		//返回JSON格式的上传文件信息 !!!
		echo trim ( $json_ret );
	}
	
	
	public function upload_test(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$this->loadview('upload_test');
	}
	
	public function upload_file_view(){
		
		$this->loadview('osaUpload');
	}
}

