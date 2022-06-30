<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class server extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(count($_SESSION)<=1){ 
			redirect('login');
		}
	}
	public function index(){
		echo "SIP";
	}

	public function txt_stat($i=null){
		$status = ['Waiting Approval','Order Received','Courier Assigned','Prepare Item','Courier On The Way','Done','Cancel'];
		return $status[$i];
	}
	function sent(){
		$ses = $_SESSION['user_type'];
		$id  = $_GET['id'];

		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$this->load->model('model_user');
		$a_user = $this->model_user->user_info();
		// $user = json_encode($a_user);

		$this->load->model('model_transaksi');		
		$a_trans = $this->model_transaksi->notif_pemesanan();
		$trans = json_encode($a_trans);

		if($id==1){
			echo "id: 1\n\n";
			echo "event: $ses\n";
			echo "data: {$trans}\n\n";
		}

		if($id==2){
			echo "id: 2\n\n";
			echo "event: $ses\n";
			echo "data: {$trans}\n\n";
		}

		flush();
		// sleep($s);
	}

	public function cur_time(){
		// $get = $_GET['ist'];
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$db_sel = $this->db->query('select now() as waktu');
		$date = $db_sel->row()->waktu;
		$this->load->model('model_server');
		$f_date = $this->model_server->tformat($date);


		$md5_pem = $this->md5_pemesanan();
		$md5_pem_stat = $this->md5_status_pemesanan();

		$badge = json_encode($this->model_server->badge());
		$tahun = date('Y');

		// $md5_stat = $this->md5_update_status();

		// echo "id: 3\n\n";
		echo "event: current_time\n";
		echo "data: {$f_date}\n\n";

		// echo "id: 2\n\n";
		echo "event: tb_pemesanan\n";
		echo "data: {$md5_pem}\n\n";

		echo "event: status_pemesanan\n";
		echo "data: {$md5_pem_stat}\n\n";

		echo "event: badge\n";
		echo "data: {$badge}\n\n";

		echo "event: stat\n";
		echo "data: {$tahun}\n\n";

		// echo "id: 3\n\n";
		// echo "event: update_status\n";
		// echo "data: {$md5_stat}\n\n";

		flush();
	}

	public function s_pem(){

		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');

		$md5_pem = $this->md5_pemesanan();

		echo "data: {$md5_pem}\n\n";

		flush();
	}

	public function md5_pemesanan(){
		$this->load->model('model_transaksi');
		if($_SESSION['user_type']=='Karyawan'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,$_SESSION['id_user'],null,null,date('Y'));
		}
		if($_SESSION['user_type']=='Kurir'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,$_SESSION['id_user'],null,date('Y'));
		}
		if($_SESSION['user_type']=='Super Admin' ||$_SESSION['user_type']=='Admin TOFAP' || $_SESSION['user_type']=='Admin' || $_SESSION['user_type']=='HC - Super Admin'||$_SESSION['user_type']=='Admin Gudang'||$_SESSION['user_type']=='Admin ATK'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,null,null,date('Y'));
		}

		$md = '';
		$arr_tmp = [];
		foreach ($tb_pem as $key => $value) {
			$md.=$value->no_pemesanan;
			array_push($arr_tmp, $value->no_pemesanan);
		}

		$md_sort = '';
		sort($arr_tmp);
		foreach ($arr_tmp as $key => $value) {
			$md_sort.=$value;
		}
		
		
		return md5($md_sort);
	}

	public function md5_status_pemesanan(){
		$this->load->model('model_transaksi');
		if($_SESSION['user_type']=='Karyawan'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,$_SESSION['id_user']);
		}
		if($_SESSION['user_type']=='Kurir'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,$_SESSION['id_user']);
		}
		if($_SESSION['user_type']=='Super Admin' ||$_SESSION['user_type']=='Admin TOFAP' || $_SESSION['user_type']=='Admin' || $_SESSION['user_type']=='HC - Super Admin'||$_SESSION['user_type']=='Admin Gudang'||$_SESSION['user_type']=='Admin ATK'){
			$tb_pem = $this->model_transaksi->tb_pemesanan();
		}

		$md = '';
		$arr_tmp = [];
		foreach ($tb_pem as $key => $value) {
			$md.=$value->status;
			array_push($arr_tmp, $value->status);
		}

		$md_sort = '';
		sort($arr_tmp);
		foreach ($arr_tmp as $key => $value) {
			$md_sort.=$value;
		}
		
		// print_r($md_sort);
		return md5($md_sort);
	}

	public function md5_rating_pemesanan(){
		$this->load->model('model_transaksi');
		if($_SESSION['user_type']=='Karyawan'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,$_SESSION['id_user']);
		}
		if($_SESSION['user_type']=='Kurir'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,$_SESSION['id_user']);
		}
		if($_SESSION['user_type']=='Super Admin' ||$_SESSION['user_type']=='Admin TOFAP' || $_SESSION['user_type']=='Admin' || $_SESSION['user_type']=='HC - Super Admin'||$_SESSION['user_type']=='Admin Gudang'||$_SESSION['user_type']=='Admin ATK'){
			$tb_pem = $this->model_transaksi->tb_pemesanan();
		}

		$md = '';
		$arr_tmp = [];
		foreach ($tb_pem as $key => $value) {
			$md.=$value->komentar;
			array_push($arr_tmp, $value->komentar);
		}

		$md_sort = '';
		sort($arr_tmp);
		foreach ($arr_tmp as $key => $value) {
			$md_sort.=$value;
		}
		
		// print_r($md_sort);
		return md5($md_sort);
	}


	//GET DATA FROM CLIENT
	public function get_data_tb_pemesanan(){
		$this->load->model('model_transaksi');

		if($_SESSION['user_type']=='Karyawan'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,$_SESSION['id_user'],null,null,date('Y'));
		}
		if($_SESSION['user_type']=='Kurir'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,$_SESSION['id_user'],null,date('Y'));
		}
		if($_SESSION['user_type']=='Super Admin' ||$_SESSION['user_type']=='Admin TOFAP' || $_SESSION['user_type']=='Admin' || $_SESSION['user_type']=='HC - Super Admin'||$_SESSION['user_type']=='Admin Gudang'||$_SESSION['user_type']=='Admin ATK'){
			$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,null,null,date('Y'));
		}

		$tb_sent = [];

		if(empty($_POST['data'])){
			foreach ($tb_pem as $key => $value) {
				$tb_pem[$key]->txt_status=$this->txt_stat($value->status);
				array_push($tb_sent,$value);
			}
		}else{
			foreach ($tb_pem as $key => $value) {
				$tb_pem[$key]->txt_status=$this->txt_stat($value->status);
				$tb_pem[$key]->length=count($tb_pem);
				if(!in_array($value->no_pemesanan, $_POST['data'])){
					array_push($tb_sent,$value);
				}
			}
		}

		echo json_encode($tb_sent);
	}

	public function get_data_status_pemesanan(){
		$this->load->model('model_transaksi');

		$ids = !empty($_POST['data']) ? $_POST['data']:null;

		if(!empty($ids)){
			$arr_id = [];
			foreach ($ids as $key => $value) {
				array_push($arr_id, $value['id']);
			}

			if($_SESSION['user_type']=='Karyawan'){
				$tb_pem = $this->model_transaksi->tb_pemesanan(null,$_SESSION['id_user'],$arr_id);
			}
			if($_SESSION['user_type']=='Kurir'){
				$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,$_SESSION['id_user'],$arr_id);
			}
			if($_SESSION['user_type']=='Super Admin' ||$_SESSION['user_type']=='Admin TOFAP' || $_SESSION['user_type']=='Admin' || $_SESSION['user_type']=='HC - Super Admin'||$_SESSION['user_type']=='Admin Gudang'||$_SESSION['user_type']=='Admin ATK'){
				$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,null,$arr_id);
			}

			$tb_sent = [];

			if(empty($_POST['data'])){
				foreach ($tb_pem as $key => $value) {
					$tb_pem[$key]->txt_status=$this->txt_stat($value->status);
					array_push($tb_sent,$value);
				}
			}else{
				foreach ($tb_pem as $key => $value) {
					$tb_pem[$key]->txt_status=$this->txt_stat($value->status);
					$tb_pem[$key]->length=count($tb_pem);
					if(!in_array($value->no_pemesanan, $_POST['data'])){
						array_push($tb_sent,$value);
					}
				}
			}

			echo json_encode($tb_sent);
		}
	}

	public function get_data_rating_pemesanan(){
		$this->load->model('model_transaksi');

		$ids = !empty($_POST['data']) ? $_POST['data']:null;

		if(!empty($ids)){
			$arr_id = [];
			foreach ($ids as $key => $value) {
				array_push($arr_id, $value['id']);
			}

			if($_SESSION['user_type']=='Karyawan'){
				$tb_pem = $this->model_transaksi->tb_pemesanan(null,$_SESSION['id_user'],$arr_id);
			}
			if($_SESSION['user_type']=='Kurir'){
				$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,$_SESSION['id_user'],$arr_id);
			}
			if($_SESSION['user_type']=='Super Admin' ||$_SESSION['user_type']=='Admin TOFAP' || $_SESSION['user_type']=='Admin' || $_SESSION['user_type']=='HC - Super Admin'||$_SESSION['user_type']=='Admin Gudang'||$_SESSION['user_type']=='Admin ATK'){
				$tb_pem = $this->model_transaksi->tb_pemesanan(null,null,null,$arr_id);
			}

			$tb_sent = [];

			if(empty($_POST['data'])){
				foreach ($tb_pem as $key => $value) {
					// $tb_pem[$key]->txt_status=$this->txt_stat($value->komentar);
					array_push($tb_sent,$value);
				}
			}else{
				foreach ($tb_pem as $key => $value) {
					// $tb_pem[$key]->txt_status=$this->txt_stat($value->status);
					$tb_pem[$key]->length=count($tb_pem);
					if(!in_array($value->no_pemesanan, $_POST['data'])){
						array_push($tb_sent,$value);
					}
				}
			}

			echo json_encode($tb_sent);
		}
	}
}