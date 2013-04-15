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
	
	function update_order (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin/orders';
		jQuery("#frmMain").submit();
	}
	
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
					<h1><b>Report Results</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin/reports" class="btn ui-state-default">
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin/reports">
						
						<table border="0" cellspacing="5" cellpadding="5">
						<thead>
							<tr>
								<td>Order No</td>
								<td>Order Date</td>
								<td>First Name</td>
								<td>Last Name</td>
								<td>Billing Address</td>
								<td>Shipping Address</td>
								<td>Amount</td>
							</tr>
						</thead>
							<?php foreach ($this->Report as $report) { ?>
								<tr>
									<td style="text-align:center;"><a href="javascript:update_order(<?php echo $report->id ?>);"><?php echo $report->order_no ?></a></td>
									<td style="text-align:center;"><?php echo $report->order_date ?></td>
									<td><?php echo $report->firstname ?></td>
									<td><?php echo $report->lastname ?></td>
									<td><?php echo $report->bill_address ?></td>
									<td><?php echo $report->ship_address ?></td>
									<td style="text-align:right;"><?php echo $report->amount ?></td>
								</tr>
							<?php } ?>
						</table>
						
						<input type="hidden" name="id" value="0" id="id">
						<input type="hidden" name="action" value="search" id="action">
					
					</form>
				</div>
				
			</div>			
			<div class="clearfix"></div>
		</div>
		<!-- <div id="sidebar">
			<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
				<div class="portlet-header ui-widget-header"><span class="ui-icon ui-icon-circle-arrow-s"></span>Theme Switcher</div>
				<div class="portlet-content">
					<ul class="side-menu">
						<li>
							<a title="Default Theme" style="font-weight: bold;" href="javascript:void(0);" id="default" class="set_theme">Default Theme</a>
						</li>
						<li>
							<a title="Light Blue Theme" href="javascript:void(0);" id="light_blue" class="set_theme">Light Blue Theme</a>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="clearfix"></div>
		</div> -->
		<div class="clearfix"></div>
		<?php $this->load->view('admin/footer') ?>
	</div>
</body>
</html>