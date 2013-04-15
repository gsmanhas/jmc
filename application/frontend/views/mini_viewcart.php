<table border="0" cellspacing="0" cellpadding="0" width="100%" style="font-size:1em;margin-bottom:0;">
  <?php $i = 1; ?>
  <?php 
foreach ($this->cart->contents() as $items):

 ?>
  <?php echo form_hidden($i . '[rowid]', $items['rowid']); ?> <?php echo form_hidden($i . '[id]', $items['id']); ?>
  <tr valign="top" style="height:42px;" >
    <td style="text-align:left" width="65%"><?php echo $items['name'];?>
      <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
      <p style="margin:0;">
        <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
        <?php if ($option_name == "Message") continue; ?>
        <strong class="pre-order-text"><?php echo $option_name; ?></strong> <?php echo $option_value; ?>
        <?php endforeach; ?>
      </p>
      <?php endif; ?>
    </td>
    <td style="text-align:left" width="15%" ><?php



        #echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5', 'class' => 'inputtext', 'style' => 'text-align:center;'));



        ?>
      <?php if(!array_key_exists('type', $items) || ($items['type'] != 'buy_one_get_one' && $items['type'] != 'free_gift')):?>
      <select name="<?php echo $i . '[qty]' ?>" id="<?php echo $i . '[qty]' ?>" size="1" onChange="changeQty()">
        <?php



            $MAX_UNIT = 10;



            $query = $this->db->query("SELECT in_stock, can_pre_order, on_sale, price, retail_price FROM product WHERE id = ? AND is_delete = 0 AND publish = 1", $items['id']);



            $check_in_stock_and_pre_order = $query->result();



            if (count($check_in_stock_and_pre_order) >= 1) {



                if ($check_in_stock_and_pre_order[0]->can_pre_order == 0) {



                    $MAX_UNIT = ($check_in_stock_and_pre_order[0]->in_stock <= 10) ? $check_in_stock_and_pre_order[0]->in_stock : 10;



                    if ($MAX_UNIT < 10) {



                        // $MAX_UNIT += $items['qty'];



                    }



                }



            }







            $Catalogs = $this->db->query("SELECT cid FROM product_rel_catalog WHERE pid = ?", $items['id'])->result();



            foreach ($Catalogs as $item) {



                if ($item->cid == '10009') {



                    $MAX_UNIT = 5;



                }



            }







            for ($j = 1; $j <= $MAX_UNIT; $j++) {



                printf("<option value='%s' %s>%s</option>", $j, ($items['qty'] == $j) ? "selected='selected'" : "", $j);



            }



            ?>
      </select>
      <?php endif; ?>
      <a style="font-weight:normal;color:#977778;" onClick="removeItem('<?php echo $items['rowid'] ?>')" href="#"



           class="remove">Remove</a> </td>
    <td style="text-align:center" width="10%"><?php if ($check_in_stock_and_pre_order[0]->on_sale == 1) { ?>
      <span



            style='text-decoration:line-through;color:red'>$<?php echo $check_in_stock_and_pre_order[0]->retail_price ?></span>
      <?php } ?>
      $<?php echo $items['price'] != 0 ? $this->cart->format_number($items['price']) : number_format(0, 2, '.', ','); ?> </td>
    <td style="text-align:right" width="10%"> $<?php echo $items['subtotal'] != 0 ? $this->cart->format_number($items['subtotal']) : number_format(0, 2, '.', ','); ?></td>
  </tr>
  <?php $i++; ?>
  <?php endforeach; ?>
</table>
