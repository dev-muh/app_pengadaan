<?php

defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

class FifoReport extends CI_Controller
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
    $temp_items = $this->model_produk->tb_item();

    $items       = [];
    $arr_id_item = [];

    foreach ($temp_items as $key => $value) {
      $items[$value->ID_ITEM] = $value;
      array_push($arr_id_item, $value->ID_ITEM);
    }

    $bulan      = str_pad($m, 2, '0', STR_PAD_LEFT);
    $start_date = $y . '-' . $bulan . '-01';
    $end_date   = date("Y-m-t", strtotime($start_date));

    $fifos = $this->get_fifo_data($arr_id_item, $start_date, $end_date);

    foreach ($items as $key => $value) {
      $sub_total_akhir = array('qty' => 0, 'total' => 0);
      $res_fifo        = $fifos[$value->ID_ITEM][1];

      // if (empty($fifos[$value->ID_ITEM][1])) {
      //   echo "<pre>";
      //   echo $value->ID_ITEM;
      //   print_r($fifos[$value->ID_ITEM]);
      //   echo "</pre>";
      // }

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
      // echo "<ul class='item' style='display:none;'>";
      // echo "  <li class='name'>$value->nama_item</li>";
      // echo "  <li class='jumlah_saldo'>" . $sub_total_akhir['qty'] . "</li>";
      // echo "  <li class='biaya_saldo'>" . $sub_total_akhir['total'] . "</li>";
      // echo "  <li class='jumlah_pengambilan'>" . $jumlah_pengambilan . "</li>";
      // echo "  <li class='biaya_pengambilan'>" . $biaya_pengambilan . "</li>";
      // echo "</ul>";
    }

    // echo "<pre>";
    // print_r($fifos);
    // exit;
    // echo "<script>document.getElementById('re').innerHTML = 'Load Success.';</script>";
    // echo "<p id='total_akhir' style='display:none;'>$total_saldo</p>";

    $var['log']         = $log;
    $var['total_saldo'] = $total_saldo;

    return $var;
  }

  public function fifo()
  {

    $item_id = $_GET['item'];

    if (empty($item_id)) {
      return;
    }

    if (!empty($_GET['tahunan']) && $_GET['tahunan'] == 'yes' && !empty($_GET['tahun'])) {

      $start_date = $_GET['tahun'] . "-01-01";
      $end_date   = $_GET['tahun'] . "-12-31";
      $var        = $this->get_fifo_data($item_id, $start_date, $end_date);

      $ft['log_tahunan'] = $var[$item_id];
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
      $bulan = !empty($_GET['bulan']) ? $_GET['bulan'] : '';
      $tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';

      $bulan      = str_pad($bulan, 2, '0', STR_PAD_LEFT);
      $start_date = $tahun . '-' . $bulan . '-01';
      $end_date   = date("Y-m-t", strtotime($start_date));
      // echo "$start_date $end_date";
      $fifo       = $this->get_fifo_data($item_id, $start_date, $end_date);
      // echo "<pre>";
      // print_r($fifo);
      // echo "</pre>";
      // exit;
      $var['log'] = $fifo[$item_id][1];

      $this->load->model('model_produk');
      // $var['ls_item']     = $this->model_produk->tb_item();
      $var['plugin']      = 'plugin_1';
      $var['content']     = 'view-tb_fifo_bulanan';
      $var['js']          = 'js-fifo';
      $var['s_active']    = 'report_fifo';
      $var['user']        = $_SESSION['user_type'];
      $var['active_page'] = 'fifo';
      $var['page_title']  = 'FIFO TOFAP BULANAN';
      $var['mode']        = '';
      $this->load->view('view-tb_fifo_bulanan', $var);
    }
  }

  public function get_fifo_data($arr_id_item, $start_date, $end_date)
  {

    $this->load->model('model_report');

    // Saldo awal didatabase tidak dipakai
    // saldo awal dihitung dari saldo akhir + item keluar - item masuk
    $summary_log_item = $this->model_report->summary_log_item($arr_id_item);
    $log_items        = $this->model_report->log_items($arr_id_item, $start_date, $end_date);

    $summary_item_keluar = $this->model_report->summary_item_keluar($arr_id_item, $start_date);
    $log_spb_items       = $this->model_report->log_spb_items($arr_id_item, $start_date);

    $result_saldo_akhir = [];

    $date = new DateTime($start_date);
    $date->modify('-1 month');
    $bulan_sebelumnya = $date->format('Y-m');

    // Dari Saldo Awal
    foreach ($summary_log_item as $key => $value) {
      $result_saldo_akhir[$key][] = [
        'bulan' => $this->bulan($date->format('m')),
        'qty'   => $value->qty_awal,
        'harga' => $value->harga,
      ];
    }

    // Dari SPB
    foreach ($log_spb_items as $kspb => $vspb) {
      foreach ($vspb as $key => $value) {
        if ($result_saldo_akhir[$kspb][(count($result_saldo_akhir[$kspb]) - 1)]['harga'] == $value->harga) {
          $result_saldo_akhir[$kspb][(count($result_saldo_akhir[$kspb]) - 1)]['qty'] += $value->item_masuk;
        } else {
          $result_saldo_akhir[$kspb][] = [
            'bulan' => $this->bulan($date->format('m')),
            'qty'   => $value->item_masuk,
            'harga' => $value->harga,
          ];
        }
      }
    }

    foreach ($summary_item_keluar as $key => $value) {
      $result_saldo_akhir[$key] = $this->takeout_saldo_awal($result_saldo_akhir[$key], $value);
    }

    // echo "<pre>";
    // print_r($arr_id_item);
    // print_r($result_saldo_akhir[199]);
    // exit;

    $result     = [];
    $months     = [];
    $t_date     = new DateTime($start_date);
    $t_end_date = new DateTime($end_date);
    while ($t_date < $t_end_date) {
      $months[] = $t_date->format('Y-m');
      $t_date->modify('+1 month');
    }

    // print_r($months);

    $date = new DateTime($start_date);
    $date->modify('-1 month');
    $bulan_sebelumnya = $date->format('Y-m');

    if (is_array($arr_id_item)) {
      foreach ($arr_id_item as $key => $value) {
        $result[$value] = [
          $bulan_sebelumnya => [
            'out' => [
              'saldo_akhir' => $result_saldo_akhir[$value],
            ],
          ],
        ];
      }
    } else {
      $result[$arr_id_item] = [
        $bulan_sebelumnya => [
          'out' => [
            'saldo_akhir' => $result_saldo_akhir[$arr_id_item],
          ],
        ],
      ];
    }

    if (!empty($log_items)) {
      foreach ($log_items as $k_log_item => $v_log_item) {
        $bulan = date('Y-m', strtotime($v_log_item->insert_date));

        // Set Saldo Akhir bulan sebelumnya
        if (empty($result[$v_log_item->id_item][$bulan]['in']['saldo_akhir'])) {
          $date = new DateTime($v_log_item->insert_date);
          $date->modify('-1 month');
          $bulan_sebelumnya = $date->format('Y-m');

          if (!empty($result[$v_log_item->id_item][$bulan_sebelumnya])) {
            $saldo_akhir_bulan_sebelumnya = $result[$v_log_item->id_item][$bulan_sebelumnya]['out']['saldo_akhir'];
          } else {
            // bulan sebelumnya tidak ditemukan
            // Loop bulan array untuk menemukan bulan terakhir
            foreach ($result[$v_log_item->id_item] as $key => $value) {
              $bulan_terakhir_tercatat      = new DateTime($key . '-01');
              $bulan_terakhir_diminta       = new DateTime($bulan_sebelumnya . '-01');
              $saldo_akhir_bulan_sebelumnya = $value['out']['saldo_akhir'];
            }
            // Isi transaksi bulan kosong
            while ($bulan_terakhir_tercatat < $bulan_terakhir_diminta) {
              $bulan_terakhir_tercatat->modify('+1 month');
              $result[$v_log_item->id_item][$bulan_terakhir_tercatat->format('Y-m')]['bulan']             = $this->bulan($bulan_terakhir_tercatat->format('m'));
              $result[$v_log_item->id_item][$bulan_terakhir_tercatat->format('Y-m')]['in']['saldo_akhir'] = $saldo_akhir_bulan_sebelumnya;

              foreach ($saldo_akhir_bulan_sebelumnya as $key => $value) {
                $saldo_akhir_bulan_sebelumnya[$key]['bulan'] = $this->bulan($bulan_terakhir_tercatat->format('m'));
              }
              $result[$v_log_item->id_item][$bulan_terakhir_tercatat->format('Y-m')]['out']['saldo_akhir'] = $saldo_akhir_bulan_sebelumnya;
            }
          }

          $result[$v_log_item->id_item][$bulan]['bulan']             = $v_log_item->bulan;
          $result[$v_log_item->id_item][$bulan]['in']['saldo_akhir'] = $saldo_akhir_bulan_sebelumnya;

          // Set Saldo Akhir bulan ini sama dengan data masuk
          $result[$v_log_item->id_item][$bulan]['out']['saldo_akhir'] = $saldo_akhir_bulan_sebelumnya;
          foreach ($result[$v_log_item->id_item][$bulan]['out']['saldo_akhir'] as $key => $value) {
            $result[$v_log_item->id_item][$bulan]['out']['saldo_akhir'][$key]['bulan'] = $v_log_item->bulan;
          }
        }

        // Set Sub Total IN
        if (empty($result[$v_log_item->id_item][$bulan]['in']['sub_total'])) {
          $sub_total = [
            'qty'   => 0,
            'harga' => 0,
          ];
          foreach ($result[$v_log_item->id_item][$bulan]['in']['saldo_akhir'] as $k_saldo_akhir => $v_saldo_akhir) {
            $sub_total['qty'] += $v_saldo_akhir['qty'];
            $sub_total['harga'] += ($v_saldo_akhir['qty'] * $v_saldo_akhir['harga']);
          }
          $result[$v_log_item->id_item][$bulan]['in']['sub_total'] = $sub_total;
        }

        if ($v_log_item->parent == "PENERIMAAN") {
          $spb = [
            'no_spb' => $v_log_item->no_spb,
            'bulan'  => $v_log_item->bulan,
            'qty'    => $v_log_item->item_masuk,
            'harga'  => $v_log_item->harga,
          ];
          $result[$v_log_item->id_item][$bulan]['in']['spb'][] = $spb;

          // Tambahkan SPB ke saldo akhir IN
          $result[$v_log_item->id_item][$bulan]['in']['sub_total']['qty'] += $spb['qty'];
          $result[$v_log_item->id_item][$bulan]['in']['sub_total']['harga'] += ($spb['qty'] * $spb['harga']);

          // Tambah SPB ke Saldo Akhir bulan berjalan
          $saldo_akhir = [
            'bulan' => $spb['bulan'],
            'qty'   => $spb['qty'],
            'harga' => $spb['harga'],
          ];
          $result[$v_log_item->id_item][$bulan]['out']['saldo_akhir'][] = $saldo_akhir;
        } else {
          /**
           * Pengambilan barang
           */

          // Get Harga dari saldo akhir bulan berjalan yang belum diambil
          // Kurangi Saldo akhir bulan ini sebanyak item pengambilan
          $arr_pengambilan         = empty($result[$v_log_item->id_item][$bulan]['out']['pengambilan']) ? [] : $result[$v_log_item->id_item][$bulan]['out']['pengambilan'];
          $pengambilan_saldo_akhir = $this->get_pengambilan_saldo_akhir(
            $v_log_item,
            $result[$v_log_item->id_item][$bulan]['out']['saldo_akhir'],
            $arr_pengambilan
          );

          $result[$v_log_item->id_item][$bulan]['out']['saldo_akhir'] = $pengambilan_saldo_akhir['saldo_akhir'];
          $result[$v_log_item->id_item][$bulan]['out']['pengambilan'] = $pengambilan_saldo_akhir['pengambilan'];

        }

      }
    }

    // Isi kekurangan bulan
    foreach ($result as $key => $value) {
      $result[$key] = array_values($value);
      $i            = count($value);
      while ($i <= count($months)) {
        $saldo_akhir     = $result[$key][$i - 1]['out']['saldo_akhir'];
        $out_saldo_akhir = $saldo_akhir;
        $bulan_ini       = $this->bulan($i);
        foreach ($out_saldo_akhir as $k_osa => $v_osa) {
          $out_saldo_akhir[$k_osa]['bulan'] = $bulan_ini;
        }
        $result[$key][$i] = [
          'bulan' => $bulan_ini,
          'in'    => [
            'saldo_akhir' => $saldo_akhir,
          ],
          'out'   => [
            'saldo_akhir' => $out_saldo_akhir,
          ],
        ];

        $i++;
      }
    }

    // echo "<pre>";
    // print_r($result);
    return $result;

  }

  public function export_pdf_fifo_bulanan()
  {
    $objPHPExcel = $this->getPHPExcelFifoBulanan();

    $fileName = "FIFO TOFAP BULANAN.pdf";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_excel_fifo_bulanan()
  {
    $objPHPExcel = $this->getPHPExcelFifoBulanan();

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = "FIFO TOFAP BULANAN.xlsx";
    force_download($fileName, $excelFileContents);
  }

  public function getPHPExcelFifoBulanan()
  {
    $id_item = !empty($_GET['item']) ? $_GET['item'] : '';
    $bulan   = !empty($_GET['bulan']) ? $_GET['bulan'] : '';
    $tahun   = !empty($_GET['tahun']) ? $_GET['tahun'] : '';

    $bulan      = str_pad($bulan, 2, '0', STR_PAD_LEFT);
    $start_date = $tahun . '-' . $bulan . '-01';
    $end_date   = date("Y-m-t", strtotime($start_date));

    $var                = $this->get_fifo_data($id_item, $start_date, $end_date);
    $var['log'] = $var[$id_item][1];

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

    $style_sub_total = [
      'font'    => [
        'bold' => true,
      ],
      'borders' => [
        'top' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill'    => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation'   => 90,
        'startColor' => [
          'argb' => 'FFF5FF29',
        ],
        'endColor'   => [
          'argb' => 'FFFFFFFF',
        ],
      ],
    ];

    $objPHPExcel->getActiveSheet()
      ->mergeCells('A1:H1')
      ->mergeCells('A2:H2')
      ->mergeCells('A3:H3')
      ->mergeCells('A4:H4')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - FIFO TOFAP BULANAN')
      ->setCellValue('A2', 'BULAN : ' . $this->bulan($_GET['bulan']))
      ->setCellValue('A3', 'TAHUN : ' . $_GET['tahun'])
      ->setCellValue('A4', 'TANGGAL : ' . date('Y-m-d H:i:s'))
    ;

    $startRow = 5;
    $row      = $startRow;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'Keterangan')
      ->setCellValue('B' . $row, 'Bulan')
      ->setCellValue('C' . $row, 'Jumlah Saldo')
      ->setCellValue('D' . $row, 'Harga Satuan Saldo')
      ->setCellValue('E' . $row, 'Biaya Saldo')
      ->setCellValue('F' . $row, 'Jumlah Pengambilan')
      ->setCellValue('G' . $row, 'Harga Satuan Pengambilan')
      ->setCellValue('H' . $row, 'Biaya Pengambilan');
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

    if (!empty($var['log'])) {
      foreach ($var['log']['in']['saldo_akhir'] as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, 'Saldo Akhir ' . $value['bulan'])
          ->setCellValue('B' . $row, '')
          ->setCellValue('C' . $row, $value['qty'])
          ->setCellValue('D' . $row, $value['harga'])
          ->setCellValue('E' . $row, $value['qty'] * $value['harga'])
          ->setCellValue('F' . $row, '')
          ->setCellValue('G' . $row, '')
          ->setCellValue('H' . $row, '');

        $row++;
      }
      if (!empty($var['log']['in']['spb'])) {
        foreach ($var['log']['in']['spb'] as $key => $value) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, $value['spb'])
            ->setCellValue('B' . $row, $value['bulan'])
            ->setCellValue('C' . $row, $value['qty'])
            ->setCellValue('D' . $row, $value['harga'])
            ->setCellValue('E' . $row, $value['qty'] * $value['harga'])
            ->setCellValue('F' . $row, '')
            ->setCellValue('G' . $row, '')
            ->setCellValue('H' . $row, '');

          $row++;
        }
      }

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'Sub Total Saldo')
        ->setCellValue('B' . $row, $this->bulan($_GET['bulan']))
        ->setCellValue('C' . $row, !empty($var['log']['in']['sub_total']['qty']) ? $var['log']['in']['sub_total']['qty'] : '')
        ->setCellValue('D' . $row, '')
        ->setCellValue('E' . $row, !empty($var['log']['in']['sub_total']['harga']) ? ($var['log']['in']['sub_total']['harga']) : '')
        ->setCellValue('F' . $row, '')
        ->setCellValue('G' . $row, '')
        ->setCellValue('H' . $row, '');

      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total);

      $row++;

      $sub_total_pengambilan = [
        'qty'   => 0,
        'total' => 0,
      ];

      if (!empty($var['log']['out']['pengambilan'])) {
        foreach ($var['log']['out']['pengambilan'] as $key => $value) {
          $sub_total_pengambilan['qty'] += $value['qty'];
          $sub_total_pengambilan['total'] += ($value['harga'] * $value['qty']);
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'Pengambilan')
            ->setCellValue('B' . $row, $value['bulan'])
            ->setCellValue('C' . $row, '')
            ->setCellValue('D' . $row, '')
            ->setCellValue('E' . $row, '')
            ->setCellValue('F' . $row, $value['qty'])
            ->setCellValue('G' . $row, $value['harga'])
            ->setCellValue('H' . $row, $value['harga'] * $value['qty']);

          $row++;
        }
      }
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'Sub Total Pengambilan')
        ->setCellValue('B' . $row, $this->bulan($_GET['bulan']))
        ->setCellValue('C' . $row, '')
        ->setCellValue('D' . $row, '')
        ->setCellValue('E' . $row, '')
        ->setCellValue('F' . $row, $sub_total_pengambilan['qty'])
        ->setCellValue('G' . $row, '')
        ->setCellValue('H' . $row, $sub_total_pengambilan['total']);

      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total);

      $row++;

      $sub_total_akhir = [
        'qty'   => 0,
        'total' => 0,
      ];
      if (!empty($var['log']['out']['saldo_akhir'])) {
        foreach ($var['log']['out']['saldo_akhir'] as $key => $value) {
          $sub_total_akhir['qty'] += $value['qty'];
          $sub_total_akhir['total'] += ($value['harga'] * $value['qty']);
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'Saldo Akhir ' . $value['bulan'])
            ->setCellValue('B' . $row, $this->bulan($_GET['bulan']))
            ->setCellValue('C' . $row, $value['qty'])
            ->setCellValue('D' . $row, $value['harga'])
            ->setCellValue('E' . $row, $value['harga'] * $value['qty'])
            ->setCellValue('F' . $row, '')
            ->setCellValue('G' . $row, '')
            ->setCellValue('H' . $row, '');

          $row++;
        }
      }

      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $row, 'Sub Total Saldo Akhir')
        ->setCellValue('B' . $row, $this->bulan($_GET['bulan']))
        ->setCellValue('C' . $row, $sub_total_akhir['qty'])
        ->setCellValue('D' . $row, '')
        ->setCellValue('E' . $row, $sub_total_akhir['total'])
        ->setCellValue('F' . $row, '')
        ->setCellValue('G' . $row, '')
        ->setCellValue('H' . $row, '');

      $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total);

      $objPHPExcel->getActiveSheet()->getStyle('C' . ($startRow + 1) . ':H' . $row)
        ->getNumberFormat()
        ->setFormatCode('###,###,###');

    }

    return $objPHPExcel;

  }

  public function export_pdf_fifo_tahunan()
  {
    $objPHPExcel = $this->getPHPExcelFifoTahunan();

    $fileName = "FIFO TOFAP TAHUNAN.pdf";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_excel_fifo_tahunan()
  {
    $objPHPExcel = $this->getPHPExcelFifoTahunan();

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = "FIFO TOFAP TAHUNAN.xlsx";
    force_download($fileName, $excelFileContents);
  }

  public function getPHPExcelFifoTahunan()
  {

    $id_item = !empty($_GET['item']) ? $_GET['item'] : '';
    // $bulan   = !empty($_GET['bulan']) ? $_GET['bulan'] : '';
    $tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : '';

    // $bulan      = str_pad($bulan, 2, '0', STR_PAD_LEFT);
    // $start_date = $tahun . '-' . $bulan . '-01';
    // $end_date   = date("Y-m-t", strtotime($start_date));

    $start_date = $tahun . '-01-01';
    $end_date   = date("Y-m-t", strtotime($tahun . '-12-01'));

    $var                = $this->get_fifo_data($id_item, $start_date, $end_date);
    $var['log_tahunan'] = $var[$id_item];
    // echo "<pre>";
    // print_r($var);
    // echo "</pre>";
    // exit;
    // $this->load->model('model_produk');
    // $var['ls_item'] = $this->model_produk->tb_item();

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

    $style_sub_total = [
      'font'    => [
        'bold' => true,
      ],
      'borders' => [
        'top' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill'    => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation'   => 90,
        'startColor' => [
          'argb' => 'FFF5FF29',
        ],
        'endColor'   => [
          'argb' => 'FFFFFFFF',
        ],
      ],
    ];

    $style_sub_total_sparator                               = $style_sub_total;
    $style_sub_total_sparator['fill']['startColor']['argb'] = '888A8A8A';

    $objPHPExcel->getActiveSheet()
      ->mergeCells('A1:H1')
      ->mergeCells('A2:H2')
      ->mergeCells('A3:H3')
      ->mergeCells('A4:H4')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - FIFO TOFAP TAHUNAN')
      ->setCellValue('A2', 'TAHUN : ')
      ->setCellValue('A3', 'TANGGAL : ' . date('Y-m-d H:i:s'))
    ;

    $startRow = 5;
    $row      = $startRow;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'Keterangan')
      ->setCellValue('B' . $row, 'Bulan')
      ->setCellValue('C' . $row, 'Jumlah Saldo')
      ->setCellValue('D' . $row, 'Harga Satuan Saldo')
      ->setCellValue('E' . $row, 'Biaya Saldo')
      ->setCellValue('F' . $row, 'Jumlah Pengambilan')
      ->setCellValue('G' . $row, 'Harga Satuan Pengambilan')
      ->setCellValue('H' . $row, 'Biaya Pengambilan');
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

    for ($log_k = 1; $log_k <= 12; $log_k++) {
      if (!empty($var['log_tahunan'][$log_k])) {
        foreach ($var['log_tahunan'][$log_k]['in']['saldo_akhir'] as $key => $value) {
          $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'Saldo Akhir ' . $value['bulan'])
            ->setCellValue('B' . $row, '')
            ->setCellValue('C' . $row, $value['qty'])
            ->setCellValue('D' . $row, $value['harga'])
            ->setCellValue('E' . $row, $value['qty'] * $value['harga'])
            ->setCellValue('F' . $row, '')
            ->setCellValue('G' . $row, '')
            ->setCellValue('H' . $row, '');

          $row++;
        }
        if (!empty($var['log_tahunan'][$log_k]['in']['spb'])) {
          foreach ($var['log_tahunan'][$log_k]['in']['spb'] as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A' . $row, $value['no_spb'])
              ->setCellValue('B' . $row, $value['bulan'])
              ->setCellValue('C' . $row, $value['qty'])
              ->setCellValue('D' . $row, $value['harga'])
              ->setCellValue('E' . $row, $value['qty'] * $value['harga'])
              ->setCellValue('F' . $row, '')
              ->setCellValue('G' . $row, '')
              ->setCellValue('H' . $row, '');

            $row++;
          }
        }

        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, 'Sub Total Saldo')
          ->setCellValue('B' . $row, $var['log_tahunan'][$log_k]['bulan'])
          ->setCellValue('C' . $row, !empty($var['log_tahunan'][$log_k]['in']['sub_total']['qty']) ? $var['log_tahunan'][$log_k]['in']['sub_total']['qty'] : '')
          ->setCellValue('D' . $row, '')
          ->setCellValue('E' . $row, !empty($var['log_tahunan'][$log_k]['in']['sub_total']['harga']) ? ($var['log_tahunan'][$log_k]['in']['sub_total']['harga']) : '')
          ->setCellValue('F' . $row, '')
          ->setCellValue('G' . $row, '')
          ->setCellValue('H' . $row, '');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total);

        $row++;

        $sub_total_pengambilan = [
          'qty'   => 0,
          'total' => 0,
        ];

        if (!empty($var['log_tahunan'][$log_k]['out']['pengambilan'])) {
          foreach ($var['log_tahunan'][$log_k]['out']['pengambilan'] as $key => $value) {
            $sub_total_pengambilan['qty'] += $value['qty'];
            $sub_total_pengambilan['total'] += ($value['harga'] * $value['qty']);
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A' . $row, 'Pengambilan')
              ->setCellValue('B' . $row, $value['bulan'])
              ->setCellValue('C' . $row, '')
              ->setCellValue('D' . $row, '')
              ->setCellValue('E' . $row, '')
              ->setCellValue('F' . $row, $value['qty'])
              ->setCellValue('G' . $row, $value['harga'])
              ->setCellValue('H' . $row, $value['harga'] * $value['qty']);

            $row++;
          }
        }
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, 'Sub Total Pengambilan')
          ->setCellValue('B' . $row, $var['log_tahunan'][$log_k]['bulan'])
          ->setCellValue('C' . $row, '')
          ->setCellValue('D' . $row, '')
          ->setCellValue('E' . $row, '')
          ->setCellValue('F' . $row, $sub_total_pengambilan['qty'])
          ->setCellValue('G' . $row, '')
          ->setCellValue('H' . $row, $sub_total_pengambilan['total']);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total);

        $row++;

        $sub_total_akhir = [
          'qty'   => 0,
          'total' => 0,
        ];

        if (!empty($var['log_tahunan'][$log_k]['out']['saldo_akhir'])) {
          foreach ($var['log_tahunan'][$log_k]['out']['saldo_akhir'] as $key => $value) {
            $sub_total_akhir['qty'] += $value['qty'];
            $sub_total_akhir['total'] += ($value['harga'] * $value['qty']);
            $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A' . $row, 'Saldo Akhir ' . $value['bulan'])
              ->setCellValue('B' . $row, $var['log_tahunan'][$log_k]['bulan'])
              ->setCellValue('C' . $row, $value['qty'])
              ->setCellValue('D' . $row, $value['harga'])
              ->setCellValue('E' . $row, $value['harga'] * $value['qty'])
              ->setCellValue('F' . $row, '')
              ->setCellValue('G' . $row, '')
              ->setCellValue('H' . $row, '');

            $row++;
          }
        }

        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, 'Sub Total Saldo Akhir')
          ->setCellValue('B' . $row, $var['log_tahunan'][$log_k]['bulan'])
          ->setCellValue('C' . $row, $sub_total_akhir['qty'])
          ->setCellValue('D' . $row, '')
          ->setCellValue('E' . $row, $sub_total_akhir['total'])
          ->setCellValue('F' . $row, '')
          ->setCellValue('G' . $row, '')
          ->setCellValue('H' . $row, '');

        $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total);
        $row++;

        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, '.');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':H' . $row)->applyFromArray($style_sub_total_sparator);

        $objPHPExcel->getActiveSheet()->getStyle('C' . ($startRow + 1) . ':H' . $row)
          ->getNumberFormat()
          ->setFormatCode('###,###,###');

        $row++;

      }
    }

    return $objPHPExcel;

  }

    public function export_pdf_fifo_summary()
  {
    $objPHPExcel = $this->getPHPExcelFifoSummary();

    $fileName = "FIFO TOFAP SUMMARY.pdf";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Mpdf');
    $writer->save('php://output');
  }

  public function export_excel_fifo_summary()
  {
    $objPHPExcel = $this->getPHPExcelFifoSummary();

    // Load the download helper
    $this->load->helper('download');

    // Save and capture output (into PHP memory)
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
    ob_start();
    $objWriter->save('php://output');
    $excelFileContents = ob_get_clean();

    // Download file contents using CodeIgniter
    $fileName = "FIFO TOFAP SUMMARY.xlsx";
    force_download($fileName, $excelFileContents);
  }

  public function getPHPExcelFifoSummary()
  {
    $m = !empty($_GET['bulan']) ? $_GET['bulan'] : $month;
    $y = !empty($_GET['tahun']) ? $_GET['tahun'] : $year;

    $var = $this->get_fifo_sum($m, $y);

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

    $style_sub_total = [
      'font'    => [
        'bold' => true,
      ],
      'borders' => [
        'top' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill'    => [
        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'rotation'   => 90,
        'startColor' => [
          'argb' => 'FFF5FF29',
        ],
        'endColor'   => [
          'argb' => 'FFFFFFFF',
        ],
      ],
    ];

    $objPHPExcel->getActiveSheet()
      ->mergeCells('A1:E1')
      ->mergeCells('A2:E2')
      ->mergeCells('A3:E3')
      ->mergeCells('A4:E4')
    ;

    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A1', BRAND_PT.' - Fifo Summary')
      ->setCellValue('A2', 'BULAN : ' . $this->bulan($_GET['bulan']))
      ->setCellValue('A3', 'TAHUN : ' . $_GET['tahun'])
      ->setCellValue('A4', 'TANGGAL : ' . date('Y-m-d H:i:s'))
    ;

    $startRow = 5;
    $row      = $startRow;
    $objPHPExcel->getActiveSheet()->getStyle('A' . $row . ':E' . $row)->applyFromArray($style_title);
    $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A' . $row, 'Nama')
      ->setCellValue('B' . $row, 'Jumlah Saldo')
      ->setCellValue('C' . $row, 'Biaya Saldo')
      ->setCellValue('D' . $row, 'Jumlah Pengambilan')
      ->setCellValue('E' . $row, 'Biaya Pengambilan')
      ;
    $row++;

    // Set width kolom
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

    if (!empty($var['log'])) {
      foreach ($var['log'] as $key => $value) {
        $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('A' . $row, $value['nama_item'])
          ->setCellValue('B' . $row, $value['jumlah_saldo'])
          ->setCellValue('C' . $row, $value['biaya_saldo'])
          ->setCellValue('D' . $row, $value['jumlah_pengambilan'])
          ->setCellValue('E' . $row, $value['biaya_pengambilan'])
          ;

        $row++;
      }

    }

    return $objPHPExcel;

  }

  protected function takeout_saldo_awal($saldo_awal, $log_keluar)
  {
    $total_keluar = $log_keluar->total_keluar;
    while ($total_keluar > 0) {
      if ($saldo_awal[0]['qty'] > $total_keluar) {
        $saldo_awal[0]['qty'] -= $total_keluar;
        $total_keluar -= $total_keluar;
      } else {
        $total_keluar -= $saldo_awal[0]['qty'];
        array_shift($saldo_awal);
      }
    }

    return $saldo_awal;
  }

  protected function get_pengambilan_saldo_akhir($v_log_item, $saldo_akhir, $pengambilan)
  {

    if (count($saldo_akhir) == 0) {
      // echo "recursive failure! ";
      $result = [
        'saldo_akhir' => $saldo_akhir,
        'pengambilan' => $pengambilan,
      ];

      return $result;
    }

    // Bila Saldo array pertama lebih besar sama dengan dari item yang keluar langsung kurangi saja
    if ($saldo_akhir[0]['qty'] >= $v_log_item->item_keluar) {
      $saldo_akhir[0]['qty'] -= $v_log_item->item_keluar;

      $harga = $saldo_akhir[0]['harga'];

      // jika Saldo Log pertama habis, takeout dari log saldo akhir
      if ($saldo_akhir[0]['qty'] == 0) {
        array_shift($saldo_akhir);
      }

      $temp_pengambilan = [
        'bulan' => $v_log_item->bulan,
        'qty'   => $v_log_item->item_keluar,
        'harga' => $harga,
      ];
      // Cek Kalau harga sebelumnya sama, pengambilan dijadikan 1 saja
      $jumlah_pengambilan = count($pengambilan);
      if (!empty($pengambilan) && $pengambilan[$jumlah_pengambilan - 1]['harga'] == $temp_pengambilan['harga']) {
        $pengambilan[$jumlah_pengambilan - 1]['qty'] += $temp_pengambilan['qty'];
      } else {
        $pengambilan[] = $temp_pengambilan;
      }

    } else {
      // bila qty diambil melebihi 1 log saldo akhir berjalan
      // Ambil Semua qty dari log pertama

      $temp_pengambilan = [
        'bulan' => $v_log_item->bulan,
        'qty'   => $saldo_akhir[0]['qty'],
        'harga' => $saldo_akhir[0]['harga'],
      ];

      $jumlah_pengambilan = count($pengambilan);
      if (!empty($pengambilan) && $pengambilan[$jumlah_pengambilan - 1]['harga'] == $temp_pengambilan['harga']) {
        $pengambilan[$jumlah_pengambilan - 1]['qty'] += $temp_pengambilan['qty'];
      } else {
        $pengambilan[] = $temp_pengambilan;
      }

      // $pengambilan[] = $temp_pengambilan;

      // Ambil kekurangan dari sisa selanjutnya
      // $kekurangan = $v_log_item->item_keluar - $saldo_akhir[0]['qty'];
      $v_log_item->item_keluar -= $saldo_akhir[0]['qty'];
      // take out log saldo terakhir karena sudah habis
      array_shift($saldo_akhir);

      // Rekursif sampai habis
      $pengambilan_saldo_akhir = $this->get_pengambilan_saldo_akhir($v_log_item, $saldo_akhir, $pengambilan);

      $saldo_akhir = $pengambilan_saldo_akhir['saldo_akhir'];
      $pengambilan = $pengambilan_saldo_akhir['pengambilan'];
    }

    $result = [
      'saldo_akhir' => $saldo_akhir,
      'pengambilan' => $pengambilan,
    ];

    return $result;
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

  /**
   * Mengambil data fifo
   * Reformat dari fungsi lama untuk mengurangi query ke database
   *
   * Fungsi awal mengambil semua data 12 bulan , jadi loop 12 kali
   * Sedangkan untuk sebenarnya tidak perlu sampai di loop 12 kali
   * if (!empty($_GET['tahunan']) && $_GET['tahunan'] == 'yes' && $func == null) {
   * Seharusnya dapat ditentukan dari awal.
   *
   * DAFTAR ISTILAH
   * SPB = Surat Penerimaan Barang
   *
   *
   * @return [type] [description]
   */
  // public function get_fifo_data(){
  //   $id = 182;
  //   $bulan = 3;
  //   $tahun = 2018;

  //   /**
  //    * LOG ITEM
  //    * FUNGSI ASLI => $this->model_report->report_log_item($id, $t); PARAM: id, Tahun
  //    * Mengambil data riwayat item
  //    */

  //   $this->load->model('model_report');
  //   $log_item = $this->model_report->report_log_item($id, $tahun);

  //   /**
  //    * Fifo 1 => Mengambil Harga
  //    * Fungsi ASLI => $this->model_report->fifo_func_1($id); PARAMETER: id
  //    * Harga dambil dari tabel item_spb LIMIT 1
  //    *
  //    * Note:
  //    * kenapa harus dijoin dengan tabel spb dan pos_item?
  //    * Dijoin karena ambil nama_item
  //    * kenapa harus join dengan tabel spb?
  //    * dijoin karena ambil nomor spb dan yang status spb (s.status) dan s (spb) tidak didelete
  //    */

  //   $fifo_1 = $this->model_report->fifo_func_1($id);

  //   /**
  //    * Fifo 2
  //    * FUNGSI ASLI => $this->model_report->fifo_func_2($id, $d); PARAMETER: id , Bulan
  //    *
  //    */

  //   /**
  //    * Fifo 3
  //    * FUNGSI ASLI => $this->model_report->fifo_func_3($id, $d); PARAMETER: id, Bulan
  //    *
  //    */

  //   /**
  //    * Fifo 4 => mengambil (fifo func harga aw) (HARGA AWAL)
  //    * FUNGSI ASLI => $this->model_report->fifo_func_hrg_aw($id); Parameter : id
  //    * harga dari tabel harga yang ambil data pertama (LIMIT 1).
  //    *
  //    * Note:
  //    * suplier bisa banyak, sebenarnya ini data harga yang mana yang dipakai
  //    *
  //    */
  //   $fifo_4 = $this->model_report->fifo_func_hrg_aw($id);

  //   // Harga
  //   // $harga =

  //    echo "<pre>";
  //   print_r($log_item);
  //   print_r($fifo_1);
  //   print_r($fifo_4);

  // }
}
