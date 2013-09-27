<?php
class Home extends osa_controller{

	private $model = '';
	public function __construct(){
		parent::__construct();
		$this->model = $this->loadmodel('mhome');
		if(!isset($_SESSION)){
			session_start();
		}
	}
	
	public function index(){
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		$data['username'] = $_SESSION['username'];
		$data['menu'] = 'home';
		$data['config'] = $this->loadconfig('osa_config_shortcut');
		$data['shortcut']  = $this->model->getShortCut($_SESSION['id']);
		$data['loginuser'] = $this->model->getLoginUser();
		$data['loginip'] = $this->model->getLoginIp();
		$data['operate'] = $this->model->getLastOperate();
		$data['mysql_version'] = $this->model->getMysqlVersion();
		$numkey = $this->model->getLastPatch();
		$params= array('numkey'=>$numkey);
		$info = osa_restaction('GET',$params ,OSA_WEBSERVER_DOMAIN.'/interface.php');
		//print_r((array)json_decode($info));return;
		$data['info'] = (array)json_decode($info);
		$this->loadview('home/home',$data);
		
	}
}