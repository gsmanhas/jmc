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
			document.frmMain.action = '<?php echo base_url() ?>admin.php/salesbysku/export';
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
					<h1><b>Reports: Sales by SKU </b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/salesbysku" class="btn ui-state-default">
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin/reports">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="10%">SKU</td>
									<td width="80%">Product</td>
									<!-- <td>Shade</td> -->
									<td width="10%" style="text-align:center;">Items Sold</td>
								</tr>
							</thead>
							<?php
								$sum_qty = 0;
							?>
							<?php foreach ($this->Orders as $Order): ?>
							<tr>
								<td><?php echo $Order->sku ?></td>
								<td><?php echo $Order->product ?></td>
								<!-- <td>&nbsp;</td> -->
								<td style="text-align:center;"><?php echo $Order->qty ?></td>
							</tr>
							<?php
								$sum_qty += $Order->qty;
							?>
							<?php endforeach ?>
							<tr>
								<td colspan="2" style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">Total</td>
								<td style="text-align:center;border-top:2px solid #ccc;font-weight:bold;">
									<?php echo $sum_qty ?>
								</td>
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