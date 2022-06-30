<table width="100%" style="text-align: center; font-family: Impact, Charcoal, sans-serif;">
  <tr>
    <td style="font-size: 14; font-weight: bold;">SURAT PESANAN BARANG</td>
  </tr>
  <tr>
    <td style="font-size: 12; font-weight: bold;">Nomor : <?php echo $no_pengajuan;?></td>
  </tr>
</table>


<!-- <p class="fontTB"></p><br> -->

<pre class="fontTB">
Kepada Yth.
<?php echo !empty($supplier_name) ? $supplier_name:'-' ; ?>
<br>
<?php echo !empty($supplier_address) ? $supplier_address:'-' ; ?>
<br>
<br>
<br>

Up    : <?php echo !empty($supplier_pic_name) ? $supplier_pic_name:'-' ; ?><br>
Telp  : <?php echo !empty($supplier_phone) ? $supplier_phone:'-' ; ?>
<br><br>
Mohon dapat dilaksanakan pesanan kami sebagai berikut:
</pre>

<table width="100%" cellspacing="0" class="tbBarang fontTB" style="border-collapse: separate; border-spacing: 0 0px;">
    <thead >
      <tr style="background-color: #d0d0d0;">
        <th class="thead bhead tlr c"  style="padding-left: 0px" width="30px">No.</th>
        <th class="thead bhead tlr c" >Nama Barang</th>
        <th class="thead bhead tlr c" >Volume</th>
        <th class="thead bhead tlr c" >Satuan</th>
        <th class="thead bhead tlr c"   >Harga Satuan</th>
        <th class="thead bhead tlr c"   >Total Harga</th>
      </tr>
    </thead>

    <tbody>
      <?php $no = 0; ?>
      <?php $s=5; foreach ($items as $key => $val) { ?>
        <?php $s--; ?>

          <tr>
            <td class="tlr r"><?php echo $key+1;?></td>
            <td class="tlr"><?php echo $val->item_name; ?></td>
            <td class="tlr r"><?php echo $val->qty; ?></td>
            <td class="tlr c"><?php echo $val->satuan; ?></td>
            <td class="tlr r">Rp <?php echo number_format($val->harga,0,",",".").',-'; ?></td>
            <td class="tlr r">Rp <?php echo number_format($val->total_harga,0,",",".").',-'; ?></td>
          </tr>
      <?php } ?>

      <?php if($s>0){ ?>
        <tr>
          <td class="tlr">
            <?php for($i=0; $i<=$s; $i++){ ?>
            <br>
            <?php } ?>
          </td>
          <td class="tlr"></td>
          <td class="tlr"></td>
          <td class="tlr"></td>
          <td class="tlr"></td>
          <td class="tlr"></td>
        </tr>
      <?php } ?>
      
      
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5" class="thead bhead tleft" style="text-align: center; font-weight: bold;">TOTAL :</td>
        <td class="thead bhead tright r" style="font-weight: bold;">Rp <?php echo number_format($total_all[0]->total_all,0,",",".").',-'; ?></td>
      </tr>
      <tr>
        <td class="bhead tright tleft" colspan="6"><b>Terbilang : <?php echo $terbilang. ' Rupiah'; ?></b></td>
      </tr>
    </tfoot>
</table>

<div style="width: 100%; height: 300px;">
<pre class="fontTB" style="margin-bottom: -20px;">
Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.
<br>
A.    <b>Waktu Pekerjaan</b>
      <p style="margin-left: 20px; margin-top: 0px; margin-bottom: 0px;">Terhitung mulai tanggal <?php echo $start_per_format_indo; ?> sampai dengan tanggal <?php echo $end_per_format_indo; ?>.</p>
B.    <b>Cara Pembayaran</b>
      <p style="margin-left: 20px; margin-top: 0px;">Pembayaran sebesar 100% (seratus persen), yaitu sebesar Rp <?php echo number_format($total_all[0]->total_all,0,",",".").',-'; ?> setelah barang diterima dengan melampirkan Berita Acara Serah Terima (BAST) dan Invoice.</p>

</pre>
<br>

<table width="100%" style="font-family: arial; font-size: 11px;">
  <tbody>
    <tr>
      <td width="25%" class="c">Jakarta, <?php echo $tgl_spb_indo; ?></td>
      <td width="20%"></td>
      <td width="25%"></td>
    </tr>
    <tr>
      
      <td width="25%" class="c">Pemberi Pekerjaan,<br><br><br><br><br><br><br><b style="text-align: center; border-bottom: 1px solid black;"><?= $data_ttd->nama; ?></b><br><?= $data_ttd->jabatan; ?></td>
      <td width="20%"></td>
      

      <?php if(1+1==4){ ?>
        <?php if($date_ttd>='2018-10-31'){ ?>
            <td width="25%" class="c">Pemberi Pekerjaan,<br><br><br><br><br><br><br><b style="text-align: center; border-bottom: 1px solid black;">Ismawati</b><br>ERM, Legal & Compliance Group Head</td>
                <td width="20%"></td>
        <?php }else{ ?>
            <?php if($date_ttd>='2018-09-26'){ ?>
                <td width="25%" class="c">Pemberi Pekerjaan,<br><br><br><br><br><br><br><b style="text-align: center; border-bottom: 1px solid black;">Indrajaya Busiri</b><br>HRD Group Head</td>
                <td width="20%"></td>
            <?php }else{ ?>
                <td width="25%" class="c">Pemberi Pekerjaan,<br><br><br><br><br><br><br><b style="text-align: center; border-bottom: 1px solid black;">Hedi Hudayana</b><br>HRD Group Head</td>
                <td width="20%"></td>
            <?php } ?>
        <?php } ?>
      <?php } ?>

      <td width="25%" class="c">Penerima Pekerjaan<br><br><br><br><br><br><br><b style="text-align: center; border-bottom: 1px solid black;"><?php echo !empty($supplier_pic_name) ? $supplier_pic_name:'-' ; ?></b><br><p>&nbsp;</p></td>
    </tr>
  </tbody>
</table>
</div>



<style type="text/css">
  .fontTB{
      font-size: 11; text-align: left; font-family: Impact, Charcoal, sans-serif;
  }

  .U{
    list-style-type: upper-alpha;
  }

  @page {
    margin-header: 5mm; /* <any of the usual CSS values for margins> */
    margin-footer: 5mm;
  }
</style>


  <style type="text/css">
    .tbBarang {
      /*border:0; */
      border-collapse:separate; 
      border-spacing:0 0px;
    }

    .tbBarang thead tr th{
      
      border-collapse:separate; 
      border-spacing:0 0px;
      
    } 

    .tbBarang tfoot tr td{
      /*border-top: 1px solid black; */
      border-top: 2px solid black; 
      border-collapse:separate; 
      border-spacing:0 0px;
    }
    .tbBarang thead tr th.thead{
        border-top: 1px solid black;
    }
    .tbBarang thead tr th.bhead{
        border-bottom: 1px solid black;
    }

    .tbBarang tfoot tr td.thead{
        border-top: 1px solid black;
    }
    .tbBarang tfoot tr td.bhead{
        border-bottom: 1px solid black;
    }

    .tlr{
      
      padding-left: 10px;
      border-left: 1px solid black;
      border-right: 1px solid black;
    }

    .l{
      text-align: left;
    }
    .c{
      text-align: center;
    }
    .r{
      text-align: right;
      padding-right: 5px;
    }
    .tleft{
      border-left: 1px solid black;
    }
    .tright{
      border-right: 1px solid black;
    }
/*    .tbBarang tbody tr th{
      border-top: 3px solid black; 
      border-bottom: 3px solid black; 
      border-collapse:collapse; 
      border-spacing:0 0px;
    } */


  </style>