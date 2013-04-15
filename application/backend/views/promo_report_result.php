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
	
	function promo_details (ndx) {
		
		document.getElementById('frmMain').action = "<?php echo base_url() ?>admin.php/promousage_details/search/" + ndx;
		document.getElementById('frmMain').submit();
		
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
					<h1><b>Reports: Promo Usage</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/promousage" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Back to Search
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
					<form id="frmMain" name="frmMain" method="post" action="">
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td>Discount Codes</td>
									<td style="text-align:center;">Orders</td>
									<td style="text-align:right;">Sale Subtotals</td>
									<td style="text-align:right;">Discount</td>
								</tr>
							</thead>
							<?php
								$order_count  = 0;
								$sum_subtotal = 0;
								$sum_discount = 0;
							?>
							<?php foreach ($this->Orders as $Order): ?>
							<tr>
								<td>
									<a href="javascript:promo_details(<?php echo $Order->discount_id ?>);"><?php echo $Order->discount_code ?></a>
								</td>
								<td style="text-align:center;">
									<?php echo $Order->orders ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($Order->subtotals, 2) ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($Order->discount, 2) ?>
								</td>
								<?php
									$order_count  += $Order->orders;
									$sum_subtotal += $Order->subtotals;
									$sum_discount += $Order->discount;
								?>
							</tr>
							<?php endforeach ?>
							<tr>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">Total</td>
								<td style="text-align:center;border-top:2px solid #ccc;font-weight:bold;"><?php echo $order_count ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">&#36;&nbsp;<?php echo number_format($sum_subtotal, 2) ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">&#36;&nbsp;<?php echo number_format($sum_discount, 2) ?></td>
							</tr>
							<?php
								$Query = $this->db->query("SELECT count(id) as `count` FROM `order`");
								$TotalOrders = $Query->result();
								
								$Query = $this->db->query("SELECT sum(amount) as 'amount' FROM `order`");
								$TotalAmount = $Query->result();
								
								$Query = $this->db->query("SELECT sum(discount) as 'discount' FROM `order`");
								$TotalDiscount = $Query->result();
							?>
							<tr>
								<td style="text-align:right;font-weight:bold;">% of Total Sales</td>
								<td style="text-align:center;font-weight:bold;"><?php echo round(($order_count / $TotalOrders[0]->count) * 100) ?>%</td>
								<td style="text-align:right;font-weight:bold;"><?php echo round(number_format(($sum_subtotal / $TotalAmount[0]->amount) * 100, 2)) ?>%</td>
								<td style="text-align:right;font-weight:bold;">&nbsp;</td>
							</tr>
							
							<tr>
								<td style="text-align:right;font-weight:bold;">Total Sales</td>
								<td style="text-align:center;font-weight:bold;">
									<?php										
										echo $TotalOrders[0]->count;
									?>
								</td>
								<td style="text-align:right;font-weight:bold;">
									&#36;&nbsp;<?php 
										echo number_format($TotalAmount[0]->amount, 2);
									?>
								</td>
								<td style="text-align:right;font-weight:bold;">
									&#36;&nbsp;<?php 
										echo number_format($TotalDiscount[0]->discount, 2);
									?>
								</td>
							</tr>
							
						</table>
																	
						<input type="hidden" name="action" value="search" id="action">
						<input type="hidden" name="release_date" value="<?php echo $this->input->post('release_date'); ?>" id="release_date">
						<input type="hidden" name="expiry_date" value="<?php echo $this->input->post('expiry_date'); ?>" id="expiry_date">
					
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