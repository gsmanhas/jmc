<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Change Password - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.error {
			color:#d02121;
		}
	</style>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="myaccount">
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
		
		<div id="pagetitle"><h1>Change Password</h1></div>
		
		<?php
			if ($this->session->flashdata('message')) {
		?>
		<div class="errormessage">
			<?php echo $this->session->flashdata('message') ?>
		</div>
		<?php
			}
		?>
		
		<?php $this->load->view('myaccount/left_menu') ?>
		
		<form name="frmMain" id="frmMain" method="post" action="<?php echo base_url() ?>myaccount/reset-password">
		<div style="width:628px;height:auto;overflow:hidden;margin:0 auto 80px auto;">
			<div class="accountinputbox">
				<fieldset id="account_information">
					<legend>Account Information</legend><br>
					<p>
						<label for="password">Please enter your old password<?php echo form_error('password', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="password" value="" id="password" name="password" class="inputtext" maxlength="24">
					</p>
					<p>
						<label for="new_pass">New Password<?php echo form_error('new_pass', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="password" value="" id="new_pass" name="new_pass" class="inputtext" maxlength="24">
					</p>
					<p>
						<label for="passconf">Verify Password<?php echo form_error('passconf', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="password" value="" id="passconf" name="passconf" class="inputtext" maxlength="24">
					</p>
				</fieldset>
				<input type="submit" name="btnSubmit" value="Update" id="btnSubmit" class="inputbutton" style="font-size:1.667em;padding:5px 10px;height:40px;margin-top:20px;">
			</div>
		</div>
		</form>
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

