<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<style type="text/css">

#kcfinder_div {
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

table#inner, table#inner tr td, table#inner thead th {
	border: 0;
}

table tr td#cke_contents_description {
	border-top: 1px solid #dedede;
	border-bottom: 1px solid #dedede;
}

</style>
 
<script type="text/javascript">

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
		if (jQuery("#symbolkey_image").val() == "Click here to browse the server") {
			jQuery("#symbolkey_image").val('');
		}
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
					<h1><b>Ingredients</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/ingredients" class="btn ui-state-default ui-corner-all">
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/ingredients/save">
															
								<table border="0" cellspacing="5" cellpadding="5" id="inner">
									<tr>
										<td><div class="fieldlabel" style="padding-bottom:3px;">Publish</div>
											<span>
												<input type="radio" name="publish" class="field checkbox" value="1" <?php echo set_radio('publish', '1', TRUE); ?> >
												<label class="choice">Yes</label>
											</span>	
											<span>
												<input type="radio" name="publish" class="field checkbox" value="0" <?php echo set_radio('publish', '0'); ?> >
												<label class="choice">No</label>
											</span>
										</td>
									</tr>
									<tr>
										<td><div class="fieldlabel" style="padding-bottom:3px;">Image</div>
											<input style="width:50%" id="symbolkey_image" name="image" type="text" readonly="readonly" value="<?php echo (set_value('image') == "") ? "Click here to browse the server" : set_value('image'); ?>" onclick="openKCFinder(this)" style="width:600px;cursor:pointer" /><br />
											<div id="kcfinder_div"></div>
										</td>
									</tr>
									<tr>
										<td><div class="fieldlabel" style="padding-bottom:3px;">Title</div>
											<div>
												<input style="width:50%" type="text" name="title" class="field text small" value="<?php echo set_value('title'); ?>" maxlength="255" tabindex="1">
											</div>
										</td>
									</tr>
									<tr>
										<td><div class="fieldlabel" style="padding-bottom:3px;">Description</div>
											<textarea class="ckeditor" name="description" cols="20" rows="20"><?php echo set_value('description'); ?></textarea>
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