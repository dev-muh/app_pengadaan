<!DOCTYPE html>
<html>
<head>
  <script type="text/javascript">
    var URL = '<?php echo base_url(); ?>';
  </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lupa Sandi | TOFAP</title>
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.css">

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
<div class="login-box">
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
    <p class="login-box-msg">Masukkan ID Pengguna dan Email untuk memulihkan akun anda.</p>

    <form>

      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="Masukkan ID Pengguna">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="Masukkan Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck" style="display: none">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-12">
          <div onclick="submit_forgot()" class="btn btn-primary btn-block btn-flat">Submit</div>
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
          message: '<img src="'+URL+'assets/dist/img/little-devil.svg" /><br><h4>'+pesan+'</h4>',
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
<script src="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url(); ?>assets/scripts/<?php echo $js; ?>.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
