<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/price_list.js"></script>
<script type="text/javascript" charset="utf-8">
	function changeStatus (obj) {
		if (jQuery(obj).attr('checked')) {
			jQuery(obj).val(1);
		} else {
			jQuery(obj).val(0);
		}
	}
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
					<h1><b>Price List</b></h1>
					<div class="other">
						<div class="float-left">Here you can manage the product prices all at once!</div>
						<div class="button float-right">
							<a href="javascript:SaveAll()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-bookmark"></span>Save All
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/pricelist/submit">
					
					<table border="0" cellspacing="5" cellpadding="5">
					<thead>
						<tr>
							<th style="width:6%">On Sales</th>
							<th style="width:38%">Product Name</th>
							<th style="width:6%">Regular Price</th>
							<th style="width:6%">Discounted Price</th>
							
							<th style="width:6%">On Sales</th>
							<th style="width:38%">Product Name</th>
							<th style="width:6%">Regular Price</th>
							<th style="width:6%">Discounted Price</th>
							
						</tr>
					</thead>
					
					<?php
						for ($i = 0; $i < count($this->Products); $i++) {
							if ($i % 2 == 0) {
								echo "<tr>";
							}
					?>
						<td>
							<input type="checkbox" name="on_sale_<?php echo $this->Products[$i]['id'] ?>" 
								onclick="changeStatus(this)"
								value="<?php echo $this->Products[$i]['on_sale'] ?>" <?php echo ($this->Products[$i]['on_sale'] == 1) ? "checked='checked'" : "" ?>>
						</td>
						<td>
							<?php echo $this->Products[$i]['name'] ?>
							<input type="hidden" name="pid[]" value="<?php echo $this->Products[$i]['id'] ?>">
						</td>
						<td>
							<input type="text" name="retail_<?php echo $this->Products[$i]['id'] ?>" value="<?php echo $this->Products[$i]['retail_price'] ?>" id="" style="text-align:right" size="6">
						</td>
						<td>
							<input type="text" name="sale_<?php echo $this->Products[$i]['id'] ?>" value="<?php echo $this->Products[$i]['price'] ?>" id="" style="text-align:right" size="6">
						</td>
	
					<?php
							if (($i + 1) == count($this->Products)) {
								if ($i % 2 == 0) {
									echo "<td colspan=\"6\">&nbsp;</td>";
								}
							}
												
							if ($i % 2 == 1) {
								echo "</tr>";
							}
						}
					?>
					</table>
										
					<input type="hidden" name="action" value="" id="action">
					
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