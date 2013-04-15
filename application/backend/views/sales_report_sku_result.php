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
			document.frmMain.action = '<?php echo base_url() ?>admin.php/salesreport/export_sku';
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>base/salesreport">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="10%">SKU</td>
									<td width="10%">Item Number</td>
									<td width="10%">Invoice number</td>
									<td width="40%">Product</td>
									<td width="30%">Date</td>
                                    <td width="10%" style="text-align:right;">Unit price</td>
									<td width="10%" style="text-align:center;">Units</td>
									<td width="10%" style="text-align:right;">Sales</td>
								</tr>
							</thead>
							<?php
								$last_sku     = '';
								$last_product = '';
								$sku_count = 0;
								$sku_sales = 0;
								$sum_sku   = 0;
								$sum_sales = 0;
							?>
							<?php foreach ($this->sales_report as $report): ?>
							<?php
							$bol = ($last_sku != $report->sku) ? TRUE : FALSE;
							?>
							<?php
							if ($bol == TRUE && $sku_count != 0) {
							?>	
							<tr style="background-color:#eeeeee;">
								<td style="text-align:right;font-weight:bold;" colspan="6">SKU Total</td>
								<td style="text-align:center;font-weight:bold;"><?php echo $sku_count ?></td>
								<td style="text-align:right;font-weight:bold;">&#36;&nbsp;<?php echo number_format($sku_sales, 2) ?></td>
							</tr>
							<?php
								$sku_count = 0;
								$sku_sales = 0;
							}
							$sku_count += $report->qty;
							$sku_sales += $report->sales;
							?>
							
							<tr>
								<td style="text-align:left;"><?php echo $report->sku; ?></td>
								<td style="text-align:left;"><?php echo $report->item_number; ?></td>
								<td style="text-align:left;"><?php echo $report->invoice_number; ?></td>
								<td><?php echo $report->product ?></td>
								<td><?php echo $report->odate . " " . $report->oapm . " " . $report->otime; ?></td>
                                <td style="text-align:right;">&#36;&nbsp;<?php echo number_format($report->unit_price, 2); ?></td>
								<td style="text-align:center;"><?php echo $report->qty; ?></td>
								<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($report->sales, 2); ?></td>
							</tr>
							<?php
								$last_sku     = $report->sku;
								$last_product = $report->product;
								// $sku_count += $report->qty;
								$sum_sku += $report->qty;
								$sum_sales += $report->sales;
							?>
							<?php endforeach ?>
							<tr style="background-color:#eeeeee;">
								<td style="text-align:right;font-weight:bold;" colspan="6">SKU Total</td>
								<td style="text-align:center;font-weight:bold;"><?php echo $sku_count ?></td>
								<td style="text-align:right;font-weight:bold;">&#36;&nbsp;<?php echo number_format($sku_sales, 2) ?></td>
							</tr>
							<tr>
								<td colspan="6" style="text-align:right;font-weight:bold;border-top:2px solid #ccc;">Total</td>
								<td style="text-align:center;font-weight:bold;border-top:2px solid #ccc;"><?php echo $sum_sku ?></td>
								<td style="text-align:right;font-weight:bold;border-top:2px solid #ccc;">&#36;&nbsp;<?php echo number_format($sum_sales, 2); ?></td>
							</tr>
						</table>
											
					<input type="hidden" name="action" value="search" id="action">
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




