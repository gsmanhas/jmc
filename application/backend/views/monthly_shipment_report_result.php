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
			document.frmMain.action = '<?php echo base_url() ?>admin.php/monthly_shipment_report/export';
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
					<h1><b>Reports: Monthly Shipment Report</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/monthly_shipment_report" class="btn ui-state-default">
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
									<td width="30%" style="text-align:left;">Item / SKU</td>
									<td width="30%" style="text-align:right;">Quantity</td>
									<td width="30%" style="text-align:right;">Total Collected | Discount Amount Received | Unit Price</td>
								</tr>
							</thead>
							<tbody>
								
								<?php
								$_MONTH = "";
								if (($this->input->post('selmonthly'))) {
									$_MONTH = $this->input->post('selmonthly')."-01";
								} else {
									$_MONTH = date("y")."-".date("m")."-01";
								}
								
								$Sales_tax_collected = $this->db->query(
									"SELECT " .
									"SUM(product_tax) as 'price' " .
									"FROM `order` " .
									"WHERE " .
									"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
									"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
									"AND is_delete = 0 " .
									"ORDER BY order_date ASC"
									, array($_MONTH, $_MONTH))->result();
									
									// echo $this->db->last_query();
								?>
								
								<tr>
									<td width="10%"><?php echo $this->input->post('selmonthly') ?></td>
									<td width="30%" style="text-align:left">Sales Tax Collected</td>
									<td width="30%" style="text-align:right">1</td>
									<td width="30%" style="text-align:right"><?php echo number_format($Sales_tax_collected[0]->price, 2); ?></td>
								</tr>
								
								<?php
								
								$_MONTH = "";
								if (($this->input->post('selmonthly'))) {
									$_MONTH = $this->input->post('selmonthly')."-01";
								} else {
									$_MONTH = date("y")."-".date("m")."-01";
								}
								
								$Shipping_Charged = $this->db->query(
									"SELECT " .
									"SUM(calculate_shipping) as 'price' " .
									"FROM `order` " .
									"WHERE " .
									"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
									"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
									"AND is_delete = 0 AND freeshipping = 0 " .
									"ORDER BY order_date ASC"
									, array($_MONTH, $_MONTH))->result();
									
									// echo $this->db->last_query();
									
								?>
								
								<tr>
									<td width="10%"><?php echo $this->input->post('selmonthly'); ?></td>
									<td width="30%" style="text-align:left">Shipping Charged</td>
									<td width="30%" style="text-align:right">1</td>
									<td width="30%" style="text-align:right"><?php echo number_format($Shipping_Charged[0]->price, 2) ?></td>
								</tr>
								
								<?php
								
									$_MONTH = "";
									if (($this->input->post('selmonthly'))) {
										$_MONTH = $this->input->post('selmonthly')."-01";
									} else {
										$_MONTH = date("y")."-".date("m")."-01";
									}
									
									$Free_Shipping_for_Orders_Over_50 = 0;
									
									$Q1 = $this->db->query(
										"SELECT " .
										"SUM(calculate_shipping) as 'price'" .
										"FROM `order` " .
										"WHERE " .
										"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
										"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
										"AND is_delete = 0 AND freeshipping = 1 " .
										"ORDER BY order_date ASC"
										, array($_MONTH, $_MONTH))->result();
									
									// echo $this->db->last_query().br(2);
									
									$Q2 = $this->db->query(
										"SELECT " .
										"SUM(calculate_shipping) as 'price' " .
										"FROM `order` " .
										"WHERE " .
										"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
										"AND order_state != 3 AND order_state != 5 AND order_state != 6 " .
										"AND is_delete = 0 AND promo_free_shipping = 1 " .
										"ORDER BY order_date ASC "
										, array($_MONTH, $_MONTH))->result();
									
									// echo $this->db->last_query().br(2);
									
									if (count($Q1) >= 1) {
										$Free_Shipping_for_Orders_Over_50 = is_null($Q1[0]->price) ? 0 : $Q1[0]->price;
									}
									
									if (count($Q2) >= 1) {
										$Free_Shipping_for_Orders_Over_50 += (is_null($Q2[0]->price) ? 0 : $Q2[0]->price);
									}
								?>
								
								<tr>
									<td width="10%"><?php echo $this->input->post('selmonthly') ?></td>
									<td width="30%" style="text-align:left">Discount: Free Shipping for Orders Over $50</td>
									<td width="30%" style="text-align:right">1</td>
									<td width="30%" style="text-align:right"><?php echo number_format($Free_Shipping_for_Orders_Over_50, 2) ?></td>
								</tr>
								
								<?php
								
								$_MONTH = "";
								if (($this->input->post('selmonthly'))) {
									$_MONTH = $this->input->post('selmonthly')."-01";
								} else {
									$_MONTH = date("y")."-".date("m")."-01";
								}
								
								$Query = $this->db->query(
									"SELECT discount_id, count(discount_id) as 'count', SUM(discount) as 'discount', " .
									"(SELECT `code` FROM discountcode WHERE id = discount_id) as 'name' FROM `order`" .
									" WHERE " .
									"order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " .
									// "AND order_state != 3 AND order_state != 5 AND order_state != 6  " .
									// "AND promo_free_shipping = 0 AND freeshipping = 0 AND is_delete = 0 " .
									"AND discount_id != 0 " .
									"AND order_state = 4 " .
									"GROUP BY discount_id " .
									"ORDER BY order_date ASC"
								, array($_MONTH, $_MONTH));
								
								// echo $this->db->last_query().br(2);
								
								foreach ($Query->result() as $item) {
								?>
								<tr>
									<td width="10%"><?php echo $this->input->post('selmonthly') ?></td>
									<td width="30%" style="text-align:left">Discount: <?php echo $item->name ?></td>
									<td width="30%" style="text-align:right"><?php echo $item->count ?></td>
									<td width="30%" style="text-align:right"><?php echo number_format($item->discount, 2) ?></td>
								</tr>
								<?php
								}
								?>
								
								<?php
								// foreach ($this->OrderDate as $OrderDate) {
									
									$Query = $this->db->query(
										"SELECT pid, sku, pname, price, SUM(qty) as 'qty' FROM monthly_shipment_report " .
										"WHERE order_id IN ( " .
										"SELECT id FROM `order` WHERE order_date Like '". $this->input->post('selmonthly') . "%' " .
										"AND order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0 " .
										// " AND order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ".$_MONTH.") AND DATE_ADD(LAST_DAY(".$_MONTH."), INTERVAL 1 DAY) " .
										") GROUP BY pid, price"
									)->result();
									
									// echo $this->db->last_query().br(1);
													
									foreach ($Query as $item) {
									?>
									<tr>
										<td width="10%"><?php echo $this->input->post('selmonthly') ?></td>
										<td width="30%" style="text-align:left;"><?php echo $item->sku ?></td>
										<td width="30%" style="text-align:right;"><?php echo $item->qty ?></td>
										<td width="30%" style="text-align:right;"><?php echo $item->price ?></td>
									</tr>
								<?php
									}
								//	}
								?>
								
							</tbody>

						</table>
											
					<input type="hidden" name="action" value="search" id="action">
					<input type="hidden" name="selmonthly" value="<?php echo $this->input->post('selmonthly'); ?>" id="selmonthly">
					<input type="hidden" name="last_query" value="<?php echo $this->last_query ?>" id="last_query">
					
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




