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
				shipSame(1);
			} else {
				shipSame(0);
			}
		});
		
		shipSame(<?php echo $this->user[0]->shipsame ?>)
		
	});
	
	function shipSame (ndx) {
		if (ndx == 1) {
			jQuery("#ship_firstname").attr("disabled", "true").val("");
			jQuery("#ship_lastname").attr("disabled" , "true").val("");
			jQuery("#ship_address").attr("disabled"  , "true").val("");
			jQuery("#ship_city").attr("disabled"     , "true").val("");
			jQuery("#ship_state").attr("disabled"    , "true").attr("selectedIndex", "0");
			jQuery("#ship_zipcode").attr("disabled"  , "true").val("");
			jQuery("#ship_country").attr("disabled"  , "true").attr("selectedIndex", "0");
		} else {
			jQuery("#ship_firstname").attr("disabled", "").val("<?php echo $this->user[0]->ship_firstname ?>");
			jQuery("#ship_lastname").attr("disabled" , "").val("<?php echo $this->user[0]->ship_lastname ?>");
			jQuery("#ship_address").attr("disabled"  , "").val("<?php echo $this->user[0]->ship_address ?>");
			jQuery("#ship_city").attr("disabled"     , "").val("<?php echo $this->user[0]->ship_city ?>");
			//	這要在檢查一下...
			// jQuery("#ship_state").attr("disabled"    , "").attr("selectedIndex", "<?php echo $this->user[0]->ship_state ?>");
			jQuery("#ship_zipcode").attr("disabled"  , "").val("<?php echo $this->user[0]->ship_zipcode ?>");
			jQuery("#ship_country").attr("disabled"  , "").attr("selectedIndex", "<?php echo $this->user[0]->ship_country ?>");
		}
	}
	
	function update_order (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/orders';
		// jQuery("#frmMain").submit();
		document.frmMain.submit();
	}
	
	function customercase (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/customercase';
		jQuery("#frmMain").submit();
	}
	
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
					<h1><b>Edit an existing Customer Information</b></h1>
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/members">
						
						<table border="0" style="margin-bottom:20px;">
						<tr>
							<td width="33%" style="border-right:1px dotted #ccc;vertical-align:top;">
								<table class="inner">
									<thead>
										<tr>
											<th colspan="2" style="text-align:left;">Customer Information</th>
										</tr>
									</thead>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td class="var">Username</td>
										<td><input type="text" name="username" id="username" value="<?php echo $this->user[0]->username ?>"></td>
									</tr>
									<tr>
										
										<td class="var">Email</td>
										<td><input type="text" name="email" id="email" value="<?php echo $this->user[0]->email ?>"></td>
									</tr>
									<tr>
										<td class="var">First Name</td>
										<td><input type="text" name="firstname" id="firstname" value="<?php echo $this->user[0]->firstname ?>"></td>
									</tr>
									<tr>
										
										<td class="var">Last Name</td>
										<td><input type="text" name="lastname" id="lastname" value="<?php echo $this->user[0]->lastname ?>"></td>
									</tr>
								</table>		
							</td>
							
							<td width="33%" style="border-right:1px dotted #ccc;vertical-align:top;">
								<table class="inner">
									<thead>
										<tr>
											<th colspan="2">Billing Information</th>
										</tr>
									</thead>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td class="var">Billing Address</td>
										<td><input type="text" name="bill_address" id="bill_address" value="<?php echo $this->user[0]->bill_address ?>"></td>
									</tr>
									<tr>
		
										<td class="var">Billing City</td>
										<td><input type="text" name="bill_city" id="bill_city" value="<?php echo $this->user[0]->bill_city ?>"></td>
									</tr>
									<tr>
										<td class="var">Billing State</td>
										<td>
											<select name="bill_state" id="bill_state">
											<?php
												$result = $this->db->query("SELECT * FROM state ORDER BY sharthand asc");
												$states = $result->result();
												foreach ($states as $state) {
													if (isset($_POST['state'])) {
														if ($state->id == $_POST['state']) {
															printf("<option value=\"%s\" selected=\"selected\">%s</option>", $state->id, $state->state);
														} else {
															printf("<option value=\"%s\">%s</option>", $state->id, $state->state);
														}
													} else {
														if ($state->id == $this->user[0]->bill_state) {
															printf("<option value=\"%s\" selected=\"selected\">%s</option>", $state->id, $state->state);
														} else {
															printf("<option value=\"%s\">%s</option>", $state->id, $state->state);
														}
													}											
												}
											?>
											</select>
										</td>
									</tr>
									<tr>
		
										<td class="var">Billing Zip</td>
										<td><input type="text" name="bill_zipcode" id="some_name" value="<?php echo $this->user[0]->bill_zipcode ?>"></td>
									</tr>
									<tr>
										<td><input type="checkbox" id="shipSame" name="shipSame" value="1" <?php echo ($this->user[0]->shipsame == 1) ? "checked=\"checked\"" : "" ?> style="width:20px;float:right;"></td>
										<td>
											Shipping address same as billing
										</td>
									</tr>
								</table>
							</td>
							
							<td width="33%" style="vertical-align:top;">
								<table class="inner">
									<thead>
										<tr>
											<th colspan="2" style="text-align:left;">Shipping Information</th>
										</tr>
									</thead>
									<tr>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td class="var">Shipping Address</td>
										<td><input type="text" name="ship_address" id="ship_address" value="<?php echo $this->user[0]->ship_address ?>"></td>
									</tr>
									<tr>
		
										<td class="var">Shipping City</td>
										<td><input type="text" name="ship_city" id="ship_city" value="<?php echo $this->user[0]->ship_city ?>"></td>
									</tr>
									<tr>
										<td class="var">Shipping State</td>
										<td>
											<select name="ship_state" id="ship_state" size="1">
											<?php
												$result = $this->db->query("SELECT * FROM state ORDER BY sharthand asc");
												$states = $result->result();
												foreach ($states as $state) {
													if (isset($_POST['state'])) {
														printf("<option value=\"%s\" %s >%s</option>", $state->id, ($state->id == $this->user[0]->ship_state) ? "selected='selected'" : "", $state->state);
													} else {
														printf("<option value=\"%s\" %s >%s</option>", $state->id, ($state->id == $this->user[0]->ship_state) ? "selected='selected'" : "", $state->state);
													}											
												}
											?>
											</select>
											</td>
										</tr>
										<tr>
			
											<td class="var">Shipping Zip</td>
											<td><input type="text" name="ship_zipcode" id="ship_zipcode" value="<?php echo $this->user[0]->ship_zipcode ?>"></td>
										</tr>
									</tr>
								</table>
							</td>
						</tr>
						</table>
						
						<table style="margin-bottom:20px;width:49%;float:left;" class="exception">
							<thead>
								<tr>
									<th colspan="4" style="text-align:left;">Order History</th>
								</tr>
							</thead>
							<tr style="background-color:#f8f8f8;">
								<td style="font-weight:bold;color:#888">Order No.</td>
								<td style="text-align:center;font-weight:bold;color:#888">Date</td>
								<td style="text-align:center;font-weight:bold;color:#888">Status</td>
								<td style="text-align:right;font-weight:bold;color:#888">Total</td>
							</tr>
							<?php
							$Query = $this->db->query("SELECT id, order_no, order_date, (SELECT `name` FROM order_state where id = o.order_state) as `order_state`, amount FROM `order` as o where user_id = ? order by order_date desc", $this->user[0]->id);
							?>
							<?php foreach ($Query->result() as $order): ?>
							<tr>
								<td>
									<a href="javascript:update_order(<?php echo $order->id ?>);"><?php echo $order->order_no ?></a>
								</td>
								<td style="text-align:center;"><?php echo $order->order_date ?></td>
								<td style="text-align:center;"><?php echo $order->order_state ?></td>
								<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($order->amount, 2) ?></td>
							</tr>
							<?php endforeach ?>
						</table>					
						
						<table class="exception" style="width:49%;float:right;">
							<thead>
								<tr>
									<th colspan="4" style="text-align:left;">Customer Cases</th>
								</tr>
							</thead>
							<tr style="background-color:#f8f8f8;">
								<td style="text-align:left;font-weight:bold;color:#888" width="15%">Date</td>
								<td style="text-align:left;font-weight:bold;color:#888">Case Summary</td>
								<td style="text-align:right;font-weight:bold;color:#888" width="12%">Status</td>
							</tr>
							<?php
							$Query = $this->db->query("SELECT *, (SELECT title FROM customer_cases_status WHERE id = status) as 'status' FROM customer_case WHERE email = ?", $this->user[0]->email);
							?>
							<?php foreach ($Query->result() as $log): ?>
							<tr>
								<td><?php echo $log->created_at?></td>
								<td><a href="javascript:customercase(<?php echo $log->id ?>)"><?php echo $log->comments?></a></td>
								<td style="text-align:right;"><?php echo $log->status ?></td>
							</tr>
							<?php endforeach ?>
						</table>
						
						<input type="hidden" name="action" value="update_save" id="action">
						<input type="hidden" name="id" value="<?php echo $this->user[0]->id ?>" id="id">
					
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