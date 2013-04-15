<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" charset="utf-8">
	var base_url = '<?php echo base_url() ?>';	
	jQuery(document).ready(function(){
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});	
	});
	
	function openKCFinder(field) {
	    window.KCFinder = {
	        callBack: function(url) {
	            window.KCFinder = null;
				field.src = url;
	        }
	    };
	    window.open('<?php echo base_url() ?>kcfinder/browse.php?type=files&dir=files/public', 'kcfinder_textbox',
	        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
	        'resizable=1, scrollbars=0, width=800, height=600'
	    );
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
					<h1><b>Josies bio</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/webpage " class="btn ui-state-default ui-corner-all">
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/josies-bio/save">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td class="fieldlabel">Publish</td>
								<td colspan="3">
									<input type="radio" name="publish" class="field checkbox" value="1" <?php echo ($this->special_page[0]->publish == 1) ? "checked='checked'" : "" ?> >
									<label class="choice">Yes</label>
									<input type="radio" name="publish" class="field checkbox" value="0" <?php echo ($this->special_page[0]->publish == 0) ? "checked='checked'" : "" ?> >
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
								<th style="text-align:left;">Please select one or more featured reels below</th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select name="reel_id[]" multiple="true" size="20" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT id, sp_id, reel_id FROM special_page_with_bio WHERE sp_id = ?', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->josies_reel); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->reel_id == $this->josies_reel[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->josies_reel[$i]['id'], $Checked, $this->josies_reel[$i]['title']);
										}							
										?>
										</select>
									</td>
								</tr>							
							</tbody>
						</table>
						
						<table>
							<thead>
							<tr>
								<th style="text-align:left;">Please select one or more featured portfolios below</th>
							</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select name="pro_id[]" multiple="true" size="20" style="width:100%">
										<?php
										
										$query = $this->db->query('SELECT id, sp_id, pro_id FROM special_page_with_bio WHERE sp_id = ?', $this->special_page[0]->id);
										for ($i = 0; $i < count($this->josies_profolio); $i++) {
											$Checked = '';
											foreach ($query->result() as $value) {
												if ($value->pro_id == $this->josies_profolio[$i]['id']) {
													$Checked = 'selected=\'selected\'';
												}
											}
											printf('<option value="%s" %s>%s</option>', $this->josies_profolio[$i]['id'], $Checked, $this->josies_profolio[$i]['title']);
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