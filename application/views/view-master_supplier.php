    <script type="text/javascript">
      var URL = "<?php echo base_url(); ?>";
    </script>
    <!-- Content Header (Page header) -->
    <input type="hidden" id="BASE_URL" value="<?php echo base_url(); ?>">
    <!-- <input type="hidden" id="type_pos" value="<?php //echo $type; ?>"> -->
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <div id="table-wrapper">
              <center>
                <h2>SET UP <?php echo $page_title; ?></h2>
                <hr style="border-top: 3px double #8c8b8b;">
                <?php //$this->load->view('tpl_form_message'); ?>
              </center>

              <br>
              <!-- <a href="<?php //echo base_url('kategori/add'); ?>" class="btn btn-info pull-right"><i class="fa fa-plus"></i> ADD KATEGORI</a> -->
              <button type="button" onclick="add_<?php echo $act_button; ?>()" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span>
                Tambah <?php echo $master;?>
              </button>
              <br><br>

              <?php if($act_button=='master_supplier'){ ?>
                  <table id="tb_<?php echo $act_button;?>" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                          <th class="" style="background-color: #4F81BD; color: white;">ID SUPPLIER</th>
                          <th class="" style="background-color: #4F81BD; color: white;">SUPPLIER NAME</th>
                          <th class="" style="background-color: #4F81BD; color: white;">ADDRESS</th>
                          <th class="" style="background-color: #4F81BD; color: white;">PIC NAME</th>
                          <th class="" style="background-color: #4F81BD; color: white;">PHONE</th>
                          <th class="" style="background-color: #4F81BD; color: white;">ACTION.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tb_data_supplier as $key => $val) { ?>
                            <tr>
                                <td><?php echo $key+1; ?></td>
                                <td><?php echo $val->id; ?></td>
                                <td><?php echo $val->supplier_name; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                  <button  type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Tampilkan Permintaan" onclick="sh_pemesanan_brg(<?php echo $val->id; ?>)">
                                    <span class="glyphicon glyphicon-search"></span>
                                  </button>
                                  <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Pengajuan">
                                    <span class="glyphicon glyphicon-edit"></span>
                                  </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                  </table>
              <?php } ?>

              <?php if($act_button=='master_harga'){ ?>
                  <table id="tb_<?php echo $act_button;?>" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                          <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                          <th class="" style="background-color: #4F81BD; color: white;">NAMA BARANG</th>
                          <th class="" style="background-color: #4F81BD; color: white;">BARCODE</th>
                          <th class="" style="background-color: #4F81BD; color: white;">ACTION.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tb_data_supplier as $key => $val) { ?>
                            <tr>
                                <td><?php echo $key+1; ?></td>
                                <td><?php echo $val->id; ?></td>
                                <td><?php echo $val->supplier_name; ?></td>
                                <td>
                                  <button  type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Tampilkan Permintaan" onclick="sh_pemesanan_brg(<?php echo $val->id; ?>)">
                                    <span class="glyphicon glyphicon-search"></span>
                                  </button>
                                  <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit Pengajuan">
                                    <span class="glyphicon glyphicon-edit"></span>
                                  </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                  </table>
              <?php } ?>
              
          </div>
        </div>
      </div>
  
