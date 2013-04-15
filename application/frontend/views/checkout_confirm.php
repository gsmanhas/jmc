<!DOCTYPE HTML>

<html>

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<meta name="Author" content="Six Spoke Media" />

	<meta name="viewport" content="width=1024" />

	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />

	<title>Confirm order - Josie Maran Cosmetics</title>

	<meta name="Keywords" content="thank you" />

	<meta name="Description" content="thank you" />

	<?php $this->load->view('base/head') ?>

	<style type="text/css">

        body { margin-top: 0; margin-right: 10px; margin-bottom: 20px; margin-left: 10px; color: #493838; }

        body, table { font: 12px/18px Helvetica, Arial, sans-serif; }

        #main table { min-width:680px; margin-bottom: 20px; }

        table span { color: #888; font-weight: bold; }

    </style>
	<script>
		function confirm_edit(){
			document.getElementById("go_func").value = 'confirm_edit';
			document.getElementById('frm_checkout').submit();
		}
	</script>
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

		

		<div id="topnav" style="font-size:15px;">

			View Cart &raquo; Checkout &raquo; <b>Confirm Order</b>  &raquo; Thank You

		</div>

		

		<div id="pagetitle"><h1><!--Order confirmation--></h1></div>

		

		<div id="messagewrapper">



			<table width="98%" cellspacing="0" cellpadding="3" border="0">

			    <tbody>

			        <tr>

			            <td colspan="5">&nbsp;</td>

			        </tr>

			        <tr valign="top">

			            <td colspan="2">

			                <span>Bill To</span><br />

							

							<?php

								$bill_state = '';

								$query = $this->db->query("SELECT * FROM tax_codes WHERE id = ?", $this->input->post('bill_state'))->result();

								if (count($query) >= 1) {

									$bill_state = $query[0]->tax_code;

								}

							?>

							

			                <?php echo $this->input->post('email'); ?><br />

			                <?php echo $this->input->post('firstname'); ?>&nbsp;<?php echo $this->input->post('lastname'); ?><br />

			                <?php echo $this->input->post('bill_address') ?><br />							

			                <?php echo $this->input->post('bill_city') ?>,&nbsp;<?php echo $bill_state; ?>&nbsp;<?php echo $this->input->post('bill_zipcode') ?><br /><br />



			                <span>Ship To</span><br />

							<?php if ($this->input->post('shipSame') == 1) { ?>

							

			                <?php echo $this->input->post('firstname'); ?>&nbsp;<?php echo $this->input->post('lastname'); ?><br />

							<?php echo $this->input->post('bill_address') ?><br />										                

			                <?php echo $this->input->post('bill_city') ?>,&nbsp;<?php echo $bill_state; ?>&nbsp;<?php echo $this->input->post('bill_zipcode') ?><br /><br />

							

							<?php } else { ?>

							

							<?php

								$ship_state = $this->input->post('ship_state');								

								/*$query = $this->db->query("SELECT * FROM tax_codes WHERE id = ?", $this->input->post('ship_state'))->result();

								if (count($query) >= 1) {

									$ship_state = $query[0]->tax_code;

								}*/

							?>

							

			                <?php echo $this->input->post('ship_first_name'); ?>&nbsp;<?php echo $this->input->post('ship_last_name'); ?><br />

			                <?php echo $this->input->post('ship_address').' '.$this->input->post('ship_address2'); ?><br />							

			                <?php echo $this->input->post('ship_city') ?>,&nbsp;<?php echo $ship_state; ?>&nbsp;<?php echo $this->input->post('ship_zipcode') ?><br /><br />

							

							<?php } ?>							

							

			            </td>

			            <td colspan="3">			                

			                <span>Order Date</span>&nbsp;<br /><?php echo date('Y-m-d H:i:s'); ?><br />

							

							<?php

							    

								switch($this->input->post('payment_method')) {

									case 1:

										$PAYMENT_METHOD = "Credit Card";

										break;

									case 2:

										$PAYMENT_METHOD = "Paypal";

										break;

									case 3:

										$PAYMENT_METHOD = "Gift Voucher";

										break;

									case 4:

										$PAYMENT_METHOD = "Test";

										break;	

								}

								

								if ($this->session->userdata('VoucherCode') && $this->session->userdata('Voucher_Sub_Total')) { 

									$PAYMENT_METHOD = "Gift Voucher";

									

									if($this->session->userdata('Amount') > 0){

										$PAYMENT_METHOD = "eGift Card + Credit Card";

									}

									

								}

								

								

							?>

							

			                <span>Payment Type</span>&nbsp;<br /><?php echo $PAYMENT_METHOD; ?><br />	                

			            </td>

			        </tr>

			        <tr>

			            <td colspan="5">&nbsp;</td>

			        </tr>

			        <tr valign="top" style="background-color:#f3e4e9;">

			            <td align="left"  colspan="2"	style="width:50%;color:#493838;">	Product		</td>

			            <td align="left" 	style="width:12%;color:#493838;">	Unit	  	</td>

			            <td align="right" 	style="width:11%;color:#493838;">	Unit Price	</td>

			            <td align="right" 	style="width:12%;color:#493838;">	Total	 	</td>

			        </tr>

			        <tr>

			            <td colspan="5">&nbsp;</td>

			        </tr>

					

					<?php foreach ($this->cart->contents() as $items): ?>

						<tr valign="top" height="38px;">

							

							

							<td style="text-align:left" colspan="2">



									<?php echo $items['name'];?>

							

									<?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>

							

									<p style="margin:0;">

							

										<?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

							

										<?php if ($option_name == "Message") continue; ?>

							

										<strong class="pre-order-text"><?php echo $option_name; ?></strong> <?php echo $option_value; ?>

							

										<?php endforeach; ?>

							

									</p>

							

									<?php endif; ?>



						    </td>

							<td style="text-align:left"><?php echo $items['qty'];?></td>

							<td style="text-align:center">

							        $<?php echo $items['price'] != 0 ? $this->cart->format_number($items['price']) : number_format(0, 2, '.', ','); ?>

	

    						</td>

							<td style="text-align:right">$<?php echo $items['subtotal'] != 0 ? $this->cart->format_number($items['subtotal']) : number_format(0, 2, '.', ','); ?></td>

						</tr>

					<?php endforeach; ?>

					<tr>

						<td colspan="2" align="right">&nbsp;</td>

						<td colspan="3" align="left"><div style="width: 100%; height: 1px; border-bottom: 1px solid rgb(243, 228, 233); margin-bottom: 15px; margin-top: 15px;">&nbsp;</div></td>

					</tr>

					<tr>

						<td colspan="2" align="left">

							<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Subtotal</td>

								</tr>								

							</table>

						</td>

						<td colspan="3" valign="top" align="left">$<?php echo $this->cart->format_number($this->cart->total()); ?></td>

					</tr>

					

					<?php

					$this->Promo_FreeShipping = $this->session->userdata('FreeShipping');	

					if ($this->session->userdata('DiscountCode') && isset($this->ShoppingCart->discount_sub_total)) { 

					

					$DiscountCode = $this->session->userdata('DiscountCode');

					

					$FreeShipping = "";

					if ($this->Promo_FreeShipping == 1) {

						$FreeShipping = "(Plus Free Shipping)";

					}



					

					if($DiscountCode[0]->discount_code != 'freeshippingfortest1234') {

					

					?>				

	                <tr>

						<td colspan="2" align="right">

							<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Promo</td>

								</tr>								

							</table>							

						</td>

						<td colspan="3" valign="top"  align="left">

							<span style="font-weight:normal;color:green;">

                                <?php if($DiscountCode[0]->discount_type == 4 || $DiscountCode[0]->discount_type == 5): ?>

                                +1 Free product

                                <?php else :?>

								-$<?php echo $this->cart->format_number($this->ShoppingCart->discount_sub_total); ?>&nbsp;<?php //echo $FreeShipping ?>

                                <?php endif;?>

							</span>

                            &nbsp;&#34;<?php echo $DiscountCode[0]->code; ?>&#34; 

						</td>

	                </tr>

					<?php

					 }}

					?>

				

                <tr >

					<td colspan="2" align="right">

						<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Total Tax</td>

								</tr>								

							</table>

					</td>

					<td colspan="3" valign="top" align="left">$<?=number_format($this->session->userdata('ProductTax'), 2);?></td>

                </tr>

				

				 

					

					

					

					<?php 

					 $IS_VOUCHER_ONLY = true;

			            foreach ($this->cart->contents() as $item) {

									if (!array_key_exists('type', $item)) {

										$IS_VOUCHER_ONLY = false;

										break;

									}

					 }



					 

					 $FREE_SHIPPING = ($this->session->userdata('FreeShipping') ? $this->session->userdata('FreeShipping') : 0);

					 $IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);

					 $sp = $this->session->userdata('ShippingOptions');

					?>

					

					

					

					<tr>

                        <td colspan="2" align="right">

							<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Shipping</td>

								</tr>								

							</table>							

						</td>

                        <?php  

							if ($IS_VOUCHER_ONLY) { 

						?>

                        <td colspan="3" valign="top" align="left"><?php echo 'Email Delivery'; ?></td>

                        <?php } else { ?>

						

						

                        <td colspan="3" valign="top" align="left">

								<?php //echo $sp[0]['shipping_name'] . " " . $sp[0]['delivery'] . " " . $sp[0]['price']; ?>

								<?php 

									if ($IS_FREE_SHIPPING2 == TRUE) {

										if ($this->session->userdata('FreeShipping2')!= '' and $this->session->userdata('FreeShipping2')!= '0') {

											$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$this->session->userdata('FreeShipping2')."' ");

											$shipping_method = $Query->row();

								?>

											<span style="font-weight:normal;color:green;"><?php /*?>-&#36;<?php */?><?php //echo $shipping_method->price; ?> Free Shipping</span>

								<?php	

										}

									}else {

										echo '$'.$sp[0]['price'];

									}

								 ?>

						</td>

						

						

                        <?php } ?>

                    </tr>

					

					<?php if ($this->session->userdata('VoucherCode') && $this->session->userdata('Voucher_Sub_Total')) { ?>

                    <?php $Voucher = $this->session->userdata('VoucherCode');?>

                    <tr >

                        <td colspan="2" align="right">

							<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">eGift Card</td>

								</tr>								

							</table>							

						</td>

                        <td colspan="3" valign="top" align="left">

                            <span style="font-weight:normal;color:green;">

                                -$<?php echo $this->cart->format_number($this->session->userdata('Voucher_Sub_Total')); ?>&nbsp;

								<?php

									$Query = $this->db->query("SELECT * FROM order_voucher_details WHERE code = '". $this->session->userdata('cart_voucher') ."' AND is_delete = 0");

									$VoucherCode = $Query->result();

									if (count($VoucherCode) >= 1 && $VoucherCode[0]->balance > 0) {

										

										$voucher_balance = 0;

										$sum = $VoucherCode[0]->price;

										if($sum >= $VoucherCode[0]->balance) {

                  							 $voucher_balance = 0;

                						}else {

											 $voucher_balance = $sum - $VoucherCode[0]->balance;

										} 

										

									}	

								?>

                                <?php if($voucher_balance > 0) : ?>

	                                (Remaining balance $<?php echo $voucher_balance; ?>)

                                <?php endif;?>                               

                            </span>

                            &#34;<?php echo $Voucher[0]->code ?>&#34;

                        </td>

                    </tr>

                    <?php }?>

										

	                <tr>

						<td colspan="2" align="right">

							<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%"><strong>Grand Total</strong></td>

								</tr>								

							</table>							

						</td>

						<td colspan="3" valign="top" align="left"><strong>$<?php echo $this->session->userdata('Amount'); ?></strong></td>

	                </tr>

					<tr>

	                    <td colspan="5">&nbsp;</td>

	                </tr>

					

					<tr valign="middle">

					 <td colspan="5" align="center">

					  <form action="<?php echo secure_base_url().$this->form_url_s; ?>" name="frm_checkout" id="frm_checkout" method="post" accept-charset="utf-8" >

					  	

							<input type="submit" name="btnSubmit" value="Confirm order" id="btnSubmit" class="inputbutton" style="font-size:1.667em;padding:5px 10px;height:40px;">
							&nbsp;&nbsp;&nbsp;
							<a href="javascript:void(0);" onClick="confirm_edit();" style="text-decoration:none" ><input type="button" name="btnSubmit" value="Edit" id="btnSubmit" class="inputbutton" style="font-size:1.667em;padding:5px 10px;height:40px;"></a>

							&nbsp;&nbsp;&nbsp;

							<a href="/viewcart" style="text-decoration:none" ><input type="button" name="btnSubmit" value="Cancel" class="inputbutton" style="font-size:1.667em;padding:5px 10px;height:40px;"></a>

							

							

							<input type="hidden" name="firstname" value="<?php echo $this->input->post('firstname'); ?>" >

							<input type="hidden" name="lastname" value="<?php echo $this->input->post('lastname'); ?>" >

							<input type="hidden" name="email" value="<?php echo $this->input->post('email'); ?>" >

							<input type="hidden" name="confirm_email" value="<?php echo $this->input->post('confirm_email'); ?>" >

							<input type="hidden" name="password" value="<?php echo $this->input->post('password'); ?>" >

							<input type="hidden" name="passconf" value="<?php echo $this->input->post('passconf'); ?>" >

							<input type="hidden" name="phone" value="<?php echo $this->input->post('phone'); ?>" >

							<input type="hidden" name="bill_address" value="<?php echo $this->input->post('bill_address'); ?>" >

							<input type="hidden" name="bill_city" value="<?php echo $this->input->post('bill_city'); ?>" >

							<input type="hidden" name="bill_zipcode" value="<?php echo $this->input->post('bill_zipcode'); ?>" >

							<input type="hidden" name="bill_country" value="<?php echo $this->input->post('bill_country'); ?>" >

							<input type="hidden" name="bill_state" value="<?php echo $this->input->post('bill_state'); ?>" >

							<input type="hidden" name="shipSame" value="<?php echo $this->input->post('shipSame'); ?>" >

							<input type="hidden" name="ship_first_name" value="<?php echo $this->input->post('ship_first_name'); ?>" >

							<input type="hidden" name="ship_last_name" value="<?php echo $this->input->post('ship_last_name'); ?>" >

							<input type="hidden" name="ship_address" value="<?php echo $this->input->post('ship_address'); ?>" >

							<input type="hidden" name="ship_address2" value="<?php echo $this->input->post('ship_address2'); ?>" >

							<input type="hidden" name="ship_city" value="<?php echo $this->input->post('ship_city'); ?>" >

							<input type="hidden" name="ship_zipcode" value="<?php echo $this->input->post('ship_zipcode'); ?>" >

							<input type="hidden" name="ship_country" value="<?php echo $this->input->post('ship_country'); ?>" >

							<input type="hidden" name="ship_state" value="<?php echo $this->input->post('ship_state'); ?>" >

							<input type="hidden" name="payment_method" value="<?php echo $this->input->post('payment_method'); ?>" >

							<input type="hidden" name="name_on_card" value="<?php echo $this->input->post('name_on_card'); ?>" >

							<input type="hidden" name="card_type" value="<?php echo $this->input->post('card_type'); ?>" >

							<input type="hidden" name="card_number" value="<?php echo $this->input->post('card_number'); ?>" >

							<input type="hidden" name="CardExpiryMonth" value="<?php echo $this->input->post('CardExpiryMonth'); ?>" >

							<input type="hidden" name="CardExpiryYear" value="<?php echo $this->input->post('CardExpiryYear'); ?>" >

							<input type="hidden" name="ccv" value="<?php echo $this->input->post('ccv'); ?>" >
							<input type="hidden" name="is_only_egift" value="<?php echo $this->input->post('is_only_egift'); ?>" >

							<input type="hidden" id="go_func" name="go_func" value="go_charge" >

						</form>

					 </td>

	                </tr>

					

	                <tr>

	                    <td colspan="5">&nbsp;</td>

	                </tr>	                

	                

			    </tbody>

			</table>

		</div>

	</div>

	<?php $this->load->view('base/footer_thankyou') ?>



</body>

</html>