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
			document.frmMain.action = '<?php echo base_url() ?>admin.php/taxreport/export';
			document.frmMain.submit();
		});
		
	});
	
	function sales_to_ca () {
		jQuery("#frmMain").submit();
	}
	
	function update_order (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/orders';
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
					<h1><b>Reports: Sales Tax Report</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/taxreport" class="btn ui-state-default">
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/taxreport/sales_ca">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="16%">Order ID</td>
									<td width="16%">Date/Time Placed</td>
									<td width="8%" style="text-align:center;">Shipped To</td>
									<td width="12%" style="text-align:right;">Subtotal</td>
									<td width="12%" style="text-align:right;">Tax Collected</td>
									<td width="12%" style="text-align:right;">Shipping Charged</td>
									<td width="12%" style="text-align:right;">Coupon Discounts</td>
									<td width="12%" style="text-align:right;">Total Billed</td>
								</tr>
							</thead>
							<?php
							$sum_subtotal        = 0;
							$sum_product_tax     = 0;
							$sum_shipping_price  = 0;
							$sum_discount        = 0;
							$sum_amount          = 0;
							?>
							<?php foreach ($this->CA_Query as $result): ?>
							<tr>
								<td><a href="javascript:update_order(<?php echo $result->id ?>)"><?php echo $result->order_no?></a></td>
								<td><?php echo $result->odate . " " . $result->oapm . " " . $result->otime?></td>
								<td style="text-align:center;"><?php echo $result->destination_state?></td>
								<td style="text-align:right;">$<?php echo number_format($result->subtotal, 2)?></td>
								<td style="text-align:right;">$<?php echo number_format($result->product_tax, 2)?></td>
								<?php 
									$shipping_price = 0;
									if ($result->freeshipping == 0 && $result->promo_free_shipping == 0) {
										$shipping_price = $result->shipping_price;
									}
								?>
								<td style="text-align:right;">$<?php echo number_format($shipping_price, 2) ?></td>
								<td style="text-align:right;">$<?php echo number_format($result->discount, 2)?></td>
								<td style="text-align:right;">$<?php echo number_format($result->amount, 2)?></td>
							</tr>
							<?php
								$sum_subtotal       += $result->subtotal;
								$sum_product_tax    += $result->product_tax;
								$sum_shipping_price += $shipping_price;
								$sum_discount       += $result->discount;
								// $sum_amount         += $result->amount;
								$sum_amount         += ($result->subtotal + $result->product_tax + $shipping_price) - $result->discount;
							?>
							<?php endforeach ?>
							<tr>
								<td style="text-align:left;border-top:2px solid #ccc;font-weight:bold">TOTAL</td>
								<td style="border-top:2px solid #ccc;font-weight:bold">&nbsp;</td>
								<td style="border-top:2px solid #ccc;font-weight:bold">&nbsp;</td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold">$<?php echo number_format($sum_subtotal, 2) ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold">$<?php echo number_format($sum_product_tax, 2) ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold">$<?php echo number_format($sum_shipping_price, 2) ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold">$<?php echo number_format($sum_discount, 2) ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold">$<?php 
									echo number_format($sum_amount, 2) 
								?></td>
							</tr>
						</table>
						
					<input type="hidden" name="id" value="" id="id">
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="release_date" value="<?php echo $this->input->post('release_date') ?>" id="release_date">
					<input type="hidden" name="expiry_date" value="<?php echo $this->input->post('expiry_date') ?>" id="expiry_date">
					<input type="hidden" name="last_query" value="<?php echo $this->last_query ?>" id="last_query">
					
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