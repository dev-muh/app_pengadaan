<?php $bulan_cur = !empty($_GET['bulan']) ? $_GET['bulan']:''; ?>
<script type="text/javascript">
    var bulan = "<?php echo !empty($_GET['bulan']) ? $_GET['bulan']:''; ?>";
    var tahun = "<?php echo !empty($_GET['tahun']) ? $_GET['tahun']:''; ?>";
</script>


<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sel_item">Pilih Bulan</label>
                        <div class="form-group">
                            <select id="bln_sel" class="form-control" >
                              <?php $select = 'selected="selected"'; ?>
                                <!-- <option value="0">All</option> -->
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
                    <?php $cur_tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : date('Y'); ?>
                    <div class="form-group">
                        <label for="sel_item">Pilih Tahun</label>
                        <select class="form-control" id="sel_th">
                             <?php if(!empty($tahun)){ ?>
                                  
                                  <?php foreach ($tahun as $key => $val) { ?>
                                    <option 
                                      <?php if($cur_tahun==$val->tahun){
                                        echo 'selected="selected"';
                                      } ?>
                                      value="<?php echo $val->tahun; ?>"><?php echo $val->tahun; ?></option>
                                  <?php } ?>
                                <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-success" onclick="ch($('#bln_sel').val(),$('#sel_th').val())">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-calculator"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Saldo</span>
                <span id="jumlah_saldo" class="info-box-number"></span>
                
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-credit-card"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Biaya Saldo</span>
                <span id="biaya_saldo" class="info-box-text" style="font-weight: bold;"></span>
                
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-shopping-cart"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jumlah Pengambilan</span>
                <span id="jumlah_pengambilan" class="info-box-number"></span>
                
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Biaya Pengambilan</span>
                <span id="biaya_pengambilan" class="info-box-text" style="font-weight: bold;"></span>
                
            </div>
        </div>
    </div>
</div>

<div class="row" id="row_res" style="display: none;">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">FIFO Summary</h3>&nbsp;&nbsp;<i id="loading_ctrl" class="fa fa-refresh" style="display: none;"></i>
              <a href="javascript:void(0)" onclick="export_pdf_fifo_summary($('#bln_sel').val(),$('#sel_th').val())" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> PDF</a>
              <a href="javascript:void(0)" onclick="export_excel_fifo_summary($('#bln_sel').val(),$('#sel_th').val())" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i> EXCEL</a>
            </div>
            <div class="box-body" id="res_sum">

            </div>
        </div>
    </div>
</div>
