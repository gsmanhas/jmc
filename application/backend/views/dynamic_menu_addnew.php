<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});
	});
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
					<h1><b>Create a new Dynamic Menu</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="#" id="dialog_link" class="btn ui-state-default ui-corner-all" onclick="location.href='<?php echo base_url() ?>admin.php/dynamic_menu'">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">
				<table>
					<tr>
						<td>	
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/dynamic_menu/save">
								
								<ul>
									<li>
										<label class="desc">Publish</label>
										<div class="col">
											<span>
												<input type="radio" name="publish" class="field checkbox" value="1" <?php echo set_radio('publish', '1', TRUE); ?> >
												<label class="choice">Yes</label>
											</span>	
											<span>
												<input type="radio" name="publish" class="field checkbox" value="0" <?php echo set_radio('publish', '0'); ?> >
												<label class="choice">No</label>
											</span>		
										</div>
									</li>
									<li>
										<label class="desc">Parent</label>
										<select name="parent" id="parent" size="1">
											<option value="-1">TOP</option>
											<?php
												$this->db->select('*');
												$this->db->from('menus');
												$this->db->where('is_delete', 0);
												$this->db->where('parent', -1);
												foreach ($this->db->get()->result() as $result) {
													printf("<option value=\"%s\">%s</option>", $result->id, $result->title);
												}
											?>
										</select>
									</li>
									<li>
										<label class="desc">Title</label>
										<input type="text" name="title" value="<?php echo set_value('title'); ?>" id="title" style="width:50%">
									</li>
									<li>
										<label class="desc">URL</label>
										<input type="text" name="url" value="<?php echo set_value('url'); ?>" id="title" style="width:50%">
									</li>
		
								</ul>
								<input type="hidden" name="action" value="save" id="action">
								<input type="hidden" name="id" value="0" id="id">
							
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