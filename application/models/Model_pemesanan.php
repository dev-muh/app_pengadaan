<?php 
class Model_pemesanan extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	public function getSPB($id_permintaan=null){
		$query = $this->db->query('	select 	
											s.*,
											s.`status` as status_spb,
											u_i.name as dibuat_oleh,
											u_u.name as diubah_oleh

									from 
											spb as s 
											join user as u_i
											join user as u_u
									where 	
											s.insert_by=u_i.id 
											and s.update_by=u_u.id
											and s.id_permintaan='.$id_permintaan.'
											and s.is_delete=0');

		if ($query->num_rows()>0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}
}
?>