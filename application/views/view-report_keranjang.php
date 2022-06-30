<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Filter By</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-2"><label class="pull-right">Tahun</label></div>
            <div class='col-md-3'>
                <div class="form-group">
                    <div class='input-group date' id="tahun_keranjang">
                        <input type='text' class="form-control" id="input_tahun_keranjang" value="<?php echo !empty($_GET['tahun']) ? $_GET['tahun']:date('Y'); ?>" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar" ></span>
                        </span>
                    </div>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="pull-right">Group</label></div>
            <div class='col-md-3'>
                <div class="form-group">
                    <select class="form-control sel2 act" id="input_group_keranjang">
                      <option value=''>-- ALL GROUP --</option>
                      <?php foreach ($ls_group as $key => $value) { ?>
                          <option value=<?= $value->id; ?>><?= empty($value->nama_baru)?$value->group_name:$value->nama_baru; ?></option>
                      <?php } ?>
                      
                    </select>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="pull-right">Status Rating</label></div>
            <div class='col-md-3'>
                <div class="form-group">
                    <select class="form-control" id="input_rating_keranjang">
                      <option value=''>-- ALL STATUS --</option>
                      <option value=1>SUDAH RATING</option>
                      <option value=0>BELUM RATING</option>
                    </select>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-2"><label class="pull-right"></label></div>
            <div class="col-md-2 form-group">
              <button class="form-control btn btn-success" onclick="submit($(this))">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">History Keranjang</h3>&nbsp;&nbsp;<i id="loading_ctrl" class="fa fa-refresh" style="display: none;"></i>
          <a href="javascript:void(0)" onclick="export_pdf_history_keranjang()" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> PDF</a>
          <a href="javascript:void(0)" onclick="export_excel_history_keranjang()" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i> EXCEL</a>
        </div>
        <div class="box-body" id="tb_keranjang">

        </div>
      </div>
    </div>
</div>