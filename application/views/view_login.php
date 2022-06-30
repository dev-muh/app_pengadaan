<!doctype html>
<html lang="en">
  <head>
  	<title>Login 08</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/css/style.css">

	<!-- Bootstrap 3.3.7 -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap/dist/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/login/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.min.css">

	</head>
	<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-3">
					<h2 class="heading-section">E-Procurement</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-5">
					<div class="login-wrap p-4 p-md-5">
		      	<div class="icon d-flex align-items-center justify-content-center">
		      		<span class="fa fa-user-o"></span>
		      	</div>
		      	<h3 class="text-center mb-4">Form Login</h3>
		      	<!-- <div class="alert alert-danger alert-dismissible fade in" style="display: <?php echo $display; ?>;">
					    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					    <?php echo $message; ?>
					  </div> -->
					  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="display: <?php echo $display; ?>;">
						  <?php echo $message; ?>
						  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<form action="<?php echo base_url('login/validate');?>" method="post" class="login-form">
		      		<div class="form-group">
		      			<input type="text" class="form-control rounded-left" name="username" placeholder="Username" required>
		      		</div>
	            <div class="form-group d-flex">
	              <input type="password" class="form-control rounded-left" name="password" placeholder="Password" required>
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-50">
	            		<a href="<?php echo base_url(); ?>user/register">Register</a>
								</div>
								<div class="w-50 text-md-right">
									<a href="<?php echo base_url(); ?>user/forget">Forgot Password</a>
								</div>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="btn btn-primary rounded submit p-3 px-5">Sign in</button>
	            </div>
	          </form>
	        </div>
	        <div class="mt-5 text-center">
	        	<p>Powered By RScripts</p>	
	        </div>
	        
	        
				</div>
			</div>
		</div>
	</section>

	<script src="<?php echo base_url(); ?>assets/login/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/login/js/popper.js"></script>
  <script src="<?php echo base_url(); ?>assets/login/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/login/js/main.js"></script>

	</body>
</html>

