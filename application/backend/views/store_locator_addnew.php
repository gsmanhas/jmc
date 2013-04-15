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
			location.href = '<?php echo base_url() ?>admin.php/store_locator';
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
					<h1><b>Create a new Store Locator</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/catalogs" class="btn ui-state-default ui-corner-all">
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/store_locator/save">
							<ul>
								<!-- <li>
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
								</li> -->
								<li>		
									<label class="desc">Store Name</label>
									<div>
									<input style="width:50%" type="text" name="name" value="<?php echo set_value('name'); ?>" maxlength="255" tabindex="1">
									</div>
								</li>
								<li>
									<label class="desc">Address</label>
									<div>
									<input style="width:50%" type="text" name="address" value="<?php echo set_value('address') ?>" maxlength="255" tabindex="2">
									</div>
								</li>
								<li>
									<label class="desc">Phone</label>
									<div>
									<input style="width:50%" type="text" name="phone" value="<?php echo set_value('phone') ?>" maxlength="255" tabindex="2">
									</div>
								</li>
								<li>
									<label class="desc">lat</label>
									<div>
									<input style="width:50%" type="text" name="lat" value="<?php echo set_value('lat') ?>" maxlength="255" tabindex="2">
									</div>
								</li>
								<li>
									<label class="desc">lng</label>
									<div>
									<input style="width:50%" type="text" name="lng" value="<?php echo set_value('lng') ?>" maxlength="255" tabindex="2">
									</div>
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