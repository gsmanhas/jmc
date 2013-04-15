<?php
$FreeShipping = "";
if ($this->Order[0]->promo_free_shipping == 1 || $this->Order[0]->freeshipping == 1) {
	$FreeShipping = "(Plus Free Shipping)";
}

?>
<html>
    <head>
    </head>
    <body style="background-color:white">

    	<style type="text/css">
            body { margin: 0 30px 30px 30px; color: #493838; }
            body, table { font: 12px/18px Helvetica, Arial, sans-serif; }
            table { min-width:680px; margin-bottom: 20px; }
            table span { color: #888; font-weight: bold; }
        </style>

        <table width="98%" cellspacing="0" cellpadding="3" border="0">
            <tbody>

				<tr>
                    <td colspan="5" style="height:65px;">
						<div style="width:100%;height:65px;border-bottom:1px solid #f3e4e9;">
							<img src="/images/global/josie-maran.gif" alt="Josie Maran" />
						</div>
                    </td>
                </tr>

				<tr>
					<td colspan="5">
						<div>&nbsp;</div>
					</td>
				</tr>
                <tr>
                    <td colspan="2">
	                    <span>Bill To</span><br />
	                    <?php echo $this->Order[0]->email ?><br />
	                    <?php echo $this->Order[0]->firstname ?>&nbsp;<?php echo $this->Order[0]->lastname ?><br />
	                    <?php echo $this->Order[0]->bill_address ?><br />
						<?php
							$sharthand = "";
							$query = $this->db->query('SELECT * FROM state where id = ?', $this->Order[0]->bill_state)->result();
							if (count($query) >= 1) {
								$sharthand = $query[0]->sharthand;
							}
						?>
	                    <?php echo $this->Order[0]->bill_city ?>,&nbsp;<?php echo $sharthand ?>&nbsp;<?php echo $this->Order[0]->bill_zipcode ?><br /><br />

	                    <span>Ship To</span><br />
						<?php if ($this->Order[0]->shipping_same == 1) { ?>
							<?php echo $this->Order[0]->firstname ?>&nbsp;<?php echo $this->Order[0]->lastname ?><br />
							<?php echo $this->Order[0]->bill_address ?><br />
		                    <?php echo $this->Order[0]->bill_city ?>,&nbsp;<?php echo $sharthand ?>&nbsp;<?php echo $this->Order[0]->bill_zipcode ?><br /><br />
						<?php } else { ?>
							<?php echo $this->Order[0]->ship_first_name ?>&nbsp;<?php echo $this->Order[0]->ship_last_name ?><br />
						    <?php echo $this->Order[0]->ship_address ?><br />
		                    <?php echo $this->Order[0]->ship_city ?>,&nbsp;<?php echo $this->Order[0]->destination_state ?>&nbsp;<?php echo $this->Order[0]->ship_zipcode ?><br /><br />
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
	                    <span>Payment Type</span>&nbsp;<br />
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
							
							echo $PAYMENT_METHOD 
							
						?>
							
						<br />
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
				<?php
					$subtotal = 0;
				?>
				<?php foreach ($this->OrderList as $item) { ?>
                <?php
                if($item->item_type == 'product'):
                        $Product = $this->db->query("SELECT * FROM product WHERE id = ?", $item->pid)->result();
                ?>
                <tr valign="top" height="38px;">
                    <td align="left"><?php echo $Product[0]->sku ?></td>
                    <td align="left"><?php echo $Product[0]->name ?></td>
                    <td align="left"><?php echo $item->qty ?></td>
                    <td align="right">
                        <?php echo ($Product[0]->on_sale == 1) ? ("<span style='text-decoration:line-through;color:red'>&#36;" . $Product[0]->retail_price . "</span>") : "" ?>
                        <?php echo "&#36;" . $item->price ?>
                    </td>
                    <!-- <td align="right"><?php echo number_format($item->price * $item->qty, 2) ?></td> -->
                    <td align="right">$<?php echo $item->qty * $item->price ?></td>
                </tr>
                <?php else :  $Product = $this->db->query("SELECT * FROM order_voucher_details WHERE id=?", $item->pid)->result();?>
                <tr valign="top" height="38px;">
                    <td align="left">eGift Cards / Voucher</td>
                    <td align="left"><?php echo $Product[0]->title ?></td>
                    <td align="left"><?php echo $item->qty ?></td>
                    <td align="right">
                        <?php echo "&#36;" . $item->price ?>
                    </td>
                    <!-- <td align="right"><?php echo number_format($item->price * $item->qty, 2) ?></td> -->
                    <td align="right">$<?php echo $item->qty * $item->price ?></td>
                </tr>
                <?php endif;?>

				<?php
					$subtotal += ($item->qty * $item->price);
				?>

				<?php } ?>
                <tr>
                	<td colspan="2" align="right">&nbsp;</td>
					<td colspan="3" align="left"><div style="width: 100%; height: 1px; border-bottom: 1px solid rgb(243, 228, 233); margin-bottom: 15px; margin-top: 15px;">&nbsp;</div></td>
                </tr>
                <tr valign="middle">
					<td colspan="2" align="right">Subtotal</td>
					<td colspan="3" align="left">$<?php echo $subtotal ?></td>
                </tr>
                <?php if($this->Order[0]->product_tax) { ?>
                <tr valign="middle">
					<td colspan="2" align="right">Total Tax</td>
					<td colspan="3" align="left">$<?php echo $this->Order[0]->product_tax ?></td>
                </tr>
				<?php } ?>
				<?php if($this->Order[0]->charge_zip_amount) { ?>
				<tr valign="middle">
					<td colspan="2" align="right">Total Tax</td>
					<td colspan="3" align="left">$<?php echo $this->Order[0]->charge_zip_amount ?></td>
                </tr>
				<?php } ?>

				<?php
				if ($this->Order[0]->discount_id != 0) {
				if($Order[0]->discount_code != 'freeshippingfortest1234') {
				?>
                <tr valign="middle">
					<td colspan="2" align="right">Promo</td>
					<td colspan="3" align="left">&#34;<?php echo $this->Order[0]->discount_code ?>&#34;<br />
					<span style="font-weight:normal;color:green;">
					 <?php if($this->Discount->discount_type == 4 || $this->Discount->discount_type == 5): ?>
                                +1 Free product
                                <?php else :?>
								-$<?php echo $this->Order[0]->discount ?>&nbsp;<?php echo $FreeShipping ?>
                                <?php endif;?>
					</span></td>
                </tr>
				<?php
				} }
				?>
                <tr valign="middle">
					<td colspan="2" align="right">Shipping</td>
					<td colspan="3" align="left"><?php echo $this->Order[0]->shipping_name . " " . $this->Order[0]->shipping_delivery . " &#36;" . $this->Order[0]->shipping_price ?></td>
                </tr>
				
				
				<?php 
					if ($this->Order[0]->freeshipping != '' && $this->Order[0]->freeshipping != '0') { 
					$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$this->Order[0]->freeshipping."' ");
					$shipping_method = $Query->row();
				 ?>
				<tr valign="middle">
					<td colspan="2" align="right">&nbsp;</td>
					<td colspan="3" align="left"><span style="font-weight:normal;color:green;">-&#36;<?php $shipping_method->price; ?> Free Shipping</span></td>
                </tr>
				<?php }?>
                <tr valign="middle">
					<td colspan="2" align="right">Grand Total</td>
					<td colspan="3" align="left">$<?php echo $this->Order[0]->amount ?></td>
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