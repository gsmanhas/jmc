<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('admin/head'); ?>
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
			jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin/members');
			jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ndx);
			jQuery("#frmMain").submit();
		}
	}
	
	function publish (ndx, state) {
		jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin/members/publish');
		jQuery("#action").attr('value', 'enabled');
		jQuery("#id").val(ndx);
		jQuery("#enabled").val(state);
		jQuery("#frmMain").submit();
	}
	
	function update (ndx) {
		jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin/members');
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
			alert('Please select an Members from the list to trash');
		} else {
			if (confirm('Delete This Item(s) ?')) {
				jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin/members');
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
			$this->load->view('admin/account');
			# 載入 Menu
			$this->load->view('admin/menu'); 
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
							<a href="<?php echo base_url() ?>admin/members/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Customer
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
					<form id="frmMain" name="frmMain" method="post" action="">
					<table id="sort-table">
						<thead>
						<tr>
							<th width="1%">#</th>
							<th width="8%">
								<input type="checkbox" value="0" id="chkAll" name="chkAll" class="submit">
							</th>
							<th>Username</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th style="width:100px;">Options</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($this->users as $user) {
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<input type="checkbox" value="<?php echo $user->id ?>" name="list" class="checkbox" style="margin-left:auto;margin-right:auto;">
							</td>
							<td><?php echo $user->username?></td>
							<td><?php echo $user->firstname?></td>
							<td><?php echo $user->lastname?></td>
							<td>
								<?php if ($user->block == 0) { ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $user->id ?>, 1)">
										<span class="ui-icon ui-icon-unlocked"></span>
									</a>
								<?php } else { ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $user->id ?>, 0)">
										<span class="ui-icon ui-icon-locked"></span>
									</a>
								<?php } ?>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:update(<?php echo $user->id ?>)">
									<span class="ui-icon ui-icon-wrench"></span>
								</a>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:trash(<?php echo $user->id ?>)">
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
					<input type="hidden" name="enabled" value="" id="enabled">
					
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