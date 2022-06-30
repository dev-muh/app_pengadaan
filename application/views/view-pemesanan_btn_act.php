<button data-toggle="tooltip" title="Lihat Rincian" class="btn btn-default btn-sm ps_view" onclick="sh_pemesanan(<?php echo $id_pem; ?>)">
          
          <span class="glyphicon glyphicon-fullscreen"></span>
  </button>

  <?php if($user=='Super Admin'||$user=='Admin TOFAP'||$user=='Karyawan'||$user=='Admin ATK'||$user=='Admin Gudang'){ ?>
    <?php if($s==0 || $user=='Admin Gudang' || $user=='Super Admin'){ ?>
      <!-- <a href="<?php echo base_url('transaksi/order_atk/edit/') . $id_pem; ?>"> -->
        <button onclick="ck_stat_pem(<?php echo $id_pem; ?>)" data-toggle="tooltip" title="Ubah" class="btn btn-sm btn-warning ps_edit" >
                <span class="glyphicon glyphicon-edit"></span>
        </button>
      <!-- </a> -->
    <?php } ?>
  <?php } ?>

  <?php if($user=='Super Admin'||$user=='Admin TOFAP'||$user=='Admin ATK'||$user=='Admin Gudang'){ ?>
    <?php if($s==0){ ?>
      <!-- <button data-toggle="tooltip" title="Setuju" class="btn btn-sm btn-success ps_ch_stat" onclick="acc_order(<?php echo $id_pem; ?>)">
            <span class="glyphicon glyphicon-check"></span>
      </button> -->
      <button data-toggle="tooltip" title="Setuju" class="btn btn-sm btn-success ps_ch_stat" onclick="acc_order(<?php echo $id_pem; ?>)">
            <span class="glyphicon glyphicon-check"></span>
      </button>
    <?php } ?>
  <?php } ?>
  
  <?php if($user=='Super Admin'||$user=='Admin TOFAP'||$user=='Admin ATK'||$user=='Admin Gudang'){ ?>
    <?php if($s==1){ ?>
      <button  class="csstooltip btn btn-sm btn-primary ps_kurir" value="<?php echo $id_pem; ?>" data-toggle="popover" data-title="List Kurir" data-placement="left" data-trigger="focus" onclick="pil_kurir($(this))">
        <span class="tooltiptext">Pilih Kurir</span>
              <span class="fa fa-truck"></span>
      </button>
    <?php } ?>
    

    <?php if($s>1 && $s<5){ ?>
        <button class="csstooltip btn btn-sm bg-aqua" onclick="ch_stat_pem($(this))" data-toggle="popover"  data-placement="left" data-trigger="focus" value="<?php echo $id_pem; ?>">
          <span class="tooltiptext" style="width: 300%;">Change Status</span>
          <span class="glyphicon glyphicon-send"></span>
        </button>
    <?php } ?>

    <?php if($s<5){ ?>
        <button class="csstooltip btn btn-sm btn-danger" onclick="cancel_order(<?php echo $id_pem; ?>)" data-toggle="popover"  data-placement="left" data-trigger="focus">
          <span class="tooltiptext" style="width: 300%;">Cancel Order</span>
          <span class="glyphicon glyphicon-remove-circle"></span>
        </button>
    <?php } ?>
  <?php } ?>

  <?php if($s==1&&$user=='Kurir'){ ?>
      <button  class="csstooltip btn btn-sm btn-success" data-title="Get Order" data-placement="left" data-trigger="focus" onclick="assign_courier(<?php echo $id_pem; ?>,<?php echo $_SESSION['id_user']; ?>)">
              <span class="fa fa-truck"></span>
      </button>
  <?php } ?>

  <?php if($s==5 && empty($value->rating)){ ?>
    <?php if(1+1==4){ ?>
      <button class="csstooltip btn btn-sm bg-black" onclick="rating(<?php echo $id_pem; ?>,<?php echo $_SESSION['id_user']; ?>,<?php echo !empty($value->id_kurir) ? $value->id_kurir:$id_kurir ; ?>,'<?php echo !empty($value->no_pemesanan)?$value->no_pemesanan:$no_pemesanan; ?>')">
        <span class="tooltiptext" style="width: 300%;">Rating</span>
        <span class="glyphicon glyphicon-star" style="color:yellow"></span>
      </button>
    <?php } ?>
    <!-- <button class="csstooltip btn btn-sm bg-black" onclick="rating(<?php echo $id_pem; ?>,<?php echo $_SESSION['id_user']; ?>,'','<?php echo !empty($value->no_pemesanan)?$value->no_pemesanan:$no_pemesanan; ?>')">
        <span class="tooltiptext" style="width: 300%;">Rating</span>
        <span class="glyphicon glyphicon-star" style="color:yellow"></span>
      </button> -->
  <?php } ?>

  <?php if($s==5 && empty($value->rating_gudang)){ ?>
    <button class="csstooltip btn btn-sm bg-black" onclick="rating_pelayanan_admin_gudang(<?php echo $id_pem; ?>,<?php echo $_SESSION['id_user']; ?>,'','<?php echo !empty($value->no_pemesanan)?$value->no_pemesanan:$no_pemesanan; ?>')">
        <span class="tooltiptext" style="width: 300%;">Rating</span>
        <span class="glyphicon glyphicon-star" style="color:yellow"></span>
      </button>
  <?php } ?>