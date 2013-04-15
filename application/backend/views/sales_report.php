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
					<h1><b>Reports: Sales Report</b></h1>
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/salesreport/search">
							
							<ul>
								<li>
									<label class="desc">From Order Date</label>
									<input type="text" name="release_date" value="" id="release_date" style="width:50%;">
								</li>
								<li>
									<label class="desc">To Order Date</label>
									<input type="text" name="expiry_date" value="" id="expiry_date" style="width:50%;">
								</li>
								<li>
									<label class="desc">Sales by</label>
									<select name="groupType" id="groupType" size="1">
										<option value="byDay">Sales by Day</option>
										<!-- <option value="byWeek">Sales by Week</option>
										<option value="byMonth">Sales by Month</option>
										<option value="byYear">Sales by Year</option> -->
										<!-- <option value="all">All Sales</option> -->
									</select>
								</li>
								<li>
									<label class="desc">Product Type</label>
									<select name="productType" id="productType" size="1">
										<option value="all">All Products</option>
										<option value="sku">By SKU</option>
										<!-- <option value="category">By Category</option> -->
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
		<?php $this->load->view('base/footer') ?>
	</div>
</body>
</html>