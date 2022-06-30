<div class="col-md-6">
    <table class="table">
        <tr>
            <td><b>Nomor Permintaan</b></td><td>:</td>
            <td><?php echo $ls_it_pms[0]->no_pengajuan; ?></td>
        </tr>
        <tr>
            <td><b>Tanggal Permintaan</b></td><td>:</td>
            <td><?php echo $this->Model_server->tformat($ls_it_pms[0]->tgl_pengajuan); ?></td>
        </tr>
        <tr>
            <td><b>Diajukan Oleh</b></td><td>:</td>
            <td><?php echo $ls_it_pms[0]->submiter_name; ?></td>
        </tr>
    </table>
</div>
<div class="col-md-6">
    <table class="table">
        <tr>
            <td><b>Status Permintaan</b></td><td>:</td>
            <td>
                <?php if($ls_it_pms[0]->status==1){ ?>
                    Accept
                <?php } ?>
                <?php if($ls_it_pms[0]->status==0){ ?>
                    Pending
                <?php } ?>
                <?php if($ls_it_pms[0]->status==2){ ?>
                    Decline
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td><b>Disetujui Oleh</b></td><td>:</td>
            <td><?php echo $ls_it_pms[0]->approval_name; ?></td>
        </tr>
        <tr>
            <td><b>Tanggal Persetujuan</b></td><td>:</td>
            <td><?php echo $this->Model_server->tformat($ls_it_pms[0]->approve_date); ?></td>
        </tr>
        <tr>
            <td><b>Status SPB</b></td><td>:</td>
            <td>
                <?php if($ls_it_pms[0]->stat_spb==1){ ?>
                    <p style="color:red;">CLOSED</p>
                <?php } ?>
                <?php if($ls_it_pms[0]->stat_spb==0){ ?>
                    <p style="color: green;">OPEN</p>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>
<br>
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
            <?php $no=1; ?>
            <?php foreach ($ls_it_pms as $key => $val) { ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td class="r-td"><?php echo $val->barcode; ?></td>
                    <td><?php echo $val->item_name; ?></td>
                    <td class="r-td"><?php echo $val->min_qty; ?></td>
                    <td class="r-td"><?php echo $val->max_qty; ?></td>
                    <td class="r-td"><?php echo $val->h_stock; ?></td>
                    <td class="r-td"><?php echo $val->qty; ?></td>
                </tr>
                <?php $no++; ?>
            <?php } ?>
        </tbody>
    </table>
  </div>
</div>