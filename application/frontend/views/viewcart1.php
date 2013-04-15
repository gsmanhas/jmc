<?php

$bol_SHOW_FREESHIPPING = FALSE;



if ($this->session->userdata('DiscountCode') && isset($this->ShoppingCart->discount_sub_total)) {

    if ($this->Promo_FreeShipping == 1) {

        $bol_SHOW_FREESHIPPING = TRUE;

    }

}



//	stdClass

$DiscountCode = $this->session->userdata('DiscountCode');

$VoucherCode = $this->session->userdata('VoucherCode');

?>

<!DOCTYPE HTML>

<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <meta name="Author" content="Six Spoke Media"/>

    <meta name="viewport" content="width=1024"/>

    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9"/>

    <title>Josie Maran Cosmetics</title>

    <meta name="Keywords" content=""/>

    <meta name="Description" content=""/>

    <?php $this->load->view('base/head') ?>

    <script type="text/javascript" charset="utf-8">

        var base_url = '<?php echo secure_base_url() ?>';

    </script>

    <script type="text/javascript" charset="utf-8" src="/js/viewcart.js"></script>

    <script type="text/javascript" charset="utf-8" src="/js/jquery.qtip.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/css/jquery.qtip.min.css"/>

    <script type="text/javascript">

        jQuery(function () {

            var shipping_help_text = 'For orders over $25, please select USPS Flat Rate $4.95 to recieve free shipping.\

            <br/>For orders containing only eGift Cards upon check-out, select the $4.95 shipping option and provide a valid shipping address;\

            however please note that eGift Cards will only be sent via email to the \'Recipient\'.'

            jQuery("#shipping-help").qtip({

                content:shipping_help_text,

                position:{

                    my:"left center",

                    at:"right center",

                    viewport:$(window)

                },

                style:{



                }

            })

        });

    </script>

    <style type="text/css">

        a.remove:hover {

            color: #493838;

            text-decoration: none;

        }

    </style>

    <?php $this->load->view('base/ga') ?>

</head>

<body>

<div id="header">

    <div id="logo">

        <a href="/">Josie Maran, Luxury with a Conscience</a>

    </div>

    <?php $this->load->view('base/utilities') ?>

</div>



<div id="main">



<div id="topnav">

    <?php $this->load->view('base/menu') ?>

</div>



<div id="pagetitle"><h1>View Cart</h1></div>



<?php

if (isset($this->ErrorMessage) && (!empty($this->ErrorMessage))) {

    ?>

<div class="errormessage">

    <p class="error"><?php echo $this->ErrorMessage; ?></p>

</div>

    <?php

}

?>



<div>

<form action="/myshoppingcart" method="post" accept-charset="utf-8" id="frmMain" name="frmMain">

<table border="0" cellspacing="5" cellpadding="5" width="100%" style="font-size:1em;margin-bottom:0;">

<tr style="color:#493838;background-color:#f3e4e9;text-shadow: 0 1px 0 #fff;">

    <th style="text-align:left" width="65%">Product</th>

    <th style="text-align:left" width="15%">Unit</th>

    <th style="text-align:center" width="10%">Unit Price</th>

    <th style="text-align:right;" width="10%">Total Price</th>

</tr>

<tr>

    <td colspan="4">

        <div style="width:100%;height:1px;margin-bottom:10px;">&nbsp;</div>

    </td>

</tr>

<?php $i = 1; ?>

<?php foreach ($this->cart->contents() as $items): ?>

    <?php echo form_hidden($i . '[rowid]', $items['rowid']); ?>

    <?php echo form_hidden($i . '[id]', $items['id']); ?>

<tr valign="top" style="height:42px;">

    <td style="text-align:left">

        <?php echo $items['name'];?>

        <?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>

        <p style="margin:0;">

            <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

            <?php if ($option_name == "Message") continue; ?>

            <strong class="pre-order-text"><?php echo $option_name; ?></strong> <?php echo $option_value; ?>

            <?php endforeach; ?>

        </p>

        <?php endif; ?>

    </td>

    <td style="text-align:left">

        <?php

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

           class="remove">Remove</a>

    </td>

    <td style="text-align:center">

        <?php if ($check_in_stock_and_pre_order[0]->on_sale == 1) { ?>

        <span

            style='text-decoration:line-through;color:red'>$<?php echo $check_in_stock_and_pre_order[0]->retail_price ?></span>

        <?php } ?>

        $<?php echo $items['price'] != 0 ? $this->cart->format_number($items['price']) : number_format(0, 2, '.', ','); ?>

    </td>

    <td style="text-align:right">$<?php echo $items['subtotal'] != 0 ? $this->cart->format_number($items['subtotal']) : number_format(0, 2, '.', ','); ?></td>

</tr>







    <?php $i++; ?>

    <?php endforeach; ?>



<tr>

    <td>&nbsp;</td>

    <td colspan="3">

        <div style="width:100%;height:1px;border-bottom:1px solid #F3E4E9;margin-bottom:15px;margin-top:15px;">

            &nbsp;</div>

    </td>

</tr>



<tr>

    <td class="cartvar">Subtotal</td>

    <td colspan="3" class="cartvalue">$<?php echo $this->cart->format_number($this->cart->total()); ?></td>

</tr>

<tr>

    <td class="cartvar">Shipping Destination Country</td>

    <td colspan="3" class="cartvalue">

        <select name="country" id="country" size="1" class="dropdown">

            <option

                value="1" <?php echo isset($_POST['country']) && $_POST['country'] == 1 ? 'selected="selected"' : '';?> >

                US

            </option>

            <option

                value="40" <?php echo isset($_POST['country']) && $_POST['country'] == 40 ? 'selected="selected"' : '';?> >

                Canada

            </option>

        </select>

    </td>

</tr>

<tr>

    <td class="cartvar">Shipping Destination State</td>

    <td colspan="3" class="cartvalue">

        <select name="state" id="state" size="1" class="dropdown">

            <option value="-1">Please select</option>

            <?php

            foreach ($this->ListDestinationState as $state) {

                $is_selected = '';

                if ($this->session->userdata('DestinationState')) {

                    $ds = $this->session->userdata('DestinationState');

                    if ($state->id == $ds[0]['id']) {

                        $is_selected = "selected=\"selected\"";

                    }

                }

                printf("<option value=\"%s\" %s>%s</option>", $state->id, $is_selected, $state->tax_code);

            }

            ?>

        </select>

    </td>

</tr>

<tr>

    <td class="cartvar">Shipping Option</td>

    <td colspan="3" class="cartvalue">

        <select name="shipping_method" id="shipping_method" size="1" class="dropdown">

            <option value='-1'>Please select</option>

            <?php

            $FREE_SHIPPING = ($this->session->userdata('FreeShipping') ? $this->session->userdata('FreeShipping') : 0);

            //	總金額是否大於 Free Shipping 2 的規則

            $IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);



            $ds = $this->session->userdata('DestinationState');



            $IS_VOUCHER_ONLY = true;



            foreach ($this->cart->contents() as $item) {

                var_dump($item);

                if (!array_key_exists('type', $item)) {

                    $IS_VOUCHER_ONLY = false;

                    break;

                }

            }



            if ($IS_VOUCHER_ONLY) {

                $is_selected = '';



                if ($this->session->userdata('ShippingOptions')) {

                    $sp = $this->session->userdata('ShippingOptions');

                    if (99 == $sp[0]['id']) {

                        $is_selected = "selected=\"selected\"";

                    }

                }



                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;</option>",

                    '99', $is_selected, $this->OnlineShipping->name

                );

            } else {



                foreach ($this->ListShippingMethod as $method) {

                    $is_selected = '';



                    if ($this->session->userdata('ShippingOptions')) {

                        $sp = $this->session->userdata('ShippingOptions');

                        if ($method->id == $sp[0]['id']) {

                            $is_selected = "selected=\"selected\"";

                        }

                    }



                    if (is_array($ds) && count($ds) >= 1) {

                        //	這幾個 ID 所對應的 State 是不可以出現 UPS 2 Day.

                        if ($ds[0]['id'] == 2 || $ds[0]['id'] == 21 || $ds[0]['id'] == 61 || $ds[0]['id'] == 79 || $ds[0]['id'] == 80) {

                            if ($method->id != 2 && $method->id != 1) {

                                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                    $method->id, $is_selected, $method->name, $method->price

                                );

                            }

                        } else {

                            if ($FREE_SHIPPING == 1 || $IS_FREE_SHIPPING2 == 1 || $bol_SHOW_FREESHIPPING == TRUE) {



                                /**

                                 * 現在又要開放 UPS 2 Day 的規則                                             *

                                 */



                                // if ($method->id != 2) {

                                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                    $method->id, $is_selected, $method->name, $method->price

                                );

                                // }



                            } else {

                                printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                    $method->id, $is_selected, $method->name, $method->price

                                );

                            }

                        }

                    } else {

                        if ($FREE_SHIPPING == 1 || $IS_FREE_SHIPPING2 == 1 || $bol_SHOW_FREESHIPPING == TRUE) {

                            // if ($method->id != 2) {

                            printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                $method->id, $is_selected, $method->name, $method->price

                            );

                            // }

                        } else {

                            printf("<option value=\"%s\" %s>%s&nbsp;&nbsp;&nbsp;%s</option>",

                                $method->id, $is_selected, $method->name, $method->price

                            );

                        }

                    }



                }

            }



            ?>

        </select>

        &nbsp;

        <!--<span class="inputbutton" id="shipping-help">?</span>-->

    </td>

</tr>



<!-- <tr>

                <td class="cartvar">Shipping Fee</td>

                <td colspan="3" class="cartvalue">

                    $<?php echo $this->CalculateShipping; ?>

                </td>

            </tr> -->

<?php

$IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);



if ($IS_FREE_SHIPPING2 == TRUE) :

    if (isset($_POST['shipping_method']) && $_POST['shipping_method'] > 2 && $_POST['shipping_method'] != 99) {

        ?>

    <tr>

        <td>&nbsp;</td>

        <td colspan="3" style="text-align:left; color: green">

            -&#36;4.95 Free Shipping

        </td>

    </tr>



        <?php

    }

endif; ?>

<tr>

    <td class="cartvar">Is it a PO BOX?</td>

    <td colspan="3" class="cartvalue">

        <input type="checkbox" name="pobox" value="pobox"

               id="pobox" <?php echo isset($_POST['pobox']) ? 'checked="checked"' : ''; ?> >&nbsp;<label

        for="pobox">Yes</label>

    </td>

</tr>

<tr>

    <td class="cartvar">Total Tax</td>

    <td colspan="3" class="cartvalue">$<?php echo $this->ProductTax; ?></td>

</tr>

<?php if ($this->session->userdata('VoucherCode') && isset($this->ShoppingCart->voucher_sub_total)) { ?>

<tr>

    <td>&nbsp;</td>

    <td colspan="3" style="text-align:left; color:green">

        Code: "<?php echo $VoucherCode[0]->code ?>"<br>

        Gift Card / Voucher Amount: -$<?php echo number_format($this->ShoppingCart->voucher_sub_total, 2) ?>&nbsp;&nbsp;

        <input type="hidden" name="vouchercodeInput" value="<?php echo $this->input->post('vouchercodeInput') ?>"

               id="vouchercodeInput" class="inputtext">

        <input type="submit" name="clear_voucher" value="Reset" id="clear_voucher" class="inputbutton">

        <?php if ($this->session->userdata('VoucherBalance')) : ?><br/>(Remaining balance

        $<?php echo $this->session->userdata('VoucherBalance') ?>) <?php endif;?>



    </td>

</tr>

    <?php } else { ?>
	<?php if ($this->session->userdata('DiscountCode') && isset($this->ShoppingCart->discount_sub_total)) {}else{ ?>
<tr>

    <td class="cartvar">Enter Gift Card / Voucher Code</td>

    <td colspan="3" class="cartvalue">

        <input type="text" name="vouchercodeInput" value="<?php echo set_value('vouchercodeInput') ?>"

               id="vouchercodeInput" class="inputtext">

        <input type="submit" name="btnvoucher" value="Update" id="btnvoucher" class="inputbutton">

    </td>

</tr>

    <?php } } ?>

<?php if ($this->session->userdata('DiscountCode') && isset($this->ShoppingCart->discount_sub_total)) { ?>

<tr>

    <td>&nbsp;</td>

    <td colspan="3" style="text-align:left; color:green">

        <?php if ($this->Promo_FreeShipping == 1): ?>

        <?php

        if ($this->ShoppingCart->discount_sub_total <= 0) {

            ?>

            Code: "<?php echo $DiscountCode[0]->code ?>"<br>

            <?php if (isset($_POST['shipping_method']) && $_POST['shipping_method'] <= 1) { ?>

                Discount: -$7.95 Free Shipping

                <?php } ?>

            <?php } else { ?>

            Code: "<?php echo $DiscountCode[0]->code ?>"<br>

            Discount: -$<?php echo number_format($this->ShoppingCart->discount_sub_total, 2) ?>

            <?php if (isset($_POST['shipping_method']) && $_POST['shipping_method'] <= 1) { ?>

                &nbsp;&nbsp;(Plus -$7.95 Free Shipping!)

                <?php } ?>

            <?php

        }

        ?>

        <?php else: ?>

        Code: "<?php echo $DiscountCode[0]->code ?>"<br>

        <?php if($DiscountCode[0]->discount_type != 4 && $DiscountCode[0]->discount_type != 5){?>

        Discount: -$<?php echo number_format($this->ShoppingCart->discount_sub_total, 2) ?>&nbsp;&nbsp;



        <?php }?>

        <?php endif;?>

        <input type="hidden" name="discountcodeInput" value="<?php echo $this->input->post('discountcodeInput') ?>"

               id="discountcodeInput" class="inputtext">

        <input type="submit" name="clear_promo" value="Reset" id="clear_promo" class="inputbutton">

    </td>

</tr>

    <?php } else { ?>

   <?php if ($this->session->userdata('VoucherCode') && isset($this->ShoppingCart->voucher_sub_total)) {}else{ ?> 

<tr>

    <td class="cartvar">Enter Promo Code</td>

    <td colspan="3" class="cartvalue">

        <input type="text" name="discountcodeInput" value="<?php echo set_value('discountcodeInput') ?>"

               id="discountcodeInput" class="inputtext">

        <input type="submit" name="btnpromo" value="Update" id="btnpromo" class="inputbutton">

    </td>

</tr>

    <?php } } ?>



<tr>

    <td class="cartvar">Grand Total</td>

    <td colspan="3" class="cartvalue">

        $<?php echo $this->Sum ?>

    </td>

</tr>



<tr>

    <td>&nbsp;</td>

    <td colspan="3" class="cartvalue">

        <?php #echo form_submit('', 'Update your Cart'); ?>

        <input type="submit" name="btnClear" value="Clear Your Cart" id="btnClear" class="inputbutton">

        <input type="submit" name="btnSaveOrder" value="Check Out" id="btnSaveOrder" class="inputbutton">

    </td>

</tr>

</table>





<input type="hidden" name="method" value="update" id="method">

<input type="hidden" name="rowid" value="0" id="rowid">





</div>



</div>

<?php $this->load->view('base/footer_cart'); ?>

<?php //$this->load->view('base/facebook') ?>





</body>

</html>