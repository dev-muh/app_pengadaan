<script>
  var kat = <?php echo json_encode($kats); ?>;
  var sub = <?php echo json_encode($subs); ?>;
</script>

<input type="hidden" id="BASE_URL" value="<?php echo base_url(); ?>">
<div class="row">
  <div class="col-md-12 ">
    <div id="table-wrapper">
        <center>
          <h2>SET UP <?php echo $page_title; ?></h2>
          <hr style="border-top: 3px double #8c8b8b;">
        </center>     
        <button type="button" id="addRow" class="btn btn-success pull-right" data-toggle="modal" data-target="#add-item"><span class="glyphicon glyphicon-plus"></span>
          Tambah Item
        </button>
        <br><br>            
        <table id="tblItem" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%">
          <thead>
          <tr>
            <th class="" style="background-color: #4F81BD; color: white; ">NO.</th>
            <th class="" style="background-color: #4F81BD; color: white; ">BARCODE</th>
            <th class="" style="background-color: #4F81BD; color: white; ">ITEM NAME</th>
            <th class="" style="background-color: #4F81BD; color: white; ">MIN QTY</th>
            <th class="" style="background-color: #4F81BD; color: white; ">MAX QTY</th>
            <th class="" style="background-color: #4F81BD; color: white; ">QTY</th>
            <th class="" style="background-color: #4F81BD; color: white; ">UPDATE BY</th>
            <th class="" style="background-color: #4F81BD; color: white; ">UPDATE DATE</th>
            <th class="" style="background-color: #4F81BD; color: white; ">STATUS</th>
            <th class="" style="background-color: #4F81BD; color: white; width: 20%;">ACTION.</th>
          </tr>
          </thead>
          <tbody>
            <?php 
              $no = 1;
              if(!empty($tb_item) && $tb_item !== NULL){
                
                foreach ($tb_item as $tb) {
                  $m = $tb->min_qty;
                  $jum = $m*(110/100);
                  $c_qty_min = '';
                  if($tb->qty<$jum){
                      $c_qty_min = 'bg-red';
                  }else{
                      $c_qty_min = 'bg-green';
                  }

                  $s_stat = '';
                  if($tb->is_delete==0){
                    $s_stat = 'Active';
                  }else{
                    $s_stat = 'Inactive';
                  }

                  echo '<tr>
                        <td>'.$no.'</td>
                        <td class="r-td">'.$tb->barcode.'</td>
                        <td>'.$tb->nama_item.'</td>
                        <td>'.$tb->min_qty. ' ' . $tb->satuan .'</td>
                        <td>'.$tb->max_qty. ' ' . $tb->satuan .'</td>
                        <td  class="'.$c_qty_min.'" title="'. $jum .'">'.$tb->qty . ' ' . $tb->satuan .'</td>
                        <td>'.$tb->uodate_by_u_i.'</td>
                        <td>'.tformat($tb->update_date).'</td>
                        <td>'.$s_stat.'</td>
                        <td><center>
                          <button data-toggle="tooltip" title="Ubah" class="btn btn-sm btn-warning" onclick="edit($(this),'. $tb->ID_ITEM .')" value="'
                          .$tb->barcode.'|'
                          .$tb->nama_item.'|'
                          .$tb->id_sub .'|'
                          .$tb->id_kat .'|'
                          .$tb->qty . '|'
                          .$tb->satuan . '|'
                          .$tb->deskripsi_satuan . '|'
                          .$tb->min_qty . '|'
                          .$tb->max_qty .

                          '"><span class="glyphicon glyphicon-edit"></span></button>'; ?>

                          <?php if($tb->is_delete==0){ ?>
                              <button data-toggle="tooltip" title="Inactive" class="btn btn-sm btn-danger" onclick="del(<?php echo $tb->ID_ITEM; ?>)"><span class="glyphicon glyphicon-remove"></span></button>
                          <?php } ?>

                          <?php if($tb->is_delete==1){ ?>
                              <button data-toggle="tooltip" title="Active" class="btn btn-sm btn-success" onclick="activate(<?php echo $tb->ID_ITEM; ?>)"><span class="glyphicon glyphicon-ok"></span></button>
                          <?php } ?>

                          <a href="<?php echo base_url('produk/printbarcode?id=').$tb->ID_ITEM; ?>" target="_blank"><button data-toggle="tooltip" title="Cetak Barcode" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-print"></span></button></a>
                          
                          <a href="<?php echo base_url('report/log_item?id=').$tb->ID_ITEM.'&tahun='.date('Y'); ?>" target="_blank"><button data-toggle="tooltip" title="History" class="btn btn-default btn-sm"><span class="fa fa-history"></span></button></a>
                          </center>
                        </td>
                      </tr>
                  <?php $no++;
                }
               
              }
            ?>
          </tbody>
        </table>
        <?php echo '<input type="hidden" class="cItem" value="'. $no .'">'; ?>
    </div>
  </div>
</div>

<div class="modal modal-success fade" id="add-item" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Add Item</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_item" name="id" value=""/><br>
        <div class="row">
          <label for="code" class="col-sm-3 control-label">Barcode <span class="asterisk">*</span></label>

          <div class="col-sm-7">
            <input type="text" class="req form-control" id="it_barcode" name="it_barcode" placeholder="Masukkan Barcode" value="">
          </div>
          <div class="col-sm-1 btn btn-success" onclick="c_barcode()"><span class="glyphicon glyphicon-barcode"></span></div>
        </div> <br>
        <div class="row">
          <label for="code" class="col-sm-3 control-label">Nama Item <span class="asterisk">*</span></label>

          <div class="col-sm-9">
            <input type="text" class="req form-control" id="it_nama" name="it_nama" placeholder="Nama Item/Produk" value="" required>
          </div>
        </div> <br>
        <div class="row">
          <label for="katName" class="col-sm-3 control-label">Kategori <span class="asterisk">*</span></label>  
          <div class="col-sm-9">
            <select  onchange="setSub($(this))" id="kat_id" class="katName select2 req form-control col-sm-12" name="it_id_kat" required>
              
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
          <label for="subKatName" class="col-sm-3 control-label">Sub Kategori <span class="asterisk">*</span></label>  
          <div class="col-sm-9">
            <select  id="sub_kat_id" class="subKatName select2 req form-control col-sm-12" name="it_id_sub" required>
              <?php
                foreach ($tb_sub_kategori as $op_sub_kat) {
                    echo '<option value="'. $op_sub_kat->id .'" ';                         

                    echo '>'.$op_sub_kat->sub_description.'</option>';
                }
              ?>
            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <label for="it_min_qty" class="col-sm-3 control-label">Min. Qty <span class="asterisk"></span></label>

          <div class="col-sm-9">
            <input type="number" class="req form-control" id="it_min_qty" name="it_min_qty" placeholder="Jumlah Min Item" value="" required>
          </div>
        </div> <br>

        <div class="row">
          <label for="it_max_qty" class="col-sm-3 control-label">Max. Qty <span class="asterisk"></span></label>

          <div class="col-sm-9">
            <input type="number" class="req form-control" id="it_max_qty" name="it_max_qty" placeholder="Jumlah Max Item" value="" required>
          </div>
        </div> <br>

        <div class="row">
          <label for="qty" class="col-sm-3 control-label">Qty <span class="asterisk"></span></label>

          <div class="col-sm-9">
            <input type="number" class="req form-control" id="it_qty" name="it_qty" placeholder="Jumlah Item" value="" required>
          </div>
        </div> <br>

        <div class="row">
          <label for="sat" class="col-sm-3 control-label">Satuan <span class="asterisk"></span></label>

          <div class="col-sm-9">
            <input type="text" class="req form-control" id="it_sat" name="it_sat" placeholder="Satuan Item. Ex: Pcs" value="" required>
          </div>
        </div> <br>

        <div class="row">
          <label for="it_sat_des" class="col-sm-3 control-label">Deskripsi Satuan <span class="asterisk"></span></label>

          <div class="col-sm-9">
            <textarea type="text" class="req form-control" id="it_sat_des" name="it_sat_des" placeholder="Ex : 1 Box Isi 10 Pcs" value="" required></textarea>
          </div>
        </div> <br>
<!--         <div class="row">
          <label for="code" class="col-sm-3 control-label">Selling Price <span class="asterisk">*</span></label>

          <div class="col-sm-9">
            <input type="text" class="form-control" id="it_selling_price" name="it_selling_price" placeholder="" value="" required>
          </div>
        </div> <br>
        <div class="row">
          <label for="code" class="col-sm-3 control-label">Selling Cost <span class="asterisk">*</span></label>

          <div class="col-sm-9">
            <input type="text" class="form-control" id="it_selling_cost" name="it_selling_cost" placeholder="" value="" required>
          </div>
        </div><br>
        <div class="row">
          <label for="code" class="col-sm-3 control-label">Cost Percentage <span class="asterisk">*</span></label>

          <div class="col-sm-9">
            <input type="text" class="form-control" id="it_cost_percentage" name="it_cost_percentage" placeholder="" value="" required>
          </div>
        </div><br> -->
        <div class="row">
          <label for="code" class="col-sm-3 control-label">Photo <span class="asterisk">*</span></label>

          <div class="col-sm-9">
            <form id="up" method="post" enctype="multipart/form-data">
              <div class="img_upload col-sm-4">
                <label for="photo1">
                  <img src="<?php echo base_url('assets/img/add.png'); ?>" class="photo img-thumbnail " alt="Cinque Terre">
                </label>
                <input id="photo1" type="file" accept=".jpg,.jpeg,.png" name="image[]" onchange="previewFile($(this))">
              </div>

              <!-- <div class="img_upload col-sm-4">
                <label for="photo2">
                  <img src="<?php echo base_url('assets/img/add.png'); ?>" class="photo img-thumbnail " alt="Cinque Terre">
                </label>
                <input id="photo2" type="file" accept=".jpg,.jpeg,.png" name="image[]" onchange="previewFile($(this))">
              </div>

              <div class="img_upload col-sm-4">
                <label for="photo3">
                  <img src="<?php echo base_url('assets/img/add.png'); ?>" class="photo img-thumbnail " alt="Cinque Terre">
                </label>
                <input id="photo3" type="file" accept=".jpg,.jpeg,.png" name="image[]" onchange="previewFile($(this))">
              </div> -->
            </form>

          </div>
          <label for="code" class="col-sm-3 control-label"><span class="asterisk"></span></label>

          <div class="col-sm-9">
              <p style="color:yellow; font-size: 12px;">* Ekstensi gambar harus JPG, dengan max ukuran 1 MB dan Resolusi 200x200 </p>
          </div>
          
        </div><br>





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

<style type="text/css">
  .nophoto{
    filter: opacity(0.2);
  }
  .img_upload > input{
    display: none;
  }

  .bx{
    background:rgba(255,0,0,0.1);
/*    width:100px; height:100px;
    position:relative;*/
    
    -webkit-transition: background .5s ease-out;
       -moz-transition: background .5s ease-out;
         -o-transition: background .5s ease-out;
            transition: background .5s ease-out;
  }
</style>