<html>
    <head>

    </head>
    <body>
    
    	<style type="text/css">
            body { margin-top: 0; margin-right: 10px; margin-bottom: 20px; margin-left: 10px; color: #493838; }
            body, table { font: 12px/18px Helvetica, Arial, sans-serif; }
            table { min-width:680px; margin-bottom: 20px; }
            table span { color: #888; font-weight: bold; }
            p { margin-bottom: 20px; }
        </style>
        
        <table width="98%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td colspan="5" style="height:65px;">
						<div style="width:100%;height:65px;border-bottom:1px solid #f3e4e9;">
							<img src="<?php echo base_url() ?>images/global/josie-maran.gif" alt="Josie Maran" />
						</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr valign="top">
                    <?php if (!empty($this->item['voucher_id'])): ?>
                    <td>
                        <?php
                            $item = $this->db->query("SELECT gift_voucher_image_small as image  FROM gift_voucher WHERE id=?", $this->item['voucher_id']);
                            $result = $item->row();
                        ?>
                        <?php if($result->image != ""): ?> <img src="<?php echo base_url() . $result->image?>" alt="" /> <?php endif;?>
                    </td>
                    <?php endif;?>
                    <td colspan="4">
                        <p>Hi <?php echo $this->input->post('to') ?>,</p>

                        <p>You have received a Josie Maran Cosmetics eGift Card from <?php echo $this->input->post('from') ?>.</p>

                        <p><?php echo $this->input->post('message') ?></p>


                        <p>eGift Card: <?php echo $this->item['code'] ?><br />
                        Value: $<?php echo $this->item['price'] ?></p>

                        <p>To redeem your eGift Card, valid toward products site-wide, enter your voucher code at time of check-out.</p>

                        <p>Please visit our website at: <a href="http://www.josiemarancosmetics.com">www.josiemarancosmetics.com</a></p>

                        <p>Thank you,<br />
                        Josie Maran</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>