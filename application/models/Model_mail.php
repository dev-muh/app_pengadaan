<?php 
class Model_mail extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	public function mail_filter($mail=null){
		if($mail=='gmail.com'||$mail=='tugu.com'){
			return true;
		}else{
			return false;
		}
	}
}
?>