<?php
  $ck_user = $_SESSION;

  if(count($_SESSION)>1){

?>

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

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo !empty($title) ? $title. ' | ':''; ?> TOFAP</title>
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
  <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/morris.js/morris.css"> -->
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/notify/animate.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/toast/jquery.toast.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css">




  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
<!--   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
  <script type="text/javascript">
    var URL = '<?php echo base_url(); ?>';
  </script>
</head>

<body class="hold-transition skin-blue sidebar-mini" >
  <div class="wrapper">
    <header class="main-header" >
      <a href="#" class="beta logo">
        <span class="logo-mini"><img width="60%" src="<?php echo base_url('assets/img/logo/mini.png'); ?>"></span></span>
        <span class="logo-lg"><img style="margin-top: -10px" width="170px" src="<?php echo base_url('assets/img/logo/logo.png'); ?>"></span>
      </a>
      <nav class="navbar navbar-static-top" style="background-color: #169eda;">
        <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <!-- <img src="<?php echo base_url('assets/dist/img/avatar5.png'); ?>" class="user-image" > -->
              <span class="hidden-xs"><?php echo !empty($_SESSION['name']) ? $_SESSION['name']:''; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: auto;">

                <p>
                  <?php echo $_SESSION['name']; ?>
                  <small><?php echo $_SESSION['user_type']; ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div> -->
                <div id="ch_pass" class="pull-left" >
                  <div class="btn btn-default btn-flat" onclick="ch_pass()">Ubah Kata Sandi</div>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('home/logout'); ?>" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>


      <!-- ############## -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="<?php echo !empty($_GET['mobile'])?'display: none;':''; ?>">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="spinner" style="display:none;">
          <div class="rect1"></div>
          <div class="rect2"></div>
          <div class="rect3"></div>
          <div class="rect4"></div>
          <div class="rect5"></div>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar" style="<?php echo !empty($_GET['mobile'])?'display: none;':''; ?>">
      <section class="sidebar">
        <?php $this->load->view('view-sidebar',$val); ?>
      </section>
    </aside>

    <div class="content-wrapper" id="cw">
      <section class="content-header">
      </section>
      <section id="konten" class="content">
        <?php
          if(!empty($content)){
            $this->load->view($content);
          }
        ?>
      </section>
    </div>

    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <!-- <b>Version</b> 2.4.0 -->
      </div>
      <strong>Copyright © <?= BRAND_PT ?></strong>
    </footer>
    <!-- Content Header (Page header) -->
  </div>



<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/plugins/jQueryUI/jquery-ui.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  // $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/md5/jquery.md5.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/toast/jquery.toast.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/moment/min/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/chart/chart.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/zingchart/zingchart.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jspdf/jspdf.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jspdf/jspdf.debug.js"></script>
<!-- Morris.js charts -->
<!-- <script src="<?php echo base_url(); ?>assets/plugins/raphael/raphael.min.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/plugins/morris.js/morris.min.js"></script> -->
<!-- Sparkline -->
  <?php if(!empty($plugin) && $plugin == 'plugin_0') {?>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
    <script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="<?php echo base_url(); ?>assets/plugins/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>assets/plugins/fastclick/lib/fastclick.js"></script>
  <?php } else ?>
<!-- AdminLTE App -->

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="<?php //echo base_url(); ?>assets/dist/js/pages/dashboard.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
  <?php if(!empty($plugin) && $plugin == 'plugin_1') {?>

  <!-- Datatables -->
  <link href="<?php echo base_url(); ?>assets/plugins/datatables/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.responsive.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.tableTools.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datepicker/css/bootstrap-datepicker.min.css">


  <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.tableTools.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.responsive.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.full.min.js"></script>

  <script src="<?php echo base_url(); ?>assets/plugins/autonumeric/autoNumeric.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/notify/bootstrap-notify.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/plugins/html2canvas/html2canvas.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>




  <script>
    $('.select2.act').select2({containerCssClass : "select2-act"});
    $('.select2.act').select2({width:'100%'});

    $('.sel2.act').select2({containerCssClass : "sel2-act"});
    $('.sel2.act.w-10').select2({width:'100%'});
    $('.sel2.act.w-5').select2({width:'50%'});
    $('.sel2.act.w-3').select2({width:'30%'});
    $('.sel2.act.w-2').select2({width:'20%'});
    // $('.tb_dt').DataTable({searching: false, paging: false}).column([0]).visible(false);

    function v_username(x){
        var str = x.trim();
        if(str.length>0){
            var c = str.match(/^[a-z0-9A-Z]*$/);
            if(c!=null){
                return true;
            }else{
                return false;
            }
        }
    }
    function v_name(x){
        var str = x.trim();
        if(str.length>0){
            var c = str.match(/^[\s|a-z|A-Z]*$/);
            if(c!=null){
                return true;
            }else{
                return false;
            }
        }
    }

    function form_validate(x){
        var frm = x.find('input[required]');
        var l = frm.length;
        try{
            $.each(frm, function(index, val) {
                var v = x.find('input[required]:eq('+index+')');
                 if(v.val()!=''){
                    l--;
                 }
            });
        }catch(e){

        }finally{
            if(l==0){
                return true;
            }else{
                console.log('funct (form_validate) = error');
            }
        }
    }

    function reset_form(x){
      $(x).on('hidden.bs.modal', function (e) {
        // setSubFirst();
          $(this)
          .find("input,textarea,select")
             .val('')
             .end()
          .find("input[type=checkbox], input[type=radio]")
             .prop("checked", "")
             .end();
          $(this).find("select").prop('selectedIndex',0);
          $(this).find("select.select-act").select2({width:'100%'});
          $(this).find('img.photo').attr('src',URL + 'assets/img/add.png');
      });
    }
  </script>

  <style type="text/css">
    span.select2-selection.select2-selection--single.select2-act {
      border-radius: 0px;
      height: 100%;
    }

    .btn.bt-sm{
      line-height: 50%;
      width: 100%;
    }
  </style>




  <?php } ?>
  <script src="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
  <script src="<?php echo !empty($js) ? base_url().'assets/scripts/'.$js.'.js' : '';?>"></script>
  <script src="<?php echo base_url(); ?>assets/scripts/js-sidebar.js"></script>

</body>
</html>

<style type="text/css">
  .spinner {
    margin: 5px 50px auto;
    width: 50px;
    height: 40px;
    text-align: center;
    font-size: 10px;
}

.spinner > div {
  background-color: white;
  height: 100%;
  width: 6px;
  display: inline-block;

  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
  animation: sk-stretchdelay 1.2s infinite ease-in-out;
}

.spinner .rect2 {
  -webkit-animation-delay: -1.1s;
  animation-delay: -1.1s;
}

.spinner .rect3 {
  -webkit-animation-delay: -1.0s;
  animation-delay: -1.0s;
}

.spinner .rect4 {
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}

.spinner .rect5 {
  -webkit-animation-delay: -0.8s;
  animation-delay: -0.8s;
}

@-webkit-keyframes sk-stretchdelay {
  0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
  20% { -webkit-transform: scaleY(1.0) }
}

@keyframes sk-stretchdelay {
  0%, 40%, 100% {
    transform: scaleY(0.4);
    -webkit-transform: scaleY(0.4);
  }  20% {
    transform: scaleY(1.0);
    -webkit-transform: scaleY(1.0);
  }
}

.csstooltip {
    position: relative;
    display: inline-block;
    /*border-bottom: 1px dotted black; */
}

/* Tooltip text */
.csstooltip .tooltiptext {
    visibility: hidden;
    /*width: 120px;*/
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
/*    padding-left: 10%;
    padding-right: 10%;*/
    border-radius: 6px;

    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;

    width: 250%;
    bottom: 130%;
    left: 50%;
    margin-left: -100%;
}

/* Show the tooltip text when you mouse over the tooltip container */
.csstooltip:hover .tooltiptext {
    visibility: visible;
}

.custom-icon{
    width: 20px;
    height: 20px;
    filter: invert(1);
    margin-left: 0px;
}

/* SIZE SCROLL PANE JQUERY CONFIRM */

.jconfirm .jconfirm-box div.jconfirm-content-pane::-webkit-scrollbar {
    width: 15px;
}

.jconfirm .jconfirm-box div.jconfirm-content-pane::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    /*border-radius: 10px;*/
}

.jconfirm .jconfirm-box div.jconfirm-content-pane::-webkit-scrollbar-thumb {
    /*border-radius: 10px;*/
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
}

.r-td{
  text-align: right;
}

</style>

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

    function num(x) {
      var s = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return s+',-'
    }
    function num_n(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function cnum(x) {
        var number = x.toString().replace(/[.]/g, "");
        return number.substring( 0, number.indexOf( ",-" ) );
    }

    function cnum_n(x) {
        return x.toString().replace(/[.]/g, "");
    }
</script>


<script>


    function toast(title=null,message=null,icon=null,url=null,target=null){
        $.notify({
          // options
          icon: icon,
          title: title,
          message: message,
          url: url,
          target: target
        },{
          // settings
          element: 'body',
          position: null,
          type: "info",
          allow_dismiss: true,
          newest_on_top: false,
          showProgressbar: false,
          placement: {
            from: "top",
            align: "right"
          },
          offset: 20,
          spacing: 10,
          z_index: 1031,
          delay: 3000,
          timer: 1000,
          url_target: '_blank',
          mouse_over: null,
          animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
          },
          onShow: null,
          onShown: null,
          onClose: null,
          onClosed: null,
          icon_type: 'class',
          template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
              '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
          '</div>'
        });
    }

</script>

<script type="text/javascript">

    function ch_pass(){
        var frm_ch = $.confirm({

          title:'Change Password',
          content:''+
                  '<form action="" class="frm-ch_pass">' +
                  '<div class="form-group">' +
                  '<label>Kata Sandi Lama</label>' +
                  '<input type="password" placeholder="Kata Sandi anda sebelumnya" class="old_pass form-control" required />' +
                  '<label>Kata Sandi Baru</label>' +
                  '<input type="password" placeholder="Kata Sandi baru anda" class="new_pass form-control" required />' +
                  '</div>' +
                  '</form>',
          buttons:{
            change:{
              text:'Submit',
              btnClass:'btn-blue',
              action:function(){
                  // var ret = true;
                  startloading('Mohon Tunggu');
                  var o = this.$content.find('.old_pass').val();
                  var n = this.$content.find('.new_pass').val();

                  // return false;
                  $.ajax({
                    type:'POST',
                    data:{old:o,new:n},
                    url:URL+'user/ch_pass',
                    dataType:'json',
                    success:function(data){
                      $.confirm({
                          icon: data.icon,
                          theme: 'modern',
                          closeIcon: true,
                          animation: 'zoom',
                          type: data.color,
                          title:'',
                          closeIcon:false,
                          content:data.message,
                          autoClose: 'close|5000',
                          buttons:{
                              close:{
                                  text:'CLOSE',
                                  action:function(){
                                      if(data.status==1){
                                          frm_ch.close();
                                      }
                                  }
                              }
                          }
                      });

                      endloading();
                    },
                    error:function(){

                    }

                  });

                  return false;

              }
            },
            close:{
              text:'Close'
            }
          }
        });
    }
</script>
<script type="text/javascript">
      function format_jam(x=null,akhir='WIB'){
          var time = new Date(x);
          var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
          var date = time.getDate() + ' ' + month[time.getMonth()] + ' ' + time.getFullYear();
          var minute = '';
          var hour = '';
          var second = '';

          if(String(time.getMinutes()).length<2){
              minute = '0'+time.getMinutes();
          }else{
              minute = time.getMinutes();
          }

          if(String(time.getHours()).length<2){
              hour = '0'+time.getHours();
          }else{
              hour = time.getHours();
          }

          if(String(time.getSeconds()).length<2){
              second = '0'+time.getSeconds();
          }else{
              second = time.getSeconds();
          }

          var waktu = hour + ':' + minute + ':' + second;
          return date + ', ' + waktu + ' ' + akhir;
      }



</script>


<?php }else{
    // redirect('login');
} ?>
