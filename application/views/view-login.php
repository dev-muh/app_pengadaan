<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>LOGIN | TOFAP</title>
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

  <div class="login-logo" style="margin-bottom: -15px;">
    <?php
      if(!empty($logo_url_path)){
    ?>
      <!-- <a href="<?php echo base_url();?>"><?php echo APP_NAME_BOLD; ?></a> -->
      <img src="<?php echo $logo_url_path;?>" width="100%">

    <?php
      }else{
    ?>
      <a href="<?php echo base_url();?>"><b>NO LOGO</b></a>
    <?php
      }
    ?>
  </div>

  <h2 style="margin-bottom: 30px;">
    <table id="ttl" width="100%">
      <tr>
        <td style="width:9%"></td>
        <td id="l" style="width:22%">Tugu</td>
        <td id="c" style="width:25%">Office</td>
        <td id="r" style="width:30%">Appliance</td>
        <td style="width:15%"></td>
      </tr>
    </table>
  </h2>
  <!-- /.login-logo -->
  <div class="alert alert-danger alert-dismissible fade in" style="display: <?php echo $display; ?>;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?php echo $message; ?>
  </div>

  <div class="login-box-body" style="border-radius: 10px;">
    <p class="login-box-msg"></p>

    <form action="<?php echo base_url('login/validate');?>" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="ID Pengguna">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Kata Sandi">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
          <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
        </div>
        <div class="col-xs-12" style="text-align: center;">
        <br>
              <a href="<?php echo base_url('user/register'); ?>">Daftar</a> | <a href="<?php echo base_url('user/forget'); ?>">Lupa Sandi?</a>
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



<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
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
