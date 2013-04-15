<?php

$ShippingOptions  = $this->session->userdata('ShippingOptions');

$DestinationState = $this->session->userdata('DestinationState');

$DiscountCode     = $this->session->userdata('DiscountCode');



//	是否具備 Free Shipping

$FREE_SHIPPING = ($this->session->userdata('FreeShipping') ? $this->session->userdata('FreeShipping') : 0);

//	總金額是否大於 Free Shipping 2 的規則

$IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);



$STR_FREE_SHIPPING = "";



if ($FREE_SHIPPING > 0) {

	$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$FREE_SHIPPING."' ");

	$shipping_method = $Query->row();

	$s_price = $shipping_method->price;

	$STR_FREE_SHIPPING = "(Plus -$$s_price Free Shipping)";

}

?>

<html>

    <head>

        

    </head>

    <body>

    

    	<style type="text/css">

            body { margin-top: 0; margin-right: 10px; margin-bottom: 20px; margin-left: 10px; color: #493838; }

            body, table { font: 12px/18px Helvetica, Arial, sans-serif; }

            table { min-width:680px; margin-bottom: 20px; }

            table span { color: #888; font-weight: bold; }

        </style>

        

        <table width="98%" cellspacing="0" cellpadding="3" border="0">

            <tbody>

                <tr>

                    <td colspan="5" style="height:65px;">

						<div style="width:100%;height:65px;border-bottom:1px solid #f3e4e9;">

							<img src="<?php echo base_url() ?>images/global/josie-maran.gif" alt="Josie Maran" />

						</div>

                    </td>

                </tr>

                <tr>

                    <td colspan="5">&nbsp;</td>

                </tr>

                <tr valign="top">

                    <td colspan="2">

	                    <span>Bill To</span><br />

	                    <?php echo $this->input->post('email') ?><br />

	                    <?php echo $this->input->post('firstname') ?>&nbsp;<?php echo $this->input->post('lastname') ?><br />

	                    <?php echo $this->input->post('bill_address') ?><br />

						<?php

							$bill_state = '';

                            $query = $this->db->query("SELECT * FROM tax_codes WHERE id = ?", $this->input->post('bill_state'))->result();

                            if (count($query) >= 1) {

                                $bill_state = $query[0]->tax_code;

                            }

						?>

	                    <?php echo $this->input->post('bill_city') ?>,&nbsp;<?php echo $bill_state; ?>&nbsp;<?php echo $this->input->post('bill_zipcode') ?><br /><br />

	                    

	                    <span>Ship To</span><br />

	

						<?php

							$shippingSame = $this->input->post('shipSame');



							if ($shippingSame == 1) {

						?>

						<?php echo $this->input->post('firstname') ?>&nbsp;<?php echo $this->input->post('lastname') ?><br />

						<?php echo $this->input->post('bill_address') ?><br />

	                    <?php echo $this->input->post('bill_city') ?>,&nbsp;<?php echo $this->input->post('ship_state') ?>&nbsp;<?php echo $this->input->post('bill_zipcode') ?><br /><br />						

						<?php

							} else {			

						?>

						<?php echo $this->input->post('ship_first_name') ?>&nbsp;<?php echo $this->input->post('ship_last_name') ?><br />

						<?php echo $this->input->post('ship_address') ?>&nbsp;<?php echo $this->input->post('ship_address2') ?><br />

	                    <?php echo $this->input->post('ship_city') ?>,&nbsp;<?php echo $this->input->post('ship_state') ?>&nbsp;<?php echo $this->input->post('ship_zipcode') ?><br /><br />

						<?php

							}

						?>

						

						<?php						

							$query = $this->db->get_where('webpages', array('id' => 30, 'publish' => '1')); 

							if($query->num_rows() > 0){ 

								$webpages = $query->row();

								echo $webpages->page_content;

							}

						?>



                    </td>

                    <td colspan="3">

	                    <span>Order Number</span>&nbsp;<br /><?php echo $this->INVOICE_NUMBER ?><br />

	                    <span>Order Date</span>&nbsp;<br /><?php echo $this->ORDER_DATE ?><br />

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



				<?php foreach ($this->cart->contents() as $items) {	?>

	                <tr valign="top" height="38px;">

						<td align="left">

                        <?php

                        if(!array_key_exists("type", $items) || ($items['type'] == 'voucher' || $items['type'] == 'buy_one_get_one' || $items['type'] == 'free_gift')) {

                            $query = $this->db->query("SELECT sku, on_sale, retail_price FROM product WHERE id = ?", $items['id']);

                            $sku = $query->result();

                            if (count($sku) >= 1) {

                                echo $sku[0]->sku;

                            }

                        } else{

                            echo 'eGift Voucher';

                        }

						?>

						</td>

	                    <td align="left">

                            <?php echo (key_exists("type", $items) && $items['type'] == 'buy_one_get_one') ? 'Free ': ''?>

                            <?php echo (key_exists("type", $items) && $items['type'] == 'free_gift') ? 'Gift ': ''?>

                            <?php //echo $items['name'] ?>

							<?php echo str_replace("GIFT ", "", $items['name']); ?>

                        </td>

						<td align="center"><?php echo $items['qty'] ?></td>

	                    <td align="right">

                            <?php if(!array_key_exists("type", $items)) :?>

							<?php echo ($sku[0]->on_sale == 1) ? ("<span style='text-decoration:line-through;color:red'>$" . $sku[0]->retail_price . "</span>") : "" ?>

                            <?php endif; ?>

							<?php echo "&#36;".$items['price'] ?>

						</td>

	                    <!-- <td align="right"><?php echo number_format($items['price'] * $items['qty'], 2) ?></td> -->

	                    <td align="right">$<?php echo ($items['price'] * $items['qty']) ?></td>

	                </tr>				

				<?php } ?>

				

                <tr>

                	<td colspan="2" align="right">&nbsp;</td>

					<td colspan="3" align="left"><div style="width: 100%; height: 1px; border-bottom: 1px solid rgb(243, 228, 233); margin-bottom: 15px; margin-top: 15px;">&nbsp;</div></td>

                </tr>

				

				

                <tr valign="middle">

					<td colspan="2" align="right">Subtotal</td>

					<td colspan="3" align="left">$<?php echo $this->cart->format_number($this->cart->total()) ?></td>

                </tr>

				

				<?php

				if (is_array($DiscountCode)) {

				

				if($DiscountCode[0]->code != 'freeshippingfortest1234') {

				

				?>

                <tr valign="middle">

					<td colspan="2" align="right">Promo</td>

					<td colspan="3" align="left">

						<span style="font-weight:normal;color:green;">

                            <?php if($DiscountCode[0]->discount_type == 4 || $DiscountCode[0]->discount_type == 5): ?>

                                +1 Free product

                            <?php else :?>

							<?php

								if ($this->session->userdata('Discount_Sub_Total') <= 0) {

							?>

								Free Shipping

							<?php } else { ?>

								-$<?php echo ($this->session->userdata('Discount_Sub_Total') ? $this->session->userdata('Discount_Sub_Total') : 0) ?>&nbsp;

								<?php //echo $STR_FREE_SHIPPING ?>

							<?php

								}

							?>

                            <?php endif; ?>

						</span>

                        &#34;<?php echo $DiscountCode[0]->code ?>&#34; 

					</td>

                </tr>

				<?php

				} }

				?>

				

				<?php if($this->session->userdata('is_zipcode_charge_amount')) { ?>

                <tr valign="middle">

					<td colspan="2" align="right">Total Tax</td>

					<td colspan="3" align="left">$<?php echo $this->session->userdata('is_zipcode_charge_amount') ?></td>

                </tr>

				<?php } ?>

				

               

				

                <tr valign="middle">

					<td colspan="2" align="right">Shipping</td>

                    <?php if ($ShippingOptions[0]['delivery'] == 'Email Delivery'):?>

                    <td colspan="3" align="left"><?php echo $ShippingOptions[0]['delivery']?></td>

                    <?php else:?>

                    <td colspan="3" align="left">

					  <?php 

					  	if ($IS_FREE_SHIPPING2 != '' && $IS_FREE_SHIPPING2 != '0') { 

						$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$IS_FREE_SHIPPING2."' ");

						$shipping_method = $Query->row();

					   ?>

					   	<span style="font-weight:normal;color:green;">Free Shipping</span>

					  <?php }else { ?>	

						<?php //echo $ShippingOptions[0]['name'] . "  " . $ShippingOptions[0]['delivery'] . "  " . $ShippingOptions[0]['price'] ?>

						<?php echo '$'.$ShippingOptions[0]['price'] ?>

					  <?php } ?>

					</td>

                    <?php endif;?>

                </tr>

				

				 <?php if ($this->session->userdata('VoucherCode') && $this->session->userdata('Voucher_Sub_Total')) : ?>

                <?php $Voucher = $this->session->userdata('VoucherCode');?>

                <tr valign="middle">

                    <td colspan="2" align="right">eGift Card</td>

                    <td colspan="3" align="left">

                        <span style="font-weight:normal;color:green;">

                            -$<?php echo $this->cart->format_number($this->session->userdata('Voucher_Sub_Total')); ?>&nbsp;

                            <?php if($this->session->userdata('VoucherBalance') && $this->session->userdata('VoucherBalance') > 0) : ?>

                            (Remaining balance $<?php echo $this->session->userdata('VoucherBalance')?>)

                            <?php endif;?>

                        </span>

                        &#34;<?php echo $Voucher[0]->code ?>&#34;

                    </td>

                </tr>

                <?php endif;?>

			

				

                <tr valign="middle">

					<td colspan="2" align="right">Grand Total</td>

					<td colspan="3" align="left">$<?php echo $this->session->userdata('Amount'); ?></td>

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

	                    If for any reason you are not satisfied with your Josie Maran Cosmetics purchase, simply return the unused portion, and we will be happy to remit your account for the full amount of your purchase. If you prefer, you may exchange your purchase for other Josie Maran products. We will accept returns and exchanges within 30 days of your invoice date.<br /><br />

	                    All <span style="color:#493838;font-weight:bold;">Good Buys items are FINAL SALE</span> and are non-refundable and/or exchangeable. For more information please visit <a href="<?php echo base_url() . "shipping-returns#returns" ?>">our&nbsp;site</a>.

                    </td>

                </tr>

            </tbody>

        </table>

    </body>

</html>