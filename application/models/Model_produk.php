<?php 
class Model_produk extends CI_Model {
	function __construct(){
		parent::__construct();
	}
//#################################### KATEGORI ##############################################
	public function tb_kategori(){
	 	$query = $this->db->query("select mt.id, mt.code, mt.description, u.username as update_by_username, mt.update_date from pos_kategori as mt left join user as u on u.id = mt.update_by where mt.is_delete = '0' order by mt.insert_date desc");

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}
	public function add($code,$nama){
		$response=false;
		if($code == null || $nama == null){
			$response = false;
			return $response;
		}else{
			$session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
			$data=array(
						'pos_type'=>'KANTOR',
						'code'=>$code,
						'description'=>$nama,
						'insert_by'=>$session_id,
						'update_by'=>$session_id
					);
			$add_stat = $this->db->insert('pos_kategori',$data);
			

			if($add_stat){
				$response = true;
			}else{
				$response = false;

			}
			return $response;
			
		}
		
	}
	public function edit($id,$code,$nama){
		$update_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';

        $data= array(
              'description'=>$nama,
              'code'=>$code,

              'update_by'=>$update_by,
        );

        //update data
        $this->db->where('id',$id);
        $save = $this->db->update('pos_kategori',$data);

        if($save){
              $response = array (
                    "status" => 'success',                    
                    "message" => 'Data updated'
              );
        }else{                                                
              $response = array(
                    "status" => 'error',                      
                    "message" => 'Error: Update failed'
              );
        }   
        return $response;        
	}
	public function result_add(){
		$query = $this->db->query("select mt.id, mt.code, mt.description, u.username as update_by_username, mt.update_date from pos_kategori as mt left join user as u on u.id = mt.update_by where mt.is_delete = '0' and mt.id = (select last_insert_id())");


	    if ($query->num_rows() > 0){
	          return $query->row();
	    }else{
	          return NULL;
	    }
	}
	public function del_kategori(){
		$id = $this->input->post('id');
		$delete_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
		$delete_date = date('Y-m-d H:i:s');

		$this->db->where('id',$id);
		$save = $this->db->update('pos_kategori',array('delete_by'=>$delete_by, 'delete_date'=>$delete_date, 'is_delete'=>'1')); 

		if($save){
		    $response = array(
		          "status" => 'success',                    
		          "message" => 'Success delete market type'
		    );
		}else{
		     $response = array(
		          "status" => 'error',                      
		          "message" => 'Error: Failed delete market type'
		    );
		}           

		return $response;
	}

//####################################   SUB KATEGORI ########################################
	public function tb_sub_kategori(){
		$query = $this->db->query("select 
									pk.code as kategori_code,
									pk.description as deskripsi_kategori,
									mt.id,
									mt.sub_kategori_code,
									mt.sub_description,
									pk.id as id_kat, 
									u.username as update_by_username, 
									mt.update_date 
								from 
									pos_kategori as pk,
									pos_sub_kategori as mt left join user as u on u.id = mt.update_by where mt.is_delete = '0' and pk.is_delete = '0' and pk.id=mt.id_kategori order by mt.insert_date desc");


		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}
	public function add_sub($id_kat,$code,$nama){
		$response=false;
		if($id_kat == null || $code == null || $nama == null){
			$response = false;
			return $response;
		}else{
			$session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
			$data=array(
						'pos_type'=>'KANTOR',
						'id_kategori'=>$id_kat,
						'sub_kategori_code'=>$code,
						'sub_description'=>$nama,
						'insert_by'=>$session_id,
						'update_by'=>$session_id
					);
			$add_stat = $this->db->insert('pos_sub_kategori',$data);
			

			if($add_stat){
				$response = true;
			}else{
				$response = false;

			}
			return $response;
			
		}	
	}
	public function result_add_sub(){
		$query = $this->db->query("select 
									pk.code as kategori_code,
									pk.description as deskripsi_kategori,
									mt.id,
									mt.sub_kategori_code,
									mt.sub_description,
									pk.id as id_kat, 
									u.username as update_by_username, 
									mt.update_date 
								from 
									pos_kategori as pk,
									pos_sub_kategori as mt left join user as u on u.id = mt.update_by where mt.is_delete = '0' and pk.is_delete = '0' and pk.id=mt.id_kategori and mt.id = (select last_insert_id())");


	    if ($query->num_rows() > 0){
	          return $query->row();
	    }else{
	          return NULL;
	    }
	}
	public function edit_sub($id,$id_kat,$code,$nama){
		$update_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';

        $data= array(
        		'id_kategori'=>$id_kat,
             	'sub_description'=>$nama,
             	'sub_kategori_code'=>$code,
             	'update_by'=>$update_by,
        );

        //update data
        $this->db->where('id',$id);
        $save = $this->db->update('pos_sub_kategori',$data);

        if($save){
              $response = array (
                    "status" => 'success',                    
                    "message" => 'Data updated'
              );
        }else{                                                
              $response = array(
                    "status" => 'error',                      
                    "message" => 'Error: Update failed'
              );
        }   
        return $response;        
	}
	public function del_sub_kategori(){
		$id = $this->input->post('id');
		$delete_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
		$delete_date = date('Y-m-d H:i:s');

		$this->db->where('id',$id);
		$save = $this->db->update('pos_sub_kategori',array('delete_by'=>$delete_by, 'delete_date'=>$delete_date, 'is_delete'=>'1')); 

		if($save){
		    $response = array(
		          "status" => 'success',                    
		          "message" => 'Success delete market type'
		    );
		}else{
		     $response = array(
		          "status" => 'error',                      
		          "message" => 'Error: Failed delete market type'
		    );
		}           

		return $response;
	}
//################################### ITEM ####################################################
	public function tb_item_in_master(){
		$query = $this->db->query("select      	i.id as ID_ITEM,
									            kt.code, 
									            sb.id as id_sub,
									            kt.id as id_kat,
									            sb.sub_kategori_code, 
									            kt.code,  
									            u_i.username as uodate_by_u_i, 
									            i.barcode,
									            i.qty,
									            i.min_qty,
									            i.max_qty,
									            i.satuan,
									            i.deskripsi_satuan,
									             
									            i.item_name as nama_item,
									            sb.sub_description as jenis_item,
									            kt.description as kategori_item,
									            i.selling_price,
									            i.selling_cost,
									            i.cost_percentage,
									            sb.update_date, 
									            i.is_delete

									from        pos_kategori as kt, 
									            pos_item as i join user as u_i on u_i.id=i.update_by, 
									            pos_sub_kategori as sb left join user as u on u.id = sb.update_by

									where       sb.is_delete = '0' and 
									            kt.is_delete = '0' and 
									            i.id_sub_kategori=sb.id AND
									            i.id_kategori=kt.id AND 
									            i.id_sub_kategori=sb.id order by i.item_name asc");


		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}

	public function tb_item(){
		$query = $this->db->query("select      	i.id as ID_ITEM,
									            kt.code, 
									            sb.id as id_sub,
									            kt.id as id_kat,
									            sb.sub_kategori_code, 
									            kt.code,  
									            u_i.username as uodate_by_u_i, 
									            i.barcode,
									            i.qty,
									            i.min_qty,
									            i.max_qty,
									            i.satuan,
									            i.deskripsi_satuan,
									            (select img_name from img_item where id_item=i.id) as img_name,
									             
									            i.item_name as nama_item,
									            sb.sub_description as jenis_item,
									            kt.description as kategori_item,
									            i.selling_price,
									            i.selling_cost,
									            i.cost_percentage,
									            sb.update_date, 
									            sb.is_delete

									from        pos_kategori as kt, 
									            pos_item as i join user as u_i on u_i.id=i.update_by, 
									            pos_sub_kategori as sb left join user as u on u.id = sb.update_by

									where       sb.is_delete = '0' and 
									            kt.is_delete = '0' and 
									            i.is_delete = '0' and 
									            i.id_sub_kategori=sb.id AND
									            i.id_kategori=kt.id AND 
									            i.id_sub_kategori=sb.id order by i.item_name asc");


		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}
	public function add_item(){
		$mode = $this->input->post('mode');
		$barcode = $this->input->post('barcode');
		$nama = $this->input->post('nama');
		$kat_id = $this->input->post('kat_id');
		$sub_kat_id = $this->input->post('sub_kat_id');
		$qty = $this->input->post('qty');
		$min_qty = $this->input->post('min_qty');
		$max_qty = $this->input->post('max_qty');
		$sat = $this->input->post('sat');
		$sat_des = $this->input->post('sat_des');
		// $selling_price = $this->input->post('selling_price');
		// $selling_cost = $this->input->post('selling_cost');
		// $cost_percentage = $this->input->post('cost_percentage');


		
		$response=false;
		if(	$mode == null || 
			$nama == null ||
			$kat_id == null || 
			$min_qty == null || 
			$max_qty == null || 
			$sub_kat_id == null
			// $selling_price == null ||
			// $selling_cost == null || 
			// $cost_percentage == null
			){
				$response = false;
				return $response;
		}else{
			$session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
			$data = array(
						'pos_type'=>'KANTOR',
						'barcode'=>$barcode,
						'item_name'=>$nama,
						'id_kategori'=>$kat_id,
						'id_sub_kategori'=>$sub_kat_id,
						'stock_awal'=>$qty,
						'qty'=>$qty,
						'min_qty'=>$min_qty,
						'max_qty'=>$max_qty,
						'satuan'=>$sat,
						'deskripsi_satuan'=>$sat_des,
						// 'selling_price'=>$selling_price,
						// 'selling_cost'=>$selling_cost,
						// 'cost_percentage'=>$cost_percentage,
						'insert_by'=>$session_id,
						'update_by'=>$session_id
					);
			$add_stat = $this->db->insert('pos_item',$data);
			

			if($add_stat){
				$response = true;
			}else{
				$response = false;

			}
			
			return $response;
			
		}	
	}
	public function result_add_item(){
		$query = $this->db->query("select      	i.id as ID_ITEM,
									            kt.code, 
									            sb.id as id_sub,
									            kt.id as id_kat,
									            sb.sub_kategori_code, 
									            kt.code,  
									            u.username as update_by_username, 
									            i.barcode,
									            i.qty,
									            i.min_qty,
									            i.max_qty,
									            i.satuan,
									            i.deskripsi_satuan,
									             
									            i.item_name as nama_item,
									            sb.sub_description as jenis_item,
									            kt.description as kategori_item,
									            i.selling_price,
									            i.selling_cost,
									            i.cost_percentage,
									            sb.update_date, 
									            sb.is_delete

									from        pos_kategori as kt, 
									            pos_item as i, 
									            pos_sub_kategori as sb left join user as u on u.id = sb.update_by

									where       sb.is_delete = '0' and 
									            kt.is_delete = '0' and 
									            i.is_delete = '0' and
									            i.id_sub_kategori=sb.id AND
									            i.id_kategori=kt.id AND 
									            i.id_sub_kategori=sb.id and
									            i.id = (select last_insert_id())");


	    if ($query->num_rows() > 0){
	          return $query->row();
	    }else{
	          return NULL;
	    }
	}
	public function edit_item(){
		$id = $this->input->post('id');
		$mode = $this->input->post('mode');
		$barcode = $this->input->post('barcode');
		$nama = $this->input->post('nama');
		$min_qty = $this->input->post('min_qty');
		$max_qty = $this->input->post('max_qty');
		$kat_id = $this->input->post('kat_id');
		$sub_kat_id = $this->input->post('sub_kat_id');
		//$qty = $this->input->post('qty');
		// $selling_price = $this->input->post('selling_price');
		// $selling_cost = $this->input->post('selling_cost');
		// $cost_percentage = $this->input->post('cost_percentage');
		$update_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';


		$response=false;
		if(	$mode == null || 
			$nama == null ||
			$min_qty == null || 
			$max_qty == null || 
			$kat_id == null || 
			$sub_kat_id == null
			// $selling_price == null ||
			// $selling_cost == null || 
			// $cost_percentage == null
			){
				$response = false;
				return $response;
		}else{
			$session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
			$data = array(
						'pos_type'=>'KANTOR',
						'barcode'=>$barcode,
						'item_name'=>$nama,
						'id_kategori'=>$kat_id,
						'id_sub_kategori'=>$sub_kat_id,
						'min_qty'=>$min_qty,
						'max_qty'=>$max_qty,
						//'qty'=>$qty,
						// 'selling_price'=>$selling_price,
						// 'selling_cost'=>$selling_cost,
						// 'cost_percentage'=>$cost_percentage,
						'insert_by'=>$session_id,
						'update_by'=>$session_id
					);

			$this->db->where('id',$id);
			$add_stat = $this->db->update('pos_item',$data);
			

			if($add_stat){
				$response = true;
			}else{
				$response = false;

			}
			
			return $response;
			
		}	      
	}
	public function del_item(){
		$id = $this->input->post('id');
		$delete_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
		$delete_date = date('Y-m-d H:i:s');

		$this->db->where('id',$id);
		$save = $this->db->update('pos_item',array('delete_by'=>$delete_by, 'delete_date'=>$delete_date, 'is_delete'=>'1')); 

		if($save){
		    $response = array(
		          "status" => 'success',                    
		          "message" => 'Sukses menonaktifkan Item'
		    );
		}else{
		     $response = array(
		          "status" => 'error',                      
		          "message" => 'Error: terjadi kesalahan saat mengaktifkan item<br>Error-Code:ACT-IT(6757356)'
		    );
		}           

		return $response;
	}

	public function activate_item(){
		$id = $this->input->post('id');
		$delete_by = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
		$delete_date = date('Y-m-d H:i:s');

		$this->db->where('id',$id);
		$save = $this->db->update('pos_item',array('delete_by'=>$delete_by, 'delete_date'=>'', 'is_delete'=>'0')); 

		if($save){
		    $response = array(
		          "status" => 'success',                    
		          "message" => 'Sukses mengaktifkan item'
		    );
		}else{
		     $response = array(
		          "status" => 'error',                      
		          "message" => 'Error: terjadi kesalahan saat mengaktifkan item<br>Error-Code:ACT-IT(7823428374)'
		    );
		}           

		return $response;
	}

	public function saveImgToDB($id,$name){
		$query = $this->db->query('INSERT INTO img_item (id_item,img_name) VALUES ('.$id.',"'.$name.'") ON DUPLICATE KEY UPDATE img_name="'.$name.'",id_item="'.$id.'"');

		if($query){
			return true;
		}else{
			return false;
		}
	}


	public function ck_barcode($barcode=null){
		$query = $this->db->query("select      	i.id as ID_ITEM,
									            kt.code, 
									            sb.id as id_sub,
									            kt.id as id_kat,
									            sb.sub_kategori_code, 
									            kt.code,  
									            u.username as update_by_username, 
									            i.barcode,
									             
									            i.item_name as nama_item,
									            sb.sub_description as jenis_item,
									            kt.description as kategori_item,
									            i.selling_price,
									            i.selling_cost,
									            i.cost_percentage,
									            sb.update_date, 
									            sb.is_delete

									from        pos_kategori as kt, 
									            pos_item as i, 
									            pos_sub_kategori as sb left join user as u on u.id = sb.update_by

									where       sb.is_delete = '0' and 
									            kt.is_delete = '0' and 
									            i.is_delete = '0' and
									            i.id_sub_kategori=sb.id AND
									            i.id_kategori=kt.id AND 
									            i.id_sub_kategori=sb.id AND
									            i.barcode=$barcode");


		if ($query->num_rows() > 0){
		    return "ADA";
		}else{
		    return "TIDAK";
		}
	}

//#################################### EXTEND ITEM ############################################
	public function tb_item_is_av($text=null){
		$searchText = '';
		if(!empty($text)){
			$searchText = " and item_name like '%" . $text . "%' ";
		}
		$query = $this->db->query("select      	i.id as ID_ITEM,
									            kt.code, 
									            sb.id as id_sub,
									            kt.id as id_kat,
									            sb.sub_kategori_code, 
									            kt.code,  
									            u.username as update_by_username, 
									            i.barcode,
									            i.qty,
									            i.satuan,
									            i.deskripsi_satuan,
									            (select img_name as img from img_item where id_item=i.id) as img,
									            
									            i.item_name as nama_item,
									            sb.sub_description as jenis_item,
									            kt.description as kategori_item,
									            i.selling_price,
									            i.selling_cost,
									            i.cost_percentage,
									            sb.update_date, 
									            sb.is_delete

									from        pos_kategori as kt, 
									            pos_item as i, 
									            pos_sub_kategori as sb left join user as u on u.id = sb.update_by

									where       sb.is_delete = '0' and 
									            kt.is_delete = '0' and 
									            i.is_delete = '0' and
									            i.id_sub_kategori=sb.id AND
									            i.id_kategori=kt.id AND 
									            i.id_sub_kategori=sb.id ". $searchText ." AND
									            i.qty>0 order by i.item_name asc");

		// echo $this->db->_error_message();
		// echo $this->db->_error_number();

		//show_error($message, $status_code, $heading = 'An Error Was Encountered');
		$err = $this->db->error();
		if($query){
		 	if ($query->num_rows() > 0){
			    return $query->result();
			}else{
			    // return json_decode('{"status":"-1","message":"Data Kosong"}',true);
			}
		}else{
			//echo $err['code'];
			return json_decode('{"status":"'.$err['code'].'","message":"'.$err['message'].'"}',true);
		}

	}
	
}
?>