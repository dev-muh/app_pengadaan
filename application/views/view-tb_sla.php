<?php
    function tformat($x=null){
        if($x==null){
            return '';
        }else{
            $date = new DateTime($x);
            return date_format($date,"d F Y, H:i:s \W\I\B"); 
        }
    }   

    $this->load->model('model_server');
    $val['badge'] = $this->Model_server->badge();

?>

<?php if(!empty($log)){ ?>


<div class="row">
  <div class="col-md-4">
    <div class="info-box">

      <!-- Apply any bg-* class to to the icon to color it -->
      <span class="info-box-icon bg-red"><i class="fa fa-list"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Keranjang</span>
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
        <th>No. Keranjang</th>
        <th>Waiting Approval</th>
        <th>Order Received</th>
        <th>Courier Assigned</th>
        <th>Prepare Item</th>
        <th>Courier On The Way</th>
        <th>Done</th>
        <th>Service Level</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($log)){ ?>

      <?php }else{ ?>
          <?php $no=1; foreach ($log as $key => $value) { ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $value->no_pemesanan; ?></td>
                <td><?php echo tformat($value->waiting_approval); ?></td>
                <td><?php echo tformat($value->order_received); ?></td>
                <td><?php echo tformat($value->courier_assigned); ?></td>
                <td><?php echo tformat($value->prepare_item); ?></td>
                <td><?php echo tformat($value->courier_on_the_way); ?></td>
                <td><?php echo tformat($value->done); ?></td>
                <td><?php echo $value->service_level; ?></td>
              </tr>
          <?php $no++; } ?>
      <?php } ?>
    </tbody>
</table>

<?php }else{ ?>
    <center><h5>Data kosong</h5></center>
<?php } ?>


<script type="text/javascript">
  $(function(){
    $('#tb_sla_res').DataTable();
  });
</script>


