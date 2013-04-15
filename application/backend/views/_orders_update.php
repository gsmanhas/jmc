<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('admin/head'); ?>

<script type="text/javascript">

jQuery(document).ready(function(){
	
	jQuery("#btn_submit").click(function(){
		
		var ids = [];
		for (var i = 0; i < document.getElementsByName("sel_product").length; i++) {
			ids.push(document.getElementsByName("sel_product")[i].value);
		}
		jQuery("#products_id").val(ids);
		
		var Prices = [];
		for (var i = 0; i < document.getElementsByName("order_price").length; i++) {
			Prices.push(document.getElementsByName("order_price")[i].value);
		}
		jQuery("#products_price").val(Prices);
		
		var Qtys = [];
		for (var i = 0; i < document.getElementsByName("qty").length; i++) {
			Qtys.push(document.getElementsByName("qty")[i].value);
		}
		jQuery("#products_qty").val(Qtys);
		
		var SubTotal = [];
		for (var i = 0; i < document.getElementsByName("subTotal").length; i++) {
			SubTotal.push(document.getElementsByName("subTotal")[i].value);
		}
		jQuery("#products_subtotal").val(SubTotal);
		
		
		
		jQuery("#frmMain").submit();
	});
	
});

function changeProduct (self, ndx) {
	// console.log(self.selectedIndex);
	
	var Order_Price = document.getElementsByName("order_price");
	var Qty = document.getElementsByName("qty");
	var subTotal    = document.getElementsByName("subTotal");
	// formatter = new DecimalFormat("#.##");
	
	for (var i = 0; i < self.options.length; i++) {
		if (self.selectedIndex == i) {
			// Order_Price[ndx].value = self.options[i].value;
			Order_Price[ndx].value = jQuery(self.options[i]).attr('price');
			subTotal[ndx].value = Order_Price[ndx].value * Qty[ndx].value;
		}
	}
}

function changePrice (self, ndx) {
	console.log(self.value);
	var Order_Price = document.getElementsByName("order_price");
	var Qty = document.getElementsByName("qty");
	var subTotal    = document.getElementsByName("subTotal");
	if (self.value) {
		subTotal[ndx].value = (self.value * Qty[ndx].value);
	} else {
		alert('請輸入整數');
		return false;
	}
}

function changeQty (self, ndx) {
	var Order_Price = document.getElementsByName("order_price");
	var subTotal    = document.getElementsByName("subTotal");
	if (isInteger(self.value)) {
		subTotal[ndx].value = (Order_Price[ndx].value * self.value);
	} else {
		alert('請輸入整數');
		return false;
	}
}

function changeShippingOption (self) {
	for (var i = 0; i < self.options.length; i++) {
		if (self.selectedIndex == i) {
			jQuery("#shipping_method_price").val(jQuery(self.options[i]).attr('price'));
		}
	}
}

function changeDestinationState (self) {
	var subTotal    = document.getElementsByName("subTotal");
	
	var sub = 0;
	for (var i = 0; i < subTotal.length; i++) {
		sub += parseFloat(subTotal[i].value);
	}
	
	for (var i = 0; i < self.options.length; i++) {
		if (self.selectedIndex == i) {
			var sp = jQuery("#shipping_method_price").val();
			var sp_tax = (sp * (jQuery(self.options[i]).attr('price') / 100));
			var Calculate_Shipping = parseFloat(sp) + parseFloat(sp_tax);
			jQuery("#destination_price").val(Calculate_Shipping.toFixed(2));
			var tax = jQuery("#destination_price").val() / 100;
			var num = parseFloat(sub * tax);
			jQuery("#product_tax").val(num.toFixed(2));
			var amount = parseFloat(jQuery("#product_tax").val()) + 
						 parseFloat(sub) + 
						 parseFloat(jQuery("#destination_price").val()) - jQuery("#promo_discount").val();
			jQuery("#amount").val(amount.toFixed(2));
		}
	}
}

function changeDiscountCode (self) {
	for (var i = 0; i < self.options.length; i++) {
		if (self.selectedIndex == i) {
			jQuery("#promo_discount").val(jQuery(self.options[i]).attr('price'));
		}
	}
}

function getTotal () {
	
}

function isInteger(s) {
  return (s.toString().search(/^-?[0-9]+$/) == 0);
}


</script>

</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('admin/account');
			# 載入 Menu
			$this->load->view('admin/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
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

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin/orders">
					

					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td colspan="6">Order Info</td>
						</tr>
						<tr>
							<td>Order No</td>
							<td><?php echo $this->Order[0]->order_no ?></td>
							<td>Order Date</td>
							<td><?php echo $this->Order[0]->order_date ?></td>
							<td>Order Status</td>
							<td>
								<select name="order_state" id="order_state">
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
						</tr>
					</table>					
					
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td colspan="4">訂購人基本資料</td>
						</tr>
						<tr>
							<td>First Name</td>
							<td>
								<input type="text" name="firstname" value="<?php echo $this->Order[0]->firstname ?>" id="firstname">
							</td>
							<td>Last Name</td>
							<td><input type="text" name="lastname" value="<?php echo $this->Order[0]->lastname ?>" id="lastname"></td>
						</tr>
						<tr>
							<td>E-Mail</td>
							<td><input type="text" name="email" value="<?php echo $this->Order[0]->email ?>" id="email"></td>
							<td>Phone</td>
							<td><input type="text" name="phone" value="<?php echo $this->Order[0]->phone_number ?>" id="phone"></td>
						</tr>
					</table>
					
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td colspan="4">Billing Info</td>
						</tr>
						<tr>
							<td>First Name</td>
							<td><input type="text" name="bill_firstname" value="<?php echo $this->Order[0]->bill_firstname ?>" id="bill_firstname"></td>
							<td>Last Name</td>
							<td><input type="text" name="bill_lastname" value="<?php echo $this->Order[0]->bill_lastname ?>" id="bill_lastname"></td>
						</tr>
						<tr>
							<td>Billing Address</td>
							<td colspan="3"><input type="text" name="bill_address" value="<?php echo $this->Order[0]->bill_address ?>" id="bill_address"></td>
						</tr>
						<tr>
							<td>Billing City</td>
							<td><input type="text" name="bill_city" value="<?php echo $this->Order[0]->bill_city ?>" id="bill_city"></td>
							<td>Billing State</td>
							<td>
								<select>
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
							<td>Billing Zipcode</td>
							<td colspan="3"><input type="text" name="bill_zipcode" value="<?php echo $this->Order[0]->bill_zipcode ?>" id="bill_zipcode"></td>
						</tr>
					</table>
					
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td colspan="4">Shipping Info</td>
						</tr>
						<tr>
							<td>First Name</td>
							<td><input type="text" name="ship_firstname" value="<?php echo $this->Order[0]->ship_firstname ?>" id="ship_firstname"></td>
							<td>Last Name</td>
							<td><input type="text" name="ship_lastname" value="<?php echo $this->Order[0]->ship_lastname ?>" id="ship_lastname"></td>
						</tr>
						<tr>
							<td>Shipping Address</td>
							<td colspan="3"><input type="text" name="ship_address" value="<?php echo $this->Order[0]->ship_address ?>" id="ship_address"></td>
						</tr>
						<tr>
							<td>Shipping City</td>
							<td><input type="text" name="ship_city" value="<?php echo $this->Order[0]->ship_city ?>" id="ship_city"></td>
							<td>Shipping State</td>
							<td>
								<select>
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
							<td>Shipping Zipcode</td>
							<td colspan="3"><input type="text" name="ship_zipcode" value="<?php echo $this->Order[0]->ship_zipcode ?>" id="ship_zipcode"></td>
						</tr>
					</table>
					
					<table border="0" cellspacing="5" cellpadding="5">
						<tr>
							<td colspan="10">Order Detail</td>
						</tr>
						<tr>
							<td>Product
								<div id="name">
									<a href="#">新增</a>
								</div>
							</td>
							<td>Price</td>
							<td>Qty</td>
							<td>Total</td>
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
								<select name="sel_product" size="1" onchange="changeProduct(this, <?php echo $i ?>);">
									<?php foreach ($this->Products as $item) { ?>
										<option price="<?php echo number_format($item->price, 2) ?>" value="<?php echo $item->id ?>" <?php echo ($product[0]->id == $item->id) ? "selected=\"selected\"" : "" ?>><?php echo $item->name ?></option>
									<?php } ?>
								</select>
								<br /><br />
								<div><a href="#">退貨</a></div>
								<div><a href="#">移除</a></div>
							</td>
							<td>
								<input type="text" name="order_price" value="<?php echo number_format($OrderList->price, 2) ?>" onblur="changePrice(this, <?php echo $i ?>)">
							</td>
							<td><input type="text" name="qty" value="<?php echo $OrderList->qty ?>" id="" onblur="changeQty(this, <?php echo $i ?>);"></td>
							<td><input type="text" name="subTotal" value="<?php echo number_format(($OrderList->price * $OrderList->qty), 2) ?>"></td>
						</tr>
						<?php
								$i++;
							}
						?>
						<tr>
							<td colspan="3">Product Tax</td>
							<td><input type="text" name="product_tax" value="<?php echo $this->Order[0]->product_tax ?>" id="product_tax"></td>
						</tr>
						<tr>
							<td>
								Shipping Option
								<select name="sel_shipping_option" id="" size="1" onchange="changeShippingOption(this)">
									<?php
									foreach ($this->SPMethod as $spMethod) {
									?>
									<option price="<?php echo $spMethod->price ?>" value="<?php echo $spMethod->id ?>" <?php echo ($spMethod->id == $this->ShippingMethod[0]->id) ? "selected=\"selected\"" : "" ?>><?php echo $spMethod->name ?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td>&nbsp;<?php # echo $this->ShippingMethod[0]->name ?></td>
							<td>&nbsp;<?php # echo $this->ShippingMethod[0]->delivery ?></td>
							<td><input type="text" name="shipping_method_price" value="<?php echo $this->ShippingMethod[0]->price ?>" id="shipping_method_price"></td>
						</tr>
						<tr>
							<td>
								Destination State
								<select name="sel_destination_state" id="sel_destination_state" size="1" onchange="changeDestinationState(this)">
									<?php
									foreach ($this->TaxCodes as $tax) {
									?>
									<option price="<?php echo $tax->tax_rate ?>" value="<?php echo $tax->id ?>" <?php echo ($tax->id == $this->Order[0]->destination_id) ? "selected=\"selected\"" : "" ?>><?php echo $tax->tax_code ?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td>&nbsp;<?php #echo $this->Order[0]->destination_state ?></td>
							<td>Calculate Shipping</td>
							<td><input type="text" name="destination_price" value="<?php echo $this->Order[0]->calculate_shipping ?>" id="destination_price"></td>
						</tr>
						<tr>
							<td>Discount Code
								<select name="sel_discount_code" id="sel_discount_code" size="1" onchange="changeDiscountCode(this)">
									<?php
									foreach ($this->DiscountCode as $discount) {
									?>
									<option price="<?php echo $discount->discount_amount_threshold ?>" value="<?php echo $discount->id ?>" <?php echo ($this->Order_Rel_Discount[0]->code == $discount->code) ? "selected=\"selected\"" : "" ?>><?php echo $discount->code ?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td>&nbsp;<?php #echo $this->DiscountCode[0]->code ?></td>
							<td>Promo Discount</td>
							<td><input type="text" name="promo_discount" value="<?php echo number_format($this->Order[0]->promo, 2) ?>" id="promo_discount"></td>
						</tr>
						<tr>
							<td colspan="3">Amount</td>
							<td><input type="text" name="amount" value="<?php echo $this->Order[0]->amount ?>" id="amount"></td>
						</tr>
					</table>
					
					<input type="hidden" name="action" value="update_save" id="action">
					<input type="hidden" name="products_id" value="" id="products_id">
					<input type="hidden" name="products_price" value="" id="products_price">
					<input type="hidden" name="products_qty" value="" id="products_qty">
					<input type="hidden" name="products_subtotal" value="" id="products_subtotal">
					
					<input type="hidden" name="id" value="<?php echo $this->Order[0]->id ?>" id="id">
					
					</form>
				</div>
				
			</div>			
			<div class="clearfix"></div>
		</div>
		<!-- <div id="sidebar">
			<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
				<div class="portlet-header ui-widget-header"><span class="ui-icon ui-icon-circle-arrow-s"></span>Theme Switcher</div>
				<div class="portlet-content">
					<ul class="side-menu">
						<li>
							<a title="Default Theme" style="font-weight: bold;" href="javascript:void(0);" id="default" class="set_theme">Default Theme</a>
						</li>
						<li>
							<a title="Light Blue Theme" href="javascript:void(0);" id="light_blue" class="set_theme">Light Blue Theme</a>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="clearfix"></div>
		</div> -->
		<div class="clearfix"></div>
		<?php $this->load->view('admin/footer') ?>
	</div>
</body>
</html>