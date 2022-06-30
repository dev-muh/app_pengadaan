<?php 
class Model_master extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	public function master_supplier_all(){

		$query = $this->db->query('select * from supplier where is_delete=0 order by insert_date desc');

		if($query->num_rows()>0){
			return $query->result();
		}else{
			return null;
		}
	}

	public function SQL_MODE(){
		$set_SQL = $this->db->query('set global sql_mode="NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"');
		$sql_mode = 0;
		if($set_SQL){
			$ck_SQL = $this->db->query('select @@sql_mode as sql_mode');

			if($ck_SQL->num_rows()>0){
				if($ck_SQL->row()->sql_mode=='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'){
					$sql_mode=1;
					return true;
				}else{
					return false;
				}
			}
		}
	}

	public function master_harga_all(){

		if($this->SQL_MODE()){
			$query = $this->db->query('	select 
												h.*,
												i.item_name,
												i.barcode,
												count(h.id_item) as jml_supplier
										from
												harga as h
												join pos_item as i
												join supplier as s
												join user as u

										where 
												h.id_item=i.id
												and h.id_supplier=s.id
												and h.update_by=u.id
												and h.is_delete=0
										group by h.id_item
										order by h.insert_date desc
									');

			if($query->num_rows()>0){
				return $query->result();
			}else{
				return null;
			}
		}else{
			redirect(base_url('master/master_harga_all'));
		}
	}

	public function master_supplier($id_item=null){

		$query = $this->db->query('select s.*,h.* from harga as h join supplier as s where h.id_supplier=s.id and h.id_item='.$id_item);

		if($query->num_rows()>0){
			return $query->result();
		}else{
			return null;
		}
	}

	public function ls_sup_by_id($id=null){
		$query = $this->db->query('	select 
											h.*,
											i.item_name,
											i.barcode,
											s.supplier_name
									from
											harga as h
											join pos_item as i
											join supplier as s
											join user as u

									where 
											h.id_item=i.id
											and h.id_supplier=s.id
											and h.update_by=u.id
											and h.is_delete=0
											and h.id_item='.$id.'

									order by h.update_date desc');

		if($query->num_rows()>0){
			return $query->result();
		}else{
			return null;
		}
	}
}
?>