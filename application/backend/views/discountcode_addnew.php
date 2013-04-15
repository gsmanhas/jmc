<?php 

// $date = new DateTime();
// echo $date->format('U = Y-m-d H:i:s') . br(1);
// 
// $date->setTimestamp(1171502725);
// echo $date->format('U = Y-m-d H:i:s') . br(1);

$option_index = 0;
$codetype_1 = '';
$codetype_2 = '';
$codetype_3 = '';
$codetype_4 = '';
$codetype_5 = '';

if (isset($_POST['discountcodetype'])) {
	switch ($_POST['discountcodetype']) {
		case "2" :
			$codetype_2 = "checked=\"checked\"";
			$option_index = 2;
		break;
		case "3" :
			$codetype_3 = "checked=\"checked\"";
			$option_index = 3;
		break;
		case "4" :
			$codetype_4 = "checked=\"checked\"";
			$option_index = 4;
		break;
		case "5" :
			$codetype_5 = "checked=\"checked\"";
			$option_index = 5;
		break;
		case "1" :
			$codetype_1 = "checked=\"checked\"";
			$option_index = 1;
		break;
	}
} else {
	$codetype_1 = "checked=\"checked\"";
	$option_index = 1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function(){
		
		jQuery("#release_date").datepicker({
			showButtonPanel: true,
			dateFormat: "yy-mm-dd"
		});
		
		jQuery("#expiry_date").datepicker({
			showButtonPanel: true,
			dateFormat: "yy-mm-dd"
		});
		
		jQuery("#btn_submit").click(function(){			
			// jQuery("#frmMain").submit();
			
			// var Cids = new Array();
			var Pids = new Array();
			// var Pid_with_Cids = new Array();
			
			if (jQuery("#enabled_product_list").attr("checked") == true) {
				
				jQuery("#product_list option:selected").each(function(){
					// console.log(jQuery(this).val());
					Pids.push(jQuery(this).val());
				});
				
				// jQuery("#product_list option:selected").each(function(){					
				// 	var str = jQuery(this).val();
				// 	if (str.indexOf("cid_") == -1) {
				// 		Pids.push(str);
				// 		Pid_with_Cids.push(jQuery(this).attr('cid'));
				// 	} else {
				// 		Cids.push(str.replace('cid_', ''))
				// 	}
				// 	
				// });
				
				// if (Cids.length == 0 && Pids.length == 0 && Pid_with_Cids == 0) {
				// 	alert('Please Select an Product(s) or Catalog(s)');
				// 	return false;
				// }
				
				if (Pids.length <= 0) {
					alert('Please Select an Product(s)');
					return false;
				}
				
				// jQuery("#hid_pid_with_cid").val(Pid_with_Cids);
				// jQuery("#hid_cids").val(Cids);
				jQuery("#hid_pids").val(Pids);
				
			}
			
			jQuery("#frmMain").submit();
		});
		
		jQuery("#dialog_link").click(function(){
			location.href = '<?php echo base_url() ?>admin/catalogs';
		});
		
		changeOption('<?php echo $option_index; ?>');
		
		jQuery("#enabled_product_list").click(function(){
			if (jQuery(this).attr("checked") == true) {
				jQuery("#product_list").attr("disabled", "");
				jQuery("#product_list").css({"background" : "#ffffff"});
			} else {
				jQuery("#product_list").attr("disabled", "true");
				jQuery("#product_list").css({"background" : "#CCCCCC"});
			}
		});

        jQuery("input[name=discountcodetype]").click(function() {
            if(this.id == 'free_gift_discount') {
                jQuery(".free_gift").show();
            } else {
                jQuery(".free_gift").hide();
            }
        });
		
		
		
	});
	
	function changeOption (ndx) {
		if (ndx == 1 || ndx == 2) {			
			// jQuery("#discount_fixex_amount").show();
			// jQuery("#can_free_shipping").show();
			jQuery("#discount_percentage").attr("disabled", "");
			jQuery("#can_free_shipping").attr("disabled", "");
		} else if (ndx == 3) {
			jQuery("#discount_percentage").attr("disabled", "true");
			jQuery("#can_free_shipping").attr("disabled", "true");
		}
	}
	
</script>
<style type="text/css">
    .free_gift {
        display: none;
    }
</style>
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
					<h1><b>Create a new Discount Code</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/discountcode" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick">&nbsp;</span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/discountcode/save">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td style="width:50%">
									<table border="0" cellspacing="5" cellpadding="5">
										<tr>
											<td style="width:50%" class="fieldlabel">Enabled</td>
											<td>
												<input type="radio" name="enabled" class="field checkbox" value="1" <?php echo set_radio('enabled', '1', TRUE); ?> >
												<label class="choice">Yes</label>
												<input type="radio" name="enabled" class="field checkbox" value="0" <?php echo set_radio('enabled', '0'); ?> >
												<label class="choice">No</label>
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Name</td>
											<td>
												<input type="text" name="description" class="field text small" value="<?php echo set_value('description'); ?>" maxlength="255" tabindex="1" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Code</td>
											<td>
												<input type="text" name="code" class="field text small" value="<?php echo set_value('code') ?>" maxlength="255" tabindex="2" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Type</td>
											<td>
												<input type="radio" name="discountcodetype" onclick="changeOption(this.value)" value="1" <?php echo $codetype_1; ?>>
												Percentage of Order<br/>
												<input type="radio" name="discountcodetype" onclick="changeOption(this.value)" value="2" <?php echo $codetype_2; ?>>
												Fixed Dollar Amount<br/>
												<input type="radio" name="discountcodetype" onclick="changeOption(this.value)" value="3" <?php echo $codetype_3; ?>>
												Free Shipping<br/>
												<input type="radio" name="discountcodetype" onclick="changeOption(this.value)" value="4" <?php echo $codetype_4; ?>>
                                                Buy One Get One Free<br/>
												<input type="radio" id="free_gift_discount" name="discountcodetype" onclick="changeOption(this.value)" value="5" <?php echo $codetype_5; ?>>
                                                Free Gift With Purchase<br/>
											</td>
										</tr>
										<tr id="discount_fixex_amount">
											<td class="fieldlabel">Percentage or Dollar Amount</td>
											<td><input type="text" id="discount_percentage" name="discount_percentage" class="field text small" value="<?php echo set_value('discount_percentage') ?>" maxlength="255" tabindex="2" style="width:100%"></td>
										</tr>
										<tr>
											<td class="fieldlabel">Apply discount if order value greater than <br>(leave blank if not applicable)</td>
											<td><input type="text" name="discount_amount_threshold" class="field text small" value="<?php echo set_value('discount_amount_threshold') ?>" maxlength="255" tabindex="2" style="width:100%"></td>
										</tr>
										<tr>
											<td class="fieldlabel">Expire after X redemptions <br>(leave blank if not applicable)</td>
											<td><input type="text" name="xuses" class="field text small" value="<?php echo set_value('xuses') ?>" maxlength="255" tabindex="2" style="width:100%"></td>
										</tr>
										<tr>
											<td class="fieldlabel">Plus Free Shipping?</td>
											<td>
												<?php 
													$checked = "";
													if (isset($_POST['can_free_shipping']) && $_POST['can_free_shipping'] == 1) {
														$checked = "checked=\"checked\"";
													}	
													 
												?>
												<input type="checkbox" name="can_free_shipping" value="1" id="can_free_shipping" <?php echo $checked ?>>
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Limit 1 per transation?</td>
											<td>
												<?php
													$checked = "";
													if (isset($_POST['apply_ones']) && $_POST['apply_ones'] == 1) {
														$checked = "checked=\"checked\"";
													}

												?>
												<input type="checkbox" name="apply_ones" value="1" id="apply_ones" <?php echo $checked ?>>
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Release Date</td>
											<td>
												<input type="text" id="release_date" name="release_date" class="field text small" value="<?php echo set_value('release_date') ?>" maxlength="255" tabindex="2" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Release Time</td>
											<td>
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
												&nbsp;hour
												<br>
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
												&nbsp;minute
												<br>
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
												&nbsp;second
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Expiry Date</td>
											<td>
												<input type="text" id="expiry_date" name="expiry_date" class="field text small" value="<?php echo set_value('expiry_date') ?>" maxlength="255" tabindex="2" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Expiry Time</td>
											<td>
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
												&nbsp;hour
												<br>
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
												&nbsp;minute
												<br>
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
												&nbsp;second
											</td>
										</tr>
									</table>
								</td>
								<td style="vertical-align:top;">
									<table border="0" cellspacing="5" cellpadding="5">
                                        <tr class="free_gift">
                                            <td>
                                                <div class="fieldlabel">Select product to gift</div><br>

                                                <select name="free_gift" id="free_gift" style="border:1px solid #ccc;width:100%; background:<?php echo $color; ?>" <?php echo $disabled ?>>
                                                    <?php
                                                    $query = $this->db->query("SELECT * FROM product WHERE publish = 1 and is_delete = 0 order by `name` asc");
                                                    $products = $query->result();
                                                    foreach ($products as $product) {
                                                        printf("<option style=\"margin-bottom:2px;\" value=\"%s\">%s</option>", $product->id, $product->name);
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
										<tr>
											<td class="fieldlabel" style="text-align:left;">
												<?php
												$checked = '';
												if (isset($_POST['enabled_product_list']) && $_POST['enabled_product_list'] == 1) {
													$checked = "checked=\"checked\"";
													$color = "#ffffff";
													$disabled = "";
												} else {
													$color = "#CCCCCC";
													$disabled = "disabled=\"\"";
												}
												?>
												<input type="checkbox" name="enabled_product_list" value="1" id="enabled_product_list" <?php echo $checked ?>>
												Apply discount to one or more products
											</td>
										</tr>
										
										<tr>
											<td>
												<div class="fieldlabel">Product List</div><br>
												
												<select name="product_list[]" id="product_list" multiple size="29" style="border:1px solid #ccc;width:100%; background:<?php echo $color; ?>" <?php echo $disabled ?>>
													<?php
													$query = $this->db->query("SELECT * FROM product WHERE publish = 1 and is_delete = 0 order by `name` asc");
													$products = $query->result();
													foreach ($products as $product) {
														printf("<option style=\"margin-bottom:2px;\" value=\"%s\">%s</option>", $product->id, $product->name);
													}
													?>
												</select>
											</td>
										</tr>

									</table>
								</td>
							</tr>
						</table>
												
						<!-- <input type="hidden" name="hid_cids" value="" id="hid_cids"> -->
						<input type="hidden" name="hid_pids" value="" id="hid_pids">
						<!-- <input type="hidden" name="hid_pid_with_cid" value="" id="hid_pid_with_cid"> -->
						
						<input type="hidden" name="action" value="addnew" id="action">
						<input type="hidden" name="id" value="-1" id="id">
						
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