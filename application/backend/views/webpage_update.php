<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>

<script type="text/javascript" charset="utf-8">

	jQuery(document).ready(function(){
		
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});
		
		jQuery("#dialog_link").click(function(){
			location.href = jQuery(this).attr('href');
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
					<h1><b>Edit an existing Web Page</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/webpage" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick"></span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/webpage">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td width="12%" class="fieldlabel">Publish</td>
								<td colspan="3">
									<input type="radio" name="publish" class="field checkbox" value="1" <?php echo set_radio('publish', '1', TRUE); ?> >
									<label class="choice">Yes</label>
									<input type="radio" name="publish" class="field checkbox" value="0" <?php echo set_radio('publish', '0'); ?> >
									<label class="choice">No</label>
								</td>
							</tr>
							<tr>
								<td class="fieldlabel">Page Name</td>
								<td><input type="text" name="page_name" value="<?php echo ($this->webpage[0]->page_name) ? $this->webpage[0]->page_name : set_value('page_name'); ?>" id="page_name" style="width:100%"></td>
								<td width="12%" class="fieldlabel">Page URL</td>
								<td><input type="text" name="page_url" value="<?php echo ($this->webpage[0]->page_url) ? $this->webpage[0]->page_url : set_value('page_url'); ?>" id="page_url" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Page Title</td>
								<td><input type="text" name="page_title" value="<?php echo ($this->webpage[0]->page_title) ? $this->webpage[0]->page_title : set_value('page_title'); ?>" id="page_title" style="width:100%"></td>
								<td class="fieldlabel">Author</td>
								<td><input type="text" name="author" value="<?php echo ($this->webpage[0]->author) ? $this->webpage[0]->author : set_value('author'); ?>" id="author" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Meta Description</td>
								<td colspan="3">
									<input type="text" name="meta_description" value="<?php echo ($this->webpage[0]->meta_description) ? $this->webpage[0]->meta_description : set_value('meta_description'); ?>" id="meta_description" style="width:100%">
								</td>								
							</tr>
							<tr>
								<td class="fieldlabel">Meta Keywords</td>
								<td colspan="3">
									<input type="text" name="meta_keyword" value="<?php echo ($this->webpage[0]->meta_keyword) ? $this->webpage[0]->meta_keyword : set_value('meta_keyword'); ?>" id="meta_keyword" style="width:100%">
								</td>								
							</tr>
							<tr>
								<td class="fieldlabel">Robots</td>
								<td colspan="3">
									<input type="text" name="meta_robots" value="<?php echo ($this->webpage[0]->meta_robots) ? $this->webpage[0]->meta_robots : set_value('meta_robots'); ?>" id="meta_robots" style="width:100%">
								</td>								
							</tr>
							
							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>Content<br><br></div>
									<textarea class="ckeditor" cols="80" id="page_content" name="page_content" rows="20"><?php echo ($this->webpage[0]->page_content) ? $this->webpage[0]->page_content : set_value('page_content'); ?></textarea>
								</td>
							</tr>
						</table>
						
						<input type="hidden" name="action" value="update_save" id="action">
						<input type="hidden" name="id" value="<?php echo $this->webpage[0]->id ?>" id="id">
					
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
		<?php $this->load->view('base/footer') ?>
	</div>
</body>
</html>