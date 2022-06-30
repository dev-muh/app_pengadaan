<?php

//$CI =& get_instance();

//if($CI->uri->segment(2)=='trx' || $CI->uri->segment(2)=='pemesanan_brg' || $CI->uri->segment(2)=='penerimaan_brg'){
//    echo "<center><img src='http://jogja-training.com/wp-content/uploads/2018/01/PELATIHAN-Manajemen-Pemeliharaan-Prediktif-dan-Pencegahan-1.png'></center>";
//    die();
//}

function Terbilang($x)
{
  $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
  if ($x < 12) {
    return " " . $abil[$x];
  } elseif ($x < 20) {
    return Terbilang($x - 10) . " Belas";
  } elseif ($x < 100) {
    return Terbilang($x / 10) . " Puluh" . Terbilang($x % 10);
  } elseif ($x < 200) {
    return " Seratus" . Terbilang($x - 100);
  } elseif ($x < 1000) {
    return Terbilang($x / 100) . " Ratus" . Terbilang($x % 100);
  } elseif ($x < 2000) {
    return " Seribu" . Terbilang($x - 1000);
  } elseif ($x < 1000000) {
    return Terbilang($x / 1000) . " Ribu" . Terbilang($x % 1000);
  } elseif ($x < 1000000000) {
    return Terbilang($x / 1000000) . " Juta" . Terbilang($x % 1000000);
  }

}

function check_00($str)
{
  if (strpos($str, '00') === false) {
    return false;
  } else {
    return true;
  }
}

function check_000($str)
{
  if (strpos($str, '000') === false) {
    return false;
  } else {
    return true;
  }
}

function TerbilangKoma($x)
{
  // echo $x;
  $kalimat = "";
  if ($x == "") {
    $kalimat = "Kosong";
  } else {
    if (strpos($x, ',') === false) {
      // tidak ada koma
      // echo "tidak ada koma";
      $kata    = preg_replace("/[^0-9]/", "", $x);
      $kalimat = Terbilang($kata);
    } else {
      // echo "tidak ada koma 2 ";
      $kata = explode(',', $x);
      // print_r($kata);
      $kata0 = preg_replace("/[^0-9]/", "", $kata[0]);
      $kata1 = $kata[1];
      // echo count($kata);
      if (count($kata) > 1) {
        if ($kata1 == "00") {
          $kalimat = Terbilang($kata0);
        } else {
          $kalimat = Terbilang($kata0) . " Koma " . Terbilang($kata1);
        }
      } else {
        $kalimat = Terbilang($kata0);
      }
    }
  }
  return $kalimat;
}

function number_format_dots($number)
{
  return number_format($number, 0, ',', '.');
}

function number_format_dots_rupiah($number)
{
  return "Rp " . number_format($number, 0, ',', '.');
}

function number_format_dots_decimal($number)
{
  return number_format($number, 2, ',', '.');
}

function number_format_dots_rupiah_decimal($number)
{
  return "Rp " . number_format($number, 2, ',', '.');
}

function rupiah_format_no_flag($angka)
{
  return number_format($angka, 2, ',', '.');
}

function numberConvertToDB($number)
{

}
