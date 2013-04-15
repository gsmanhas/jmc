<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function(){
	
		jQuery("#date").datepicker({
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
				<?php if($this->uri->segment(1) == 'voucheramount' ) { ?>
					<h1><b>Reports: Voucher Amount</b></h1>					
				<?php } else { ?>
					<h1><b>Reports: Voucher Orders</b></h1>
				<?php } ?>	
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
				
				if (isset($this->amount)) {
				?>
				<div class="response-msg success ui-corner-all">
					<span>Voucher Balance:&nbsp;$<?php echo number_format($this->amount, 2); ?>&nbsp;For date: <?php echo $this->input->post('date')?></span>
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/voucheramount/search">
							
							<ul>
								<li>
									<label class="desc">Enter Voucher Code</label>
									<input type="text" name="voucher_code" value="" id="voucher_code" style="width:50%;">
								</li>
								<li>
									<label class="desc">Date</label>
									<input type="text" name="date" value="" id="date" style="width:50%;">
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