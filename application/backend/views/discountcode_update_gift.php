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

if (isset($this->discountcode[0]->discount_type)) {
	switch ($this->discountcode[0]->discount_type) {
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
			location.href = '<?php echo base_url() ?>admin.php/catalogs';
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
					<h1><b>Edit an existing Discount Code</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/gift_with_purchase" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-minusthick">&nbsp;</span>Cancel
							</a>

						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">	
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/gift_with_purchase">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<tr>
								<td style="width:50%">
									<table border="0" cellspacing="5" cellpadding="5">
										<tr>
											<td style="width:50%" class="fieldlabel">Enabled</td>
											<td>
												<input type="radio" name="enabled" class="field checkbox" value="1" <?php echo ($this->discountcode[0]->enabled == 1) ? 'checked="checked"' : "" ?> >
												<label class="choice">Yes</label>
												<input type="radio" name="enabled" class="field checkbox" value="0" <?php echo ($this->discountcode[0]->enabled == 0) ? 'checked="checked"' : "" ?> >
												<label class="choice">No</label>
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Name</td>
											<td>
												<input type="text" name="description" class="field text small" value="<?php echo $this->discountcode[0]->description ?>" maxlength="255" tabindex="1" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Code</td>
											<td>
												<input type="text" name="code" class="field text small" value="<?php echo $this->discountcode[0]->code ?>" maxlength="255" tabindex="2" style="width:100%">
											</td>
										</tr>
										<input type="hidden" id="free_gift_discount" name="discountcodetype"  value="5" >
										<input type="hidden" id="discount_percentage" name="discount_percentage"  value="" >
										<input type="hidden" id="xuses" name="xuses"  value="" >
										<input type="hidden" id="can_free_shipping" name="can_free_shipping"  value="" >
										<input type="hidden" id="apply_ones" name="apply_ones"  value="" >
										<input type="hidden" id="product_list" name="product_list"  value="" >
                                        <input type="hidden" id="enabled_product_list" name="enabled_product_list"  value="" >
										
										
										<tr>
											<td class="fieldlabel">Apply discount if order value greater than</td>
											<td><input type="text" name="discount_amount_threshold" class="field text small" value="<?php echo $this->discountcode[0]->discount_amount_threshold ?>" maxlength="255" tabindex="2" style="width:100%"></td>
										</tr>
										
										
										<tr>
											<td class="fieldlabel">Release Date</td>
											<td>
												<input type="text" id="release_date" name="release_date" class="field text small" value="<?php echo $this->discountcode[0]->release_date ?>" maxlength="255" tabindex="2" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Release Time</td>
											<td>
												<select name="release_hour" id="release_hour" onchange="" size="1">
													<?php
														for ($i = 0; $i < 24; $i++) {
															if ($this->discountcode[0]->release_hour == $i) {
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
															if ($this->discountcode[0]->release_mins == $i) {
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
															if ($this->discountcode[0]->release_seconds == $i) {
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
												<input type="text" id="expiry_date" name="expiry_date" class="field text small" value="<?php echo $this->discountcode[0]->expiry_date ?>" maxlength="255" tabindex="2" style="width:100%">
											</td>
										</tr>
										<tr>
											<td class="fieldlabel">Expiry Time</td>
											<td>
												<select name="expiry_hour" id="expiry_hour" onchange="" size="1">
													<?php
														for ($i = 0; $i < 24; $i++) {
															if ($this->discountcode[0]->expiry_hour == $i) {
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
															if ($this->discountcode[0]->expiry_mins == $i) {
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
															if ($this->discountcode[0]->expiry_seconds == $i) {
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
                                        <tr <?php echo $this->discountcode[0]->discount_type == 5 ? 'style="display:table-row"' : ''?>>
                                            <td>
                                                <div class="fieldlabel">Select product to gift</div><br>

                                                <select name="free_gift" id="free_gift" style="border:1px solid #ccc;width:100%; background:<?php echo $color; ?>" <?php echo $disabled ?>>
                                                    <?php
                                                    $query = $this->db->query("SELECT * FROM product WHERE publish = 1 and is_delete = 0 order by `name` asc");
                                                    $products = $query->result();
                                                    if($this->discountcode[0]->discount_type == 5) {
                                                        $gift = $this->db->query("SELECT p_id FROM discountcode_rel_gift WHERE d_id = " . $this->discountcode[0]->id)->row()->p_id;
                                                    } else {
                                                        $gift = 0;
                                                    }
                                                    foreach ($products as $product) {
                                                        if($product->id == $gift) {
                                                            $selected = "selected=\"selected\"";
                                                        } else {
                                                            $selected = '';
                                                        }

                                                        printf("<option style=\"margin-bottom:2px;\" value=\"%s\" %s>%s</option>", $product->id, $selected, $product->name);
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
										
										
									</table>
								</td>
							</tr>
						</table>
												
						<input type="hidden" name="hid_cids" value="" id="hid_cids">
						<input type="hidden" name="hid_pids" value="" id="hid_pids">
						<input type="hidden" name="hid_pid_with_cid" value="" id="hid_pid_with_cid">
						
						<input type="hidden" name="action" value="update_save" id="action">
						<input type="hidden" name="id" value="<?php echo $this->discountcode[0]->id ?>" id="id">
						
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