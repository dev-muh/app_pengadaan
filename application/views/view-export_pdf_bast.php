<?php
$html = '';
$html .= '<!DOCTYPE html>';
$html .= '<html>';
$html .= '<head>';
$html .= '</head>';
$html .= '
<style type="text/css">
@media(print)
{
    body {
        background-image: url(' . base_url('/images/tugu_mini.png') . ');
        background-repeat: no-repeat;
        background-position: 650px 40px;
        background-size: 10px;

    }

    h1 {
        font-size: 16px;
        box-shadow: inset 0px 0px 0px 1px rgba(0,0,0,1);
    }

    p {
        margin-bottom: 2px;
    }

    table.table {
        width: 100%;
        font-size: 13px;
        border-collapse: collapse;
    }

    table.table, .table th, .table td {
        padding: 5px;
        border: 1px solid black;
    }

    th {
        box-shadow: inset 0px 0px 0px 1px rgba(0,0,0,1);
        background-color: #B0B0B0;
    }

    tfoot td {
        font-weight: bold;
    }

    .logo {
        float:right;
        height: 100px;
    }
}
</style>
';
$html .= '<body>';

$html .= '<h2 style="text-align: center; margin-bottom: 3px;">BERITA ACARA SERAH TERIMA</h2>';
$html .= '<div style="text-align: center;"><b>Nomor : ' . $spb_info->no_bast . '</b></div>';
$html .= '<br>';
$html .= '<p>SPB No: ' . $spb_info->no_spb . '</p>';

$html .= '<table width="100%" class="table">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th rowspan="2" width="20px;">No.</th>';
$html .= '<th rowspan="2">Deskripsi Barang</th>';
$html .= '<th rowspan="2">Volume</th>';
$html .= '<th rowspan="2">Satuan</th>';
$html .= '<th colspan="2">Harga (Rp)</th>';
$html .= '<th colspan="2">Kondisi</th>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th width="95px">Satuan</th>';
$html .= '<th width="95px">Jumlah</th>';
$html .= '<th>Baik</th>';
$html .= '<th>Rusak</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';
$total_spb = 0;
foreach ($ls_it_spb as $key => $value) {
    $total_spb += $value->total_harga;
    $html .= '<tr>';
    $html .= '<td align="center">' . ($key + 1) . '.</td>';
    $html .= '<td>' . $value->item_name . '.</td>';
    $html .= '<td align="center">' . $value->qty . '</td>';
    $html .= '<td align="center">' . $value->satuan . '</td>';
    $html .= '<td align="right">' . (number_format($value->harga,0,",",".").',-') . '</td>';
    $html .= '<td align="right">' . (number_format($value->total_harga,0,",",".").',-') . '</td>';
    $html .= '<td align="right"></td>';
    $html .= '<td align="right"></td>';
    $html .= '</tr>';
}
$html .= '</tbody>';
$html .= '<tfoot>';
$html .= '<tr>';
$html .= '<td colspan="5" align="right">Total Rp. </td>';
$html .= '<td align="right">' . (number_format($total_spb,0,",",".").',-') . '</td>';
$html .= '<td colspan="2"></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td colspan="8" style="background-color: #B0B0B0;">Terbilang: ' . Terbilang($total_spb) . ' Rupiah</td>';
$html .= '</tr>';
$html .= '</tfoot>';
$html .= '</table>';

$html .= '<p>Jakarta, ' . date('d F Y', strtotime($spb_info->tanggal_bast)) . '</p>';

$html .= '<table width="100%">';

$html .= '<tr>';
$html .= '<td width="40%" style="text-align: center;">';
$html .= 'Diterima Oleh,';
$html .= '<br><br><br><br><br>';
$html .= '<b><u>Saadudin Zuhri</u></b><br>';
$html .= '<i>Staff</i>';
$html .= '</td>';
$html .= '<td width="20%"></td>';
$html .= '<td width="40%" style="text-align: center;">';
$html .= 'Diberikan Oleh,';
$html .= '<br><br><br><br><br>';
$html .= '<b><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></b><br>';
$html .= '<i>Pihak Pengirim</i>';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr><td colspan="3">&nbsp;</td></tr>';

$html .= '<tr>';
$html .= '<td width="40%" style="text-align: center;">';
$html .= 'Diketahui Oleh,';
$html .= '<br><br><br><br><br>';
$html .= '<b><u>Elang M. Haerudin</u></b><br>';
$html .= '<i>Service & Facilities Dept. Head</i>';
$html .= '</td>';
$html .= '<td width="60%" colspan="2"></td>';
$html .= '</tr>';

$html .= '</table>';

$html .= '</body>';
$html .= '</html>';

// echo "$html";
// exit;

$fileName = 'BAST.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/../../assets/temp']);
$mpdf->WriteHTML($html);
$mpdf->Output($fileName, 'D');
