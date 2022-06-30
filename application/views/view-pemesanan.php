  <script >
    var URL = '<?php echo base_url(); ?>';
    var arr_cs = [];
    var m = '<?php echo $mode; ?>';
  </script>
  <div class="row">
    <div class="col-md-12 ">
      <div id="table-wrapper">
          <center>
            <h2><?php echo $page_title; ?></h2>
            <hr style="border-top: 3px double #8c8b8b;">
          </center>     

          <?php if($mode=='view'){ ?>
            <?php if($user=='Super Admin'||$user=='Admin TOFAP'||$user=='Admin'||$user=='Karyawan'||$user=='Admin ATK'||$user=='Admin Gudang'){ ?>
              <?php if($act_button=='pemesanan'){ ?>
                  <!-- <a href=""> -->
                    <button <?php // echo ($jml_rate[0]->jml_rate==0&&$jml_rate[0]->pem_process==0) ? '':'disabled'; ?> type="button" class="btn btn-success btn-sm" onclick="window.location.replace('<?php echo base_url('transaksi/order_atk/add'); ?>')">
                      <span class="glyphicon glyphicon-plus"></span>
                      <span class="glyphicon glyphicon-shopping-cart" style="font-size: 20px"></span>
                    </button>
                  <!-- </a> -->
                  <br><br> 
              <?php } ?>
            <?php } ?> 

              <!-- <div class="box box-solid box-primary">
                  <div class="box-header">
                      <h3 class="box-title">Filter</h3>
                  </div>
                  <div class="box-body">
                      <div class='col-sm-6'>
                          <div class="form-group">
                              <label>Tahun</label>
                              <div class='input-group date' >
                                  <input type='text' class="form-control" id='tahun_keranjang' value="<?php echo !empty($_GET['tahun']) ? $_GET['tahun']:date('Y'); ?>" />
                                  <span class="input-group-addon bg-green" onclick="ch_tahun_tb($(this))">
                                      <span class="glyphicon glyphicon-search"></span>
                                  </span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div> -->
              <!-- <div class="box box-solid box-primary">
                  <div class="box-header">
                      <h3 class="box-title">Tabel Keranjang</h3>
                  </div>
                  <div class="box-body"> -->
                      <table id="tb_pemesanan" class="table table-bordered table-striped table-hover dt-responsive" cellspacing="0" width="100%" style="font-size: small;">
                        <thead>
                        <tr>
                          <!-- <th class="" style="background-color: #4F81BD; color: white; ">NO.</th> -->
                          <th class="" style="background-color: #4F81BD; color: white; ">NO. PENGAMBILAN</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">NAMA KARYAWAN</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">GROUP</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">LANTAI</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">TGL. PENGAMBILAN</th>
                          <th class="" style="background-color: #4F81BD; color: white; display:none;">KURIR</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">STATUS</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">RATING GUDANG</th>
                          <th class="" style="background-color: #4F81BD; color: white; ">ULASAN</th>
                          <th class="" style="background-color: #4F81BD; color: white; width: 17%">ACTION</th>
                          <th class="" style="display: none">STATUS</th>
                          <th class="" style="display: none">ID_PEMESANAN</th>
                        </tr>
                        </thead>
                        <tbody>
                          <?php 
                            if(!empty($tb_pemesanan)||$tb_pemesanan!=null){ ?>
                              <?php $no=1; foreach ($tb_pemesanan as $key => $value) { ?>
                                <script>arr_cs.push({"id":"<?php echo $value->id_pemesanan; ?>","status":"<?php echo $value->status; ?>"})</script>
                                <?php if($act_button=='pemesanan'){ ?>
                                    <?php 
                                      $s = $value->status; 
                                      $c = $stat_pemesanan[$s]['color'];
                                      $st = $stat_pemesanan[$s]['status'];
                                      $id_pem = $value->id_pemesanan;
                                    ?>

                                    <tr class="id-<?php echo $value->id_pemesanan; ?>">
                                      <!-- <td><?php echo $no; ?></td> -->
                                      <td><?php echo $value->no_pemesanan; ?></td>
                                      <td><?php echo $value->pemesan; ?></td>
                                      <td><?php echo $value->group_name; ?></td>
                                      <td><?php echo $value->lantai; ?></td>
                                      <td><?php 
                                                $date = new DateTime($value->tgl_pemesanan);
                                                echo date_format($date,"d F Y, H:i:s \W\I\B"); 

                                          ?>
                                      </td>
                                      <td style="display:none;"><?php echo $value->kurir; ?></td>
                                      <td>
                                          <?php echo $st; ?>
                                          <!-- <div class="dropdown">
                                            <button class="btn-pop btn <?php echo $c; ?> btn-sm btn-flat center-block csstooltip dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $st; ?>

                                              <?php if($user=='Super Admin'||$user=='Admin'){ ?>
                                                <?php if($s>1 && $s<5){ ?>
                                                  <span class="caret"></span></button>
                                                  
                                                  <ul class="dropdown-menu">
                                                      <?php foreach($stat_kurir as $keyp=>$st_pem){ ?>
                                                              <li><a href="#" onclick="adm_ch_stat('<?php echo $id_pem; ?>','<?php echo $keyp; ?>')"><?php echo $st_pem['status']; ?></a></li>
                                                      <?php } ?>
                                                  </ul>
                                                <?php } ?>
                                              <?php } ?>


                                          </div>  -->
                                      </td>
                                      <td><?php echo $value->rating_gudang; ?></td>
                                      <td><?php echo $value->komentar; ?></td>
                                      <td>
                                        <?php include('view-pemesanan_btn_act.php'); ?>

                                        <!--  -->
                                      </td>
                                      <td style="display: none;"><?php echo $value->status; ?></td>
                                      <td style="display: none;"><?php echo $value->id_pemesanan; ?></td>
                                    </tr>
                                <?php } ?>

                              <?php $no++; } ?>
                          <?php  } 
                          ?>
                          
                        </tbody>
                      </table>
                  <!-- </div>
              </div> -->
          <?php } ?>
          

          <?php if($mode=='add' || $mode == 'edit'){ ?> 
            <script type="text/javascript">

              var arr_it = <?php echo json_encode($it_bc); ?>;
              var m = '<?php echo $mode; ?>';
              //alert(arr_it[0].barcode);
            </script>
            <div class="row" style="padding: 10px;">
              <div class="col-lg-12 col-xs-12">
                <form>
                  <div class="row">
                      <div class="form-group">
                        <?php if($user=='Karyawan'){ ?>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="col-md-4">
                                        <label>ID Pelanggan</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label>: <?php echo $_SESSION['username']; ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="col-md-4">
                                        <label>Nama</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label>: <?php echo $_SESSION['name']; ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-xs-4">
                            
                            <?php if($user=='Karyawan'){ ?>
                                <div class="col-md-12" style="display: none; visibility: hidden;">
                                    <select id="list_customer" class="sel2 act w-10" disabled="disabled">
                                        <option value="<?php echo $_SESSION['id_user'] ?>"><?php echo $_SESSION['name'] ?></option>
                                    </select>
                                </div>
                            <?php }else{ ?>
                              <?php if($mode=='add'){ ?>
                                <label for="judul_pemesanan">Select Karyawan / Customer</label><br>
                                <select id="list_customer" class="sel2 act w-10" >
                                  <option disabled="disabled" selected="selected">Pilih Karyawan</option>
                                  <?php foreach ($list_customer as $key => $value){ ?>
                                      <option value="<?php echo $value->id; ?>"
                                        <?php echo ($mode=='edit'&&$tb_pemesanan[0]->id_pemesan==$value->id) ? 'selected=selected':''; ?>>
                                        <?php echo $value->name; ?>
                                      
                                      </option>
                                  <?php } ?>
                                </select>
                              <?php } ?>
                              <?php if($mode=='edit'){ ?>
                                <select id="list_customer" class="sel2 act w-10" disabled="disabled">
                                  <?php foreach ($list_customer as $key => $value){ ?>
                                      <option value="<?php echo $value->id; ?>"
                                        <?php echo ($mode=='edit'&&$tb_pemesanan[0]->id_pemesan==$value->id) ? 'selected=selected':''; ?>>
                                        <?php echo $value->name; ?>
                                      
                                      </option>
                                  <?php } ?>
                                </select>
                              <?php } ?>
                            <?php } ?>
                          
                        </div>
<!--                         <div class="col-xs-3">
                            <label for="group">Group</label><br>
                            <input id="group" type="text" name="group" style="width: 100%;" value="<?php echo ($mode=='edit'&&(!empty($tb_pemesanan))) ? $tb_pemesanan[0]->group:''; ?>">
                        </div>

                        <div class="col-xs-2">
                            <label for="lantai">Lantai</label><br>
                            <input id="lantai" type="number" name="lantai" style="width: 100%;" value="<?php echo ($mode=='edit'&&(!empty($tb_pemesanan))) ? $tb_pemesanan[0]->lantai:''; ?>">
                        </div> -->
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="panel-group">
                        <div class="panel panel-default">
                          <div class="panel-heading">Pengambilan  <i class="pull-right">Nomor : <b id="no_pemesanan"><?php echo !empty($tb_pemesanan) ? $tb_pemesanan[0]->no_pemesanan:$nomor_order; ?></b></i></div>
                          <div class="panel-body">
                              <div class="row">
                                <div class="col-xs-3">
                                  <div class="form-group">
                                    <label for="item_select">Jenis Barang</label>
                                    <div class="input-group" style="width:100%;">
                                      
                                      <select id="item_select" onchange="ch_select($(this).val())">
                                        <?php if(!array_key_exists('status', $items)){?>
                                              <option selected="selected" disabled="disabled">Pilih Barang</option>
                                          <?php foreach ($items as $key => $value) { ?>
                                              <?php 
                                                $val =  $value->ID_ITEM . '|' .
                                                        $value->barcode . '|' .
                                                        $value->nama_item . '|' .
                                                        $value->qty . '|' .
                                                        $value->satuan . '|' .
                                                        $value->img;
                                              ?>
                                              <option value="<?php echo $val; ?>"><?php echo $value->nama_item; ?></option>
                                          <?php } ?>
                                        <?php } ?>
                                      </select>

                                    </div>
                                    <br>
                                    
                                  </div>
                                  <!-- <button id="sh_item" class="btn btn-default">Pilih barang</button> -->
                                </div>
                                <div class="col-lg-2 col-xs-12" id="bc_par" style="display: none">
                                  <div class="form-group">
                                    <label for="bc">Barcode 
                                        <!-- <input  type="checkbox" onclick="autoenter()" id="auto_enter" name=""><i>Auto Enter</i> -->
                                    </label>

                                    <input  disabled="disabled" type="number" min="1" class="form-control" id="bc" placeholder="Input Barcode">
                                  </div>
                                </div>
                                <div class="col-lg-2 col-xs-12">
                                  <div class="form-group">
                                    <label for="stock">Stok</label>
                                    <input disabled="disabled" type="number" min="1" class="form-control" id="stock" placeholder="Stok saat ini" >
                                  </div>
                                </div>
                                <div class="col-lg-1 col-xs-12">
                                  <div class="form-group">
                                    <label for="qty">Jumlah</label>
                                    <input onkeydown="enter(event)" type="number" min="1" class="form-control no-spin" id="qty" req-add-item placeholder="Jumlah Pengambilan">
                                  </div>
                                </div>
                                <div class="col-lg-3 col-xs-12">
                                  <div class="form-group">
                                    <label for="note">Catatan</label>
                                    <input  id="note" type="text" class="form-control" placeholder="Keterangan tambahan">
                                  </div>
                                </div>
                                <div class="col-xs-1">
                                  <div class="form-group">
                                    <label for="btn_add form-control">&nbsp;</label>
                                    <button id="btn_add" onclick="add_item(); " type="button" class="btn btn-success btn-sm form-control" data-html="true" data-toggle="tooltip" title="Masukkan Keranjang <span class='glyphicon glyphicon-shopping-cart'></span>"><span class="glyphicon glyphicon-plus"></span></button>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-xs-12">
                                  <div clas="table-responsive">
                                    <table id="tb_item_pemesanan" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                                        <thead>
                                          <tr style="background-color: #4F81BD; color: white;">
                                            <th>No.</th>
                                            <th>Barcode</th>
                                            <th>Nama Barang</th>
                                            <th>Stok</th>
                                            <th style="width: 10%;">Jumlah</th>
                                            <th style="width: 10%;">Catatan</th>
                                            <th style="width:10%;">Action</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php if($mode=='edit'){ ?>
                                            <input type="hidden" class="id_pemesanan" value="<?php echo $id; ?>">
                                            <?php if(!empty($list)){ ?>
                                              
                                              
                                              <?php $no=1; foreach($list as $key => $value) { ?>
                                                
                                                <tr>
                                                  <input type="hidden" class="id_item" value="<?php echo $value->ID_ITEM; ?>">
                                                  <input type="hidden" class="id_it_pemesanan" value="<?php echo $value->ID_IT_PEMESANAN; ?>">
                                                  <td><?php echo $no; ?></td>
                                                  <td><?php echo $value->barcode; ?></td>
                                                  <td><?php echo $value->item_name; ?></td>
                                                  <td class="r-td"><?php echo $value->i_qty; ?></td>
                                                  <td class="r-td"><?php echo $value->qty; ?></td>
                                                  <td><?php echo $value->note; ?></td>
                                                  <td>
                                                    <button onclick="del_ip(<?php echo $value->ID_IT_PEMESANAN; ?>,$(this))" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Item">
                                                      <span class="glyphicon glyphicon-trash"></span>
                                                    </button>
                                                    <button onclick="edit_item($(this))" type="button" class="btn btn-warning btn-sm it_edit" data-toggle="tooltip" title="Ubah Jumlah Item">
                                                      <span class="glyphicon glyphicon-edit"></span>
                                                    </button>
                                                  </td>
                                                </tr>
                                              <?php $no++; } ?>
                                            <?php }?>
                                          <?php }?>
                                        </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                          </div>
                          <div class="panel panel-footer">
                            <div class="row">
                              <div class="col-xs-12">
                                <a onclick="submit('<?php echo $mode; ?>')" id="btn-submit" class="btn btn-success btn-sm pull-right btn-submit">
                                    <span class="glyphicon glyphicon-plus"></span> SUBMIT
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </form>
              </div>
            </div>

          <?php } ?>
      </div>
    </div>
  </div>







  <!-- Modal -->
<div id="modal_view_pemesanan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title judul_pemesanan"></h4>
      </div>
      <div class="modal-body">
          
          <label>Nomor Pemesanan : <i class="no_pemesanan"></i></label>
          <br>
          <label>Tanggal Pemesanan : <i class="tgl_pemesanan"></i></label>
          <br><br>

          <!-- <label>Pemesan </i></label><br> -->
          <div class="col-md-10">
              <table class="table">
                <tr>
                    <td>Nama Pemesan</td><td>:</td>
                    <td class="nm_pemesan"></td>
                </tr>
                <tr>
                    <td>Group</td><td>:</td>
                    <td class="v_group"></td>
                </tr>
                <tr>
                    <td>Lantai</td><td>:</td>
                    <td class="v_lantai"></td>
                </tr>
                <tr>
                    <td>Status</td><td>:</td>
                    <td class="v_status"></td>
                </tr>
                <tr>
                    <td>Nama Kurir</td><td>:</td>
                    <td class="v_kurir"></td>
                </tr>
                <tr>
                    <td>Rating Gudang</td><td>:</td>
                    <td class="v_rating_gudang"></td>
                </tr>
                <tr>
                    <td>Komentar Gudang</td><td>:</td>
                    <td class="v_komentar_gudang"></td>
                </tr>

              </table>
          </div>

          <div class="row verifikasi" style="display: none;">  
          </div>
          <div class="row">
            <div class="col-xs-12">
              <div clas="table-responsive">
                <input type="hidden" id="id_pemesanan_lg" name="">
                <table id="tb_item_pemesanan" class="table table-bordered table-hover dt-responsive" cellspacing="0" width="100%">
                    <thead>
                      <tr style="background-color: #4F81BD; color: white;">
                        <th>No</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Stock</th>
                        <th>Jumlah</th>
                        <th>Note</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                </table>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="modal_view_kurir" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="" id="md_kurir_id_pemesanan">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<ul id="popover-content" class="list-group" style="display: none;">
  <input type="hidden" name="id_pem" class="id_pem">
  <?php if(!empty($list_kurir)){ ?>
    <?php foreach ($list_kurir as $key => $list) { ?>
        <a href="#" onclick="kurir_act($(this))" class="bg-blue list-group-item"><input name="kurir_id" type="hidden" value="<?php echo $list->id; ?>"><b class="kurir_name"><?php echo $list->name; ?></b></a>
    <?php } ?>
  <?php }else{ ?>
        <label>Kurir Kosong</label>
  <?php } ?>
</ul>

<ul id="status_pop_content" class="list-group" style="display: none;">
  <input type="hidden" name="id_pem" class="id_pem">
  <?php foreach ($list_kurir as $key => $list) { ?>
      <a href="#" onclick="kurir_act($(this))" class="list-group-item"><input name="kurir_id" type="hidden" value="<?php echo $list->id; ?>"><b class="kurir_name"><?php echo $list->name; ?></b></a>
  <?php } ?>
</ul>

<ul id="stat_pem_popover" class="dropdown-menu" style="display: none;">
  <input type="hidden" name="id_pem" class="id_pem">
  <button class="btn bg-aqua btn-block" type="button" onclick="adm_ch_stat(String($(this).parent().find('.id_pem').val()),String(3))">Prepare Item</button>
  <button class="btn btn-primary btn-block" type="button" onclick="adm_ch_stat(String($(this).parent().find('.id_pem').val()),String(4))">Courier On The Way</button>
  <button class="btn btn-success btn-block" type="button" onclick="adm_ch_stat(String($(this).parent().find('.id_pem').val()),String(5))">Done</button>

</div> 
</ul>

<style type="text/css">
  .popover-content{
    max-height: 500px;
    overflow-x:overlay;
  }

  input#stock::-webkit-outer-spin-button,
  input#stock::-webkit-inner-spin-button {
      /* display: none; <- Crashes Chrome on hover */
      -webkit-appearance: none;
      margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
  }

  input.no-spin::-webkit-outer-spin-button,
  input.no-spin::-webkit-inner-spin-button {
      /* display: none; <- Crashes Chrome on hover */
      -webkit-appearance: none;
      margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
  }

  .bigdrop .select2-results .select2-results__option span img{
      width: 100px !important;
      height: 100px !important;
      background-color: white;
  }
  .select2-dropdown.bigdrop{
      width: 450px !important;
  }
 /* #select2-item_select-results{
      max-height: 400px; 
  }*/

  /*.select2-results__options {*/
     /*max-height: 500px;
  }*/
</style>

<script type="text/javascript">
  
</script>