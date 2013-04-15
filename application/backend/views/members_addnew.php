<?php
$is_checked = "";
if (isset($_POST['shipSame'])) {
	if ($_POST['shipSame'] == 1) {
		$is_checked = "checked=\"checked\"";
	} else {
		$is_checked = "";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});
				
		jQuery("#shipSame").click(function(){
			if (jQuery(this).attr("checked") == true) {
				jQuery("#ship_firstname").attr("disabled", "true").val("");
				jQuery("#ship_lastname").attr("disabled" , "true").val("");
				jQuery("#ship_address").attr("disabled"  , "true").val("");
				jQuery("#ship_city").attr("disabled"     , "true").val("");
				jQuery("#ship_state").attr("disabled"    , "true").attr("selectedIndex", "0");
				jQuery("#ship_zipcode").attr("disabled"  , "true").val("");
				jQuery("#ship_country").attr("disabled"  , "true").attr("selectedIndex", "0");
			} else {
				jQuery("#ship_firstname").attr("disabled", "");
				jQuery("#ship_lastname").attr("disabled" , "");
				jQuery("#ship_address").attr("disabled"  , "");
				jQuery("#ship_city").attr("disabled"     , "");
				jQuery("#ship_state").attr("disabled"    , "");
				jQuery("#ship_zipcode").attr("disabled"  , "");
				jQuery("#ship_country").attr("disabled"  , "");	
			}
		});
		
		if (jQuery("#shipSame").attr("checked") == true) {
			jQuery("#ship_firstname").attr("disabled", "true").val("");
			jQuery("#ship_lastname").attr("disabled" , "true").val("");
			jQuery("#ship_address").attr("disabled"  , "true").val("");
			jQuery("#ship_city").attr("disabled"     , "true").val("");
			jQuery("#ship_state").attr("disabled"    , "true").attr("selectedIndex", "0");
			jQuery("#ship_zipcode").attr("disabled"  , "true").val("");
			jQuery("#ship_country").attr("disabled"  , "true").attr("selectedIndex", "0");
		}
	});
	

	
</script>
</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Create a new Customer</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/members" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/members/save">
						
						<table border="0" cellspacing="5" cellpadding="5" style="margin-bottom:10px;">
							<tr>
								<td width="12%" class="fieldlabel">Username</td>
								<td width="38%"><input style="width:100%;" type="text" name="username" id="username" value="<?php echo set_value('username'); ?>"></td>

								<td width="12%" class="fieldlabel">Email</td>
								<td width="38%"><input style="width:100%;" type="text" name="email" id="email" value="<?php echo set_value('email'); ?>"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Password</td>
								<td><input style="width:100%;" type="password" name="password" id="password" value="<?php echo set_value('password'); ?>"></td>

								<td class="fieldlabel">Verify Password</td>
								<td><input style="width:100%;" type="password" class="" id="passconf" name="passconf" size="" value="<?php echo set_value('passconf') ?>"></td>
							</tr>
							<tr>
								<td class="fieldlabel">First Name</td>
								<td><input style="width:100%;" type="text" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>"></td>

								<td class="fieldlabel">Last Name</td>
								<td><input style="width:100%;" type="text" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>"></td>
							</tr>
						</table>
						<table style="margin-bottom:10px;">
						<thead>
							<tr>
								<th colspan="4" style="text-align:left;">Billing and Shipping Information</th>
							</tr>
						</thead>	
							<!--
							<tr>
								<td colspan="4" style="border-left:1px solid #ccc;color:#222;background-color:#e6e6e6;text-shadow: 0 1px 0 #FFFFFF;font-weight:bold;">Billing Information</td>
							</tr>
							
							
							<tr>
								<td width="12%" class="fieldlabel">First Name</td>
								<td width="38%"><input style="width:100%;" type="text" name="bill_firstname" id="bill_firstname" value="<?php echo set_value('bill_firstname'); ?>"></td>

								<td width="12%" class="fieldlabel">Last Name</td>
								<td width="38%"><input style="width:100%;" type="text" name="bill_lastname" id="bill_lastname" value="<?php echo set_value('bill_lastname'); ?>"></td>
							</tr>
							-->
							
							<tr>
								<td class="fieldlabel" width="12%">Billing Address</td>
								<td width="38%"><input style="width:100%;" type="text" name="bill_address" id="bill_address" value="<?php echo set_value('bill_address'); ?>"></td>

								<td class="fieldlabel" width="12%">Billing City</td>
								<td width="38%"><input style="width:100%;" type="text" name="bill_city" id="bill_city" value="<?php echo set_value('bill_city'); ?>"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Billing State</td>
								<td>
									<select name="bill_state" id="bill_state">
									<?php
										$result = $this->db->query("SELECT * FROM `state` ORDER BY `sharthand` asc");
											$states = $result->result();
											foreach ($states as $state) {
												if (isset($_POST['state'])) {
													if ($state->id == $_POST['state']) {
														printf("<option value=\"%s\" selected=\"selected\">%s</option>", $state->id, $state->state);
													} else {
														printf("<option value=\"%s\">%s</option>", $state->id, $state->state);
													}
												} else {
													if ($state->id == set_value('bill_state')) {
														printf("<option value=\"%s\" selected=\"selected\">%s</option>", $state->id, $state->state);
													} else {
														printf("<option value=\"%s\">%s</option>", $state->id, $state->state);
													}
												}											
											}
									?>
									</select>
								</td>

								<td class="fieldlabel">Billing Zip</td>
								<td><input style="width:50%;" type="text" name="bill_zipcode" id="some_name" value="<?php echo set_value('bill_zipcode'); ?>"></td>
							</tr>
							<tr>
								<!--
								<td class="fieldlabel">Billing Country</td>
								<td>
									<select name="bill_country" id="bill_country">
									<?php
										$result = $this->db->query("SELECT * FROM country ORDER BY sharthand asc");
										$countrys = $result->result();
										foreach ($countrys as $country) {
											if (isset($_POST['bill_country'])) {
												if ($country->id == $_POST['bill_country']) {
													printf("<option value=\"%s\" selected=\"selected\">%s</option>", $country->id, $country->country);
												} else {
													printf("<option value=\"%s\">%s</option>", $country->id, $country->country);
												}
											} else {
												if ($country->id == set_value('bill_country')) {
													printf("<option value=\"%s\" selected=\"selected\">%s</option>", $country->id, $country->country);
												} else {
													printf("<option value=\"%s\">%s</option>", $country->id, $country->country);
												}
											}											
										}
									?>
									</select>
								</td>
								-->
								<td class="fieldlabel">
									<input type="checkbox" id="shipSame" name="shipSame" value="1" <?php echo $is_checked; ?>>
								</td>
								<td colspan="3">
									Check here if shipping address is the same as billing address
								</td>
							</tr>
						<!--</table>
						<table>
						<thead>
							<tr>
								<th colspan="4" style="text-align:left;">Shipping Information</th>
							</tr>
						</thead>
							
							<tr>
								<td width="12%" class="fieldlabel">First Name</td>
								<td width="38%"><input style="width:100%;" type="text" name="ship_firstname" id="ship_firstname" value="<?php echo set_value('ship_firstname'); ?>"></td>

								<td width="12%" class="fieldlabel">Last Name</td>
								<td width="38%"><input style="width:100%;" type="text" name="ship_lastname" id="ship_lastname" value="<?php echo set_value('ship_lastname'); ?>"></td>
							</tr>
							-->
							<tr>
								<td class="fieldlabel">Shipping Address</td>
								<td><input style="width:100%;" type="text" name="ship_address" id="ship_address" value="<?php echo set_value('ship_address'); ?>"></td>

								<td class="fieldlabel">Shipping City</td>
								<td><input style="width:100%;" type="text" name="ship_city" id="ship_city" value="<?php echo set_value('ship_city'); ?>"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Shipping State</td>
								<td>
									<select name="ship_state" id="ship_state">
									<?php
										$result = $this->db->query("SELECT * FROM state ORDER BY sharthand asc");
										$states = $result->result();
										foreach ($states as $state) {
											if (isset($_POST['state'])) {
												if ($state->id == $_POST['ship_state']) {
													printf("<option value=\"%s\" selected=\"selected\">%s</option>", $state->id, $state->state);
												} else {
													printf("<option value=\"%s\">%s</option>", $state->id, $state->state);
												}
											} else {
												if ($state->id == set_value('ship_state')) {
													printf("<option value=\"%s\" selected=\"selected\">%s</option>", $state->id, $state->state);
												} else {
													printf("<option value=\"%s\">%s</option>", $state->id, $state->state);
												}
											}											
										}
									?>
									</select>
								</td>

								<td class="fieldlabel">Shipping Zip</td>
								<td><input style="width:50%;" type="text" name="ship_zipcode" id="ship_zipcode" value="<?php echo set_value('ship_zipcode'); ?>"></td>
							</tr>
							<!--
							<tr>
								<td class="fieldlabel">Shipping Country</td>
								<td colspan="3">
									<select name="ship_state" id="ship_country">
									<?php
										$result = $this->db->query("SELECT * FROM country ORDER BY sharthand asc");
										$countrys = $result->result();
										foreach ($countrys as $country) {
											if (isset($_POST['ship_country'])) {
												if ($country->id == $_POST['ship_country']) {
													printf("<option value=\"%s\" selected=\"selected\">%s</option>", $country->id, $country->country);
												} else {
													printf("<option value=\"%s\">%s</option>", $country->id, $country->country);
												}
											} else {
												if ($country->id == set_value('ship_country')) {
													printf("<option value=\"%s\" selected=\"selected\">%s</option>", $country->id, $country->country);
												} else {
													printf("<option value=\"%s\">%s</option>", $country->id, $country->country);
												}
											}											
										}
									?>
									</select>
								</td>
							</tr>
							-->
							
						</table>
											
						<input type="hidden" name="action" value="addnew" id="action">
						<input type="hidden" name="id" value="-1" id="id">
					
					</form>
				</div>
				
			</div>			
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<?php $this->load->view('base/footer') ?>
	</div>
</body>
</html>