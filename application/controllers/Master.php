<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(count($_SESSION)<=1){ 
			redirect('login');
		}
	}

	public function master_supplier_all(){
		$this->load->model('model_master');
		$var['title']='MASTER DATA';
		$var['master']='Supplier';
		$var['s_active']='master_supplier';
		$var['mode']='view';
		$var['act_button']='master_supplier';
		$var['page_title']='MASTER DATA SUPPLIER';
		$var['tb_data_supplier'] = $this->model_master->master_supplier_all();

		$var['user'] = $_SESSION['user_type'];
		
		$var['js'] = 'js-master';
		$var['plugin'] = 'plugin_1';
		$var['content']='view-master';
		
		$this->load->view('view-index',$var);
	}

	public function add_master_supplier(){
		$supplier = $this->input->post('supplier');
		$mode = $this->input->post('mode');
		$id = $this->input->post('id');

		$supplier['update_by']=$_SESSION['id_user'];
		$supplier['insert_by']=$_SESSION['id_user'];

		if($mode=='edit'){
			$this->db->where('id',$id);
			$upd_sup = $this->db->update('supplier',$supplier);

			if($upd_sup){
				$res = array(
								'status'=>2,
								'message'=>'Sukses mengubah Data Supplier',
								'result'=>null
							);

				echo json_encode($res);
			}
		}else{
			$this->db->where('supplier_id',$supplier['supplier_id']);
			$this->db->select('*');
			$validate = $this->db->get('supplier');

			if($validate->num_rows()>0){
				$res = array(
								'status'=>-1,
								'message'=>'ID Sudah terdaftar',
								'result'=>null
							);

				echo json_encode($res);
			}else{
				$insert_supplier = $this->db->insert('supplier',$supplier);
				$result = $this->db->where('id',$this->db->insert_id())->select('*')->get('supplier');
				$data_sup = null;

				if($result->num_rows()>0){
					$data_sup = $result->result()[0];
				}
				
				if($insert_supplier){
					$res = array(
									'status'=>1,
									'message'=>'Sukses Menambahkan Supplier',
									'result'=>$data_sup
								);

					echo json_encode($res);
				}else{
					$res = array(
									'status'=>0,
									'message'=>'Gagal Menambahkan Supplier',
									'result'=>null
								);

					echo json_encode($res);
				}
			}

		}

	}

	public function ck_id($id=null){
		$this->db->where('supplier_id',$id);
		$this->db->select('*');
		$validate = $this->db->get('supplier');

		if($validate->num_rows()>0){
			$res = array(
							'status'=>0,
							'message'=>'ID Sudah terdaftar',
							'result'=>null
						);

			echo json_encode($res);
		}else{
			$res = array(
							'status'=>1,
							'message'=>'ID Dapat Digunakan',
							'result'=>null
						);

			echo json_encode($res);
		}
	}

	public function add_master_harga(){
		$post = $this->input->post('data');
		
		$post[0]['update_by']=$_SESSION['id_user'];
		$post[0]['insert_by']=$_SESSION['id_user'];
		$this->db->insert('harga',$post[0]);
	}

	public function master_harga_all(){
		$var['title']='MASTER DATA';
		$this->load->model('model_master');
		$var['master']='Harga';
		$var['s_active']='master_harga';
		$var['mode']='view';
		$var['act_button']='master_harga';
		$var['page_title']='MASTER DATA HARGA';
		$var['tb_data_harga'] = $this->model_master->master_harga_all();

		$var['user'] = $_SESSION['user_type'];
		
		$var['js'] = 'js-master';
		$var['plugin'] = 'plugin_1';
		$var['content']='view-master';
		
		$this->load->view('view-index',$var);
	}

	public function add_harga_sup(){
		$this->load->model('model_master');
		$this->load->model('model_produk');

		$var['master']='Harga';
		$var['s_active']='master_harga';
		$var['mode']='add';
		$var['act_button']='master_harga';
		$var['page_title']='MASTER DATA HARGA';
		$var['tb_data_harga'] = $this->model_master->master_harga_all();
		$var['tb_item'] = $this->model_produk->tb_item();

		$var['user'] = $_SESSION['user_type'];
		
		$var['js'] = 'js-master';
		$var['plugin'] = 'plugin_1';
		$var['content']='view-master';
		
		$this->load->view('view-index',$var);
	}

	public function sh_tb_sup_harga($id=null,$mode=null){
		$var['mode']=$mode;
		$this->load->model('model_master');
		$var['ls_sup'] = $this->model_master->ls_sup_by_id($id);
		$var['ls_sup_all'] = $this->model_master->master_supplier_all();

		$var['it_info'] = $this->db->where('id',$id)->where('is_delete',0)->select('*')->get('pos_item');
		$var['item_info'] = null;
		if($var['it_info']->num_rows()>0){
			$var['item_info'] = $var['it_info']->result();
		}else{
			$var['item_info'] = null;
		}
		// print_r($var['item_info']);
		$this->load->view('view-tb_sup_harga',$var);
	}

	public function get_harga_supplier($id_item=null){
		$this->load->model('model_master');
		$var['ls_sup'] = $this->model_master->master_supplier($id_item);
		echo json_encode($var['ls_sup']);
	}

	public function delete_supplier_frm_produk(){
		$id = $_POST['id'];

		$this->db->where('id',$id);
		$upd_hrg = $this->db->update('supplier',array('is_delete'=>1));

		if($upd_hrg){
			$res = array(
							'status'=>1,
							'message'=>'Berhasil menghapus data Supplier',
							'result'=>null
						);

			echo json_encode($res);
		}else{
			$res = array(
							'status'=>0,
							'message'=>'Gagal menghapus supplier',
							'result'=>null
						);

			echo json_encode($res);
		}

	}

	public function delete_hrg(){
		$id_sup = $_POST['id_sup'];
		$id_it = $_POST['id_it'];

		$this->db->where('id_supplier',$id_sup)->where('id_item',$id_it);
		$upd_hrg = $this->db->update('harga',array('is_delete'=>1));

		if($upd_hrg){
			$res = array(
							'status'=>1,
							'message'=>'Berhasil menghapus data Supplier',
							'result'=>null
						);

			echo json_encode($res);
		}else{
			$res = array(
							'status'=>0,
							'message'=>'Gagal menghapus supplier',
							'result'=>null
						);

			echo json_encode($res);
		}

	}

	public function ch_harga(){
		$id_sup = $_POST['id_sup'];
		$id_it = $_POST['id_it'];
		$hrg = $_POST['harga'];

		$this->db->where('id_supplier',$id_sup)->where('id_item',$id_it);
		$upd_hrg = $this->db->update('harga',array('harga'=>$hrg));
		
	}

	public function query($x=null){
		

		if($x=='submit'){
			$q = $_POST['query'];
			$s = $this->db->query($q);

			if($s){
				echo "<a href='".base_url('master/query')."'>SUCCESS</a>";
			}
		}else{
			echo "<form action='query/submit' method='POST'>
				<textarea name='query' rows=20 style='width:100%'></textarea><br><br><button type='submit'>KIRIM</button>
				</form>";
		}

	}

	public function to_query($x=null){
		if($x=='submit'){
			// print_r($_FILES['file']);
			$fh = fopen($_FILES['file']['tmp_name'],'r');
			// print_r($fh);
			$query_all = '';
			while ($line = fgets($fh)) {
				$query_all.=$line."\n";
			}
			fclose($fh);

			

			echo "Total Query = <span id='total'>1</span> Query(s)<br>";
			echo "Execute = <span id='count'>0</span> Query Executed.<br><br><br>";
			$q = $query_all;

			$keywords = explode("\n", $q);
			
			$str = '';
			$query_count = 1;
			$total_query = 1;

			$execute = 0;

			if($execute==0){
				ob_start();
				foreach ($keywords as $key => $value) {

						$cr = explode("#",$value,-1);
						if(count($cr)==0){
							$s = explode(";",$value,-1);

							if(count($s)==0){
								$str.=$value;
							}else{
								$str.=$value;
								echo "<script>document.getElementById('total').innerHTML='".$total_query."'</script>";
								echo str_repeat(' ', 5000);
								flush();
								ob_flush();
								usleep(1000);

								$str = '';
								$total_query++;
							}
						}


				}
				$execute = 1;
				ob_end_flush();
			}

			if($execute==1){

				ob_start();
				foreach ($keywords as $key => $value) {
					$cr = explode("#",$value,-1);
					// echo count($cr);
					if(count($cr)==0){
						$s = explode(";",$value,-1);

						if(count($s)==0){
							$str.=$value;
						}else{
							$str.=$value;
							$x = $this->db->query($str);
							if($x){
								echo "<a href='".base_url('master/to_query')."'>Query ke ".($query_count)." : SUCCESS</a><br>";
								echo $str;
								echo "<br><br>";
								echo "<script>document.getElementById('count').innerHTML='".$query_count."'</script>";
								echo str_repeat(' ', 5000);
	    						flush();
								ob_flush();
	    						usleep(1000);
							}else{
								echo "<a href='".base_url('master/to_query')."'>Query ke ".($query_count)." : ERROR</a><br>";
							}
							$str = '';
							$query_count++;
						}
					}
				}
				ob_end_flush();
			}


		}else{
			echo "<form action='to_query/submit' method='POST' enctype='multipart/form-data'>
				<input type='file' name='file'>
				<br><br><button type='submit'>KIRIM</button>
				</form>";
		}


	}

	public function master_group(){
		$this->load->model('model_master_group');

		$var['title']='MASTER DATA GROUP';
		$var['master']='Group';
		$var['s_active']='master_group';
		$var['mode']='view';
		$var['act_button']='master_group';
		$var['page_title']='MASTER DATA GROUP';

		$var['tb_data_group'] = $this->model_master_group->master_group_getData();

		$var['user'] = $_SESSION['user_type'];
		
		$var['js'] = 'js-master';
		$var['plugin'] = 'plugin_1';
		$var['content']='view-master_group';
		
		$this->load->view('view-index',$var);
	}

	public function submit_ch_group(){
		$v_id = $this->input->post('id');
		$v_nama_baru = $this->input->post('nama_baru');

		$nama_awal = $this->db->limit(1)->order_by('id','desc')->get_where('log_group',array('id_group'=>$v_id));
		$nama_pertama = $this->db->get_where('group',array('id'=>$v_id));

		$v_nama_awal = '';

		if($nama_awal->num_rows()>0){
			$v_nama_awal = $nama_awal->row()->nama_baru;
		}else{
			if($nama_pertama->num_rows()>0){
				$v_nama_awal = $nama_pertama->row()->group_name;
			}
		}

		$update = $this->db->where('id_group',$v_id)->update('log_group',array('is_change'=>1));
		$insert = $this->db->insert('log_group',array(
			'id_group'=>$v_id,
			'nama_awal'=> $v_nama_awal,
			'nama_baru'=> $v_nama_baru,
			'insert_by'=>$_SESSION['id_user']
		));
	}

	public function submit_add_group(){
		$v_nama_baru = $this->input->post('group_baru');

		$insert = $this->db->insert('group',array('group_name'=>$v_nama_baru));
		if($insert){
			echo "done";
		}else{
			echo "fail";
		}
	}

	public function get_his_group(){
		$id = $this->input->post('id');

		$query = $this->db->order_by('id','desc')->get_where('log_group',array('id_group'=>$id));

		if($query->num_rows()>0){
			$var['ls_group'] = $query->result();
			$this->load->view('view-his_group',$var);
		}else{
			echo "Belum ada perubahan nama Group";
		}
	}
	
	public function select($x=null){
		if($x=='submit'){
			$q = $_POST['query'];
			$qq = $this->db->query($q)->result();

			if(!empty($qq)){
				echo "<a href='".base_url('master/select')."'>QUERY LAGI</a><br>";
				echo "<table border='1' cellspacing='0' style='font-size:13px;'>";
				echo "<tr style='background-color:grey; color:white;'>";

				foreach ($qq[0] as $key => $value) {
					echo "<th>".$key."</th>";
				}
				echo "</tr>";
				
				foreach ($qq as $key1 => $value1) {
					echo "<tr>";
					foreach ($qq[$key1] as $key2 => $value2) {
						echo "		<td nowrap style='padding-left:5px; padding-right:5px;'>".$value1->$key2."</td>";
					}
					echo "</tr>";
				}
				
				echo "</table>";
				
			}else{
				echo "TIDAK ADA DATA";
			}

		}else{
			echo "<form action='select/submit' method='POST'>
				<textarea name='query' rows=20 style='width:100%'></textarea><br><br><button type='submit'>KIRIM</button>
				</form>";
		}
	}

}
