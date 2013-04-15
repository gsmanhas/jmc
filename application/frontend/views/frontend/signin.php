<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="/css/general.css" type="text/css" />
	<!--[if (IE 5)|(IE 6)|(IE 7)|(IE 8)|(IE 9)]> 
		<link href='/css/iefixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]-->
	<!--[if IE 6]> 
		<link href='/css/ie6fixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]--> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<!-- // <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script> -->
	
	<script src="/js/search.js" type="text/javascript" charset="utf-8"></script>
	<!-- <script src="/js/shop.js" type="text/javascript" charset="utf-8"></script> -->
	<?php $this->load->view('base/ga') ?>
</head>
<body>
	
	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $this->load->view('base/utilities') ?>
	</div>
	<div id="main">
		
		<div id="topnav">
			<?php $this->load->view('base/menu') ?>
		</div>
		
		<div id="pagetitle"><h1>Sign In</h1></div>
		
		<div style="width:628px;height:auto;overflow:hidden;margin:40px auto 60px auto;">
			<div class="accountinputbox">
				<form action="/signin/submit" method="post" name="frmLogin" id="frmLogin">
					<fieldset>
						<legend>Existing Customers</legend><br>
						<p>
							<label for="username">E-mail Address</label><br>
							<input type="text" value="<?php echo set_value('username') ?>" id="username" name="username" class="inputtext">
						</p>
						<p>
							<label for="password">Password</label><br>
							<input type="password" value="" id="password" name="password" class="inputtext">
						</p>
						<p>
							<a href="/passwordhelp">Forgot Your Password?</a>
						</p>
						
						<!--<p style="color:red">
							On 11/8 we upgraded the security of the site. If you have not logged into your account since 11/8, we have reset your password.  Please click on the link "Forgot Your Password?" above and your password will be emailed to you. 
						</p>-->
						
					</fieldset>
					
					<input type="submit" name="btnLogin" value="Login" id="" class="inputbutton">
					<input type="hidden" name="history" value="<?php echo (isset($this->history) ? $this->history : "") ?>" id="history">
				</form>
				<div style="margin:20px 0;">
					<?php echo validation_errors('<p class="error" style="margin-bottom:0;color:#d02121;">','</p>'); ?>
				</div>
			</div>
			
			<div class="accountinputboxverticaldivider" style="height:150px;">&nbsp;</div>
			
			<div class="accountinputbox">
				<form>
					<fieldset>
						<legend>New Customers</legend><br>
						<p style="margin-bottom:20px;">You don't need an account to shop with us, however, there are many benefits to registering online such as, faster checkout, sample opportunities and advance notice of new product launches. If you would like to create an account profile, you can do so now, or later in the checkout process.</p>
						<p>&nbsp;</p>
						<a id="bt_register" class="bt_login" href="/register" name="bt_register">Register</a>
					</fieldset>
				</form>
			</div>
		</div>
		
		<?php $this->load->view('base/footer') ?>
		
	</div>

</body>
</html>