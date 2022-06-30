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
              <?php } ?> 
              
                <table id="tb_penerimaan" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                  <thead>
                  <tr>
                    <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                    <th class="" style="background-color: #4F81BD; color: white;">NO. SPB</th>
                    <th class="" style="background-color: #4F81BD; color: white;">NO. PERMINTAAN</th>
                    <th class="" style="background-color: #4F81BD; color: white;">JUDUL PERMINTAAN</th>
                    <th class="" style="background-color: #4F81BD; color: white;">DIAJUKAN OLEH</th>
                    <th class="" style="background-color: #4F81BD; color: white;">TGL PENERIMAAN</th>
                    <th class="" style="background-color: #4F81BD; color: white;">DITERIMA OLEH</th>
                    <th class="" style="background-color: #4F81BD; color: white;">STATUS PENERIMAAN</th>
                    <th class="" style="background-color: #4F81BD; color: white; width: 12%;">ACTION</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php $no = 1; ?>
                      <?php if(!empty($tb_spb)){ ?>
                        <?php foreach ($tb_spb as $key => $val) { ?>
                          <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $val->no_spb; ?></td>
                            <td><?php echo $val->no_permintaan; ?></td>
                            <td><?php echo $val->judul; ?></td>
                            <td><?php echo $val->diajukan_oleh; ?></td>
                            <td><?php echo tformat($val->tgl_penerimaan); ?></td>
                            <td><?php echo $val->diterima_oleh; ?></td>
                            <td>
                                <?php if($val->status_penerimaan==0){ ?>
                                    <b style="color:green">OPEN</b>
                                <?php } ?>
                                <?php if($val->status_penerimaan==1){ ?>
                                    <b style="color:red">CLOSED</b>
                                <?php } ?>                                
                            </td>
                            <td>
                                <button onclick="sh_penerimaan(<?php echo $val->id; ?>,'<?php echo $val->judul; ?>')" type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Lihat Rincian">
                                      <span class="glyphicon glyphicon-fullscreen"></span>
                                </button>

                                <?php if($user!='Admin Gudang' && $user!='Approval' && $user!='Admin Pemesanan' && $user!='Admin Pengadaan'){ ?>
                                  <button onclick="sh_penerimaan(<?php echo $val->id; ?>,'<?php echo $val->judul; ?>','edit')" type="button" class="btn bg-yellow btn-sm" data-toggle="tooltip" title="Ubah">
                                    <span class="glyphicon glyphicon-edit"></span>
                                  </button>
                                <?php } ?>
                                <?php if ($val->status_penerimaan == 1) { ?>
                                  <!-- <button onclick="get_pdf_bast(<?php echo $val->id; ?>)" class="btn btn-info btn-sm" data-toggle="tooltip" title="BAST"><span class="fa fa-file-pdf-o"></span></button> -->
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

