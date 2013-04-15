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
	});
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
					<h1><b>Reports: Monthly Shipment Report</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<!-- <a href="<?php echo base_url() ?>admin.php/orders/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Order
							</a> -->
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
				
				<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>','</span></div>'); ?>

				<div class="hastable">					
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/monthly_shipment_report/search">

						<table>
							<tr>
								<td>					
									<ul>
										<li>
											<label class="desc">Select a month</label>
											<select name="selmonthly" id="selmonthly" size="1">
												<option value="0">Please select</option>
												<?php
													$IS_SELECTED = "";
													$query = $this->db->query(
														"SELECT DISTINCT " .
														"SUBSTR(STR_TO_DATE(order_date, '%Y-%m-%d'), 1, 4) as `year`, " .
														"SUBSTR(STR_TO_DATE(order_date, '%Y-%m-%d'), 6, 2) as `month` " .
														"FROM `order` " .
														"WHERE order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0;")->result();
													
													echo $this->db->last_query();
													
													foreach ($query as $item) {
														
														if ($this->input->post('selSalesYearAndMonth') == ($item->year . '-' . $item->month)) {
															$IS_SELECTED = "selected='selected'";
														} else {
															$IS_SELECTED = "";
														}
														
														printf("<option value=\"%s\" %s>%s</option>", $item->year . '-' . $item->month, $IS_SELECTED, $item->year . '-' . $item->month);
													}
												?>
											</select>
										</li>
									</ul>
									<br><br>
									<input type="submit" name="btnSubmit" value="Search" id="btnSubmit" class="btn ui-state-default ui-corner-all">						
									<input type="hidden" name="action" value="search" id="action">
								</td>
							</tr>
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