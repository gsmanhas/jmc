<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Register - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="message">
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
		
		<div id="pagetitle"><h1>Register</h1></div>
		
		<div id="messagewrapper">
		
			<h2>Thank you for registering at Josie Maran Cosmetics!</h2>
			<?php /*?><p>You will receive a confirmation email shortly, be sure to check your spam inbox as they frequently get filed there. Your account can be activated by clicking or copying and pasting the link in the e-mail into your browser address bar.</p><?php */?>
		    <p>You are now registered! <a href="/shop/bestbets" >Click here</a> to continue shopping.  <a href="/myaccount" >Click here</a> to edit your account information</p>
		</div>
		
		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>
