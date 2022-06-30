<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Tambahan {
		function kekata($x){
		    $x=abs($x);
		    $angka=array("","satu","dua","tiga","empat","lima",
		    "enam","tujuh","delapan","sembilan","sepuluh","sebelas");
		    $temp="";
		    if($x<12){
		        $temp=" ".$angka[$x];
		    }elseif($x<20){
		        $temp=$this->kekata($x-10)." belas";
		    }elseif($x<100){
		        $temp=$this->kekata($x/10)." puluh".$this->kekata($x%10);
		    }elseif($x<200){
		        $temp=" seratus".$this->kekata($x-100);
		    }elseif($x<1000){
		        $temp=$this->kekata($x/100)." ratus".$this->kekata($x%100);
		    }elseif($x<2000){
		        $temp=" seribu".$this->kekata($x-1000);
		    }elseif($x<1000000){
		        $temp=$this->kekata($x/1000)." ribu".$this->kekata($x%1000);
		    }elseif($x<1000000000){
		        $temp=$this->kekata($x/1000000)." juta".$this->kekata($x%1000000);
		    }elseif($x<1000000000000){
		        $temp=$this->kekata($x/1000000000)." milyar".$this->kekata(fmod($x,1000000000));
		    }elseif($x<1000000000000000){
		        $temp=$this->kekata($x/1000000000000)." trilyun".$this->kekata(fmod($x,1000000000000));
		    }    
		        return $temp;
		}

		function terbilang($x,$style=4){
		    if($x<0){
		        $hasil="minus ".trim(kekata($x));
		    }else{
		        $hasil=trim($this->kekata($x));
		    }    
		    switch($style){
		        case 1:
		            $hasil=strtoupper($hasil);
		            break;
		        case 2:
		            $hasil=strtolower($hasil);
		            break;
		        case 3:
		            $hasil=ucwords($hasil);
		            break;
		        default:
		            $hasil=ucfirst($hasil);
		            break;
		    }    
		    return $hasil;
		}

		
}