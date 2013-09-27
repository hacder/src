<?php
class mfilecenter extends osa_model{

	public function __construct(){
	
		parent::__construct();
	}
	
	
	
	/************************** page per list ********************************/
	
	/**
	 * select files per page
	 */
	public function files_select_page($search ,$perpage ,$offset){
		$sql = "select * from osa_upload_file where 1";
		if(!empty($search)){
			$sql .=" and oFileName like '%$search%' ";
		}
		$sql .=" order by id desc";
		$sql .= " limit $offset , $perpage";
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	/**
	 * select files num
	 */
	public function files_select_num($search){
		
		$sql = "select count(id) as num from osa_upload_file where 1";
		if(!empty($search)){
			$sql .=" and oFileName like '%$search%' ";
		}
		$rs = $this->db->queryFetchAllAssoc($sql);
		return $rs[0]['num'];
	}
	
	
	public function files_delete($id){
	
		$rs = $this->files_select_id($id);
		$this->files_delete_id($id);
		@unlink($rs[0]['oRealPath']);
		
	}
	
	
	public function files_select_id($id){
	
		$sql = "select * from osa_upload_file where id=".$id ;
		return $this->db->queryFetchAllAssoc($sql);
	}
	
	public function files_delete_id($id){
	
		$sql = "delete from osa_upload_file where id=".$id;
		$this->db->exec($sql);
	}
}