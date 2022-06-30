<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

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
		//HARUSNYA DIISI HALAMAN DASHBOARD
		$data['title'] = 'Dashboard';
		$data['s_active']='dashboard';
		$data['js'] = 'js-dashboard';
		$data['user'] = $_SESSION['user_type'];
		$this->load->view('view-index',$data);

	}
	public function pos(){
		$data['s_active']='pos';
		$data['js'] = 'js-pos';
		$data['content']='view-pos';
		$this->load->view('view-index',$data);
	}
	public function logout(){
		session_destroy();
		redirect('login');
	}
}
