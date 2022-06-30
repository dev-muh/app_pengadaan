<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

class Report extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    // session_destroy();
    $username   = !empty($_GET['username']) ? $_GET['username'] : '';
    $password   = !empty($_GET['password']) ? sha1(md5($_GET['password'])) : '';
    $query      = $this->db->query("select * from user where username='" . $username . "' and password='" . $password . "'");
    $is_android = false;
    if ($query->num_rows() > 0) {
      $is_android = true;
    }

    if (count($_SESSION) <= 1) {
      if ($_GET['mobile'] == 1 && $is_android == true) {
        $_SESSION['user_type'] = 'Admin TOFAP';
        // session_destroy();

        // $mobile='SIP';
      } else {
        redirect('login');
      }
    }
  }

  public function log_item()
  {
    $var['s_active'] = 'item';
    $var['mode']     = 'view';
    // $var['act_button']='report';
    $var['page_title'] = 'LAPORAN';
    $var['user']       = $_SESSION['user_type'];
    $var['js']         = 'js-history_item';
    $var['plugin']     = 'plugin_1';
    $var['content']    = 'view-history_item';

    if (!empty($_GET['id'])) {
      $id = $_GET['id'];
    } else {
      return false;
      echo "Anda belum memasukkan ID ITEM dan Tahun";
    }

    $tahun = date('Y');
    if (!empty($_GET['tahun'])) {
      $tahun = $_GET['tahun'];
    }

    $var['tahun_cur']   = $_GET['tahun'];
    $var['item_masuk']  = 0;
    $var['item_keluar'] = 0;

    $this->load->model('model_report');
    $var['it_info'] = $this->db->get_where('pos_item', array('id' => $id))->result();

    $var['log_item'] = $this->model_report->report_log_item($id, $tahun);
    if (!empty($var['log_item'])) {
      foreach ($var['log_item'] as $key => $val) {
        $var['item_masuk'] += $val->item_masuk;
        $var['item_keluar'] += $val->item_keluar;
      }
    }

    $var['tahun'] = $this->db->query('select year(insert_date) as tahun from log_item group by year(insert_date)')->result();
    $var['id']    = $id;

    $this->load->view('view-index', $var);
  }

  public function log_by_karyawan($api = null)
  {
    // $tipe = $_GET['type'];

    // if(empty($tipe) || !($tipe=='employee'||$tipe=='group')){
    //   redirect(base_url());
    // }else{
    //   if($_SESSION['user_type']=='Karyawan' && $tipe=='group'){
    //     redirect(base_url());
    //   }
    // }

    $by        = $_GET['by'];
    $var['by'] = $_GET['by'];
    $this->load->model('model_report');

    $var['s_active']   = 'report_emp_group';
    $var['mode']       = 'view';
    $var['act_button'] = 'report';
    $var['page_title'] = 'LAPORAN';
    // $var['stat_user']=$var['user_id'][0]->user_type;
    $var['user'] = $_SESSION['user_type'];

    $var['js']      = 'js-history_item_by_karyawan';
    $var['plugin']  = 'plugin_1';
    $var['content'] = 'view-report_karyawan';
    // $var['tb_pengajuan'] = $this->model_transaksi->tb_pengajuan('','tgl_pengajuan');

    $var['user_info'] = $this->model_report->info_user_log_karyawan($by);
    $var['item_data'] = $this->model_report->log_by_kar($by, $_GET['bulan'], $_GET['tahun'], $_GET['group']);

    $var['all_user']  = $this->model_report->all_user();
    $var['all_group'] = $this->db->select('*')->order_by('group_name', 'asc')->get('`group`')->result();

    $var['tahun_cur'] = $_GET['tahun'];
    $var['bulan_cur'] = $_GET['bulan'];
    $var['user_cur']  = $_GET['by'];
    $var['periode']   = $_GET['periode'];
    $var['tanggal']   = $_GET['tanggal'];

    $var['tahun'] = $this->db->query('select year(insert_date) as tahun from log_item group by year(insert_date)')->result();

    $it_name      = [];
    $it_name_null = [];
    $it_value     = [];
    $it_total     = 0;
    $it_sat       = [];

    if (!empty($var['item_data'])) {

      foreach ($var['item_data'] as $key => $val) {
        array_push($it_name, $val->item_name);
        array_push($it_sat, $val->item_satuan);
        array_push($it_name_null, '');
        // array_push($it_name, '');
        array_push($it_value, (int) $val->total);
        $it_total += (int) $val->total;
      }
    }

    $var['item_name']      = json_encode($it_name);
    $var['item_name_null'] = json_encode($it_name_null);
    $var['item_value']     = json_encode($it_value);
    // print_r($var['item_value']);
    $var['item_total'] = count($it_value) > 0 ? max($it_value) : 0;

    $group_name = !empty($var['user_info'][0]->group_name) ? $var['user_info'][0]->group_name : '';

    $data = array('item_name' => $it_name, 'item_value' => $it_value, 'item_total' => $var['item_total'], 'item_name_null' => $it_name_null, 'group_name' => $group_name, 'item_sat' => $it_sat);

    // print_r($_GET);
    if (!empty($api)) {
      if ($api == 'yes') {
        echo json_encode($data);
        // print_r($_GET);
      } else {
        return $var;
      }
    } else {
      $this->load->view('view-index', $var);
    }

  }

  public function formatInd($angka = null)
  {
    return number_format($angka, 0, ",", ".") . ",-";
  }

  public function bulan($bulan = null)
  {
    $b = [
      'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember',
    ];

    if ($bulan <= 0) {
      return $b[(12 + ($bulan)) - 1];
    } else {
      return $b[$bulan - 1];
    }
  }
  public function fifo($func = null, $id_item = null, $month = null, $tahun = null)
  {
    $var = $this->get_fifo($func, $id_item, $month, $tahun);

    echo "<pre>";
    print_r($var);
    echo "</pre>";
    exit;

    if (!empty($_GET['tahunan']) && $_GET['tahunan'] == 'yes' && $func == null) {
      $this->load->model('model_produk');
      $ft['log_tahunan'] = $var['log_tahunan'];
      $ft['ls_item']     = $this->model_produk->tb_item();
      $ft['plugin']      = 'plugin_1';
      $ft['content']     = 'view-tb_fifo_tahunan';
      $ft['js']          = 'js-fifo';
      $ft['s_active']    = 'report_fifo_tahunan';
      $ft['user']        = $_SESSION['user_type'];
      $ft['active_page'] = 'fifo';
      $ft['page_title']  = 'FIFO TOFAP TAHUNAN';
      $ft['mode']        = '';
      $this->load->view('view-tb_fifo_tahunan', $ft);
    } else {
      if ($func == null) {
        $this->load->model('model_produk');
        $var['ls_item']     = $this->model_produk->tb_item();
        $var['plugin']      = 'plugin_1';
        $var['content']     = 'view-tb_fifo_bulanan';
        $var['js']          = 'js-fifo';
        $var['s_active']    = 'report_fifo';
        $var['user']        = $_SESSION['user_type'];
        $var['active_page'] = 'fifo';
        $var['page_title']  = 'FIFO TOFAP BULANAN';
        $var['mode']        = '';
        $this->load->view('view-tb_fifo_bulanan', $var);
      } else {
        if ($func == 'func') {
          // echo "<pre>";
          // print_r($var);
          return $var['log'];
        }

        if ($func == 'json' && $tahun == null) {
          print_r($var['log']);
        }

        if ($func == 'json' && $tahun != null) {
          print_r($log);
        }
      }
    }
  }

  public function get_fifo($func = null, $id_item = null, $month = null, $tahun = null)
  {
    $id = !empty($_GET['item']) ? $_GET['item'] : '';
    $m  = !empty($_GET['bulan']) ? $_GET['bulan'] : '';
    $t  = !empty($_GET['tahun']) ? $_GET['tahun'] : '';

    if ($func == 'func') {
      $id = $id_item;
      $m  = $month;
    }
    if ($func == 'json') {
      $id = $id_item;
      $m  = $month;
    }

    $var = [];

    if ($id != null) {
      $var['item_masuk']  = 0;
      $var['item_keluar'] = 0;

      $this->load->model('model_report');
      $var['it_info'] = $this->db->get_where('pos_item', array('id' => $id))->result();

      $var['log_item'] = $this->model_report->report_log_item($id, $t);

      // echo "<pre>";
      // print_r($t);
      // print_r($var['log_item']);

      $fifo_1 = $this->model_report->fifo_func_1($id);

      $fifo_4 = $this->model_report->fifo_func_hrg_aw($id);

      if (!empty($var['log_item'])) {
        foreach ($var['log_item'] as $key => $val) {
          $var['item_masuk'] += $val->item_masuk;
          $var['item_keluar'] += $val->item_keluar;
        }
      }

      $log = [];
      for ($i = 0; $i < 12; $i++) {
        $log[$i] = array(
          'in'    => array(
            'saldo_akhir' => [],
            'spb'         => [],
            'sub_total'   => []
          ),
          'out'   => array(),
          'bulan' => ''
        );
      }

      $st_aw = $var['it_info'][0]->qty + $var['item_keluar'] - $var['item_masuk'];
      // $stock  = $var['it_info'][0]->qty; // Nggak Dipake ini variabel
      // $masuk  = ($var['item_masuk'] + $st_aw); // Nggak Dipakai juga ini variabel
      // $keluar = $var['item_keluar']; // Nggak dipakai juga ini variabel

      $harga_awal = !empty($fifo_4[0]) ? $fifo_4[0]->harga : 0;
      $harga      = !empty($fifo_1[0]) ? $fifo_1[0]->harga : $harga_awal;

      // echo "Saldo Akhir Januari ".$st_aw."-----".($this->formatInd($harga))."-----".($this->formatInd($st_aw*$harga));

      // echo "<br><br>";
      $jml_qty   = $st_aw;
      $jml_harga = $st_aw * $harga;
      $spb       = [];
      array_push($spb, array('qty' => $st_aw, 'harga' => $harga, 'no_spb' => ''));

      // echo "<pre>";
      for ($d = 1; $d <= 12; $d++) {
        // print_r($spb);
        $log[$d]['bulan'] = $this->bulan($d);
        foreach ($spb as $key => $value) {
          // if($value['qty']>0){
          // echo "Saldo Akhir ".$this->bulan($d-1)."--- ".$value['qty']." --- ".$this->formatInd($value['harga'])." --- ".$this->formatInd($value['qty']*$value['harga'])."<br>";
          if (!empty($log[$d]['in']['saldo_akhir'])) {
            foreach ($log[$d]['in']['saldo_akhir'] as $kp => $vp) {
              if ($value['harga'] == $vp['harga']) {
                $log[$d]['in']['saldo_akhir'][$kp] = array(
                  'bulan' => $this->bulan($d - 1),
                  'qty'   => $vp['qty'] + $value['qty'],
                  'harga' => $value['harga'],
                );
              } else {
                if ($spb[$key]['harga'] == $spb[$key - 1]['harga']) {

                } else {
                  $log[$d]['in']['saldo_akhir'][$key] = array(
                    'bulan' => $this->bulan($d - 1),
                    'qty'   => $value['qty'],
                    'harga' => $value['harga'],
                  );
                }
              }
            }
          } else {
            $log[$d]['in']['saldo_akhir'][$key] = array(
              'bulan' => $this->bulan($d - 1),
              'qty'   => $value['qty'],
              'harga' => $value['harga'],
            );
          }

        }

        $fifo_2 = $this->model_report->fifo_func_2($id, $d);
        $fifo_3 = $this->model_report->fifo_func_3($id, $d);

        if (!empty($fifo_2)) {
          foreach ($fifo_2 as $key => $value) {

            $jml_qty += $value->qty_masuk;
            $jml_harga += ($value->qty_masuk * $value->harga);

            array_push($spb, array('qty' => $value->qty_masuk, 'harga' => $value->harga, 'no_spb' => $value->no_spb));

            $log[$d]['in']['spb'][$key] = array(
              'no_spb' => $value->no_spb,
              'bulan'  => $value->bulan,
              'qty'    => $value->qty_masuk,
              'harga'  => $value->harga,
            );

          }
        }

        $jml_qty_tmp   = 0;
        $jml_harga_tmp = 0;

        foreach ($spb as $key => $value) {
          if ($value['qty'] > 0) {
            $jml_qty_tmp += $value['qty'];
            $jml_harga_tmp += ($value['harga'] * $value['qty']);
          }
        }

        $log[$d]['in']['sub_total'] = array(
          'qty'   => $jml_qty_tmp,
          'harga' => $jml_harga_tmp,
        );

        $f  = $fifo_3[0]->qty_keluar; //28 -3
        $st = 0;

        foreach ($spb as $key => $value) {

          $c = $value['qty']; //25
          $z = $f;
          for ($i = 1; $i <= $z; $i++) {
            if ($spb[$key]['qty'] > 0) {
              $spb[$key]['qty'] -= 1;
              $f -= 1;
            }
          }
          if (($c - $spb[$key]['qty']) > 0) {

            if (!empty($log[$d]['out']['pengambilan'])) {
              foreach ($log[$d]['out']['pengambilan'] as $kp => $vp) {

                if ($value['harga'] == $vp['harga']) {

                  $log[$d]['out']['pengambilan'][$kp] = array(
                    'bulan' => $this->bulan($d),
                    'qty'   => $vp['qty'] + ($c - $spb[$key]['qty']),
                    'harga' => $value['harga'],
                  );
                } else {

                  $log[$d]['out']['pengambilan'][$key] = array(
                    'bulan' => $this->bulan($d),
                    'qty'   => ($c - $spb[$key]['qty']),
                    'harga' => $value['harga'],
                  );
                }
              }
            } else {

              $log[$d]['out']['pengambilan'][$key] = array(
                'bulan' => $this->bulan($d),
                'qty'   => ($c - $spb[$key]['qty']),
                'harga' => $value['harga'],
              );
            }

          } else {
            // $log[$d]['out']['pengambilan'][$key]=array(
            //   'bulan'=>$this->bulan($d),
            //   'qty'=>($c-$spb[$key]['qty']),
            //   'harga'=>$value['harga']
            // );
          }
        }
        // echo "<br>";

        foreach ($spb as $key => $value) {
          // if($value['qty']>0){
          if (!empty($log[$d]['out']['saldo_akhir'])) {
            foreach ($log[$d]['out']['saldo_akhir'] as $kp => $vp) {

              if ($value['harga'] == $vp['harga']) {

                $log[$d]['out']['saldo_akhir'][$kp] = array(
                  'bulan'  => $this->bulan($d),
                  'qty'    => $vp['qty'] + $value['qty'],
                  'harga'  => $value['harga'],
                  'no_spb' => $value['no_spb'],
                );

              } else {
                if ($spb[$key]['harga'] == $spb[$key - 1]['harga']) {

                } else {
                  $log[$d]['out']['saldo_akhir'][$key] = array(
                    'bulan'  => $this->bulan($d),
                    'qty'    => $value['qty'],
                    'harga'  => $value['harga'],
                    'no_spb' => $value['no_spb'],
                  );
                }
              }
            }
          } else {

            $log[$d]['out']['saldo_akhir'][$key] = array(
              'bulan'  => $this->bulan($d),
              'qty'    => $value['qty'],
              'harga'  => $value['harga'],
              'no_spb' => $value['no_spb'],
            );
          }
        }

        // echo "<hr size=10 style='background-color:black;'>";

      }
      // echo "<pre>";
      // print_r($log);

      if (!empty($_GET['tahunan']) && $_GET['tahunan'] == 'yes' && $func == null) {
        // $ft['log_tahunan'] = $log;
        $var['log_tahunan'] = $log;
        // print_r($ft);
      } else {
        if ($func == null) {
          $var['log'] = $log[$m];
        } else {
          $var['log'] = $log[$m];
        }
      }

    }

    echo "<pre>";
    print_r($var['log']);
    return $var;
  }

  public function fifo_sum($month = null, $year = null)
  {
    // echo $month;
    echo "<div id='re'></div>";

    $m = !empty($_GET['bulan']) ? $_GET['bulan'] : $month;
    $y = !empty($_GET['tahun']) ? $_GET['tahun'] : $year;

    if (!empty($m)) {
      if (!empty($y)) {
        $var = $this->get_fifo_sum($m, $y);

        $this->load->view('view-fifo_sum', $var);

      } else {
        echo "Tahun belum diisi<br>";
      }
    } else {
      echo "Bulan belum diisi<br>";
    }
  }

  protected function get_fifo_sum($m, $y)
  {
    // $m = !empty($_GET['bulan']) ? $_GET['bulan'] : $month;
    // $y = !empty($_GET['tahun']) ? $_GET['tahun'] : $year;

    $log         = [];
    $total_saldo = 0;

    $this->load->model('model_produk');
    $items = $this->model_produk->tb_item();

    foreach ($items as $key => $value) {
      $sub_total_akhir = array('qty' => 0, 'total' => 0);
      $res_fifo        = $this->fifo('func', $value->ID_ITEM, $m);

      foreach ($res_fifo['out']['saldo_akhir'] as $key => $val) {
        $sub_total_akhir['qty'] += $val['qty'];
        $sub_total_akhir['total'] += ($val['harga'] * $val['qty']);
      }

      $jumlah_pengambilan = 0;
      $biaya_pengambilan  = 0;
      if (!empty($res_fifo['out']['pengambilan'])) {
        foreach ($res_fifo['out']['pengambilan'] as $kp => $vp) {
          // array_push($qtyz, array('item_name'=>$value->nama_item,'qty'=>$vp['qty'],'harga'=>$vp['harga']));
          $jumlah_pengambilan += $vp['qty'];
          $biaya_pengambilan += ($vp['qty'] * $vp['harga']);
        }
      }

      array_push($log, array(
        'id_item'            => $value->ID_ITEM,
        'nama_item'          => $value->nama_item,
        'jumlah_saldo'       => $sub_total_akhir['qty'],
        'biaya_saldo'        => $sub_total_akhir['total'],
        'jumlah_pengambilan' => $jumlah_pengambilan,
        'biaya_pengambilan'  => $biaya_pengambilan,
      ));
      $total_saldo += $sub_total_akhir['total'];

      // echo str_repeat(" ", 2000);
      // echo "<script>document.getElementById('re').innerHTML = '$value->nama_item';</script>";
      echo "<ul class='item' style='display:none;'>";
      echo "  <li class='name'>$value->nama_item</li>";
      echo "  <li class='jumlah_saldo'>" . $sub_total_akhir['qty'] . "</li>";
      echo "  <li class='biaya_saldo'>" . $sub_total_akhir['total'] . "</li>";
      echo "  <li class='jumlah_pengambilan'>" . $jumlah_pengambilan . "</li>";
      echo "  <li class='biaya_pengambilan'>" . $biaya_pengambilan . "</li>";
      echo "</ul>";

      // flush();
    }

    // echo "<script>document.getElementById('re').innerHTML = 'Load Success.';</script>";
    echo "<p id='total_akhir' style='display:none;'>$total_saldo</p>";

    $var['log']         = $log;
    $var['total_saldo'] = $total_saldo;

    return $var;
  }

  public function fifo_summary()
  {
    // echo "<div id='re'>sfsdf</div>";

    $m = !empty($_GET['bulan']) ? $_GET['bulan'] : 3;
    $y = !empty($_GET['tahun']) ? $_GET['tahun'] : 2018;

    if (!empty($m)) {
      if (!empty($y)) {
        $log         = [];
        $total_saldo = 0;

        $this->load->model('model_produk');
        $items = $this->model_produk->tb_item();

        $qtyz = [];
        foreach ($items as $key => $value) {
          $sub_total_akhir = array('qty' => 0, 'total' => 0);
          $res_fifo        = $this->fifo('func', $value->ID_ITEM, $m);
          // foreach ($res_fifo['out']['pengambilan'] as $keyP => $valueP) {
          //   echo 'Pengambilane ==> '.$valueP['qty'];
          // }
          // $jml_pengambilan=0;
          // if(!empty($res_fifo['out'])){
          // $jml_pengambilan = !empty($res_fifo['out']['pengambilan'])&&!empty($res_fifo['out']['pengambilan'][0]) ? $res_fifo['out']['pengambilan'][0]['qty']:0;
          // $biaya_pengambilan = !empty($res_fifo['out']['pengambilan'])&&!empty($res_fifo['out']['pengambilan'][0]) ? $res_fifo['out']['pengambilan'][0]['harga']*$jml_pengambilan:0;
          // }
          // echo $res_fifo['out']['pengambilan'][0]['qty'];
          // print_r($res_fifo);
          foreach ($res_fifo['out']['saldo_akhir'] as $key => $val) {
            $sub_total_akhir['qty'] += $val['qty'];
            $sub_total_akhir['total'] += ($val['harga'] * $val['qty']);
          }

          $jumlah_pengambilan = 0;
          $biaya_pengambilan  = 0;
          if (!empty($res_fifo['out']['pengambilan'])) {
            foreach ($res_fifo['out']['pengambilan'] as $kp => $vp) {
              // array_push($qtyz, array('item_name'=>$value->nama_item,'qty'=>$vp['qty'],'harga'=>$vp['harga']));
              $jumlah_pengambilan += $vp['qty'];
              $biaya_pengambilan += ($vp['qty'] * $vp['harga']);
            }
          }

          array_push($log, array(
            'id_item'            => $value->ID_ITEM,
            'nama_item'          => $value->nama_item,
            'jumlah_saldo'       => $sub_total_akhir['qty'],
            'biaya_saldo'        => $sub_total_akhir['total'],
            'jumlah_pengambilan' => $jumlah_pengambilan,
            'biaya_pengambilan'  => $biaya_pengambilan,
          ));
          $total_saldo += $sub_total_akhir['total'];

          // echo str_repeat(" ", 2000);
          // echo "<script>document.getElementById('re').innerHTML = '$value->nama_item';</script>";
          flush();

          // echo $value->nama_item.'<br>';
          // print_r($res_fifo);
          // echo "<br>";
          // echo "------------------------------------";
          // echo "<br>";
        }

        // echo "<script>document.getElementById('re').innerHTML = 'Load Success.';</script>";

// print_r($log);
        // print_r($log);
        return $log;

        // $var['total_saldo']=$total_saldo;

      } else {
        echo "Tahun belum diisi<br>";
      }
    } else {
      echo "Bulan belum diisi<br>";
    }
  }

  public function summary()
  {
    $this->load->model('model_produk');
    $items = $this->model_produk->tb_item();

    $var['plugin']      = 'plugin_1';
    $var['content']     = 'view-fifo_sum_head';
    $var['js']          = 'js-fifo_sum';
    $var['s_active']    = 'report_fifo_sum';
    $var['user']        = $_SESSION['user_type'];
    $var['active_page'] = 'fifo_sum';
    $var['page_title']  = 'FIFO SUMMARY';
    $var['mode']        = '';
    // $var['log'] = $this->fifo_summary();
    $var['tahun'] = $this->db->query('select year(insert_date) as tahun from log_item group by year(insert_date)')->result();
    $this->load->view('view-index', $var);
  }

  public function test()
  {

    $var['plugin']      = 'plugin_1';
    $var['content']     = 'view-fifo_sum';
    $var['js']          = 'js-fifo_sum';
    $var['s_active']    = 'report_fifo_sum';
    $var['user']        = $_SESSION['user_type'];
    $var['active_page'] = 'fifo_sum';
    $var['page_title']  = 'FIFO SUMMARY';
    $var['mode']        = '';
    // $var['log'] = $this->fifo_summary();
    $this->load->view('view-index', $var);

  }

  public function tt()
  {
    $this->fifo_sum($_GET['bulan'], $_GET['tahun']);
  }

  public function view_fifo_sum()
  {
    $var['log'] = $this->fifo_summary();
    $this->load->view('view-fifo-s', $var);
  }

  public function reportsla()
  {

    $var['s_active']   = 'report_sla';
    $var['mode']       = '';
    $var['act_button'] = 'report';
    $var['page_title'] = 'LAPORAN';
    $var['user']       = $_SESSION['user_type'];
    $var['js']         = 'js-sla';
    $var['plugin']     = 'plugin_1';
    $var['content']    = 'view-sla';
    $this->load->model('model_report');
    $var['user_sla'] = $this->model_report->user_sla();

    $this->load->view('view-index', $var);
  }

  public function tb_sla()
  {
    $id_user  = $_POST['id_user'];
    $startact = $_POST['startACT'];
    $endact   = $_POST['endACT'];
    $startper = $_POST['startper'];
    $endper   = $_POST['endper'];

    $var = [];
    if ($id_user == null) {
      echo "KOSONG";
    } else {
      $this->load->model('model_report');
      $var['log'] = $this->model_report->sla($id_user, $startact, $endact, $startper, $endper);
      $total_sec  = 0;
      if (!empty($var['log'])) {
        foreach ($var['log'] as $key => $value) {
          $total_sec += $value->sla_sec;
        }
      }

      $var['total_sec']    = $total_sec;
      $hours               = floor($total_sec / 3600);
      $mins                = floor($total_sec / 60 % 60);
      $secs                = floor($total_sec % 60);
      $var['time_service'] = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

      //Average Service
      $avg_time_service        = empty($var['log']) ? 0 : $total_sec / count($var['log']);
      $avg_hours               = floor($avg_time_service / 3600);
      $avg_mins                = floor($avg_time_service / 60 % 60);
      $avg_secs                = floor($avg_time_service % 60);
      $var['avg_time_service'] = sprintf('%02d:%02d:%02d', $avg_hours, $avg_mins, $avg_secs);
    }

    $this->load->view('view-tb_sla', $var);
  }

  public function tembak()
  {
    $query = $this->db->query("select a.id_keranjang from (
        select (
          if(not isnull(pem.order_received),
            concat(
              timestampdiff(day,pem.waiting_approval,pem.order_received),' ',
              convert(concat(
                mod(timestampdiff(hour,pem.waiting_approval,pem.order_received),24),':',
                mod(timestampdiff(minute,pem.waiting_approval,pem.order_received),60),':',
                mod(timestampdiff(second,pem.waiting_approval,pem.order_received),60)
              ),time)
            ),'0 00:00:00'
          )

        ) as D_waiting_approval,
        (
          if(not isnull(pem.order_received),
            if(not isnull(pem.courier_assigned),
                concat(
                  timestampdiff(day,pem.order_received,pem.courier_assigned),'hari ',
                  mod(timestampdiff(hour,pem.order_received,pem.courier_assigned),24),'j:',
                  mod(timestampdiff(minute,pem.order_received,pem.courier_assigned),60),'m:',
                  mod(timestampdiff(second,pem.order_received,pem.courier_assigned),60),'d'
                ),'0 00:00:00'),'0 00:00:00'
          )
        ) as D_order_received,
        (
          if(not isnull(pem.courier_assigned),
            if(not isnull(pem.prepare_item),concat(
              timestampdiff(day,pem.courier_assigned,(pem.prepare_item)),'hari ',
              mod(timestampdiff(hour,pem.courier_assigned,(pem.prepare_item)),24),'j:',
              mod(timestampdiff(minute,pem.courier_assigned,(pem.prepare_item)),60),'m:',
              mod(timestampdiff(second,pem.courier_assigned,(pem.prepare_item)),60),'d'
            ),if(not isnull(pem.courier_on_the_way),concat(
                timestampdiff(day,pem.courier_assigned,(pem.courier_on_the_way)),'hari ',
                mod(timestampdiff(hour,pem.courier_assigned,(pem.courier_on_the_way)),24),'j:',
                mod(timestampdiff(minute,pem.courier_assigned,(pem.courier_on_the_way)),60),'m:',
                mod(timestampdiff(second,pem.courier_assigned,(pem.courier_on_the_way)),60),'d'
              ),concat(
              timestampdiff(day,pem.courier_assigned,(pem.done)),'hari ',
              mod(timestampdiff(hour,pem.courier_assigned,(pem.done)),24),'j:',
              mod(timestampdiff(minute,pem.courier_assigned,(pem.done)),60),'m:',
              mod(timestampdiff(second,pem.courier_assigned,(pem.done)),60),'d'
          ))),'0 00:00:00')

        ) as D_courier_assigned,
        (
          if(not isnull(pem.prepare_item),
            if(not isnull(pem.courier_on_the_way),concat(
              timestampdiff(day,pem.prepare_item,(pem.courier_on_the_way)),'hari ',
              mod(timestampdiff(hour,pem.prepare_item,(pem.courier_on_the_way)),24),'j:',
              mod(timestampdiff(minute,pem.prepare_item,(pem.courier_on_the_way)),60),'m:',
              mod(timestampdiff(second,pem.prepare_item,(pem.courier_on_the_way)),60),'d'
            ),concat(
              timestampdiff(day,pem.prepare_item,(pem.done)),'hari ',
              mod(timestampdiff(hour,pem.prepare_item,(pem.done)),24),'j:',
              mod(timestampdiff(minute,pem.prepare_item,(pem.done)),60),'m:',
              mod(timestampdiff(second,pem.prepare_item,(pem.done)),60),'d'
            )),'0 00:00:00'
          )

        ) as D_prepare_item,
        (

          if(not isnull(pem.courier_on_the_way),
            concat(
              timestampdiff(day,pem.courier_on_the_way,(pem.done)),'hari ',
              mod(timestampdiff(hour,pem.courier_on_the_way,(pem.done)),24),'j:',
              mod(timestampdiff(minute,pem.courier_on_the_way,(pem.done)),60),'m:',
              mod(timestampdiff(second,pem.courier_on_the_way,(pem.done)),60),'d'
            ),'0 00:00:00'
          )


        ) as D_courier_on_the_way,
        (
          select convert(concat(
            timestampdiff(hour,pem.waiting_approval,(pem.done)),':',
            mod(timestampdiff(minute,pem.waiting_approval,(pem.done)),60),':',
            mod(timestampdiff(second,pem.waiting_approval,(pem.done)),60)),time
          )
        ) as D_done,
        (
          select concat(
            timestampdiff(day,pem.waiting_approval,(pem.cancel)),'hari ',
            mod(timestampdiff(hour,pem.waiting_approval,(pem.cancel)),24),'j:',
            mod(timestampdiff(minute,pem.waiting_approval,(pem.cancel)),60),'m:',
            mod(timestampdiff(second,pem.waiting_approval,(pem.cancel)),60),'d'
          )
        ) as D_cancel,

        pem.* from(
select
    p.id as id_keranjang,
    insert_date,
    insert_by,
    1 as `status`,
    'Waiting Approval' as `status_text`,
    no_pemesanan,
    `status` as stat_pem,
    update_date,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=0 limit 1) as `waiting_approval`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=1) as `order_received`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=2) as `courier_assigned`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=3 limit 1) as `prepare_item`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=4 limit 1) as `courier_on_the_way`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=5 limit 1) as `done`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=5 and `message` like '%Rating%' limit 1) as `rating`,
    (select insert_date from log_keranjang where id_keranjang=p.id and `status`=6 limit 1) as `cancel`
from pemesanan as p where p.is_delete=0) as pem) as a where a.D_done>convert('10:00:00',time)");

    $timez = [0, 5, 15, 20, 30, 36];
    foreach ($query->result() as $key => $value) {
      echo $value->id_keranjang;
      for ($i = 1; $i <= 5; $i++) {
        $this->db->query("update
                log_keranjang as l
          set
                l.insert_date=(
                          select
                              date_add(p.tgl_pemesanan, interval " . $timez[$i] . " minute)
                          from
                              pemesanan as p
                          where
                              p.id=l.id_keranjang
                          ) where l.id_keranjang=" . $value->id_keranjang . " and `status`=" . $i);
      }
    }

    echo "Sudah";

  }

  public function fifo_bulanan()
  {
    $this->load->model('model_produk');
    $var['ls_item']     = $this->model_produk->tb_item();
    $var['plugin']      = 'plugin_1';
    $var['content']     = 'view-fifo';
    $var['js']          = 'js-fifo';
    $var['s_active']    = 'report_fifo';
    $var['user']        = $_SESSION['user_type'];
    $var['active_page'] = 'fifo';
    $var['page_title']  = 'FIFO TOFAP BULANAN';
    $var['mode']        = '';
    $var['tahun']       = $this->db->query('select year(insert_date) as tahun from log_item group by year(insert_date)')->result();
    $this->load->view('view-index', $var);
  }

  public function fifo_tahunan()
  {
    $this->load->model('model_produk');
    $var['ls_item']     = $this->model_produk->tb_item();
    $var['plugin']      = 'plugin_1';
    $var['content']     = 'view-fifo';
    $var['js']          = 'js-fifo';
    $var['s_active']    = 'report_fifo_tahunan';
    $var['user']        = $_SESSION['user_type'];
    $var['active_page'] = 'fifo';
    $var['page_title']  = 'FIFO TOFAP TAHUNAN';
    $var['mode']        = '';
    $var['tahun']       = $this->db->query('select year(insert_date) as tahun from log_item group by year(insert_date)')->result();
    $this->load->view('view-index', $var);
  }

  public function sla_penerimaan()
  {

    $var['s_active']   = 'report_sla_penerimaan';
    $var['mode']       = '';
    $var['act_button'] = 'report';
    $var['page_title'] = 'LAPORAN';
    $var['user']       = $_SESSION['user_type'];
    $var['js']         = 'js-sla_penerimaan';
    $var['plugin']     = 'plugin_1';
    $var['content']    = 'view-sla_penerimaan';
    $this->load->model('model_report');

    $this->load->view('view-index', $var);
  }

  public function tb_sla_penerimaan()
  {
    $sla_type = $_POST['sla_type'];
    $startper = $_POST['startper'];
    $endper   = $_POST['endper'];

    $this->load->model('model_report');
    $var['log']       = $this->model_report->getDataSla($startper, $endper, $sla_type);
    $var['jenis_sla'] = $sla_type;

    $total_sec = 0;

    if (!empty($var['log'])) {
      foreach ($var['log'] as $key => $value) {
        $total_sec += $value->sla_sec;
      }

      $var['total_sec']    = $total_sec;
      $hours               = floor($total_sec / 3600);
      $mins                = floor($total_sec / 60 % 60);
      $secs                = floor($total_sec % 60);
      $var['time_service'] = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

      //Average Service
      $avg_time_service        = $total_sec / count($var['log']);
      $avg_hours               = floor($avg_time_service / 3600);
      $avg_mins                = floor($avg_time_service / 60 % 60);
      $avg_secs                = floor($avg_time_service % 60);
      $var['avg_time_service'] = sprintf('%02d:%02d:%02d', $avg_hours, $avg_mins, $avg_secs);
    }

    $this->load->view('view-tb_sla_penerimaan', $var);

  }

  public function history_keranjang()
  {
    $this->load->model('model_produk');
    $this->load->model('model_master_group');
    // $var['ls_item']=$this->model_produk->tb_item();
    $var['plugin']      = 'plugin_1';
    $var['content']     = 'view-report_keranjang';
    $var['js']          = 'js-history_keranjang';
    $var['s_active']    = 'report_keranjang';
    $var['user']        = $_SESSION['user_type'];
    $var['active_page'] = 'fifo';
    $var['page_title']  = 'FIFO TOFAP TAHUNAN';
    $var['mode']        = '';

    $var['ls_group'] = $this->model_master_group->master_group_getData();

    $this->load->view('view-index', $var);
  }

  public function getTbKeranjang()
  {
    $var['status'] = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];

    $tahun  = $_POST['tahun'];
    $group  = !empty($this->input->post('group')) ? 'and g.id=' . $this->input->post('group') : '';
    $rating = $this->input->post('rating');

    $p_rating = null;

    if ($rating == 1) {
      $p_rating = 'where not a.rating is null';
    }

    if ($rating == 0) {
      $p_rating = 'where a.rating is null';
    }

    $this->load->model('model_transaksi');
    $var['tb_keranjang'] = $this->model_transaksi->tb_pemesanan(null, null, null, null, $tahun, 'report', $group, $p_rating);

    $this->load->view('view-tb_history_keranjang', $var);
  }

  public function export_pdf_history_keranjang()
  {
    $objPHPExcel = $this->getPHPExcelHistoryKeranjang();

    $fileName = "History Keranjang.pdf";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_excel_history_keranjang()
  {
    $objPHPExcel = $this->getPHPExcelHistoryKeranjang();

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = "History Keranjang.xlsx";
    force_download($fileName, $excelFileContents);
  }

  public function getPHPExcelHistoryKeranjang()
  {
    $var['status'] = ['Waiting Approval', 'Order Received', 'Courier Assigned', 'Prepare Item', 'Courier On The Way', 'Done', 'Cancel'];
    $tahun         = $this->input->get('tahun', true);
    $group         = !empty($this->input->get('group')) ? 'and g.id=' . $this->input->get('group') : '';
    $rating        = $this->input->get('rating', true);

    $p_rating = null;

    if ($rating == 1) {
      $p_rating = 'where not a.rating is null';
    }

    if ($rating == 0) {
      $p_rating = 'where a.rating is null';
    }

    $this->load->model('model_transaksi');
    $var['tb_keranjang'] = $this->model_transaksi->tb_pemesanan(null, null, null, null, $tahun, 'report', $group, $p_rating);

    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Pengaturan style dari isi tabel
    $style_row = array(
      'alignment' => array(
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      ),
      'borders'   => array(
        'top'    => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'right'  => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'left'   => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
      ),
    );

    $style_title         = $style_row;
    $style_title['font'] = ['bold' => true];

    $objPHPExcel->getActiveSheet()
      ->mergeCells('A1:H1')
      ->mergeCells('A2:H2')
      ->mergeCells('A3:H3')
      ->mergeCells('A4:H4')
      ->mergeCells('A5:H5')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - HISTORY KERANJANG')
      ->setCellValue('A2', 'TAHUN : ' . $tahun)
      ->setCellValue('A3', 'GROUP : ' . $this->input->get('group_name'))
      ->setCellValue('A4', 'STATUS RATING : ' . $this->input->get('rating_name'))
      ->setCellValue('A5', 'TANGGAL : ' . date('Y-m-d H:i:s'))
    ;

    $row = 6;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'NO. PENGAMBILAN')
      ->setCellValue('B' . $row, 'NAMA KARYAWAN')
      ->setCellValue('C' . $row, 'GROUP')
      ->setCellValue('D' . $row, 'LANTAI')
      ->setCellValue('E' . $row, 'TGL. PENGAMBILAN')
    // ->setCellValue('F' . $row, 'KURIR')
      ->setCellValue('F' . $row, 'STATUS')
      ->setCellValue('G' . $row, 'RATING')
      ->setCellValue('H' . $row, 'ULASAN');
    $row++;

    // Set width kolom
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

    foreach ($var['tb_keranjang'] as $key => $value) {
      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_row);

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, $value->no_pemesanan)
        ->setCellValue('B' . $row, $value->pemesan)
        ->setCellValue('C' . $row, $value->group_name)
        ->setCellValue('D' . $row, $value->lantai)
        ->setCellValue('E' . $row, $value->tgl_pemesanan)
      // ->setCellValue('F' . $row, $value->kurir)
        ->setCellValue('F' . $row, $var['status'][$value->status])
        ->setCellValue('G' . $row, $value->rating)
        ->setCellValue('H' . $row, $value->komentar);

      $row++;
    }
    return $objPHPExcel;
  }

  public function export_pdf_pengambilan_barang()
  {
    if ($_GET['periode'] == "Harian") {
      // $title       = 'Laporan Harian Pengambilan Barang.pdf';
      // $objPHPExcel = $this->getDataPengambilanBarangHarian();
      // exit;
      return $this->export_pdf_pengambilan_barang_harian();
    } else {
      $title       = 'Laporan Pengambilan Barang.pdf';
      $objPHPExcel = $this->getPHPExcelPengambilanBarang();
    }
    // $objPHPExcel = $this->getPHPExcelPengambilanBarang();

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $title . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_pdf_pengambilan_barang_harian()
  {
    $val['data']               = $this->getDataPengambilanBarangHarian();
    $val['pemohon_nama']       = $_GET['pemohon_nama'];
    $val['pemohon_jabatan']    = $_GET['pemohon_jabatan'];
    $val['mengetahui_nama']    = $_GET['mengetahui_nama'];
    $val['mengetahui_jabatan'] = $_GET['mengetahui_jabatan'];
    // return $this->load->view('view-print_pengambilan_barang_harian', $val);
    $html = $this->load->view('view-print_pengambilan_barang_harian', $val, true);

    $pdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../assets/temp']);
    $pdf->WriteHTML($html);
    $pdfFilePath = "Laporan Harian Pengambilan Barang__" . date('d-m-Y') . ".pdf";
    $pdf->Output($pdfFilePath, "D");
    exit();

  }

  public function export_excel_pengambilan_barang()
  {
    if ($_GET['periode'] == "Harian") {
      $title       = 'Laporan Harian Pengambilan Barang.xlsx';
      $objPHPExcel = $this->getPHPExcelPengambilanBarangHarian();
      // exit;
    } else {
      $title       = 'Laporan Pengambilan Barang.xlsx';
      $objPHPExcel = $this->getPHPExcelPengambilanBarang();
    }

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = $title;
    force_download($fileName, $excelFileContents);
  }

  public function getDataPengambilanBarangHarian()
  {
    $this->load->model('model_report_new');
    // $data = $this->log_by_karyawan('no');

    $by              = $_GET['by'];
    $filter['bulan'] = !empty($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $filter['tahun'] = !empty($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    $log_item        = $this->model_report_new->get_log_barang_keluar($by, $filter['bulan'], $filter['tahun'], $_GET['group']);

    // Reformat Data
    $result = [];
    foreach ($log_item as $key => $value) {
      $result[$value->id_pemesan]['nama_pemesan']       = $value->nama_pemesan;
      $result[$value->id_pemesan]['department_pemesan'] = $value->department_pemesan;

      $qty  = !empty($result[$value->id_pemesan]['items'][$value->id_item]) ? $result[$value->id_pemesan]['items'][$value->id_item]['qty'] + $value->qty : $value->qty;
      $item = [
        'id_item'   => $value->id_item,
        'item_name' => $value->item_name,
        'qty'       => $qty,
      ];
      $result[$value->id_pemesan]['items'][$value->id_item] = $item;
    }
    // echo "<pre>";
    // print_r($result);
    // print_r($log_item);
    return $result;
  }

  public function getPHPExcelPengambilanBarangHarian()
  {
    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $result = $this->getDataPengambilanBarangHarian();

    $style_title = [
      'font'      => [
        'bold' => true,
      ],
      'alignment' => [
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
      'borders'   => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill'      => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation'   => 90,
        'startColor' => [
          'argb' => 'FF0399D9',
        ],
        'endColor'   => [
          'argb' => 'FFFFFFFF',
        ],
      ],
    ];

    $style_row_border = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(18);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Laporan Harian Pengambilan Barang');
    $objPHPExcel->getActiveSheet()->setCellValue('A2', $_GET['tanggal']);

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Paid');
    $drawing->setDescription('Paid');
    $drawing->setPath('assets/img/logo/tugu.png'); // put your path and image here
    $drawing->setCoordinates('D1');
    $drawing->setOffsetX(910);
    $drawing->setHeight(50);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($objPHPExcel->getActiveSheet());

    $startRow = 4;
    $row      = $startRow;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'No')
      ->setCellValue('B' . $row, 'Nama User')
      ->setCellValue('C' . $row, 'Departement')
      ->setCellValue('D' . $row, 'Nama Barang')
      ->setCellValue('E' . $row, 'Qty')
    ;
    $row++;

    // Set width kolom
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

    $nomor = 1;
    foreach ($result as $k_user => $v_user) {
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $nomor++);
      $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $v_user['nama_pemesan']);
      $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $v_user['department_pemesan']);
      foreach ($v_user['items'] as $k_item => $v_item) {
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $v_item['item_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $v_item['qty']);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_row_border);

        $row++;
      }

      // $row++;
    }

    $row++;

    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($row + 1), 'Pemohon,');
    $objPHPExcel->getActiveSheet()->getStyle('B' . ($row + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($row + 5), $_GET['pemohon_nama']);
    $objPHPExcel->getActiveSheet()->getStyle('B' . ($row + 5))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($row + 6), $_GET['pemohon_jabatan']);
    $objPHPExcel->getActiveSheet()->getStyle('B' . ($row + 6))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($row + 1), 'Mengetahui,');
    $objPHPExcel->getActiveSheet()->getStyle('D' . ($row + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($row + 5), $_GET['mengetahui_nama']);
    $objPHPExcel->getActiveSheet()->getStyle('D' . ($row + 5))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($row + 6), $_GET['mengetahui_jabatan']);
    $objPHPExcel->getActiveSheet()->getStyle('D' . ($row + 6))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    return $objPHPExcel;
  }

  public function getPHPExcelPengambilanBarang()
  {
    $reader      = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
    $spreadsheet = $reader->load('assets/template_laporan_pengambilan_barang.xls');

    $this->load->model('model_report_new');
    // $data = $this->log_by_karyawan('no');

    $by              = $_GET['by'];
    $filter['bulan'] = !empty($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $filter['tahun'] = !empty($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    $log_item        = $this->model_report_new->get_log_barang_keluar($by, $filter['bulan'], $filter['tahun'], $_GET['group']);
    $pos_items       = $this->model_report_new->get_saldo_awal($by, $filter['bulan'], $filter['tahun'], $_GET['group']);

    // REFORMAT LOG ITEM
    $temp_items = [];
    foreach ($log_item as $key => $value) {
      $temp_items[$value->id_item]['data'] = $value;
      $log_date                            = date('d-m-Y', strtotime($value->date));
      if (!empty($temp_items[$value->id_item]['log_keluar'][$log_date])) {
        $temp_items[$value->id_item]['log_keluar'][$log_date] += $value->qty;
      } else {
        $temp_items[$value->id_item]['log_keluar'][$log_date] = $value->qty;
      }
    }

    $items = $temp_items;
    $items = [];

    foreach ($pos_items as $key => $value) {
      $items[$value->id]['data']       = $value;
      $items[$value->id]['log_keluar'] = !empty($temp_items[$value->id]['log_keluar']) ? $temp_items[$value->id]['log_keluar'] : [];
    }

    // echo "<pre>";
    // print_r($items);exit;
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A4', 'TANGGAL ' . date('d-m-Y'));
    $sheet->setCellValue('E5', 'BULAN ' . strtoupper($this->bulan($filter['bulan'])) . ' TAHUN ' . $filter['tahun']);

    $style_row = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    $style_row_border = [
      'borders' => [
        'bottom' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
          'color'       => ['argb' => '00000000'],
        ],
      ],
      'font'    => [
        'size' => 8,
      ],
    ];

    $row = 9;
    foreach ($items as $key => $item) {
      // $row += 1;
      $harga = $item['data']->harga;

      $sisa_bulan_lalu_jml   = $item['data']->qty_awal;
      $sisa_bulan_lalu_harga = $sisa_bulan_lalu_jml * $harga;
      $pembelian_jml         = 0;
      $pembelian_balik       = 0;
      $pembelian_harga       = 0;
      $jml_bulan_ini_jml     = 0;
      $jml_bulan_ini_return  = 0;
      $jml_bulan_ini_harga   = 0;

      $sheet->setCellValue('B' . $row, $item['data']->satuan);
      $sheet->mergeCells('A' . ($row + 1) . ':B' . ($row + 1))->setCellValue('A' . ($row + 1), $item['data']->item_name);
      // Rincian item keluar
      if (!empty($item['log_keluar'])) {
        foreach ($item['log_keluar'] as $k_log_keluar => $v_log_keluar) {
          $jml_bulan_ini_jml += $v_log_keluar;
          $day = date('d', strtotime($k_log_keluar)) * 1;
          if ($day > 15) {
            $day     = $day - 15;
            $row_log = $row + 1;
          } else {
            $row_log = $row;
          }
          $col = 71 + $day;
          $sheet->setCellValue(chr($col) . $row_log, $v_log_keluar);
        }
      }

      $sisa_bulan_ini_jml   = $sisa_bulan_lalu_jml + $pembelian_jml - $jml_bulan_ini_jml;
      $sisa_bulan_ini_harga = $sisa_bulan_ini_jml * $harga;

      $sheet->setCellValue('C' . $row, $sisa_bulan_lalu_jml);
      $sheet->setCellValue('D' . $row, $sisa_bulan_lalu_harga);
      $sheet->setCellValue('E' . $row, $pembelian_jml);
      $sheet->setCellValue('F' . $row, $pembelian_balik);
      $sheet->setCellValue('G' . $row, $pembelian_harga);
      // -------------------------------------------------
      $sheet->setCellValue('X' . $row, $jml_bulan_ini_jml);
      $sheet->setCellValue('Y' . $row, $jml_bulan_ini_return);
      $sheet->setCellValue('Z' . $row, $jml_bulan_ini_harga);
      $sheet->setCellValue('AA' . $row, $sisa_bulan_ini_jml);
      $sheet->setCellValue('AB' . $row, $sisa_bulan_ini_harga);

      $row += 2;
    }

    $sheet->getStyle('A9:AB' . $row)->applyFromArray($style_row);
    $sheet->getStyle('A' . $row . ':AB' . ($row + 16))->applyFromArray($style_row_border);
    $sheet->setCellValue('A' . ($row + 1), ' ');

    $row += 2;

    $sheet->mergeCells('B' . $row . ':E' . $row);
    $sheet->setCellValue('B' . ($row), 'Jakarta, ' . format_tanggal_indonesia(date('Y-m-d')));

    // $sheet->getStyle('A' . $row . ':AB' . ($row + 16))->applyFromArray($style_row_border);

    $sheet->setCellValue('A' . ($row + 2), ' ');
    $sheet->setCellValue('A' . ($row + 3), ' ');
    $sheet->setCellValue('A' . ($row + 4), ' ');

    $sheet->mergeCells('B' . ($row + 1) . ':D' . ($row + 1));
    $sheet->setCellValue('B' . ($row + 1), 'Pelaksana,');
    $sheet->mergeCells('B' . ($row + 5) . ':D' . ($row + 5));
    $sheet->setCellValue('B' . ($row + 5), $_GET['pelaksana_nama']);
    $sheet->mergeCells('B' . ($row + 6) . ':D' . ($row + 6));
    $sheet->setCellValue('B' . ($row + 6), $_GET['pelaksana_jabatan']);

    $sheet->mergeCells('G' . ($row + 1) . ':k' . ($row + 1));
    $sheet->setCellValue('G' . ($row + 1), 'Saksi,');
    $sheet->mergeCells('G' . ($row + 5) . ':k' . ($row + 5));
    $sheet->setCellValue('G' . ($row + 5), $_GET['saksi_nama']);
    $sheet->mergeCells('G' . ($row + 6) . ':k' . ($row + 6));
    $sheet->setCellValue('G' . ($row + 6), $_GET['saksi_jabatan']);

    $row += 7;
    $sheet->setCellValue('A' . ($row++), ' ');

    $sheet->setCellValue('A' . ($row + 2), ' ');
    $sheet->setCellValue('A' . ($row + 3), ' ');
    $sheet->setCellValue('A' . ($row + 4), ' ');

    $sheet->mergeCells('B' . ($row + 1) . ':D' . ($row + 1));
    $sheet->setCellValue('B' . ($row + 1), 'Mengetahui,');
    $sheet->mergeCells('B' . ($row + 5) . ':D' . ($row + 5));
    $sheet->setCellValue('B' . ($row + 5), $_GET['mengetahui_nama']);
    $sheet->mergeCells('B' . ($row + 6) . ':D' . ($row + 6));
    $sheet->setCellValue('B' . ($row + 6), $_GET['mengetahui_jabatan']);

    $sheet->mergeCells('G' . ($row + 5) . ':k' . ($row + 5));
    $sheet->setCellValue('G' . ($row + 5), $_GET['mengetahui_nama_2']);
    $sheet->mergeCells('G' . ($row + 6) . ':k' . ($row + 6));
    $sheet->setCellValue('G' . ($row + 6), $_GET['mengetahui_jabatan_2']);

    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setRight(0.2);
    $sheet->getPageMargins()->setBottom(0.5);
    $sheet->getPageMargins()->setLeft(0.2);

    return $spreadsheet;
  }

  public function export_pdf_report_sla()
  {
    $objPHPExcel = $this->getPHPExcelReportSLA();

    $fileName = "Service Level Pengambilan Report.pdf";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_excel_report_sla()
  {
    $objPHPExcel = $this->getPHPExcelReportSLA();

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = "Service Level Pengambilan Report.xlsx";
    force_download($fileName, $excelFileContents);
  }

  public function getPHPExcelReportSLA()
  {
    $id_user  = $_GET['id_user'];
    $startact = $_GET['startACT'];
    $endact   = $_GET['endACT'];
    $startper = $_GET['startper'];
    $endper   = $_GET['endper'];

    $var = [];
    if ($id_user == null) {
      echo "KOSONG";
    } else {
      $this->load->model('model_report');
      $var['log'] = $this->model_report->sla($id_user, $startact, $endact, $startper, $endper);
      $total_sec  = 0;
      foreach ($var['log'] as $key => $value) {
        $total_sec += $value->sla_sec;
      }

      $var['total_sec']    = $total_sec;
      $hours               = floor($total_sec / 3600);
      $mins                = floor($total_sec / 60 % 60);
      $secs                = floor($total_sec % 60);
      $var['time_service'] = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

      //Average Service
      $avg_time_service        = $total_sec / count($var['log']);
      $avg_hours               = floor($avg_time_service / 3600);
      $avg_mins                = floor($avg_time_service / 60 % 60);
      $avg_secs                = floor($avg_time_service % 60);
      $var['avg_time_service'] = sprintf('%02d:%02d:%02d', $avg_hours, $avg_mins, $avg_secs);
    }

    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Pengaturan style dari isi tabel
    $style_row = array(
      'alignment' => array(
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      ),
      'borders'   => array(
        'top'    => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'right'  => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'left'   => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
      ),
    );

    $style_title = [
      'font'      => [
        'bold' => true,
      ],
      'alignment' => [
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
      'borders'   => [
        'top' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill'      => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation'   => 90,
        'startColor' => [
          'argb' => 'FF0399D9',
        ],
        'endColor'   => [
          'argb' => 'FFFFFFFF',
        ],
      ],
    ];

    $style_row_border = [
      'borders' => [
        'bottom' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    $objPHPExcel->getActiveSheet()
      ->mergeCells('A1:I1')
      ->mergeCells('A2:D2')
      ->mergeCells('A3:D3')
      ->mergeCells('A4:D4')
      ->mergeCells('E2:I2')
      ->mergeCells('E3:I3')
      ->mergeCells('E4:I4')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - Service Level Pengambilan Report')
      ->setCellValue('A2', 'PIC : ' . $_GET['name'])
      ->setCellValue('A3', 'Periode : ' . date('d F Y', strtotime($startper)) . ' to ' . date('d F Y', strtotime($endper)))
      ->setCellValue('A4', 'Service Level : ' . get_status_pemesanan()[$startact] . ' - ' . get_status_pemesanan()[$endact])
      ->setCellValue('E2', 'Total Keranjang : ' . count($var['log']) . ' Order(s)')
      ->setCellValue('E3', 'Service Level : ' . $var['time_service'])
      ->setCellValue('E4', 'Level Average : ' . $var['avg_time_service'])
    ;

    $startRow = 5;
    $row      = $startRow;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':I' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'No')
      ->setCellValue('B' . $row, 'No. Keranjang')
      ->setCellValue('C' . $row, 'Waiting Approval')
      ->setCellValue('D' . $row, 'Order Received')
      ->setCellValue('E' . $row, 'Courier Assigned')
      ->setCellValue('F' . $row, 'Prepare Item')
      ->setCellValue('G' . $row, 'Courier On The Way')
      ->setCellValue('H' . $row, 'Done')
      ->setCellValue('I' . $row, 'Service Level')
    ;
    $row++;

    // Set width kolom
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

    if (!empty($var['log'])) {
      foreach ($var['log'] as $k_log => $vlog) {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, $k_log + 1)
          ->setCellValue('B' . $row, $vlog->no_pemesanan)
          ->setCellValue('C' . $row, format_date_time($vlog->waiting_approval))
          ->setCellValue('D' . $row, format_date_time($vlog->order_received))
          ->setCellValue('E' . $row, format_date_time($vlog->courier_assigned))
          ->setCellValue('F' . $row, format_date_time($vlog->prepare_item))
          ->setCellValue('G' . $row, format_date_time($vlog->courier_on_the_way))
          ->setCellValue('H' . $row, format_date_time($vlog->done))
          ->setCellValue('I' . $row, $vlog->service_level)
        ;

        $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':I' . $row)->applyFromArray($style_row_border);

        $row++;
      }

    }

    $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

    return $objPHPExcel;

  }

  public function export_pdf_report_sla_penerimaan()
  {
    $objPHPExcel = $this->getPHPExcelReportSLAPenerimaan();

    $fileName = "Service Level Penerimaan Report.pdf";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_excel_report_sla_penerimaan()
  {
    $objPHPExcel = $this->getPHPExcelReportSLAPenerimaan();

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = "Service Level Penerimaan Report.xlsx";
    force_download($fileName, $excelFileContents);
  }

  public function getPHPExcelReportSLAPenerimaan()
  {
    $sla_type = $_GET['sla_type'];
    $startper = $_GET['startper'];
    $endper   = $_GET['endper'];

    $this->load->model('model_report');
    $var['log']       = $this->model_report->getDataSla($startper, $endper, $sla_type);
    $var['jenis_sla'] = $sla_type;

    $total_sec = 0;

    if (!empty($var['log'])) {
      foreach ($var['log'] as $key => $value) {
        $total_sec += $value->sla_sec;
      }

      $var['total_sec']    = $total_sec;
      $hours               = floor($total_sec / 3600);
      $mins                = floor($total_sec / 60 % 60);
      $secs                = floor($total_sec % 60);
      $var['time_service'] = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

      //Average Service
      $avg_time_service        = $total_sec / count($var['log']);
      $avg_hours               = floor($avg_time_service / 3600);
      $avg_mins                = floor($avg_time_service / 60 % 60);
      $avg_secs                = floor($avg_time_service % 60);
      $var['avg_time_service'] = sprintf('%02d:%02d:%02d', $avg_hours, $avg_mins, $avg_secs);
    }

    $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Pengaturan style dari isi tabel
    $style_row = array(
      'alignment' => array(
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      ),
      'borders'   => array(
        'top'    => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'right'  => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'bottom' => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
        'left'   => array('style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
      ),
    );

    $style_title = [
      'font'      => [
        'bold' => true,
      ],
      'alignment' => [
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
      'borders'   => [
        'top' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill'      => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation'   => 90,
        'startColor' => [
          'argb' => 'FF0399D9',
        ],
        'endColor'   => [
          'argb' => 'FFFFFFFF',
        ],
      ],
    ];

    $style_row_border = [
      'borders' => [
        'bottom' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ];

    $objPHPExcel->getActiveSheet()
      ->mergeCells('A1:I1')
      ->mergeCells('A2:C2')
      ->mergeCells('A3:C3')
      ->mergeCells('A4:C4')
      ->mergeCells('D2:E2')
      ->mergeCells('D3:E3')
      ->mergeCells('D4:E4')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - Service Level Penerimaan Report')
      ->setCellValue('A2', 'Periode : ' . date('d F Y', strtotime($startper)) . ' to ' . date('d F Y', strtotime($endper)))
      ->setCellValue('A3', 'Service Level : ' . $_GET['service_level'])
      ->setCellValue('A4', 'Tanggal Cetak : ' . date('Y-m-d H:i:s'))
      ->setCellValue('D2', 'Total ' . $sla_type . ' : ' . count($var['log']) . ' Order(s)')
      ->setCellValue('D3', 'Service Level : ' . $var['time_service'])
      ->setCellValue('D4', 'Level Average : ' . $var['avg_time_service'])
    ;

    $startRow = 5;
    $row      = $startRow;

    if ($sla_type == 'permintaan') {
      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_title);
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'No')
        ->setCellValue('B' . $row, 'No. Permintaan')
        ->setCellValue('C' . $row, 'Pending')
        ->setCellValue('D' . $row, 'Accept')
        ->setCellValue('E' . $row, 'Service Level')
      ;

      $row++;

      // Set width kolom
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

      if (!empty($var['log'])) {
        foreach ($var['log'] as $k_log => $vlog) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, $k_log + 1)
            ->setCellValue('B' . $row, $vlog->no_permintaan)
            ->setCellValue('C' . $row, format_date_time($vlog->submit_date))
            ->setCellValue('D' . $row, format_date_time($vlog->approve_date))
            ->setCellValue('E' . $row, $vlog->service_level)
          ;

          $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_row_border);

          $row++;
        }

      }
    } elseif ($sla_type == 'pemesanan') {
      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_title);
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'No')
        ->setCellValue('B' . $row, 'No. Permintaan')
        ->setCellValue('C' . $row, 'Open')
        ->setCellValue('D' . $row, 'Closed')
        ->setCellValue('E' . $row, 'Service Level')
      ;

      $row++;

      // Set width kolom
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

      if (!empty($var['log'])) {
        foreach ($var['log'] as $k_log => $vlog) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, $k_log + 1)
            ->setCellValue('B' . $row, $vlog->no_permintaan)
            ->setCellValue('C' . $row, format_date_time($vlog->approve_date))
            ->setCellValue('D' . $row, format_date_time($vlog->closed_date))
            ->setCellValue('E' . $row, $vlog->service_level)
          ;

          $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_row_border);

          $row++;
        }

      }
    } elseif ($sla_type == 'penerimaan') {
      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_title);
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'No')
        ->setCellValue('B' . $row, 'No. SPB')
        ->setCellValue('C' . $row, 'Open')
        ->setCellValue('D' . $row, 'Closed')
        ->setCellValue('E' . $row, 'Service Level')
      ;

      $row++;

      // Set width kolom
      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

      if (!empty($var['log'])) {
        foreach ($var['log'] as $k_log => $vlog) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, $k_log + 1)
            ->setCellValue('B' . $row, $vlog->no_spb)
            ->setCellValue('C' . $row, format_date_time($vlog->open))
            ->setCellValue('D' . $row, format_date_time($vlog->closed))
            ->setCellValue('E' . $row, $vlog->service_level)
          ;

          $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_row_border);

          $row++;
        }

      }
    }

    $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

    return $objPHPExcel;

  }

}
