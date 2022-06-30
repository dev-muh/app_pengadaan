<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

class Transaksi extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (count($_SESSION) <= 1) {
      redirect('login');
    }
  }
  public function index()
  {

  }

  public function txt_stat($i = null)
  {
    $status = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];
    return $status[$i];
  }

  public function nomor($no = null, $format = null)
  {
    try {
      $nomor       = '';
      $q           = $this->db->query("SELECT * FROM " . $no . " order by id desc limit 1");
      $array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

      if ($q->num_rows() > 0) {

        $kd    = $q->row()->nomor;
        $th_h  = $q->row()->tahun;
        $th    = date('Y');
        $bln   = date('n');
        $bln_n = $array_bulan[date('n')];

        $kd++;
        $kd = sprintf("%03s", $kd);

        if (date('Y') > $th_h) {
          $nomor = '001' . $format . $bln_n . '/' . $th;
          $this->db->insert($no, array('nomor' => '001', 'bulan' => $bln, 'tahun' => $th));
        } else {
          $nomor = $kd . $format . $bln_n . '/' . $th;
          $this->db->insert($no, array('nomor' => $kd, 'bulan' => $bln, 'tahun' => $th));
        }

      } else {
        $nomor = '001' . $format . $array_bulan[date('n')] . '/' . date('Y');
        $this->db->insert($no, array('nomor' => '001', 'bulan' => date('n'), 'tahun' => date('Y')));
      }
    } catch (Exception $e) {

    } finally {
      return $nomor;
    }
  }

  public function nomor_order($no = 'nomor_order', $format = 'TFPABL')
  {
    try {
      $nomor       = '';
      $q           = $this->db->query("SELECT * FROM " . $no . " order by id desc limit 1");
      $array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

      if ($q->num_rows() > 0) {

        $kd    = $q->row()->nomor;
        $th_h  = $q->row()->tahun;
        $th    = date('Y');
        $bln   = date('n');
        $bln_n = $array_bulan[date('n')];

        $kd++;
        $kd = sprintf("%07s", $kd);

        if (date('Y') > $th_h) {
          // $nomor = '001'.$format.$bln_n.'/'.$th;
          $nomor = $format . substr($th, 2, 2) . '0000001';
          $this->db->insert($no, array('nomor' => '0000001', 'bulan' => $bln, 'tahun' => $th));
        } else {
          // $nomor = $kd.$format.$bln_n.'/'.$th;
          $nomor = $format . substr($th, 2, 2) . $kd;
          $this->db->insert($no, array('nomor' => $kd, 'bulan' => $bln, 'tahun' => $th));
        }

      } else {
        // $nomor = '001'.$format.$array_bulan[date('n')].'/'.date('Y');
        $nomor = $format . substr(date('Y'), 2, 2) . '0000001';
        $this->db->insert($no, array('nomor' => '0000001', 'bulan' => date('n'), 'tahun' => date('Y')));
      }
    } catch (Exception $e) {

    } finally {
      return $nomor;
    }
  }

//#######################   PENGAJUAN ########################
  public function trx($mode = null, $id = null)
  {
    $this->load->model('model_transaksi');
    $session_id     = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $var['user_id'] = $this->model_transaksi->user_type($session_id);
    $var['title']   = 'PERMINTAAN';
    if ($mode == 'view') {
      $var['s_active']     = 'pengajuan';
      $var['mode']         = 'view';
      $var['act_button']   = 'pengajuan';
      $var['page_title']   = 'PERMINTAAN';
      $var['tb_pengajuan'] = $this->model_transaksi->tb_pengajuan('', 'tgl_pengajuan');
    }

    if ($mode == 'view_penerimaan') {
      $var['s_active']     = 'penerimaan';
      $var['mode']         = 'view';
      $var['act_button']   = 'penerimaan';
      $var['page_title']   = 'PENERIMAAN';
      $var['tb_pengajuan'] = $this->model_transaksi->tb_pengajuan();
    }

    if ($mode == 'add') {
      $var['s_active']   = 'pengajuan';
      $var['mode']       = 'add';
      $var['page_title'] = 'TAMBAH PERMINTAAN';
      $this->load->model('model_produk');
      $var['items'] = $this->model_produk->tb_item();
    }

    if ($mode == 'edit') {
      $this->load->model('model_produk');
      $var['s_active']     = 'pengajuan';
      $var['mode']         = 'edit';
      $var['page_title']   = 'EDIT PERMINTAAN';
      $var['id']           = $id;
      $var['tb_pengajuan'] = $this->model_transaksi->tb_pengajuan($id);
      $var['list']         = $this->model_transaksi->list_item_pengajuan_edit($id);
      $var['items']        = $this->model_produk->tb_item();
    }

    $var['stat_user'] = $var['user_id'][0]->user_type;
    $var['user']      = $_SESSION['user_type'];

    $var['js']      = 'js-pengajuan';
    $var['plugin']  = 'plugin_1';
    $var['content'] = 'view-pengajuan';

    $this->load->view('view-index', $var);
  }

  public function pengajuan_view($id = null)
  {
    if (empty($id)) {
      $id = $this->input->post('id');
    }
    $this->load->model('model_transaksi');
    $var['list_item_pengajuan'] = $this->model_transaksi->list_item_pengajuan($id);
    if ($var['list_item_pengajuan']) {
      echo json_encode($var['list_item_pengajuan']);
    }
  }
  public function trx_pengajuan()
  {
    $this->load->model('model_transaksi');
    $var['add_pengajuan'] = $this->model_transaksi->trx_pengajuan();
  }
  public function del_it_pengajuan()
  {
    $id = $this->input->post('id');

    $this->db->where('id', $id);
    $this->db->update('item_pengajuan', array('is_delete' => 1));
  }

  public function del_pengajuan()
  {
    $id = $this->input->post('id');

    $this->db->where('id', $id);
    $this->db->update('pengajuan', array('is_delete' => 1));
  }
  public function accept_pengajuan()
  {
    try {
      $session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
      $id         = $this->input->post('id');

      // $nomor = $this->nomor('nomor_permintaan', '/PB/HRG-PROC/');
      /// $nomor = $this->nomor('nomor_permintaan', '/PB/CCG-SF/ATPI/');
      /// $nomor = $this->nomor('nomor_permintaan', '/PB/HRG-SF/');
      $nomor = $this->nomor('nomor_permintaan', '/PB/HCS-SF/');
    } catch (Exception $e) {

    } finally {
      $this->db->where('id', $id);
      $this->db->update('pengajuan', array('no_pengajuan' => $nomor, 'status' => 1, 'stat_penerimaan' => 0, 'approval' => $session_id, 'approve_date' => date('Y-m-d H:i:s')));
    }

  }
  public function reject_pengajuan()
  {
    $id = $this->input->post('id');

    $this->db->where('id', $id);
    $this->db->update('pengajuan', array('status' => 2));
  }

  public function verifikasi($id = null)
  {
    // $var['item'] = ;
    $this->load->model('model_transaksi');
    $var['items'] = $this->model_transaksi->list_item_pengajuan($id);
    $this->load->view('view-verifikasi_part', $var);
  }
//##########################   PENERIMAAN    ###########################

  public function update_it_pn()
  {
    $id      = $this->input->post('id');
    $jml     = $this->input->post('jml');
    $id_item = $this->input->post('id_item');
    $qty     = $this->input->post('qty');

    $this->db->where('id', $id);
    $this->db->update('item_pengajuan', array('qty_masuk' => $jml));

    $q     = $this->db->select('qty')->where('id', $id_item)->get('pos_item');
    $qtyDB = $q->row()->qty;

    $this->db->where('id', $id_item);
    $this->db->update('pos_item', array('qty' => $qtyDB + $qty));

  }

  public function update_stat_penerimaan()
  {
    $id   = $this->input->post('id');
    $data = array(
      'stat_penerimaan' => 1,
      'receiver'        => $_SESSION['id_user'],
      'receive_date'    => date('Y-m-d H:i:s'),
      'update_by'       => $_SESSION['id_user'],

    );

    $this->db->where('id', $id);
    $this->db->update('pengajuan', $data);
  }

  public function verifikasi_penerimaan()
  {
    $dt = $this->input->post('items');
    //print_r($dt);
    $c_it   = count($dt);
    $c_itDB = count($dt);
    foreach ($dt as $key => $value) {
      $this->db->where('id', $value['id_it_pn']);
      $u_peng = $this->db->update('item_pengajuan', array('qty_masuk' => $value['qty_masuk']));

      if ($value['qty_masuk'] == $value['qty']) {
        $c_it--;
      }

      // $this->db->where('id',$val['id_item']);
      // $this->db->update('pos_item',array('qty'=>$qtyDB+$qty));
    }

    if ($c_it == 0) {
      $data = array(
        'stat_penerimaan' => 1,
        'receiver'        => $_SESSION['id_user'],
        'receive_date'    => date('Y-m-d H:i:s'),
        'update_by'       => $_SESSION['id_user'],

      );

      $this->db->where('id', $dt[0]['id']);
      $u_pengajuan = $this->db->update('pengajuan', $data);

      if ($u_pengajuan) {

        foreach ($dt as $key => $value) {
          $q     = $this->db->select('qty')->where('id', $value['id_item'])->get('pos_item');
          $qtyDB = $q->row()->qty;

          $this->db->where('id', $value['id_item']);
          $u_item = $this->db->update('pos_item', array('qty' => $qtyDB + $value['qty_masuk']));
          if ($u_item) {
            $c_itDB--;
          }
        }

        if ($c_itDB == 0) {
          echo '{"status":1,"message":"Data telah lengkap, item sudah ditambahkan. Verifikasi sukses."}';
        } else {
          echo '{"status":-3,"message":"Error saat menambahkan ke item."}';
        }

      } else {
        echo '{"status":-1,"message":"Error saat memverifikasi penerimaan."}';
      }
    } else {
      echo '{"status":2,"message":"Sukses memverifikasi item(s)"}';
    }

  }
//##########################   PEMESANAN    ###########################
  public function ck_status($id = null, $return = null)
  {
    $res  = $this->db->get_where('pemesanan', array('id' => $id));
    $stat = $res->row()->status;

    $response;
    // if ($stat == 0) {
    if ($stat == 0 || $_SESSION['user_type'] == 'Admin Gudang' || $_SESSION['user_type'] == 'Super Admin') {
      $response = array(
        'status'   => 0,
        // 'status'   => $stat,
        'redirect' => base_url('transaksi/order_atk/edit/') . $id,
        'message'  => '',
      );
    } else {
      if ($stat >= 1) {
        if ($stat == 6) {
          $response = array('status' => $stat, 'redirect' => '#', 'message' => "Tidak dapat mengubah pemesanan. <br>Pemesanan telah <b style='color:red;'>DIBATALKAN</b>. <br><br>Klik <b>Reload</b> untuk me-refresh halaman.");
        } else {
          $response = array('status' => $stat, 'redirect' => '#', 'message' => "Tidak dapat mengubah pemesanan. <br>Pemesanan sudah disetujui. <br><br>Klik <b>Reload</b> untuk me-refresh halaman.");
        }
      } else {
        $response = array('status' => -1, 'redirect' => '#', 'message' => 'Terjadi kesalahan. Error Code : CK_ST(7263472834)');
      }
    }

    if ($return) {
      return $response;
    } else {
      echo json_encode($response);
    }
  }
  public function order_atk($mode = null, $id = null)
  {

    $this->load->model('model_transaksi');
    $this->load->model('model_user');
    $session_id = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    // $var['user_id'] = $this->model_transaksi->user_type($session_id);
    // $var['stat_user']=$var['user_id'][0]->user_type;
    $var['user']    = $_SESSION['user_type'];
    $var['id_user'] = $_SESSION['id_user'];

    $var['title'] = 'KERANJANG';
    if ($mode == 'view') {
      $var['s_active']   = 'pemesanan';
      $var['mode']       = 'view';
      $var['act_button'] = 'pemesanan';
      $var['page_title'] = 'KERANJANG';
      $var['title']      = 'KERANJANG';

      $tahun = null;

      if (!empty($_GET['tahun'])) {
        $tahun = $_GET['tahun'];
      } else {
        $tahun = date('Y');
      }

      if ($var['user'] == 'Karyawan') {
        $var['tb_pemesanan'] = $this->model_transaksi->tb_pemesanan(null, $var['id_user'], null, null, $tahun);
      }
      if ($var['user'] == 'Kurir') {
        $var['tb_pemesanan'] = $this->model_transaksi->tb_pemesanan(null, null, $var['id_user'], null, $tahun);
      }
      if ($var['user'] == 'Super Admin' || $var['user'] == 'Admin TOFAP' || $var['user'] == 'Admin' || $var['user'] == 'HC - Super Admin' || $var['user'] == 'Admin Gudang' || $var['user'] == 'Admin ATK') {
        $var['tb_pemesanan'] = $this->model_transaksi->tb_pemesanan(null, null, null, null, $tahun);
      }

      $var['list_kurir'] = $this->model_user->list_kurir();

      $var['stat_pemesanan'][0] = array('color' => 'bg-red', 'status' => 'Waiting Approval'); //Order Received
      $var['stat_pemesanan'][1] = array('color' => 'bg-darken-2', 'status' => 'Order Received');
      $var['stat_pemesanan'][2] = array('color' => 'bg-yellow', 'status' => 'Courier Assigned'); //Courier Assigned
      $var['stat_pemesanan'][3] = array('color' => 'bg-aqua', 'status' => 'Prepare Item'); //Packing Done
      $var['stat_pemesanan'][4] = array('color' => 'bg-blue', 'status' => 'Courier On The Way'); //Courier On The Way
      $var['stat_pemesanan'][5] = array('color' => 'btn-success', 'status' => 'Done');
      $var['stat_pemesanan'][6] = array('color' => 'btn-danger', 'status' => 'Cancel');

      $var['stat_kurir'][3] = array('color' => 'bg-aqua', 'status' => 'Prepare Item'); //Packing Done
      $var['stat_kurir'][4] = array('color' => 'bg-blue', 'status' => 'Courier On The Way'); //Courier On The Way
      $var['stat_kurir'][5] = array('color' => 'btn-success', 'status' => 'Done');
      //$var['stat_kurir'][5]=array('color'=>'btn-danger','status'=>'Cancel');
      // $var['stat_per_id'][2]=[];

      $var['jml_rate'] = $this->model_transaksi->getCRating($_SESSION['id_user']);

      // echo $var['jml_rate'][0]->jml_rate;
      // $i=$var['ck_ls'][0]->status+1; //1
      // for($i; $i<=5; $i++){
      //   array_push($var['send_data'][2], $var['stat_pemesanan'][$i]);
      // }

    }

    $var['it_bc'] = [];
    if ($mode == 'add') {
      $var['s_active']    = 'pemesanan';
      $var['mode']        = 'add';
      $var['nomor_order'] = '-';
      $var['page_title']  = 'KERANJANG';
      $var['title']       = 'KERANJANG';
      $this->load->model('model_user');
      $var['list_customer'] = $this->model_user->list_customer();
      $this->load->model('model_produk');
      $var['items']     = $this->model_produk->tb_item_is_av();
      $var['items_arr'] = json_decode(json_encode($var['items']), true);
      //print_r($var['items']);

      if (!array_key_exists('status', $var['items'])) {
        foreach ($var['items_arr'] as $in => $value) {
          $var['it_bc'][$in]['id']        = $value['ID_ITEM'];
          $var['it_bc'][$in]['barcode']   = $value['barcode'];
          $var['it_bc'][$in]['nama_item'] = $value['nama_item'];
        }
      }
      // $var['jml_rate'] = $this->model_transaksi->getCRating($_SESSION['id_user']);
      // if ($var['jml_rate'][0]->jml_rate == 0 && $var['jml_rate'][0]->pem_process == 0) {
      //   // $var['s_active']    = 'pemesanan';
      //   // $var['mode']        = 'add';
      //   // $var['nomor_order'] = '-';
      //   // $var['page_title']  = 'KERANJANG';
      //   // $var['title']       = 'KERANJANG';
      //   // $this->load->model('model_user');
      //   // $var['list_customer'] = $this->model_user->list_customer();
      //   // $this->load->model('model_produk');
      //   // $var['items']     = $this->model_produk->tb_item_is_av();
      //   // $var['items_arr'] = json_decode(json_encode($var['items']), true);
      //   // //print_r($var['items']);

      //   // if (!array_key_exists('status', $var['items'])) {
      //   //   foreach ($var['items_arr'] as $in => $value) {
      //   //     $var['it_bc'][$in]['id']        = $value['ID_ITEM'];
      //   //     $var['it_bc'][$in]['barcode']   = $value['barcode'];
      //   //     $var['it_bc'][$in]['nama_item'] = $value['nama_item'];
      //   //   }
      //   // }
      // } else {
      //   redirect('transaksi/order_atk/view');
      // }

    }

    if ($mode == 'edit') {
      $ck_status_pemesanan = $this->ck_status($id, 'return');
      // print_r($ck_status_pemesanan);
      if ($ck_status_pemesanan['status'] >= 1) {
        redirect('transaksi/order_atk/view');
      } else {
        $this->load->model('model_produk');
        $var['s_active']   = 'pemesanan';
        $var['mode']       = 'edit';
        $var['page_title'] = 'EDIT KERANJANG';

        $var['id']           = $id;
        $var['tb_pemesanan'] = $this->model_transaksi->tb_pemesanan($id);
        $var['list']         = $this->model_transaksi->list_item_pemesanan_edit($id);
        $this->load->model('model_user');
        $var['list_customer'] = $this->model_user->list_customer();
        $this->load->model('model_produk');
        $var['items'] = $this->model_produk->tb_item_is_av();
      }
    }

    $var['js']      = 'js-pemesanan';
    $var['plugin']  = 'plugin_1';
    $var['content'] = 'view-pemesanan';

    $this->load->view('view-index', $var);
  }

  public function trx_pemesanan()
  {
    echo $this->input->post('idd');
    $this->load->model('model_transaksi');
    $var['add_pengajuan'] = $this->model_transaksi->trx_pemesanan();
  }

  public function pemesanan_view()
  {
    $var['stat_pemesanan'][0] = array('color' => 'bg-red', 'status' => 'Waiting Approval'); //Order Received
    $var['stat_pemesanan'][1] = array('color' => 'bg-darken-2', 'status' => 'Order Received');
    $var['stat_pemesanan'][2] = array('color' => 'bg-yellow', 'status' => 'Courier Assigned'); //Courier Assigned
    $var['stat_pemesanan'][3] = array('color' => 'bg-aqua', 'status' => 'Prepare Item'); //Packing Done
    $var['stat_pemesanan'][4] = array('color' => 'bg-blue', 'status' => 'Courier On The Way'); //Courier On The Way
    $var['stat_pemesanan'][5] = array('color' => 'btn-success', 'status' => 'Done');
    $var['stat_pemesanan'][6] = array('color' => 'btn-danger', 'status' => 'Cancel');

    $this->load->model('model_transaksi');
    $var['list_item_pemesanan'] = $this->model_transaksi->list_item_pemesanan();
    // $var['list_item_pemesanan']->status_txt = 'SIP';

    foreach ($var['list_item_pemesanan'] as $key => $value) {
      $var['list_item_pemesanan'][$key]->status_txt = $var['stat_pemesanan'][$value->status]['status'];
    }

    // print_r($var['list_item_pemesanan']);
    if ($var['list_item_pemesanan']) {
      echo json_encode($var['list_item_pemesanan']);
    }
  }

  public function sh_item()
  {
    $this->load->model('model_produk');
    $var['items'] = $this->model_produk->tb_item_is_av();

    $this->load->view('view-select_item', $var);
  }

  public function del_it_pemesanan()
  {
    $id = $this->input->post('id');

    $this->db->where('id', $id);
    $this->db->update('item_pemesanan', array('is_delete' => 1));
  }

  public function add_kurir()
  {
    $id     = $this->input->post('id_kurir');
    $id_pem = $this->input->post('id_pem');

    $result = $this->db->get_where('pemesanan', array('id' => $id_pem));

    if ($result && $result->num_rows() > 0) {
      if ($result->row()->status >= 2) {
        if ($result->row()->status == 6) {
          echo json_encode(array('status' => 'INFORMATION', 'message' => 'Maaf, pemesanan telah dibatalkan sebelumnya. Status saat ini adalah <b>"' . $this->txt_stat($result->row()->status) . '"</b>. Anda tidak dapat menambahkan Kurir ke dalam pemesanan ini. <br><br>Klik OK untuk memuat ulang halaman.'));
          return false;
        } else {
          echo json_encode(array('status' => 'INFORMATION', 'message' => 'Kurir sudah ditambahkan pada pemesanan ini. Klik OK untuk memuat ulang halaman.'));
          return false;
        }
      } else {
        $data = array(
          'id'             => $id_pem,
          'status'         => 2,
          'id_kurir'       => $id,
          'add_kurir_date' => date('Y-m-d H:i:s'),
          'add_kurir_by'   => $_SESSION['id_user'],
        );
        $this->db->where('id', $id_pem);
        $act = $this->db->update('pemesanan', $data);

        if ($act) {
          echo json_encode(array('status' => 'success', 'message' => 'Sukses Mengubah Data'));

          //SEND NOTIF
          $this->load->model('model_mobile');
          $dt_pem     = $this->db->get_where('pemesanan', array('id' => $id_pem));
          $id_kurir   = $dt_pem->row()->id_kurir;
          $dt_kurir   = $this->db->get_where('user', array('id' => $id_kurir));
          $kurir_name = $dt_kurir->row()->name;
          $id_pemesan = $dt_pem->row()->id_pemesan;
          $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Kurir [' . $kurir_name . '] telah ditambahkan ke Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan);

          //TO LOG
          try {
            $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
            $name_user = $res_user->row()->name;
            $this->db->insert('log_keranjang', array(
              'insert_by'    => $_SESSION['id_user'],
              'id_keranjang' => $id_pem,
              'status'       => 2,
              'status_text'  => $this->txt_stat(2),
              'message'      => 'User dengan ID ' . $_SESSION['id_user'] . '(' . $_SESSION['name'] . ') menambahkan kurir ID:' . $id_kurir . '(' . $kurir_name . ') ke keranjang untuk USER_ID ' . $id_pemesan . '(' . $name_user . ') dengan ID Pemesanan ' . $id_pem,
            ));
          } catch (Exception $e) {

          }
        } else {
          echo json_encode(array('status' => 'error', 'message' => 'Error Mengubah Data, Silahkan coba kembali'));
        }
      }
    } else {
      echo json_encode(array('status' => 'error', 'message' => 'Error Mengubah Data, Silahkan coba kembali'));
    }

  }

  public function acc_order()
  {

    $id            = $this->input->post('id');
    $id_pem_to_log = $id;
    $result        = $this->db->get_where('pemesanan', array('id' => $id));
    $g_id_pemesan  = $result->row()->id_pemesan;

    if ($result && $result->num_rows() > 0) {
      if ($result->row()->status >= 1) {
        if ($result->row()->status == 6) {
          echo json_encode(array('status' => 'success', 'message' => 'Pemesanan Telah dibatalkan sebelumnya. Status saat ini adalah <b>"' . $this->txt_stat($result->row()->status) . '"</b>. <br><br>Klik OK untuk memuat ulang halaman.'));
          return false;
        } else {
          echo json_encode(array('status' => 'success', 'message' => 'Pemesanan Sudah disetujui sebelumnya. Status saat ini adalah <b>"' . $this->txt_stat($result->row()->status) . '"</b>. <br><br>Klik OK untuk memuat ulang halaman.'));
          return false;
        }
      }
    }

    $this->load->model('model_transaksi');
    $ls_item = json_decode(json_encode($this->model_transaksi->list_item_pemesanan($id)), true);

    $stat = 0;

    $error_message = array('status' => 'error', 'message' => []);

    foreach ($ls_item as $key => $val) {
      $stock_item = $this->db->select('id,qty,barcode,item_name')->where('id', $val['id_item'])->get('pos_item');
      if ((int) $stock_item->row()->qty <= 0) {
        array_push($error_message['message'], array('status' => -1, 'message' => 'Stock item <b>' . $val['item_name'] . '</b> dengan barcode <b>' . $val['barcode'] . '</b> <b style="color:red;">tidak tersedia</b>.'));
      } else {
        if ((int) $stock_item->row()->qty < $val['qty']) {
          array_push($error_message['message'], array('status' => -2, 'message' => 'Stock item <b>' . $val['item_name'] . '</b> saat ini dengan barcode <b>' . $val['barcode'] . '</b> <b style="color:red;">kurang dari jumlah pemesanan</b>.'));
        } else {
          $stat++;

        }
      }
    }

    if ($stat == count($ls_item)) {

      foreach ($ls_item as $k => $v) {
        $this->db->where('id', $v['id_it_pn']);
        $act_it_penerimaan = $this->db->update('item_pemesanan', array('h_stock' => $v['qty_item']));

        if ($act_it_penerimaan) {
          $this->db->set('qty', 'qty-' . $v['qty'], false);
          $this->db->where('id', $v['id_item']);
          $act_item = $this->db->update('pos_item');
        }
      }

      $data = array(
        'status' => 1,
      );
      $this->db->where('id', $id);
      $act = $this->db->update('pemesanan', $data);

      if ($act) {
        //echo json_encode(array('status'=>'success','message'=>'Sukses Mengubah Data'));

        //SEND NOTIF
        if (1 + 2 == 2) {
          try {
            $this->load->model('model_mobile');
            $dt_pem = $this->db->get_where('pemesanan', array('id' => $id));
            //$id_pemesan = $dt_pem->row()->id_pemesan;
            $id_pemesan = $g_id_pemesan;
            $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan . ' telah disetujui.');
          } catch (Exception $e) {

          }
        }

        //TO LOG
        try {
          $res_user  = $this->db->get_where('user', array('id' => $g_id_pemesan));
          $name_user = $res_user->row()->name;
          $this->db->insert('log_keranjang', array(
            'insert_by'    => $_SESSION['id_user'],
            'id_keranjang' => $id,
            'status'       => 1,
            'status_text'  => $this->txt_stat(1),
            'message'      => 'User dengan ID ' . $_SESSION['id_user'] . '(' . $_SESSION['name'] . ') menyetujui pemesanan keranjang untuk USER_ID ' . $g_id_pemesan . '(' . $name_user . ') dengan ID Pemesanan ' . $id,
          ));

          $it_pem_to_log = $this->db->get_where('item_pemesanan', array('id_pemesanan' => $id_pem_to_log));

          foreach ($it_pem_to_log->result() as $i => $v) {
            $getStock = $this->db->get_where('pos_item', array('id' => $v->id_item));
            $data     = array(
              'id_pemesan'     => $g_id_pemesan,
              'id_item'        => $v->id_item,
              'stock_awal'     => $getStock->row()->stock_awal,
              'stock_terakhir' => $v->h_stock,
              'action'         => 'KURANGI',
              'qty'            => $v->qty,
              'is_android'     => 0,
              'parent'         => 'KERANJANG',
              'id_parent'      => $v->id_pemesanan,
              'trigger'        => 'ITEM PEMESANAN',
              'id_trigger'     => $v->id,
              'insert_by'      => $_SESSION['id_user'],
              'update_by'      => $_SESSION['id_user'],
            );

            $insert = $this->db->insert('log_item', $data);
          }

        } catch (Exception $e) {

        }

        $this->ch_stat_from_admin($id, 5);
      } else {
        echo json_encode(array('status' => 'error', 'message' => array(0 => array('status' => 'error', 'message' => 'Error DB. Err Code (7676)'))));
      }
    } else {
      echo json_encode($error_message);
    }

  }

  public function ck_stat($get = null)
  {
    $dt = $this->input->post('dt');

    $this->load->model('model_transaksi');
    $var['ck_stat'] = $this->model_transaksi->ck_stat();

    $dt_post = json_decode(json_encode($dt), true);
    $dt_arr  = json_decode(json_encode($var['ck_stat']), true);

    //print_r($dt_arr);

    if ($get == 'get') {
      if ($var['ck_stat']) {
        $id = $this->input->post('id');
        $this->load->model('model_transaksi');
        $var['ck_ls'] = $this->model_transaksi->list_item_pemesanan_edit($id);
        //var_dump($var['ck_ls']);
        $stat[0] = array('color' => 'bg-red', 'status' => 'Waiting Approval'); //Order Received
        $stat[1] = array('color' => 'bg-darken-2', 'status' => 'Order Received');
        $stat[2] = array('color' => 'bg-yellow', 'status' => 'Courier Assigned'); //Courier Assigned
        $stat[3] = array('color' => 'bg-aqua', 'status' => 'Prepare Item'); //Packing Done
        $stat[4] = array('color' => 'bg-blue', 'status' => 'Courier On The Way'); //Courier On The Way
        $stat[5] = array('color' => 'btn-success', 'status' => 'Done');
        $stat[5] = array('color' => 'btn-danger', 'status' => 'Cancel');
        //print_r($var['ck_']);

        $var['send_data'][0] = $var['ck_stat'];
        $var['send_data'][1] = $var['ck_ls'];
        $var['send_data'][2] = [];

        $i = $var['ck_ls'][0]->status; //1
        // echo $i;
        // for($i; $i<=5; $i++){
        array_push($var['send_data'][2], $stat[$i]);
        // }

        echo json_encode($var['send_data']);
      }
    } else {
      //print_r($dt_post);
      foreach ($dt_post as $key => $value) {
        $d1 = $dt_post[$key]['id'] . '-' . $dt_post[$key]['status'];
        $d2 = $dt_arr[$key]['id'] . '-' . $dt_arr[$key]['status'];

        if ($d1 != $d2) {
          echo json_encode($dt_arr[$key]);
        }
      }
    }
  }

  public function pemesanan_btn_act($id = null, $status = null)
  {
    $var['s']      = $status;
    $var['id_pem'] = $id;
    $var['user']   = $_SESSION['user_type'];

    $this->load->view('view-pemesanan_btn_act', $var);

  }

  public function ch_stat_from_admin($id_pem = null, $stat = null)
  {
    if (empty($id_pem) || empty($stat)) {
      $id_pem = $this->input->post('id');
      $stat   = $this->input->post('stat');
    }

    //$id_pem = $this->input->post('id_pem');
    $data = array(
      'id'        => $id_pem,
      'status'    => $stat,
      'update_by' => $_SESSION['id_user'],
    );
    $this->db->where('id', $id_pem);
    $act = $this->db->update('pemesanan', $data);

    if ($act) {
      echo json_encode(array('status' => 'success', 'message' => 'Sukses Mengubah Data'));
      //SEND NOTIF
      $this->load->model('model_mobile');
      $dt_pem = $this->db->get_where('pemesanan', array('id' => $id_pem));

      // $id_kurir = $dt_pem->row()->id_kurir;
      // $dt_kurir = $this->db->get_where('user',array('id'=>$id_kurir));
      // $kurir_name = $dt_kurir->row()->name;

      $id_pemesan = $dt_pem->row()->id_pemesan;
      $stat_txt   = $this->txt_stat($dt_pem->row()->status);

      // $this->model_mobile->send_notif($id_pemesan,'TOFAP','Kurir ['. $kurir_name .'] mengubah status Pesanan anda dengan nomor pengambilan '. $dt_pem->row()->no_pemesanan.' menjadi '.$stat_txt);

      $this->model_mobile->send_notif($id_pemesan, 'TOFAP', 'Status Pesanan anda dengan nomor pengambilan ' . $dt_pem->row()->no_pemesanan . ' berubah menjadi ' . $stat_txt);

      //TO LOG
      // try{
      //   $res_user = $this->db->get_where('user',array('id'=>$id_pemesan));
      //   $name_user = $res_user->row()->name;
      //   $this->db->insert('log_keranjang',array(
      //     'insert_by'=>$_SESSION['id_user'],
      //     'id_keranjang'=>$id_pem,
      //     'status'=>$stat,
      //     'status_text'=>$this->txt_stat($stat),
      //     'message'=>'User dengan ID '. $_SESSION['id_user'].'('. $_SESSION['name'] .') mengubah status Order dengan kurir ID:'.$id_kurir.'('.$kurir_name.') ke keranjang untuk USER_ID '. $id_pemesan .'('.$name_user.') dengan ID Pemesanan '.$id_pem
      //   ));
      // }catch(Exception $e){

      // }

      try {
        $res_user  = $this->db->get_where('user', array('id' => $id_pemesan));
        $name_user = $res_user->row()->name;
        $this->db->insert('log_keranjang', array(
          'insert_by'    => $_SESSION['id_user'],
          'id_keranjang' => $id_pem,
          'status'       => $stat,
          'status_text'  => $this->txt_stat($stat),
          'message'      => 'User dengan ID ' . $_SESSION['id_user'] . '(' . $_SESSION['name'] . ') mengubah status Order keranjang untuk USER_ID ' . $id_pemesan . '(' . $name_user . ') dengan ID Pemesanan ' . $id_pem,
        ));
      } catch (Exception $e) {

      }
    } else {
      echo json_encode(array('status' => 'error', 'message' => 'Error Mengubah Data, Silahkan coba kembali'));
    }

  }

  public function cetak_pengajuan($id = null)
  {
    $this->load->library('m_pdf');

    $this->load->model('model_transaksi');
    $val['items'] = $this->model_transaksi->list_item_pengajuan($id);

    $val['nomor'] = $val['items'][0]->no_pengajuan;

    $val['diajukan_nama']     = $val['items'][0]->submiter_name;
    $val['diajukan_username'] = $val['items'][0]->submiter_username;
    $val['diajukan']          = $val['items'][0]->submiter_jabatan;
    $val['diajukan_rule']     = $val['items'][0]->submiter_rule;
    //
    $val['disetujui_id']      = $val['items'][0]->approval;
    $val['disetujui_nama']    = $val['items'][0]->approval_name;
    $val['disetujui']         = $val['items'][0]->approval_rule;
    $val['disetujui_jabatan'] = $val['items'][0]->approval_jabatan;
    $val['tgl_pengajuan']     = $val['items'][0]->tgl_pengajuan;

    $val['status_permintaan'] = $val['items'][0]->status_permintaan;

    if (!empty($val['items'])) {
      $date        = date_create($val['items'][0]->update_date);
      $val['date'] = date_format($date, "d-M-Y");
    }

    $this->load->view('view-print_pengajuan', $val);
    $html = $this->load->view('view-print_pengajuan', $val, true);

    // if(!empty($mode)){
    $css = [];

    // array_push($css, file_get_contents(base_url('assets/dist/css/AdminLTE.min.css')));
    // array_push($css, file_get_contents(base_url('assets/plugins/bootstrap/dist/css/bootstrap.min.css')));

    $pdfFilePath = $val['nomor'] . "__" . date('d-m-Y') . ".pdf";

    // $pdf = $this->m_pdf->load();
    $pdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../assets/temp']);
    // $mpdf = new Mpdf(['format' => 'Legal']);
    $pdf->SetHTMLFooter('<table width="100%" style="font-family: calibri; font-size: 11px;">
                      <tr>
                        <td align="left"><b>' . $val['nomor'] . '-' . $val['diajukan_username'] . '</b></td>
                        <td align="right"><b>Hal {PAGENO} dari {nb}</b></td>
                      </tr>
                    </table>');
    $pdf->AddPage('P', '', '', '', '', '', '', '', 30, 20, 20);
    foreach ($css as $key => $v) {
      $pdf->WriteHTML($v, 1);
    }

    $pdf->WriteHTML($html);

    $pdf->Output($pdfFilePath, "I");
    exit();

    // }
  }

  public function cetak_penerimaan($id = null)
  {
    $this->load->library('m_pdf');

    $this->load->model('model_transaksi');
    $val['items'] = $this->model_transaksi->list_item_pengajuan($id);

    if (!empty($val['items'])) {
      $date                = date_create($val['items'][0]->update_date);
      $val['date']         = date_format($date, "d-M-Y");
      $val['no_pengajuan'] = $val['items'][0]->no_pengajuan;

      $this->load->view('view-print_spb', $val);
      $html = $this->load->view('view-print_spb', $val, true);

      $css = [];

      $pdfFilePath = "SPB_" . $val['no_pengajuan'] . ".pdf";

      // $pdf = $this->m_pdf->load();
      $pdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../assets/temp']);
      // $mpdf = new Mpdf(['format' => 'Legal']);

      $pdf->AddPage('P', '', '', '', '', '', '', '', '', 20, 20);
      foreach ($css as $key => $v) {
        $pdf->WriteHTML($v, 1);
      }

      $pdf->WriteHTML($html);

      $pdf->Output($pdfFilePath, "I");
      exit();

    }
  }

  public function tanggal_indo($tanggal = null)
  {
    //TANGGAL FORMAT
    $bln = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $tgl_peng = date_create($tanggal);

    $it_hr  = date_format($tgl_peng, "d");
    $it_bln = $bln[(int) date_format($tgl_peng, "m")];
    $it_th  = date_format($tgl_peng, "Y");

    return $it_hr . ' ' . $it_bln . ' ' . $it_th;
    //-----------------------------------------------
  }

  public function print_spb($id = null)
  {
    $this->load->library('m_pdf');
    $this->load->library('tambahan');

    $this->load->model('model_transaksi');
    $val['items']     = $this->model_transaksi->ls_it_spb($id);
    $val['total_all'] = $this->model_transaksi->total_all_spb_by_id($id);

    $val['terbilang'] = $this->tambahan->terbilang($val['total_all'][0]->total_all, 3);

    if (!empty($val['items'])) {
      $date                     = date_create($val['items'][0]->update_date);
      $val['date']              = date_format($date, "d-M-Y");
      $val['no_pengajuan']      = $val['items'][0]->no_pengajuan;
      $val['supplier_name']     = $val['items'][0]->supplier_name;
      $val['supplier_address']  = $val['items'][0]->supplier_address;
      $val['supplier_pic_name'] = $val['items'][0]->supplier_pic_name;
      $val['supplier_phone']    = $val['items'][0]->supplier_phone;

      $val['tanggal_spb']       = $val['items'][0]->insert_date;
      $val['start_periode']     = $val['items'][0]->start_periode;
      $val['end_periode']       = $val['items'][0]->end_periode;
      $val['diajukan_username'] = $val['items'][0]->diajukan_oleh;

      $date_start                   = date_create($val['start_periode']);
      $val['start_per_format']      = date_format($date_start, "d F Y");
      $val['start_per_format_indo'] = $this->tanggal_indo($val['start_per_format']);

      $date_end                   = date_create($val['end_periode']);
      $val['end_per_format']      = date_format($date_end, "d F Y");
      $val['end_per_format_indo'] = $this->tanggal_indo($val['end_per_format']);

      $create_spb          = date_create($val['tanggal_spb']);
      $val['tgl_spb']      = date_format($create_spb, "d F Y");
      $val['tgl_spb_indo'] = $this->tanggal_indo($val['tgl_spb']);

      $val['date_ttd'] = date_format($create_spb, "Y-m-d");

      $date1            = date_create($val['start_periode']);
      $date2            = date_create($val['end_periode']);
      $val['diff']      = date_diff($date1, $date2);
      $val['date_diff'] = $val['diff']->format("%a");
      $val['date_text'] = $this->tambahan->terbilang($val['date_diff'], 3);

      $this->load->model('model_tandatangan');
      $val['data_ttd'] = $this->model_tandatangan->getDataTTD();

      $this->load->view('view-print_spb', $val);
      $html = $this->load->view('view-print_spb', $val, true);

      $css = [];

      $pdfFilePath = "SPB_" . $val['no_pengajuan'] . ".pdf";

      // $pdf = $this->m_pdf->load();
      $pdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../assets/temp']);

      $pdf->SetHTMLFooter('<table width="100%" style="font-family: calibri; font-size: 11px;">
                      <tr>
                        <td align="left">
                          <!--<b>' . $val['no_pengajuan'] . '-' . $val['diajukan_username'] . '</b>-->
                        </td>
                        <td align="right"><b>Hal {PAGENO} dari {nb}</b></td>
                      </tr>
                    </table>');

      $pdf->AddPage('P', '', '', '', '', 35, '', 40, 50, '', 50);
      foreach ($css as $key => $v) {
        $pdf->WriteHTML($v, 1);
      }

      $pdf->WriteHTML($html);

      $pdf->Output($pdfFilePath, "I");
      exit();

    }
  }
//##########################   PEMESANAN BARANG   #####################
  public function pemesanan_brg($mode = null, $id = null)
  {
    $this->load->model('model_transaksi');

    $session_id     = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $var['user_id'] = $this->model_transaksi->user_type($session_id);

    $var['title'] = 'PEMESANAN';

    if ($mode == 'view') {
      $var['s_active']         = 'pemesanan_brg';
      $var['mode']             = 'view';
      $var['act_button']       = 'pemesanan_brg';
      $var['page_title']       = 'PEMESANAN';
      $var['tb_pemesanan_brg'] = $this->model_transaksi->tb_pengajuan('', 'no_pengajuan');
    }

    if ($mode == 'edit') {
      $var['s_active']         = 'pemesanan_brg';
      $var['mode']             = 'edit';
      $var['id']               = $id;
      $var['act_button']       = 'pemesanan_brg';
      $var['page_title']       = 'EDIT PEMESANAN';
      $var['tb_pemesanan_brg'] = $this->model_transaksi->tb_pengajuan($id);
      $var['ls_it_pms']        = $this->model_transaksi->list_item_pengajuan($id);
      $var['ls_spb']           = $this->model_transaksi->list_spb($id);

    }

    $var['stat_user'] = $var['user_id'][0]->user_type;
    $var['user']      = $_SESSION['user_type'];

    $var['js']      = 'js-pemesanan_barang';
    $var['plugin']  = 'plugin_1';
    $var['content'] = 'view-pemesanan_barang';

    $this->load->view('view-index', $var);

  }

  public function sh_pemesanan_brg($id = null)
  {
    if (empty($id)) {
      $id = $this->input->post('id');
    }
    $this->load->model('model_transaksi');
    // $var['plugin'] = 'plugin_1';
    $var['ls_it_pms'] = $this->model_transaksi->list_item_pengajuan($id);
    // print_r($var['ls_it_pms']);
    if ($var['ls_it_pms']) {
      $this->load->view('view-pms_tb.php', $var);
    }
  }

  public function create_spb()
  {
    $id_pem        = $this->input->post('id_pem');
    $id_permintaan = $this->input->post('id_permintaan');
    $items         = $this->input->post('items');
    $spb           = array('status' => '', 'message' => '');
    $spb['spb']    = $this->input->post('supplier');
    $s_date        = $this->input->post('start');
    $e_date        = $this->input->post('end');

    try {
      try {
        foreach ($spb['spb'] as $k => $v) {
          $spb['spb'][$k]['items'] = [];
        }
      } catch (Exception $e) {

      } finally {
        foreach ($spb['spb'] as $key => $value) {
          $spb['spb'][$key]['insert_by'] = $_SESSION['id_user'];
          $spb['spb'][$key]['update_by'] = $_SESSION['id_user'];

          $data_spb = array(
            'start_periode' => $s_date,
            'end_periode'   => $e_date,
            'id_permintaan' => $id_pem,
            'id_supplier'   => $spb['spb'][$key]['id_supplier'],
            'update_by'     => $_SESSION['id_user'],
            'insert_by'     => $_SESSION['id_user'],
          );
          $this->db->insert('spb', $data_spb);

          $last_id                    = $this->db->insert_id();
          $spb['spb'][$key]['id_spb'] = $last_id;

          // $nomor = $this->nomor('nomor_spb','/SPB/HRG-PROC/');
          //diganti menjadi (Per 20 September 2018)
          // $nomor = $this->nomor('nomor_spb', '/SPB/PRC-ATPI/');
          // PRC-ATPI diganti menjadi CCG-SF/ATPI 20 Feb 2020
          ///$nomor = $this->nomor('nomor_spb', '/SPB/CCG-SF/ATPI/');
          ///$nomor = $this->nomor('nomor_spb', '/SPB/HRG-SF/');
          $nomor = $this->nomor('nomor_spb', '/SPB/HCS-SF/');
          //echo $nomor; die();
          // $no_spb = $last_id.'/SPB/TPI/I/'.date('Y');
          $spb['spb'][$key]['no_spb'] = $nomor;

          $this->db->where('id', $last_id);
          $this->db->update('spb', array('no_spb' => $nomor));

          $dt_spb = $this->db->query('select
                            s.*,
                            u_i.name as dibuat_oleh,
                            u_u.name as diubah_oleh

                        from
                            spb as s
                            join user as u_i
                            join user as u_u
                        where
                            s.insert_by=u_i.id
                            and s.update_by=u_u.id
                            and s.id=' . $last_id);

          if ($dt_spb->num_rows() > 0) {
            $spb['spb'][$key]['update_by']   = $dt_spb->row()->update_by;
            $spb['spb'][$key]['update_date'] = $dt_spb->row()->update_date;
            $spb['spb'][$key]['insert_by']   = $dt_spb->row()->insert_by;
            $spb['spb'][$key]['insert_date'] = $dt_spb->row()->insert_date;
            $spb['spb'][$key]['dibuat_oleh'] = $dt_spb->row()->dibuat_oleh;
            $spb['spb'][$key]['diubah_oleh'] = $dt_spb->row()->diubah_oleh;
          }

          foreach ($items as $key_it => $value_it) {
            if ($value_it['id_supplier'] == $value['id_supplier']) {
              $items[$key_it]['id_spb'] = $last_id;
              $item                     = $this->db->where('id', $value_it['id_item'])->select('*')->get('pos_item');
              if ($item->num_rows() > 0) {
                $items[$key_it]['h_stock'] = $item->row()->qty;
              }
              $items[$key_it]['insert_by'] = $_SESSION['id_user'];
              $items[$key_it]['update_by'] = $_SESSION['id_user'];

              $this->db->set('qty_spb', 'qty_spb+' . $items[$key_it]['qty'], false);
              $this->db->where('id_pengajuan', $items[$key_it]['id_pemesanan_brg']);
              $this->db->where('id_item', $items[$key_it]['id_item']);
              $this->db->update('item_pengajuan');

              // print_r($items[$key_it]);
              array_push($spb['spb'][$key]['items'], $items[$key_it]);

              $this->db->insert('item_spb', $items[$key_it]);
            }
          }
        }
      }
    } catch (Exception $er) {

    } finally {
      $it_permintaan = $this->db->where('id_pengajuan', $id_permintaan)->select('*')->get('item_pengajuan');
      if ($it_permintaan->num_rows() > 0) {
        $status_spb = $it_permintaan->num_rows();

        $it_ip = $it_permintaan->result();
        foreach ($it_ip as $key_ip => $val_ip) {
          if ($val_ip->qty == $val_ip->qty_spb) {
            $status_spb--;
          }
        }

        if ($status_spb == 0) {
          $spb['status']  = 1;
          $spb['message'] = 'ITEM SPB LENGKAP.';
        }
      }
    }

    echo json_encode($spb);
  }

//##########################   PENERIMAAN BARANG  #######################
  public function penerimaan_brg($mode = null, $id = null)
  {
    $this->load->model('model_transaksi');

    $session_id     = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $var['user_id'] = $this->model_transaksi->user_type($session_id);

    $var['title'] = 'PENERIMAAN';

    if ($mode == 'view') {
      $var['s_active']   = 'penerimaan';
      $var['mode']       = 'view';
      $var['act_button'] = 'penerimaan_brg';
      $var['page_title'] = 'PENERIMAAN';
      $var['tb_spb']     = $this->model_transaksi->tb_spb();
    }

    if ($mode == 'edit') {
      $var['s_active']         = 'pemesanan_brg';
      $var['mode']             = 'edit';
      $var['id']               = $id;
      $var['act_button']       = 'pemesanan_brg';
      $var['page_title']       = 'EDIT PEMESANAN';
      $var['tb_pemesanan_brg'] = $this->model_transaksi->tb_pengajuan($id);
      $var['ls_it_pms']        = $this->model_transaksi->list_item_pengajuan($id);
      $var['ls_spb']           = $this->model_transaksi->list_spb($id);

    }

    $var['stat_user'] = $var['user_id'][0]->user_type;
    $var['user']      = $_SESSION['user_type'];

    $var['js']      = 'js-penerimaan';
    $var['plugin']  = 'plugin_1';
    $var['content'] = 'view-penerimaan';

    $this->load->view('view-index', $var);

  }

  public function get_select2_spb_without_bast()
  {
    $this->load->model('model_transaksi');
    $data = $this->model_transaksi->get_select2_spb_without_bast($this->input->post());

    $this->output->set_output(json_encode($data));
  }

  public function bast_penerimaan_brg($mode = null, $id = null)
  {
    $this->load->model('model_transaksi');

    $session_id     = $this->session->userdata('id_user') ? $this->session->userdata('id_user') : '1';
    $var['user_id'] = $this->model_transaksi->user_type($session_id);

    $var['title'] = 'BAST PENERIMAAN';

    if ($mode == 'view') {
      $var['s_active']   = 'bast_penerimaan';
      $var['mode']       = 'view';
      $var['act_button'] = 'penerimaan_brg';
      $var['page_title'] = 'BAST PENERIMAAN';
      $var['tb_spb']     = $this->model_transaksi->tb_spb(true);
    }

    $var['stat_user'] = $var['user_id'][0]->user_type;
    $var['user']      = $_SESSION['user_type'];

    $var['js']      = 'js-bast-penerimaan';
    $var['plugin']  = 'plugin_1';
    $var['content'] = 'view-bast-penerimaan';

    $this->load->view('view-index', $var);

  }

  public function create_bast()
  {
    //$nomor = $this->nomor('nomor_bast', '/BAST/CCG-SF/ATPI/');
    //$nomor = $this->nomor('nomor_bast', '/BAST/HRG-SF/');
    $nomor = $this->nomor('nomor_bast', '/BAST/HCS-SF/');

    $data['no_bast']      = $nomor;
    $data['tanggal_bast'] = $this->input->post('tanggal_bast');

    $this->db->set('no_bast', $nomor);
    $this->db->set('tanggal_bast', date('Y-m-d', strtotime($data['tanggal_bast'])));
    $this->db->where('id', $this->input->post('spb'));
    $this->db->update('spb');

    $this->output->set_output(json_encode($data));

  }

  public function sh_spb()
  {
    $id = $this->input->post('id');

    $this->load->model('model_transaksi');
    // $var['plugin'] = 'plugin_1';
    $var['ls_it_spb'] = $this->model_transaksi->list_item_spb($id);
    // print_r($var['ls_it_pms']);
    if ($var['ls_it_spb']) {
      $this->load->view('view-sh_spb', $var);
    }
  }

  public function edit_item_spb()
  {
    $id   = $this->input->post('id');
    $jml  = $this->input->post('jumlah');
    $awal = $this->input->post('jumlah_awal');
    $spb  = array('status' => '', 'message' => '');

    $upd_item_spb = $this->db->set('qty', $jml)->where('id', $id)->update('item_spb');
    if ($upd_item_spb) {
      $it_spb = $this->db->where('id', $id)->select('*')->get('item_spb');
      if ($it_spb->num_rows() > 0) {
        $id_permintaan       = $it_spb->row()->id_pemesanan_brg;
        $id_item             = $it_spb->row()->id_item;
        $upd_item_permintaan = $this->db->set('qty_spb', '(qty_spb-' . $awal . ')+' . $jml, false)->where('id_pengajuan', $id_permintaan)->where('id_item', $id_item)->update('item_pengajuan');

        if ($upd_item_permintaan) {
          $it_permintaan = $this->db->where('id_pengajuan', $id_permintaan)->select('*')->get('item_pengajuan');
          if ($it_permintaan->num_rows() > 0) {
            $status_spb = $it_permintaan->num_rows();

            $it_ip = $it_permintaan->result();
            foreach ($it_ip as $key_ip => $val_ip) {
              if ($val_ip->qty == $val_ip->qty_spb) {
                $status_spb--;
              }
            }

            if ($status_spb == 0) {
              $spb['status']  = 1;
              $spb['message'] = 'ITEM SPB LENGKAP.';

            } else {
              $spb['status']  = 0;
              $spb['message'] = 'Masih ada item SPB yang masih kosong. Membuat SPB baru?';
            }
          }
        } else {
          $spb['status']  = -1;
          $spb['message'] = 'Error Saat mengambil data';
        }
      }
    }

    echo json_encode($spb);
  }

  public function verifikasi_spb()
  {
    $id          = $_POST['id'];
    $spb         = array('status' => '', 'message' => '');
    $blm_lengkap = [];
    $msg_it      = '<br>';

    if (!empty($id)) {
      $it_permintaan = $this->db->query(' select
                            ip.*,
                            i.item_name,
                            i.barcode,
                            (select (ip.qty-ip.qty_spb)) as sisa,
                            i.satuan
                        from
                            item_pengajuan as ip
                            join pos_item as i
                        where
                            ip.id_item=i.id
                            and ip.id_pengajuan=' . $id . '
                            and ip.is_delete=0');

      if ($it_permintaan->num_rows() > 0) {
        $status_spb = $it_permintaan->num_rows();

        $it_ip = $it_permintaan->result();
        foreach ($it_ip as $key_ip => $val_ip) {
          if ($val_ip->qty == $val_ip->qty_spb) {
            $status_spb--;
          } else {
            array_push($blm_lengkap, array(
              'id_item'       => $val_ip->id_item,
              'id_permintaan' => $val_ip->id_pengajuan,
              'barcode'       => $val_ip->barcode,
              'item_name'     => $val_ip->item_name,
              'sisa'          => $val_ip->sisa,
              'satuan'        => $val_ip->satuan,
            ));
          }
        }

        if ($status_spb == 0) {
          $spb['status']  = 1;
          $spb['message'] = 'ITEM SPB LENGKAP.';

          $this->db->set('stat_spb', 1);
          $this->db->set('closed_date', date('Y-m-d h:i:s'));
          $this->db->set('closed_by', $_SESSION['id_user']);
          $this->db->where('id', $id);
          $this->db->update('pengajuan');

        } else {
          $spb['status'] = 0;

          foreach ($blm_lengkap as $key_i => $val_i) {
            // print_r($val_i);
            $msg_it .= '- Item <b style="color:blue;">' . $val_i['item_name'] . '</b> dengan barcode <b style="color:purple;">' . $val_i['barcode'] . '</b> masih tersisa <b style="color:red;">' . $val_i['sisa'] . ' ' . $val_i['satuan'] . '</b><br>';
          }
          $spb['message'] = 'Masih ada item SPB yang masih kosong. ' . $msg_it;

        }
      }
    } else {
      $spb['status']  = (-1);
      $spb['message'] = 'ID TIDAK DIISI';
    }
    echo json_encode($spb);

  }

  public function del_spb()
  {
    $id = $_POST['id'];
    $this->load->model('model_transaksi');
    $it_spb         = $this->model_transaksi->ls_it_spb($id);
    $jml_it_dihapus = count($it_spb);
    // $spb_create_date = $it_spb[0]->insert_date;
    // $no_spb = $it_spb[0]->no_pengajuan;
    // print_r(substr($no_spb, 0, 3));
    // print_r(date('m', strtotime($spb_create_date)));
    // exit;

    try {
      if (!empty($it_spb)) {
        foreach ($it_spb as $key => $value) {
          $upd_qty = $this->db->set('qty_spb', 'qty_spb-' . $value->qty, false)
            ->where('id_pengajuan', $value->id_pemesanan_brg)
            ->where('id_item', $value->id_item)
            ->update('item_pengajuan');

          if ($upd_qty) {
            $jml_it_dihapus--;
          } else {
            echo '{"status":-2,"message":"Error Mengubah data."}';
            return false;
          }
        }

      } else {
        echo '{"status":-1,"message":"SPB Sudah dihapus sebelumnya"}';
      }
    } catch (Exception $e) {

    } finally {
      if ($jml_it_dihapus == 0) {
        $del = $this->db->set('is_delete', 1)
          ->where('id', $id)
          ->update('spb');
        // Hapus data di tabel nomor_spb juga

        if ($del) {
          echo '{"status":1,"message":"Berhasil menghapus SPB"}';
        } else {
          echo '{"status":0,"message":"Gagal menghapus SPB"}';
        }
      } else {
        echo '{"status":-3,"message":"Gagal menghapus SPB. Beberapa Item tidak terhapus"}';
      }
    }

  }

  //// PENERIMAAN BARANG

  public function sh_penerimaan()
  {
    $id   = $this->input->post('id');
    $mode = $this->input->post('mode');

    $this->load->model('model_transaksi');
    // $var['plugin'] = 'plugin_1';

    $var['ls_it_spb'] = $this->model_transaksi->list_item_spb($id);
    $var['spb_info']  = $var['ls_it_spb'][0];
    $var['mode']      = $mode;
    // print_r($var['ls_it_pms']);
    if ($var['ls_it_spb']) {
      $this->load->view('view-sh_penerimaan', $var);
    }
  }

  public function export_pdf_bast()
  {
    $id = $this->input->get('id');

    $this->load->model('model_transaksi');

    $var['ls_it_spb']    = $this->model_transaksi->list_item_spb($id);
    $var['spb_info']     = $var['ls_it_spb'][0];
    $var['tanggal_bast'] = $this->input->get('date');

    // echo "<pre>";
    // print_r($var['spb_info']);
    // exit;

    $this->load->view('view-export_pdf_bast', $var);

  }

  public function verifikasi_penerimaan_barang()
  {
    $items     = $_POST['items'];
    $note      = $_POST['note'];
    $id_spb    = $_POST['id'];
    $c_it_spb  = count($items);
    $c_it_sama = count($items);
    // print_r($items);
    $upd_note = $this->db
      ->where('id', $id_spb)
      ->set('note', $note)
      ->set('update_by', $_SESSION['id_user'])
      ->update('spb');

    foreach ($items as $key => $value) {
      $ck_item_spb = $this->db
        ->where('id_spb', $value['id_spb'])
        ->where('id_item', $value['id_item'])
        ->where('id_supplier', $value['id_supplier'])
        ->select('*')
        ->get('item_spb');

      if ($ck_item_spb->row()->qty == $ck_item_spb->row()->qty_masuk) {
        $c_it_spb--;
        $c_it_sama--;
      } else {
        $upd_spb = $this->db
          ->where('id_spb', $value['id_spb'])
          ->where('id_item', $value['id_item'])
          ->where('id_supplier', $value['id_supplier'])
          ->set('qty_masuk', 'qty_masuk+' . $value['qty'], false)
          ->update('item_spb');

        if ($upd_spb) {
          $c_it_spb--;

          $this->db->set('qty', 'qty+' . $value['qty'], false);
          $this->db->where('id', $value['id_item']);
          $update_item = $this->db->update('pos_item');

          // $qtyAdd = $value['qty'];

          if ($update_item && (int) $value['qty'] > 0) {
            $CK_IT = $this->db->get_where('pos_item', array('id' => $value['id_item']));

            $data = array(
              'id_item'        => $value['id_item'],
              'stock_awal'     => $CK_IT->row()->stock_awal,
              'stock_terakhir' => $CK_IT->row()->qty,
              'action'         => 'TAMBAH',
              'qty'            => $value['qty'],
              'is_android'     => 0,
              'parent'         => 'PENERIMAAN',
              'id_parent'      => $id_spb,
              'trigger'        => 'ITEM SPB',
              'id_trigger'     => $ck_item_spb->row()->id,
              'insert_by'      => $_SESSION['id_user'],
              'update_by'      => $_SESSION['id_user'],
            );
            $insert = $this->db->insert('log_item', $data);
          }

          $ck_count = $this->db
            ->where('id_spb', $value['id_spb'])
            ->where('id_item', $value['id_item'])
            ->where('id_supplier', $value['id_supplier'])
            ->select('*')
            ->get('item_spb');

          if ($ck_count->row()->qty == $ck_count->row()->qty_masuk) {
            $c_it_sama--;
          }
        }
      }
    }

    if ($c_it_sama == 0 && $c_it_spb == 0) {
      $current = $this->db->select('CURRENT_TIMESTAMP() as now_date')->get();

      $upd_stat_spb = $this->db
        ->where('id', $id_spb)
        ->set('status', 1)
        ->set('update_by', $_SESSION['id_user'])
        ->set('receiver', $_SESSION['id_user'])
        ->set('receive_date', $current->result()[0]->now_date)
        ->update('spb');

      if ($upd_stat_spb) {
        echo '{"status":1,"message":"Data sukses diverifikasi<br>Jumlah keseluruhan Item Penerimaan sudah lengkap.<br>Status Penerimaan : <b style=\"color:red;\"> CLOSED </b>"}';
      } else {
        echo '{"status":2,"message":"Jumlah keseluruhan Item Penerimaan sudah lengkap.<br><br>Terjadi Kesalahan saat mengubah Status Penerimaan<br>Status Penerimaan : <b style=\"color:green;\"> OPEN </b>"}';
      }
    } else {
      echo '{"status":3,"message":"Berhasil memverifikasi item Penerimaan<br>Status Penerimaan : <b style=\"color:green;\"> OPEN </b>"}';
    }

    // if($c_it_spb==9999){

    //   $it_spb = $this->db
    //       ->where('id_spb',$value['id_spb'])
    //     ->where('id_supplier',$value['id_supplier'])
    //     ->select('*')
    //     ->get('item_spb');

    //   $c_it = count($it_spb->result());

    //   if($it_spb->num_rows()>0){
    //     foreach ($it_spb->result() as $k => $v) {
    //       if($v->qty==$v->qty_masuk){
    //         $c_it--;
    //       }
    //     }

    //     if($c_it==0){
    //       $current = $this->db->select('CURRENT_TIMESTAMP() as now_date')->get();

    //       $upd_stat_spb = $this->db
    //         ->where('id',$id_spb)
    //         ->set('status',1)
    //         ->set('update_by',$_SESSION['id_user'])
    //         ->set('receiver',$_SESSION['id_user'])
    //         ->set('receive_date',$current->result()[0]->now_date)
    //         ->update('spb');

    //       if($upd_stat_spb){
    //         $count_all=count($it_spb->result());
    //         $upd_it = $this  ->db
    //                   ->where('id_spb',$items[0]['id_spb'])
    //                 ->where('id_supplier',$items[0]['id_supplier'])
    //                 ->select('*')
    //                 ->get('item_spb');

    //         // if($upd_it->num_rows()>0){
    //         //   foreach ($upd_it->result() as $kU => $vU) {
    //         //     if($vU->qty==$vU->qty_masuk){
    //         //       $this->db->set('qty', 'qty+'.$vU->qty_masuk, FALSE);
    //         //       $this->db->where('id',$vU->id_item);
    //         //       $u_item = $this->db->update('pos_item');
    //         //       if($u_item){
    //         //         echo '{"status":1,"message":"Data sukses diverifikasi<br>Jumlah keseluruhan Item Penerimaan sudah lengkap.<br>Status Penerimaan : <b> CLOSED </b>"}';
    //         //       }else{
    //         //         echo '{"status":1,"message":"Data sukses diverifikasi<br>Jumlah keseluruhan Item Penerimaan sudah lengkap.<br>
    //         //         <font color=\"red\">Terdapat kesalahan saat menambahkan Stock Item.</font><br> Status Penerimaan : <b> CLOSED </b>"}';
    //         //       }
    //         //     }
    //         //   }
    //         // }

    //       }else{
    //         echo '{"status":2,"message":"Jumlah keseluruhan Item Penerimaan sudah lengkap.<br><br>Terjadi Kesalahan saat mengubah Status Penerimaan<br>Status Penerimaan : <b> OPEN </b>"}';
    //       }

    //     }else{
    //       echo '{"status":3,"message":"Berhasil memverifikasi item Penerimaan<br>Status Penerimaan : <b> OPEN </b>"}';
    //     }
    //   }else{
    //     echo '{"status":0,"message":"Data tidak ditemukan."}';
    //   }
    // }else{
    //   echo '{"status":-1,"message":"Data tidak lengkap/sebagian item terhapus."}';
    // }

  }

  public function del_upload_file_spb()
  {
    $id     = $_POST['id'];
    $upd_db = $this->db->where('id', $id)->set('attach_file', null)->update('spb');
    if ($upd_db) {
      echo '{"status":1,"message":"Sukses menghapus document."}';
    } else {
      echo '{"status":0,"message":"Gagal menghapus document SPB"}';
    }
  }

  public function ch_period()
  {
    $id     = $_POST['id'];
    $s_date = $_POST['start'];
    $e_date = $_POST['end'];

    // sleep(5);
    $upd_period = $this->db
      ->where('id', $id)
      ->set('start_periode', $s_date)
      ->set('end_periode', $e_date)
      ->update('spb');

    if ($upd_period) {
      echo '{"status":1,"message":"Sukses Mengubah tanggal periode SPB."}';
    } else {
      echo '{"status":0,"message":"Gagal Mengubah tanggal periode SPB"}';
    }
  }

  public function upd_hs($id = null)
  {
    $q = $this->db->query("select
                      ip.id as ID_ITEM_PEM,
                      i.item_name,
                      ip.h_stock,
                      ip.qty,
                      p.`status`,
                      p.tgl_pemesanan,
                      p.id as ID_PEMESANAN,
                      i.qty as sisa,
                      ip.insert_by,
                      ip.insert_date,
                      p.update_date,
                      u_p.name as user_insert,
                      u_p.user_type

                from
                      item_pemesanan as ip
                      join pemesanan as p on ip.id_pemesanan=p.id
                      join pos_item as i on ip.id_item=i.id
                      join user as u_p on ip.insert_by=u_p.id
                where
                      ip.id_item=" . $id . "
                order by p.tgl_pemesanan desc");

    $a = $q->row()->sisa;
    echo 'SISA : ' . $a;
    echo '<br>';
    echo 'H_STOCK___QTY<br><br>';
    foreach ($q->result() as $key => $value) {
      if ($value->user_type == 'Karyawan') {
        $is_android = 1;
      } else {
        $is_android = 0;
      }
      if ($value->status > 0 && $value->status < 6) {
        echo '<li>';
        $a += $value->qty;
        echo $a . "_________" . $value->qty;
        $this->db->where('id', $value->ID_ITEM_PEM);
        $st = $this->db->update('item_pemesanan', array('h_stock' => $a));
        if ($st) {

          $data = array(
            'id_item'        => $id,
            'stock_terakhir' => $a,
            'action'         => 'KURANGI',
            'qty'            => $value->qty,
            'is_android'     => $is_android,
            'parent'         => 'KERANJANG',
            'id_parent'      => $value->ID_PEMESANAN,
            'trigger'        => 'ITEM PEMESANAN',
            'id_trigger'     => $value->ID_ITEM_PEM,
            'insert_by'      => $value->insert_by,
            'insert_date'    => $value->insert_date,
            'update_by'      => $value->insert_by,
            'update_date'    => $value->update_date,
          );
          $insert    = $this->db->insert('log_item', $data);
          $insert_id = $this->db->insert_id();
          if ($insert) {
            echo "----->SUCCESS<br>";
          } else {
            echo "----->ERROR<br>";
          }
        } else {
          echo "----->ERROR<br>";
        }
      } else {
        if ($value->status == 6) {
          echo '<li>';
          echo $a . "_________" . $value->qty . " (CANCEL)";
          $this->db->where('id', $value->ID_ITEM_PEM);
          $st = $this->db->update('item_pemesanan', array('h_stock' => $a));
          if ($st) {

            $data = array(
              'id_item'        => $id,
              'stock_terakhir' => $a,
              'action'         => 'TAMBAH',
              'qty'            => 0,
              'is_android'     => $is_android,
              'parent'         => 'KERANJANG',
              'id_parent'      => $value->ID_PEMESANAN,
              'trigger'        => 'ITEM PEMESANAN',
              'id_trigger'     => $value->ID_ITEM_PEM,
              'insert_by'      => $value->insert_by,
              'insert_date'    => $value->insert_date,
              'update_by'      => $value->insert_by,
              'update_date'    => $value->update_date,
            );
            $insert    = $this->db->insert('log_item', $data);
            $insert_id = $this->db->insert_id();
            if ($insert) {
              echo "----->SUCCESS<br>";
            } else {
              echo "----->ERROR<br>";
            }
          } else {
            echo "----->ERROR<br>";
          }
        }
      }
    }
    echo '<br>STOCK AWAL : ' . $a;
    $this->db->where('id_item', $id);
    $stock_awal = $this->db->update('log_item', array('stock_awal' => $a));

    $this->db->where('id', $id);
    $stock_awal_item = $this->db->update('pos_item', array('stock_awal' => $a));

    if ($stock_awal && $stock_awal_item) {
      echo "----->SUCCESS<br>";
    } else {
      echo "----->ERROR<br>";
    }
  }

  public function update_all_hs()
  {
    $q = $this->db->query('select
                      ip.id as ID_ITEM_PEM,
                      ip.id_item,
                      i.item_name,
                      ip.h_stock,
                      ip.qty,
                      (select ip.h_stock-ip.qty) as SISA,
                      i.qty as sisa,
                      p.`status`,
                      p.tgl_pemesanan

                from
                      item_pemesanan as ip
                      join pemesanan as p on ip.id_pemesanan=p.id
                      join pos_item as i on ip.id_item=i.id

                group by ip.id_item
                order by p.tgl_pemesanan desc');
    foreach ($q->result() as $key => $value) {
      echo "<br>" . $value->item_name . "<br>";
      $this->upd_hs($value->id_item);
      echo "<br>###############################################################<br>";
    }
  }

  public function set_item()
  {
    $arr_item = [28362, 71640, 49542, 54127, 8998899002033, 8997020369083, 8999999390198, 62046, 53739, 8992742375350, 8993053111149, 67809, 59354, 8992936115021, 8995177102058, 8850124006677, 8992696408869, 8994171101715, 8991002105423, 22101, 46796, 8992696430624];

    $arr_qty = [2, 0, 1, 19, 4, 1, 8, 17, 1, 11, 4, 15, 15, 40, 37, 0, 2, 20, 13, 12, 17, 11];

    foreach ($arr_item as $key => $val) {
      $this->db->where('barcode', $val);
      $st = $this->db->update('pos_item', array('qty' => $arr_qty[$key]));
      if ($st) {
        $q = $this->db->get_where('pos_item', array('barcode' => $val));
        echo $q->row()->barcode . " --> " . $q->row()->item_name . " = " . $q->row()->qty . " --> SUCCESS<br>";
      } else {
        $q = $this->db->get_where('pos_item', array('barcode' => $val));
        echo $q->row()->barcode . " --> " . $q->row()->item_name . " = " . $q->row()->qty . " --> ERROR<br>";
      }
    }
  }
}
