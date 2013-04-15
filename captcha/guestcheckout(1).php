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
	<script type="text/javascript" charset="utf-8" src="/js/checkout.js"></script>
	<script type="text/javascript" charset="utf-8" src="/js/formFunctions.js"></script>

	<style type="text/css" media="screen">
		.error {
			color:#d02121;
		}
	</style>
	<?php $this->load->view('base/ga') ?>
	
	<script>



function zipcodes(the_value){
	var action = '/zipcodes/'+the_value;
	//window.location.href = action;
	var uid = '';
	ajaxFunction(action,uid, 'ajax_amount');
	
	
}

function zipcodes_with_state(the_value){
	var action = '/zipcodes/with_state/'+the_value;
	//window.location.href = action;
	var uid = '';
	ajaxFunction(action,uid, 'ajax_amount');
	
	
}


function zip_wise_tax(){
	var is_shipsame = 'no';
	if (jQuery("#shipSame").is(":checked")) {
		var is_shipsame = 'yes';
	}
		var zip_code_js = jQuery("#bill_zipcode").val();	
		var bill_state = jQuery("#bill_state").val();	
		zip_code_js = zip_code_js+'_'+bill_state;
		
		zipcodes_with_state(zip_code_js);
	
}


</script>	

</head>
<body id="checkout">
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
		
		<div id="topnav" style="font-size:15px;">
			<a href="/viewcart" style="font-weight:normal" >view cart</a> &raquo; checkout
		</div>
		
		
		<?php
			if ($this->session->flashdata('wrong_password') == 'yes') {
		?>
			<div class="errormessage">
			
				<p class="error">	
					This email id already exists on Josiemarancosmetics.com. Would you like to:
					<ul style="list-style:decimal outside none; padding-left:20px;">
						
						<li><a href="/signin" >Log in and check out as a member</a></li>
						<li>Continue with guest checkout using a different email</li>
					</ul>
				</p>
			
			</div>
	    <?php
			}
		?>


		<div id="pagetitle"><h1>Guest Checkout</h1></div>
		
		
		
		<?php
		if (isset($this->ErrorMessage) && (!empty($this->ErrorMessage))) {
		?>
		<div class="errormessage">
			<p class="error"><?php echo $this->ErrorMessage; ?></p>
		</div>
		<?php
		}
		?>
			<?php
			$style = "display:none";
			if($this->zip_wise_tax){
				$style = "";
			}
		?>
		<div class="errormessage" id="zipcode_msg" style=" <?php echo $style; ?>">
			<p class="error">You have been charged <?php echo $this->zip_wise_tax; ?>% Sales tax</p>
		</div>
		<form action="<?php echo secure_base_url() ?>guestcheckout/submit" method="post" accept-charset="utf-8">
		<div class="accountinputbox">
			<fieldset id="account_information">
				<legend>Account Information</legend><br>
				<p>
					<label for="firstname">First Name*<?php echo form_error('firstname', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('firstname') ?>" id="firstname" name="firstname" class="inputtext" maxlength="24">
				</p>
				<p>
					<label for="lastname">Last Name*<?php echo form_error('lastname', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('lastname') ?>" id="lastname" name="lastname" class="inputtext" maxlength="24">
				</p>
				<p>
					<label for="email">Email Address*<?php echo form_error('email', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('email') ?>" id="email" name="email" class="inputtext" maxlength="64">
				</p>
				<p>
					<label for="phone">Phone*<?php echo form_error('phone', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('phone') ?>" id="phone" name="phone" class="inputtext" maxlength="16">
				</p>
				<p>
						<label for="confirm_email">Confirm Email Address*<?php echo form_error('confirm_email', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="text" value="<?php echo set_value('confirm_email') ?>" id="confirm_email" name="confirm_email" class="inputtext" maxlength="64">
				</p>
				 
				 <p>
						<label for="password">Password<?php echo form_error('password', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="password" value="" id="password" name="password" class="inputtext" maxlength="24">
					</p>
					<p>
						<label for="passconf">Verify Password<?php echo form_error('passconf', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
						<input type="password" value="" id="passconf" name="passconf" class="inputtext" maxlength="24">
					</p>
				 
				
			</fieldset>
		</div>
		
		<div class="accountinputboxverticaldivider">&nbsp;</div>
		
		<div class="accountinputbox">
			<fieldset id="billing_information">
				<legend>Billing Information</legend><br>
				<p>
					<label for="bill_address">Address*<?php echo form_error('bill_address', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('bill_address') ?>" id="bill_address" name="bill_address" class="inputtext" maxlength="120">
				</p>
				<p style="width:206px;float:left;">
					<label for="bill_city">City*<?php echo form_error('bill_city', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('bill_city') ?>" id="bill_city" name="bill_city" class="inputtext" style="width:186px;" maxlength="24">
				</p>
				<p style="width:60px;float:left;">
					<label for="bill_zipcode">Zip*<?php echo form_error('bill_zipcode', '<span class="error">', '</span>'); ?></label><br>
					<input type="text" onBlur="javascript:zip_wise_tax();" value="<?php echo set_value('bill_zipcode') ?>" id="bill_zipcode" name="bill_zipcode" class="inputtext" style="width:60px;" maxlength="12">
				</p>
                <p>
                    <label for="bill_country">Country*<?php echo form_error('bill_country', '<span class="error">', '</span>'); ?></label><br>
                    <select name="bill_country" id="bill_country" size="1" class="dropdown" style="width:195px;">
                        <option
                            value="1" <?php echo isset($_POST['bill_country']) && $_POST['bill_country'] == 1 ? 'selected="selected"' : '';?> >
                            US
                        </option>
                        <option
                            value="40" <?php echo isset($_POST['bill_country']) && $_POST['bill_country'] == 40 ? 'selected="selected"' : '';?> >
                            Canada
                        </option>
                    </select>
                </p>
				<p>
					<label for="bill_state">State*<?php echo form_error('bill_state', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<select id="bill_state" name="bill_state" class="dropdown" style="width:195px;" onChange="javascript:zip_wise_tax();" >
						<option selected="selected" value="">Please Select</option>
						<?php foreach ($this->continental as $state) { ?>
							<option value="<?php echo $state->id ?>" <?php echo (isset($_POST['bill_state']) && $_POST['bill_state'] == $state->id) ? "selected='selected'" : "" ?> ><?php echo $state->state ?></option>
						<?php } ?>
						<option value="0">Other</option>
					</select>
				</p>
				<div style="width:260px;height:auto;overflow:hidden;margin-top:20px;">
					<div style="float:left;margin-top:-2px;">
						<?php
                            $c1 = "";
							if (isset($_POST['shipSame'])) {
								if ($_POST['shipSame'] == 1) {
									$c1 = "checked=\"checked\"";
								}
							}

                            $shipping_option = $this->session->userdata('ShippingOptions');
                            if($shipping_option[0]['id'] == 99) {
                                $style = 'style="visibility:hidden"';
                                $style1 = 'visibility:hidden;';
                                $c1 = "checked=\"checked\"";
                            } else {
                                $style = '';
                                $style1 = '';
                            }
							$ds = $this->session->userdata('DestinationState');		
						?>
						<input  onClick="zip_wise_tax();"  type="checkbox" value="1" name="shipSame" id="shipSame" <?php echo $c1 ?> class="inputtext" <?php echo $style?>>
					</div>
					<div style="float:right;font-size:1.083em;<?php echo $style1?>">Shipping address same as billing address</div>
                    <?php if($style != '') :?>
                    <div style="font-size:1.083em;text-align: left;">
                        <br />
                        <br />
                        Shipping Information not needed.<br />
                        Your eGift card will be delivered via email
                    </div>
                    <?php endif; ?>
				</div>
			</fieldset>

			<fieldset id="shipping_information" <?php echo $style?>>
				<legend>Shipping Information</legend><br>
				<p>
					<label for="ship_first_name">Recipient First Name*<?php echo form_error('ship_first_name', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('ship_first_name') ?>" id="ship_first_name" name="ship_first_name" class="inputtext" maxlength="100">
				</p>
				<p>
					<label for="ship_last_name">Recipient Last Name*<?php echo form_error('ship_last_name', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('ship_last_name') ?>" id="ship_last_name" name="ship_last_name" class="inputtext" maxlength="100">
				</p>
				<p>
					<label for="ship_address">Address*<?php echo form_error('ship_address', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('ship_address') ?>" id="ship_address" name="ship_address" class="inputtext" maxlength="120">
				</p>
				<p>
					<input type="text" value="<?php echo set_value('ship_address2') ?>" id="ship_address2" name="ship_address2" class="inputtext" maxlength="120">
				</p>
				<p style="width:206px;float:left;">
					<label for="ship_city">City*<?php echo form_error('ship_city', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('ship_city') ?>" id="ship_city" name="ship_city" class="inputtext" style="width:186px;" maxlength="24">
				</p>
				<?php $ds = $this->session->userdata('DestinationState'); ?>
				<p style="width:60px;float:left;">
					<label for="ship_zipcode">Zip*<?php echo form_error('ship_zipcode', '<span class="error">', '</span>'); ?></label><br>
					<input type="text" value="<?php echo set_value('ship_zipcode') ?>" id="ship_zipcode" name="ship_zipcode" class="inputtext" style="width:60px;" maxlength="12" onBlur="javascript:zipcodes(this.value);" >
				</p>
                <p>
                    <?php
                        $country = $this->session->userdata('DestinationCountry') == 1 ? 'US' : 'Canada';
                    ?>
                    <label for="ship_state">Country</label><br>
                    <input type="text" name="ship_country" value="<?php echo $country ?>" id="ship_country" readonly="true" class="inputtext" style="color:#999;width:186px;">
                </p>
				<p>
					<?php
					$ds = $this->session->userdata('DestinationState');
					?>
					<label for="ship_state">State</label><br>
					<input type="text" name="ship_state" value="<?php echo $ds[0]['tax_code'] ?>" id="ship_state" readonly="true" class="inputtext" style="color:#999;width:186px;">
				</p>
			</fieldset>
		</div>
		
		<div class="accountinputboxverticaldivider">&nbsp;</div>
		
		<?php
		/**
		 * 2011/06/14 特別狀態
		 * 這個是特殊狀態, 由於要先提供給客戶退換貨的功能, 所以要先打開這個功能
		 * 當客戶輸入 Discount Code, 總數小於等於零, 就要先讓他先寫入訂單
		 */
		$Discount = ($this->session->userdata('Discount_Sub_Total') ? $this->session->userdata('Discount_Sub_Total') : 0);
		$SPECIAL_STATE = '';		
		if ($this->session->userdata('Amount') == 0 && $Discount != 0) {
			$SPECIAL_STATE = "display:none";
		}
		
		?>
		
		<div class="accountinputbox" style="height:520px;">
			<span style="<?php echo $SPECIAL_STATE ?>">
			<fieldset id="payment_method" style="margin-bottom:23px;">
				<legend>Payment Information</legend><br>
				<?php
						$style = 'style="display:none"';
						if($this->session->userdata('Amount') > 0) {
							$style = 'style="display:block"';
						}
					?>
                    <p <?php echo $style; ?> >
					<?php
						$p1 = "";
						$p2 = "";
						if (isset($_POST['payment_method'])) {
							if ($_POST['payment_method'] == 1) {
								$p1 = "checked=\"checked\"";
								$p2 = "";
							} else {
								$p1 = "";
								$p2 = "checked=\"checked\"";
							}
						} else {
							$p1 = "checked=\"checked\"";
							$p2 = "";
						}
					?>
					<input type="radio" value="1" name="payment_method" <?php echo $p1 ?>>
					<label>Credit Card</label>
					
					<input type="radio" value="2" name="payment_method" <?php echo $p2 ?> style="margin-left:20px;">
					<label for="paypal">PayPal</label>
					
				</p>
			</fieldset>
			<div <?php echo $style; ?>>
			<fieldset id="paypal_information" <?php echo ($p2 != "") ? "style='display:block'" : "style='display:none'" ?>>
				<div>
					Please Note: Your order will not process if you do not wait for the confirmation page from our site after you complete your payment via PayPal. Please be sure to wait for the confirmation page to complete loading before closing your browser. 
				</div>					
			</fieldset>
			
			<fieldset id="card_information" <?php echo ($p1 != "") ? "style='display:block'" : "style='display:none'" ?>>
				<p>
					<label>Name on Card*<?php echo form_error('name_on_card', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label>
					<input type="text" name="name_on_card" value="<?php echo set_value('name_on_card') ?>" id="name_on_card" class="inputtext">
				</p>
				<p>	
					<label>Card Type*<?php echo form_error('card_type', '<br><span class="error">', '</span>'); ?></label>
					<select name="card_type" id="card_type" size="1" class="dropdown" style="width:100%">
						<option value="1">Visa</option>
					    <option value="2">Master Card</option>
					    <option value="3">Bank Card</option>
					    <option value="4">American Express</option>
					    <option value="5">Diners Club</option>
					    <option value="6">JCB</option>
					    <option value="7">Discover</option>						
					</select>
				</p>
				<p>
					<label>Card Number*<?php echo form_error('card_number', '<span class="error">&nbsp;&nbsp;', '</span>'); ?></label>
					<input type="text" name="card_number" value="<?php echo set_value('card_number') ?>" id="card_number" class="inputtext">
				</p>
				<p style="float:left;">
					<label>Card Expiry*</label><br>
					<select name="CardExpiryMonth" id="CardExpiryMonth" size="1" class="dropdown" style="width:70px;margin-right:5px;">
						<?php for ($i=1; $i <= 12; $i++) { 
							printf("<option value=\"%s\" %s >%s</option>", $i, ($_POST['CardExpiryMonth'] == $i) ? "selected='selected'" : "", ($i <= 9) ? "0".$i : $i);
						} ?>
					</select>
					<select name="CardExpiryYear" id="CardExpiryYear" size="1" class="dropdown" style="width:90px;">
					<?php
					foreach ($this->CardExpiryYear as $expiryYear) {
						if($expiryYear >= date('Y')) {	
							printf("<option value=\"%s\" %s >%s</option>", $expiryYear, ($_POST['CardExpiryYear'] == $expiryYear) ? "selected='selected'" : "", $expiryYear);
						}
					}
					?>
					</select>
				</p>
				<p style="float:right;">
					<label>CCV Number*</label><br>
					<input type="text" name="ccv" value="<?php echo set_value('ccv') ?>" id="ccv" size="6" maxlength="4" class="inputtext" style="width:75px;">
				</p>
			</fieldset><?php echo form_error('ccv', '<div class="error" style="text-align:right;margin-top:-25px;">', '</div>'); ?>
			</span>
			
			</div>
			
			<fieldset>
				<p style="clear:both;margin-top:7px;">&nbsp;</p>
				<p style="font-size:1.667em;">
					<label>Total Amount</label>
					$<span id="ajax_amount"><?php if($this->session->userdata('Amount')) { echo $this->session->userdata('Amount'); }else { echo '0.00'; } ?></span>
				</p>
				<input type="submit" name="btnSubmit" value="Place Order" id="btnSubmit" class="inputbutton" style="font-size:1.667em;padding:5px 10px;height:40px;">
			</fieldset>
			
		</div>
		 <input type="hidden" name="go_func" value="confirm" >
		</form>
	</div>
	
	<?php $this->load->view('base/footer_cart') ?>
	<?php //$this->load->view('base/facebook') ?>
	
</body>
</html>