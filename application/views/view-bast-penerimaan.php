  <script >
    var URL = '<?php echo base_url(); ?>';
    var TYPE_PAGE = '<?php echo $act_button; ?>';

  </script>

    <div class="row">
      <div class="col-md-12 ">
        <div id="table-wrapper">
            <center>
              <h2><?php echo $page_title; ?></h2>
              <hr style="border-top: 3px double #8c8b8b;">
            </center>     

            <?php if($mode=='view'){ ?>
              <?php if($stat_user=='Super Admin'||$stat_user=='Admin TOFAP'||$stat_user=='Admin'||$stat_user=='Admin Penerimaan'||$stat_user=='Admin Gudang'||$stat_user=='Admin Pengadaan'){ ?>
                <button type="button" id="addRow" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-bast-penerimaan"><span class="glyphicon glyphicon-plus"></span>
                Tambah BAST
              </button>
                  <br><br> 
              <?php } ?> 
              
                <table id="tb_penerimaan" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                    <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                    <th class="" style="background-color: #4F81BD; color: white;">NO. BAST</th>
                    <th class="" style="background-color: #4F81BD; color: white;">NO. SPB</th>
                    <th class="" style="background-color: #4F81BD; color: white;">NO. PERMINTAAN</th>
                    <th class="" style="background-color: #4F81BD; color: white;">JUDUL PERMINTAAN</th>
                    <!-- <th class="" style="background-color: #4F81BD; color: white;">DIAJUKAN OLEH</th> -->
                    <!-- <th class="" style="background-color: #4F81BD; color: white;">TGL PENERIMAAN</th> -->
                    <!-- <th class="" style="background-color: #4F81BD; color: white;">DITERIMA OLEH</th> -->
                    <!-- <th class="" style="background-color: #4F81BD; color: white;">STATUS PENERIMAAN</th> -->
                    <th class="" style="background-color: #4F81BD; color: white; width: 12%;">ACTION</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php $no = 1; ?>
                      <?php if(!empty($tb_spb)){ ?>
                        <?php foreach ($tb_spb as $key => $val) { ?>
                          <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $val->no_bast; ?></td>
                            <td><?php echo $val->no_spb; ?></td>
                            <td><?php echo $val->no_permintaan; ?></td>
                            <td><?php echo $val->judul; ?></td>
                            <td>
                                <button onclick="sh_penerimaan(<?php echo $val->id; ?>,'<?php echo $val->judul; ?>')" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Lihat Rincian">
                                      <span class="glyphicon glyphicon-fullscreen"></span>
                                </button>
                                <?php if ($val->status_penerimaan == 1) { ?>
                                  <button onclick="get_pdf_bast(<?php echo $val->id; ?>)" class="btn btn-info btn-sm" data-toggle="tooltip" title="BAST"><span class="fa fa-file-pdf-o"></span></button>
                                <?php } ?>
                            </td>
                          </tr>
                        <?php } ?>
                      <?php } ?>
                  </tbody>
                </table>
            <?php } ?>
        </div>
      </div>
    </div>

<div class="modal modal-info fade" id="modal-bast-penerimaan" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">BAST PENERIMAAN</h4>
      </div>
      <form id="form-add-bast-penerimaan" autocomplete="off" onsubmit="return false;">
      <div class="modal-body">
        <div class="row">
            <label for="no_spb" class="col-sm-4 control-label">NO SPB <span class="asterisk">*</span></label>

            <div class="col-sm-8">
              <select class="form-control" name="spb" required="" width="100%">
                <option></option>
              </select>
            </div>
          </div>  
          <br>
         <div class="row">
            <label for="tanggal-bast" class="col-sm-4 control-label">Tanggal BAST <span class="asterisk">*</span></label>

            <div class="col-sm-8">
              <input type="text" class="form-control datepicker" id="tanggal-bast" name="tanggal_bast" required>
            </div>
          </div>  
          <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        <button type="submit" id="u_save" class="btn btn-outline">Simpan BAST</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>