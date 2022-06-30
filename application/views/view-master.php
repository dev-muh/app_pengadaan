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
              
              <?php if($mode=='view'){ ?>
                  <?php if($act_button=='master_supplier'){ ?>

                      <button type="button" onclick="add_<?php echo $act_button; ?>()" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span>
                        Tambah <?php echo $master;?>
                      </button>
                      <br><br>

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
                            <?php if(!empty($tb_data_supplier)){ ?>
                                <?php foreach ($tb_data_supplier as $key => $val) { ?>

                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $val->supplier_id; ?></td>
                                        <td><?php echo $val->supplier_name; ?></td>
                                        <td><?php echo $val->supplier_address; ?></td>
                                        <td><?php echo $val->supplier_pic_name; ?></td>
                                        <td><?php echo $val->supplier_phone; ?></td>
                                        <td>

                                          <button onclick="add_master_supplier('','edit',$(this))" type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah" 
                                          data_sup="<?php 
                                                        echo 
                                                            $val->id.'|'.
                                                            $val->supplier_id.'|'.
                                                            $val->supplier_name.'|'.
                                                            $val->supplier_address.'|'.
                                                            $val->supplier_pic_name.'|'.
                                                            $val->supplier_phone;
                                                    ?>">
                                            <span class="glyphicon glyphicon-edit"></span>
                                          </button>
                                          <button onclick="del_supplier(<?php echo $val->id; ?>)" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus">
                                                <span class="glyphicon glyphicon-trash"></span>
                                          </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                      </table>
                  <?php } ?>

                  <?php if($act_button=='master_harga'){ ?>
                      <a href="<?php echo base_url('master/add_harga_sup'); ?>">
                          <button type="button" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span>
                            Tambah <?php echo $master;?>
                          </button>
                      </a>
                      <br><br>

                      <table id="tb_<?php echo $act_button;?>" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                              <th class="" style="background-color: #4F81BD; color: white;">NO.</th>
                              <th class="" style="background-color: #4F81BD; color: white;">NAMA BARANG</th>
                              <th class="" style="background-color: #4F81BD; color: white;">BARCODE</th>
                              <th class="" style="background-color: #4F81BD; color: white;">JUMLAH SUPPLIER</th>
                              <th class="" style="background-color: #4F81BD; color: white;">ACTION.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($tb_data_harga)){ ?>
                                <?php foreach ($tb_data_harga as $key => $val) { ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $val->item_name; ?></td>
                                        <td class="r-td"><?php echo $val->barcode; ?></td>
                                        <td  class="r-td"><?php echo $val->jml_supplier; ?></td>
                                        <td>
                                          <button  type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Lihat Rincian" onclick="sh_<?php echo $act_button; ?>(<?php echo $val->id_item; ?>,'<?php echo $val->item_name; ?>')">
                                            <span class="glyphicon glyphicon-search"></span>
                                          </button>

                                          <a href="<?php echo base_url('master/add_harga_sup?mode=edit&id=').$val->id_item; ?>">
                                              <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Ubah">
                                                <span class="glyphicon glyphicon-edit"></span>
                                              </button>
                                          </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                      </table>
                  <?php } ?>
              <?php } ?>

              <?php if($mode=='add' || $mode=='edit'){ ?>
                    <script type="text/javascript">
                      <?php if($_GET['mode']=='edit'){ ?>
                          var item_get = <?php echo $_GET['id']; ?>;
                      <?php }else{ ?>
                          var item_get = '';
                      <?php } ?>
                    </script>
                      <div class="col-md-12"></div>
                          <div class="panel panel-primary">
                              <div class="panel-heading">
                                  LIST SUPPLIER
                              </div>
                              <div class="panel-body" >
                                  <div class="row">
                                      <div class="col-md-5">
                                          <div class="form-group">
                                              <label>Select Item</label>
                                              <select class="inp_it form-control select2 act" onchange="sel_prod($(this).val())">
                                                  <option disabled="disabled" selected="selected">-- Select Item --</option>
                                                  <?php foreach ($tb_item as $key => $val) { ?>
                                                      <option value="<?php echo $val->ID_ITEM; ?>"><?php echo $val->nama_item; ?> - <?php echo $val->barcode; ?></option>
                                                  <?php } ?>
                                              </select>
                                          </div>
                                      </div>
                                      <div class="form-group col-md-2">
                                          <label></label>
                                          <div id="loading_item" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
                                      </div>
                                  </div>
                                  <br><br>
                                  <div class="row" id="tb_ls_supplier">

                                  </div>
                              </div>

                              <div class="panel-footer">
                                  

                              </div>
                          </div>
                      </div>
              <?php } ?>
              
          </div>
        </div>
      </div>
  
