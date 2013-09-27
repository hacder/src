<?php
class Device extends osa_controller{
	
	private $model = null;
	private $page = null;
	private $devdate = array();
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mdevice');
		$this->page = $this->loadmodel('mpage');
		$this->date = array(
			'today'        => date("Y-m-d H:i:s" ,strtotime('today')),
			'yesterday'    => date("Y-m-d H:i:s" ,strtotime('-1 day')),
			'lastweek'     => date("Y-m-d H:i:s" ,strtotime('-7 day')) ,
			'last2week'    => date("Y-m-d H:i:s" ,strtotime('-15 day')) 
		);
		if(!isset($_SESSION)){
			session_start();
		}
	}
	
	//设备信息列表页面
	public function index(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$region = $groupid = $typeid = '';
		$ex_url = '';
		if(isset($_GET['region'])){ //分地区查看设备信息
			$region = $_GET['region'];
			$ex_url = '&region='.$region.'&rid='.$_GET['rid'];
			$data['left'] = 'rlist'.$_GET['rid'];
		}else if(isset($_GET['type'])){ //分类型查看信息
			$typeid = $_GET['type'];
			$ex_url = '&type='.$typeid;
			$data['left'] = 'tlist'.$typeid;
		}else{
			$data['left'] = 'devlist';
		}
		//权限判断 9
		if(!osa_checkstr($_SESSION['login_role'],9)){
			header("Location: index.php?c=device&a=permiterror&left=".$data['left'], TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['devsearch']);
			unset($_SESSION['devstart']);
			unset($_SESSION['devend']);
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['devsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['devsearch'])?$_SESSION['devsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['devstart'] = $_POST['starttime'];
			$_SESSION['devend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['devstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['devstart'];
			$endtimes = $_SESSION['devend'];
			$endtime = date('Y-m-d ',strtotime($endtimes)+86400);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'device';//控制头部菜单栏。
		//$data['left'] = 'devlist';
		//$data['group']= $this->model->selectDeviceGroup();
		$data['type']= $this->model->selectDeviceType();
		$data['region'] = $this->model->getRegionfromDevinfo();
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['devinfo'] = $this->model->selectDeviceInfo($perpage ,$offset ,$search ,$starttime ,$endtime ,$region , $typeid);
		$num = $this->model->getNumfromDevinfo($search ,$starttime ,$endtime ,$region , $typeid);
		$url = 'index.php?c=device&a=index'.$ex_url;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('device/list',$data);
	}
	
	//设备信息分地区查看
	public function regionlist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['devsearch']);
			unset($_SESSION['devstart']);
			unset($_SESSION['devend']);
		}
		if(isset($_POST['keyword'])){
			if(!isset($_SESSION)){
				session_start();
			}
			$_SESSION['devsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['devsearch'])?$_SESSION['devsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['devstart'] = $_POST['starttime'];
			$_SESSION['devend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['devstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['devstart'];
			$endtimes = $_SESSION['devend'];
			$endtime = date('Y-m-d ',strtotime($endtimes)+86400);
		}else{ //不是自定义搜索
			$datename = isset($_GET['date'])? trim($_GET['date'],' '):'lastweek';
			$starttime = $this->devdate[$datename];
			$endtimes = date("Y-m-d" ,strtotime('today'));
			$endtime = date("Y-m-d" ,strtotime('+1 day'));
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'device';//控制头部菜单栏。
		//$data['group']= $this->model->selectDeviceGroup();
		$data['type']= $this->model->selectDeviceType();
		$data['region'] = $this->model->getRegionfromDevinfo();
		$data['starttime'] = $starttime;
		$data['endtime'] = $endtimes;
		//处理分页
		$area = $_GET['region'];
		$perpage = isset($config['perpage'])?$config['perpage']:2;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['devinfo'] = $this->model->selectDeviceInfo($perpage ,$offset ,$search ,$starttime ,$endtime,$area);
		$num = $this->model->getNumfromDevinfo($search ,$starttime ,$endtime ,$area);
		$url = 'index.php?c=device&a=regionlist&region='.$area;
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('device/list',$data);
	}
	
	//添加设备
	public function add(){
		if(!isset($_POST['oDevName'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 0
			if(!osa_checkstr($_SESSION['login_role'],1)){
				header("Location: index.php?c=device&a=permiterror&left=devlist", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'device';//控制头部菜单栏。
			$data['left'] = 'devlist';
			$data['group'] = $this->model->selectDeviceGroup();
			$data['type'] = $this->model->selectDeviceType();
			$data['region'] = $this->model->getRegionfromDevinfo();
			$this->loadview('device/add',$data);
		}else{		
			$ipid = $this->model->insertIpinfo($_POST['oIp']);
			$oPlace = trim(trim($_POST['oPlace'],'|'),'|');
			if($ipid){
				$devinfo = array(
					'oDevName'=>$_POST['oDevName'],
					//'oGroupid'=>$_POST['oGroupid'],
					'oIpid' => $ipid,
					'oIp'=>$_POST['oIp'],
					'oPlace'=>$oPlace,
					'oAddress'=>$_POST['oAddress'],
					'oTypeid'=>$_POST['oTypeid'],
					'oDevPrice'=>$_POST['oDevPrice'],
					'oDevTgPrice'=>$_POST['oDevTgPrice'],
					'oDevDetail'=>$_POST['oDevDetail'],
					'oUserid' =>$_SESSION['id']	,
					'oCreateTime'=>date('Y-m-d H:i;s',time()),
					'oShelveTime'=>$_POST['oShelveTime'],
					'oBusinessDes'=>$_POST['oBusinessDes']				
				);
				//新增逻辑 --根据分组id更新分组信息
				//$this->model->setServerList($_POST['oGroupid'],$_POST['oIp']);
				$rs = $this->model->insertDeviceInfo($devinfo);
				if($rs){
					$title = "添加设备";
					$info = $_SESSION['username']."添加设备成功，设备名称：".$_POST['oDevName'].",时间：".date("Y-m-d H:i:s");
					$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
					echo 'success';
					return ;
				}
				$title = "添加设备";
				$info = $_SESSION['username']."添加设备失败，设备名称：".$_POST['oDevName'].",可能原因:数据库操作失败,时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
				echo 'failure';
				return ;
			}
			$title = "添加设备";
			$info = $_SESSION['username']."添加设备失败，设备名称：".$_POST['oDevName'].",可能原因:数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;
			
		}
	}
	
	//添加分组处理函数
	public function addgroup(){
		if(!isset($_POST['oGroupName'])){
			echo 'failure';
			return ;
		}
		$name = $_POST['oGroupName'];
		$description = $_POST['oDescription'];
		$insertid = $this->model->insertDeviceGroup($name ,$description);
		if($insertid){
			$title = "添加设备分组";
			$info = $_SESSION['username']."添加设备分组成功，设备分组名称：".$_POST['oGroupName'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo $insertid;
			return ;
		}else{
			$title = "添加设备分组";
			$info = $_SESSION['username']."添加设备分组失败，设备分组名称：".$_POST['oGroupName'].",可能原因:数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;
		}
	}
	
	//添加类型
	public function addtype(){
		if(!isset($_POST['oTypeName'])){
			echo 'failure';
			return ;
		}
		$name = $_POST['oTypeName'];
		$insertid = $this->model->insertDeviceType($name );
		if($insertid){
			$title = "添加设备类型";
			$info = $_SESSION['username']."添加设备类型成功，设备类型名称：$name,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo $insertid;
			return ;
		}else{
			$title = "添加设备类型";
			$info = $_SESSION['username']."添加设备类型失败，设备分组名称：$name,可能原因:数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;
		}
	}
	
	//设备名唯一性验证
	public function checkname(){
		$devname = $_POST['devname'];
		$rs = $this->model->getInfoBydevname($devname);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	//IP 唯一性验证
	public function checkip(){
		$ip = $_POST['ip'];
		$rs = $this->model->selectIpinfo($ip);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	//编辑设备
	public function edit($id=''){
		if(!isset($_POST['oDevName'])){
			if(!isset($_GET['id'])){
				header("Location: index.php?c=device&a=index", TRUE, 302);
			}
			//权限判断 2
			if(!osa_checkstr($_SESSION['login_role'],2)){
				header("Location: index.php?c=device&a=permiterror&left=devlist", TRUE, 302);
			}
			$id = $_GET['id'];
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'device';//控制头部菜单栏。
			$data['left'] = 'devlist';
			$data['devinfo'] =$devinfo = $this->model->getDevinfoByid($id);
			if($devinfo){
				$regin = explode('|' ,$devinfo[0]['oPlace']);
				$data['country'] = $regin[0];
				$data['province'] = $regin[1];
				$data['city'] = $regin[2];
				$data['group'] = $this->model->selectDeviceGroup();
				$data['type'] = $this->model->selectDeviceType();
				$data['region'] = $this->model->getRegionfromDevinfo();
				$this->loadview('device/edit',$data);
			}
			//header("Location: index.php?c=device&a=index", TRUE, 302);
		}else{
			$id = $_GET['id'];
			$oPlace = trim(trim($_POST['oPlace'],'|'),'|');
			$devinfo = array(
					//'oGroupid'=>$_POST['oGroupid'],
					'oPlace'=>$oPlace,
					'oAddress'=>$_POST['oAddress'],
					'oTypeid'=>$_POST['oTypeid'],
					'oDevPrice'=>$_POST['oDevPrice'],
					'oDevTgPrice'=>$_POST['oDevTgPrice'],
					'oDevDetail'=>$_POST['oDevDetail'],	
					'oShelveTime'=>$_POST['oShelveTime'],
					'oBusinessDes'=>$_POST['oBusinessDes']	
				);
			//$this->model->setServerList($_POST['oGroupid'],$_POST['oIp']);
			$rs = $this->model->updateDeviceInfo($id ,$devinfo);
			$title = "编辑设备";
			$info = $_SESSION['username']."编辑设备成功，设备名称：".$_POST['oDevName'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}
	}
	
	//复制页面
	public function copy($id =''){
		if(!isset($_GET['id'])){
			header("Location: index.php?c=device&a=index", TRUE, 302);
		}
		//权限判断 4
		if(!osa_checkstr($_SESSION['login_role'],4)){
			header("Location: index.php?c=device&a=permiterror&left=devlist", TRUE, 302);
		}
		$id = $_GET['id'];
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'device';//控制头部菜单栏。
		$data['left'] = 'devlist';
		$data['devinfo'] =$devinfo = $this->model->getDevinfoByid($id);
		if($devinfo){
			$regin = explode('|' ,$devinfo[0]['oPlace']);
			$data['country'] = $regin[0];
			$data['province'] = $regin[1];
			$data['city'] = $regin[2];
			$data['group'] = $this->model->selectDeviceGroup();
			$data['type'] = $this->model->selectDeviceType();
			$data['region'] = $this->model->getRegionfromDevinfo();
			$this->loadview('device/copy',$data);
		}
		header("Location: index.php?c=device&a=index", TRUE, 302);
	}
	
	//删除设备记录
	public function del(){
		//权限判断 3
		if(!osa_checkstr($_SESSION['login_role'],3)){
			//header("Location: index.php?c=device&a=permiterror&left=devlist", TRUE, 302);
			echo 'no_permissions';return ;
		}
		if(!$_POST['arr']){
			echo 'error';
			return ;
		}
		$arr = $_POST['arr'];
		$deliparr = array();
		foreach ($arr as $key) { //还需同步删除ip表里面的ip信息 -尽量减少数据库操作
			$idarr = explode('-',$key);
			$this->model->delDeviceInfo($idarr[0]);
			$this->model->delIpinfo($idarr[1]);
			array_push($deliparr,$idarr[2]);
		}
		$this->model->dealDevGroup($deliparr);//将分组管理中删除的ip剔除掉-尽量减少数据库操作。
		$title = "删除设备";
		$info = $_SESSION['username']."删除设备成功，共删除设备".count($arr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	/********************************************分组管理*****************************************************/
	/**
	 * 分组管理列表
	 */
	public function devgrouplist(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		//权限判断 10
		if(!osa_checkstr($_SESSION['login_role'],10)){
			header("Location: index.php?c=device&a=permiterror&left=devgrouplist", TRUE, 302);
		}
		if(isset($_GET['date'])||isset($_GET['clean'])){
			unset($_SESSION['grpstart']);
			unset($_SESSION['grpend']);
			unset($_SESSION['grpsearch']);
		}
		if(isset($_POST['keyword'])){
			$_SESSION['grpsearch'] = $search = $_POST['keyword'] ;
		}else{
			$search = isset($_SESSION['grpsearch'])?$_SESSION['grpsearch']:'';
		}
		if(isset($_POST['starttime'])){
			$_SESSION['grpstart'] = $_POST['starttime'];
			$_SESSION['grpend'] = $_POST['endtime'];
		}
		if(isset($_SESSION['grpstart'])){ //说明是自定义搜索
			$starttime = $_SESSION['grpstart'];
			$endtime = date('Y-m-d H:i:s',strtotime($_SESSION['grpend'])+86399);
		}else if(isset($_GET['date'])){ //不是自定义搜索
			if($_GET['date']== 'yesterday'){
				$starttime = date("Y-m-d",strtotime("-1 day"));
				$endtime = date("Y-m-d",time()+1);
			}else{
				$datename = trim($_GET['date'],' ');
				$starttime = $this->date[$datename];
				$endtime = date("Y-m-d H:i:s" ,time()+1);
			}
			$data['picker'] = $_GET['date'];
		}else{//默认
			$starttime = OSA_DEFAULT_STARTTIME;
			$endtime = date("Y-m-d H:i:s" ,time()+1);
		}
		$data['menu'] = 'device';//控制头部菜单栏。
		$data['left'] = 'devgrouplist';
		//$data['group']= $this->model->selectDeviceGroup();
		$data['type']= $this->model->selectDeviceType();
		$data['region'] = $this->model->getRegionfromDevinfo();
		$data['starttime'] = date("Y-m-d H:i:s",strtotime($starttime));
		$data['endtime'] = date("Y-m-d H:i:s",strtotime($endtime));
		//处理分页
		$perpage = OSA_DEFAULT_PERPAGE;
		$offset = isset($_GET['offset'])?$_GET['offset']:0;
		$data['grpinfo'] = $this->model->getGroupInfo($perpage ,$offset ,$search,$starttime ,$endtime );
		$num = $this->model->getGrpinfoNum($search ,$starttime ,$endtime);
		$url = 'index.php?c=device&a=devgrouplist';
		$mb_url = isset($_GET['date'])?"&date=".$_GET['date']:"";
		$pageurl = $url.$mb_url;
		$data['url'] = $url;
		$data['page'] = $this->page->create_links($pageurl,$num ,$perpage ,$offset);
		$this->loadview('device/devgrouplist',$data);
	}
	
	/**
	 * 设备添加
	 */
	public function groupadd(){
		if(!isset($_POST['groupname'])){
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			//权限判断 11
			if(!osa_checkstr($_SESSION['login_role'],11)){
				header("Location: index.php?c=device&a=permiterror&left=devgrouplist", TRUE, 302);
			}
			$data['menu'] = 'device';//控制头部菜单栏。
			$data['left'] = 'devgrouplist';
			//$data['group'] = $this->model->selectDeviceGroup();
			$data['type'] = $this->model->selectDeviceType();
			$data['region'] = $this->model->getRegionfromDevinfo();
			//还需修改
			$this->loadview('device/groupadd',$data);
		}else{		
			$groupinfo = array(
				'oGroupName'=>$_POST['groupname'],
				'oDescription'=>$_POST['descript'],
				'oAddTime' => date('Y-m-d H:i;s',time()),
				'oServerList'=>$_POST['serverlist'],			
			);
			$rs = $this->model->insertDevGroup($groupinfo);
			if($rs){
				$title = "添加设备分组";
				$info = $_SESSION['username']."编辑设备分组成功，设备分组名称：".$_POST['groupname'].",时间：".date("Y-m-d H:i:s");
				$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
				echo 'success';
				return ;
			}
			$title = "添加设备分组";
			$info = $_SESSION['username']."添加设备分组失败，设备分组名称：".$_POST['groupname'].",可能原因:数据库操作失败,时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo 'failure';
			return ;	
		}
	}
	
	
	/**
	 * 设备编辑
	 */
	public function groupedit(){
		if(!isset($_POST['groupname'])){
			if(!isset($_GET['id'])){
				header("Location: index.php?c=device&a=index", TRUE, 302);
			}
			//权限判断 12
			if(!osa_checkstr($_SESSION['login_role'],12)){
				header("Location: index.php?c=device&a=permiterror&left=devgrouplist", TRUE, 302);
			}
			$id = $_GET['id'];
			if(!isset($_SESSION['username'])){
				header("Location: index.php?c=login&a=index", TRUE, 302);
			}
			$data['username'] = $_SESSION['username'];
			$data['menu'] = 'device';//控制头部菜单栏。
			$data['left'] = 'devgrouplist';
			//$data['devinfo'] =$devinfo = $this->model->getDevinfoByid($id);
			$data['ginfo'] = $this->model->getGroupByid($id);
			$data['hideurl'] = "index.php?c=device&a=groupedit&id=".$id;
			//$data['group'] = $this->model->selectDeviceGroup();
			$data['type'] = $this->model->selectDeviceType();
			$data['region'] = $this->model->getRegionfromDevinfo();
			//还需修改
			$this->loadview('device/groupedit',$data);
			
		}else{
			$id = $_GET['id'];
			$groupinfo = array(
				'oDescription'=>$_POST['descript'],
				'oServerList'=>$_POST['serverlist'],	
			);
			$rs = $this->model->updateDevGroup($groupinfo,$id);
			$title = "编辑设备分组";
			$info = $_SESSION['username']."编辑设备分组成功，设备分组名称：".$_POST['groupname'].",时间：".date("Y-m-d H:i:s");
			$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 检查分组名是否唯一
	 */
	public function checkgname(){
		$groupname = $_POST['groupname'];
		$rs = $this->model->getInfoBygname($groupname);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 检查设备分类名是否唯一
	 */
	public function checktname(){
		$typename = $_POST['typename'];
		$rs = $this->model->getInfoBytname($typename);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 检查日志分类名是否唯一
	 */
	public function checklogtype(){
		$typename = $_POST['typename'];
		$rs = $this->model->getLogTypeByname($typename);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 检查知识分类名是否唯一
	 */
	public function checkknowtype(){
		$typename = $_POST['typename'];
		$rs = $this->model->getKnowTypeByname($typename);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 检查文件分类名是否唯一
	 */
	public function checkfiletype(){
		$typename = $_POST['typename'];
		$rs = $this->model->getFileTypeByname($typename);
		if($rs){
			echo 'exist';
			return ;
		}else{
			echo 'success';
			return ;
		}
	}
	
	/**
	 * 删除设备分组
	 */
	public function groupdel(){
		//权限判断 13
		if(!osa_checkstr($_SESSION['login_role'],13)){
			//header("Location: index.php?c=device&a=permiterror&left=devlist", TRUE, 302);
			echo 'no_permissions';return ;
		}
		$delarr = $_POST['delarr'];
		foreach ($delarr as $key){
			$this->model->delDevGroup($key);
		}
		$title = "删除设备分组";
		$info = $_SESSION['username']."删除设备分组成功，共删除设备分组".count($delarr)."个,时间：".date("Y-m-d H:i:s");
		$this->model->saveSysLog(2,$title,$info,$_SESSION['username']);
		echo 'success';
		return ;
	}
	
	public function permiterror(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['menu'] = $_GET['c'];
		$data['left'] = $_GET['left'];
		$data['type'] = $this->model->selectDeviceType();
		$data['region'] = $this->model->getRegionfromDevinfo();
		$this->loadview("device/permiterror",$data);
	}
	
	/**
	 * 处理ckeditor 图片上传问题
	 */
	public function uploadimg(){		
		if(!isset($_SESSION['username'])){
			mkhtml(1,"","没有上传图片权限");;
		}
		$imagesize = 500;
		if(empty($_GET['ckeditorfuncnum']))
			mkhtml(1,"","错误的功能调用请求");
		$fn=$_GET['ckeditorfuncnum'];
		$type=$_GET['type'];
		if(is_uploaded_file($_FILES['upload']['tmp_name']))
		{
			//判断上传文件是否允许
			$filearr=pathinfo($_FILES['upload']['name']);
			$filetype=$filearr["extension"];
			if(!in_array($filetype,array("jpg","bmp","gif","png")))
				mkhtml($fn,"","错误的文件类型！");
			//判断文件大小是否符合要求
			if($_FILES['upload']['size']>$imagesize*1024)
				mkhtml($fn,"","上传的文件不能超过".$imagesize."KB！");
			$filepath = mktime().".".$filetype;//加上年月日保证文件名唯一
			$savepath = osa_datapath('img',$filepath);
			//$file_abso=$config[$type."_dir"]."/".$config['name'].".".$filetype;
			osa_mkdirs(dirname($savepath));
			//$file_host=$_SERVER['DOCUMENT_ROOT'].$file_abso;
			  
			if(move_uploaded_file($_FILES['upload']['tmp_name'],$savepath))
			{
				mkhtml($fn,$savepath,'上传成功');
			}
			else
			{
				mkhtml($fn,"","文件上传失败，请检查上传目录设置和目录读写权限");
			}
		}
	}
}