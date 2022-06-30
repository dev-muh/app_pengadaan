<?php
function format_date_time($x = null)
{
  if ($x == null) {
    return '';
  } else {
    $date = new DateTime($x);
    return date_format($date, "d F Y, H:i:s \W\I\B");
  }
}

function get_status_pemesanan()
{
  $act = [
    0 => 'Waiting Approval',
    1 => 'Order Received',
    2 => 'Courier Assigned',
    3 => 'Prepare Item',
    4 => 'Courier On The Way',
    5 => 'Done',
  ];
  return $act;
}


function format_tanggal_indonesia($tanggal)
{
  if (empty($tanggal) || $tanggal == '0000-00-00') {
    return;
  }

  $bulan = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
  );

  // pecah string
  $tanggalan = explode('-', $tanggal);
  return $tanggalan[2] . ' ' . $bulan[$tanggalan[1]] . ' ' . $tanggalan[0];
}