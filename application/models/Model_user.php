<?php 
class Model_user extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	public function index(){
		$id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
		$query = $this->db->query('select * from user where id='.$id);

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}
	public function tb_customer(){
		
		$query = $this->db->query('select 	
											us.*,
											(select g.group_name from `group` as g where g.id=us.group) as group_name,
											(select g.id from `group` as g where g.id=us.group) as group_id,	
											u.username as update_by_username 
									from 	
											user as us join user as u 
									where 
											us.update_by=u.id 
											and us.is_delete=0 order by insert_date desc');

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}

	public function ck_user($u=null,$e=null,$t=null){

		if($t=='Karyawan'){
			$query = $this->db->query('select username from user where username="'. $u .'" or email="' . $e . '"');
		}else{
			$query = $this->db->query('select username from user where username="'. $u .'"');
		}
		

		if ($query->num_rows() > 0){
		    return true;
		}else{
		    return NULL;
		}
	}

	public function list_customer(){
		$query = $this->db->query('select * from user where user_type="Karyawan" and is_delete=0 and is_active=1');

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}

	public function list_kurir(){
		$query = $this->db->query('select id,username,name from user where (user_type="Kurir" or user_type="Admin Gudang") and is_delete=0 and is_active=1');

		if ($query->num_rows() > 0){
		    return $query->result();
		}else{
		    return NULL;
		}
	}

	// public function sendMail($email=null,$nama=null,$user_id=null,$group=null,$lantai=null,$username=null,$password=null){

	// 	$this->load->library('mail');
		
		
	// 	$html = '<div >
	// 				<div >
	// 					<center><h3>Bukti Registrasi Akun Karyawan PT. TUGU PRATAMA INDONESIA</h3></center>

	// 					<p style="text-align:justify; text-indent: 40px;">Terimakasih anda telah melakukan registrasi Akun Karyawan <b>PT. TUGU PRATAMA INDONESIA</b>. Berikut ini adalah informasi mengenai Registrasi Akun Anda :</p>

	// 				<pre>
	// 				Nama		: '. $nama .'
	// 				Group		: '. $group .'
	// 				Lantai		: '. $lantai .'
	// 				Email		: '. $email .'
	// 				Jabatan		: KARYAWAN

	// 				Username	: <b>'. $username .'</b>
	// 				Password	: <b>'. $password .'</b>
	// 				</pre>

	// 						<p style="text-align:justify; text-indent: 40px;">Mohon untuk tidak memberitahukan password kepada siapapun. Silahkan <a href="http://www.myogir.com/tofap" target="_blank">KLIK DISINI</a> untuk login.<br>
	// 						</p>
	// 					<p style="text-align:justify; text-indent: 40px;">Terimakasih.</p>
	// 				</div>
	// 			</div>';

	// 	return $this->mail->register($email,$nama,'Registrasi Akun Karyawan',$html);
	// }

	public function sendMail($email=null,$nama=null,$user_id=null,$group=null,$lantai=null,$username=null,$password=null){
		
		$this->load->library('mail');

		$var['name'] = $nama;
		$var['username'] = $username;
		$var['password'] = $password;
		$var['group'] = '-';
		$var['lantai'] = $lantai;
		$var['email'] = $email;

		$group_select = $this->db->where('id',$group)->select('group_name')->get('`group`');
		if($group_select->num_rows()>0){
			$var['group']=$group_select->row()->group_name;
		}

		$html = $this->load->view('view-form_registrasi',$var,true);

		return $this->mail->register($email,$nama,'[TOFAP] Halo '.strtoupper($nama),$html);
	}


	public function mailForgetPassword($email=null,$newpassword=null,$nama=null,$username=null){
		$this->load->library('mail');

		$var['name'] = $nama;
		$var['username'] = $username;
		$var['new_pass'] = $newpassword;

		$html = $this->load->view('views',$var,true);

		return $this->mail->forgotPassword($email,$nama,'[TOFAP] '.strtoupper($nama).' Lupa Kata Sandi?',$html);
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

	public function user_info(){
		$q = $this->db->query("select * from `user`");

		if($q->num_rows()>0){
			return $q->result();
		}else{
			return null;
		}
	}
}
?>