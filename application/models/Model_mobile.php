<?php 
class Model_mobile extends CI_Model {
	function __construct(){
		parent::__construct();
		$this->load->library('KirimFcm'); 
	}
	public function ls_pemesanan($gudang=false){
		$gdg = '';

		if($gudang){
			$gdg = " or p.`status`=0";
		}
		$query = $this->db->query('select 	
										p.id as id_pemesanan,
										p.id_pemesan,
										u_p.name as pemesan,
										p.no_pemesanan, 
										p.tgl_pemesanan, 
										p.`status`,
										u_p.group,
										u_p.lantai,
										(select u.id from user as u where u.id=p.id_kurir) as id_kurir,
										(select u.name from user as u where u.id=p.id_kurir) as kurir,
										u_u.user_type,
										(select rt.rate from rating as rt where rt.id_pemesanan=p.id) as rate,
										(select count(*) as total from item_pemesanan where id_pemesanan=p.id) as total_barang,
										ip.qty,
										u_u.username,
										u_u.name as update_by

								from pemesanan as p
										join item_pemesanan as ip
										join user as u_u
										join user as u_p
								where p.is_delete="0" 
										and p.update_by=u_u.id
										and p.id=ip.id_pemesanan
										and p.id_pemesan=u_p.id
										and (p.`status`=1 or p.`status`=0)
								group by p.id');

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}	
	}

	public function ls_pemesanan_by_kurir($id_kurir=null){
		$query = $this->db->query('select 	
										p.id as id_pemesanan,
										p.id_pemesan,
										u_p.name as pemesan,
										p.no_pemesanan, 
										p.tgl_pemesanan, 
										p.`status`,
										(select group_name from `group` where id=u_p.group) as `group`,
										u_p.lantai,
										(select u.id from user as u where u.id=p.id_kurir) as id_kurir,
										p.add_kurir_date,
										(select u.name from user as u where u.id=p.id_kurir) as kurir,
										u_u.user_type,
										(select rt.rate from rating as rt where rt.id_pemesanan=p.id) as rate,
										(select rt.comment from rating as rt where rt.id_pemesanan=p.id) as comment,
										#(select count(*) as total from item_pemesanan where id_pemesanan=p.id) as total_barang,
										u_u.username,
										u_u.name as update_by
								from pemesanan as p
										join item_pemesanan as ip
										join user as u_u
										join user as u_p
								where p.is_delete="0" 
										and id_kurir='.$id_kurir.'
										and p.update_by=u_u.id
										and p.id=ip.id_pemesanan
										and p.id_pemesan=u_p.id
								group by p.id order by p.add_kurir_date desc limit 20');

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}	
	}

	public function ls_pemesanan_by_pemesan($id_staff=null){
		$query = $this->db->query('select 	
										p.id as id_pemesanan,
										p.id_pemesan,
										p.no_pemesanan, 
										p.tgl_pemesanan, 
										p.`status`,
										(select u.id from user as u where u.id=p.id_pemesan) as id_pemesan,
										(select u.name from user as u where u.id=p.id_pemesan) as pemesan,
										(select u.id from user as u where u.id=p.id_kurir) as id_kurir,
										(select u.name from user as u where u.id=p.id_kurir) as kurir,
										u_u.user_type,
										(select count(*) as total from item_pemesanan where id_pemesanan=p.id) as total_barang,
										u_u.username,
										u_u.name as update_by
								from pemesanan as p
										join item_pemesanan as ip
										join user as u_u
								where p.is_delete="0" 
										and id_pemesan='.$id_staff.'
										and p.update_by=u_u.id
										and p.id=ip.id_pemesanan
										and p.id_pemesan='.$id_staff.'
								group by p.id');
		//print_r($query->result());

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}	
	}

	public function ls_it_pemesanan($id=null){
		$query = $this->db->query('	select 	p.id,p.no_pemesanan,
											p.id_pemesan,
												p.tgl_pemesanan,
												p.`status`,
												ip.qty,
												ip.qty_masuk,
												p.update_by,
												p.update_date,
												ip.id as id_it_pn,
												ip.note as note,
												i.item_name,
												i.barcode,
												i.id as id_item,
												i.qty as stock,
												i.satuan,
												(select up.`group` from pemesanan as pm join user as up where pm.id_pemesan=up.id and pm.id='.$id.') as `group`,
												(select up.lantai from pemesanan as pm join user as up where pm.id_pemesan=up.id and pm.id='.$id.') as lantai,
												(select rt.rate from rating as rt where rt.id_pemesanan='.$id.') as rate,
												(select rt.comment from rating as rt where rt.id_pemesanan='.$id.') as comment
												
												

									from pemesanan as p
											join item_pemesanan as ip
											join pos_item as i
											
											
									where p.id='.$id.' and ip.id_pemesanan=p.id and ip.id_item=i.id and ip.is_delete=0');

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}

	public function ck_kurir($id_kurir=null){
		$this->db->where('id',$id_kurir);
		$this->db->where('is_delete',0);
		$this->db->where('is_active',1);
		$this->db->where('user_type','Kurir');
		$kurir = $this->db
							->select('id')
							->select('username')
							->select('name')
							->from('user')
							->get();

		if($kurir->num_rows()>0){
			return $kurir->result();
		}else{
			return null;
		}

	}

	public function ck_staff($id_staff=null){
		$this->db->where('id',$id_staff);
		$this->db->where('is_delete',0);
		$this->db->where('is_active',1);
		$this->db->where('user_type','Karyawan');
		$staff = $this->db
							->select('id')
							->select('username')
							->select('name')
							->from('user')
							->get();

		if($staff->num_rows()>0){
			return $staff->result();
		}else{
			return null;
		}

	}

	public function ck_rate($id_pemesanan=null){
		$this->db->where('id_pemesanan',$id_pemesanan);
		$rate = $this->db->select('rate')->from('rating')->get();

		if($rate->num_rows()>0){
			return $rate->row()->rate;
		}else{
			return null;
		}
	}

	public function getPhoto($id=null){
		//$id=$this->input->post('id');
		$res=$this->db->get_where('img_item',array('id_item'=>$id));
		if ($res->num_rows() > 0){
		    return json_encode($res->result());
		}else{
		    return NULL;
		}
	}

	public function search_pemesanan($id=null,$str=null){
		$query = $this->db->query("select 	
										p.id as id_pemesanan,
										p.id_pemesan,
										u_p.name as pemesan,
										p.no_pemesanan, 
										p.tgl_pemesanan, 
										p.`status`,
										(select u.id from user as u where u.id=p.id_pemesan) as id_pemesan,
										(select u.name from user as u where u.id=p.id_pemesan) as pemesan,
										(select u.id from user as u where u.id=p.id_kurir) as id_kurir,
										(select u.name from user as u where u.id=p.id_kurir) as kurir,
										u_u.user_type,
										(select count(*) as total from item_pemesanan where id_pemesanan=p.id) as total_barang,
										u_u.username,
										u_u.name as update_by,
										ip.id_pemesanan
								from pemesanan as p
										join item_pemesanan as ip
										join pos_item as i
										join user as u_u
										join user as u_p
								where p.is_delete=0
										and id_pemesan=".$id."
										and p.update_by=u_u.id
										and p.id=ip.id_pemesanan
										and p.id_pemesan=".$id."
										and ip.id_item=i.id
										and i.item_name like '%".$str."%'
								group by p.id");
		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}

	}

	public function send_notif($id=null,$title=null,$message=null){
		$user = $this->db->get_where('user',array('id'=>$id));
		$token = $user->row()->token;

		return json_encode($this->kirimfcm->kirim(array('reg_id'=>$token,'title'=>$title,'message'=>$message)));	
	}



}
?>