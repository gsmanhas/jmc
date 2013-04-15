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
<style type="text/css" media="screen">
	.hastable input.text {
		width: 50%;
	}
</style>
</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Customers</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/members/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Customer
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
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>
				
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/members/search">
					<div class="hastable">
					<table>
						<tr>
							<td>
								<ul>
									<li>
										<label class="desc">First Name</label>
										<input type="text" name="first_name" value="" id="first_name" style="width:50%;">
									</li>
									<li>
										<label class="desc">Last Name</label>
										<input type="text" name="last_name" value="" id="last_name" style="width:50%;">
									</li>
									<li>
										<label class="desc">Email</label>
										<input type="text" name="email" value="" id="email" style="width:50%;">
									</li>
									<li>
										<label class="desc">Billing Address</label>
										<input type="text" name="bill_address" value="" id="bill_address" style="width:50%;">
									</li>
									<li>
										<label class="desc">Shipping Address</label>
										<input type="text" name="ship_address" value="" id="ship_address" style="width:50%;">
									</li>
									<li>
										<label class="desc">State</label>
										<select name="state" id="state" size="1">
											<option value="0">Select a state</option>
											<?php foreach ($this->ListDestinationState as $state): ?>
											<option value="<?php echo $state->id ?>"><?php echo $state->state ?></option>
											<?php endforeach ?>
										</select>
									</li>
									<!-- <li>
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
									</li> -->
									<li>
										<br><br>
										<input type="submit" name="btnSubmit" value="Search" id="btnSubmit" class="btn ui-state-default ui-corner-all">
									</li>
								</ul>
							</td>
						</tr>
					</table>
						
						<input type="hidden" name="action" value="" id="action">
						<input type="hidden" name="id" value="0" id="id">
						<input type="hidden" name="enabled" value="" id="enabled">
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