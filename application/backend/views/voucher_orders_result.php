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

    function update_order (ndx) {
        jQuery("#action").attr('value', 'update');
        jQuery("#id").val(ndx);
        document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/orders';
        jQuery("#frmMain").submit();
    }
	
	function promo_details (ndx) {
		
		document.getElementById('frmMain').action = "<?php echo base_url() ?>admin.php/promousage_details/search/" + ndx;
		document.getElementById('frmMain').submit();
		
	}


    function customer (ndx) {
   		jQuery("#action").attr('value', 'update');
   		jQuery("#id").val(ndx);
   		document.getElementById("frmMain").action = '<?php echo base_url() ?>admin.php/members';
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
				  <?php if($this->uri->segment(1) == 'voucherorders' ) { ?>
					<h1><b>Reports: Voucher Orders</b></h1>
					<?php } else { ?>
					<h1><b>Reports: Voucher Purchases</b></h1>	
					<?php } ?>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
							<a href="<?php echo base_url() ?>admin.php/voucherorders" class="btn ui-state-default">
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
									<td style="text-align:right;">Customer name</td>
									<td style="text-align:right;">Order number</td>
									<td style="text-align:right;">Order Date</td>
                                    <td style="text-align:right;">Amount Used</td>
								</tr>
							</thead>
							<?php
								$sum_subtotal = 0;
                                $balance = 0;
                                $i = 0;
							?>
							<?php foreach ($this->Orders as $Order): ?>
                            <?php $i++;?>
							<tr>
								<td>
									<?php echo $i ?>
								</td>
                                <td>
                                    <?php echo $Order->code ?>
                                </td>
                                <td style="text-align:right;">
                                    <?php
									
                                    if ($Order->user_id != 0) {
									
										$user_information = $this->db->query("SELECT * FROM `users` WHERE id = '".$Order->user_id."' ");
										$user_info = $user_information->row();
										$firstname = $user_info->firstname;
										$lastname = $user_info->lastname;
									
                                    ?>
                                    <a href="javascript:customer(<?php echo $Order->user_id ?>)"><?php echo $firstname . ' ' . $lastname ?></a>
                                    <?php
                                    } else {										
                                    ?>
                                    <?php echo $Order->firstname . ' ' . $Order->lastname ?>
                                    <?php
                                    }
                                    ?>
                                </td>
								<td style="text-align:right;">
                                    <a href="javascript:update_order(<?php echo $Order->id ?>);">
                                        <?php echo $Order->order_no ?>
                                    </a>
								</td>
                                <td style="text-align:right;">
                                    <?php echo $Order->order_date ?>
                                    <?php //echo $Order->oapm ?>
                                    <?php //echo $Order->otime ?>
                                </td>
                                <td style="text-align:right;">
									&#36;&nbsp;<?php echo number_format($Order->v_amount, 2) ?>
								</td>
								<?php
									$sum_subtotal += $Order->v_amount;
								?>
							</tr>
							<?php endforeach ?>
							<tr>
                                <td colspan="3"></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">Total</td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;"><?php echo $i ?></td>
								<td style="text-align:right;border-top:2px solid #ccc;font-weight:bold;">&#36;&nbsp;<?php echo number_format($sum_subtotal, 2) ?></td>
							</tr>
						</table>
																	
						<input type="hidden" name="action" value="search" id="action">
						<input type="hidden" name="id" value="" id="id">

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