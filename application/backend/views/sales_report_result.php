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
			document.frmMain.action = '<?php echo base_url() ?>admin.php/salesreport/export';
			document.frmMain.submit();
		});
		
	});
	
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
					<h1><b>Reports: Sales Report</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/salesreport" class="btn ui-state-default">
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/salesreport">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="1%" style="text-align:left;">No</td>
									<td width="15%" style="text-align:left;">Date</td>
									<td width="15%" style="text-align:right;">Total Product Sales</td>
									<td width="15%" style="text-align:right;">Total Gift Card Sales</td>
									<td width="20%" style="text-align:right;">Tax Collected</td>
									<td width="20%" style="text-align:right;">Shipping Charged</td>
									<td width="20%" style="text-align:right;">Grand Total</td>
								</tr>
							</thead>
							<?php $total = 0; $i = 1; ?>
							<?php 
								$sum_orders = 0;
								$Sum_TotalProductSales = 0;
								$Sum_ShippingCharged = 0;
								$Sum_TaxCollected = 0;
								$SumGrandTotal = 0;
							?>
							<?php foreach ($this->sales_report as $sales_report): ?>
							<tr>
								<td style="text-align:left;"><?php echo $i; ?></td>
								<td style="text-align:left;"><?php echo $sales_report->order_date ?></td>
								<?php

									$Query = $this->db->query("SELECT " .
									"SUM((SELECT SUM(price * qty) FROM order_list as ol WHERE ol.item_type!='voucher' and ol.order_id = o.id)) as 'subtotal'," .
									"SUM(amount) as 'TotalProductSales', " .
									"SUM(calculate_shipping) as 'ShippingCharged', " .
									"SUM(product_tax) as 'TaxCollected'," .
									"SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 1 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'ShippingCharged2'," .
									// "SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'twoDays'" .
									"SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND  order_state != 5 AND order_state != 6)) as 'twoDays'" .
									", SUM(o.discount) as 'discount' " .
									", SUM(o.amount) as 'amount' " .
									"FROM `order` as o " .
									"WHERE order_date like '" . $sales_report->order_date . "%' " .
									"AND order_state != 5 AND order_state != 6 AND o.is_delete = 0", FALSE)->result();
									
									// echo $this->db->last_query().br(2);
									
									$TotalProductSales = 0;
									$ShippingCharged = 0;
									$TaxCollected = 0;
									
									if (count($Query) >= 1) {
										$TotalProductSales = $Query[0]->subtotal;
										$ShippingCharged   = $Query[0]->ShippingCharged + $Query[0]->twoDays;
										$TaxCollected      = $Query[0]->TaxCollected;
									}
									
									$Total = round($TotalProductSales + $TaxCollected + $ShippingCharged, 2) - $Query[0]->discount;
									
									/*$Query = $this->db->query("SELECT " .
									"SUM((SELECT SUM(price * qty) FROM order_voucher_details as ovd WHERE ovd.order_id = o.id)) as 'subtotal'," .
									"SUM(amount) as 'TotalVoucherSales', " .
									"SUM(calculate_shipping) as 'ShippingCharged', " .
									"SUM(product_tax) as 'TaxCollected'," .
									"SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 1 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'ShippingCharged2'," .
									"SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'twoDays'" .
									", SUM(o.discount) as 'discount' " .
									", SUM(o.amount) as 'amount' " .
									"FROM `order` as o " .
									"WHERE order_date like '" . $sales_report->order_date . "%' " .
									"AND `o`.`order_state` != 3 AND order_state != 5 AND order_state != 6 AND o.is_delete = 0", FALSE)->result();*/
									
									$Query = $this->db->query("SELECT " .
									"SUM((SELECT SUM(price * qty) FROM order_list as ol WHERE ol.item_type='voucher' and ol.order_id = o.id)) as 'subtotal'," .
									"SUM(amount) as 'TotalProductSales', " .
									"SUM(calculate_shipping) as 'ShippingCharged', " .
									"SUM(product_tax) as 'TaxCollected'," .
									"SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 1 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'ShippingCharged2'," .
									// "SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND promo_free_shipping = 0 AND freeshipping = 0 AND order_state != 3 AND order_state != 5 AND order_state != 6)) as 'twoDays'" .
									"SUM((SELECT ROUND(calculate_shipping, 2) FROM `order` as o1 WHERE o1.id = o.id AND shipping_id = 2 AND  order_state != 5 AND order_state != 6)) as 'twoDays'" .
									", SUM(o.discount) as 'discount' " .
									", SUM(o.amount) as 'amount' " .
									"FROM `order` as o " .
									"WHERE order_date like '" . $sales_report->order_date . "%' " .
									"AND order_state != 5 AND order_state != 6 AND o.is_delete = 0", FALSE)->result();
									
									$TotalVoucherSales = 0;
									$ShippingCharged_v = 0;
									$TaxCollected_v = 0;
									$Total_v = 0;
									
									if (count($Query) >= 1) {
										$TotalVoucherSales = $Query[0]->subtotal;
										$ShippingCharged_v = $Query[0]->ShippingCharged + $Query[0]->twoDays;
										$TaxCollected_v      = $Query[0]->TaxCollected;
										$Total_v = round($TotalVoucherSales + $TaxCollected_v + $ShippingCharged_v, 2) - $Query[0]->discount;
										
										$TaxCollected      = $TaxCollected + $Query[0]->TaxCollected;
										$ShippingCharged = $ShippingCharged + ($Query[0]->ShippingCharged + $Query[0]->twoDays);
										$Total = $Total + $Total_v;
									}
									
									
																			
								?>
								<td style="text-align:right;">&#36;<?php echo number_format($TotalProductSales, 2) ?></td>
								<td style="text-align:right;">&#36;<?php echo number_format($TotalVoucherSales, 2) ?></td>
								<td style="text-align:right;">&#36;<?php echo number_format($TaxCollected, 2) ?></td>
								<td style="text-align:right;">&#36;<?php echo number_format($ShippingCharged, 2) ?></td>
								<td style="text-align:right;">&#36;<?php echo number_format($Total, 2) ?></td>
							</tr>
							
							<?php 
								// $sum_orders += $sales_report->orders;
								// $total += $sales_report->amount;
								$Sum_TotalProductSales += $TotalProductSales;
								$Sum_TotalVoucherSales += $TotalVoucherSales;
								$Sum_ShippingCharged   += $ShippingCharged;
								$Sum_TaxCollected      += $TaxCollected;
								$SumGrandTotal         += ($Total);

								
								// $SumGrandTotal = $Query[0]->TotalProductSales;
								
								$i++;
							?>
							<?php endforeach ?>
							
							<tr>
								<td colspan="2" style="text-align:left;font-weight:bold">TOTAL</td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($Sum_TotalProductSales, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($Sum_TotalVoucherSales, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($Sum_TaxCollected, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($Sum_ShippingCharged, 2) ?></td>
								<td style="text-align:right;font-weight:bold">&#36;&nbsp;<?php echo number_format($SumGrandTotal, 2) ?></td>
							</tr>
						</table>
											
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="last_query" value="<?php echo $this->last_query ?>">
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




