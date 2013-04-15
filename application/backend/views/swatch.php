<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>

<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function(){
		
		jQuery("#chkAll").click(function(){
			
			// console.log(jQuery(':checkbox'));
			
			if (jQuery(this).attr('checked') == true) {
				jQuery(':checkbox').each(function(index, value){
					jQuery(this).attr('checked', 'true');
				});
			} else {
				jQuery(':checkbox').each(function(index, value){
					jQuery(this).attr('checked', '');
				});
			}
			
		});
		
	});
	
	function trash (ndx) {
		if (confirm('Delete this Item ?')) {
			jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin.php/swatch');
			jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ndx);
			jQuery("#frmMain").submit();
		}
	}
	
	function publish (ndx, state) {
		jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin.php/swatch/publish');
		jQuery("#action").attr('value', 'publish');
		jQuery("#id").val(ndx);
		jQuery("#publish_state").val(state);
		jQuery("#frmMain").submit();
	}
	
	function update (ndx) {
		jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin.php/swatch');
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		jQuery("#frmMain").submit();
	}
	
	function removeAll () {
		var ids = [];
		jQuery(':checkbox').each(function(index, value){
			if (jQuery(this).attr('checked') == true) {
				if (jQuery(this).val() != 0)
					ids.push(jQuery(this).val());
			}
		});
		
		if (ids.length <= 0) {
			alert('Please select an item from the list to delete');
		} else {
			if (confirm('Delete This Item(s) ?')) {
				jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin.php/swatch');
				jQuery("#action").attr('value', 'remove');
				jQuery("#id").val(ids);
				jQuery("#frmMain").submit();	
			}
		}

	}
	
</script>
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
					<h1><b>Swatch</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/swatch/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Swatch
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
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">					
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/swatch">
					<table>
						<thead>
						<tr>
							<th width="1%">#</th>
							<th width="1%">
								<input type="checkbox" value="0" id="chkAll" name="chkAll" class="submit">
							</th>
							<th width="10%" style="text-align:center;">Images</th>
							<th>Title</th>
							<th width="100" style="text-align:center;">Options</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($this->swatches as $swatche) {
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<input type="checkbox" value="<?php echo $swatche->id ?>" name="list" class="checkbox">
							</td>
							<td style="text-align:center;">
									<img src="<?php echo $swatche->image?>" style="background-color: rgb(204, 204, 204);width:50px; heigh: 50px">
							</td>
							<td><?php echo $swatche->title?></td>
							<td>
								<?php if ($swatche->publish == 1) { ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $swatche->id ?>, 0)">
										<span class="ui-icon ui-icon-unlocked"></span>
									</a>
								<?php } else { ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $swatche->id ?>, 1)">
										<span class="ui-icon ui-icon-locked"></span>
									</a>
								<?php } ?>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:update(<?php echo $swatche->id ?>)">
									<span class="ui-icon ui-icon-wrench"></span>
								</a>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:trash(<?php echo $swatche->id ?>)">
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