<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>js/admin/orders.js"></script>
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
					<h1><b>Orders</b></h1>
					<div class="other">
						<div class="float-left"></div>
						<div class="button float-right">
                            <a href="javascript:void(0)" id="change_status" class="btn ui-state-default" style="display: none;">
                                <span class="ui-icon ui-icon-pencil"></span>Mark as Shipped & Email Customer
                            </a>
							<a href="<?php echo base_url() ?>admin.php/orders" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Go back to Search
							</a>
							<!-- <a href="<?php echo base_url() ?>admin.php/orders/addnew" class="btn ui-state-default">
								<span class="ui-icon ui-icon-circle-plus"></span>Create a new Order
							</a> -->			
							<a href="javascript:removeAll()" class="btn ui-state-default">
								<span class="ui-icon ui-icon-trash"></span>Delete
							</a>
							<a href="javascript:void(0)" id="bulk_print" class="btn ui-state-default">
								<span class="ui-icon ui-icon-print"></span>Print
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
					<form id="frmMain" name="frmMain" method="post" action="<?php echo base_url() ?>admin.php/orders">
					<table>
						<thead>
						<tr>
							<th width="1%">#</th>
							<th width="1%">
								<input type="checkbox" value="0" id="chkAll" name="chkAll" class="submit">
							</th>
							<th>Order No.</th>

							<th>Customer Name</th>
							<th>Payment Method</th>
							<th style="text-align:right;">Total Product Sales</th>
							<th style="text-align:right;">Total Gift Card Sales</th>
							<th style="text-align:right;">Total</th>
							<th width="40">&nbsp;</th>
							<th width="160">Order Date</th>
							<th width="100">Order Status</th>
							<th width="120">Tracking No.</th>
							<th width="40">Printed</th>							
							<th style="text-align:center;" width="70">Options</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($this->orders as $order) {
						
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<input type="checkbox" value="<?php echo $order->id ?>" name="list" class="checkbox">
								<input type="hidden" value="<?php echo $order->id ?>" name="lists[]" class="checkbox">
							</td>
							<td>
								<a href="javascript:update(<?php echo $order->id ?>);"><?php echo $order->order_no ?></a>
							</td>
							
							<td><?php echo $order->firstname ?>&nbsp;<?php echo $order->lastname ?></td>
							<td><?php if($order->payment_method == 1) { echo 'Credit Card'; }else if($order->payment_method == 3) { echo 'Gift Voucher'; } else if($order->payment_method == 4) { echo 'Test'; } else if($order->payment_method == 5) { echo 'eGift Card + Credit Card'; } else { echo 'PayPal'; } ?></td>
							<?php
								$without_voucher_total = 0;
								$voucher_total = 0;
								$query_v = $this->db->query("select sum(price) as without_voucher_total from order_list where invoice_number = '".$order->order_no."' and item_type != 'voucher' ");
								
								if($query_v->num_rows() > 0){
								  $voucher_total =  $query_v->result();			
								  $without_voucher_total = $voucher_total[0]->without_voucher_total;
								}
								$query_v = $this->db->query("select sum(price) as voucher_total from order_list where invoice_number = '".$order->order_no."' and item_type = 'voucher' ");
								if($query_v->num_rows() > 0){
								  $voucher_total =  $query_v->result();								
								 $voucher_total = $voucher_total[0]->voucher_total;
								}
							?>
							<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($without_voucher_total, 2); ?></td>
							<td style="text-align:right;">&#36;&nbsp;<?php echo number_format($voucher_total, 2); ?></td>
							<td style="text-align:right;">&#36;&nbsp;<?php echo $order->amount; ?></td>
							<td>&nbsp;</td>
							<td><?php echo $order->odate . ' ' . $order->oapm . ' ' . $order->otime ?></td>
							<td>
								<?php
									$result = $this->db->query("SELECT `name` as `name` FROM order_state where id = ?", $order->order_state);
									$order_state = $result->result();
								?>
								<?php echo $order_state[0]->name?>
							</td>
							<td><?php echo ($order->track_number) ? $order->track_number : "&nbsp;" ?></td>
							<td>
								<?php if($order->is_print == 'Y'){ ?>
									<img src="/assets/correct.png" alt="Yes" title="Yes"  />
							    <?php }else { ?>
									<img src="/assets/cross.png" alt="No" title="No"  />
								<?php } ?>
							 </td>
							<td>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:update(<?php echo $order->id ?>)">
									<span class="ui-icon ui-icon-wrench"></span>
								</a>
								<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" href="javascript:trash(<?php echo $order->id ?>)">
									<span class="ui-icon ui-icon-trash"></span>
								</a>
							</td>
						</tr>
						<?php
							$i++;
						}
						?>
						</tbody>
					</table>
					
					<input type="hidden" name="action" value="" id="action">
					<input type="hidden" name="id" value="0" id="id">
					<input type="hidden" name="publish_state" value="" id="publish_state">
					<input type="hidden" name="last_query" value="<?php echo $this->QUERY_STRING ?>" id="last_query">
					
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