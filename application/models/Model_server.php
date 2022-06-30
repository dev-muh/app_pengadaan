<?php 
class Model_server extends CI_Model {
	// date_default_timezone_set('Asia/Jakarta');
	function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}

	function tformat($x=null){
        if($x==null){
            return '';
        }else{
            $date = new DateTime($x);
            return date_format($date,"d F Y, H:i:s \W\I\B"); 
        }
    } 

    public function badge(){
        $query = $this->db->query('SELECT * FROM (
                    (SELECT COUNT(*) AS keranjang FROM `pemesanan` WHERE STATUS=0 AND is_delete=0 AND `group` != 0 AND YEAR(tgl_pemesanan) = ?) AS `keranjang`,
                    (SELECT COUNT(*) AS `permintaan` FROM `pengajuan` WHERE STATUS=0 AND is_delete=0) AS `permintaan`,
                    (SELECT COUNT(*) AS `pemesanan` FROM `pengajuan` WHERE stat_spb=0 AND is_delete=0 AND no_pengajuan!="") AS `pemesanan`,
                    (SELECT COUNT(*) AS `penerimaan` FROM `spb` WHERE STATUS=0 AND is_delete=0) AS `penerimaan`
                )', date('Y'));

        if($query){
            if($query->num_rows()>0){
                return $query->result();
            }else{
                return null;
            }
        }
    }
}
?>