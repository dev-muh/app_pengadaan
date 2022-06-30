<?php 
class Model_tandatangan extends CI_Model {
	public function index(){

	}

	public function getDataTTD(){
		$query = $this->db->limit(1)->order_by('id','desc')->get_where('tandatangan',array('is_delete'=>0));

		if($query->num_rows()>0){
			return $query->row();
		}else{
			return null;
		}
	}
}