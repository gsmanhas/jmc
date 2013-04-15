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
					<h1><b>Reports: Promo Usage</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/promousage" class="btn ui-state-default">
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/reports">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="20%">Customer Name</td>
									<td width="20%" style="text-align:;">Order No</td>
									<td width="20%" style="text-align:;">Order Date</td>
									<td width="12%" style="text-align:right;">Grand Total</td>
									<td width="12%" style="text-align:right;">Discount Total</td>
									<td width="12%" style="text-align:right;">Free Shipping</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($this->orders as $order): ?>
									<tr>
										<td><?php echo $order->firstname . ' ' .$order->lastname ?></td>
										<td><?php echo $order->order_no ?></td>
										<td><?php echo $order->order_date ?></td>
										<td style="text-align:right;">$<?php echo number_format($order->amount, 2) ?></td>
										<td style="text-align:right;">$<?php echo number_format($order->discount, 2) ?></td>
										<td style="text-align:right;"><?php echo ($order->promo_free_shipping == 1) ? "x" : "&nbsp;" ?></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>
						
											
					<input type="hidden" name="action" value="search" id="action">

					
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