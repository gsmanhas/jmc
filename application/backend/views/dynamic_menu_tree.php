<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('admin/head'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>css/jquery.treeview.css" type="text/css" media="screen" charset="utf-8">
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/jquery.treeview.js"></script>
<script type="text/javascript" charset="utf-8">

	jQuery(document).ready(function(){

		$("#nav").treeview();
		
		jQuery("#btn_submit").click(function(){
			jQuery("#action").attr('value', 'save_tree');
			jQuery("#frmMain").submit();
		});

		jQuery("#btn_delete").click(function(){
			jQuery("#action").attr('value', 'delete_tree');
			jQuery("#frmMain").submit();
		});
	
		jQuery("#btn_update").click(function(){
			jQuery("#action").attr('value', 'update_tree_save');
			jQuery("#frmMain").submit();
		})
	
		jQuery("#btn_cancel").click(function(){
			jQuery("#frmMain").submit();
		})
		
	});
	
	function edit_tree (ndx) {
		jQuery("#edit_id").val(ndx);
		jQuery("#action").attr('value', 'update_tree');
		jQuery("#frmMain").submit();
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
					<h1><b>Edit an existing Dynamic Menu</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin/dynamic_menu" id="btn_back" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Back to Dynamic Menus
							</a>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>
				
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

				<div class="hastable">
					<form id="frmMain" name="frmMain" method="post" action="">

						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td width="30%" style="vertical-align:top">
									ROOT
									<?php echo $this->nodes; ?>	
								</td>
								<td style="vertical-align:top">
									<div>
									<table>
										<tr>
											<td width="16%">Item Text</td>
											<td><input type="text" name="title" value="<?php echo (!empty($this->items[0]->title)) ? $this->items[0]->title : set_value('title', ''); ?>" id="title"></td>
										</tr>
										<tr>
											<td>Item URL</td>
											<td><input type="text" name="url" value="<?php echo (!empty($this->items[0]->url)) ? $this->items[0]->url : set_value('url', ''); ?>" id="url"></td>
										</tr>
										<tr>
											<td>Parent</td>
											<td>
												<select name="parent" id="parent" size="1">
													<option value="0">TOP</option>
													<?php echo $this->nodes2selectbox; ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>Enabled</td>
											<td><input type="checkbox" name="enabled" value="0" id="enabled"></td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>
												<?php if (empty($this->items[0]->id)) { ?>
												<a class="btn ui-state-default ui-corner-all" id="btn_submit" href="#">
													<span class="ui-icon ui-icon-disk"></span>Save Item
												</a>
												<?php } else { ?>
												<a class="btn ui-state-default ui-corner-all" id="btn_update" href="#">
													<span class="ui-icon ui-icon-disk"></span>update
												</a>
												<?php } ?>
											
												<a class="btn ui-state-default ui-corner-all" id="btn_delete" href="#">
													<span class="ui-icon ui-icon-disk"></span>Delete Item
												</a>
											
												<a class="btn ui-state-default ui-corner-all" id="btn_cancel" href="#">
													<span class="ui-icon ui-icon-disk"></span>Cancel
												</a>
											</td>
										</tr>
									</table>
									</div>
								</td>
							</tr>
						</table>
		
						<input type="hidden" name="action" value="" id="action">
						<input type="hidden" name="id" value="<?php echo $this->id ?>" id="id">
						<input type="hidden" name="edit_id" value="<?php echo (!empty($this->items[0]->id)) ? $this->items[0]->id : 0 ?>" id="edit_id">
					
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