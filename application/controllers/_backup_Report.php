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
    $id = !empty($_GET['item']) ? $_GET['item'] : '';
    $m  = !empty($_GET['bulan']) ? $_GET['bulan'] : '';

    if ($func == 'func') {
      $id = $id_item;
      $m  = $month;
    }
    if ($func == 'json') {
      $id = $id_item;
      $m  = $month;
    }

    if ($id != null) {
      $var['item_masuk']  = 0;
      $var['item_keluar'] = 0;

      $this->load->model('model_report');
      $var['it_info'] = $this->db->get_where('pos_item', array('id' => $id))->result();

      $var['log_item'] = $this->model_report->report_log_item($id);

      // echo "<pre>";
      // print_r($var);
      // echo "</pre>";
      // exit;
      $fifo_1 = $this->model_report->fifo_func_1($id);

      $fifo_4 = $this->model_report->fifo_func_hrg_aw($id);

      if (!empty($var['log_item'])) {
        foreach ($var['log_item'] as $key => $val) {
          $var['item_masuk'] += $val->item_masuk;
          $var['item_keluar'] += $val->item_keluar;
        }
      }

      $log = array(
        0  => 'Januari',
        1  => 'Februari',
        2  => 'Maret',
        3  => 'April',
        4  => 'Mei',
        5  => 'Juni',
        6  => 'Juli',
        7  => 'Agustus',
        8  => 'September',
        9  => 'Oktober',
        10 => 'November',
        11 => 'Desember',
      );

      foreach ($log as $key => $value) {
        $log[$key] = array(
          'in'    => array(
            'saldo_akhir' => [],
            'spb'         => [],
            'sub_total'   => []
          ),
          'out'   => array(),
          'bulan' => ''
        );
      }

      // print_r($log);
      // exit;

      $st_aw  = $var['it_info'][0]->qty + $var['item_keluar'] - $var['item_masuk'];
      $stock  = $var['it_info'][0]->qty;
      $masuk  = ($var['item_masuk'] + $st_aw);
      $keluar = $var['item_keluar'];

      $harga_awal = !empty($fifo_4[0]) ? $fifo_4[0]->harga : 0;
      $harga      = !empty($fifo_1[0]) ? $fifo_1[0]->harga : $harga_awal;

      // echo "Saldo Akhir Januari ".$st_aw."-----".($this->formatInd($harga))."-----".($this->formatInd($st_aw*$harga));

      // echo "<br><br>";
      $jml_qty   = $st_aw;
      $jml_harga = $st_aw * $harga;
      $spb       = [];
      array_push($spb, array('qty' => $st_aw, 'harga' => $harga, 'no_spb' => ''));

      for ($d = 1; $d <= 12; $d++) {
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

          // }else{
          // $log[$d]['in']['saldo_akhir'][$key]=array(
          //   'bulan'=>$this->bulan($d-1),
          //   'qty'=>$value['qty'],
          //   'harga'=>$value['harga']
          // );
          // }
        }

        $fifo_2 = $this->model_report->fifo_func_2($id, $d);
        $fifo_3 = $this->model_report->fifo_func_3($id, $d);

        if (!empty($fifo_2)) {
          foreach ($fifo_2 as $key => $value) {

            // echo $value->no_spb." ---- Bulan ".$value->bulan." ---- ".$value->qty." ---- ".$this->formatInd($value->harga)." ---- ".$this->formatInd($value->qty * $value->harga)."<br>";
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

        // echo "<br>Sub Total ---- ".$jml_qty_tmp."----".$this->formatInd($jml_harga_tmp);

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

            // echo "<br>Pengambilan ".$this->bulan($d)." -------".($c-$spb[$key]['qty'])."---".$this->formatInd($value['harga'])."---".(($c-$spb[$key]['qty'])*$value['harga']);
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
          // }else{

          // if(!empty($log[$d]['out']['saldo_akhir'])){
          //   foreach ($log[$d]['out']['saldo_akhir'] as $kp => $vp) {
          //     if($value['harga']==$vp['harga']){
          //       $log[$d]['out']['saldo_akhir'][$kp]=array(
          //         'bulan'=>$this->bulan($d),
          //         'qty'=>$vp['qty']+$value['qty'],
          //         'harga'=>$value['harga']
          //       );
          //     }else{
          //       $log[$d]['out']['saldo_akhir'][$key]=array(
          //         'bulan'=>$this->bulan($d),
          //         'qty'=>$value['qty'],
          //         'harga'=>$value['harga']
          //       );
          //     }
          //   }
          // }else{
          //   $log[$d]['out']['saldo_akhir'][$key]=array(
          //     'bulan'=>$this->bulan($d),
          //     'qty'=>$value['qty'],
          //     'harga'=>$value['harga']
          //   );
          // }
          // }
        }

        // echo "<hr size=10 style='background-color:black;'>";

      }

      if (!empty($_GET['tahunan']) && $_GET['tahunan'] == 'yes' && $func == null) {
        $ft['log_tahunan'] = $log;
      } else {
        if ($func == null) {
          $var['log'] = $log[$m];
        } else {
          $var['log'] = $log[$m];
        }
      }

    }

    if (!empty($_GET['tahunan']) && $_GET['tahunan'] == 'yes' && $func == null) {
      $this->load->model('model_produk');
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

  public function fifo_sum($month = null, $year = null)
  {
    // echo $month;
    echo "<div id='re'></div>";

    $m = !empty($_GET['bulan']) ? $_GET['bulan'] : $month;
    $y = !empty($_GET['tahun']) ? $_GET['tahun'] : $year;

    if (!empty($m)) {
      if (!empty($y)) {
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
          echo "	<li class='name'>$value->nama_item</li>";
          echo "	<li class='jumlah_saldo'>" . $sub_total_akhir['qty'] . "</li>";
          echo "	<li class='biaya_saldo'>" . $sub_total_akhir['total'] . "</li>";
          echo "	<li class='jumlah_pengambilan'>" . $jumlah_pengambilan . "</li>";
          echo "	<li class='biaya_pengambilan'>" . $biaya_pengambilan . "</li>";
          echo "</ul>";

          // flush();
        }

        // echo "<script>document.getElementById('re').innerHTML = 'Load Success.';</script>";
        echo "<p id='total_akhir' style='display:none;'>$total_saldo</p>";

        $var['log']         = $log;
        $var['total_saldo'] = $total_saldo;

        $this->load->view('view-fifo_sum', $var);

      } else {
        echo "Tahun belum diisi<br>";
      }
    } else {
      echo "Bulan belum diisi<br>";
    }
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

    // echo "<pre>";
    // print_r($var);
    // exit;

    // $this->load->library('PHPExcel');

    //Create new PHPExcel object
    // $objPHPExcel = new PHPExcel();
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
      ->mergeCells('A1:I1')
      ->mergeCells('A2:I2')
      ->mergeCells('A3:I3')
      ->mergeCells('A4:I4')
      ->mergeCells('A5:I5')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - HISTORY KERANJANG')
      ->setCellValue('A2', 'TAHUN : ' . $tahun)
      ->setCellValue('A3', 'GROUP : ' . $this->input->get('group_name'))
      ->setCellValue('A4', 'STATUS RATING : ' . $this->input->get('rating_name'))
      ->setCellValue('A5', 'TANGGAL : ' . date('Y-m-d H:i:s'))
    ;

    $row = 6;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':I' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'NO. PENGAMBILAN')
      ->setCellValue('B' . $row, 'NAMA KARYAWAN')
      ->setCellValue('C' . $row, 'GROUP')
      ->setCellValue('D' . $row, 'LANTAI')
      ->setCellValue('E' . $row, 'TGL. PENGAMBILAN')
      ->setCellValue('F' . $row, 'KURIR')
      ->setCellValue('G' . $row, 'STATUS')
      ->setCellValue('H' . $row, 'RATING')
      ->setCellValue('I' . $row, 'ULASAN');
    $row++;

    // Set width kolom
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

    foreach ($var['tb_keranjang'] as $key => $value) {
      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':I' . $row)->applyFromArray($style_row);

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, $value->no_pemesanan)
        ->setCellValue('B' . $row, $value->pemesan)
        ->setCellValue('C' . $row, $value->group_name)
        ->setCellValue('D' . $row, $value->lantai)
        ->setCellValue('E' . $row, $value->tgl_pemesanan)
        ->setCellValue('F' . $row, $value->kurir)
        ->setCellValue('G' . $row, $var['status'][$value->status])
        ->setCellValue('H' . $row, $value->rating)
        ->setCellValue('I' . $row, $value->komentar);

      $row++;
    }
    return $objPHPExcel;
  }

}
