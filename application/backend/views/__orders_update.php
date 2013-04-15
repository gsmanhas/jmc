<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('admin/head'); ?>
<script type="text/javascript" charset="utf-8">
	var base_url = '<?php echo base_url() ?>';
</script>
<script src="<?php echo base_url() ?>js/admin/editor_order.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url() ?>js/admin/blockUI.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() { 
	    $('#shipping_method').change(function() { 
	        $.blockUI();
	        setTimeout(function() { 
	            $.unblockUI({ 
	                onUnblock: function(){ 
						// alert('onUnblock'); 
					} 
	            }); 
	        }, 2000); 
	    }); 
	}); 
	
</script>
</head>
<body>
	<div id="demo8">
		Click Me.
	</div>
	<div id="header">
		<?php 
			$this->load->view('admin/account');
			# 載入 Menu
			$this->load->view('admin/menu'); 
		?>
	</div>
	
	<div id="page-wrapper">
		<div id="main-wrapper">
			<div id="main-content">				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1>Viewing: <b>Order Update</b></h1>
					<div class="other">
						<div class="float-left">Updating Order</div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin/orders" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>
				<form method="post" action="<?php echo base_url() ?>admin/orders" id="frmMain">
					<div class="hastable">
						
						<table border="0" cellspacing="5" cellpadding="5" style="margin-bottom:0px">
							<tr>
								<td colspan="6" style="background-color:#aaaaaa; color: white">Order Info</td>
							</tr>
							<tr>
								<td>Order No</td>
								<td><?php echo $this->Order[0]->order_no ?></td>
								<td>Order Date</td>
								<td><?php echo $this->Order[0]->order_date ?></td>
								<tr>
									<td>Order Status</td>
									<td>
										<select name="order_state" id="order_state" style="width:100%">
										<?php
										foreach ($this->OrderState as $OrderState) {
											$is_selected = "";
											if ($OrderState->id == $this->Order[0]->order_state) {
												$is_selected = "selected=\"selected\"";
											}
											printf("<option value=\"%s\" %s >%s</option>", $OrderState->id, $is_selected, $OrderState->name);
										}
										?>
										</select>
									</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
							</tr>
							<tr>
								<td colspan="4" style="background-color:pink; color: white">Buyer info</td>
							</tr>
							<tr>
								<td>First Name</td>
								<td>
									<input type="text" name="firstname" value="<?php echo $this->Order[0]->firstname ?>" id="firstname" style="width:100%">
								</td>
								<td>Last Name</td>
								<td><input type="text" name="lastname" value="<?php echo $this->Order[0]->lastname ?>" id="lastname" style="width:100%"></td>
							</tr>
							<tr>
								<td>E-Mail</td>
								<td><input type="text" name="email" value="<?php echo $this->Order[0]->email ?>" id="email" style="width:100%"></td>
								<td>Phone</td>
								<td><input type="text" name="phone" value="<?php echo $this->Order[0]->phone_number ?>" id="phone" style="width:100%"></td>
							</tr>
							<tr>
								<td colspan="4" style="background-color:878787; color: white">Billing Info</td>
							</tr>
							<tr>
								<td>First Name</td>
								<td><input type="text" name="bill_firstname" value="<?php echo $this->Order[0]->bill_firstname ?>" id="bill_firstname" style="width:100%"></td>
								<td>Last Name</td>
								<td><input type="text" name="bill_lastname" value="<?php echo $this->Order[0]->bill_lastname ?>" id="bill_lastname" style="width:100%"></td>
							</tr>
							<tr>
								<td>Billing Zipcode</td>
								<td><input type="text" name="bill_zipcode" value="<?php echo $this->Order[0]->bill_zipcode ?>" id="bill_zipcode" style="width:100%"></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>Billing City</td>
								<td><input type="text" name="bill_city" value="<?php echo $this->Order[0]->bill_city ?>" id="bill_city" style="width:100%"></td>
								<td>Billing State</td>
								<td>
									<select style="width:100%">
									<?php foreach ($this->States as $State) {
										$is_selected = "";
										if ($State->id == $this->Order[0]->bill_state) {
											$is_selected = "selected=\"selected\"";
										}
										printf("<option value=\"%s\" %s >%s</option>", $State->id, $is_selected, $State->state);
									} ?>
									</select>								
								</td>
							</tr>
							<tr>
								<td>Billing Address</td>
								<td colspan="3"><input type="text" name="bill_address" value="<?php echo $this->Order[0]->bill_address ?>" id="bill_address" style="width:100%"></td>
							</tr>
							<tr>
								<td colspan="4" style="background-color:blue; color: white">Shipping Info</td>
							</tr>
							<tr>
								<td>First Name</td>
								<td><input type="text" name="ship_firstname" value="<?php echo $this->Order[0]->ship_firstname ?>" id="ship_firstname" style="width:100%"></td>
								<td>Last Name</td>
								<td><input type="text" name="ship_lastname" value="<?php echo $this->Order[0]->ship_lastname ?>" id="ship_lastname" style="width:100%"></td>
							</tr>
							<tr>
								<td>Shipping Zipcode</td>
								<td><input type="text" name="ship_zipcode" value="<?php echo $this->Order[0]->ship_zipcode ?>" id="ship_zipcode" style="width:100%"></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>Shipping City</td>
								<td><input type="text" name="ship_city" value="<?php echo $this->Order[0]->ship_city ?>" id="ship_city" style="width:100%"></td>
								<td>Shipping State</td>
								<td>
									<select style="width:100%">
									<?php foreach ($this->States as $State) {
										$is_selected = "";
										if ($State->sharthand == $this->Order[0]->ship_state) {
											$is_selected = "selected=\"selected\"";
										}
										printf("<option value=\"%s\" %s >%s</option>", $State->id, $is_selected, $State->state);
									} ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Shipping Address</td>
								<td colspan="3"><input type="text" name="ship_address" value="<?php echo $this->Order[0]->ship_address ?>" id="ship_address" style="width:100%"></td>
							</tr>
						</table>
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td style="background-color:blue; color: white" colspan="5">Order Details</td>
							</tr>
							<tr>
								<td>Product</td>
								<td>&nbsp;</td>
								<td>Price</td>
								<td>Qty</td>
								<td>SubTotal</td>
							</tr>
							<?php 
								$summy = 0;
								$i = 0;
								foreach($this->OrderLists as $OrderList) { 
								$query = $this->db->query("SELECT * FROM product WHERE id = " . $OrderList->pid);
								$product = $query->result();
							?>
							<tr>
								<td>
									<?php echo $product[0]->name ?>
									<input type="hidden" name="hid_product_name_<?php echo $OrderList->id ?>" value="<?php echo $product[0]->name ?>" id="hid_product_name_<?php echo $OrderList->id ?>">
								</td>
								<td>&nbsp;</td>
								<td>
									<?php echo number_format($OrderList->price, 2) ?>
									<input type="text" name="hid_price_<?php echo $OrderList->id ?>" value="<?php echo number_format($OrderList->price, 2) ?>" id="hid_price_<?php echo $OrderList->id ?>" onfocus="this.select()">
								</td>
								<td>
									<?php echo $OrderList->qty ?>
									<input type="text" name="hid_qty_<?php echo $OrderList->id ?>" value="<?php echo $OrderList->qty ?>" id="hid_qty_<?php echo $OrderList->id ?>" onfocus="this.select()" ndx="<?php echo $OrderList->id ?>">
								</td> 
								<td>
									<?php echo number_format($OrderList->price * $OrderList->qty, 2) ?>
								</td>
							</tr>
							<?php
								}
							?>
							<tr>
								<td colspan="4">Product Tax</td>
								<td><input type="text" name="product_tax" value="<?php echo number_format($this->Order[0]->product_tax, 2) ?>" id="product_tax"></td>
							</tr>
							<tr>
								<td colspan="4">Shipping Option</td>
								<td>
									<select name="shipping_method" id="shipping_method" size="1">
										<option value="-1">Choose Shipping Option</option>
										<?php
											foreach ($this->ListShippingMethod as $method) {
												$is_selected = '';
												if ($this->session->userdata('ShippingOptions')) {
													$sp = $this->session->userdata('ShippingOptions');
													if ($method->id == $sp[0]['id']) {
														$is_selected = "selected=\"selected\"";
													}
												}
												printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>", 
													$method->id, $is_selected, $method->name, $method->price
												);		
											}
										?>
										<option value="-2" <?php echo ($this->input->post('shipping_method') == -2) ? "selected=\"selected\"" : "" ?>>(ORIGIN)&nbsp;&nbsp;<?php echo $this->Order[0]->shipping_name ?>&nbsp;&nbsp;&nbsp;<?php echo $this->Order[0]->shipping_price ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4">Destination State</td>
								<td>
									<select name="state" id="state" size="1">
										<option value="-1">Choose Shipping Option</option>
										<?php
											foreach ($this->ListDestinationState as $state) {
												$is_selected = '';
												if ($this->session->userdata('DestinationState')) {
													$ds = $this->session->userdata('DestinationState');
													if ($state->id == $ds[0]['id']) {
														$is_selected = "selected=\"selected\"";
													}
												}
												printf("<option value=\"%s\" %s>%s</option>", $state->id, $is_selected, $state->tax_code);
											}
										?>
										<option value="-2" <?php echo ($this->input->post('state') == -2) ? "selected=\"selected\"" : "" ?>>(ORIGIN)&nbsp;&nbsp;<?php echo $this->Order[0]->destination_state ?>&nbsp;&nbsp;&nbsp;<?php echo $this->Order[0]->tax_rate ?></option>
									</select>
									<?php echo $this->Order[0]->destination_state ?>
								</td>
							</tr>
							<tr>
								<td colspan="4">Discount Code</td>
								<td>
									<select name="promo" id="promo" size="1">
										<option value="-1">Choose Promo Discount</option>
										<?php
										foreach ($this->DiscountCode as $discount) {
											printf("<option value=\"%s\">%s</option>", $discount->id, $discount->code);
										}
										?>
									</select>
									<?php echo (empty($this->Order[0]->discount_code) ? "&nbsp;" : $this->Order[0]->discount_code) ?>
								</td>
							</tr>
							<tr>
								<td colspan="4">Promo Discount</td>
								<td style="text-align:right; color: green">-$<?php echo number_format($this->Order[0]->discount, 2) ?></td>
							</tr>
							<tr>
								<td colspan="4">Calculate Shipping</td>
								<td style="text-align:right">$<?php echo number_format($this->Order[0]->calculate_shipping, 2) ?></td>
							</tr>
							<tr>
								<td colspan="4">Amount</td>
								<td style="text-align:right">$<?php echo number_format($this->Order[0]->amount, 2) ?></td>
							</tr>
							<tr>
								<td colspan="5" style="text-align:right">
									<input type="button" name="btnCal" value="Cal" id="btnCal">
								</td>
							</tr>
						</table>
					</div>
					
					<input type="hidden" name="method" value="update" id="method">
					<input type="hidden" name="id" value="<?php echo $this->Order[0]->id ?>" id="id">
					
					<input type="button" name="" value="Click ME" id="btnClick">
										
				</form>

			<div class="clearfix"></div>
		</div>
		
		
		<div id="dialog" title="Basic dialog">
			<p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
		</div>
		
		<div id="loading">
			Loading...111111
		</div>
		
		<a href="ajaxTBcontent.html?height=200&width=300" class="thickbox" title="Update">Update ThickBox content</a>
		
		<div class="clearfix"></div>
		
		<?php $this->load->view('admin/footer') ?>
		
	</div>
</body>
</html>