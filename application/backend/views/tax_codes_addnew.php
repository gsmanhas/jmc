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
			location.href = '<?php echo base_url() ?>admin/taxcodes';
		});
		
		jQuery("#state").change(function(){
			jQuery("#state option:selected").each(function(index, value){
				if (jQuery(value).attr('alt') == undefined) {
					jQuery("#tax_code").val('');
				} else {
					jQuery("#tax_code").val(jQuery(value).attr('alt'));
				}
			});
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
					<h1><b>Create a new Tax Code</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">

							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/taxcodes" class="btn ui-state-default ui-corner-all">
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/taxcodes/save">
								
								<ul>
									<li>
										<label class="desc">Please select a state</label>
										<select name="state" id="state" size="1">
										<option value="-1">Choose state</option>
										<?php
											$query = $this->db->query("SELECT s.id, s.state, s.sharthand,  c.country
											                            FROM state s, country c
											                            WHERE c.id = s.country_id order by c.id asc");
											$states = $query->result();
											foreach ($states as $state) {
												$is_select = "";
												if (isset($_POST['state'])) {
													if ($state->id == $_POST['state']) {
														$is_select = "selected=\"selected\"";
													}
												}
												printf("<option alt=\"%s\" value=\"%s\" %s>%s - %s</option>", $state->sharthand, $state->id, $is_select, $state->country, $state->state);
											}
										?>
										</select>
									</li>
									<li>
										<label class="desc">Tax Code</label>
										<input type="text" name="tax_code" value="<?php echo set_value('tax_code') ?>" id="tax_code" style="width:50%">
									</li>
									<li>
										<label class="desc">Tax Rate %</label>
										<input type="text" name="tax_rate" value="<?php echo set_value('tax_rate') ?>" id="tax_rate" style="width:50%">
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