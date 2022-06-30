<script type="text/javascript">
    var id_item = <?php echo !empty($_GET['item']) ? $_GET['item']:'""'; ?>;
</script>
<?php $bulan_cur = !empty($_GET['bulan']) ? $_GET['bulan']:''; ?>

<?php 
    function nf($x){
        return "Rp. ".number_format($x,0,",",".").',-';
    } 

    function bl($bulan=null){
        $b = [
                    'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                ];

        if($bulan<=0){
            return $b[(12+($bulan))-1];
        }else{
            return $b[$bulan-1];
        }
    }
?>

<center>
    <h2><?php echo $page_title; ?></h2>
    <hr style="border-top: 3px double #8c8b8b;">
</center>

<div class="row" >
    <div class="col-md-12" >
        <div class="box">
            <div class="box-body">
                <div id="opt" class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="sel_item">Pilih Barang</label>
                                <select id="sel_item" class="select2 act">
                                    <option disabled="disabled" selected="selected">Pilih Barang</option>
                                    <?php foreach ($ls_item as $key => $value) { ?>
                                        <option value=<?php echo $value->ID_ITEM; ?>><?php echo $value->nama_item; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <?php if($s_active=='report_fifo'){ ?>
                                <div class="form-group">
                                    <label for="sel_item">Pilih Bulan</label>
                                    <div class="form-group">
                                        <select id="bln_sel" class="form-control">
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
                            <?php } ?>
                            
                            <?php $cur_tahun = !empty($_GET['tahun']) ? $_GET['tahun'] : date('Y'); ?>

                            <div class="form-group"> 
                                <label for="sel_item">Pilih Tahun</label>
                                <select class="form-control" id="tahun_sel">
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
                        </div>
                    </div> 

                    <?php if($s_active=='report_fifo'){ ?>
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <button class="btn btn-success" onclick="ch_it($('#sel_item').val(),$('#bln_sel').val(),$('#tahun_sel').val())">Submit</button>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if($s_active=='report_fifo_tahunan'){ ?>
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <button class="btn btn-success" onclick="ch_it_tahunan($('#sel_item').val(),$('#tahun_sel').val())">Submit</button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <br>
            </div>   
        </div>
    </div>
</div>

<?php if($s_active=='report_fifo'){ ?>
<div class="row" id="res_fifo" style="display: none;">
    <div class="col-md-12" >
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Fifo Bulanan</h3>&nbsp;&nbsp;<i id="loading_ctrl" class="fa fa-refresh" style="display: none;"></i>
              <a href="javascript:void(0)" onclick="export_pdf_fifo_bulanan($('#sel_item').val(), $('#tahun_sel').val(),$('#bln_sel').val())" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> PDF</a>
              <a href="javascript:void(0)" onclick="export_excel_fifo_bulanan($('#sel_item').val(), $('#tahun_sel').val(),$('#bln_sel').val())" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i> EXCEL</a>
            </div>
            <div class="box-body" id="tb_res">

            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($s_active=='report_fifo_tahunan'){ ?>
    <div class="row" id="res_fifo" style="display: none;">
        <div class="col-md-12" >
            <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Fifo Tahunan</h3>&nbsp;&nbsp;<i id="loading_ctrl" class="fa fa-refresh" style="display: none;"></i>
                  <a href="javascript:void(0)" onclick="export_pdf_fifo_tahunan($('#sel_item').val(), $('#tahun_sel').val())" class="btn btn-sm btn-default"><i class="fa fa-file-pdf-o"></i> PDF</a>
                  <a href="javascript:void(0)" onclick="export_excel_fifo_tahunan($('#sel_item').val(), $('#tahun_sel').val())" class="btn btn-sm btn-default"><i class="fa fa-file-excel-o"></i> EXCEL</a>
                </div>
                <div class="box-body" id="tb_res">

                </div>
            </div>
        </div>
    </div>
<?php } ?>

<br>

<!-- <div class="row">
    <div class="col-md-12">
        <table id="tb_out" class="table table-bordered table-hover dt-responsive cell-border" style="background-color: white;">
            <thead>
                <tr style="background-color: #169eda; color:white;">
                    <th></th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Biaya</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($log)){ ?>
                    <tr>
                        <td>Sub Total</td>
                        <td><?php echo !empty($log['in']['sub_total']['qty']) ? $log['in']['sub_total']['qty']:''; ?></td>
                        <td></td>
                        <td class="r"><?php echo !empty($log['in']['sub_total']['harga']) ? nf($log['in']['sub_total']['harga']):''; ?></td>
                    </tr>

                    <?php if(!empty($log['out']['pengambilan'])){ ?>
                        <?php foreach ($log['out']['pengambilan'] as $key => $val) { ?>
                            <tr>
                                <td>Pengambilan <?php echo $val['bulan']; ?></td>
                                <td><?php echo $val['qty']; ?></td>
                                <td class="r"><?php echo nf($val['harga']); ?></td>
                                <td class="r"><?php echo nf($val['harga']*$val['qty']); ?></td>
                            </tr>
                        <?php } ?> 
                    <?php } ?>

                    <?php if(!empty($log['out']['saldo_akhir'])){ ?>
                        <?php foreach ($log['out']['saldo_akhir'] as $key => $val) { ?>
                            <tr>
                                <td>Saldo Akhir <?php echo $val['bulan']; ?></td>
                                <td><?php echo $val['qty']; ?></td>
                                <td class="r"><?php echo nf($val['harga']); ?></td>
                                <td class="r"><?php echo nf($val['harga']*$val['qty']); ?></td>
                            </tr>
                        <?php } ?>   
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div> -->


<style type="text/css">
    .r{
        text-align: right;
    }
    .c{
        text-align: center;
    }
    .tb-c th{
        text-align: center;
    }
</style>

<script type="text/javascript">
    // $('#tb_in').DataTable();
</script>