<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>My Account - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function(){

			jQuery("#shipSame").click(function(){
				if (jQuery(this).attr("checked") == true) {
					jQuery("#ship_address").attr("disabled"  , "true").val("");
					jQuery("#ship_city").attr("disabled"     , "true").val("");
					jQuery("#ship_state").attr("disabled"    , "true").attr("selectedIndex", "0");
					jQuery("#ship_zipcode").attr("disabled"  , "true").val("");
				} else {
					jQuery("#ship_address").attr("disabled"  , "");
					jQuery("#ship_city").attr("disabled"     , "");
					jQuery("#ship_state").attr("disabled"    , "");
					jQuery("#ship_zipcode").attr("disabled"  , "");
				}
				
				if (jQuery(this).val() == 0) {
					jQuery(this).val(1);
				} else {
					!jQuery(this).val(0);
				}
				
			});
						
			if (jQuery("#shipSame").attr('checked') == true) {
				jQuery("#ship_address").attr("disabled"  , "true").val("");
				jQuery("#ship_city").attr("disabled"     , "true").val("");
				jQuery("#ship_state").attr("disabled"    , "true").attr("selectedIndex", "0");
				jQuery("#ship_zipcode").attr("disabled"  , "true").val("");				
			}
			
		});
	</script>
	<?php $this->load->view('base/ga') ?>
	<style type="text/css" media="screen">
		.error {
			color:#d02121;
		}
	</style>
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
		
		<div id="pagetitle"><h1>My Account</h1></div>
						
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
		
		<form name="frmMain" id="frmMain" method="post" action="/myaccount/account-info-update">
		
		<div style="width:628px;height:auto;overflow:hidden;margin:0 auto 80px auto;">
			<div class="accountinputbox">
				<fieldset id="account_information">
					<legend>Account Information</legend><br>
					<p>
						<label for="email">Email Address / Username<?php echo form_error('email', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo $this->session->userdata('email') ?>" id="email" name="email" class="inputtext" maxlength="64" readonly="true" style="color:#999999">
					</p>
					<p>
						<label for="firstname">First Name<?php echo form_error('firstname', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo $this->session->userdata('firstname') ?>" id="firstname" name="firstname" class="inputtext" maxlength="24">
					</p>
					<p>
						<label for="lastname">Last Name<?php echo form_error('lastname', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo $this->session->userdata('lastname') ?>" id="lastname" name="lastname" class="inputtext" maxlength="24">
					</p>
					<p>
						<label for="phone">Phone<?php echo form_error('phone', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<?php if ($_POST) { ?>
							<input type="text" value="<?php echo set_value('phone') ?>" id="phone" name="phone" class="inputtext" maxlength="16">
						<?php } else { ?>
							<input type="text" value="<?php echo $this->session->userdata('phone') ?>" id="phone" name="phone" class="inputtext" maxlength="16">
						<?php } ?>
					</p>
					<p style="margin-bottom:15px;">
						<label for="date_of_birth">Date of Birth</label><br>
						<select name="year_of_birth" id="year_of_birth" size="1" class="dropdown" style="width:95px;margin-right:11px;">
							<?php for ($i = 1940; $i <= date("Y"); $i++) { ?>
								<option value="<?php echo $i ?>" <?php echo ($this->session->userdata('year_of_birth') == $i) ? "selected='selected'" : "" ?> ><?php echo $i ?></option>
							<?php } ?>
						</select>
						<select name="month_of_birth" id="month_of_birth" class="dropdown" style="width:75px;margin-right:11px;">
							<?php for ($i = 1; $i <= 12; $i++) { ?>
								<option value="<?php echo $i ?>" <?php echo ($this->session->userdata('month_of_birth') == $i) ? "selected='selected'" : "" ?> ><?php echo $i ?></option>
							<?php } ?>
						</select>
						<select name="day_of_birth" id="day_of_birth" size="1" class="dropdown" style="width:75px;float:right;">
							<?php for ($i = 1; $i <= 31; $i++) { ?>
								<option value="<?php echo $i ?>" <?php echo ($this->session->userdata('day_of_birth') == $i) ? "selected='selected'" : "" ?> ><?php echo $i ?></option>
							<?php } ?>
						</select>
					</p>		
					<p>
						<input type="checkbox" value="1" name="subscribe" id="subscribe" <?php echo ($this->session->userdata('subscribe') == 1) ? "checked='checked'" : "" ?>>
						<label for="subscribe">Subscribe to Monthly Newsletter</label>
					</p>
				</fieldset>
			</div>
			
			<div class="accountinputboxverticaldivider">&nbsp;</div>
			
			<div class="accountinputbox">
				<fieldset id="billing_information">
					<legend>Billing Information</legend><br>
					<p>
						<label for="bill_address">Address<?php echo form_error('bill_address', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo $this->session->userdata('bill_address') ?>" id="bill_address" name="bill_address" class="inputtext" maxlength="120">
					</p>
					<p style="width:206px;float:left;">
						<label for="bill_city">City<?php echo form_error('bill_city', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo $this->session->userdata('bill_city') ?>" id="bill_city" name="bill_city" class="inputtext" style="width:186px;" maxlength="24">
					</p>
					<p style="width:60px;float:left;">
						<label for="bill_zipcode">Zip<?php echo form_error('bill_zipcode', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo $this->session->userdata('bill_zipcode') ?>" id="bill_zipcode" name="bill_zipcode" class="inputtext" style="width:60px;" maxlength="12">
					</p>
					<p>
						<label for="bill_state">State<?php echo form_error('bill_state', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<select id="bill_state" name="bill_state" class="dropdown" style="width:195px;">
							<option selected="selected" value="">Please Select</option>
							<?php foreach ($this->continental as $state) { ?>
								<option value="<?php echo $state->id ?>" <?php echo ($this->session->userdata('bill_state') == $state->id) ? "selected='selected'" : "" ?> ><?php echo $state->state ?></option>
							<?php } ?>
							<option value="0">Other</option>
						</select>
					</p>
					
					<div style="width:260px;height:auto;overflow:hidden;margin-top:20px;">
						<div style="float:left;margin-top:-2px;">
							<?php if ($_POST) { ?>
								<input type="checkbox" value="<?php echo (isset($_POST['shipSame']) && $_POST['shipSame'] == 1) ? "1" : "0" ?>" name="shipSame" id="shipSame" <?php (isset($_POST['shipSame']) && ($_POST['shipSame'] == 1)) ? "checked='checked'" : "" ?> class="inputtext">
							<?php } else { ?>
								<input type="checkbox" value="<?php echo $this->session->userdata('shipsame') ?>" name="shipSame" id="shipSame" <?php echo ($this->session->userdata('shipsame') == 1) ? "checked='checked'" : "" ?> class="inputtext">
							<?php }?>
						</div>
						<div style="float:right;font-size:1.083em;">Shipping address same as billing address</div>
					</div>
					
				</fieldset>
	
				<fieldset id="shipping_information">
					<legend>Shipping Information</legend><br>
					<p>
						<label for="ship_address">Address<?php echo form_error('ship_address', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>						
						<?php if ($_POST) { ?>
						<?php if (isset($_POST['shipSame']) && $_POST['shipSame'] == 1) { ?>
							<input type="text" value="<?php echo $this->session->userdata('ship_address') ?>" id="ship_address" name="ship_address" class="inputtext" maxlength="120">
						<?php } else { ?>
							<input type="text" value="<?php echo (isset($_POST['ship_address']) ? $_POST['ship_address'] : "") ?>" id="ship_address" name="ship_address" class="inputtext" maxlength="120">
						<?php }?>
						<?php } else { ?>
							<input type="text" value="<?php echo $this->session->userdata('ship_address') ?>" id="ship_address" name="ship_address" class="inputtext" maxlength="120">
						<?php }?>
					</p>
					<p style="width:206px;float:left;">
						<label for="ship_city">City<?php echo form_error('ship_city', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<?php if ($_POST) { ?>
						<?php if (isset($_POST['shipSame']) && $_POST['shipSame'] == 1) { ?>
							<input type="text" value="<?php echo $this->session->userdata('ship_city') ?>" id="ship_city" name="ship_city" class="inputtext" style="width:186px;" maxlength="24">
						<?php } else { ?>
							<input type="text" value="<?php echo (isset($_POST['ship_city']) ? $_POST['ship_city'] : "") ?>" id="ship_city" name="ship_city" class="inputtext" style="width:186px;" maxlength="24">
						<?php }?>
						<?php } else { ?>
							<input type="text" value="<?php echo $this->session->userdata('ship_city') ?>" id="ship_city" name="ship_city" class="inputtext" style="width:186px;" maxlength="24">
						<?php }?>
					</p>
					<p style="width:60px;float:left;">
						<label for="ship_zipcode">Zip<?php echo form_error('ship_zipcode', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<?php if ($_POST) { ?>
						<?php if (isset($_POST['shipSame']) && $_POST['shipSame'] == 1) { ?>
							<input type="text" value="<?php echo $this->session->userdata('ship_zipcode') ?>" id="ship_zipcode" name="ship_zipcode" class="inputtext" style="width:60px;" maxlength="12">
						<?php } else { ?>
							<input type="text" value="<?php echo (isset($_POST['ship_zipcode']) ? $_POST['ship_zipcode'] : "") ?>" id="ship_zipcode" name="ship_zipcode" class="inputtext" style="width:60px;" maxlength="12">
						<?php }?>
						<?php } else { ?>
							<input type="text" value="<?php echo $this->session->userdata('ship_zipcode') ?>" id="ship_zipcode" name="ship_zipcode" class="inputtext" style="width:60px;" maxlength="12">
						<?php }?>
					</p>
					<p>
						<label for="ship_state">State<?php echo form_error('ship_state', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						
						<?php if ($_POST) { ?>
						<?php if (isset($_POST['shipSame']) && $_POST['shipSame'] == 1) { ?>
							<select id="ship_state" name="ship_state" class="dropdown" style="width:195px;">
								<option selected="selected" value="">Please Select</option>
								<?php foreach ($this->continental as $state) { ?>
									<option value="<?php echo $state->id ?>" <?php echo ($this->session->userdata('ship_state') == $state->id) ? "selected='selected'" : "" ?> ><?php echo $state->state ?></option>
								<?php } ?>
								<option value="0">Other</option>
							</select>
						<?php } else { ?>
							<select id="ship_state" name="ship_state" class="dropdown" style="width:195px;">
								<option selected="selected" value="">Please Select</option>
								<?php foreach ($this->continental as $state) { ?>
									<option value="<?php echo $state->id ?>" <?php echo (isset($_POST['ship_state']) && $state->id == $_POST['ship_state']) ? "selected='selected'" : "" ?> ><?php echo $state->state ?></option>
								<?php } ?>
								<option value="0">Other</option>
							</select>
						<?php }?>
						<?php } else { ?>
							<select id="ship_state" name="ship_state" class="dropdown" style="width:195px;">
								<option selected="selected" value="">Please Select</option>
								<?php foreach ($this->continental as $state) { ?>
									<option value="<?php echo $state->id ?>" <?php echo ($this->session->userdata('ship_state') == $state->id) ? "selected='selected'" : "" ?> ><?php echo $state->state ?></option>
								<?php } ?>
								<option value="0">Other</option>
							</select>
						<?php }?>
						

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

