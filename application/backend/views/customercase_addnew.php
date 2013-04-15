<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8">
	var base_url = '<?php echo base_url() ?>';
</script>
<script src="<?php echo base_url() ?>js/admin/blockUI.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function(){
		
		jQuery("#btn_submit").click(function(){
			jQuery("#frmMain").submit();
		});
		
		jQuery("#dialog_link").click(function(){
			location.href = '<?php echo base_url() ?>admin/customercase';
		});
		
		jQuery("#btn_reply").click(function(){
			jQuery.blockUI({
				message: jQuery('#reply_dialog'), css: {
					top : '20px',
					left: '20px',
					right: '20px',
					backgroundColor: '#000', 
	                '-webkit-border-radius': '10px', 
	                '-moz-border-radius': '10px',
					width: '95%'
				}
			});
		});
		
		jQuery("#btnReplyMessage").click(function(){
			jQuery.post(base_url + 'ajax/customer_case_send_message',{
				"id"        : jQuery("#id").val(),
				"message"   : jQuery("#reply_message").val(),
				"first_name": jQuery("#first_name").val(),
				"last_name" : jQuery("#last_name").val(),
				"email"     : jQuery("#email").val(),
				"comments"  : jQuery("#comments").val()
			},
			function(response){
				if (response.success == 1) {
					var li = document.createElement("li");
					jQuery(li).html(jQuery("#reply_message").val() + ' ' + response.created_at );
					jQuery("#reply_log").children('ul').append(li);
					jQuery(".reply_message").html("");
					$.unblockUI({ fadeOut: 200 });
				} else {
					$.unblockUI({ fadeOut: 200 });
				}
			}, "json")
		});
		
		jQuery("#btnCancel").click(function(){
			jQuery(".reply_message").html("");
			$.unblockUI({ fadeOut: 200 });
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
					<h1><b>Customer Cases</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="#" id="btn_submit" class="btn ui-state-default ui-corner-all">
								<span class="ui-icon ui-icon-disk"></span>Save
							</a>
							<a href="<?php echo base_url() ?>admin.php/customercase" class="btn ui-state-default ui-corner-all">
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
							<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/customercase/submit">
							<ul>
								<li>		
									<label class="desc">The type of case</label>
									<div>
										<select name="services_options" id="services_options" size="1">
											<option value="">-- Please select --</option>
											<?php foreach ($this->CaseCatalogs as $catalogs): ?>
											<option value="<?php echo $catalogs->id ?>"><?php echo $catalogs->name ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</li>
								<li>		
									<label class="desc">First Name</label>
									<div>
									<input style="width:50%" type="text" name="first_name" id="first_name" value="" maxlength="255" tabindex="1">
									</div>
								</li>
								<li>
									<label class="desc">Last Name</label>
									<div>
									<input style="width:50%" type="text" name="last_name" id="last_name" value="" maxlength="255" tabindex="1">
									</div>
								</li>
								<li>		
									<label class="desc">Email</label>
									<div>
									<input style="width:50%" type="text" name="email" id="email" value="" maxlength="255" tabindex="1">
									</div>
								</li>
								<li>
									<label class="desc">Comments</label>
									<div>
										<textarea name="comments" id="comments" rows="8" cols="80" style="width:50%;border:1px solid #ccc;"></textarea>
									</div>
								</li>
								
								<li>
									<label class="desc">Does the customer use Josie Maran Cosmetics? (optional)</label>
									<input type="radio" name="use_jmc_cosmetics" value="1" >Yes
									<input type="radio" name="use_jmc_cosmetics" value="0" >No
								</li>

								<li>
									<label class="desc">Is the customer registered member? (optional)</label>
									<input type="radio" name="is_register" value="1" >Yes
									<input type="radio" name="is_register" value="0" >No
								</li>
								
								<li>
									<label class="desc">Cases Status</label>
									<select name="cases_status" id="cases_status" size="1">
										<?php foreach ($this->CaseStatus as $item): ?>
										<option value="<?php echo $item->id ?>"><?php echo $item->title ?></option>	
										<?php endforeach ?>
									</select>
								</li>
													
							</ul>
							
								<input type="hidden" name="action" value="update_save" id="action">
								<input type="hidden" name="id" value="0" id="id">
								
							</form>
							
							
							
							<div id="reply_dialog" style="display:none">
								<div><textarea id="reply_message" name="reply_message" rows="8" cols="80" style="width:50%;border:1px solid #ccc;"></textarea></div>
								<div>
									<input type="button" name="Search" value="Reply" id="btnReplyMessage">
									<input type="button" name="Cancel" value="Cancel" id="btnCancel">
								</div>
								<div class="search_customers_result"></div>
							</div>
							
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