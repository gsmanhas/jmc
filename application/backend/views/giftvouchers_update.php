<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
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

    </style>

    <script type="text/javascript" charset="utf-8">

        function openKCFinder(field) {
            var div = $(field).next().next().get(0);
            if (div.style.display == "block") {
                div.style.display = 'none';
                div.innerHTML = '';
                return;
            }
            window.KCFinder = {
                callBack: function(url) {
                    window.KCFinder = null;
                    field.value = url;
                    div.style.display = 'none';
                    div.innerHTML = '';
                }
            };
            div.innerHTML = '<iframe name="kcfinder_iframe" src="<?php echo base_url() ?>kcfinder/browse.php?type=files&dir=files/public" ' +
                'frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" />';
            div.style.display = 'block';
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
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1>Viewing: <b>Gift Voucher :: Add New</b></h1>
					<div class="other">
						<div class="float-left">AddNew Gift Voucher</div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/giftvouchers" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick">&nbsp;</span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable" style="min-height: 550px;">
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/giftvouchers">
                        <table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td width="14%" class="fieldlabel">Enabled</td>
								<td>
                                    <input type="radio" name="enabled" class="field checkbox" value="1" <?php echo ($this->gift[0]->enabled == 1) ? 'checked="checked"' : "" ?> >
									<label class="choice">Yes</label>
                                    <input type="radio" name="enabled" class="field checkbox" value="0" <?php echo ($this->gift[0]->enabled == 0) ? 'checked="checked"' : "" ?> >
									<label class="choice">No</label>
								</td>
                                <td width="13%" class="fieldlabel">Gift Voucher Balance</td>
								<td><input type="text" name="gift_voucher_balance" class="field text small" value="<?php echo $this->gift[0]->gift_voucher_balance ?>" maxlength="255" tabindex="1" style="width: 90%;"></td>
							</tr>
							<tr>
                                <td width="14%" class="fieldlabel">Original Gift Voucher Value</td>
								<td><input type="text" name="gift_voucher_value" class="field text small" value="<?php echo $this->gift[0]->gift_voucher_value ?>" maxlength="255" tabindex="2" style="width: 90%;"></td>
                                <td class="fieldlabel">Gift Voucher Type</td>
								<td><input type="text" readonly="readonly" name="gift_voucher_type" class="field text small" value="<?php echo $this->gift[0]->gift_voucher_type ?>" maxlength="255" tabindex="3" style="width: 90%;"></td>
							</tr>
                            <tr>
                                <td  width="14%" class="fieldlabel">Gift Voucher Small Image</td>
                                <td>
                                    <input id="small_image" class="field text small" name="small_image" type="text" readonly="readonly" value="<?php echo ($this->gift[0]->gift_voucher_image_small == "") ? "Click here to browse" : $this->gift[0]->gift_voucher_image_small; ?>" onclick="openKCFinder(this)" style="width:90%;cursor:pointer" /><br />
                                    <div class="kcfinder_div"></div>
                                </td>
                                <td  width="14%" class="fieldlabel">Gift Voucher Big Image</td>
                                <td>
                                    <input id="big_image" class="field text small" name="big_image" type="text" readonly="readonly" value="<?php echo ($this->gift[0]->gift_voucher_image_big == "") ? "Click here to browse" : $this->gift[0]->gift_voucher_image_big; ?>" onclick="openKCFinder(this)" style="width:90%;cursor:pointer" /><br />
                                    <div class="kcfinder_div"></div>
                                </td>
                            </tr>
							<!--<tr>
                                <td class="fieldlabel">Recipient Name</td>
								<td><input type="text" name="recipient_name" class="field text small" value="<?php /*echo $this->gift[0]->recipient_name */?>" maxlength="255" tabindex="4" style="width: 90%;"></td>
								<td class="fieldlabel">Recipient Email</td>
								<td><input type="text" name="recipient_email" class="field text small" value="<?php /*echo $this->gift[0]->recipient_email */?>" maxlength="255" tabindex="5" style="width: 90%;"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Message</td>
								<td colspan="3">
									<textarea name="send_message" rows="8" cols="40" style="width: 95.5%;"><?php /*echo $this->gift[0]->send_message */?></textarea>
								</td>
							</tr>-->
						</table>
					
						<input type="hidden" name="action" value="update_save" id="action">
						<input type="hidden" name="id" value="<?php echo $this->gift[0]->id ?>" id="id">
					
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