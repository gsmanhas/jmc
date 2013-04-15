<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!DOCTYPE html>
<html>
	<head>
		<!-- Meta -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- End of Meta -->
		
		<!-- Page title -->
		<title>System Cart  - Signin</title>
		<!-- End of Page title -->
		
		<!-- Libraries -->
		<link type="text/css" href="<?php echo base_url() ?>css/backend/login.css" rel="stylesheet" />	
		<link type="text/css" href="<?php echo base_url() ?>css/backend/styles/flick/ui.css" rel="stylesheet" />	
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.js" type="text/javascript"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js" type="text/javascript"></script>
		<!-- End of Libraries -->	
	</head>
	<body>
	<div id="container">
		<div class="logo">
			<a href=""><img src="<?php echo base_url() ?>assets/logo.png" alt="" /></a>
		</div>
		<div id="box">
			<form action="<?php echo site_url(); ?>/signin/submit" method="post" accept-charset="utf-8">
			<p class="main">
				<label>Username</label>
				<input name="username" value="<?php echo set_value('username') ?>" />
			</p>
			<p class="main">
				<label>Password</label>
				<input type="password" name="password" value="">	
			</p>
			<p class="space">
				<span><input type="checkbox" />Remember Me</span>
				<input type="submit" value="Login" class="login" />
			</p>
			<br>
			<?php echo validation_errors('<div style="text-align:left;color:red"><span style="color:red">','</span></div>'); ?>
			</div>
			</form>
		</div>
	</div>

	</body>
</html>