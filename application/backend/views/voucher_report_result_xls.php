<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=exceldata.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
				<div class="hastable">					
					
						
						<table border="0" cellspacing="5" cellpadding="5">
							<thead>
								<tr>
									<td><strong>ID#</strong></td>
									<td><strong>Code</strong></td>
									<td><strong>Title</strong></td>
                                    <td><strong>Date</strong></td>
                                    <td><strong>Type</strong></td>
									<td style="text-align:center;"><strong>Customer</strong></td>
                                    <td style="text-align:right;"><strong>From</strong></td>
                                    <td style="text-align:right;"><strong>To</strong></td>
                                    <td style="text-align:right;"><strong>Recipient email</strong></td>
									<td style="text-align:right;"><strong>Price</strong></td>
									<td style="text-align:right;"><strong>Remaining balance</strong></td>
									
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
                                
							</tr>
							<tr>
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
                               
							</tr>
						</table>
																	
						
				