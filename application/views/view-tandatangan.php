<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Tanda Tangan</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="POST" action="tandatangan/submit" enctype="multipart/form-data">
      <div class="col-md-6">
<!--         <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="tanggal">Per Tanggal</label>
              <input type="date" name="tanggal" class="form-control" id="tanggal">
            </div>
          </div>
        </div> -->

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" name="nama" class="form-control" id="nama" value="<?= !empty($dt_ttd)?$dt_ttd->nama:''; ?>">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="jabatan">Jabatan</label>
              <input type="text" name="jabatan" class="form-control" id="jabatan" value="<?= !empty($dt_ttd)?$dt_ttd->jabatan:''; ?>">
            </div>
          </div>
        </div>

<!--         <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="ttd">Tanda Tangan</label>
              <input type="file" name="ttd" class="form-control" id="ttd">
            </div>
          </div>
        </div> -->

        <div class="row">
          <div class="col-md-12">
            <button class="btn btn-primary pull-right" name="submit">Update</button>
          </div>
        </div>
      </div>
    </form>

<!--     <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <img src="https://paragram.id/upload/media/entries/2019-07/31/9317-1-7454c0792fd0bc375015427933ee39c4.jpg" width="100%" height="100%">
        </div>
      </div>
    </div> -->

    
  </div>

  <div class="box-footer with-border">

  </div>
</div>


<!-- <div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">List Tanda Tangan SPB</h3>
  </div>
  <div class="box-body">
    <div class="col-md-12">
      <table class="table table-responsive table-bordered">
        <thead>
          <tr bgcolor="grey">
            <th>Dari Tanggal</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Gambar</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($dt_ttd)){ ?>
            <?php foreach ($dt_ttd as $key => $value) { ?>
              <tr>
                <td><?= $value->periode; ?></td>
                <td><?= $value->nama; ?></td>
                <td><?= $value->jabatan; ?></td>
                <td><a href="<?= base_url('assets/img/ttd/').$value->file_tandatangan; ?>">View Image</a></td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div> -->