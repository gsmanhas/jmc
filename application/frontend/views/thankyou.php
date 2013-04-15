<?php

if (!$this->session->userdata('INVOICE_NUMBER')) {

	redirect('/');

}



if (!isset($this->Order[0])) {

	redirect('/');

}



$FreeShipping = "";

if ($this->Order[0]->promo_free_shipping == 1 || $this->Order[0]->freeshipping == 1) {

	$FreeShipping = "(Plus Free Shipping)";

}



function convert_amount($amount){

	$amount =  str_replace(",", "", $amount);

	return number_format($amount, 2);

}



?>



<!DOCTYPE HTML>

<html>

<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />

	<meta name="Author" content="Six Spoke Media" />

	<meta name="viewport" content="width=1024" />

	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />

	<title>Thank You - Josie Maran Cosmetics</title>

	<meta name="Keywords" content="thank you" />

	<meta name="Description" content="thank you" />

	<?php $this->load->view('base/head') ?>

	<!-- <script src="/js/shop.js" type="text/javascript" charset="utf-8"></script> -->

	<?php # $this->load->view('base/ga') ?>

	<style type="text/css">

        body { margin-top: 0; margin-right: 10px; margin-bottom: 20px; margin-left: 10px; color: #493838; }

        body, table { font: 12px/18px Helvetica, Arial, sans-serif; }

        #main table { min-width:680px; margin-bottom: 20px; }

        table span { color: #888; font-weight: bold; }

    </style>

<?php

if (isset($this->Order) && count($this->Order) >= 1) {

?>



<script type="text/javascript"> 

   var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); 

   document.write("<script src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'>" + "</sc" + "ript>"); 

</script>



<script type='text/javascript'>

var pageTracker = _gat._getTracker("UA-11098190-20");

pageTracker._initData();

pageTracker._trackPageview();



var timeObj     = new Date;

var unixTimeMs  = timeObj.getTime();

var unixTime    = parseInt(unixTimeMs / 1000);

var orderID     = '<?php echo $this->Order[0]->order_no ?>';



pageTracker._addTrans(

	'<?php echo $this->Order[0]->order_no ?>',

	'Josie Maran Cosmetics',

	'<?php echo $this->Order[0]->amount ?>',

	'<?php echo $this->Order[0]->product_tax ?>',			

	'<?php echo $this->Order[0]->shipping_price ?>',

	'<?php echo $this->Order[0]->ship_city ?>',

	'<?php echo $this->Order[0]->ship_state ?>',

	'USA'

  );



<?php foreach ($this->OrderList as $item): ?>

pageTracker._addItem(

    '<?php echo $this->Order[0]->order_no ?>',      // Order ID

    '<?php echo $item->sku ?>',						// SKU

    '<?php echo $item->name ?>',   					// Product Name

    '',                            					// Category

    '<?php echo number_format($item->price, 2) ?>',	// Price

    '<?php echo $item->qty ?>'                     	// Quantity

);

<?php endforeach ?>

pageTracker._trackTrans();

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

			View Cart &raquo; Checkout &raquo; Confirm Order  &raquo; <b>Thank You</b> 

		</div>

		

		<div id="pagetitle"><h1><!--Thank You--></h1></div>

		

		<div id="messagewrapper">

		

			<p>Thank you for shopping at Josie Maran Cosmetics. Please find confirmation of your order and payment below. You will also receive an email confirmation of your order.</p>

			



			<table width="98%" cellspacing="0" cellpadding="3" border="0">

			    <tbody>

			        <tr>

			            <td colspan="5">&nbsp;</td>

			        </tr>

			        <tr valign="top">

			            <td colspan="2">

			                <span>Bill To</span><br />

			                <?php echo $this->Order[0]->email ?><br />

			                <?php echo $this->Order[0]->firstname ?>&nbsp;<?php echo $this->Order[0]->lastname ?><br />

			                <?php echo $this->Order[0]->bill_address ?><br />

							<?php

								$bill_state = '';

								$query = $this->db->query("SELECT * FROM tax_codes WHERE id = ?", $this->Order[0]->bill_state)->result();

								if (count($query) >= 1) {

									$bill_state = $query[0]->tax_code;

								}

							?>

			                <?php echo $this->Order[0]->bill_city ?>,&nbsp;<?php echo $bill_state; ?>&nbsp;<?php echo $this->Order[0]->bill_zipcode ?><br /><br />



			                <span>Ship To</span><br />

							<?php if ($this->Order[0]->shipping_same == 1) { ?>

								<?php echo $this->Order[0]->firstname ?>&nbsp;<?php echo $this->Order[0]->lastname ?><br />

								<?php echo $this->Order[0]->bill_address ?><br />

				                <?php echo $this->Order[0]->bill_city ?>,&nbsp;<?php echo $bill_state ?>&nbsp;<?php echo $this->Order[0]->bill_zipcode ?><br /><br />

							<?php } else { ?>

								<?php echo $this->Order[0]->ship_first_name ?>&nbsp;<?php echo $this->Order[0]->ship_last_name ?><br />

								<?php echo $this->Order[0]->ship_address ?><br />

				                <?php echo $this->Order[0]->ship_city ?>,&nbsp; <?php echo $this->Order[0]->destination_state ?>&nbsp;<?php echo $this->Order[0]->ship_zipcode ?><br /><br />

							<?php } ?>

							

							<?php						

							$query = $this->db->get_where('webpages', array('id' => 30, 'publish' => '1')); 

							if($query->num_rows() > 0){ 

								$webpages = $query->row();

								echo $webpages->page_content;

							}

							?>

							

			            </td>

			            <td colspan="3">

			                <span>Order Number</span>&nbsp;<br /><?php echo $this->Order[0]->order_no ?><br />

			                <span>Order Date</span>&nbsp;<br /><?php echo $this->Order[0]->order_date ?><br />

							<?php

							

								switch($this->Order[0]->payment_method) {

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

									case 5:

										$PAYMENT_METHOD = "eGift Card + Credit Card";

										break;		

								}

							?>

			                <span>Payment Type</span>&nbsp;<br /><?php echo $PAYMENT_METHOD ?><br />	                

			            </td>

			        </tr>

			        <tr>

			            <td colspan="5">&nbsp;</td>

			        </tr>

			        <tr valign="top" style="background-color:#f3e4e9;">

			            <td align="left" 	style="width:15%;color:#493838;">	SKU			</td>

			            <td align="left" 	style="width:50%;color:#493838;">	Product		</td>

			            <td align="left" 	style="width:12%;color:#493838;">	Unit	  	</td>

			            <td align="right" 	style="width:11%;color:#493838;">	Unit Price	</td>

			            <td align="right" 	style="width:12%;color:#493838;">	Total	 	</td>

			        </tr>

			        <tr>

			            <td colspan="5">&nbsp;</td>

			        </tr>

					<?php $subtotal = 0 ?>

					<?php foreach ($this->OrderList as $items) {?>

					<tr valign="top" height="38px;">

                        <?php if($items->item_type == 'product' || $items->item_type == 'buy_one_get_one' || $items->item_type == 'free_gift'):?>

						<?php

						$query = $this->db->query("SELECT DISTINCT name, sku, on_sale, retail_price FROM product WHERE sku = ?", $items->sku);

						$sku = $query->result();

						if (count($sku) >= 1) {

							// echo $sku[0]->sku;

						}

						?>

						<td align="left"><?php echo $items->sku; ?></td>

				        <td align="left">

							<?php if($items->item_type == 'buy_one_get_one') { echo 'Free '; } if($items->item_type == 'free_gift') { echo 'Gift '; }?>

							<?php echo $items->name ?>

						</td>

						<td align="center"><?php echo $items->qty ?></td>

				        <td align="right">

							<?php echo ($sku[0]->on_sale == 1) ? ("<span style='text-decoration:line-through;color:red'>&#36;" . $sku[0]->retail_price . "</span>") : "" ?>

							<?php echo "&#36;".$items->price ?>

						</td>

				        <td align="right">&#36;<?php echo ($items->price * $items->qty) ?></td>



                        <?php else:?>

                        <td align="left"><?php echo $items->sku ?></td>

                        <td align="left"><?php echo $items->name ?></td>

                        <td align="center"><?php echo $items->qty ?></td>

                        <td align="right">

                            <?php echo "&#36;".$items->price ?>

                        </td>

                        <td align="right">&#36;<?php echo ($items->price * $items->qty) ?></td>

                        <?php endif;?>

                        <?php

                        $subtotal += $items->price * $items->qty;

                        ?>

				    </tr>

					<?php } ?>

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

						<td colspan="3" valign="top" align="left">$<?php echo convert_amount($subtotal) ?></td>

					</tr>

				

				<?php

					if ($this->Order[0]->discount_id != 0) {

					if($Order[0]->discount_code != 'freeshippingfortest1234') {

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

						<td colspan="3" valign="top" align="left">

							<span style="font-weight:normal;color:green;">

                                <?php if($this->Discount->discount_type == 4 || $this->Discount->discount_type == 5): ?>

                                +1 Free product

                                <?php else :?>

								-$<?php echo $this->cart->format_number($this->Order[0]->discount); ?>&nbsp;<?php //echo $FreeShipping ?>

                                <?php endif;?>

							</span>

                            &#34;<?php echo $this->Order[0]->discount_code ?>&#34; 

						</td>

	                </tr>

					<?php

					} }

					?>	

					

				<?php if($this->Order[0]->product_tax) { ?>

                <tr>

					<td colspan="2" align="right">

						<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Total Tax</td>

								</tr>								

							</table>

					</td>

					<td colspan="3" valign="top" align="left">$<?php echo convert_amount($this->Order[0]->product_tax) ?></td>

                </tr>

				<?php } ?>

				<?php if($this->Order[0]->charge_zip_amount) { ?>

				<tr >

					<td colspan="2" align="right">

						<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Total Tax</td>

								</tr>								

							</table>

					</td>

					<td colspan="3" valign="top" align="left">$<?php echo convert_amount($this->Order[0]->charge_zip_amount) ?></td>

                </tr>

				<?php } ?>

					

                

					

                    <tr>

                        <td colspan="2" align="right">

							<table align="left" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0px;" >

								<tr>

									<td align="left" valign="top" width="90%" >&nbsp;</td>

									<td align="left" valign="top" width="10%">Shipping</td>

								</tr>								

							</table>							

						</td>

                        <?php if ($this->Order[0]->shipping_delivery == 'Email Delivery'):?>

                        <td colspan="3" valign="top" align="left"><?php echo $this->Order[0]->shipping_delivery?></td>

                        <?php else:?>

                        <td colspan="3" valign="top" align="left">

						  <?php

						  	if ($this->Order[0]->freeshipping != '' && $this->Order[0]->freeshipping != '0') { 

								$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$this->Order[0]->freeshipping."' ");

								$shipping_method = $Query->row();

						  ?>

						  	<span style="font-weight:normal;color:green;"><?php /*?>-&#36;<?php */?><?php //echo $shipping_method->price; ?> Free Shipping</span>

						  <?php }else { ?>

							<?php //echo $this->Order[0]->shipping_name . " " . $this->Order[0]->shipping_delivery . " " . $this->Order[0]->shipping_price ?>

								<?php echo '$'.$this->Order[0]->shipping_price; ?>

							<?php } ?>

						</td>

                        <?php endif;?>

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

						<td colspan="3" valign="top" align="left"><strong>$<?php echo convert_amount($this->Order[0]->amount); ?></strong></td>

	                </tr>

	                <tr>

	                    <td colspan="5">&nbsp;</td>

	                </tr>

					<tr>

	                    <td colspan="5" align="center">

							<a href="/print-invoice/<?php echo $this->Order[0]->id ?>" style="text-decoration:none" target="_blank" ><input type="button" name="btnSubmit" value="Print Invoice" class="inputbutton" style="font-size:1.667em;padding:5px 10px;height:40px;"></a>

						</td>

	                </tr>

					<tr>

	                    <td colspan="5">&nbsp;</td>

	                </tr>

					

	                <tr>

	                    <td colspan="5">Thank you for shopping at Josie Maran!<br /><br /></td>

	                </tr>

	                <tr valign="top">

	                    <td colspan="5">

		                    <span>Return Policy</span><br />

		                    If for any reason you are not satisfied with your Josie Maran Cosmetics purchase, simply return the unused portion, and we will be happy to remit your account for the full amount of your purchase. If you prefer, you may exchange your purchase for other Josie Maran products. We will accept returns and exchanges within 30 days of your invoice date. All Good Buys items are FINAL SALE and are non-refundable and/or exchangeable. For more information please visit <a href="<?php echo base_url() . "shipping-returns#returns" ?>">our&nbsp;site</a>.

	                    </td>

	                </tr>

			    </tbody>

			</table>

		</div>

	</div>

	<?php $this->load->view('base/footer_thankyou') ?>



</body>

</html>

<?php



	// $this->session->unset_userdata('INVOICE_NUMBER');

}

