<?php if(!empty($tb_keranjang)){ ?>

<table id="tb_his" class="table table-bordered table-striped table-hover dt-responsive" style="font-weight: smaller;">
    <thead>
      <tr>
        <th>NO. PENGAMBILAN</th>
        <th>NAMA KARYAWAN</th>
        <th>GROUP</th>
        <th>LANTAI</th>
        <th>TGL. PENGAMBILAN</th>
        <!-- <th>KURIR</th> -->
        <th>STATUS</th>
        <th>RATING</th>
        <th>ULASAN</th>
        <th class="" style="display: none">ID_PEMESANAN</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach ($tb_keranjang as $key => $value) { ?>
            <tr>
              <td><?php echo $value->no_pemesanan; ?></td>
              <td><?php echo $value->pemesan; ?></td>
              <td><?php echo $value->group_name; ?></td>
              <td><?php echo $value->lantai; ?></td>
              <td><?php echo $value->tgl_pemesanan; ?></td>
              <!-- <td><?php echo $value->kurir; ?></td> -->
              <td><?php echo $status[$value->status]; ?></td>
              <td><?php echo $value->rating; ?></td>
              <td><?php echo $value->komentar; ?></td>
              <td style="display: none;"><?php echo $value->id_pemesanan; ?></td>
              <td width="100px">
                  <button data-toggle="tooltip" title="Lihat Rincian" class="btn btn-default btn-sm ps_view" onclick="sh_pemesanan(<?php echo $value->id_pemesanan; ?>)">
                          <span class="glyphicon glyphicon-fullscreen"></span>
                  </button>

                  <?php if(empty($value->rating)){ ?>
                      <button class="csstooltip btn btn-sm bg-black" onclick="rating(<?php echo $value->id_pemesanan; ?>,<?php echo $_SESSION['id_user']; ?>,'','<?php echo !empty($value->no_pemesanan)?$value->no_pemesanan:$no_pemesanan; ?>')">
                        <span class="tooltiptext" style="width: 300%;">Rating</span>
                        <span class="glyphicon glyphicon-star" style="color:yellow"></span>
                      </button>
                  <?php } ?>
              </td>
            </tr>
        <?php $no++; } ?>
    </tbody>
</table>


<div id="modal_view_pemesanan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title judul_pemesanan"></h4>
      </div>
      <div class="modal-body">
          
          <label>Nomor Pemesanan : <i class="no_pemesanan"></i></label>
          <br>
          <label>Tanggal Pemesanan : <i class="tgl_pemesanan"></i></label>
          <br><br>

          <!-- <label>Pemesan </i></label><br> -->
          <div class="col-md-10">
              <table class="table">
                <tr>
                    <td>Nama Pemesan</td><td>:</td>
                    <td class="nm_pemesan"></td>
                </tr>
                <tr>
                    <td>Group</td><td>:</td>
                    <td class="v_group"></td>
                </tr>
                <tr>
                    <td>Lantai</td><td>:</td>
                    <td class="v_lantai"></td>
                </tr>
                <tr>
                    <td>Status</td><td>:</td>
                    <td class="v_status"></td>
                </tr>
                <tr>
                    <td>Nama Kurir</td><td>:</td>
                    <td class="v_kurir"></td>
                </tr>
                <tr>
                    <td>Rating</td><td>:</td>
                    <td class="v_rating"></td>
                </tr>
                <tr>
                    <td>Komentar</td><td>:</td>
                    <td class="v_komentar"></td>
                </tr>

              </table>
          </div>

          <div class="row verifikasi" style="display: none;">  
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div clas="table-responsive">
                <input type="hidden" id="id_pemesanan_lg" name="">
                <table id="tb_item_pemesanan" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                    <thead>
                      <tr style="background-color: #4F81BD; color: white;">
                        <th>No</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Stock</th>
                        <th>Jumlah</th>
                        <th>Note</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<script type="text/javascript">
  $(function(){
      $('#tb_his').DataTable({
      "pageLength": 100,
      // "ordering": false 
      "order": [[ 9, 'desc' ]]
    });
  });

  
</script>

<?php }else{ ?>
    <center><h5>Data kosong</h5></center>
<?php } ?>




