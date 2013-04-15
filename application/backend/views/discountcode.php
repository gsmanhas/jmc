<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/discountcode.js"></script>
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
					<h1><b>Discount Codes</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/discountcode/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Discount Code
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
							<th>Name</th>
							<th>Code</th>
							<th width="15%">Type</th>
							<th width="15%" style="text-align:center;">Percentage or Dollar Amount</th>
							<th width="15%" style="text-align:center;">Expire after X redemptions</th>
							<th width="100" style="text-align:center;">Options</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($this->discountcodes as $discountcode) {
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<input type="checkbox" value="<?php echo $discountcode->id ?>" name="list" class="checkbox">
								<input type="hidden" value="<?php echo $discountcode->id ?>" name="lists[]" class="checkbox">
							</td>
							<td>
								<?php echo $discountcode->description ?>
							</td>
							<td>
								<?php echo $discountcode->code ?>
							</td>
							<td>
								<?php 
									switch($discountcode->discount_type) {
										case "1" : 
											echo "Percentage of Order";
										break;
										case "2" : 
											echo "Fixed Dollar Amount";
										break;
										case "3" : 
											echo "Free Shipping";
                                        break;
                                        case "4":
                                            echo "Buy One Get One Free";
                                        break;
                                        case "5":
                                            echo "Free Gift With Purchase";
                                        break;
									}
								?>
							</td>
							<td style="text-align:center;">
								<?php echo $discountcode->discount_percentage ?>
							</td>
							<td style="text-align:center;">
								<?php echo $discountcode->xuses ?>
							</td>
							<td>
								<?php if ($discountcode->enabled == 1) { ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $discountcode->id.', 0' ?>)">
										<span class="ui-icon ui-icon-unlocked"></span>
									</a>
								<?php } else { ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $discountcode->id.', 1' ?>)">
										<span class="ui-icon ui-icon-locked"></span>
									</a>
								<?php } ?>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:update(<?php echo $discountcode->id ?>)">
									<span class="ui-icon ui-icon-wrench"></span>
								</a>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:trash(<?php echo $discountcode->id ?>)">
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