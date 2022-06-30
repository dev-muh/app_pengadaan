<script type="text/javascript">
    var id_spb = <?php echo $ls_it_spb[0]->id_sp; ?>;
</script>
<div class="col-md-8">
    <table class="table" id="table_spb">
        <tr>
            <td><b>Nomor SPB</b></td><td>:</td>
            <td><?php echo $ls_it_spb[0]->no_spb; ?></td>
            <td></td>
        </tr>
        <tr>
            <td><b>Tanggal Pembuatan SPB</b></td><td>:</td>
            <td><?php echo $this->Model_server->tformat($ls_it_spb[0]->tgl_pembuatan_spb); ?></td>
            <td></td>
        </tr>
        <tr>
            <td><b>SPB Dibuat Oleh</b></td><td>:</td>
            <td><?php echo $ls_it_spb[0]->spb_dibuat_oleh; ?></td>
            <td></td>
        </tr>
        <tr>
            <td><b>Start</b></td><td>:</td>

            <td id="start_date_per" date="<?php echo $ls_it_spb[0]->start; ?>">
                <?php 
                    $cr_date=date_create($ls_it_spb[0]->start); 
                    $start_dt = date_format($cr_date,"d M Y"); 
                    echo $start_dt;
                ?>

            </td>
            <td>
                <button id="btn_change_periode" onclick="ed_date_period($(this))" class="btn btn-default btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span>
                </button>

                <div id="st_date" style="width:50%; display: none; z-index: 99999999999999999;" class="input-group input-daterange date" data-provide="datepicker">
                    <input id="start_date" type="text" class="form-control" value="<?php echo $start_dt; ?>">
                </div>
            </td>
        </tr>
        <tr>
            <td><b>End</b></td><td>:</td>
            <td id="end_date_per" date="<?php echo $ls_it_spb[0]->end; ?>">
                <?php 
                    $date=date_create($ls_it_spb[0]->end); 
                    echo date_format($date,"d M Y"); 
                ?>
            </td>
            <td></td>
        </tr>
    </table>
</div>
<div class="col-md-3">
</div>

<br>
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
            <th  width="20%">Action</th>
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
                    <td ><?php echo $val->supplier_name; ?></td>
                    <td class="edit_harga r-td"><?php echo number_format($val->harga,0,",",".").',-'; ?></td>
                    <td class="edit_total r-td"><?php echo number_format($val->total_harga,0,",",".").',-'; ?></td>
                    <td style="display: -webkit-inline-box;">
                        <?php if($val->status_permintaan==0){ ?>
                            <?php if($val->status_spb==0){ ?>
                                <button type="button" onclick="edit_item($(this),<?php echo $val->id_spb; ?>,<?php echo $val->id_item; ?>)" class="bt-hide it_edit btn btn-warning btn-sm " data-toggle="tooltip" title="Edit Item">
                                      <span class="glyphicon glyphicon-pencil"></span>
                                </button>&nbsp;
                                <!-- <button type="button" class="bt-hide btn bg-red btn-sm " data-toggle="tooltip" title="Delete">
                                  <span class="glyphicon glyphicon-trash"></span>
                                </button> -->
                                <div id="loading_spb" style="display:none; z-index: 9; background-image:url(<?php echo base_url('assets/dist/img/loading-mini.gif'); ?>);width: 30px; height: 30px;background-size: cover;">
                                </div>
                            <?php } ?>

                            <?php if($val->status_spb==1){ ?>
                                <b style="color: red;">CLOSED</b>
                            <?php } ?>
                        <?php } ?>
                        <?php if($val->status_permintaan==1){ ?>
                            <b style="color: red;">CLOSED</b>
                        <?php }?>
                        
                        
                    </td>
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
</style>
