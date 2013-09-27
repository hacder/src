<?php
class home extends osa_controller{

	private $model = null;
	private $page = null;

	public function __construct(){
	
		parent::__construct();
		$this->model = $this->loadmodel('mhome');
		$this->page = $this->loadmodel('mpage');
		
		if(!isset($_SESSION)){
			session_start();
		}
		$_SESSION['header'] = "home";
	}
	
	
	
	public function index(){
	
		if(!isset($_SESSION['username'])){
			header("Location: index.php?c=login&a=index", TRUE, 302);
		}
		
		$data['server'] = $this->model->home_server_graph();
		$data['item'] = $this->model->home_item_graph();	
		$this->loadview('home/index',$data);
	}
	
	
	public function getalarmnum(){
		
		$itemnum = $this->model->home_item_alarmnum();
		$servernum = $this->model->home_server_alarmnum();
		$total = $itemnum + $servernum ;
		echo $total;
		return ;
	}

}