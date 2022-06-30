<!DOCTYPE html>
<html>
<head>
  <script type="text/javascript">
    var URL = '<?php echo base_url(); ?>';
  </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Register | TOFAP</title>
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/img/logo/mini.png" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-confirm/css/jquery-confirm.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
<!--  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
</head>
<body class="hold-transition login-page">
<div class="login-box" >
  <div class="login-logo">
<!--     <?php
      if(!empty($logo_url_path)){
    ?>

      <img src="<?php echo $logo_url_path;?>" width="70%" height="70%">

    <?php
      }else{
    ?>
      <a href="<?php echo base_url();?>"><b>NO LOGO</b></a>
    <?php
      }
    ?> -->


  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Lengkapi form di bawah ini untuk daftar.</p>

    <form id="frm_reg">
      <!-- <div class="form-group has-feedback">
        <input type="text" class="form-control" name="user_id" placeholder="Masukkan Nomor Pegawai">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div> -->
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="Masukkan ID Pengguna" >
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="name" placeholder="Masukkan Nama Lengkap">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">

        <!-- <span class="glyphicon glyphicon-use r form-control-feedback"></span> -->

        <select  id="select_group" class="form-control" required name="group">
          <option disabled selected value="0">Pilih Group</option>
          <?php foreach ($ls_group as $key => $value) { ?>
              <option value="<?php echo $value->id; ?>"><?php echo $value->group_name; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group has-feedback">
        <!-- <input type="number" class="form-control" name="lantai" placeholder="Masukkan Lantai"> -->
        <!-- <span class="glyphicon glyphicon-user form-control-feedback"></span> -->

        <select id="select_lantai" class="form-control" name="lantai">
          <option disabled="disabled" selected="selected" value="0">Pilih Lokasi</option>
          <option value="1">Lantai 1</option>
          <option value="2">Lantai 2</option>
          <option value="3">Lantai 3</option>
          <option value="4">Lantai 4</option>
          <option value="5">Lantai 5</option>
        </select>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="Masukkan Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck" style="display: none">
            <label>
              <!-- <input type="checkbox"> Remember Me -->
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-12">
          <button id="submit-reg" class="btn btn-primary btn-block btn-flat">Submit</button>

        </div>
        <div class="col-xs-12" style="text-align: center;">
        <br>
              <a href="<?php echo base_url('login'); ?>">Kembali ke halaman Login</a>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
  <br><br>
  <center>
    <strong>Copyright © <?= BRAND_PT ?></strong>
  </center>

</div>


<!-- /.login-box -->

<script type="text/javascript">
    function startloading(pesan){
      $.blockUI({
          message: '<img style="width:30%;" src="'+URL+'assets/dist/img/loading.gif" /><br><h4>'+pesan+'</h4>',
          theme: false,
          baseZ: 999999999
      });
    }

    function endloading(){
      setTimeout($.unblockUI, 100);  //1 second
    }
</script>

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-confirm/js/jquery-confirm.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url(); ?>assets/scripts/<?php echo $js; ?>.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.full.min.js"></script>

<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });

    $('select[name=group]').select2();
    $('select[name=lantai]').select2({minimumResultsForSearch: -1});
  });


</script>
</body>
</html>
