<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemesanan extends CI_Controller {
	function __construct() { 
		parent::__construct(); 
		//$this->load->library('mail');
		//$this->load->library('email'); //tambahkan dalam contruct pemanggil libarary mail
	}
	public function index(){

	}

	public function sh_SPB(){
		$id_permintaan = $this->input->post('id_permintaan');

		$this->load->model('model_pemesanan');
		$var['ls_spb'] = $this->model_pemesanan->getSPB($id_permintaan);


		$this->load->view('view-ls_spb',$var);

	}
}

?>