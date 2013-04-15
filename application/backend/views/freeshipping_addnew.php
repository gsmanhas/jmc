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
		
		jQuery("#dialog_link").click(function(){
			location.href = '<?php echo base_url() ?>admin.php/freeshipping';
		});
		
		jQuery("#release_date").datepicker({
			showButtonPanel: true,
			dateFormat: "yy-mm-dd"
		});
		
		jQuery("#expiry_date").datepicker({
			showButtonPanel: true,
			dateFormat: "yy-mm-dd"
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
					<h1><b>Create a new Free Shipping Promotion</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/freeshipping" class="btn ui-state-default ui-corner-all">
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/freeshipping/save">
							
							<ul>
								<li>
									<label class="desc">Apply greater than X dollars</label>
									<input type="text" name="x_dollar_amount" value="" id="x_dollar_amount" style="width:50%;">
								</li>
								<li>
									<label class="desc">Shipping Method</label>
									<select name="shipping_method" id="shipping_method" onchange="" size="1">
									<?php
										$is_selected = '';	
										foreach ($this->ListShippingMethod as $method) {	
											
											 printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",
                                  			  $method->id, $is_selected, $method->name, $method->price
				                                );
										}
									?>									
									</select>
								</li>
								<li>
									<label class="desc">Release Date</label>
									<input type="text" id="release_date" name="release_date" class="field text small" value="<?php echo set_value('release_date') ?>" maxlength="255" tabindex="2" style="width:50%;">
								</li>
								<li>
									<label class="desc">Release Time</label>
									<select name="release_hour" id="release_hour" onchange="" size="1">
									<?php
										for ($i = 0; $i < 24; $i++) {
											if (isset($_POST['release_hour']) && $_POST['release_hour'] == $i) {
												$selected = "selected=\"selected\"";
											} else {
												$selected = "";
											}
											printf("<option value=\"%s\" %s>%s</option>", $i, $selected, $i);
										}
									?>									
									</select>
									&nbsp;:&nbsp;
									<select name="release_mins" id="release_mins" onchange="" size="1">
										<?php
											for ($i = 0; $i < 60; $i++) {
												if (isset($_POST['release_mins']) && $_POST['release_mins'] == $i) {
													$selected = "selected=\"selected\"";
												} else {
													$selected = "";
												}
												printf("<option value=\"%s\" %s>%s</option>", $i, $selected, $i);
											}
										?>									
									</select>
									&nbsp;:&nbsp;
									<select name="release_seconds" id="release_seconds" onchange="" size="1">
										<?php
											for ($i = 0; $i < 60; $i++) {
												if (isset($_POST['release_seconds']) && $_POST['release_seconds'] == $i) {
													$selected = "selected=\"selected\"";
												} else {
													$selected = "";
												}
												printf("<option value=\"%s\" %s>%s</option>", $i, $selected, $i);
											}
										?>									
									</select>
								</li>
								<li>
									<label class="desc">Expiry Date</label>
									<input type="text" id="expiry_date" name="expiry_date" class="field text small" value="<?php echo set_value('expiry_date') ?>" maxlength="255" tabindex="2"  style="width:50%;">
								</li>
								<li>
									<label class="desc">Expiry Time</label>
									<select name="expiry_hour" id="expiry_hour" onchange="" size="1">
										<?php
											for ($i = 0; $i < 24; $i++) {
												if (isset($_POST['expiry_hour']) && $_POST['expiry_hour'] == $i) {
													$selected = "selected=\"selected\"";
												} else {
													$selected = "";
												}
												printf("<option value=\"%s\" %s>%s</option>", $i, $selected, $i);
											}
										?>									
									</select>
									&nbsp;:&nbsp;
									<select name="expiry_mins" id="expiry_mins" onchange="" size="1">
										<?php
											for ($i = 0; $i < 60; $i++) {
												if (isset($_POST['expiry_mins']) && $_POST['expiry_mins'] == $i) {
													$selected = "selected=\"selected\"";
												} else {
													$selected = "";
												}
												printf("<option value=\"%s\" %s>%s</option>", $i, $selected, $i);
											}
										?>									
									</select>
									&nbsp;:&nbsp;
									<select name="expiry_seconds" id="expiry_seconds" onchange="" size="1">
										<?php
											for ($i = 0; $i < 60; $i++) {
												if (isset($_POST['expiry_seconds']) && $_POST['expiry_seconds'] == $i) {
													$selected = "selected=\"selected\"";
												} else {
													$selected = "";
												}
												printf("<option value=\"%s\" %s>%s</option>", $i, $selected, $i);
											}
										?>									
									</select>
								</li>
							</ul>
		
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