<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
	}


	public function index()
	{
		if(!empty($this->check())){
			redirect($this->check());
		}else{
			$var['logo_url_path']='assets/img/logo/logo.png';
			$var['display']=!empty($_GET['log']) ? 'block':'none';
			$log=!empty($_GET['log']) ? $_GET['log']:'';
			$var['message']='';

			if($log=='nouser'){
				$var['message']='<b>User tidak ditemukan.</b>';
			}

			if($log=='salahpass'){
				$var['message']='<b>Kata sandi salah.</b>';
			}
			if($log=='notvalid'){
				$var['message']='<b>Harap isi ID Pengguna dan Password.</b>';
			}

			// Create New Password
			// echo sha1(md5('admin'));
			// exit;

			$this->load->view('view_login',$var);
			// echo $_GET['log'];
		}
	}

	public function check(){
		if(count($_SESSION)>1){
			$user_type = $_SESSION['user_type'];
			if($user_type=='Super Admin'||$user_type=='Admin TOFAP'||$user_type=='Admin'){
				return 'transaksi/order_atk/view';
			}
			if($user_type=='Approval'){
				return 'transaksi/trx/view';
			}
			if($user_type=='Karyawan'||$user_type=='Kurir'||$user_type=='Admin ATK'||$user_type=='Admin Gudang'){
				return 'transaksi/order_atk/view';
			}
			if($user_type=='Admin Penerimaan'||$user_type=='Admin Pengadaan'){
				return 'transaksi/penerimaan_brg/view';
			}
			if($user_type=='Admin Pemesanan'||$user_type=='Admin Pengadaan'){
				return 'transaksi/pemesanan_brg/view';
			}
		}else{
			return null;
		}
	}

	public function validate(){
		if(!empty($this->check())){
			redirect($this->check());
		}else{
			$this->form_validation->set_rules('username','','required');
			$this->form_validation->set_rules('password','','required');

			if($this->form_validation->run()==TRUE){
				$q = $this->db->query("select * from user where 
										(username='".$this->input->post('username')."' || email='".$this->input->post('username')."') 
									and is_delete='0' and is_active='1'");

				if ($q->num_rows() > 0){

					$id_user = $q->row()->id;
					$username = $q->row()->username;
					$email = $q->row()->email;
					$password = $q->row()->password;
					$user_type = $q->row()->user_type;		
					$name = $q->row()->name;
					$no_pegawai = $q->row()->no_pegawai;
					$department = $q->row()->department;
					$jabatan = $q->row()->jabatan;
					

					if(($this->input->post('username')==$username || $this->input->post('username')==$email) && sha1(md5($this->input->post('password')))==$password){
						$data = array(	
						'id_user' => $id_user,		      
						'username' => $username,				
						'logged_in' => TRUE,
						'user_type' => $user_type,
						'name' => $name,
						'no_pegawai'=>$no_pegawai,
						'department'=>$department,
						'jabatan'=>$jabatan
						 );

						$this->session->set_userdata($data);

						if($user_type=='Super Admin'||$user_type=='Admin TOFAP'||$user_type=='Admin'){
							redirect('transaksi/order_atk/view');
						}
						if($user_type=='Approval'){
							redirect('transaksi/trx/view');
						}
						if($user_type=='Karyawan'||$user_type=='Kurir'||$user_type=='Admin ATK'||$user_type=='Admin Gudang'){
							redirect('transaksi/order_atk/view');
						}
						if($user_type=='Admin Penerimaan'||$user_type=='Admin Pengadaan'){
							redirect('transaksi/penerimaan_brg/view');
						}
						if($user_type=='Admin Pemesanan'||$user_type=='Admin Pengadaan'){
							redirect('transaksi/pemesanan_brg/view');
						}
						
					}else{
						// redirect($this->agent->referrer().'?log=salahpass');	
						redirect('login?log=salahpass');			
						// redirect('www.google.com');
					}

				}else{
				    redirect('login?log=nouser');
				}			

			}else{
				redirect('login?log=notvalid');
			}
		}
	}

	public function logout(){
		$this->session->sess_destroy();		
		redirect('login');
	}

	public function ck_user(){
		if(!empty($this->check())){
			return 'login';
		}else{
			return null;
		}
	}

	public function ck_session(){
		print_r($_SESSION);
	}
}