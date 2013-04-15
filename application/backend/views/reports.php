<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('admin/head'); ?>
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
			$this->load->view('admin/account');
			$this->load->view('admin/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Reports</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
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
				<table>
					<tr>
						<td>					
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin/reports">
							
							<ul>
								<li>
									<label class="desc">Order No.</label>
									<input type="text" name="order_no" value="" id="order_no" style="width:50%;">
								</li>
								<li>
									<label class="desc">From Order Date</label>
									<input type="text" name="release_date" value="" id="release_date" style="width:50%;">
								</li>
								<li>
									<label class="desc">To Order Date</label>
									<input type="text" name="expiry_date" value="" id="expiry_date" style="width:50%;">
								</li>
								<li>
									<label class="desc">Customer Name</label>
									<input type="text" name="customer_name" value="" id="" style="width:50%;">
								</li>
								<li>
									<label class="desc">Billing Address</label>
									<input type="text" name="bill_address" value="" id="" style="width:50%;">
								</li>
								<li>
									<label class="desc">Shipping Address</label>
									<input type="text" name="ship_address" value="" id="" style="width:50%;">
								</li>
								<li>
									<label class="desc">Product Name</label>
									<input type="text" name="product_name" value="" id="" style="width:50%;">
								</li>
								<li>
									<label class="desc">SKU</label>
									<input type="text" name="sku" value="" id="" style="width:50%;">
								</li>
								<li>
									<label class="desc">Discount Code</label>
									<input type="text" name="discount_code" value="" id="" style="width:50%;">
								</li>
								<li>
									<label class="desc">Shipping Destination State</label>
									<select name="destination_state" id="destination_state" size="1">
										<?php
										foreach ($this->TaxCodes as $tax) {
										?>
										<option price="<?php echo $tax->tax_rate ?>" value="<?php echo $tax->id ?>"><?php echo $tax->tax_code ?></option>
										<?php
										}
										?>
									</select>
								</li>
							</ul>
							
							<br><br>
							
							<input type="submit" name="btnSubmit" value="Search" id="btnSubmit" class="btn ui-state-default ui-corner-all">						
							<input type="hidden" name="action" value="search" id="action">
							
							</form>
						</td>
					</tr>
				</table>
				</div>
				
			</div>			
			<div class="clearfix"></div>
		</div>

		<div class="clearfix"></div>
		<?php $this->load->view('admin/footer') ?>
	</div>
</body>
</html>