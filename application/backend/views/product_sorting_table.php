<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/product_sorting.js"></script>
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
					<h1><b>Product Sorting</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/product_sorting" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Back to Product Sorting
							</a>
							<a href="javascript:orderSave()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-bookmark"></span>Save Order
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
					<table>
						<thead>
						<tr>
							<td style="text-align:left" colspan="5">Catalog Name : <?php echo $this->catalogs[0]->name ?></td>
						</tr>
						</thead>
						<tr style="background-color:#f8f8f8;">
							<th style="font-weight:bold;color:#888" width="1%">#</th>
							<th style="font-weight:bold;color:#888">Name</th>
							<th style="font-weight:bold;color:#888" width="1%">Ordering</th>
							<th style="font-weight:bold;color:#888;text-align:center;" width="100">Show/Hide</th>
						</tr>
						<tbody>
						<?php
						$i = 1;
						foreach ($this->products as $product) {
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<?php echo $product->name ?>
								<input type="hidden" value="<?php echo $product->id ?>" name="lists[]" class="checkbox">
							</td>
							<td>
								<input style="width:100%;" type="text" name="order[]" value="<?php echo $product->sorting ?>" size="6" onfocus="this.select()">
							</td>
							<td style="text-align:center">
								<input type="checkbox" name="hide_or_show_<?php echo $product->id ?>" <?php echo ($product->show_it == 1) ? "checked='checked'" : "" ?> value="<?php echo ($product->show_it == 1) ? "1" : "0" ?>" onclick="funChangeState(this)">
							</td>
						</tr>
						<?php
							$i++;
						}
						?>
						</tbody>
					</table>
					
					<input type="hidden" name="action" value="" id="action">
					<input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" id="id">
					<input type="hidden" name="pid" value="" id="id">
					<input type="hidden" name="publish_state" value="" id="publish_state">
					
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