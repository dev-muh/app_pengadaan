      <input type="hidden" id="BASE_URL" value="<?php echo base_url(); ?>">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <div id="table-wrapper">
              <center>
                <h2>SET UP <?php echo $page_title; ?></h2>
                <hr style="border-top: 3px double #8c8b8b;">
                
              </center>
              <br><br>
              <button type="button" id="addRow" class="btn btn-success pull-right" data-toggle="modal" data-target="#add-sub_kategori"><span class="glyphicon glyphicon-plus"></span>
                Tambah Sub Kategori
              </button>
              <br><br>
              <table id="tblSubKategori" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th class="" style="background-color: #4F81BD; color: white; width: 15px">NO.</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 30px">KATEGORI</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 30px">KODE</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 30px">NAMA SUB</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 100px">UPDATE BY</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 110px">UPDATE DATE</th>
                  <th class="" style="background-color: #4F81BD; color: white; width: 100px">ACTION.</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    $no = 1;
                    if(!empty($tb_sub_kategori) && $tb_sub_kategori !== NULL){
                      
                      foreach ($tb_sub_kategori as $tb) {
                        echo '<tr>
                              <td>'.$no.'</td>
                              <td>'.$tb->deskripsi_kategori.'</td>
                              <td>'.$tb->sub_kategori_code.'</td>
                              <td>'.$tb->sub_description.'</td>
                              <td>'.$tb->update_by_username.'</td>
                              <td>'.tformat($tb->update_date).'</td>
                              <td>
                                <button data-toggle="tooltip" title="Ubah" class="btn btn-warning" onclick="edit($(this),'. $tb->id .')" value="'.$tb->sub_kategori_code.','.$tb->sub_description.','. $tb->id_kat .'"><span class="glyphicon glyphicon-edit"></span></button>
                                <button data-toggle="tooltip" title="Hapus" class="btn btn-danger" onclick="del('.$tb->id.')"><span class="glyphicon glyphicon-trash"></span></button>
                              </td>
                            </tr>';
                        $no++;
                      }
                     
                    }

                  ?>
                </tbody>
              </table>
              <?php echo '<input type="hidden" class="cSubKat" value="'. $no .'">'; ?>
          </div>
        </div>
      </div>

      <div class="modal modal-success fade" id="add-sub_kategori" style="display: none;">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Add SUBKategori</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" id="id_sub_kat" name="id" value=""/>
              <div class="row">
                <label for="katName" class="col-sm-4 control-label">Kategori <span class="asterisk">*</span></label>  
                <div class="col-sm-8">
                  <select  id="kat_id" class="katName form-control col-sm-12" name="id_kategori">
                    <?php
                      foreach ($tb_kategori as $op_kat) {
                          echo '<option value="'. $op_kat->id .'" ';                         

                          echo '>'.$op_kat->description.'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <br>
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