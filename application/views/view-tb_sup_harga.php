<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <td <?php echo $mode=='add' ? 'class="col-md-3"':''; ?>><b>Nama Barang</b></td><td  <?php echo $mode=='add'? 'class="col-md-1"':''; ?>>:</td>
                    <td><?php echo !empty($item_info) ? $item_info[0]->item_name:''; ?></td>
                </tr>
                <tr>
                    <td><b>Barcode</b></td><td>:</td>
                    <td><?php echo !empty($item_info) ? $item_info[0]->barcode:''; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php if($mode=='edit'||$mode=='add'){ ?>
        <hr>
        
        <script type="text/javascript">
            var id_item = <?php echo !empty($item_info) ? $item_info[0]->id:''; ?>;
            

        
        </script>
        <div class="row">
            <div class="form-group col-md-4">
                <label>Select Supplier / <b onclick="add_master_supplier('no')" data-toggle="tooltip" title="Click to Add Supplier" style="color:blue;cursor: pointer;">Tambah Supplier</b></label>
                <select class="inp_hrg form-control select2 act" id="sel_sup">
                    <option disabled="disabled" selected="selected">-- Select Supplier --</option>
                    <?php if(!empty($ls_sup_all)){ ?>
                        <?php foreach ($ls_sup_all as $key => $val) { ?>
                            <option value="<?php echo $val->id.'|'.$val->supplier_name; ?>"><?php echo $val->supplier_name; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label>Harga</label>
                <input type="text" id="hrg_frm_sup" name="" placeholder="Masukkan Harga" class="inp_hrg form-control">
            </div>

            <div class="form-group col-md-1">
                <label for="btn-act">Action</label>
                <button id="btn-act" class="inp_hrg btn btn-sm btn-success form-control" onclick="add_it_harga()">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>

            <div class="form-group col-md-2">
                <label></label>
                <div id="loading_sup" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
            </div>
        </div>
        <hr>
    <?php } ?>

    <div class="row">
        <div class="col-xs-12">
          <div clas="table-responsive">
            <input type="hidden" id="id_pengajuan_penerimaan" name="">
            <table id="tb_sp_hrg" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                <thead>
                  <tr style="background-color: #4F81BD; color: white;">
                    <th>ID</th>
                    <th width="10%">No</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <?php if($mode=='add'||$mode=='edit'){ ?>
                        <th>Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    <?php if(!empty($ls_sup)){ ?>
                        <?php foreach ($ls_sup as $key => $val) { ?>
                            <tr>
                                <td><?php echo $val->id_supplier; ?></td>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $val->supplier_name; ?></td>
                                <td><?php echo number_format($val->harga,0,",",".").',-'; ?></td>
                                <?php if($mode=='add'||$mode=='edit'){ ?>
                                    <td>

                                        <button class="btn btn-edit btn-warning btn-sm" onclick="edit(this,'edit')">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </button>

                                        <button style="display: none" class="btn btn-ok btn-success btn-sm" onclick="edit(this,'save')">
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>

                                        <button class="btn btn-danger btn-sm" onclick="del(this)">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php $no++; ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
          </div>
        </div>
    </div>
    <hr>
</div>

<script type="text/javascript">
  $("#hrg_frm_sup").on("keydown paste cut", function() {
    var x = '';
    setTimeout(function(){
        $("#hrg_frm_sup").val(cnum_n($("#hrg_frm_sup").val()));
        $("#hrg_frm_sup").val(num_n($("#hrg_frm_sup").val()));
    },10);
  });
</script>