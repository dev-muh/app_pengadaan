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
                <a href="<?php echo base_url('transaksi/print_spb/');?><?php echo $value->id; ?>" target="_blank">
                  <button type="button" class="l<?php echo $value->id; ?> btn bg-green btn-sm" data-toggle="tooltip" title="Cetak">
                    <span class="glyphicon glyphicon-print"></span>
                  </button>
                </a>
                
              </td>
              
          </tr>
    <?php } ?>
    <?php } ?>
  </tbody>
</table>