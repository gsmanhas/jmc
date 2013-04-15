<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/homebanners.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<style type="text/css">
.kcfinder_div {
    display: none;
    position: absolute;
    width: 670px;
    height: 400px;
    background: #e0dfde;
    border: 2px solid #3687e2;
    border-radius: 6px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    padding: 1px;
}
input {
	width: 100%;
}
</style>
<script type="text/javascript" charset="utf-8">
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
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Homepage Images</b></h1>
					<div class="other">
						<div class="float-left"><b>Homepage Image Size Specifications</b><br>Background Size: 1440 x 956px<br>Banner Size: 302 x 86px</div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/homepagebanner/submit">
						
					
					<table style="margin-bottom: 10px;">
						<thead>
							<tr>
								<th style="text-align: left;" colspan="4">
									Homepage SEO
								</th>
							</tr>
						</thead>
						<tr>
							<td colspan="2">
								<label class="desc">Meta Description</label>
								<textarea name="meta_description" rows="8" cols="40" style="width:100%"><?php echo $this->SEO[0]->description ?></textarea>
							</td>
							<td colspan="2">
								<label class="desc">Meta Keywords</label>
								<textarea name="meta_keywords" rows="8" cols="40" style="width:100%"><?php echo $this->SEO[0]->keywords ?></textarea>
							</td>
						</tr>
					</table>
					
					<table style="margin-bottom: 10px;">
							<thead>
								<tr>
									<th style="text-align: left;" colspan="3">
										Homepage Background
									</th>
								</tr>
							</thead>
							<tr>
								<td>
									<label class="desc">Background 1</label>
									<input id="banner1" name="banner1" type="text" readonly="readonly" value="<?php echo ($this->HomeBanner[0]->image == "") ? "Click here to browse" : $this->HomeBanner[0]->image; ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div01" class="kcfinder_div"></div>
									
									<label class="desc">Title</label>
									<input type="text" name="bg_1_title" value="<?php echo $this->HomeBanner[0]->title ?>" id="bg_1_title">
									
									<label class="desc">URL</label>
									<input type="text" name="banner_01_url" value="<?php echo ($this->HomeBanner[0]->url == "") ? "" : $this->HomeBanner[0]->url; ?>" id="banner_01_url">
									
									<label class="desc">Description</label>
									<textarea name="banner1_text" rows="8" cols="40" style="width:100%"><?php echo $this->HomeBanner[0]->text ?></textarea>
									
									<label class="desc">Status</label>
									<select style="width:100%" name="banner_01_status" id="banner_01_status">
										<option value="Y" <?php if($this->HomeBanner[0]->banner_status == 'Y') { ?> selected="selected" <?php } ?> >Active</option>
										<option value="N" <?php if($this->HomeBanner[0]->banner_status == 'N') { ?> selected="selected" <?php } ?> >Inactive</option>
									</select>
									
									
									
								</td>
								<td>
									<label class="desc">Background 2</label>
									<input id="banner2" name="banner2" type="text" readonly="readonly" value="<?php echo ($this->HomeBanner[1]->image == "") ? "Click here to browse" : $this->HomeBanner[1]->image; ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div02" class="kcfinder_div"></div>
									
									<label class="desc">Title</label>
									<input type="text" name="bg_2_title" value="<?php echo $this->HomeBanner[1]->title ?>" id="bg_2_title">
									
									<label class="desc">URL</label>
									<input type="text" name="banner_02_url" value="<?php echo ($this->HomeBanner[1]->url == "") ? "" : $this->HomeBanner[1]->url; ?>" id="banner_02_url">
									
									<label class="desc">Description</label>
									<textarea name="banner2_text" rows="8" cols="40" style="width:100%"><?php echo $this->HomeBanner[1]->text ?></textarea>
									
									<label class="desc">Status</label>
									<select style="width:100%" name="banner_02_status" id="banner_02_status">
										<option value="Y"  <?php if($this->HomeBanner[1]->banner_status == 'Y') { ?> selected="selected" <?php } ?>  >Active</option>
										<option value="N" <?php if($this->HomeBanner[1]->banner_status == 'N') { ?> selected="selected" <?php } ?> >Inactive</option>
									</select>
									
								</td>
								<td>
									<label class="desc">Background 3</label>
									<input id="banner3" name="banner3" type="text" readonly="readonly" value="<?php echo ($this->HomeBanner[2]->image == "") ? "Click here to browse" : $this->HomeBanner[2]->image; ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div03" class="kcfinder_div"></div>
									
									<label class="desc">Title</label>
									<input type="text" name="bg_3_title" value="<?php echo $this->HomeBanner[2]->title ?>" id="bg_3_title">
									
									<label class="desc">URL</label>
									<input type="text" name="banner_03_url" value="<?php echo ($this->HomeBanner[2]->url == "") ? "" : $this->HomeBanner[2]->url; ?>" id="banner_03_url">
									
									<label class="desc">Description</label>
									<textarea name="banner3_text" rows="8" cols="40" style="width:100%"><?php echo $this->HomeBanner[2]->text ?></textarea>
									
									<label class="desc">Status</label>
									<select style="width:100%" name="banner_03_status" id="banner_03_status">
										<option value="Y" <?php if($this->HomeBanner[2]->banner_status == 'Y') { ?> selected="selected" <?php } ?> >Active</option>
										<option value="N" <?php if($this->HomeBanner[2]->banner_status == 'N') { ?> selected="selected" <?php } ?> >Inactive</option>
									</select>
									
								</td>
							</tr>
							
				</table>
				<table>
							<thead>
								<tr>
									<th style="text-align: left;" colspan="3">
										Homepage Banners
									</th>
								</tr>
							</thead>
							<tr>
								<td>
									<label class="desc">Banner 1</label>

									<input id="side_left" name="side_left" type="text" readonly="readonly" value="<?php echo ($this->HomeBanner[5]->image == "") ? "Click here to browse" : $this->HomeBanner[5]->image; ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div06" class="kcfinder_div"></div>
									
									<label class="desc">Title</label>
									<input type="text" name="banner_1_title" value="<?php echo $this->HomeBanner[5]->title ?>" id="banner-1-title">
									
									<label class="desc">URL</label>
									<input type="text" name="banner_06_url" value="<?php echo ($this->HomeBanner[5]->url == "") ? "" : $this->HomeBanner[5]->url; ?>" id="banner_06_url">

									<label class="desc">Description</label>
									<textarea name="side_left_text" rows="8" cols="40" style="width:100%"><?php echo $this->HomeBanner[5]->text ?></textarea>
								</td>
								<td>
									<label class="desc">Banner 2</label>
									
									<input id="side_middle" name="side_middle" type="text" readonly="readonly" value="<?php echo ($this->HomeBanner[6]->image == "") ? "Click here to browse" : $this->HomeBanner[6]->image; ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div07" class="kcfinder_div"></div>
									
									<label class="desc">Title</label>
									<input type="text" name="banner_2_title" value="<?php echo $this->HomeBanner[6]->title ?>" id="banner-2-title">
									
									<label class="desc">URL</label>
									<input type="text" name="banner_07_url" value="<?php echo ($this->HomeBanner[6]->url == "") ? "" : $this->HomeBanner[6]->url; ?>" id="banner_07_url">
									
									<label class="desc">Description</label>
									<textarea name="side_middle_text" rows="8" cols="40" style="width:100%"><?php echo $this->HomeBanner[6]->text ?></textarea>
								</td>
								<td>
									<label class="desc">Banner 3</label>
									
									<input id="side_right" name="side_right" type="text" readonly="readonly" value="<?php echo ($this->HomeBanner[7]->image == "") ? "Click here to browse" : $this->HomeBanner[7]->image; ?>" onclick="openKCFinder(this)" style="width:100%;cursor:pointer" /><br />
									<div id="kcfinder_div08" class="kcfinder_div"></div>
									
									<label class="desc">Title</label>
									<input type="text" name="banner_3_title" value="<?php echo $this->HomeBanner[7]->title ?>" id="banner-3-title">
									
									<label class="desc">URL</label>
									<input type="text" name="banner_08_url" value="<?php echo ($this->HomeBanner[7]->url == "") ? "" : $this->HomeBanner[7]->url; ?>" id="banner_08_url">
								
									<label class="desc">Description</label>
									<textarea name="side_right_text" rows="8" cols="40" style="width:100%"><?php echo $this->HomeBanner[7]->text ?></textarea>
								</td>
							</tr>
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