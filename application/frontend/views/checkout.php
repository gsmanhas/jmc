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
	<?php $this->load->view('base/head') ?>
	<!-- <script src="/js/shop.js" type="text/javascript" charset="utf-8"></script> -->
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
		
		<div id="pagetitle"><h1>Checkout</h1></div>
		
		<div style="width:628px;height:auto;overflow:hidden;margin:40px auto 60px auto;">
			<div class="accountinputbox">
				<form action="/signin/submit" method="post" accept-charset="utf-8">
				
					<fieldset id="member_login" class="">
						<legend>Already a member? Please sign in</legend><br>
						<p>
							<label for="username">E-mail Address</label><br>
							<input type="text" value="<?php echo set_value('username') ?>" id="username" name="username" class="inputtext">
						</p>
						<p>
							<label for="password">Password</label><br>
							<input type="password" value="" id="password" name="password" class="inputtext">
						</p>
					</fieldset>
					
					<input type="submit" name="btnLogin" value="Login" id="" class="inputbutton">
					<input type="hidden" name="checkout" value="true" id="checkout" class="inputbutton">
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
						<legend>Not a member?</legend><br>
						<p style="margin-bottom:20px;">
							You don't need an account to shop with us, however, there are many benefits to registering online such as, faster checkout, sample opportunities and advance notice of new product launches. If you would like to create an account profile, you can do so now in the checkout process. 
						</p>
						<a href="/guestcheckout" id="bt_guestcheckout">Proceed with guest checkout</a>
					</fieldset>
				</form>
			</div>
		</div>		

	</div>
	
	<?php $this->load->view('base/footer_cart') ?>
	<?php //$this->load->view('base/facebook') ?>
	
</body>
</html>

