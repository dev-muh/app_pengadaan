<!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/css/bootstrap.min.css"> -->
<table width="100%" style="font-family: arial; font-size: 12px; font-weight: bold;">
    <tr>
      <td style="width: 70%; text-align: left;">
          <b style="font-size: 20px;">Laporan Harian Pengambilan Barang</b><br>
          <b style="font-size: 20px;"><?= $_GET['tanggal'] ?></b><br>
      </td>
      <td style="text-align: center; width: 30%">
          <img width="150px" src="<?php echo base_url('assets/img/logo/tugu.png'); ?>">
            
      </td>
    <tr>
  </table>

  <table width="100%" cellpadding="5" class="tbBarang" style="font-family: arial; font-size: 12px;">
    <thead >

      <tr >
        <th class="thead bhead" style="width: 30px; text-align: center;">No</td>
        <th class="thead bhead" style="padding-left: 15px; text-align: left; " width="40%">Nama User</td>
        <th class="thead bhead" style="text-align: left; ">Departement</td>
        <th class="thead bhead" style="text-align: left; ">Nama Barang</td>
        <th class="thead bhead" style="text-align: center; ">Qty</td>
        
      </tr>

    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($data as $k_pemesan => $v_pemesan) {
        $no_item = 1;
        foreach ($v_pemesan['items'] as $k_item => $v_item) {
          echo "<tr>";
          if ($no_item == 1) {
            echo "<td rowspan='" . count($v_pemesan['items']) . "' style='text-align: center;'>" . $no++ . "</td>";
            echo "<td rowspan='" . count($v_pemesan['items']) . "'>" . $v_pemesan['nama_pemesan'] . "</td>";
            echo "<td rowspan='" . count($v_pemesan['items']) . "'>" . $v_pemesan['department_pemesan'] . "</td>";
          }
          echo "<td>" . $v_item['item_name'] . "</td>";
          echo "<td style='text-align: center;'>" . $v_item['qty'] . "</td>";

          $no_item++;
        }
        echo "</tr>";
      }
      ?>

    </tbody>
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

            ?>
            <!-- hh -->
            Pemohon,
            <br>
            <br>
            <br>
            <br>
            <br>
            <b style="text-decoration: underline;"><?= $pemohon_nama ?></b><br>
            <?= $pemohon_jabatan ?></b></td>
        <td width="5%"></td>
        <td width="20%"></td>
        <td width="20%"></td>
        <td width="25%" style="text-align: center;vertical-align: top;">
          <br>Mengetahui, 
            <br>
            <br>
            <br>
            <br>
            <br>
            <b style="text-decoration: underline; text-align:center;">
              <?= $mengetahui_nama ?>
            </b>
            <br>
              <?= $mengetahui_jabatan ?>
        </td>
      </tr>
    </tbody>
  </table>


  <style type="text/css">
    .tbBarang {
      /*border:0; */
      border-collapse:collapse; 
      border-spacing:0 2px;
    }

    .tbBarang thead tr th{
      
      border-collapse:collapse; 
      border-spacing:0 2px;
      
    } 

    .tbBarang tfoot tr td{
      /*border-top: 1px solid black; */
      border-top: 2px solid black; 
      border-collapse:collapse; 
      border-spacing:0 5px;
    }
    .tbBarang tbody tr td{
      /*border-top: 1px solid black; */
      border: 1px solid black; 
      border-collapse:collapse; 
      border-spacing:0;
    }
    .tbBarang thead tr th.thead{
        border: 1px solid black;
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