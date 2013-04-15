<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/freeshipping.js"></script>
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
					<h1><b>Free Shipping</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/freeshipping/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Free Shipping Promotion
							</a>
							<a href="javascript:removeAll()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-trash"></span>Delete
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
							<th width="1%">#</th>
							<th width="1%">
								<input type="checkbox" value="0" id="chkAll" name="chkAll" class="submit">
							</th>
							<th>Apply greater than</th>
							<th>Shipping Method</th>
							<th style="text-align:center;">Release Date</th>
							<th style="text-align:center;">Release Time</th>
							<th style="text-align:center;">Expiry Date</th>
							<th style="text-align:center;">Expiry Time</th>
							<th style="width:70px;text-align:center;">Options</th>
						</tr>
						</thead>
						<tbody>
						<?php
							$i = 1;
							foreach ($this->freeshipping as $fs) {
							
							$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 and id = '".$fs->shipping_method."' ");
							$shipping_method = $Query->row();
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<input type="checkbox" value="<?php echo $fs->id ?>" name="list" class="checkbox">
							</td>
							<td>&#36;&nbsp;<?php echo number_format($fs->x_dollar_amount, 2) ?></td>
							<td><?php echo $shipping_method->name.' '.$shipping_method->price; ?></td>
							<td style="text-align:center;"><?php echo $fs->release_date ?></td>
							<td style="text-align:center;"><?php echo $fs->release_hour ?> : <?php echo $fs->release_mins ?> : <?php echo $fs->release_seconds ?></td>
							<td style="text-align:center;"><?php echo $fs->expiry_date ?></td>
							<td style="text-align:center;"><?php echo $fs->expiry_hour ?> : <?php echo $fs->expiry_mins ?> : <?php echo $fs->expiry_seconds ?></td>
							<td>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:update(<?php echo $fs->id ?>)">
									<span class="ui-icon ui-icon-wrench"></span>
								</a>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:trash(<?php echo $fs->id ?>)">
									<span class="ui-icon ui-icon-trash"></span>
								</a>
							</td>
						</tr>
						<?php
								$i++;
							}
						?>
						</tbody>
					</table>
					
					<input type="hidden" name="action" value="" id="action">
					<input type="hidden" name="id" value="0" id="id">
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