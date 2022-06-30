<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends CI_Controller {
	public function __construct() { 
		parent::__construct(); 
		//$this->load->library('mail');
		//$this->load->library('email'); //tambahkan dalam contruct pemanggil libarary mail
	}
	public function index(){
		$this->load->view('view-privacy_policy');
	}
}
?>