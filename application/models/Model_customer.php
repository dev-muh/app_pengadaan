<?php 
class Model_customer extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	public function index(){

	}

	public function group(){
		$q = $this->db->query("select * from (
				select 
					lg.id_group,lg.nama_awal,lg.nama_baru
				from 
					log_group as lg 
				where 
					is_change=0
				group by lg.id_group
				order by lg.id desc
			) as l
			right join `group` as g on l.id_group=g.id 
			order by g.group_name");

		if($q->num_rows()>0){
			return $q->result();
		}else{
			return null;
		}
	}



}
?>