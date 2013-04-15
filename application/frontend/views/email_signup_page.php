<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Sign Up for Email - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="author" content="">
	<?php $this->load->view('base/head') ?>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
	var icpForm7867 = document.getElementById('icpsignup7867');
	if (document.location.protocol === "https:")
		icpForm7867.action = "https://app.icontact.com/icp/signup.php";
		function verifyRequired7867() {
	  		if (icpForm7867["fields_email"].value == "") {
	    		icpForm7867["fields_email"].focus();
	    		alert("The Email field is required.");
	    		return false;
	  		}
			return true;
	}
	</script>
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
		<div id="pagetitle"><h1>Sign-Up for Email</h1></div>
		<div id="messagewrapper">

			<form method=post action="https://app.icontact.com/icp/signup.php" name="icpsignup" id="icpsignup7867" accept-charset="UTF-8" onsubmit="return verifyRequired7867();" >

				<p>
					<label>Please enter your email *</label><br>

					<input type="text" value="sign-up for email" name="fields_email" id="signup_for_email" class="inputtext" size="40" maxlength="40" style="float:left;margin-right:8px;">
					
					<a href="#" id="go" class="inputbutton" style="float:left;width:20px;">go</a>
					<input type=hidden name=redirect value="http://www.josiemarancosmetics.com/email-signup-success" />
					<input type=hidden name=errorredirect value="http://www.josiemarancosmetics.com/email-signup-error" />					
				    <input type=hidden name="listid" value="80976">
				    <input type=hidden name="specialid:80976" value="595Q">
				    <input type=hidden name=clientid value="279625">
				    <input type=hidden name=formid value="7867">
				    <input type=hidden name=reallistid value="1">
				    <input type=hidden name=doubleopt value="0">
				</p>
			</form>	
			
		</div>
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>