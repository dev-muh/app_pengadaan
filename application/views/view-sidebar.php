<?php $userid = !empty($_SESSION['id_user']) ? $_SESSION['id_user']:''; ?>
<script>
  var URL = '<?php echo base_url(); ?>';
  var active_page = '<?php echo $s_active; ?>';
  var mode = '<?php echo $mode; ?>';
  var sesi = '<?php echo $_SESSION['user_type']; ?>';
  var userid = '<?php echo $_SESSION['id_user']; ?>';

  var view_keranjang_tahun = function(){
      return <?php 
        if(!empty($_GET['tahun'])){
            echo $_GET['tahun'];
        }else{
            echo date('Y');
        }
      ?>
  }

  var current_tahun = function(){
      return <?php 
        echo date('Y');
      ?>
  }

</script>



<ul class="sidebar-menu tree" data-widget="tree">
  <li class="header" id="cur_time">Time : </li>
  <li class="header">MAIN NAVIGATION</li>
  <?php if($user=='Super Admin'||$user=='Admin TOFAP'||$user=='Admin'||$user=='Admin Gudang'||$user=='Approval'||$user=='Admin Pemesanan'||$user=='Admin Penerimaan'||$user=='Admin Pengadaan'||$user=='Admin ATK'){ ?>
    <li onclick="beta()"><a href="#"><i class="fa fa-pie-chart"></i> <span>Dashboard</span></a></li>
  <?php } ?>

  <?php if($user=='Super Admin'||$user=='Admin TOFAP'||$user=='Admin'||$user=='Karyawan'||$user=='Kurir'||$user=='Admin ATK'||$user=='Admin Gudang'){ ?>
      <li class="<?php echo $s_active=='pemesanan' ? 'active':''; ?>"><a href="<?php echo base_url('transaksi/order_atk/view'); ?>"><i class="glyphicon glyphicon-shopping-cart"></i> <span>Keranjang</span> <span style="display: <?php echo $badge[0]->keranjang>0 ? 'block':'none'; ?>" class="label label-primary pull-right" id="badge_keranjang"><?php echo $badge[0]->keranjang; ?></span> </a></li>
  <?php } ?>



  <?php if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin' || $user == 'Approval'|| $user == 'Admin Penerimaan' || $user == 'Admin Gudang'||$user=='Admin Gudang'||$user=='Admin Pemesanan'||$user=='Admin Pengadaan'){ ?>
      <?php if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin' || $user == 'Approval'|| $user == 'Admin Gudang'||$user=='Admin Gudang'||$user=='Admin Pemesanan'||$user=='Admin Penerimaan'){ ?>
        <li class="<?php echo $s_active=='pengajuan' ? 'active':''; ?>"><a href="<?php echo base_url('transaksi/trx/view'); ?>"><img width="0px" src="<?php echo base_url('assets/img/icons/ICON_PERMINTAAN.png'); ?>" style="width: 15px; height: 15px; filter: invert(0.8); margin-left: 0px; margin-right: 5px;"> <span>Permintaan</span><span style="display: <?php echo $badge[0]->permintaan>0 ? 'block':'none'; ?>" class="label label-primary pull-right" id="badge_permintaan"><?php echo $badge[0]->permintaan; ?></span></a></li>
      <?php } ?>

      <?php if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin'||$user=='Admin Pemesanan'||$user=='Admin Gudang'||$user=='Admin Penerimaan'||$user=='Admin Pengadaan'||$user=='Approval'){ ?>
          <li class="<?php echo $s_active=='pemesanan_brg' ? 'active':''; ?>"><a href="<?php echo base_url('transaksi/pemesanan_brg/view'); ?>"><img width="0px" src="<?php echo base_url('assets/img/icons/ICON_PEMESANAN.png'); ?>" class="custom-icon"> <span>Pemesanan</span><span style="display: <?php echo $badge[0]->pemesanan>0 ? 'block':'none'; ?>" class="label label-primary pull-right" id="badge_pemesanan"><?php echo $badge[0]->pemesanan; ?></span></a></li>
      <?php } ?>
      <?php if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin' || $user == 'Admin Penerimaan'|| $user == 'Admin Pengadaan'||$user=='Admin Gudang'||$user=='Admin Pemesanan'||$user=='Approval'){ ?>
          <li class="<?php echo $s_active=='penerimaan' ? 'active':''; ?>"><a href="<?php echo base_url('transaksi/penerimaan_brg/view'); ?>"><img width="0px" src="<?php echo base_url('assets/img/icons/ICON_PENERIMAAN.png'); ?>" class="custom-icon"> <span>Penerimaan</span><span style="display: <?php echo $badge[0]->penerimaan>0 ? 'block':'none'; ?>" class="label label-primary pull-right" id="badge_penerimaan"><?php echo $badge[0]->penerimaan; ?></span></a></li>
      <?php } ?>
      <?php if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin' || $user == 'Admin Penerimaan'|| $user == 'Admin Pengadaan'||$user=='Admin Gudang'||$user=='Admin Pemesanan'||$user=='Approval'){ ?>
          <li class="<?php echo $s_active=='bast_penerimaan' ? 'active':''; ?>"><a href="<?php echo base_url('transaksi/bast_penerimaan_brg/view'); ?>"><i class="glyphicon glyphicon glyphicon-certificate"></i> <span>BAST Penerimaan</span><span style="display: <?php // echo $badge[0]->penerimaan>0 ? 'block':'none'; ?>" class="label label-primary pull-right" id="badge_bast_penerimaan"><?php // echo $badge[0]->penerimaan; ?></span></a></li>
      <?php } ?>
  <?php } ?>

  <?php //if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin' || $user == 'Karyawan'){ ?>
      <li class="treeview <?php echo $s_active=='report' || $s_active=='report_sla' || $s_active=='report_sla_penerimaan' || $s_active=='report_emp_group' || $s_active=='report_fifo'|| $s_active=='report_fifo_sum' || $s_active=='report_fifo_tahunan' || $s_active=='report_keranjang' ? 'active':''; ?>">
          <a href="#">
            <img width="0px" src="<?php echo base_url('assets/img/icons/ICON_LAPORAN.png'); ?>" class="custom-icon"> <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php //if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin'){ ?>
                <li class="<?php echo $s_active=='report_keranjang'? 'active':''; ?>"><a href="<?php echo base_url('report/history_keranjang'); ?>"><i class="fa fa-circle-o"></i> History Keranjang</a></li>
                <li class="<?php echo $s_active=='report_emp_group'? 'active':''; ?>"><a href="<?php echo base_url('report/log_by_karyawan?by=0&bulan=' . date('m') . '&tahun=' . date('Y') . '&group=0&type=employee') . '&periode=Bulanan&tanggal=' . date('d/m/Y'); ?>"><i class="fa fa-circle-o"></i> Pengambilan Barang</a></li>

                <li class="<?php echo $s_active=='report_fifo' ? 'active':''; ?>"><a href="<?php echo base_url('report/fifo_bulanan'); ?>"><i class="fa fa-circle-o"></i> FIFO Bulanan</a></li>
                <?php $cur_bulan = date('n'); ?>
                <?php $cur_tahun = date('Y'); ?>

                <li class="<?php echo $s_active=='report_fifo_tahunan' ? 'active':''; ?>"><a href="<?php echo base_url('report/fifo_tahunan'); ?>"><i class="fa fa-circle-o"></i> FIFO Tahunan</a></li>
                <li class="<?php echo $s_active=='report_fifo_sum' ? 'active':''; ?>"><a href="<?php echo base_url('report/summary'); ?>"><i class="fa fa-circle-o"></i> FIFO Summary</a></li>
                <li class="<?php echo $s_active=='report_sla' ? 'active':''; ?>"><a href="<?php echo base_url('report/reportsla'); ?>"><i class="fa fa-circle-o"></i> Service Level Pengambilan</a></li>
                <li class="<?php echo $s_active=='report_sla_penerimaan' ? 'active':''; ?>"><a href="<?php echo base_url('report/sla_penerimaan'); ?>"><i class="fa fa-circle-o"></i> Service Level Penerimaan</a></li>
            <?php //} ?>
            <!-- <?php //if($user == 'Karyawan'){ ?>
                <li class="<?php echo $s_active=='report_emp_group'? 'active':''; ?>"><a href="<?php echo base_url('report/log_by_karyawan?by='. $userid . '&bulan=0&tahun=0&group=0&type=employee'); ?>"><i class="fa fa-circle-o"></i> Laporan Karyawan / Group</a></li>
            <?php //} ?> -->
            <!-- <li class="<?php echo $s_active=='sub_kategori'? 'active':''; ?>"><a href="<?php echo base_url('produk/sub_kategori/view'); ?>"><i class="fa fa-circle-o"></i> Sub Kategori</a></li>
            <li class="<?php echo $s_active=='item'? 'active':''; ?>"><a href="<?php echo base_url('produk/item/view'); ?>"><i class="fa fa-circle-o"></i> Item</a></li> -->
          </ul>
      </li>
  <?php //} ?>

  <?php if($user == 'Super Admin'||$user == 'Admin TOFAP'){ ?>
      <li class="<?php echo $s_active=='user' ? 'active':''; ?>"><a href="<?php echo base_url('customer/view'); ?>"><i  class="fa fa-user"></i>User Management</a></li>
      <li class="<?php echo $s_active=='tandatangan' ? 'active':''; ?>">
        <a href="<?php echo base_url('tandatangan'); ?>">
          <i class="fa fa-user"></i>TTD
        </a>
      </li>
  <?php } ?>
  <?php if($user == 'Super Admin'||$user == 'Admin TOFAP'||$user=='Admin Gudang' || $user=='Admin'|| $user=='Approval'||$user=='Admin Pemesanan'||$user=='Admin Penerimaan'||$user=='Approval'){ ?>
      <li class="treeview <?php echo  $s_active=='master_supplier' || 
                                      $s_active=='master_harga' ||
                                      $s_active=='master_group' ||
                                      $s_active=='kategori' || 
                                      $s_active=='sub_kategori' || 
                                      $s_active=='item'
                                      ? 'active':''; ?>">
        <a href="#">
          <i class="fa fa-shopping-bag"></i> <span>Master Data</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?php echo $s_active=='master_supplier'? 'active':''; ?>"><a href="<?php echo base_url('master/master_supplier_all'); ?>"><i class="fa fa-circle-o"></i> Master Supplier</a></li>
          <li class="<?php echo $s_active=='master_harga'? 'active':''; ?>"><a href="<?php echo base_url('master/master_harga_all'); ?>"><i class="fa fa-circle-o"></i> Master Harga</a>
          </li>
          <li class="<?php echo $s_active=='master_group'? 'active':''; ?>"><a href="<?php echo base_url('master/master_group'); ?>"><i class="fa fa-circle-o"></i> Master Group</a></li>
            <?php if($user == 'Super Admin' ||$user == 'Admin TOFAP' || $user == 'Admin'||$user=='Admin Gudang'){ ?>
          <li class="treeview <?php echo $s_active=='kategori' || $s_active=='sub_kategori' || $s_active=='item' ? 'active':''; ?>">
            <a href="#">
              <i class="fa fa-shopping-bag"></i> <span>Produk</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="<?php echo $s_active=='kategori'? 'active':''; ?>"><a href="<?php echo base_url('produk/kategori/view'); ?>"><i class="fa fa-circle-o"></i> Kategori</a></li>
              <li class="<?php echo $s_active=='sub_kategori'? 'active':''; ?>"><a href="<?php echo base_url('produk/sub_kategori/view'); ?>"><i class="fa fa-circle-o"></i> Sub Kategori</a></li>
              <li class="<?php echo $s_active=='item'? 'active':''; ?>"><a href="<?php echo base_url('produk/item/view'); ?>"><i class="fa fa-circle-o"></i> Item</a></li>
            </ul>
          </li>
      <?php } ?>
        </ul>
      </li>
  <?php } ?>

  


</ul>

<script>
  function beta(){
    alert('Maaf, halaman belum bisa dibuka');
  }

  var del_index = 1;

  setTimeout(function(){
      if(typeof(EventSource) !== "undefined") {
          var source = new EventSource(URL+"server/cur_time");

          source.addEventListener("current_time",function(event){
              $('#cur_time').html(event.data);
          });
          source.addEventListener("badge",function(event){
              let res = JSON.parse(event.data);

              if(res[0].keranjang>0){
                  $('#badge_keranjang').show();
              }else{
                  $('#badge_keranjang').hide();
              }

              if(res[0].permintaan>0){
                  $('#badge_permintaan').show();
              }else{
                  $('#badge_permintaan').hide();
              }

              if(res[0].pemesanan>0){
                  $('#badge_pemesanan').show();
              }else{
                  $('#badge_pemesanan').hide();
              }

              if(res[0].penerimaan>0){
                  $('#badge_penerimaan').show();
              }else{
                  $('#badge_penerimaan').hide();
              }

              $('#badge_keranjang').html(res[0].keranjang);
              $('#badge_permintaan').html(res[0].permintaan);
              $('#badge_pemesanan').html(res[0].pemesanan);
              $('#badge_penerimaan').html(res[0].penerimaan);

          });

          if(active_page=='pemesanan' && mode=='view' && view_keranjang_tahun()==current_tahun()){
              source.addEventListener("tb_pemesanan",function(event){
                  try{

                      var ver_tb_pem = trigger_sse();
                      console.log(trigger_sse(1));

                      // alert(pemesanan_tb.rows().data().length);
                      // console.log(ver_tb_pem);
                      if(event.data==ver_tb_pem){
                          console.log('on');
                      }else{
                          var arr_send_pem = [];
                          var arr_pem = pemesanan_tb.data().toArray();
                          // console.log(arr_pem);
                          $.each(arr_pem,function(key,val){
                              arr_send_pem.push(val[0]);
                          });


                          $.post(URL+'server/get_data_tb_pemesanan',{data:arr_send_pem}).done(function(data){
                              var res = JSON.parse(data);

                              $.each(res,function(key,val){

                                  var btn_act = $.ajax({
                                      type: "GET",
                                      url: URL+'mobile/btn_pemesanan',
                                      data: {
                                        id_pem:val.id_pemesanan,
                                        user_type:sesi,
                                        status:val.status,
                                        id_kurir:val.id_kurir,
                                        no_pem:val.no_pemesanan
                                      },
                                      dataType: 'json',
                                      async: false,
                                      success: function (result) {
                                          return result;
                                      },
                                      error: function (result) {
                                          // code here
                                          return result;
                                      }
                                  }).responseText;                                  
                                  
                                  if(pemesanan_tb.rows().data().length>=100){
                                      pemesanan_tb.row(pemesanan_tb.rows().data().length-del_index).remove().draw(false);
                                  }

                                  pemesanan_tb.row.add([
                                      val.no_pemesanan,
                                      val.pemesan,
                                      val.group_name,
                                      val.lantai,
                                      format_jam(val.tgl_pemesanan),
                                      val.kurir,
                                      val.txt_status,
                                      val.rating,
                                      val.komentar,
                                      btn_act,
                                      val.status,
                                      val.id_pemesanan
                                  ]).column([10,11]).visible(false).draw(false);

                                  if(pemesanan_tb.rows().data().length>=100){
                                      del_index++;
                                  }


                                  



                                  var nodes = pemesanan_tb.column(0).nodes();
                                  pemesanan_tb.rows(pemesanan_tb.data().length-1).nodes().to$().addClass( 'id-'+val.id_pemesanan +' highlight' );

                                  // var elem = $('tr.highlight');
                                  // if(elem) {
                                  //     $('html').scrollTop(elem.offset().top);
                                  //     $('html').scrollLeft(elem.offset().left);
                                  // }

                                  $('.highlight').css('background-color','green');
                                  $( ".highlight" ).animate({
                                    backgroundColor:'none'
                                  }, 1000, function() {
                                    $('.highlight').parent().find('tr').removeAttr('style');
                                    $('.highlight').parent().find('tr').removeClass('highlight');

                                  });

                                  $.toast({
                                      heading: 'Order ATK baru.',
                                      text: 'Pemesanan dari <b>'+val.pemesan+'</b> dengan nomor <b>'+ val.no_pemesanan+ '</b> telah ditambahkan.',
                                      showHideTransition: 'slide',
                                      position:'bottom-right',
                                      icon: 'success',
                                      hideAfter:8000,
                                      stack:5
                                  });
                              });

                              
                          });
                      }
                  }catch(e){
                      console.log(e);
                  }
              });

              source.addEventListener("status_pemesanan",function(event){
                  var status_pemesanan = trigger_status_pemesanan();

                  if(status_pemesanan==event.data){
                  }else{
                      getDataStatus();
                  }
              });

              // source.addEventListener("rating_pemesanan",function(event){
              //     var rating_pemesanan = trigger_rating_pemesanan();

              //     if(rating_pemesanan==event.data){
              //     }else{
              //         getDataRating();
              //     }
              // });
          }

      } else {
        document.getElementById("cur_time").innerHTML = "Sorry, your browser does not support server-sent events...";
      }
  },3000);
</script>