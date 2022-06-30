<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(count($_SESSION)<=1){ 
			redirect('login');
		}

	}
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// $this->load->view('view-index');

	}
	public function kategori($mode=null){
			$var['user'] = $_SESSION['user_type'];
			$var['title']='MASTER DATA';

			if($mode=='add'){
				$code = $this->input->post('code');
				$nama = $this->input->post('nama');
				$this->load->model('model_produk');
				$add = $this->model_produk->add($code,$nama);
				$result_add = $this->model_produk->result_add();
				if($add){
					echo json_encode($result_add);
				}
			}
			if($mode=='edit'){
				$id = $this->input->post('id');
				$code = $this->input->post('code');
				$nama = $this->input->post('nama');
				$this->load->model('model_produk');
				$edit = $this->model_produk->edit($id,$code,$nama);
				if($edit){
					echo json_encode($edit);
				}
			}
			if($mode=='del'){
				$id = $this->input->post('id');
				$this->load->model('model_produk');
				$del = $this->model_produk->del_kategori($id);
				if($del){
					echo json_encode($del);
				}
			}
			if($mode=='view'){
				$var['s_active']='kategori';
				$var['js'] = 'js-kategori';
				$var['mode']='view';
				$var['page_title']='KATEGORI';
				$var['plugin'] = 'plugin_1';
				$var['content']='view-pos_kategori';
				$this->load->model('model_produk');
				$var['tb_kategori'] = $this->model_produk->tb_kategori();
				$this->load->view('view-index',$var);

			}	
	}
	public function sub_kategori($mode=null){
		$var['user'] = $_SESSION['user_type'];
		$var['title']='MASTER DATA';

		if($mode=='view'){
			$var['s_active']='sub_kategori';
			$var['js'] = 'js-sub_kategori';
			$var['mode']='view';
			$var['page_title']='SUB KATEGORI';
			$var['plugin'] = 'plugin_1';
			$var['content']='view-pos_sub_kategori';
			$this->load->model('model_produk');
			$var['tb_kategori'] = $this->model_produk->tb_kategori();
			$var['tb_sub_kategori'] = $this->model_produk->tb_sub_kategori();
			$this->load->view('view-index',$var);
		}

		if($mode=='add'){
			$id_kat = $this->input->post('id_kat');
			$code = $this->input->post('code');
			$nama = $this->input->post('nama');
			$this->load->model('model_produk');
			$add_sub = $this->model_produk->add_sub($id_kat,$code,$nama);
			$result_add_sub = $this->model_produk->result_add_sub();
			if($add_sub){
				echo json_encode($result_add_sub);
			}
		}

		if($mode=='edit'){
			$id = $this->input->post('id_sub_kat');
			$id_kat = $this->input->post('id_kat');
			$code = $this->input->post('code');
			$nama = $this->input->post('nama');
			$this->load->model('model_produk');
			$edit_sub = $this->model_produk->edit_sub($id,$id_kat,$code,$nama);
			if($edit_sub){
				echo json_encode($edit_sub);
			}
		}

		if($mode=='del'){
			$id = $this->input->post('id');
			$this->load->model('model_produk');
			$del_sub = $this->model_produk->del_sub_kategori($id);
			if($del_sub){
				echo json_encode($del_sub);
			}
		}
	}
	public function generate_barcode(){
		$rnd = $this->db->query('select floor(10000+(RAND()*69999)) as rnd');
		$r_rnd = $rnd->row()->rnd;

		$this->db->insert('barcode',array('id'=>$r_rnd));
		$err = $this->db->error();
		if($err['code']==1062){
			$this->db->insert('barcode',array('id'=>''));
		}


		$fnl = $this->db->insert_id();
		$bc_it = $this->db->query('select barcode from pos_item where barcode='.$r_rnd);

		if($bc_it->num_rows()>0){
			$this->generate_barcode();
		}else{
			echo $fnl;
		}
	}
	public function item($mode=null){
		$var['user'] = $_SESSION['user_type'];
		$var['title']='MASTER DATA';

		if($mode=='view'){
			$var['s_active']='item';
			$var['js'] = 'js-item';
			$var['mode']='view';
			$var['page_title']='ITEM';
			$var['plugin'] = 'plugin_1';
			$var['content']='view-pos_item';
			$this->load->model('model_produk');
			$var['tb_kategori'] = $this->model_produk->tb_kategori();
			$var['tb_sub_kategori'] = $this->model_produk->tb_sub_kategori();
			$var['tb_item'] = $this->model_produk->tb_item_in_master();

			$this->load->model('model_produk');
			$var['tb_kategori'] = $this->model_produk->tb_kategori();
			$var['tb_sub_kategori'] = $this->model_produk->tb_sub_kategori();

			//print_r($var['tb_sub_kategori']);
			$var['kats']=array();
			$var['subs']=array();
			if(!empty($var['tb_kategori'])){
				foreach ($var['tb_kategori'] as $kat=>$val) {
					array_push($var['kats'],$val);
				}

				//print_r($var['tb_kategori']);
				

				
				foreach ($var['tb_kategori'] as $kat=>$val) {
					array_push($var['subs'],[]);
					if(!empty($var['tb_kategori'])){
						foreach ($var['tb_sub_kategori'] as $skat=>$sval) {
							if($sval->id_kat==$val->id){
								array_push($var['subs'][$kat],$sval);
							}
						}
					}
				}
			}
			

			$this->load->view('view-index',$var);

		}

		if($mode=='add'){
			$this->load->model('model_produk');
			$add_item = $this->model_produk->add_item();
			$result_add_item = $this->model_produk->result_add_item();
			if($add_item){
				echo json_encode($result_add_item);
			}
		}

		if($mode=='edit'){
			$this->load->model('model_produk');
			$edit_item = $this->model_produk->edit_item();
			if($edit_item){
				echo json_encode($edit_item);
			}
		}

		if($mode=='del'){
			$id = $this->input->post('id');
			$this->load->model('model_produk');
			$del_item = $this->model_produk->del_item($id);
			if($del_item){
				echo json_encode($del_item);
			}
		}

		if($mode=='activate'){
			$id = $this->input->post('id');
			$this->load->model('model_produk');
			$activate_item = $this->model_produk->activate_item($id);
			if($activate_item){
				echo json_encode($activate_item);
			}
		}
	}

	public function kat_and_sub(){
		$var['user'] = $_SESSION['user_type'];

		$this->load->model('model_produk');
		$var['tb_kategori'] = $this->model_produk->tb_kategori();
		$var['tb_sub_kategori'] = $this->model_produk->tb_sub_kategori();

		$var['kats']=array();
		foreach ($var['tb_kategori'] as $kat=>$val) {
			array_push($var['kats'],$val);
		}
		$var['subs']=array();
		foreach ($var['tb_kategori'] as $kat=>$val) {
			array_push($var['subs'],[]);
			foreach ($var['tb_sub_kategori'] as $skat=>$sval) {
				if($sval->id_kat==$val->id){
					array_push($var['subs'][$kat],$sval);
				}
			}
		}
	}


	public function upload($id){

		if(isset($_FILES['image'])){
			foreach ($_FILES['image']['name'] as $key => $value) {
				$errors= array();

				$file_name = $_FILES['image']['name'][$key];
				$file_size = $_FILES['image']['size'][$key];
				$file_tmp = $_FILES['image']['tmp_name'][$key];
				$file_type = $_FILES['image']['type'][$key];
				$ext=explode('.',$file_name);
				$file_ext = strtolower(end($ext));
				
				$expensions= array("jpeg","jpg","png");

				if(in_array($file_ext,$expensions)=== false){
				 $errors[]="extension not allowed, please choose a JPEG or PNG file.";
				}

				if($file_size > 2097152) {
				 $errors[]='File size must be excately 2 MB';
				}

				if(empty($errors)==true) {
					$name = 'ID'.$id.'_G'.$key.'_'.time().'.jpg';
				 	move_uploaded_file($file_tmp,"./assets/img/".$name);
				 	$this->load->model('model_produk');
				 	$var['saveToDB'] = $this->model_produk->saveImgToDB($id,$name);
				 	echo "Success";
				}else{
				 	print_r($errors);
				}
			}
	   	}
	}

	public function getPhoto(){
		$id=$this->input->post('id');
		$res=$this->db->get_where('img_item',array('id_item'=>$id));
		if ($res->num_rows() > 0){
		    echo json_encode($res->result());
		}else{
		    echo NULL;
		}
	}

	public function cekBarcodeItem($barcode=null){
		$this->load->model('model_produk');
		$var['barcode']=$this->model_produk->ck_barcode($barcode);
		echo $var['barcode'];
	}

	public function printbarcode(){
		$this->load->library('barcode');
		$bc = $this->db->query("select * from pos_item where id=".$this->input->get('id'));
		//echo $this->input->get('id');
		//print_r($bc->result());
		if($bc->num_rows()>0){
			$barcode = $bc->row()->barcode;
			$nama = $bc->row()->item_name;

			$this->barcode->print_barcode($barcode,$nama);
		}else{
			echo "Item tidak ada atau barcode kosong.";
		}

		
		
	}


}