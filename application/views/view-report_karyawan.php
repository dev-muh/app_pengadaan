<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/checkbox/check-radio.css">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script> -->
<script src="<?php echo base_url(); ?>assets/plugins/chart/chart.js"></script>
<script type="text/javascript">
    var user = <?php echo $_GET['by']; ?>;
    var group = "<?php echo empty($user_info) ? 'All Group':$user_info[0]->group_name; ?>";
    var group_id = <?php echo $_GET['group']; ?>;

    var type_sel = "<?php echo $_GET['type']; ?>";
    var bulan = <?php echo $_GET['bulan']; ?>;
    var tahun = <?php echo $_GET['tahun']; ?>;

</script>



<div class="row">
    <div class="col-md-8">
        <div class="box box-solid box-primary">
          <div class="box-header with-border">
            <i class="fa fa-bar-chart"></i>

            <h3 class="box-title">Report</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
              <br>
              <div class="col-md-6">

                  <label class="Form-label--tick">
                      <input type="radio" value="employee" onclick="ch_by(
                                          $('#emp_sel').val().split('|')[0],
                                          $('#bln_sel').val(),
                                          $('#th_sel').val(),
                                          $('#group_sel').val(),
                                          'employee',
                                          null,
                                          $('#input_periode').val(),
                                          $('#input_tanggal').val()
                                          )" name="SomeRadio" class="Form-label-radio" <?php echo $_GET['type']=='employee' ? 'checked':'';?>>
                      <span class="Form-label-text">By Employee</span>
                  </label>

                  <?php //if($_SESSION['user_type']<>'Karyawan'){ ?>
                  <label class="Form-label--tick">
                      <input type="radio" value="group" onclick="ch_by(
                                          $('#emp_sel').val().split('|')[0],
                                          $('#bln_sel').val(),
                                          $('#th_sel').val(),
                                          $('#group_sel').val(),
                                          'group',
                                          null,
                                          $('#input_periode').val(),
                                          $('#input_tanggal').val()
                                          )" name="SomeRadio" class="Form-label-radio" <?php echo $_GET['type']=='group' ? 'checked':'';?>>
                      <span class="Form-label-text">By Group</span>
                  </label>
                  <?php //} ?>

              </div>
              <div class="col-md-6">
                <div class="row">
                  <label class="col-sm-4">Periode</label>
                  <div class="col-sm-8 form-group">
                    <select id="input_periode" class="form-control">
                      <option value="Bulanan" <?= $periode == "Bulanan" ? 'selected' : '' ?>>Bulanan</option>
                      <option value="Harian" <?= $periode == "Harian" ? 'selected' : '' ?>>Harian</option>
                    </select>
                  </div>
                </div>  
              </div>

              <div class="col-md-12">
                  <hr style="border-top: 3px double #8c8b8b;">
                  <div id="konten_laporan_karyawan">
                      <div class="row" id="emp_name">
                          <label  class="col-md-6" style="display:inline-block;width: 150px;">Employee Name</label>
                          <div class="form-group col-md-6">
                              <select id="emp_sel" class="select2 act form-control" onchange="change($(this).val(),bulan,tahun,gr,type_sel,$(this).val().split('|')[1])" <?php //echo $_SESSION['user_type']=='Karyawan' ? 'disabled="disabled"':'' ?> >
                                <option value="0" selected="selected">All</option>
                                <?php if(!empty($all_user)){ ?>
                                  
                                  <?php foreach ($all_user as $key => $val) { ?>
                                    <option 
                                      <?php if($user_cur==$val->id){
                                        echo 'selected="selected"';
                                      } ?>
                                      value="<?php echo $val->id; ?>|<?php echo $val->group_name; ?>"><?php echo $val->name; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                          </div> 
                      </div>


                      <div class="row" id="group_div">
                          <label class="col-md-5" style="display:inline-block; width: 150px;">Group</label>
                          <div class="form-group col-md-5">
                              <b id="group_name"></b>
                          </div>
                      </div>

                      <div class="row" id="group_div_g" style="display: none;">
                          <label  class="col-md-5" style="display:inline-block; width: 150px;">Group</label>
                          <div class="form-group col-md-5">
                              <select id="group_sel" class="select2 act form-control" onchange="change(user,bulan,tahun,$(this).val(),type_sel)">
                                <option value="0" selected="selected">All</option>
                                <?php if(!empty($all_group)){ ?>
                                  
                                  <?php foreach ($all_group as $key => $val) { ?>
                                    <option 
                                      <?php if($user_cur==$val->id){
                                        echo 'selected="selected"';
                                      } ?>
                                      value="<?php echo $val->id; ?>"><?php echo $val->group_name; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                          </div> 
                      </div>
                      
                      <div class="row" id="wrapper_select_bulan">
                          <label  class="col-md-5" style="display:inline-block;width: 150px;">Bulan</label>
                          <div class="form-group col-md-5">
                              <select id="bln_sel" class="form-control" onchange="change(user,$(this).val(),tahun,gr,type_sel)" value="2">
                                  <?php $select = 'selected="selected"'; ?>
                                  <option value="0">All</option>
                                  <option <?php echo $bulan_cur==1 ? $select:'' ?> value="1" >Januari</option>
                                  <option <?php echo $bulan_cur==2 ? $select:'' ?> value="2" >Februari</option>
                                  <option <?php echo $bulan_cur==3 ? $select:'' ?> value="3" >Maret</option>
                                  <option <?php echo $bulan_cur==4 ? $select:'' ?> value="4" >April</option>
                                  <option <?php echo $bulan_cur==5 ? $select:'' ?> value="5" >Mei</option>
                                  <option <?php echo $bulan_cur==6 ? $select:'' ?> value="6" >Juni</option>
                                  <option <?php echo $bulan_cur==7 ? $select:'' ?> value="7" >Juli</option>
                                  <option <?php echo $bulan_cur==8 ? $select:'' ?> value="8" >Agustus</option>
                                  <option <?php echo $bulan_cur==9 ? $select:'' ?> value="9" >September</option>
                                  <option <?php echo $bulan_cur==10 ? $select:'' ?> value="10" >Oktober</option>
                                  <option <?php echo $bulan_cur==11 ? $select:'' ?> value="11" >November</option>
                                  <option <?php echo $bulan_cur==12 ? $select:'' ?> value="12" >Desember</option>
                              </select>
                          </div> 
                      </div>

                      <div class="row" id="wrapper_select_tahun">
                          <label  class="col-md-5" style="display:inline-block;width: 150px;">Tahun</label>
                          <div class="form-group col-md-5">
                              <select id="th_sel" class="form-control" onchange="change(user,bulan,$(this).val(),gr,type_sel)">
                                <option value="0" selected="selected">All</option>
                                <?php if(!empty($tahun)){ ?>
                                  
                                  <?php foreach ($tahun as $key => $val) { ?>
                                    <option 
                                      <?php if($tahun_cur==$val->tahun){
                                        echo 'selected="selected"';
                                      } ?>
                                      value="<?php echo $val->tahun; ?>"><?php echo $val->tahun; ?></option>
                                  <?php } ?>
                                <?php } ?>
                              </select>
                          </div> 
                      </div>

                      <div class="row" id="wrapper_select_tanggal" style="display: none;">
                        <label class="col-md-5" style="display: inline-block; width: 150px;">Tanggal</label>
                        <div class="form-group col-md-5">
                          <input type="text" class="form-control datepicker" id="input_tanggal" name="tanggal" required value="<?= $tanggal ?>">
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <div id="submit-filter" class="btn btn-success" 
                              onclick="ch_bulan(
                                          $('#emp_sel').val().split('|')[0],
                                          $('#bln_sel').val(),
                                          $('#th_sel').val(),
                                          $('#group_sel').val(),
                                          type_sel,
                                          $(this),
                                          $('#input_periode').val(),
                                          $('#input_tanggal').val()
                                          )">Submit</div>
                          <a href="javascript:void(0)" onclick="export_pdf_pengambilan_barang(
                            $('#emp_sel').val().split('|')[0],
                            $('#bln_sel').val(),
                            $('#th_sel').val(),
                            $('#group_sel').val(),
                            type_sel,
                            $(this),
                            $('#input_periode').val(),
                            $('#input_tanggal').val()
                          )" class="btn btn-default"><i class="fa fa-file-pdf-o"></i> PDF</a>
                          <a href="javascript:void(0)" onclick="export_excel_pengambilan_barang(
                            $('#emp_sel').val().split('|')[0],
                            $('#bln_sel').val(),
                            $('#th_sel').val(),
                            $('#group_sel').val(),
                            type_sel,
                            $(this),
                            $('#input_periode').val(),
                            $('#input_tanggal').val()
                          )" class="btn btn-default"><i class="fa fa-file-excel-o"></i> EXCEL</a>
                        </div>
                      </div>
                  </div>
                  <br>
              </div>

          </div>
        </div>
    </div>
    <div class="col-md-4">
      <div class="box box-solid box-primary">
        <div class="box-header with-border">
          <i class="fa fa-info"></i>

          <h3 class="box-title">Information</h3>
        </div>
        <div class="box-body">

          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Total Pengambilan</b></span>
              <span id="total_pengambilan" class="info-box-number"></span>
              <span class="info-box-text">Barang</span>
            </div>
            <!-- /.info-box-content -->
          </div>

          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Jenis Barang</b></span>
              <span id="jenis_barang" class="info-box-number"></span>
              <span class="info-box-text">Macam</span>
            </div>
            <!-- /.info-box-content -->
          </div>

          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-bar-chart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><b>Pengambilan Terbanyak</b></span>
              <span id="nama_barang_terbanyak" class="info-box-text"></span>
              <span id="jumlah_terbanyak" class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
          </div>

        </div>
      </div>
    </div>
</div>

<div class="row" >
    <div class="col-md-12">
        <div class="box box-solid box-primary">

          <!-- /.box-header -->
          <div class="box-body">
                <div class="row">
                  <div class="col-md-12" id="content_canvas" style="padding:30px">
                      <canvas id="myChart" width="100%" style="display:none;"></canvas>
                  </div>
                </div>
                <div class="row" id="nodata" style="display: none;">
                  <div class="col-md-12">
                    <center>Tidak Ada Data</center>
                  </div>
                </div>
                <script type="text/javascript">
                  var item_name = <?php echo  $item_name; ?>;
                  var item_value = <?php echo $item_value; ?>;
                  var item_total = <?php echo $item_total; ?>;
                </script>
          </div>
        </div>
        
    </div>
</div>

<style type="text/css">

</style>


<div id='Chartku' style="min-height: 1000px"></div>

<script type="text/javascript">
  
</script>

<div class="modal modal-info fade" id="modal-report-harian" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Export Pengambilan Barang Harian</h4>
      </div>
      <form id="form-export-pengambilan-barang-harian" autocomplete="off" onsubmit="return false;">
        <input type="hidden" name="export_type">
      <div class="modal-body">
         <div class="row">
            <label class="col-sm-4 control-label">Nama Pemohon<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pemohon_nama" name="pemohon_nama" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Jabatan Pemohon<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pemohon_jabatan" name="pemohon_jabatan" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Nama Mengetahui<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mengetahui_nama" name="mengetahui_nama" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Jabatan Mengetahui<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mengetahui_jabatan" name="mengetahui_jabatan" required>
            </div>
          </div><br>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        <button type="submit" id="u_save" class="btn btn-outline">Export</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal modal-info fade" id="modal-report-bulanan" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Export Pengambilan Barang Bulanan</h4>
      </div>
      <form id="form-export-pengambilan-barang-bulanan" autocomplete="off" onsubmit="return false;">
        <input type="hidden" name="export_type">
      <div class="modal-body">
         <div class="row">
            <label class="col-sm-4 control-label">Nama Pelaksana<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pelaksana_nama" name="pelaksana_nama" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Jabatan Pelaksana<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pelaksana_jabatan" name="pelaksana_jabatan" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Nama Saksi<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="saksi_nama" name="saksi_nama" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Jabatan Saksi<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="saksi_jabatan" name="saksi_jabatan" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Nama Mengetahui 1<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mengetahui_nama" name="mengetahui_nama" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Jabatan Mengetahui 1<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mengetahui_jabatan" name="mengetahui_jabatan" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Nama Mengetahui 2<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mengetahui_nama_2" name="mengetahui_nama_2" required>
            </div>
          </div><br>
          <div class="row">
            <label class="col-sm-4 control-label">Jabatan Mengetahui 2<span class="asterisk">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mengetahui_jabatan_2" name="mengetahui_jabatan_2" required>
            </div>
          </div><br>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        <button type="submit" id="u_save" class="btn btn-outline">Export</button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>