<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>

<script type="text/javascript" charset="utf-8">
	var base_url = '<?php echo base_url() ?>';
</script>
<!-- // <script src="<?php echo base_url() ?>js/admin/editor_order.js" type="text/javascript" charset="utf-8"></script> -->
<script src="<?php echo base_url() ?>js/admin/blockUI.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url() ?>js/admin/addnew_order.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>css/order.css" type="text/css" media="screen" title="" charset="utf-8">
</head>
<body>
<div id="content">
	
</div>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper">
		<div id="main-wrapper">
			<div id="main-content">				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Create a New Order</b></h1>
					<div class="other">
						<div class="float-left">New Order</div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/orders" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>
						</div>						
						<div class="clearfix"></div>
					</div>
				</div>
				
				<div class="response-msg success ui-corner-all">
					<span>Success message</span>
				</div>
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>
								
				<form method="post" action="<?php echo base_url() ?>admin.php/orders" id="frmMain">
					<div class="hastable">
						<table border="0" cellspacing="5" cellpadding="5" style="margin-bottom:0px">
							<tr>
								<td colspan="6" style="background-color:#aaaaaa; color: white">Order Info</td>
							</tr>
							<tr>
								<td style="width:25%">Order No</td>
								<td style="width:25%">(The Order Number will be gerated when this order is saved.)</td>
								<td style="width:25%">Order Date</td>
								<td style="width:25%"><input type="text" name="order_date" value="" id="order_date" style="width:100%"></td>
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
									<td>Tracking No.</td>
									<td><input type="text" name="track_number" value="<?php echo set_value('track_number') ?>" id="track_number"></td>
								</tr>
							</tr>
							<tr>
								<td colspan="3" style="background-color:pink; color: white">Buyer info</td>
								<td><a href="#" id="btnAddUser">Add User</a></td>
							</tr>
							<tr>
								<td>First Name</td>
								<td>
									<input type="text" name="firstname" value="" id="firstname" style="width:100%">
								</td>
								<td>Last Name</td>
								<td><input type="text" name="lastname" value="" id="lastname" style="width:100%"></td>
							</tr>
							<tr>
								<td>E-Mail</td>
								<td><input type="text" name="email" value="" id="email" style="width:100%"></td>
								<td>Phone</td>
								<td><input type="text" name="phone" value="" id="phone" style="width:100%"></td>
							</tr>
							<tr>
								<td colspan="4" style="background-color:878787; color: white">Billing Info</td>
							</tr>
							<!-- <tr>
								<td>First Name</td>
								<td><input type="text" name="bill_firstname" value="" id="bill_firstname" style="width:100%"></td>
								<td>Last Name</td>
								<td><input type="text" name="bill_lastname" value="" id="bill_lastname" style="width:100%"></td>
							</tr> -->
							<tr>
								<td>Billing Zipcode</td>
								<td><input type="text" name="bill_zipcode" value="" id="bill_zipcode" style="width:100%"></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>Billing City</td>
								<td><input type="text" name="bill_city" value="" id="bill_city" style="width:100%"></td>
								<td>Billing State</td>
								<td>
									<select name="bill_state" id="bill_state" style="width:100%">
										<option value="0">Please Select a State</option>
										<?php foreach ($this->States as $state) {
											printf("<option value=\"%s\">%s</option>", $state->id, $state->sharthand);
										} ?>
									</select>								
								</td>
							</tr>
							<tr>
								<td>Billing Address</td>
								<td colspan="3"><input type="text" name="bill_address" value="" id="bill_address" style="width:100%"></td>
							</tr>
							<tr>
								<td colspan="4" style="background-color:blue; color: white">Shipping Info</td>
							</tr>
							<!-- <tr>
								<td>First Name</td>
								<td><input type="text" name="ship_firstname" value="" id="ship_firstname" style="width:100%"></td>
								<td>Last Name</td>
								<td><input type="text" name="ship_lastname" value="" id="ship_lastname" style="width:100%"></td>
							</tr> -->
							<tr>
								<td>Shipping Zipcode</td>
								<td><input type="text" name="ship_zipcode" value="" id="ship_zipcode" style="width:100%"></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>Shipping City</td>
								<td><input type="text" name="ship_city" value="" id="ship_city" style="width:100%"></td>
								<td>Shipping State</td>
								<td>
									<select id="ship_state" name="ship_name" style="width:100%">
										<option value="0">Please Select a State</option>
										<?php foreach ($this->States as $state) {
											printf("<option value=\"%s\">%s</option>", $state->id, $state->sharthand);
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Shipping Address</td>
								<td colspan="3">
									<input type="text" name="ship_address" value="" id="ship_address" style="width:100%">
								</td>
							</tr>
						</table>
						<table id="table_order_details" border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td style="background-color:blue; color: white" colspan="5">Order Details</td>
							</tr>
							<tr>
								<td style="width:40%">Product</td>
								<td style="width:10%; text-align:right"><a href="#" id="btn_addnew_product">AddNew</a></td>
								<td style="width:10%; text-align:right">Price</td>
								<td style="width:10%; text-align:right">Qty</td>
								<td style="width:30%; text-align:right">SubTotal</td>
							</tr>
							<tr>
								<td colspan="4">Product Tax</td>
								<td><input type="text" name="product_tax" value="" id="product_tax"></td>
							</tr>
							<tr>
								<td colspan="4">Total</td>
								<td style="text-align:right"><input type="text" name="total" value="" id="total"></td>
							</tr>
							<tr>
								<td colspan="4">Shipping Option</td>
								<td>
									<select name="shipping_method" id="shipping_method" size="1">
										<option value="-1">Choose Shipping Option</option>
										<?php foreach ($this->ShippingMethod as $SP): ?>
										<option value="<?php echo $SP->id ?>"><?php echo $SP->name ?>&nbsp;&nbsp;<?php echo $SP->price ?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4">Destination State</td>
								<td>
									<select name="state" id="state" size="1">
										<option value="0">Choose Shipping Option</option>
										<?php foreach ($this->ListDestinationState as $state): ?>
										<option value="<?php echo $state->id ?>"><?php echo $state->tax_code ?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4">Discount Code</td>
								<td>
									<select name="promo" id="promo" size="1">
										<option value="-1">Choose Promo Discount</option>
										<?php foreach ($this->DiscountCode as $discount): ?>
										<option value="<?php echo $discount->id ?>"><?php echo $discount->code ?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="4">Promo Discount</td>
								<td style="text-align:right; color: green">
									<div id="discount"></div>
									<div id="discount_freeshipping"></div>
									<input type="hidden" name="hid_discount" value="" id="hid_discount">
									<input type="hidden" name="hid_discount_can_freeshipping" value="" id="hid_discount_can_freeshipping">
								</td>
							</tr>
							<tr>
								<td colspan="4">Calculate Shipping</td>
								<td style="text-align:right">
									<input type="text" name="calculate_shipping" value="" id="calculate_shipping">
								</td>
							</tr>
							<tr>
								<td colspan="4">Amount</td>
								<td style="text-align:right"><input type="text" name="amount" value="" id="amount"></td>
							</tr>
						</table>
						

						
					</div>
					<input type="hidden" name="user_id" value="" id="user_id">
					<input type="hidden" name="method" value="addnew" id="method">
					<input type="hidden" name="id" value="-1" id="id">
				</form>
				

				<div id="search_customers">
					<div>
						Customer Name
					</div>
					<div>
						<input type="text" name="customer_name" value="" id="customer_name">
						<input type="button" name="Search" value="Search" id="btnSearchCustomers">
						<input type="button" name="Cancel" value="Cancel" id="btnCancelSearchCustomers">
					</div>
					<div class="search_customers_result"></div>
				</div>
				
				<div id="search_product">
					<div class="search_product_name">Product Name</div>
					<div class="search_product_field">
						<input type="text" name="product_name" value="" id="product_name">
					</div>
					<div class="search_product_toolbar">
						<input type="button" name="Search" value="Search" id="btnSearchProduct">
						<input type="button" name="Cancel" value="Cancel" id="btnCancelSearchProduct">
					</div>
					<div class="search_result"></div>
				</div>
				
			</div>
		</div>
	</div>
</body>
</html>
	