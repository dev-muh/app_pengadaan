<div class="col-md-6">
    <table class="table">
        <tr>
            <td><b>Nomor Permintaan</b></td><td>:</td>
            <td><?php echo $spb_info->no_permintaan; ?></td>
        </tr>
        <tr>
            <td><b>Tanggal Pembuatan SPB</b></td><td>:</td>
            <td><?php echo $this->Model_server->tformat($spb_info->tanggal_permintaan); ?></td>
        </tr>
        <tr>
            <td><b>Diajukan Tanggal</b></td><td>:</td>
            <td><?php echo $spb_info->diajukan_oleh; ?></td>
        </tr>
        <tr>
            <td><b>Status Permintaan</b></td><td>:</td>
            <td>
                <?php if($spb_info->status_permintaan_brg==1){ ?>
                    <b style="color: green;">Accept</b>
                <?php }?>
                <?php if($spb_info->status_permintaan_brg==0){ ?>
                    <b style="color: yellow;">Pending</b>
                <?php }?>
                <?php if($spb_info->status_permintaan_brg==2){ ?>
                    <b style="color: red;">Decline</b>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <label>Note :</label>&nbsp;<button class="btn btn-xs btn-primary" onclick="show_note($('#note').val(),'<?php echo $mode; ?>')">Show Note</button>
                <textarea <?php echo $mode=='edit' ? '':'disabled="disabled"'; ?> width="100%" id="note" class="form-control" style="resize: none;"><?php echo $spb_info->note_penerimaan; ?></textarea>
            </td>
        </tr>
    </table>
</div>

<div class="col-md-6">
    <table class="table">
        <tr>
            <td><b>Disetujui Oleh</b></td><td>:</td>
            <td><?php echo $spb_info->disetujui_oleh; ?></td>
        </tr>
        <tr>
            <td><b>Tanggal Persetujuan</b></td><td>:</td>
            <td><?php echo $this->Model_server->tformat($spb_info->disetujui_tanggal); ?></td>
        </tr>
        <tr>
            <td><b>Nomor SPB</b></td><td>:</td>
            <td><?php echo $spb_info->no_spb; ?></td>
        </tr>
        <tr>
            <td><b>SPB Dibuat Oleh</b></td><td>:</td>
            <td><?php echo $spb_info->spb_dibuat_oleh; ?></td>
        </tr>
        <tr>
            <td><b>File SPB</b></td><td>:</td>
            <td>
                <!-- <p id="file_uploaded_add" style="font-weight:bold; color:blue;"></p>
                <input onchange="prepareUpload(event)" class="name" id="cust_k_attach" type="file" name="cust_k_attach" required>
                <input id="cust_k_attach_hide" type="hidden" name="cust_k_attach_hide" required>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete File">
                    <span class="glyphicon glyphicon-remove"></span>
                </button> -->
                <?php if($mode=='edit' && empty($spb_info->attach_file)){ ?>
                    <div class="upload_spb upload-btn-wrapper">
                      <button class="btn btn-upload btn-success">Upload File</button>
                      <input onchange="prepareUpload(event,<?php echo $spb_info->id; ?>)" class="name" id="cust_k_attach" type="file" name="myfile" />
                      <input id="cust_k_attach_hide" type="hidden" name="cust_k_attach_hide" required>
                    </div>
                <?php }else{ ?>
                    <?php if(empty($spb_info->attach_file)){ ?>
                        -
                    <?php } ?>
                <?php } ?>
                <p class="upload_spb" id="file_uploaded_add" style="font-weight:bold; color:blue;">
                    <?php if(!empty($spb_info->attach_file)){ ?>
                       <a target="_blank" href="<?php echo base_url('assets/customer_attach/').$spb_info->attach_file; ?>"><?php echo $spb_info->attach_file; ?></a>
                       <?php if($mode=='edit'){ ?>
                       <a href="#" onclick="del_att(<?php echo $spb_info->id; ?>)" style="color:red;"> X</a>
                       <?php } ?>
                    <?php } ?>
                </p>
                <div id="loading_up_spb" class="col-sm-12" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;"></div>
            </td>
        </tr>
    </table>
</div>

<?php if($mode=='edit'){ ?>

    <?php if($spb_info->status_spb==0){ ?>
        <div class="col-md-12">
                <div class="col-md-5 form-group">
                    <label>Select Item</label>
                    <select id="sl_it" onchange="set_it_masuk($(this))" class="select2 act form-control" name="select_item">
                        <option selected="selected" disabled="disabled">-- Select Item --</option>
                        <?php foreach ($ls_it_spb as $key => $val) { ?>
                            <option  
                                    value='<?php echo   $val->id_it_pn."|".
                                                        $val->id_item."|".
                                                        $val->item_name."|".
                                                        $val->barcode."|".
                                                        $val->qty."|".
                                                        $val->qty_masuk; ?>'>
                                    <?php echo $val->item_name; ?>
                            </option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Barcode</label>&nbsp;<input id="auto_enter" onclick="autoenter()" type="checkbox"> Auto Enter
                    <input id="bc" class="form-control" type="number" name="barcode">
                </div>
                <div class="col-md-3 form-group">
                    <label>Qty</label>
                    <input id="qty" class="form-control" type="number" name="qty">
                </div>
                <div class="col-md-1 form-group">
                    <label>Act</label>
                    <button id="btn_add" onclick="add_qty($(this))" class="btn btn-success form-control">
                        <span class="fa fa-plus"></span>
                    </button>
                </div>
        </div>
    <?php } ?>
<?php } ?>


<div class="col-xs-12">
  <div clas="table-responsive">
    <input type="hidden" id="id_pengajuan_penerimaan" name="">
    <table id="tb_ls_item_spb" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
          <tr style="background-color: #4F81BD; color: white;">
            <th class="tb-hide"></th>
            <th class="tb-hide"></th>
            <th>No</th>
            <th>Barcode</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Supplier</th>
            <th>Harga</th>
            <th>Total Harga</th>
            <th>Item Masuk</th>
            <th>Jumlah Item Masuk</th>
            <?php if($mode=='edit'){ ?>
                <th  width="10%">Action</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
            <?php $no=1; ?>
            <?php foreach ($ls_it_spb as $key => $val) { ?>
                <tr>
                    <td class="tb-hide"><?php echo $val->id_item; ?></td>
                    <td class="tb-hide"><?php echo $val->id_supplier; ?></td>
                    <td><?php echo $no; ?></td>
                    <td class="r-td"><?php echo $val->barcode; ?></td>
                    <td><?php echo $val->item_name; ?></td>
                    <td class="edit_qty r-td"><?php echo $val->qty; ?></td>
                    <td><?php echo $val->supplier_name; ?></td>
                    <td class="edit_harga r-td"><?php echo number_format($val->harga,0,",",".").',-'; ?></td>
                    <td class="edit_total r-td"><?php echo number_format($val->total_harga,0,",",".").',-'; ?></td>
                    <td class="edit_masuk r-td">0</td>
                    <td class="r-td"><?php echo $val->qty_masuk; ?></td>
                    <?php if($mode=='edit'){ ?>
                            <td>
                                <?php if($spb_info->status_spb==0){ ?>
                                    <?php if($val->qty!=$val->qty_masuk){ ?>
                                        <button onclick="reset($(this))" type="button" class="bt-hide btn bg-red btn-sm " data-toggle="tooltip" title="Refresh">
                                          <span class="glyphicon glyphicon-refresh"></span>
                                        </button>
                                    <?php } ?>
                                <?php } ?>
                            </td>     
                    <?php } ?>              
                </tr>
                <?php $no++; ?>
            <?php } ?>
        </tbody>
    </table>
  </div>
</div>


<script type="text/javascript">
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('#tb_ls_item_spb').DataTable({searching: false, paging: false,order: [[ 2, 'asc' ]]}).column([0,1]).visible(false);
    });

    $(function(){
        $('.select2.act').select2({dropdownCssClass: "dropdownCLASS"});
    });
</script>

<style type="text/css">
    .tooltip{
        position: fixed;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    .tb-hide{
      display: none;
    }

    .select2-dropdown.dropdownCLASS {
      z-index: 99999999999999;
    }

    .upload-btn-wrapper {
      position: relative;
      overflow: hidden;
      display: <?php echo !empty($spb_info->attach_file) ? 'none':'inline-block'; ?>;
        cursor: pointer;
    }

/*    .btn-upload {
      border: 2px solid gray;
      color: gray;
      background-color: white;
      padding: 8px 20px;
      border-radius: 8px;
      font-size: 20px;
      font-weight: bold;
    }*/
    .btn-upload {
        height: 20px;
        padding-top: 0px;
        cursor: pointer;
    }

    .upload-btn-wrapper input[type=file] {
      font-size: 100px;
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
    }
</style>

<script type="text/javascript">
    $(function(){
        $("#bc").on("keydown paste cut", function() {
            var tmpItem = $('#sl_it').find('option');
            setTimeout(function(){
                if($('#bc').val().length>0){
                    $.each(tmpItem, function(index, val) {
                        var it = val.value.split('|');
                        if(it[3]==$("#bc").val()){
                            
                            it_sel_qty = index-1;
                            it_sel_jml = it[4];

                            var tb = $('#tb_ls_item_spb').DataTable();
                            tb.rows(index-1).nodes().to$().addClass( 'highlight' );
                            // it_sel_qty = key;

                            
                            var content = $('.jconfirm-content-pane').offset().top;
                            var td = $('.jconfirm-content-pane').find('.highlight:last').offset().top;
                            
                            $('.jconfirm-content-pane').animate({scrollTop: (td-content)},200);
                            
                            $('.highlight').css('background-color','green');
                            $( ".highlight" ).animate({
                                backgroundColor:'none'
                            }, 1000, function() {
                                $('.highlight').parent().find('tr').removeAttr('style');
                                $('.highlight').parent().find('tr').removeClass('highlight');

                            });

                            Ex2($('#sl_it').prop('selectedIndex',index).select2({dropdownCssClass: "dropdownCLASS",width:'100%'}));
                        }
                    });
                }
            },10);
        });
    });
function Ex2(x){
    x;
    // $('#sl_it').select2({width:'100%'});
    // alert('');
    if($('#auto_enter').is(':checked')==true){
        $('#qty').val(1);
        add_qty();
    }else{
        $('#qty').focus();
        setTimeout(function(){
            $('#btn_add').prop('disabled',false);
            // st_btn_add = true;
        },10);
        
    }
 }
</script>


<style type="text/css">

    textarea#note_p {
        width:100%;
        height: 100%;
        box-sizing:border-box;
/*        direction:rtl;*/
        display:block;
        max-width:100%;
        line-height:1.5;
        padding:15px 15px 30px;
        border-radius:3px;
        border:1px solid #F7E98D;
        font:13px Tahoma, cursive;
        transition:box-shadow 0.5s ease;
        box-shadow:0 4px 6px rgba(0,0,0,0.1);
        font-smoothing:subpixel-antialiased;
        background:linear-gradient(#F9EFAF, #F7E98D);
        background:-o-linear-gradient(#F9EFAF, #F7E98D);
        background:-ms-linear-gradient(#F9EFAF, #F7E98D);
        background:-moz-linear-gradient(#F9EFAF, #F7E98D);
        background:-webkit-linear-gradient(#F9EFAF, #F7E98D);
    }

</style>