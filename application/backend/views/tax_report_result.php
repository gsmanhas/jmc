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
			
	});
	
	function sales_to_ca () {
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
									<td width="25%">Description</td>
									<td width="15%" style="text-align:right;">Subtotal</td>
									<td width="15%" style="text-align:right;">Tax Collected</td>
									<td width="15%" style="text-align:right;">Shipping Charged</td>
									<td width="10%" style="text-align:right;">Coupon Discounts</td>
									<td width="10%" style="text-align:right;">Total Billed</td>
									<td width="10%" style="text-align:right;">Gift Card Sale</td>
								</tr>
							</thead>
							<tr>
								<td>
									<a href="javascript:sales_to_ca()">Orders Shipped to California</a>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($this->SubTotal[0]->subtotal, 2) ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($this->Product_tax[0]->product_tax, 2) ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($this->Shipping_Charged[0]->shipping_charged, 2) ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;(<?php echo number_format($this->Discount[0]->discount, 2) ?>)
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;
									<?php
									$Total = $this->SubTotal[0]->subtotal + 
											 $this->Product_tax[0]->product_tax + 
											 $this->Shipping_Charged[0]->shipping_charged - $this->Discount[0]->discount;
									echo number_format($Total, 2);
									?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;
									<?php
										echo number_format($this->SubTotal_Voucher[0]->subtotal, 2);
									?>
								</td>
								
							</tr>
							<tr>
								<td>Orders shipped to other states</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($this->NOT_CA[0]->subtotal, 2) ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($this->NOT_CA[0]->tax_collected, 2) ?>
								</td>
								<td style="text-align:right;">
									<?php
										$release_date = $this->input->post('release_date');
										$expiry_date  = $this->input->post('expiry_date');
										$Query = $this->db->query(
											"SELECT sum(shipping_price) as 'shipping_charged' FROM `order` as o WHERE destination_state != 'CA' AND freeshipping = 0 " .
											"AND `o`.`order_state` != 3 AND " .
											" order_state != 5 AND order_state != 6 AND `o`.`order_date` >= ? " .
											" AND `o`.`order_date` <= ? AND `o`.`is_delete` = 0"
										, array($release_date, $expiry_date))->result();
									?>
									&#36;&nbsp;<?php echo number_format($Query[0]->shipping_charged, 2) ?>
								</td>
								<td style="text-align:right;">
									<?php
										$Query2 = $this->db->query(
											"SELECT SUM(discount) as 'discount' FROM `order` as o " .
											" WHERE `destination_state` != 'CA' AND `o`.`order_state` != 3 " .
											" AND order_state != 5 AND order_state != 6 AND " .
											"`o`.`order_date` >= ? AND `o`.`order_date` <= ? AND `o`.`is_delete` = 0"
											, array($release_date, $expiry_date))->result();
									?>
									&#36;&nbsp;(<?php echo number_format($Query2[0]->discount, 2) ?>)
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;
									<?php
									$Total2 = $this->NOT_CA[0]->subtotal + 
											 $this->NOT_CA[0]->tax_collected + 
											 $Query[0]->shipping_charged - $Query2[0]->discount;
									echo number_format($Total2, 2);
									?>
								</td>
								
								<td style="text-align:right;">
									&#36;&nbsp;
									<?php
										echo number_format($this->NOT_CA_Voucher[0]->subtotal, 2);
									?>
								</td>
								
							</tr>
						</table>
						
											
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="release_date" value="<?php echo $this->input->post('release_date') ?>" id="release_date">
					<input type="hidden" name="expiry_date" value="<?php echo $this->input->post('expiry_date') ?>" id="expiry_date">
					
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