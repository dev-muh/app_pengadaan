<?php
    function tformat($x=null){
        if($x==null){
            return '';
        }else{
            $date = new DateTime($x);
            return date_format($date,"d F Y, H:i:s \W\I\B"); 
        }
    }   
?>

<?php if($jenis_sla=='permintaan'){ ?>
    <?php if(!empty($log)){ ?>
        <div class="row">
          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-red"><i class="fa fa-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Permintaan</span>
                <span class="info-box-number"><?php echo count($log); ?> Order(s)</span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-purple"><i class="fa fa-calendar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Service Level</span>
                <span class="info-box-number"><?php echo $time_service; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-blue"><i class="fa fa-area-chart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Level Average</span>
                <span class="info-box-number"><?php echo $avg_time_service; ?></span>
              </div>
              
              <!-- /.info-box-content -->
            </div>
          </div>
        </div>

        <hr>

        <table id="tb_sla_res" class="table table-bordered table-hover dt-responsive cell-border">
            <thead>
              <tr style="background-color: #4F81BD; color: white;">
                <th>No.</th>
                <th>No. Permintaan</th>
                <th>Pending</th>
                <th>Accept</th>
                <th>Service Level</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($log)){ ?>

              <?php }else{ ?>
                  <?php $no=1; foreach ($log as $key => $value) { ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $value->no_permintaan; ?></td>
                        <td><?php echo tformat($value->submit_date); ?></td>
                        <td><?php echo tformat($value->approve_date); ?></td>
                        <td><?php echo $value->service_level; ?></td>
                      </tr>
                  <?php $no++; } ?>
              <?php } ?>
            </tbody>
        </table>
    <?php }else{ ?>
        <center><h5>Data kosong</h5></center>
    <?php } ?>
<?php } ?>


<!-- ################################################################################# -->

<?php if($jenis_sla=='pemesanan'){ ?>
    <?php if(!empty($log)){ ?>
        <div class="row">
          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-red"><i class="fa fa-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Pemesanan</span>
                <span class="info-box-number"><?php echo count($log); ?> Order(s)</span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-purple"><i class="fa fa-calendar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Service Level</span>
                <span class="info-box-number"><?php echo $time_service; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-blue"><i class="fa fa-area-chart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Level Average</span>
                <span class="info-box-number"><?php echo $avg_time_service; ?></span>
              </div>
              
              <!-- /.info-box-content -->
            </div>
          </div>
        </div>

        <hr>

        <table id="tb_sla_res" class="table table-bordered table-hover dt-responsive cell-border">
            <thead>
              <tr style="background-color: #4F81BD; color: white;">
                <th>No.</th>
                <th>No. Permintaan</th>
                <th>Open</th>
                <th>Closed</th>
                <th>Service Level</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($log)){ ?>

              <?php }else{ ?>
                  <?php $no=1; foreach ($log as $key => $value) { ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $value->no_permintaan; ?></td>
                        <td><?php echo tformat($value->approve_date); ?></td>
                        <td><?php echo tformat($value->closed_date); ?></td>
                        <td><?php echo $value->service_level; ?></td>
                      </tr>
                  <?php $no++; } ?>
              <?php } ?>
            </tbody>
        </table>
    <?php }else{ ?>
        <center><h5>Data kosong</h5></center>
    <?php } ?>
<?php } ?>


<!-- ################################################################################ -->

<?php if($jenis_sla=='penerimaan'){ ?>
    <?php if(!empty($log)){ ?>
        <div class="row">
          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-red"><i class="fa fa-list"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Penerimaan</span>
                <span class="info-box-number"><?php echo count($log); ?> Order(s)</span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-purple"><i class="fa fa-calendar"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Service Level</span>
                <span class="info-box-number"><?php echo $time_service; ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box">

              <!-- Apply any bg-* class to to the icon to color it -->
              <span class="info-box-icon bg-blue"><i class="fa fa-area-chart"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Level Average</span>
                <span class="info-box-number"><?php echo $avg_time_service; ?></span>
              </div>
              
              <!-- /.info-box-content -->
            </div>
          </div>
        </div>

        <hr>

        <table id="tb_sla_res" class="table table-bordered table-hover dt-responsive cell-border">
            <thead>
              <tr style="background-color: #4F81BD; color: white;">
                <th>No.</th>
                <th>No. SPB</th>
                <th>Open</th>
                <th>Closed</th>
                <th>Service Level</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($log)){ ?>

              <?php }else{ ?>
                  <?php $no=1; foreach ($log as $key => $value) { ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo $value->no_spb; ?></td>
                        <td><?php echo tformat($value->open); ?></td>
                        <td><?php echo tformat($value->closed); ?></td>
                        <td><?php echo $value->service_level; ?></td>
                      </tr>
                  <?php $no++; } ?>
              <?php } ?>
            </tbody>
        </table>
    <?php }else{ ?>
        <center><h5>Data kosong</h5></center>
    <?php } ?>
<?php } ?>


<script type="text/javascript">
  $(function(){
    $('#tb_sla_res').DataTable();
  });
</script>


