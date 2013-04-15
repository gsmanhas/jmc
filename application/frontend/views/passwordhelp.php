<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Password Help - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="author" content="">
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.error {
			color:#d02121;
		}
	</style>
</head>
<body id="">
	
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
		<div id="pagetitle"><h1>Password Help</h1></div>
		<p>If you have forgotten your password please enter the email address registered with your account. <br>We will send you an email notifying you of more instructions.</p>
		<form id="frmMain" action="/passwordhelp/submit" method="post" accept-charset="utf-8">
			<p>
				<label for="email">Your Email Address *<?php echo form_error('email', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
				<input type="text" value="<?php echo set_value('email') ?>" id="email" name="email" class="inputtext" size="40" maxlength="40">
			</p>
			<p>
				<a style="width:100px" href="javascript:document.getElementById('frmMain').submit()" class="inputbutton">Retrieve Password</a>
			</p>
			
		</form>
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>