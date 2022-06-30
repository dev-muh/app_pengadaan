<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KirimFcm {

	protected $CI;

    public function __construct()
    {    	
        $this->CI   =&  get_instance();       
    }

    public function kirim($params){	

	    define( 'API_ACCESS_KEY', 'AAAAMPN6f8E:APA91bFMWNnd03CoL5Ga6CYFSG3_SEtqt5Pg-J9tdvD5a1cg7p-m6OlkSU4AmgW3UG9TXlT0sFt0iyVDmywf74tRbxNQYYl4wol64AlueUy8byyNtr6JpOFZbfHkeoPuKoaF8x_m1j7i');
	    $registrationIds = $params['reg_id'];
	
	    $msg = array
	          (
				'body' 	=> $params['message'],
				'title'	=> $params['title'],
	         	'icon'	=> 'myicon',/*Default Icon*/
	          	'sound' => 'mySound'/*Default sound*/
	          );
		$fields = array
				(
					'to'		=> $registrationIds,
					'notification'	=> $msg
				);
		
		
		$headers = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		
		$result = json_decode($result, true);
	    $GCMIDChanged = $result['canonical_ids'];
	    if($GCMIDChanged)
	    {
	      $NewGCMIdList = end($result['results']);
	      $NewGCMId =  $NewGCMIdList['registration_id'];
	    }

	    //Close request
	    if ($result === FALSE) {
	    die('FCM Send Error: ' . curl_error($ch));
	    }

	    curl_close($ch);
			

		return $result;

	}
}