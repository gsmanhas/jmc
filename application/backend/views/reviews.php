<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" src="/js/jquery.raty.js"></script>
<script type="text/javascript" charset="utf-8">
	function update (ndx) {
		jQuery("#action").attr('value', 'update');
		jQuery("#id").val(ndx);
		jQuery("#frmMain").submit();
	}
	function publish (ndx, state) {
		jQuery("#action").attr('value', 'publish');
		jQuery("#id").val(ndx);
		jQuery("#publish_state").val(state);
		jQuery("#frmMain").submit();
	}
	function trash (ndx) {
		if (confirm('Delete this Item ?')) {
			jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ndx);
			jQuery("#frmMain").submit();
		}
	}
	
	function orderSave () {

		var ids = [];
		jQuery(':checkbox').each(function(index, value){
			//if (jQuery(this).attr('checked') == true) {
				if (jQuery(this).val() != 0)
					ids.push(jQuery(this).val());
			//}
		});

		jQuery("#frmMain").attr('action', 'admin/reviews');
		jQuery("#action").attr('value', 'order');
		jQuery("#id").val(ids);
		jQuery("#frmMain").submit();
	}
	
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
					<h1><b>Reviews</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
						  <a href="<?php echo base_url() ?>admin.php/reviews/allpublish" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Publish All
							</a>
							<a href="javascript:orderSave()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-bookmark"></span>Save Order
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
				
				<?php
					$i = 1; $k = 0;
					foreach ($this->Products as $Product) {
				?>

				<div class="hastable">					
					<form id="frmMain" name="frmMain" method="post" action="">
					<table>
						<thead>
							<tr>
								<td><?php echo $Product->name ?></td>
							</tr>
						</thead>
						<?php
						$Query = $this->db->query(
							"SELECT " .
							"*, (SELECT CONCAT(CONCAT(firstname, ' '), lastname) FROM `users` WHERE id = uid) as 'customer_name' " .
							" FROM product_review WHERE pid = ? AND is_delete = 0 ORDER BY ordering ASC"
						, $Product->pid);
						// echo $this->db->last_query();
						foreach ($Query->result() as $review) {
						?>
						<tr>
							<td>
								<input type="checkbox" value="<?php echo $review->id ?>" name="list" class="checkbox" style="display:none">
								
								<div style="float:left;width:150px;height:auto;overflow:hidden;margin-bottom:15px;">
									<script type="text/javascript" charset="utf-8">
										jQuery(document).ready(function(){
											$('#rate_<?php echo $review->id ?>').raty({readOnly: true, start: <?php echo $review->rate ?>});
										});
									</script>
									
									<div id="rate_<?php echo $review->id ?>" style="margin-bottom:5px;"></div>
									
									<div>Review By</div>
									
									<div style="color:#977778;font-size:1.167em;font-weight:bold;margin-bottom:5px;"><?php echo $review->customer_name ?></div>
									
									<div style="color:#977778;font-size:0.917em;font-style:italic;"><?php echo $review->created_at ?></div>
								</div>
								
								<div style="float:left;width:650px;height:auto;overflow:hidden;margin-bottom:15px;">
									<div style="color:#493838;font-size:1.25em;"><?php echo $review->title ?></div>
									
									<div style="color:#493838;">
										<?php 
											echo br(1);
											echo $review->message;
											echo br(1);
										?>
									</div>
								</div>
								
								<div style="float:right;width:120px;height:auto;overflow:hidden;">
									<input type="hidden" value="<?php echo $review->id ?>" name="lists[]" class="checkbox">
									<input type="text" name="order[]" value="<?php echo $review->ordering ?>" size="2" onfocus="this.select();" style="margin-top:5px;">
									<a style="float:right;" class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:trash(<?php echo $review->id ?>)">
										<span class="ui-icon ui-icon-trash"></span>
									</a>
									
									<?php if ($review->publish == 1) { ?>
										<a style="float:right;" class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $review->id ?>, 0)">
											<span class="ui-icon ui-icon-unlocked"></span>
										</a>
									<?php } else { ?>
										<a style="float:right;" class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:publish(<?php echo $review->id ?>, 1)">
											<span class="ui-icon ui-icon-locked"></span>
										</a>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php
						}
						}
						?>
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