<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function(){
	
		jQuery("#release_date").datepicker({
			showButtonPanel: true,
			dateFormat: "yy-mm-dd"
		});
		
		jQuery("#expiry_date").datepicker({
			showButtonPanel: true,
			dateFormat: "yy-mm-dd"
		});		
		
		jQuery("#export").click(function(){
			document.frmMain.action = '<?php echo base_url() ?>admin.php/vieworders/export';
			document.frmMain.submit();
		});
		
	});
	
	
	function update_order (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/orders';
		jQuery("#frmMain").submit();
	}
	
	function customer (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/members';
		jQuery("#frmMain").submit();
	}
	
	
	
</script>
</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Reports: View Orders</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/vieworders" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Back to Search
							</a>
							<a href="#" class="btn ui-state-default" id="export">
								<span class="ui-icon ui-icon-circle-plus"></span>Export
							</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<?php
				if (isset($this->message) && !empty($this->message)) {
				?>
				<div class="response-msg success ui-corner-all">
					<span>Success message</span><?php echo $this->message; ?>
				</div>
				<?php
				}
				?>
				
				<?php
				if (isset($this->update_message) && !empty($this->update_message)) {
				?>
				<div class="response-msg inf ui-corner-all">
					<span>Information message</span><?php echo $this->update_message; ?>
				</div>
				<?php					
				}
				?>
				
				<?php
				if (isset($this->error_message) && !empty($this->error_message)) {
				?>
				<div class="response-msg error ui-corner-all">
					<span>Error message</span><?php echo $this->error_message; ?>
				</div>
				<?php
				}
				?>
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">					
					<form id="frmMain" name="frmMain" method="post" action="">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="10%">Order No.</td>
									<td width="10%">Customer Name</td>
									<td width="10%">Payment Method</td>
									<td width="10%">Order Date</td>
									<td width="10%" style="text-align:right;">Total Product Sales</td>
									<td width="10%" style="text-align:right;">Total Gift Card Sales</td>
									<td width="10%" style="text-align:right;">Discount</td>
									<td width="10%" style="text-align:right;">Taxes Collected</td>
									<td width="10%" style="text-align:right;">Shipping Charged</td>
									<td width="10%" style="text-align:right;">Grand Total</td>
									<td width="10%">Order Status</td>
									<td width="10%">Tracking No.</td>
								</tr>
							</thead>
							<?php
								$SUM_TOTAL_PRODUCT_SALES = 0;
								$SUM_TOTAL_VOUCHER_SALES = 0;
								$SUM_TAX                 = 0;
								$SUM_CALCULATE_SHIPPING  = 0;
								$SUM_GRAND_TOTAL         = 0;
								$SUM_DISCOUNT	         = 0;
							?>
							<?php foreach ($this->Orders as $Order): ?>
							<tr>
								<td>
									<a href="javascript:update_order(<?php echo $Order->order_id ?>);">
										<?php echo $Order->order_no ?>
									</a>
								</td>
								<td>
									<?php
									if ($Order->user_id != 0) {
									?>
									<a href="javascript:customer(<?php echo $Order->user_id ?>)"><?php echo $Order->firstname . ' ' . $Order->lastname ?></a>
									<?php
									} else { 
									?>
									<?php echo $Order->firstname . ' ' . $Order->lastname ?>
									<?php
									}
									
									?>
								
								</td>
								<?php if($Order->use_encryption == 'Y'){ ?>
                                <td>
									<?php if($Order->payment_method == 1) { echo base64_decode($Order->card_type); }else if($Order->payment_method == 3) { echo 'Voucher'; }else if($Order->payment_method == 4) { echo 'Test'; }else if($Order->payment_method == 5) { echo 'eGift Card + Credit Card'; } else { echo 'PayPal'; } ?>
								</td>
								<?php }else { ?>
								<td>
								<?php if($Order->payment_method == 1) { echo $Order->card_type; }else if($Order->payment_method == 3) { echo 'Voucher'; }else if($Order->payment_method == 4) { echo 'Test'; }else if($Order->payment_method == 5) { echo 'eGift Card + Credit Card'; }else { echo 'PayPal'; } ?>
								</td>
								<?php } ?>
								<td><?php echo $Order->odate . " " . $Order->oapm . " " . $Order->otime ?></td>
								<?php
									$query_v = $this->db->query("select sum(price) as voucher_total from order_list where invoice_number = '".$Order->order_no."' and item_type = 'voucher' ");
									$voucher_total =  $query_v->result();			
									$voucher_total = $voucher_total[0]->voucher_total;
									$without_voucher_total = $Order->subtotal - $voucher_total;
								?>
								
								<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($without_voucher_total, 2);  ?></td>
								<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($voucher_total, 2); ?></td>
								<td style="text-align:right;">&#36;&nbsp;(<?php echo number_format($Order->discount, 2) ?>)</td>
								<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($Order->product_tax, 2) ?></td>
								<td style="text-align:right;">&#36;&nbsp;
									<?php
										$calculate_shipping = 0;
										if ($Order->promo_free_shipping == 0 AND $Order->freeshipping == 0) {
											$calculate_shipping = $Order->calculate_shipping;
										} else {											
											if ($Order->shipping_id == 2 AND $Order->promo_free_shipping == 0 AND $Order->freeshipping == 0) {
												$calculate_shipping = $Order->calculate_shipping;
											} else if ($Order->shipping_id == 2) {
												$calculate_shipping = $Order->calculate_shipping;
											} else {
												$calculate_shipping = 0;
											}
										}
										$calculate_shipping = $Order->calculate_shipping;
										
										$cart_total = $Order->subtotal;
										
										$this->db->select('*');
										$this->db->from('freeshipping');
										$this->db->where('x_dollar_amount <=', $cart_total);
										$this->db->where('is_delete', 0);
										
										$this->db->where('release_date <=', $Order->created_at);
										$this->db->where('expiry_date >=', $Order->created_at);
										$can_FreeShipping = $this->db->get()->result_array();
										
										if(isset($Order->freeshipping_db) && $Order->freeshipping_db!="") {
											$calculate_shipping = 0.00;
										}
										
										
										if ($Order->freeshipping == 3) {
											$calculate_shipping = 0.00;
										}
										
										
									?>
									<?php echo number_format($calculate_shipping, 2) ?>
								</td>
								
								<!-- <td style="text-align:right;">&#36;&nbsp;<?php echo $Order->amount; ?></td> -->
								<td style="text-align:right;">&#36;&nbsp;
									<?php 
										$grand = round($without_voucher_total + $voucher_total + $Order->product_tax + $calculate_shipping, 2) - $Order->discount;
										//$grand = $Order->amount;
										echo number_format($grand, 2);
									?>
								</td>
								<td><?php echo $Order->order_state ?></td>
								<td><?php echo $Order->track_number ?></td>
							</tr>
							<?php
								$SUM_TOTAL_PRODUCT_SALES += $without_voucher_total;
								$SUM_TOTAL_VOUCHER_SALES += $voucher_total;
								$SUM_TAX                 += $Order->product_tax;
								$SUM_CALCULATE_SHIPPING  += $calculate_shipping;
								$SUM_DISCOUNT            += $Order->discount;
								$SUM_GRAND_TOTAL         = ($SUM_TOTAL_PRODUCT_SALES + $SUM_TOTAL_VOUCHER_SALES + $SUM_TAX + $SUM_CALCULATE_SHIPPING) - $SUM_DISCOUNT;
							?>
							<?php endforeach ?>
							
							<tr>
								<td style="text-align:left;font-weight:bold">TOTAL</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($SUM_TOTAL_PRODUCT_SALES, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($SUM_TOTAL_VOUCHER_SALES, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;(<?php echo number_format($SUM_DISCOUNT, 2) ?>)</td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($SUM_TAX, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($SUM_CALCULATE_SHIPPING, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($SUM_GRAND_TOTAL, 2) ?></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							
<style>
	.emm-paginate a { padding-left:5px; }
	.emm-current { padding-left:5px; font-weight:bold; }
</style>
							<?php /*?><tr style="border:none;">
								<td colspan="11" style="border:none;">
									<?php if ($this->pagination->create_links()) { ?>
										<div class="emm-paginate">
										<span class='emm-title' style="font-weight:bold;"><strong>Pages</strong>:</span>
										<?php  echo $this->pagination->create_links(); ?>
										</div>
									<?php } ?>
								</td>
							</tr><?php */?>
							
						</table>
						
					<input type="hidden" name="last_query" value="<?php echo $this->last_query ?>">
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="id" value="" id="id">
					<input type="hidden" name="report" value="TRUE" id="report">
					
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