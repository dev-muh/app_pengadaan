<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
	public function __construct() { 
		parent::__construct(); 
		//$this->load->library('mail');
		//$this->load->library('email'); //tambahkan dalam contruct pemanggil libarary mail
	}
	public function index(){

	}

	public function upload_attach(){
		$data = array();

		if(isset($_GET['files']))
		{  
			$id = $_GET['id'];
			$filename = $_GET['number'];
		    $error = false;
		    $files = '';

		    if (!file_exists('./assets/customer_attach/')) {
    			mkdir('./assets/customer_attach/', 0777, true);
			}

		    $uploaddir = './assets/customer_attach/';
		    foreach($_FILES as $file)
		    {
		    	$ext = explode('.',basename($file['name']));
		        if(move_uploaded_file($file['tmp_name'], $uploaddir .$file['name']))
			        {
			        	
			            $files = $file['name'];
			        }
		        else
			        {
			            $error = true;
			        }
		    }
		    
		    if(!$error){
		    	$upd_db = $this->db->where('id',$id)->set('attach_file',$files)->update('spb');

		    	if($upd_db){
		    		$data = ($error) ? array('status'=>0,'message' => 'There was an error uploading your files') : array('status'=>1,'message' => $files);
		    	}else{
		    		$data = array('status'=>0,'message' => 'There was an error uploading your files');
		    	}
		    }else{
		    	$data = array('status'=>0,'message' => 'There was an error uploading your files');
		    }
		}
		else
		{
		    $data = array('success' => 'Form was submitted', 'formData' => $_POST);
		}

		echo json_encode($data);
	}
}

?>