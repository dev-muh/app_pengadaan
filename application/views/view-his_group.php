<table class="table table-responsive table-bordered">
  <thead>
    <tr>
      <th>Nama Awal</th>
      <th>Diganti Menjadi</th>
      <th>Pada Tanggal</th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($ls_group)){ ?>
      <?php foreach ($ls_group as $key => $value) { ?>
        <tr>
          <td><?= $value->nama_awal; ?></td>
          <td><?= $value->nama_baru; ?></td>
          <td><?= $value->insert_date; ?></td>
        </tr>
      <?php } ?>
    <?php } ?>
  </tbody>
</table>