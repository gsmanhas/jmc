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
					<h1><b>Giving Back</b></h1>
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

				<?php
				if (isset($this->message) && !empty($this->message)) {
				?>
				<div class="response-msg success ui-corner-all">
					<span>Success message</span><?php echo $this->message; ?>
				</div>
				<?php
				}
				?>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/giving-back/save">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td class="fieldlabel">Publish</td>
								<td colspan="3">
									<input type="radio" name="publish" class="field checkbox" value="1" <?php echo set_radio('publish', '1', TRUE); ?> >
									<label class="choice">Yes</label>
									<input type="radio" name="publish" class="field checkbox" value="0" <?php echo set_radio('publish', '0'); ?> >
									<label class="choice">No</label>
								</td>
							</tr>
							<tr>
								<td width="12%" class="fieldlabel">Page Name</td>
								<td><input type="text" name="page_name" value="<?php echo $this->special_page[0]->page_name ?>" id="page_name" style="width:100%"></td>
								<td width="12%" class="fieldlabel">Page URL</td>
								<td><input type="text" name="page_url" value="<?php echo $this->special_page[0]->page_url ?>" id="page_url" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Page Title</td>
								<td><input type="text" name="page_title" value="<?php echo $this->special_page[0]->page_title ?>" id="page_title" style="width:100%"></td>
								<td class="fieldlabel">Author</td>
								<td><input type="text" name="author" value="<?php echo $this->special_page[0]->author ?>" id="author" style="width:100%"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Meta Description</td>
								<td colspan="3">
									<input type="text" name="meta_description" value="<?php echo $this->special_page[0]->meta_description ?>" id="meta_description" style="width:100%">
								</td>								
							</tr>
							<tr>
								<td class="fieldlabel">Meta Keywords</td>
								<td colspan="3">
									<input type="text" name="meta_keyword" value="<?php echo $this->special_page[0]->meta_keyword ?>" id="meta_keyword" style="width:100%">
								</td>								
							</tr>
							<tr>
								<td class="fieldlabel">Robots</td>
								<td colspan="3">
									<input type="text" name="meta_robots" value="<?php echo $this->special_page[0]->meta_robots ?>" id="meta_robots" style="width:100%">
								</td>								
							</tr>
							
							<tr>
								<td colspan="4">
								<div class="fieldlabel"><br>Content<br><br></div>
									<textarea class="ckeditor" cols="80" id="page_content" name="page_content" rows="20"><?php echo $this->special_page[0]->page_content ?></textarea>
								</td>
							</tr>
						</table>

						<table>
							<thead>
							<tr>
								<th style="text-align:left;">Please select featured products below</th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select name="pid1[]" size="1" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT * FROM special_page_with_product WHERE sp_id = ? AND ordering = 1', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->Products); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pid == $this->Products[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->Products[$i]['id'], $Checked, $this->Products[$i]['name']);
										}							
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="pid2[]" size="1" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT * FROM special_page_with_product WHERE sp_id = ? AND ordering = 2', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->Products); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pid == $this->Products[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->Products[$i]['id'], $Checked, $this->Products[$i]['name']);
										}							
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="pid3[]" size="1" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT * FROM special_page_with_product WHERE sp_id = ? AND ordering = 3', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->Products); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pid == $this->Products[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->Products[$i]['id'], $Checked, $this->Products[$i]['name']);
										}							
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="pid4[]" size="1" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT * FROM special_page_with_product WHERE sp_id = ? AND ordering = 4', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->Products); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pid == $this->Products[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->Products[$i]['id'], $Checked, $this->Products[$i]['name']);
										}							
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="pid5[]" size="1" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT * FROM special_page_with_product WHERE sp_id = ? AND ordering = 5', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->Products); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pid == $this->Products[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->Products[$i]['id'], $Checked, $this->Products[$i]['name']);
										}							
										?>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										<select name="pid6[]" size="1" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT * FROM special_page_with_product WHERE sp_id = ? AND ordering = 6', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->Products); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pid == $this->Products[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->Products[$i]['id'], $Checked, $this->Products[$i]['name']);
										}							
										?>
										</select>
									</td>
								</tr>
							</tbody>
						</table>

						<input type="hidden" name="action" value="update" id="action">
						<input type="hidden" name="id" value="<?php echo $this->special_page[0]->id ?>" id="id">
						
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