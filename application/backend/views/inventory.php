<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/inventory.js"></script>
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
					<h1><b>Inventory</b></h1>
					<div class="other">
						<div class="float-left">Here you can manage the product inventory all at once!</div>
						<div class="button float-right">
							<a href="javascript:sortByName()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-bookmark"></span>Sort by Name
							</a>
							<a href="javascript:sortByInStock()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-bookmark"></span>Sort by In Stock
							</a>
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
					<form id="frmMain" name="frmMain" method="post" action="">
					
					<table border="0" cellspacing="5" cellpadding="5">
					<thead>
						<tr>
							<th width="10%">Can Pre-Order</th>
							<th>Product Name</th>
							<th colspan="2">In Stock</th>
							<th width="10%">Can Pre-Order</th>
							<th>Product Name</th>
							<th colspan="2">In Stock</th>
						</tr>
					</thead>
					<?php
						for ($i = 0; $i < count($this->Inventory); $i++) {
							
							if ($i % 2 == 0) {
								echo "<tr>";
							}
					?>
							<td style="width: 65px">
								<input type="checkbox" 
									name="can_pre_order_<?php echo $this->Inventory[$i]['id'] ?>" 
									value="<?php echo $this->Inventory[$i]['can_pre_order'] ?>" 
									id="can_pre_order_<?php echo $this->Inventory[$i]['id'] ?>"
									inventory_id="<?php echo $this->Inventory[$i]['id'] ?>"
									<?php echo ($this->Inventory[$i]['can_pre_order'] == 1) ? "checked=\"checked\"" : "";?>
									onclick="funChangeState(this)"
								>
							</td>
							<td style="width: 300px; "><?php echo $this->Inventory[$i]['product_name']; ?></td>
				    		<td style="width: 1%;">
								<input type="text" 
								name="qty_<?php echo $this->Inventory[$i]['id'] ?>" 
								value="<?php echo $this->Inventory[$i]['in_stock'] ?>" 
								id="qty_<?php echo $this->Inventory[$i]['id'] ?>" size="4" onfocus="this.select()">
							</td>
							<td style="width: 1%; ">
								<input type="hidden" name="inventory_<?php echo $this->Inventory[$i]['id'] ?>" value="<?php echo $this->Inventory[$i]['id'] ?>" id="inventory_<?php echo $this->Inventory[$i]['id'] ?>">
								<input class="btn ui-state-default ui-corner-all" type="submit" name="btnSave" value="Save" id="btnSave" onclick="saveItem('<?php echo $this->Inventory[$i]['id'] ?>')">
							</td>
					<?php
					
							if (($i + 1) == count($this->Inventory)) {
								if ($i % 2 == 0) {
									echo "<td colspan=\"4\">&nbsp;</td>";
								}
							}
												
							if ($i % 2 == 1) {
								echo "</tr>";
							}
													
						}
					
					?>
						<!--
							<tr>
								<td colspan="8">Total Product Count: <?php echo count($this->Inventory) ?></td>
							</tr>
						-->
					</table>
										
					<input type="hidden" name="action" value="" id="action">
					<input type="hidden" name="id" value="0" id="id">
					
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