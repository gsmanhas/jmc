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
	
		jQuery("#export").click(function(){
			document.frmMain.action = '<?php echo base_url() ?>admin.php/shipments_for_a_given_time_period/export';
			document.frmMain.submit();
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
					<h1><b>Reports: Product Shipments</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/shipments_for_a_given_time_period" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Back to Search
							</a>
							<a href="#" class="btn ui-state-default" id="export">
								<span class="ui-icon ui-icon-circle-plus"></span>Export
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

				<div class="hastable">					
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>base/shipments_for_a_given_time_period">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td width="10%">Date</td>
									<td width="30%" style="text-align:right;">UPC</td>
									<td width="30%" style="text-align:right;">Quantity</td>
									<td width="30%" style="text-align:right;">Unit Price</td>
								</tr>
							</thead>
							<?php
							
							$release_date = $this->input->post('release_date');
							$expiry_date  = $this->input->post('expiry_date');
							
							$Shipping_Charged = $this->db->query(
								"SELECT " .
								"SUM(calculate_shipping) as 'price', DATE_FORMAT(?, '%m/%d/%Y') as 'curr_date'" .
								"FROM `order` " .
								"WHERE " .
								"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND (?) " .
								"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
								"AND is_delete = 0 AND freeshipping = 0 " .
								"ORDER BY order_date ASC"
								, array($release_date, $release_date, $expiry_date))->result();
								
								// echo $this->db->last_query();
								
							?>
							<tr>
								<td width="10%" style="text-align:right;"><?php echo $Shipping_Charged[0]->curr_date ?></td>
								<td width="30%" style="text-align:right;">WEBSHIP</td>
								<td width="30%" style="text-align:right;">1</td>
								<td width="30%" style="text-align:right;"><?php echo number_format($Shipping_Charged[0]->price, 2) ?></td>
							</tr>
							
							<?php foreach ($this->shipments_report as $report): ?>
							<?php
								$result = $this->db->query(
									"SELECT " .
									"'". $report->order_date ."' as 'order_date'," .
									"(SELECT sku FROM product WHERE id = pid) as 'sku', pid, sum(qty) as 'qty'," .
									"price " .
									"FROM order_list " .
									"WHERE order_id in(" .
									"SELECT id FROM `order` as o WHERE order_date LIKE '" . $report->order_date2 . "%' " .
									"AND `o`.`order_state` != 3 AND o.order_state != 5 AND o.order_state != 6 " .
									"AND is_delete = 0 " .
									"ORDER BY order_date ASC " .
									")" .
									" GROUP BY pid, price " .
									" ORDER BY sku ASC"
								, FALSE)->result();
								
								// echo $this->db->last_query().br(1);		
								// if (count($result) >= 1) {
								foreach ($result as $item) {
							?>
							<tr style="">
								<td style="text-align:right;"><?php echo $report->order_date ?></td>
								<td style="text-align:right;"><?php echo $item->sku ?></td>
								<td style="text-align:right;"><?php echo $item->qty ?></td>
								<td style="text-align:right;"><?php echo $item->price ?></td>
							</tr>
							<?php
								}
							?>
	
							<?php endforeach ?>

						</table>
											
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="last_query" value="<?php echo $this->last_query ?>" id="last_query">
					
					<input type="hidden" name="release_date" value="<?php echo $this->input->post('release_date') ?>" id="release_date">
					<input type="hidden" name="expiry_date" value="<?php echo $this->input->post('expiry_date') ?>" id="expiry_date">
					
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




