
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
              <button type="button" id="addRow" class="btn btn-success pull-right" data-toggle="modal" data-target="#add-kategori"><span class="glyphicon glyphicon-plus"></span>
                Tambah Kategori
              </button>
              <br><br>
              <table id="tblKategoriRestaurant" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th class="" style="background-color: #4F81BD; color: white; width: 15px">NO.</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 30px">KODE</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 30px">NAMA BARANG</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 100px">UPDATE BY</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 110px">UPDATE DATE</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 100px">ACTION.</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 1;
                    if(!empty($tb_kategori) && $tb_kategori !== NULL){
                      
                      foreach ($tb_kategori as $tb) {
                        echo '<tr>
                              <td>'.$no.'</td>
                              <td>'.$tb->code.'</td>
                              <td>'.$tb->description.'</td>
                              <td>'.$tb->update_by_username.'</td>
                              <td>'.tformat($tb->update_date).'</td>
                              <td>
                                <button data-toggle="tooltip" title="Ubah" class="btn btn-warning" onclick="edit($(this),'. $tb->id .')" value="'.$tb->code.','.$tb->description.'"><span class="glyphicon glyphicon-edit"></span></button>
                                <button data-toggle="tooltip" title="Hapus" class="btn btn-danger" onclick="del('.$tb->id.')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                            </tr>';
                        $no++;
                      }
                    }

                  ?>
                </tbody>
              </table>
              <?php echo '<input type="hidden" class="cKat" value="'. $no .'">'; ?>
          </div>
        </div>
      </div>

      <div class="modal modal-success fade" id="add-kategori" style="display: none;">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Kategori</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" id="id_kat" name="id" value=""/>
                <div class="row">
                  <label for="code" class="col-sm-4 control-label">Code <span class="asterisk">*</span></label>

                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="code" name="code" placeholder="" value="">
                  </div>
                </div>  
                <br>
                <div class="row">
                  <label for="name_txt" class="col-sm-4 control-label">Name <span class="asterisk">*</span></label>

                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="name_txt" name="name_txt" placeholder="" value="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="button" id="save" class="btn btn-outline">Save</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

    <!-- /.content -->
  
