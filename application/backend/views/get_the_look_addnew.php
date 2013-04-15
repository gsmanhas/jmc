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
			
			if (jQuery("#download_file").val() == "Click here to browse") {
				jQuery("#download_file").val('');
			}
			
			if (jQuery("#face_image").val() == "Click here to browse") {
				jQuery("#face_image").val('');
			}
			
			if (jQuery("#eyes_image").val() == "Click here to browse") {
				jQuery("#eyes_image").val('');
			}
			
			if (jQuery("#lips_image").val() == "Click here to browse") {
				jQuery("#lips_image").val('');
			}
			
			if (jQuery("#hair_image").val() == "Click here to browse") {
				jQuery("#hair_image").val('');
			}
			
			if (jQuery("#skin_image").val() == "Click here to browse") {
				jQuery("#skin_image").val('');
			}
			
			jQuery("#frmMain").submit();
		});	
	});
	
	function openKCFinder(field) {
	    window.KCFinder = {
	        callBack: function(url) {
	            window.KCFinder = null;
				field.value = url;
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
					<h1><b>Create a new "get the look"</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/get_the_look" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick">&nbsp;</span>Cancel
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/get_the_look/save">
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
									<label class="desc">Title</label>
									<div>
									<input style="width:50%" type="text" name="title" value="<?php echo set_value('title'); ?>" maxlength="255" tabindex="1">
									</div>
								</li>
								<li>
									<label class="desc">PDF file of the Look</label>
									<div>
									<input style="width:50%" id="download_file" name="download_file" type="text" readonly="readonly" value="<?php echo (set_value('download_file') == "") ? "Click here to browse" : set_value('download_file'); ?>" onclick="openKCFinder(this)" style="width:50%;cursor:pointer" />
									</div>
								</li>
								<li>
									<label class="desc">The image of the Look</label>
									<div>
									<input style="width:50%" id="the_look" name="the_look" type="text" readonly="readonly" value="<?php echo (set_value('the_look') == "") ? "Click here to browse" : set_value('the_look'); ?>" onclick="openKCFinder(this)" style="width:50%;cursor:pointer" />
									</div>
								</li>
							</ul>

							<br>

							<table border="0" cellspacing="5" cellpadding="5">
								<thead>
									<tr>
										<td class="fieldlabel" style="text-align:left;" width="50%">Face</td>
										<td>
											<span style="font-weight:bold;">The Face Image</span>
											<input id="face_image" name="face_image" type="text" readonly="readonly" value="<?php echo (set_value('face_image') == "") ? "Click here to browse" : set_value('face_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" />
										</td>
									</tr>
								</thead>
								<tr>
									<td>
										<span style="font-weight:bold;">Description</span><br><br>
										<textarea class="ckeditor" cols="20" id="face" name="face" rows="20"><?php echo set_value('face') ?></textarea>
									</td>
									<td style="vertical-align:top">
										<span style="font-weight:bold;">Featured Products</span><br><br>
										<select name="face_group_by[]" id="face_group_by" size="35" multiple="true" style="width:100%">
										<?php
											$query = $this->db->query(
												"SELECT id, name FROM product WHERE id in(" .
												"SELECT DISTINCT pid FROM product_rel_catalog " .
												"WHERE cid = (" .
												"SELECT DISTINCT id FROM product_catalogs WHERE name = 'face'" .
												") ORDER BY pid asc) ORDER BY name asc" 
											);
											foreach ($query->result() as $item) {
												$is_selected = '';
												foreach ($_POST['face_group_by'] as $selected) {
													if ($selected == $item->id) {
														$is_selected = 'selected=\"selected\"';
													}
												}
												printf("<option value='%s' %s>%s</option>", $item->id, $is_selected, $item->name);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
							<table>
								<thead>
									<tr>
										<td class="fieldlabel" style="text-align:left;" width="50%">Eyes</td>
										<td>
											<span style="font-weight:bold;">The Eyes Image</span>
											<input id="eyes_image" name="eyes_image" type="text" readonly="readonly" value="<?php echo (set_value('eyes_image') == "") ? "Click here to browse" : set_value('eyes_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" />
										</td>
									</tr>
								</thead>
								<tr>
									<td>
										<span style="font-weight:bold;">Description</span><br><br>
										<textarea class="ckeditor" cols="20" id="eyes" name="eyes" rows="20"><?php echo set_value('eyes') ?></textarea>
									</td>
									<td style="vertical-align:top">
										<span style="font-weight:bold;">Featured Products</span><br><br>
										<select name="eyes_group_by[]" id="eyes_group_by" size="35" multiple="true" style="width:100%">
										<?php
											$query = $this->db->query(
												"SELECT id, name FROM product WHERE id in(" .
												"SELECT DISTINCT pid FROM product_rel_catalog " .
												"WHERE cid = (" .
												"SELECT DISTINCT id FROM product_catalogs WHERE name = 'eyes'" .
												") ORDER BY pid asc) ORDER BY name asc" 
											);
											foreach ($query->result() as $item) {
												$is_selected = '';
												foreach ($_POST['eyes_group_by'] as $selected) {
													if ($selected == $item->id) {
														$is_selected = 'selected=\"selected\"';
													}
												}
												printf("<option value='%s' %s>%s</option>", $item->id, $is_selected, $item->name);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
							<table>	
								<thead>
									<tr>
										<td class="fieldlabel" style="text-align:left;" width="50%">Lips</td>
										<td>
											<span style="font-weight:bold;">The Lips Image</span>
											<input id="lips_image" name="lips_image" type="text" readonly="readonly" value="<?php echo (set_value('lips_image') == "") ? "Click here to browse" : set_value('lips_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" />
										</td>
									</tr>
								</thead>
								<tr>
									<td>
										<span style="font-weight:bold;">Description</span><br><br>
										<textarea class="ckeditor" cols="20" id="lips" name="lips" rows="20"><?php echo set_value('lips') ?></textarea>
									</td>
									<td style="vertical-align:top">
										<span style="font-weight:bold;">Featured Products</span><br><br>
										<select name="lips_group_by[]" id="lips_group_by" size="35" multiple="true" style="width:100%">
										<?php
											$query = $this->db->query(
												"SELECT id, name FROM product WHERE id in(" .
												"SELECT DISTINCT pid FROM product_rel_catalog " .
												"WHERE cid = (" .
												"SELECT DISTINCT id FROM product_catalogs WHERE name = 'lips'" .
												") ORDER BY pid asc) ORDER BY name asc" 
											);
											foreach ($query->result() as $item) {
												$is_selected = '';
												foreach ($_POST['lips_group_by'] as $selected) {
													if ($selected == $item->id) {
														$is_selected = 'selected=\"selected\"';
													}
												}
												printf("<option value='%s' %s>%s</option>", $item->id, $is_selected, $item->name);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
							<table>
								<thead>
									<tr>
										<td class="fieldlabel" style="text-align:left;" width="50%">Hair</td>
										<td>
											<span style="font-weight:bold;">The Hair Image</span>
											<input id="hair_image" name="hair_image" type="text" readonly="readonly" value="<?php echo (set_value('hair_image') == "") ? "Click here to browse" : set_value('hair_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" />
										</td>
									</tr>
								</thead>
								<tr>
									<td>
										<span style="font-weight:bold;">Description</span><br><br>
										<textarea class="ckeditor" cols="20" id="hair" name="hair" rows="20"><?php echo set_value('hair') ?></textarea>
									</td>
									<td style="vertical-align:top">
										<span style="font-weight:bold;">Featured Products</span><br><br>
										<select name="hair_group_by[]" id="hair_group_by" size="35" multiple="true" style="width:100%">
										<?php
											$query = $this->db->query(
												"SELECT id, name FROM product WHERE id in(" .
												"SELECT DISTINCT pid FROM product_rel_catalog " .
												"WHERE cid = (" .
												"SELECT DISTINCT id FROM product_catalogs WHERE name = 'hair'" .
												") ORDER BY pid asc) ORDER BY name asc" 
											);
											foreach ($query->result() as $item) {
												$is_selected = '';
												foreach ($_POST['hair_group_by'] as $selected) {
													if ($selected == $item->id) {
														$is_selected = 'selected=\"selected\"';
													}
												}
												printf("<option value='%s' %s>%s</option>", $item->id, $is_selected, $item->name);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
							<table>
								<thead>
									<tr>
										<td class="fieldlabel" style="text-align:left;" width="50%">Skin</td>
										<td>
											<span style="font-weight:bold;">The Skin Image</span>
											<input id="skin_image" name="skin_image" type="text" readonly="readonly" value="<?php echo (set_value('skin_image') == "") ? "Click here to browse" : set_value('skin_image'); ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" />
										</td>
									</tr>
								</thead>
								<tr>
									<td>
										<span style="font-weight:bold;">Description</span><br><br>
										<textarea class="ckeditor" cols="20" id="skin" name="skin" rows="20"><?php echo set_value('skin') ?></textarea>
									</td>
									<td style="vertical-align:top">
										<span style="font-weight:bold;">Featured Products</span><br><br>
										<select name="skin_group_by[]" id="skin_group_by" size="35" multiple="true" style="width:100%">
										<?php
											$query = $this->db->query(
												"SELECT id, name FROM product WHERE id in(" .
												"SELECT DISTINCT pid FROM product_rel_catalog " .
												"WHERE cid = (" .
												"SELECT DISTINCT id FROM product_catalogs WHERE name = 'skin'" .
												") ORDER BY pid asc) ORDER BY name asc" 
											);
											foreach ($query->result() as $item) {
												$is_selected = '';
												foreach ($_POST['skin_group_by'] as $selected) {
													if ($selected == $item->id) {
														$is_selected = 'selected=\"selected\"';
													}
												}
												printf("<option value='%s' %s>%s</option>", $item->id, $is_selected, $item->name);
											}
										?>
										</select>
									</td>
								</tr>	
							</table>
								
								<input type="hidden" name="action" value="addnew" id="action">
								<input type="hidden" name="id" value="-1" id="id">
	
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