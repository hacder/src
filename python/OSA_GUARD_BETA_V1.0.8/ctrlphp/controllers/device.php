<?php

class device extends osa_controller{

	private $model = null;
	private $page = null;
	
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mdevice');
		$this->page = $this->loadmodel('mpage');
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION['header'] = "device";
	}
	
	/****************************************start--- device views ---start*****************************************/
	
	/**
	 * device info list
	 */
	public function listindex(){
		
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$_SESSION['roomname'] = isset($_GET['room'])?trim($_GET['room']):'';
		$data['typeinfo'] = $this->model->devtype_select();
		$data['roominfo'] = $this->model->devroom_select();
		$data['labelinfo'] = $this->model->devlabel_select();
		$data['info'] = $this->model->device_select($_SESSION['roomname'],$search='');
		$this->loadview('device/list',$data);
	}
	
	
	
	/**
	 * list view ajax search
	 */
	public function list_search(){
		
		$search = trim($_POST['search']);
		$info = $this->model->device_select($_SESSION['roomname'],$search);
		$arr = array();
		if(!empty($info)){
			foreach ($info as $key) {
				$tem = array('id'=>$key['id']);
				array_push($arr,$tem);
			}
		}
		echo json_encode($arr);
	}
	
	
	/**
	 * list view ajax retrieve
	 */
	public function list_retrieve(){
	
		$typename = trim($_POST['typename']);
		$labelname = trim($_POST['labelname']);
		$info = $this->model->device_retrieve($_SESSION['roomname'],$typename,$labelname);
		$arr = array();
		if(!empty($info)){
			foreach ($info as $key) {
				$tem = array('id'=>$key['id']);
				array_push($arr,$tem);
			}
		}
		echo json_encode($arr);	
	}
	
	
	/**
	 *  device info graph view
	 */
	public function graphindex(){
		
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$_SESSION['roomname'] = isset($_GET['room'])?trim($_GET['room']):'';
		$status = isset($_GET['status'])?trim($_GET['status']):'';
		$data['typeinfo'] = $this->model->devtype_select();
		$data['roominfo'] = $this->model->devroom_select();
		$data['info'] = $this->model->device_select($_SESSION['roomname'],$search='',$status);
		$this->loadview('device/graph',$data);
		
	}
	
	
	/**
	 * device info add
	 */
	public function addindex(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['typeinfo'] = $this->model->devtype_select();
		$data['roominfo'] = $this->model->devroom_select();
		$this->loadview('device/addindex',$data);
	}
	
	
	/**
	 * device info edit
	 */
	public function editindex(){
		
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		if(!isset($_GET['id'])){		
			$this->listindex();
		}
		$id = trim($_GET['id']);
		$devinfo = $this->model->device_select_id($id);
		$data['typeinfo'] = $this->model->devtype_select();
		$data['roominfo'] = $this->model->devroom_select();
		$data['devinfo'] = $devinfo;
		$this->loadview('device/editindex',$data);
		
	}
	
	/****************************************end--- device views ---end*****************************************/
	
	
	/****************************************start--- device list method ---start*****************************************/
	
	
	public function device_getvalue(){
		
		$id = $_POST['id'];
		$info = $this->model->device_select_id($id);
		echo json_encode($info);
		return ;
	}
	
	/**
	 * ajax check ip
	 */
	public function ip_check(){
		
		$ip = trim($_POST['ip']);
		$result = $this->model->ip_isexist($ip);
		if($result[0]){
			 exit('exists');
		}else{
			exit('success');
		}
	}
		
	
	/**
	 * ajax device add
	 */
	public function device_add(){
		
		$ipid = $this->model->ipinfo_insert(trim($_POST['ipname']));
		$typeid = $roomid = '';
		if(!empty($_POST['devtype'])){
			$typeid = $this->model->devtype_getid(trim($_POST['devtype']));
		}
		if(!empty($_POST['engineroom'])){
			$roomid = $this->model->devroom_getid(trim($_POST['engineroom']));
		}
		if(isset($_POST['devlabel'])&!empty($_POST['devlabel'])){//标签处理
			$this->model->devlabel_hots($_POST['devlabel']);
		}
		if($ipid){
			$devinfo = array(
				'oDevName'=>trim($_POST['devname']),
				'oIp'=>trim($_POST['ipname']),
				'oIpid'=>$ipid,
				'oTypeName'=>trim($_POST['devtype']),
				'oTypeid'=>$typeid,
				'oEngineRoom'=>trim($_POST['engineroom']),
				'oRoomid'=>$roomid,
				'oDevPrice'=>trim($_POST['devprice']),
				'oDevTgprice'=>trim($_POST['tgprice']),
				'oWorkDes'=>isset($_POST['workdes'])?trim($_POST['workdes']):'',
				'oShelveTime'=>isset($_POST['shelvetime'])?$_POST['shelvetime']:'',
				'oDevLabel'=>isset($_POST['devlabel'])?trim($_POST['devlabel']):'',
				'oDevDetail'=>isset($_POST['devdetail'])?trim($_POST['devdetail']):''
			);
			$rs = $this->model->device_insert($devinfo);
			$content = "执行事件：添加服务器信息,服务器id：".$rs;
			//osa_logs_save($content,'1');
			exit('sucess_add');
		}
		$content = "执行事件：添加服务器信息";
		//osa_logs_save($content,'0');
		exit('failure_add');
	}
	
	
	/**
	 * pop ajax device add
	 */
	public function pop_device_add(){
	
		$ipid = $this->model->ipinfo_insert(trim($_POST['ipname']));
		$typeid = $roomid = '';
		if(!empty($_POST['devtype'])){
			$typeid = $this->model->devtype_getid(trim($_POST['devtype']));
		}

		$devinfo = array(
			'oDevName'=>trim($_POST['devname']),
			'oIp'=>trim($_POST['ipname']),
			'oIpid'=>$ipid,
			'oTypeName'=>trim($_POST['devtype']),
			'oTypeid'=>$typeid,
		);
		$rs = $this->model->device_insert($devinfo);
		$html = '<dl class="graph-unit">
			      <dt><img src="images/2.gif" /></dt>
				  <dd>
				  	<span><input type="checkbox" value="'.$rs.'" class="graph-checkbox"/><input type="hidden" class="input-hide" value="'.$ipid.'"/></span>
				  	<span ><a href="index.php?c=paint&a=serverable&ipid='.$ipid.'" title="图形中心">'.$_POST["ipname"].'</a></span>
				  </dd>
				  <dd>
						<span class="actedit"><a href="index.php?c=device&a=editindex&id='.$rs.'">编辑</a></span>
						<span class="actpause graph-pause" ><a href="#">暂停</a></span>
						<span class="actpause graph-open" style="display:none;"><a href="#">启用</a></span>
						<span class="actdel graph-del"><a href="#">删除</a></span>
				  </dd>
			  </dl>';
		echo $html;return;
	
	}
	
	/**
	 * ajax device edit
	 */
	public function device_edit(){
	
		$id = trim($_POST['id']);
		$typeid = $roomid = '';
		if(!empty($_POST['devtype'])){
			$typeid = $this->model->devtype_getid(trim($_POST['devtype']));
		}
		if(!empty($_POST['engineroom'])){
			$roomid = $this->model->devroom_getid(trim($_POST['engineroom']));
		}
		if(isset($_POST['devlabel'])&!empty($_POST['devlabel'])){//标签处理
			$this->model->devlabel_hots($_POST['devlabel']);
		}
		$devinfo = array(
			'oDevName'=>trim($_POST['devname']),
			'oTypeName'=>trim($_POST['devtype']),
			'oTypeid'=>$typeid,
			'oEngineRoom'=>trim($_POST['engineroom']),
			'oRoomid'=>$roomid,
			'oDevPrice'=>trim($_POST['devprice']),
			'oDevTgprice'=>trim($_POST['tgprice']),
			'oWorkDes'=>isset($_POST['workdes'])?trim($_POST['workdes']):'',
			'oShelveTime'=>isset($_POST['shelvetime'])?$_POST['shelvetime']:'',
			'oDevLabel'=>isset($_POST['devlabel'])?trim($_POST['devlabel']):'',
			'oDevDetail'=>isset($_POST['devdetail'])?trim($_POST['devdetail']):''
		);
		$this->model->device_update($id,$devinfo);
		$content = "执行事件：服务器信息编辑,服务器id：".$id;
		//osa_logs_save($content);
		exit('success_edit');
	}
	
	
	public function supply_infos(){
	
		$id = trim($_POST['id']);
		if(isset($_POST['devlabel'])&!empty($_POST['devlabel'])){//标签处理
			$this->model->devlabel_hots($_POST['devlabel']);
		}
		$devinfo = array(
			'oWorkDes'=>isset($_POST['workdes'])?trim($_POST['workdes']):'',
			'oShelveTime'=>isset($_POST['worktime'])?$_POST['worktime']:'',
			'oDevLabel'=>isset($_POST['devlabel'])?trim($_POST['devlabel']):'',
			'oDevDetail'=>isset($_POST['devdetail'])?trim($_POST['devdetail']):''
		);
		$this->model->device_update($id,$devinfo);
		exit('success_supply');
	}
	
	
	/**
	 * list view ajax add device 
	 */
	public function list_add_ajax(){
		
		$ipid = $this->model->ipinfo_insert(trim($_POST['ipname']));
		$typeid = $roomid = '';
		if(!empty($_POST['devtype'])){
			$typeid = $this->model->devtype_getid(trim($_POST['devtype']));
		}
		if(!empty($_POST['engineroom'])){
			$roomid = $this->model->devroom_getid(trim($_POST['engineroom']));
		}
		if(isset($_POST['devlabel'])&!empty($_POST['devlabel'])){//标签处理
			$this->model->devlabel_hots($_POST['devlabel']);
		}
		$devinfo = array(
			'oDevName'=>trim($_POST['devname']),
			'oIp'=>trim($_POST['ipname']),
			'oIpid'=>$ipid,
			'oTypeName'=>trim($_POST['devtype']),
			'oTypeid'=>$typeid,
			'oEngineRoom'=>trim($_POST['engineroom']),
			'oRoomid'=>$roomid,
			'oDevPrice'=>trim($_POST['devprice']),
			'oDevTgprice'=>trim($_POST['tgprice'])
		);
		$rs = $this->model->device_insert($devinfo);
		$html = '<div class="record-list listli_1 list-li">
				<div class="selectall">
					<input type="checkbox" class="select_all sel_all_input" value="'.$rs.'" />
					<input type="hidden" class="select_hide" value="'.$ipid.'" />
				</div>
				<div class="rdname show-li">'.$_POST['devname'].'</div>
				<div class="rd_server show-li">'.$_POST['devtype'].'</div>
				<div class="rdip show-li">'.$_POST['ipname'].'</div>
				<div class="rdplace show-li">'.$_POST['engineroom'].'</div>
				<div class="rdbuy show-li">'.$_POST['devprice'].'</div>
				<div class="rddeposit show-li">'.$_POST['tgprice'].'</div>
				<div class="rdaction">		
					<div class="actdes">
						<a title="图形中心" href="index.php?c=paint&a=serverable&ipid='.$ipid.'">&nbsp;</a>
					</div>
					<div class="actprompt0">
						<a class="list-msg" title="补充信息">&nbsp;</a>
					</div>	
					<div class="actedit"><a title="编辑" href="index.php?c=device&a=editindex&id='.$rs.'">EDIT</a></div>
					<div class="actpause"><a class="list-pause"><img src="images/mon_pause.gif" /></a><a class="list-open" style="display:none;"><img src="images/mon_play.gif" /></a></div>
					<div class="actdel"><a class="list-del"><img src="images/mon_trash.gif" /></a></div>						
				</div>
				</div>';
		echo $html;return ;
	}
	
	/**
	 * list view ajax edit device 
	 */
	public function list_edit_ajax(){
		
		$id = trim($_POST['id']);
		$typeid = $roomid = '';
		if(!empty($_POST['devtype'])){
			$typeid = $this->model->devtype_getid(trim($_POST['devtype']));
		}
		if(!empty($_POST['engineroom'])){
			$roomid = $this->model->devroom_getid(trim($_POST['engineroom']));
		}
		if(isset($_POST['devlabel'])&!empty($_POST['devlabel'])){//标签处理
			$this->model->devlabel_hots($_POST['devlabel']);
		}
		$devinfo = array(
			'oDevName'=>trim($_POST['devname']),
			'oTypeName'=>trim($_POST['devtype']),
			'oTypeid'=>$typeid,
			'oEngineRoom'=>trim($_POST['engineroom']),
			'oRoomid'=>$roomid,
			'oDevPrice'=>trim($_POST['devprice']),
			'oDevTgprice'=>trim($_POST['tgprice'])
		);
		$this->model->device_update($id,$devinfo);
		$html = '
				<div class="rdname show-li">'.$_POST['devname'].'</div>
				<div class="rd_server show-li">'.$_POST['devtype'].'</div>
				<div class="rdip show-li">'.$_POST['ipname'].'</div>
				<div class="rdplace show-li">'.$_POST['engineroom'].'</div>
				<div class="rdbuy show-li">'.$_POST['devprice'].'</div>
				<div class="rddeposit show-li">'.$_POST['tgprice'].'</div>
				';
		echo $html;return ;
	}
	
	/**
	 * ajax device del
	 */
	public function device_del(){
		
		$id = trim($_POST['id']);
		$ipid = trim($_POST['ipid']);
		$this->model->ipinfo_delete($ipid);
		$this->model->device_delete($id);
		$content = "执行事件：删除服务器,服务器id：".$id;
		//osa_logs_save($content);
		return 'success_del';
	}
	
	
	/**
	 * ajax device stop
	 */
	public function device_stop(){
		
		$id = trim($_POST['id']);
		$ipid = trim($_POST['ipid']);
		$this->model->ip_pause($ipid);
		$this->model->device_pause($id);
		$content = "执行事件：暂停服务器,服务器id：".$id;
		//osa_logs_save($content);
		return 'success_stop';
	}
	
	
	/**
	 * ajax device open
	 */
	public function device_open(){
		
		$id = trim($_POST['id']);
		$ipid = trim($_POST['ipid']);
		$this->model->ip_open($ipid);
		$rs = $this->model->device_open($id);
		$content = "执行事件：开启服务器,服务器id：".$id;
		//osa_logs_save($content);
		return 'success_open';
	}
	
	
	/**
	 * ajax device del batch
	 */
	public function device_del_batch(){
		
		$idarr = $_POST['idarr'];
		$iparr = $_POST['iparr'];
		$num = count($idarr);
		for($i=0;$i<$num;$i++){
			$this->model->ipinfo_delete($iparr[$i]);
			$this->model->device_delete($idarr[$i]);
		}
		$content = "执行事件：批量删除服务器";
		//osa_logs_save($content);
		return 'success_del_batch';
	}
	
	
	/**
	 * ajax device stop batch
	 */
	public function device_stop_batch(){
	
		$idarr = $_POST['idarr'];
		$iparr = $_POST['iparr'];
		$num = count($idarr);
		for($i=0;$i<$num;$i++){
			$this->model->ip_pause($iparr[$i]);
			$this->model->device_pause($idarr[$i]);
		}
		$content = "执行事件：批量暂停服务器";
		//osa_logs_save($content);
		return 'success_stop_batch';
	}
	
	
	/**
	 * ajax device open batch
	 */
	public function device_open_batch(){
	
		$idarr = $_POST['idarr'];
		$iparr = $_POST['iparr'];
		$num = count($idarr);
		for($i=0;$i<$num;$i++){
			$this->model->ip_open($iparr[$i]);
			$this->model->device_open($idarr[$i]);
		}
		$content = "执行事件：批量开启服务器";
		//osa_logs_save($content);
		return 'success_open_batch';
		
	}
	
	
	/********************************end---  device list method ---end***********************************/
	
	
	public function device_more_message(){
	
		$id = $_POST['id'];
		$info = $this->model->device_select_id($id);
		$workdes = empty($info[0]['oWorkDes'])?"暂无描述":$info[0]['oWorkDes'];
		$devroom = empty($info[0]['oEngineRoom'])?"暂无描述":$info[0]['oEngineRoom'];
		$devlabel = empty($info[0]['oDevLabel'])?"暂无描述":$info[0]['oDevLabel'];
		$detail = empty($info[0]['oDevDetail'])?"暂无描述":$info[0]['oDevDetail'];
		$html = "
				<div style='padding:10px 20px;'>
					<p>业务描述：".$workdes."</p>
					<p>托管机房：".$devroom."</p>
					<p>设备标签：".$devlabel."</p>
					<p>设备详情：".$detail."</p>
				</div>";
		echo $html;return ;
	}
	
	
}