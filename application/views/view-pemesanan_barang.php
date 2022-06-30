  <script >
    var URL = '<?php echo base_url(); ?>';
    var TYPE_PAGE = '<?php echo $act_button; ?>';
  </script>

  <div class="row">
    <div class="col-md-12 ">
      <div id="table-wrapper">
          <center >
            <h2><?php echo $page_title; ?></h2>
            <hr style="border-top: 3px double #8c8b8b;">
            
          </center>     

          <?php if($mode=='view'){ ?>
            <?php if($stat_user=='Super Admin'||$stat_user=='Admin TOFAP'||$stat_user=='Admin'||$stat_user=='Admin Penerimaan'||$stat_user=='Admin Gudang'||$stat_user=='Admin Pengadaan'){ ?>
              <?php if($act_button=='pengajuan'){ ?>
                  <a href="<?php echo base_url('transaksi/trx/add'); ?>">
                    <button type="button" class="btn btn-success btn-sm">
                      <span class="glyphicon glyphicon-plus"></span> PERMINTAAN BARANG
                    </button>
                  </a>
                  <br><br> 
              <?php } ?>
            <?php } ?> 
            
              <table id="tb_pemesanan_brg" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                  <?php if($s_active=='penerimaan'){ ?>
                      <th class="" style="background-color: #4F81BD; color: white;">ID PROJECT</th>
                  <?php } ?>
                  <th class="" style="background-color: #4F81BD; color: white;">NO. PERMINTAAN</th>
                  <th class="" style="background-color: #4F81BD; color: white;">JUDUL PERMINTAAN</th>
                  <th class="" style="background-color: #4F81BD; color: white;">TGL PERMINTAAN</th>
                  <th class="" style="background-color: #4F81BD; color: white;">DIAJUKAN OLEH</th>
                  <th class="" style="background-color: #4F81BD; color: white;">TGL PERSETUJUAN</th>
                  <th class="" style="background-color: #4F81BD; color: white;">DISETUJUI OLEH</th>
                  <th class="" style="background-color: #4F81BD; color: white;">STATUS PERMINTAAN</th>
                  <th class="" style="background-color: #4F81BD; color: white;">STATUS SPB</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 10%;">ACTION</th>
                </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if(!empty($tb_pemesanan_brg)){ ?>
                      <?php foreach ($tb_pemesanan_brg as $key => $val) { ?>
                          <?php if($val->status==1){ ?>
                              <tr>
                                  <td><?php echo $no; ?></td>
                                  <td><?php echo $val->no_pengajuan; ?></td>
                                  <td><?php echo $val->judul; ?></td>
                                  <td><?php echo tformat($val->tgl_pengajuan); ?></td>
                                  <td><?php echo $val->submiter; ?></td>
                                  <td><?php echo tformat($val->approve_date); ?></td>
                                  <td><?php echo $val->approval; ?></td>
                                  <td>
                                    <?php if($val->status==0){ ?>
                                        <p class="btn bt-sm btn-warning btn-sm" data-toggle="tooltip" title="Status Permintaan : Menunggu">Pending</p>

                                    <?php }else if($val->status==1){ ?>
                                        <?php if($act_button=='pemesanan_brg'){ ?>
                                            <p class="btn bt-sm btn-primary btn-sm" data-toggle="tooltip" title="Status Permintaan : Diterima">Accept</p>
                                        <?php } ?>
                                    <?php } ?>
                                  </td>
                                  <?php 
                                        $st = $val->stat_spb;
                                        if($st==0){ ?>
                                            <td class="bg-green">
                                                OPEN
                                            </td>
                                  <?php   } ?>
                                  <?php 
                                        $st = $val->stat_spb;
                                        if($st==1){ ?>
                                            <td class="bg-red">
                                                CLOSED
                                            </td>
                                  <?php   } ?>
                                  <td>

                                      <button  type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Lihat Rincian" onclick="sh_pemesanan_brg(<?php echo $val->id; ?>,'<?php echo $val->judul; ?>')">
                                        <span class="glyphicon glyphicon-search"></span>
                                      </button>

                                      <button  type="button" class="btn btn-success btn-sm" data-toggle="tooltip" title="Lihat SPB" onclick="sh_ls_spb(<?php echo $val->id; ?>)">
                                        <span class="glyphicon glyphicon-print"></span>
                                      </button>

                                      <?php if($user!='Admin Gudang' && $user!='Admin Penerimaan'&& $user!='Approval' && $user!='Admin Pengadaan'){ ?>
                                          <a href="<?php echo base_url('transaksi/pemesanan_brg/edit/') . $val->id; ?>?id=pms">
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah">
                                              <span class="glyphicon glyphicon-edit"></span>
                                            </button>
                                          </a>
                                      <?php } ?>
                                  </td>
                              </tr>
                              <?php $no++; ?>
                          <?php } ?>

                      <?php } ?>
                    <?php } ?>
                </tbody>
              </table>
          <?php } ?>
          

          <?php if($mode=='add' || $mode == 'edit'){ ?> 
            <script type="text/javascript">
              var id_pemesanan = <?php echo $ls_it_pms[0]->id; ?>;
              var tmpItem = [];
              var id_permintaan = <?php echo $id; ?>
              // alert(id_permintaan);
            </script>
            <div class="row">
              <div class="col-lg-12 col-xs-12">
                <div class="row">
                  <div class="col-lg-12 col-xs-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading" title="Click to Expand/Collapse" href="#p-1" data-toggle="collapse" style="cursor: pointer;">
                          <h4 class="panel-title" >
                           <b>JUDUL PERMINTAAN : </b><?php echo $ls_it_pms[0]->judul;?>
                          </h4>
                          
                          
                      </div>
                      <div id="p-1" class="panel-collapse collapse in">
                          <div class="panel-body" >
                              <?php 
                                    $this->load->view('view-pms_tb.php');
                              ?>
                          </div>
                      </div>
                    </div>
                  </div>             
                </div>

                <div class="row">
                  <div class="col-lg-12 col-xs-12">
                    <div class="panel panel-primary">
                      <div class="panel-heading" title="Click to Expand/Collapse" href="#p-2" data-toggle="collapse" style="cursor: pointer;">
                          <h4 class="panel-title" >
                           <b>CREATE SPB</b>
                          </h4>
                          
                          
                      </div>
                      <div id="p-2" class="panel-collapse collapse in">
                          <div class="panel-body">
                              <div class="row">
                                  <div class="form-group col-md-3">
                                      <label>Select Item</label>
                                      
                                      <select onchange="get_sup_harga($(this))" class="inp select2 act">
                                          <option disabled="disabled" selected="selected">-- Select Item --</option>
                                          <?php if(!empty($ls_it_pms)){ ?>
                                              <?php foreach ($ls_it_pms as $k => $v_spb) { ?>
                                                <script type="text/javascript">
                                                    tmpItem.push({
                                                        id_it_pn:<?php echo $v_spb->id_it_pn; ?>,
                                                        id_item:<?php echo $v_spb->id_item; ?>,
                                                        qty:<?php echo $v_spb->qty; ?>,
                                                        qty_spb:<?php echo $v_spb->qty_spb; ?>
                                                    });
                                                </script>
                                                <option value='
                                                <?php echo $v_spb->id_it_pn."|".
                                                $v_spb->id_item."|".
                                                $v_spb->item_name."|".
                                                $v_spb->barcode."|".
                                                $v_spb->qty."|".
                                                $v_spb->qty_spb; ?>
                                                '><?php echo $v_spb->item_name; ?> - <?php echo $v_spb->barcode; ?></option>
                                              <?php } ?>
                                          <?php } ?>
                                      </select>
                                  </div>

                                  <div class="form-group col-md-2">
                                    <label>Barcode</label>
                                      <input id="spb_it_barcode" title="Barcode" type="number" name="" placeholder="Input Barcode" class="inp form-control">

                                  </div>

                                  <div class="form-group col-md-2">
                                    <label>Jumlah</label>
                                      <input type="number" name="" placeholder="Input Jumlah" class="inp form-control">
                                  </div>

                                  <div class="form-group col-md-2">
                                      <label>Supplier</label>
                                      <div id="loading_sup" style="display:none; position: fixed; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
                                      <select class="inp select2 act" id="sel_sup" onchange="get_harga($(this))">
                                          <option disabled="disabled" value="" selected="selected">-- Select Supplier --</option>
                                      </select>
                                  </div>

                                  <div class="form-group col-md-2">
                                      <label>Harga</label>
                                      <input id="spb_it_harga" type="text" name="" placeholder="Input Harga" class="inp form-control" disabled="disabled">
                                  </div>

                                  <div class="form-group col-md-1">
                                      <label>Act</label>
                                      <button onclick="add_sup()" class="btn btn-success">
                                          <span class="glyphicon glyphicon-plus"></span>
                                      </button>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-xs-12">
                                    <div clas="table-responsive">
                                      <input type="hidden" name="">
                                      <table id="tb_spb_sup" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                                          <thead>
                                            <tr style="background-color: #4F81BD; color: white;">
                                              <th class="tb-hide"></th>
                                              <th class="tb-hide"></th>
                                              <th>No</th>
                                              <th>Barcode</th>
                                              <th>Nama Barang</th>
                                              <th>Jumlah</th>
                                              <th>Sisa Jml Pemesanan</th>
                                              <th>Supplier</th>
                                              <th>Harga</th>
                                              <th>Total Harga</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                    </div>
                                  </div>
                              </div>
                          </div>
                          <div class="panel-footer">
                            <div class="row">
                              <div class="col-md-12">
                                <?php 
                                      $stat_pem = $ls_it_pms[0]->stat_spb;
                                      if($stat_pem==1){ ?>
                                          <button style="margin-right: 10px;" class="btn btn-danger col-md-2 pull-right" disabled="disabled">CLOSED</button>
                                <?php   } ?>
                                <?php 
                                      $stat_pem = $ls_it_pms[0]->stat_spb;
                                      if($stat_pem==0){ ?>
                                          <button id="create_spb_submit" onclick="before_spb()" style="margin-right: 10px;" class="btn btn-success col-md-2 pull-right">CREATE SPB</button>
                                <?php   } ?>
                                
                                <div id="loading_create" class="col-sm-12 pull-right" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
                                <div class="clearfix"></div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  
                </div>


                <div class="row">
                  <div class="col-lg-12 col-xs-12">
                    <div class="panel panel-success">
                      <div class="panel-heading bg-green" title="Click to Expand/Collapse" href="#p-3" data-toggle="collapse" style="cursor: pointer;">
                          <h4 class="panel-title" >
                           <b>SPB</b>
                          </h4>
                          
                          
                      </div>
                      <div id="p-3" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <table id="tb_ls_spb" class="table table-bordered table-hover dt-responsive">
                              <thead>
                                <tr class="bg-green">
                                  <th style="display: none;"></th>
                                  <th>No.</th>
                                  <th>No. SPB</th>
                                  <th>DIBUAT OLEH</th>
                                  <th>TANGGAL PEMBUATAN SPB</th>
                                  <th>UPDATE BY</th>
                                  <th>ACTION</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if(!empty($ls_spb)){ ?>
                                    <?php foreach ($ls_spb as $key => $value) { ?>
                                      <tr>
                                          <td style="display: none;"><?php echo $value->id; ?></td>
                                          <td><?php echo $key+1; ?></td>
                                          <td><?php echo $value->no_spb; ?></td>
                                          <td><?php echo $value->dibuat_oleh; ?></td>
                                          <td><?php echo $value->insert_date; ?></td>
                                          <td><?php echo $value->diubah_oleh; ?></td>
                                          <td>
                                            <?php $stts = $ls_it_pms[0]->stat_spb; ?>
                                            
                                                <button onclick="sh_spb(<?php echo $value->id; ?>)" type="button" class="l<?php echo $value->id; ?> btn btn-default btn-sm" data-toggle="tooltip" title="Lihat Rincian">
                                                      <span class="glyphicon glyphicon-fullscreen"></span>
                                                </button>
                                            
                                            <?php if($stts==0){ ?>
                                                <?php if($value->status_spb==0){ ?>
                                                    <button type="button" onclick="del_spb(<?php echo $value->id; ?>)" class="l<?php echo $value->id; ?> btn bg-red btn-sm" data-toggle="tooltip" title="Hapus">
                                                      <span class="glyphicon glyphicon-trash"></span>
                                                    </button>
                                                <?php } ?>
                                            <?php } ?>
                                            <a href="<?php echo base_url('transaksi/print_spb/');?><?php echo $value->id; ?>" target="_blank">
                                              <button type="button" class="l<?php echo $value->id; ?> btn bg-green btn-sm" data-toggle="tooltip" title="Cetak">
                                                <span class="glyphicon glyphicon-print"></span>
                                              </button>
                                            </a>
                                            <div id="loading_del" class="col-sm-12" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
                                          </td>
                                          
                                      </tr>
                                <?php } ?>
                                <?php } ?>
                              </tbody>
                            </table>
                        </div>

                        <div class="panel-footer">
                          <div class="row">
                            <div class="col-md-12">
                              <?php 
                                    $stat_pem = $ls_it_pms[0]->stat_spb;
                                    if($stat_pem==1){ ?>
                                        <button style="margin-right: 10px;" class="btn btn-danger col-md-2 pull-right" disabled="disabled">CLOSED</button>
                              <?php   } ?>
                              <?php 
                                    $stat_pem = $ls_it_pms[0]->stat_spb;
                                    if($stat_pem==0){ ?>
                                        <button id="create_spb_submit" onclick="verifikasi_spb()" style="margin-right: 10px;" class="btn btn-success col-md-2 pull-right">Verifikasi</button>
                              <?php   } ?>
                              
                              <div id="loading_create" class="col-sm-12 pull-right" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>

          <?php } ?>
      </div>
    </div>
  </div>







  <!-- Modal -->
<!-- <div id="modal_view_pengajuan" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title judul_pengajuan"></h4>
      </div>
      <div class="modal-body">
          
          <div class="col-md-10">
              <table class="table">
                <tr>
                    <td>Nomor Permintaan</td><td>:</td>
                    <td class="no_pengajuan"></td>
                </tr>
                <tr>
                    <td>Tanggal Permintaan</td><td>:</td>
                    <td class="tgl_pengajuan"></td>
                </tr>
                <tr>
                    <td>Diajukan Oleh</td><td>:</td>
                    <td class="v_lantai"></td>
                </tr>
                <tr>
                    <td>Status Permintaan</td><td>:</td>
                    <td class="v_status"></td>
                </tr>
                <tr>
                    <td>Disetujui Oleh</td><td>:</td>
                    <td class="v_kurir"></td>
                </tr>
                <tr>
                    <td>Tanggal Persetujuan</td><td>:</td>
                    <td class="v_rating"></td>
                </tr>
              </table>
          </div>

          <div class="row verifikasi" style="display: none;">  
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div clas="table-responsive">
                <input type="hidden" id="id_pengajuan_penerimaan" name="">
                <table id="tb_item_pengajuan" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                    <thead>
                      <tr style="background-color: #4F81BD; color: white;">
                        <th>No</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Min. Qty</th>
                        <th>Max. Qty</th>
                        <th>Stock</th>
                        <th>Jumlah</th>
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
</div> -->


<style type="text/css">
    input#bc::-webkit-inner-spin-button,input#bc_item::-webkit-inner-spin-button, 
    input#bc::-webkit-outer-spin-button,input#bc_item::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
    }

    a.no-color:hover{
      font-weight: bold;
      text-decoration: underline;
      color: black;
    }

    a.no-color:focus{
      font-weight: none;
      text-decoration: none;
      color: black;
    }

    .tb-hide{
      display: none;
    }
</style>