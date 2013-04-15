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
			document.frmMain.action = '<?php echo base_url() ?>admin.php/shipping_address/export';
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
					<h1><b>Reports: Monthly Shipment Report</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/shipping_address" class="btn ui-state-default">
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
									<td>Order No</td>
									<td>Order Date</td>
									<td>Tracking No</td>
									<td>Billing First Name</td>
									<td>Billing Last Name</td>
									<td>E-mail</td>
									<td>Phone Number</td>
									
									<td>Billing City</td>
									<td>Billing State</td>
									<td>Billing Zipcode</td>
									<td>Billing Address</td>
									
									<td>Shipping First Name</td>
									<td>Shipping Last Name</td>
									<td>Shipping City</td>
									<td>Shipping State</td>
									<td>Shipping Zipcode</td>
									<td>Shipping Address</td>
									
								</tr>
							</thead>
							<tbody>
								<?php foreach ($this->Orders as $order)  { ?>
								<tr>
									<td><?php echo $order->order_no ?></td>
									<td><?php echo $order->order_date ?></td>
									<td><?php echo $order->track_number ?></td>
									<td><?php echo $order->firstname ?></td>
									<td><?php echo $order->lastname ?></td>
									<td><?php echo $order->email ?></td>
									<td><?php echo $order->phone_number ?></td>
									
									<td><?php echo $order->bill_city ?></td>
									<td><?php echo $order->bill_state ?></td>
									<td><?php echo $order->bill_zipcode ?></td>
									<td><?php echo $order->bill_address ?></td>
									<td><?php echo $order->ship_first_name ?></td>
									<td><?php echo $order->ship_last_name ?></td>
									<td><?php echo $order->ship_city ?></td>
									<td><?php echo $order->ship_state ?></td>
									<td><?php echo $order->ship_zipcode ?></td>
									<td><?php echo $order->ship_address ?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>		
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="selmonthly" value="<?php echo $this->input->post('selmonthly'); ?>" id="selmonthly">
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




