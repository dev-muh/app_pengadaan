<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct() { 
		parent::__construct(); 
		//$this->load->library('mail');
		//$this->load->library('email'); //tambahkan dalam contruct pemanggil libarary mail
	}
	public function index(){

	}

	public function ch_pass(){
		//$var['user'] = $_SESSION['user_type'];
		$id = $_SESSION['id_user'];

		$o = $this->input->post('old');
		$n = $this->input->post('new');

		if(!empty($o)&&!empty($n)){
			$this->db->where('id',$id);
			$this->db->select('password');
			$v_old = $this->db->get('user');


			try{
				$old = $v_old->row()->password;
				
					if(sha1(md5($o))==$old){
						
						$this->db->where('id', $id);
						$u = $this->db->update('user',array('password'=>sha1(md5($n))));
						if($u){
							$res = array(
										"status"=>1,
										"message"=>"Kata Sandi anda telah berubah.",
										"icon"=>"fa fa-info-circle",
										"color"=>"green"
									);
							echo json_encode($res);
						}else{
							$res = array(
										"status"=>-2,
										"message"=>"Error Mengubah Kata Sandi.",
										"icon"=>"fa fa-exclamation-circle",
										"color"=>"red"
									);
							echo json_encode($res);
						}
						
					}else{
						$res = array(
									"status"=>-1,
									"message"=>"Kata Sandi lama salah.",
									"icon"=>"fa fa-exclamation-circle",
									"color"=>"red"
								);
						echo json_encode($res);
					}
			}catch(Exception $e){

			}
		}else{
			$res = array(
						"status"=>-3,
						"message"=>"Kata Sandi lama & Kata Sandi baru harus diisi.",
						"icon"=>"fa fa-exclamation-circle",
						"color"=>"red"
					);
			echo json_encode($res);
		}
		
	}	

	public function register($dt=null){
		$var['js']='js-register';
		$this->load->model('model_customer');
		$var['ls_group'] = $this->model_customer->group();
		// print_r($group);
		$this->load->view('view-register',$var);
	}

	public function forget($dt=null){
		$var['js']='js-forgot';
		$this->load->view('view-forgot',$var);
	}

	public function submit_register($ck=null){
		$email_split = explode('@',$_POST['email']);
		$fil_email = end($email_split);

		$this->load->model('model_mail');
		$mail = $this->model_mail->mail_filter($fil_email);

		if($mail){
			$this->register_advance();
		}else{
			echo '{"status":"-5","message":"Hanya boleh menggunakan email dengan domain @tugu.com"}';
		}
	}

	public function register_advance(){
		$this->load->model('model_user');
		$user_id = time();
		$username = $this->input->post('username');
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$group = $this->input->post('group');
		$lantai = $this->input->post('lantai');
		$password = $this->randomPassword();
		$registrasi_sukses = '<p style=\"text-align:center;\">Terimakasih.<br>Pendaftaran Akun Karyawan berhasil.<br>Kata Sandi telah dikirim ke email anda.<br>Silahkan cek email anda.</p>';

		if(!is_numeric($group)){
			echo '{"status":"-5","message":"Group yang anda masukkan salah."}';
			return false;
		}

		if(!is_numeric($lantai) || $lantai==0){
			echo '{"status":"-6","message":"Lantai yang anda masukkan salah."}';
			return false;
		}

		$data = array(
			'user_id' => $user_id,
			'username' => $username,
			'name' => $name,
			'group' => $group,
			'lantai' => $lantai,
			'email' => $email,
			'user_type' => 'Karyawan',
			'is_active' => 1,
			'active_date' => date('Y-m-d H:i:s'),
			'password' => sha1(md5($password))
		);

		$q_username = $this->db->query("select id from user where username='".$username."'");
		$q_email = $this->db->query("select id from user where email='".$email."'");

		

		if(!empty($ck)){
			if($q_username->num_rows()>0){
				echo '{"status":"1","message":"'.$registrasi_sukses.'"}';
			}else{
				if($q_email->num_rows()>0){
					echo '{"status":"1","message":"'.$registrasi_sukses.'"}';
				}else{
					echo '{"status":"1","message":"'.$registrasi_sukses.'"}';
				}
			}
		}else{
			if($q_username->num_rows()>0){
				echo '{"status":"-1","message":"Username sudah terdaftar."}';
			}else{
				if($q_email->num_rows()>0){
					echo '{"status":"-2","message":"Email sudah terdaftar."}';
				}else{
					//echo $this->randomPassword();
					$email_stat = $this->model_user->sendMail($email,$name,$user_id,$group,$lantai,$username,$password);

					if($email_stat == '1'){
						$insert = $this->db->insert('user',$data);
						$l_id = $this->db->insert_id();
						if($insert){
							$this->db->where('id',$l_id);
							$this->db->update('user',array(
															'active_by'=>$l_id,
															'insert_by'=>$l_id,
															'update_by'=>$l_id
														));
							echo '{"status":"1","message":"'.$registrasi_sukses.'"}';
						}else{
							echo '{"status":"-3","message":"Registrasi Error, Silahkan coba kembali"}';
						}
					}else{
						echo '{"status":"-4","message":"Registrasi Error, Silahkan coba kembali"}';
					}
				}
			}
		}
	}

	public function randomPassword() {
		$alphabet = '1234567890';
	    // $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	public function submit_forget(){
		$this->load->model('model_user');
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$q_akun = $this->db->query("select * from user where email='".$email."' and username='".$username."'");

		

		if($q_akun->num_rows()>0){
			$id = $q_akun->row()->id;
			//$user_id = $q_akun->row()->user_id;
			$name = $q_akun->row()->name;
			$group = $q_akun->row()->group;
			$lantai = $q_akun->row()->lantai;
			$username = $q_akun->row()->username;
			$password = $this->randomPassword();
			$password_en = sha1(md5($password));
		 	$email_stat = $this->model_user->mailForgetPassword($email,$password,$name,$username);
		 	
		 	if($email_stat=='1'){
			 	$this->db->where('id',$id);
				$update = $this->db->update('user',array(
												'password' => $password_en,
												'update_by'=>$id
											));
				if($update){
					echo '{"status":"1","message":"Forgot Password Sukses"}';
				}else{
					echo '{"status":"-1","message":"Database Error"}';
				}
			}else{
				echo '{"status":"-2","message":"Error Forgot Password, Silahkan coba kembali."}';
			}
			
		}else{
			echo '{"status":"-3","message":"Tidak dapat menemukan User dengan Email tersebut"}';
		}
	}

	public function reset_password(){
		$id = $this->input->post('id');

		$this->db->where('id',$id);
		$update = $this->db->update('user',array(
										'password' => sha1(md5(12345678)),
										'update_by'=>$id
									));
		if($update){
			echo '{"status":1,"message":"Reset Password Sukses"}';
		}else{
			echo '{"status":-1,"message":"Reset Password Error"}';
		}
	}

}

?>