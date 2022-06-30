<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">MASTER GROUP</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-md-6">
        <button class="btn btn-success" onclick="addGroup()">
          <span class="fa fa-plus"></span>&nbsp;Tambah Group
        </button>  
      </div>
    </div>
    <br>

    <div class="row">
      <div class="col-md-12">
        <table class="table table-responsive table-bordered">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Group</th>
              <th>Nama Pertama Group</th>
              <th>Nama Terakhir Group (Ditampilkan)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($tb_data_group)){ ?>
              <?php $no=1; ?>
              <?php foreach ($tb_data_group as $key => $value) { ?>
                <tr>
                  <td><?= $no; ?></td>
                  <td><?= $value->id; ?></td>
                  <td><?= $value->group_name; ?></td>
                  <td><?= $value->nama_baru; ?></td>
                  <td>
                    <button class="btn btn-xs btn-default" onclick="v_group(<?= $value->id; ?>)">
                      <span class="fa fa-search"></span>
                    </button>
                    <button class="btn btn-xs btn-warning" onclick="changeGroup(<?= $value->id; ?>,'<?= $value->nama_baru; ?>')">
                      <span class="fa fa-edit"></span>
                    </button>
                  </td>
                </tr>
                <?php $no++; ?>
              <?php } ?>
            <?php } ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>