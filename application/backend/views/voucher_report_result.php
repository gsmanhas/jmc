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

        jQuery(".resend_voucher").click(function(e) {
            e.preventDefault();

            var href = jQuery(this).attr("href");

            jQuery.get(href, function(){
                jQuery("#success_resend").show();
            });
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
					<h1><b>Reports: Voucher Purchases</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/voucherreport/export/<?php echo $from_date; ?>/<?php echo $to_date; ?>" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Export as XSL
							</a>
							
							<a href="<?php echo base_url() ?>admin.php/voucherreport" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Back to Search
							</a>
							
						</div>
						<div class="clearfix"></div>
					</div>
				</div>

                <div id="success_resend" class="response-msg success ui-corner-all" style="display: none;">
                    <span>Success message</span> Gift Voucher Resended
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
									<td>ID#</td>
									<td>Code</td>
									<td>Title</td>
                                    <td>Date</td>
                                    <td>Type</td>
									<td style="text-align:center;">Customer</td>
                                    <td style="text-align:right;">From</td>
                                    <td style="text-align:right;">To</td>
                                    <td style="text-align:right;">Recipient email</td>
									<td style="text-align:right;">Price</td>
									<td style="text-align:right;">Remaining balance</td>
									<td style="text-align:center; width: 125px;">Options</td>
								</tr>
							</thead>
							<?php
								$order_count  = 0;
								$sum_subtotal = 0;
                                $balance = 0;
							?>
							<?php foreach ($this->Orders as $Order): ?>
							<tr>
								<td>
									<?php echo $Order->id ?>
								</td>
								<td>
									<?php echo $Order->code ?>
								</td>
								<td>
									<?php echo $Order->title ?>
								</td>
                                <td>
                                    <?php echo $Order->odate ?>
                                    <?php echo $Order->oapm ?>
                                    <?php echo $Order->otime ?>
                                </td>
                                <td>
                                    <?php echo $Order->gift_voucher_type == 'purchased' ? 'Purchased' : 'Manually Created By:<br /><b>' . $Order->created_by . '</b><br /><b>Note:</b><br/>'.$Order->note ?>
                                </td>
								<td style="text-align:center;">
									<?php echo $Order->name ?>
								</td>
                                <td style="text-align:right;">
                                    &nbsp;<?php echo $Order->from ?>
                                </td>
                                <td style="text-align:right;">
                                    &nbsp;<?php echo $Order->to ?>
                                </td>
                                <td style="text-align:right;">
                                    &nbsp;<?php echo $Order->recipient_email  ?>
                                </td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($Order->price, 2) ?>
								</td>
								<td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($Order->balance, 2) ?>
								</td>
                                <td style="text-align: center">
                                    <a class="resend_voucher btn ui-state-default" href="<?php echo base_url() ?>admin.php/giftvouchers/resend/<?php echo $Order->id?>">
                                        <span class="ui-icon ui-icon-arrowrefresh-1-e"></span>Resend Email
                                    </a>
                                </td>
								<?php
									$order_count++;
									$sum_subtotal += $Order->price;
									$balance += $Order->balance;
								?>
							</tr>
							<?php endforeach ?>
							<tr>
                                <td colspan="7"></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">Total</td>
								<td style="text-align:center;border-top:2px solid #ccc;font-weight:bold;"><?php echo $order_count ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">&#36;&nbsp;<?php echo number_format($sum_subtotal, 2) ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">&#36;&nbsp;<?php echo number_format($balance, 2) ?></td>
                                <td></td>
							</tr>
							<?php /*?><tr>
                                <td colspan="7"></td>
								<td style="text-align:right;font-weight:bold;">Total Sales</td>
								<td style="text-align:center;font-weight:bold;">
									<?php
										$Query = $this->db->query("SELECT count(id) as `count` FROM `order_voucher_details`");
										$TotalOrders = $Query->result();
										echo $TotalOrders[0]->count;
									?>
								</td>
								<td style="text-align:right;font-weight:bold;">
									&#36;&nbsp;<?php 
										$Query = $this->db->query("SELECT sum(price) as 'amount' FROM `order_voucher_details`");
										
										$TotalAmount = $Query->result();
										echo number_format($TotalAmount[0]->amount, 2);
									?>
								</td>
								<td style="text-align:right;font-weight:bold;">
									&#36;&nbsp;<?php 
										$Query = $this->db->query("SELECT sum(balance) as 'discount' FROM `order_voucher_details`");
										$TotalDiscount = $Query->result();
										echo number_format($TotalDiscount[0]->discount, 2);
									?>
								</td>
                                <td></td>
							</tr><?php */?>
						</table>
																	
						<input type="hidden" name="action" value="search" id="action">
						<input type="hidden" name="from_date" value="<?php echo $this->input->post('from_date'); ?>" id="release_date">
						<input type="hidden" name="to_date" value="<?php echo $this->input->post('to_date'); ?>" id="expiry_date">
					
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