<!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/css/bootstrap.min.css"> -->
<table width="100%" style="font-family: arial; font-size: 12px; font-weight: bold;">
    <tr>
      <td style="width: 30%; text-align: left;">
        
          <img width="150px" src="<?php echo base_url('assets/img/logo/tugu.png'); ?>">
        
      </td>
      <td style="text-align: center; width: 45%">
           
      </td>
      <td style="text-align: center; width: 30%">
            
      </td>
    <tr>
  </table>

  <table width="100%" style="font-family: arial; font-size: 12px; font-weight: bold;">
    <tr>
      <td style="text-align: center; width: 20%"></td>
      
      <td style="text-align: center; width: 60%">
          <b style="font-size: 20px;">PERMINTAAN BARANG</b><br>
          Nomor : <?php echo $nomor; ?>
      </td>
      <td style="text-align: center; width: 20%"></td>  
    </tr>
  </table>
  <br>
<!--   <table width="100%" style="font-family: arial; font-size: 11px;">
    <tr>
      <td width="25%"><b>TANGGAL PERMINTAAN :</b></td>
      <td width="20%"><?php echo $date; ?></td>
      <td width="15%"></td>
      <td width="15%"></td>
      <td width="25%"><b>GUDANG. QRP / request.ap</b></td>
    </tr>
  </table> -->

  <table width="100%" cellpadding="5" class="tbBarang" style="font-family: arial; font-size: 12px;">
    <thead >
      <tr>
        <th class="bhead"></th>
        <th class="bhead"></th>
        <th class="bhead"></th>
        <th class="bhead"></th>
        <th class="bhead"></th>
        
        <th class="bhead"></th>
      </tr>

      <tr >
        <th class="thead bhead" style="width: 15%; text-align: left;">Kode</td>
        <th class="thead bhead" style="padding-left: 15px; text-align: left; " width="40%">Nama Barang</td>
        <th class="thead bhead" style="text-align: left; " width="100px">Min. Stok</td>
        <th class="thead bhead" style="text-align: left; " width="100px">Maks. Stok</td>
        <th class="thead bhead" style="text-align: left; " width="100px">Stok</td>
        <th class="thead bhead" style="text-align: left; " width="100px">Permintaan</td>
        
      </tr>

      <tr>
        <th class="thead"></th>
        <th class="thead"></th>
        <th class="thead"></th>
        <th class="thead"></th>
        <th class="thead"></th>
        
        <th class="thead"></th>
      </tr>
    </thead>
    <tbody >

      <?php foreach ($items as $key => $value) { ?>
        <?php
            if(!empty($items)){
                $date_it = date_create($value->update_it_date);
                $it_date=date_format($date_it,"M Y");
            }
        ?>
        <tr>
          <td style="padding-left: 5px"><?php echo $value->barcode; ?></td>
          <td style="padding-left: 15px"><?php echo $value->item_name; ?></td>
          <td style="padding-left: 5px"><?php echo $value->min_qty; ?></td>
          <td style="padding-left: 5px"><?php echo $value->max_qty; ?></td>
          <td style="padding-left: 5px"><?php echo $value->h_stock; ?></td>
          <td style="padding-left: 5px"><?php echo $value->qty; ?></td>
        </tr>
      <?php } ?>

    </tbody>
    <tfoot>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tfoot>
  </table>

  <table width="100%" style="font-family: arial; font-size: 12px;">
    <tbody>
      <tr>
        <td colspan="5"><br><br><br></td>
      </tr>
      <tr>
        <td width="30%" style="text-align: center;vertical-align: top;">
            <?php 
                $bln = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];


                $tgl_peng = date_create($tgl_pengajuan);

                $it_hr = date_format($tgl_peng,"d");
                $it_bln = $bln[(int)date_format($tgl_peng,"m")];
                $it_th = date_format($tgl_peng,"Y");

                $it_date=$it_hr . ' ' . $it_bln . ' ' . $it_th;
            ?>
            <!-- hh -->
            Jakarta, <?php echo $it_date; ?><br>
            Diajukan oleh,<br>
            <?php if($diajukan_rule=='Admin Gudang'){ ?>            	
              	<?php if(file_exists(APPPATH."../assets/img/ttd/TTD_Admin_Gudang_".$items[0]->submiter.".jpg")){ ?>
                	<img height="100" src="<?php echo base_url('assets/img/ttd/TTD_Admin_Gudang_'.$items[0]->submiter.'.jpg'); ?>">
                <?php }else{ ?>
                	<img height="100" src="<?php echo base_url('assets/img/ttd/WHITE.jpg'); ?>">
                <?php } ?>
            <?php } ?>
            <br><b style="text-decoration: underline;"><?php echo $diajukan_nama; ?></b><br>
            <?php echo $diajukan; ?></b></td>
        <td width="5%"></td>
        <td width="20%"></td>
        <td width="20%"></td>
        <td width="25%" style="text-align: center;vertical-align: top;"><br>Disetujui oleh, <br>
            <?php if($status_permintaan==1){ ?>
            	<?php if(file_exists(APPPATH."../assets/img/ttd/DISETUJUI_".$disetujui_id.".jpg")){ ?>
                	<img height="100" src="<?php echo base_url('assets/img/ttd/DISETUJUI_'.$disetujui_id.'.jpg'); ?>">
                <?php }else{ ?>
                	<img height="100" src="<?php echo base_url('assets/img/ttd/WHITE.jpg'); ?>">
                <?php } ?>
            <?php }else{ ?>
                <br><br><br>
            <?php } ?>
            <br>
            <b style="text-decoration: underline; text-align:center;">
              Elang M. Haerudin
                <?php // echo $disetujui_nama; ?>
            </b>
            <br>
            Service & Facilities Dept. Head
            <?php // echo $disetujui_jabatan; ?>
        </td>
      </tr>
    </tbody>
  </table>


  <style type="text/css">
    .tbBarang {
      /*border:0; */
      border-collapse:separate; 
      border-spacing:0 2px;
    }

    .tbBarang thead tr th{
      
      border-collapse:separate; 
      border-spacing:0 2px;
      
    } 

    .tbBarang tfoot tr td{
      /*border-top: 1px solid black; */
      border-top: 2px solid black; 
      border-collapse:separate; 
      border-spacing:0 5px;
    }
    .tbBarang thead tr th.thead{
        border-top: 1px solid black;
    }
    .tbBarang thead tr th.bhead{
        border-bottom: 1px solid black;
    }
/*    .tbBarang tbody tr th{
      border-top: 3px solid black; 
      border-bottom: 3px solid black; 
      border-collapse:collapse; 
      border-spacing:0 0px;
    } */


  </style>