<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class tandatangan extends CI_Controller {

	public function index(){
		$var['title']='TANDA TANGAN';
		$var['user'] = $_SESSION['user_type'];
		$var['s_active']='tandatangan';
		$var['js'] = 'js-customer';
		$var['mode']='view';
		$var['page_title']='SETTING TANDATANGAN';
		$var['plugin'] = 'plugin_1';
		$var['content']='view-tandatangan';
		$this->load->model('model_tandatangan');
		$var['dt_ttd'] = $this->model_tandatangan->getDataTTD();

		$this->load->view('view-index',$var);
	}

	public function submit(){
		// $target_dir = "assets/img/ttd/";
		// $target_file = $target_dir . basename($_FILES["ttd"]["name"]);
		// $uploadOk = 1;
		// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// $filename = date('Y-m-d').'_'.md5(time()).'.'.$imageFileType;

		// // Check if image file is a actual image or fake image
		// if(isset($_POST["submit"])) {
		//     $check = getimagesize($_FILES["ttd"]["tmp_name"]);
		//     if($check !== false) {
		//         echo "File is an image - " . $check["mime"] . ".";
		//         $uploadOk = 1;
		//         $terupload = move_uploaded_file($_FILES["ttd"]["tmp_name"], $target_dir.$filename);

		        

		//         if($insert){
		//         	redirect(base_url().'tandatangan');
		//         }else{
		//         	echo "ERROR UPDATE DATA";
		//         }
		//     } else {
		//         echo "File is not an image.";
		//         $uploadOk = 0;
		//     }
		// }

		$insert = $this->db->insert('tandatangan',array(
        	// 'periode'=>$this->input->post('periode'),
        	'nama'=>$this->input->post('nama'),
        	'jabatan'=>$this->input->post('jabatan'),
        	// 'file_tandatangan'=>$filename
        ));

        if($insert){
        	redirect(base_url('tandatangan'));
        }
	}

}

?>
