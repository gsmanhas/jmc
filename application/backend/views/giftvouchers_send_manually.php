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
					<h1>Viewing: <b>Gift Voucher :: Send</b></h1>
					<div class="other">
						<div class="float-left">Send Gift Voucher to Customer</div>
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/giftvouchers/send">
                        <table border="0" cellspacing="5" cellpadding="5">
							<tr>
                                <?php if($this->input->post('id') == ''):?>
                                <td width="14%" class="fieldlabel">Gift Voucher Balance</td>
                                <td>
                                    <input id="voucher_balance" class="field text small" name="voucher_balance" type="text" style="width:90%;cursor:pointer" /><br />
                                    <input id="voucher_type" class="field text small" name="voucher_type" type="hidden" readonly="readonly" value="Manually Created" style="width:90%;cursor:pointer" /><br />
                                </td>
                                <?php else:?>
                                <td width="14%" class="fieldlabel">Gift Voucher Type</td>
                                <td>
                                    <input id="voucher_type" class="field text small" name="voucher_type" type="text" readonly="readonly" value="Manually Created" style="width:90%;cursor:pointer" /><br />
                                </td>
                                <?php endif;?>
                                <td width="14%" class="fieldlabel">From </td>
                                <td>
                                    <input id="from" class="field text small" name="from" type="text" style="width:90%;" /><br />
                                </td>
                            </tr>
							<tr>
                                <td class="fieldlabel">Recipient Name</td>
								<td><input type="text" name="to"  style="width:90%" class="field text small" value="<?php echo set_value('recipient_name') ?>" maxlength="255" tabindex="4"></td>
								<td class="fieldlabel">Recipient Email</td>
								<td><input type="text" name="recipient_email"  style="width:90%" class="field text small" value="<?php echo set_value('recipient_email') ?>" maxlength="255" tabindex="5"></td>
							</tr>
							<tr>
								<td class="fieldlabel">Message</td>
								<td colspan="3">
									<textarea name="message"  style="width:96.5%" rows="8" cols="40"><?php echo (isset($_POST['send_message'])) ? $_POST['send_message'] : ""; ?></textarea>
								</td>
							</tr>
							<tr>
								<td class="fieldlabel">Notes</td>
								<td colspan="3">
									<textarea name="note"  style="width:96.5%" rows="8" cols="40"><?php echo (isset($_POST['note'])) ? $_POST['note'] : ""; ?></textarea>
								</td>
							</tr>
						</table>

						<input type="hidden" name="action" value="addnew" id="action">
						<input type="hidden" name="id" value="<?php echo $this->input->post('id') ?>" id="id">
					
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