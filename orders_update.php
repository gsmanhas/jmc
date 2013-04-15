<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <?php $this->load->view('base/head'); ?>
    <script type="text/javascript" charset="utf-8">
        var base_url = '<?php echo base_url() ?>';
    </script>
    <script src="<?php echo base_url() ?>js/admin/blockUI.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url() ?>js/admin/editor_order.js" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>css/order.css" type="text/css" media="screen" title=""
          charset="utf-8">
    <script type="text/javascript" charset="utf-8">

        var MAX_LEN = <?php echo count($this->OrderList) + 1; ?>;

        function sendTracking() {
            if (confirm('Are you sure you want to send a shipping notification to the customer?')) {
                $.blockUI();
                jQuery.post(base_url + 'ajax/send_shipping_notify', {
                        "id": <?php echo $this->Order[0]->id ?>,
                        "track_number":jQuery("#track_number").val()
                    }, function (response) {
                        $.unblockUI({ fadeOut:800 });
                    }, "json"
                );
            }
        }

        function sendOrder() {
            if (confirm('Are you sure you want to send the invoice to the customer?')) {
                $.blockUI();
                jQuery.post(base_url + 'ajax/send_order_notify', {
                        "id": <?php echo $this->Order[0]->id ?>,
                        "track_number":jQuery("#track_number").val()
                    }, function (response) {
                        $.unblockUI({ fadeOut:800 });
                    }, "json"
                );
            }
        }

        function restore() {
            jQuery("#action").attr('value', 'restore');
            document.forms[0].action = '<?php echo base_url() ?>admin.php/orders/restore';
            jQuery("#frmMain").submit();
        }

        function printOrder() {
            window.open('/printer/' +<?php echo $this->Order[0]->id ?>);
        }

        function saveOrder() {
            jQuery("#frmMain").submit();
        }

        function goback() {
            jQuery("#action").attr('value', 'goback');
            document.forms[0].action = '<?php echo base_url() ?>admin.php/orders/search2';
            jQuery("#frmMain").submit();
        }
    </script>
    <style type="text/css">
        .hastable table {
            border-left: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .hastable tr td, .hastable thead th {
            border-left: 0;
            border-bottom: 0;
        }

        table td.var {
            color: #888;
            text-align: right;
            margin: 0;
            padding: 0;
            width: 35%;
        }

        table.inner {
            margin-bottom: 5px;
            border: 0;
        }

        table.inner td {
            padding: 5px 5px 0 3px;
        }

        table.inner input {
            width: 200px;
        }

        table#table_order_details input {
            width: 60px;
        }

        table.inner p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>

<div id="content">

</div>
<div id="header">
    <?php
    $this->load->view('base/account');
    # 載入 Menu
    $this->load->view('base/menu');
    ?>
</div>

<div id="page-wrapper">
<div id="main-wrapper">
<div id="main-content">

<div class="page-title ui-widget-content ui-corner-all">
    <h1><b>Order Details</b></h1>

    <div class="other">
        <div class="float-left"></div>
        <div class="button float-right">

            <a href="javascript:printOrder()" id="btn_send_order" class="btn ui-state-default ui-corner-all">
                <span class="ui-icon ui-icon-disk"></span>Print Invoice
            </a>

            <a href="javascript:sendOrder()" id="btn_send_order" class="btn ui-state-default ui-corner-all">
                <span class="ui-icon ui-icon-disk"></span>Email Invoice to Customer
            </a>

            <?php
            if (isset($_POST['report']) && $_POST['report'] == TRUE) {

            } else {
                ?>
                <a href="javascript:goback()" class="btn ui-state-default">
                    <span class="ui-icon ui-icon-search"></span>Go Back
                </a>
                <?php
            }
            ?>


            <?php
            if ($this->Order[0]->restore == 0 && ($this->Order[0]->order_state == 3 || $this->Order[0]->order_state == 5 || $this->Order[0]->order_state == 6)) {
                ?>
                <a href="javascript:restore();" id="btn_restore" class="btn ui-state-default ui-corner-all">
                    <span class="ui-icon ui-icon-disk"></span>Restore Inventory
                </a>
                <?php
            }
            ?>


            <a href="javascript:saveOrder();" id="btn_update" class="btn ui-state-default ui-corner-all">
                <span class="ui-icon ui-icon-disk"></span>Save
            </a>

            <a href="<?php echo base_url() ?>admin.php/orders" class="btn ui-state-default ui-corner-all">
                <span class="ui-icon ui-icon-minusthick"></span>Cancel
            </a>

        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php
if (isset($this->update_message) && !empty($this->update_message)) {
    ?>
<div class="response-msg inf ui-corner-all">
    <span>Information message</span><?php echo $this->update_message; ?>
</div>
    <?php
}
?>

<?php echo validation_errors('<div class="response-msg error ui-corner-all"><span>', '</span></div>'); ?>

<form method="post" action="<?php echo base_url() ?>admin.php/orders/update2" id="frmMain">
<div class="hastable">
<table border="0" style="margin-bottom:10px;">
    <tr>
        <td width="33%" style="border-right:1px dotted #ccc;vertical-align:top;">

            <table class="inner">
                <thead>
                <tr>
                    <th>
                        Customer
                    </th>
                    <th style="text-align:right;">
                        <a href="javascript:">&nbsp;</a>
                    </th>
                </tr>
                </thead>
                <tr>
                    <td class="var">First Name</td>
                    <td>
                        <p>
                            <input type="text" name="firstname" value="<?php echo $this->Order[0]->firstname ?>"
                                   id="firstname">
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="var">Last Name</td>
                    <td>
                        <p>
                            <input type="text" name="lastname" value="<?php echo $this->Order[0]->lastname ?>"
                                   id="lastname" size="10">
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="var">Email</td>
                    <td>
                        <p>
                            <input type="text" name="email" value="<?php echo $this->Order[0]->email ?>" id="email">
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="var">Phone</td>
                    <td>
                        <p>
                            <input type="text" name="phone" value="<?php echo $this->Order[0]->phone_number ?>"
                                   id="phone">
                        </p>
                    </td>
                </tr>
            </table>

        </td>
        <td width="33%" style="border-right:1px dotted #ccc;vertical-align:top;">
            <table class="inner">
                <thead>
                <tr>
                    <th colspan="2">
                        Order
                    </th>
                </tr>
                </thead>
                <tr>
                    <td class="var">
                        Order No.
                    </td>
                    <td>
                        <strong><?php echo $this->Order[0]->order_no ?></strong>
                    </td>
                </tr>
                <tr>
                    <td class="var">
                        Date
                    </td>
                    <td>
                        <p><?php echo $this->Order[0]->odate . ' ' . $this->Order[0]->oapm . ' ' . $this->Order[0]->otime ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="var">
                        Status
                    </td>
                    <td>
                        <select name="order_state" id="order_state">
                            <?php
                            foreach ($this->OrderState as $OrderState) {
                                $is_selected = "";
                                if ($OrderState->id == $this->Order[0]->order_state) {
                                    $is_selected = "selected=\"selected\"";
                                }
                                printf("<option value=\"%s\" %s >%s</option>", $OrderState->id, $is_selected, $OrderState->name);
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="var">Tracking No.</td>
                    <td>
                        <input type="text" name="track_number" value="<?php echo $this->Order[0]->track_number ?>"
                               id="track_number">
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="button" name="btnSendTrack" value="Email Notification to Customer"
                               id="btnSendTrack" onclick="sendTracking();" class="inputbutton" style="margin-top:2px;">
                    </td>
                </tr>
            </table>

        </td>
        <td width="33%">
            <table class="inner">
                <thead>
                <tr>
                    <th>
                        Notes
                    </th>
                </tr>
                </thead>
                <tr>
                    <td>
                        <div id="notes">
                            <ul>
                                <?php if (count($this->notes) > 0): ?>
                                <?php foreach ($this->notes as $note) : ?>
                                    <li class="btn ui-state-default ui-corner-all" style="position: relative;">
                                        <span class="ui-icon ui-icon-comment"></span>
                                        <i>Date:<?php echo ($note->created_at); ?>&nbsp;Created by:<?php echo $note->username ?></i>
                                        <p><?php echo $note->text?></p>
                                        <div style="position: absolute; top:0px; right: 0px;">
                                            <a href="/admin.php/orders/deletenote/<?php echo $note->id ?>" class="deletenote">x</a>
                                        </div>
                                    </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <br/>
                        <textarea id="note_text" style="width: 99%;"></textarea>
                        <br/>
                        <button id="add_note" id="<?php echo $this->Order[0]->id ?>"
                                class="btn ui-state-default ui-corner-all">
                            Add note
                        </button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table border="0" style="margin-bottom:10px;">
<tr>
<td width="33%" style="border-right:1px dotted #ccc;vertical-align:top;">
    <table class="inner">
        <thead>
        <tr>
            <th>
                Billing
            </th>
            <th style="text-align:right;">
                <a href="#">&nbsp;</a>
            </th>
        </tr>
        </thead>
        <tr>
            <td class="var">Billing Address</td>
            <td><p><input type="text" name="bill_address" value="<?php echo $this->Order[0]->bill_address ?>"
                          id="bill_address"></p></td>
        </tr>
        <tr>
            <td class="var">Billing City</td>
            <td><p><input type="text" name="bill_city" value="<?php echo $this->Order[0]->bill_city ?>"
                          id="bill_city"></p></td>
        </tr>
        <tr>
            <td class="var">Billing State123</td>
            <td>
                <p>
                    <select id="bill_state" name="bill_state" class="dropdown" style="width:195px;">
                        <option selected="selected" value="-1">Please Select</option>
                        <?php foreach ($this->continental as $state) { ?>
                        <?php
                        printf("<option value=\"%s\" %s %s >%s</option>", $state->id, ($state->id == $this->Order[0]->bill_state) ? "selected=\"selected\"" : "", $is_selected, $state->sharthand);
                        ?>
                        <?php } ?>
                    </select>
                </p>
            </td>
        </tr>
        <tr>
            <td class="var">Billing Zip</td>
            <td><p><input type="text" name="bill_zipcode" value="<?php echo $this->Order[0]->bill_zipcode ?>"
                          id="bill_zipcode"></p></td>
        </tr>
        <?php /*<tr>
                                            <td><input type="checkbox" style="width:20px;float:right;" <?php echo $this->Order[0]->shipping_same == 1 ? 'checked="checked"' : ''?> value="1" name="shipSame" id="shipSame"></td>
                                            <td>
                                                Shipping address same as billing
                                            </td>
                                        </tr>*/?>
    </table>
</td>

<td width="33%" style="border-right:1px dotted #ccc;vertical-align:top;">
    <table class="inner">
        <thead>
        <tr>
            <th>
                Shipping
            </th>
            <th style="text-align:right;">
                <a href="#">&nbsp;</a>
            </th>
        </tr>
        </thead>
        <tr>
            <td class="var">Shipping Address</td>
            <td>
                <?php
                $ship_address = $this->Order[0]->ship_address;
                // if ($this->Order[0]->shipping_same == 1) {
                // 	$ship_address = $this->Order[0]->bill_address;
                // }
                ?>
                <input type="text" name="ship_address" value="<?php echo $ship_address ?>" id="ship_address">
            </td>
        </tr>
        <tr>
            <td class="var">Shipping City</td>
            <td>
                <?php
                $ship_city = $this->Order[0]->ship_city;
                if ($this->Order[0]->shipping_same == 1) {
                    $ship_city = $this->Order[0]->bill_city;
                }
                ?>
                <input type="text" name="ship_city" value="<?php echo $ship_city ?>" id="ship_city">
            </td>
        </tr>
        <tr>
            <td class="var">Shipping State</td>
            <td>
                <select id="ship_state" name="ship_state" class="dropdown" style="width:195px;">
                    <option selected="selected" value="-1">Please Select</option>
                    <?php
					 foreach ($this->continental as $state) { 
					 
					?>
					
                    <?php
                    printf("<option value=\"%s\" %s %s >%s</option>", $state->id, ($state->id == $this->Order[0]->destination_id) ? "selected=\"selected\"" : "", $is_selected, $state->sharthand);
                    ?>
                    <?php } ?>
                </select>

            </td>
        </tr>
        <tr>
            <td class="var">Shipping Zip</td>
            <td>
                <?php
                $ship_zipcode = $this->Order[0]->ship_zipcode;
                if ($this->Order[0]->shipping_same == 1) {
                    $ship_zipcode = $this->Order[0]->bill_zipcode;
                }
                ?>
                <input type="text" name="ship_zipcode" value="<?php echo $ship_zipcode ?>" id="ship_zipcode">
            </td>
        </tr>
    </table>

</td>
<td width="33%" style="vertical-align:top;">
    <?php
    if ($this->Order[0]->payment_method == 1) {
        ?>
        <table class="inner">
            <thead>
            <tr>
                <th>
                    Credit Card
                </th>
                <th style="text-align:right;">
                    <!-- <a href="#">Edit</a> -->
                </th>
            </tr>
            </thead>
            <tr>
                <td class="var">Name on Card</td>
                <td><p><?php echo $this->Order[0]->name_on_card ?></p></td>
            </tr>
            <tr>
                <td class="var">Card Type</td>
                <td><p><?php echo $this->Order[0]->card_type ?></p></td>
            </tr>
            <tr>
                <td class="var">Card Number <br>(last 4-digit)</td>
                <td><p><?php echo substr($this->Order[0]->card_number, -4, 4) ?></p></td>
            </tr>
            <tr>
                <td class="var">Card Expiry</td>
                <td><p><?php echo $this->Order[0]->card_expiry_month ?>
                    /<?php echo $this->Order[0]->card_expiry_year ?></p></td>
            </tr>
        </table>
        <?php
    }
    ?>

    <?php if ($this->Order[0]->payment_method == 2) { ?>
    <table class="inner">
        <thead>
        <tr>
            <th>
                Paypal
            </th>
            <th style="text-align:right;">
            </th>
        </tr>
        </thead>
        <tr>
            <td class="var">Invoice Number</td>
            <td><p><?php echo $this->Order[0]->order_no ?></p></td>
        </tr>
    </table>
    <?php } ?>

    <table class="inner" style="display:none;">
        <thead>
        <tr>
            <th>
                Credit Card
            </th>
            <th style="text-align:right;color:#999;">
                <a href="#">Save</a>&nbsp;&nbsp;|&nbsp;
                <a href="#">Cancel</a>
            </th>
        </tr>
        </thead>
        <tr>
            <td class="var">Name on Card</td>
            <td><input type="text" name="" value="" id=""></td>
        </tr>
        <tr>
            <td class="var">Card Type</td>
            <td><input type="text" name="" value="" id=""></td>
        </tr>
        <tr>
            <td class="var">Card Number <br>(last 4-digit)</td>
            <td><input type="text" name="" value="" id=""></td>
        </tr>
        <tr>
            <td class="var">Card Expiry</td>
            <td><input type="text" name="" value="" id=""></td>
        </tr>
    </table>
</td>
</tr>
</table>

<!-- <table border="0" style="clear:both;">
							<tr>
								<th>Product Name</th>
								<th>Price</th>
								<th>Qty</th>
								<th colspan="2">Total</th>
							</tr>
							<tr>
								<td>
									<select name="sel_products" id="sel_products" size="1">
										<option value="0">Please Select</option>
										<?php
$Products = $this->db->query("SELECT id, retail_price, price, name, on_sale FROM `product` ORDER BY `name` ASC")->result();
foreach ($Products as $Product) {
    printf("<option value='%s' price='%s'>%s</option>", $Product->id, ($Product->on_sale == 1) ? $Product->price : $Product->retail_price, $Product->name);
}
?>
									</select>
								</td>
								<td><input type="text" name="txt_price" value="" id="txt_price" READONLY></td>
								<td><input type="text" name="txt_qty" value="1" id="txt_qty"></td>
								<td><input type="text" name="txt_total" value="" id="txt_total" READONLY></td>
								<td><a href="#" id="btn_addnew_product">Add a product</a></td>
							</tr>
						</table> -->

<table id="table_order_details" border="0" style="clear:both;">
    <thead>
    <tr>
        <th style="text-align:left;" colspan="1">Order Details</th>
        <th style="text-align:right;width:200px" colspan="5">
            <!-- <a href="#" id="btn_restore_to_inventory">Restore to inventory</a> -->
        </th>
    </tr>
    </thead>
    <tr>
        <td class="fieldlabel">Product</td>
        <td class="fieldlabel" style="text-align:center;width:60px;">Units</td>
        <td class="fieldlabel" style="text-align:center;width:60px;">Unit Price</td>
        <td class="fieldlabel" style="text-align:center;width:60px;">Total Price</td>
        <td class="fieldlabel" style="text-align:right;width:1%;"><!-- Return --></td>
        <td class="fieldlabel" style="text-align:center;width:1%;"><a href=""><!-- Remove --></a></td>
    </tr>

    <?php
    $subtotal = 0;
    ?>
    <?php foreach ($this->OrderList as $item) { ?>
    <?php
    if ($item->item_type == 'product' || $item->item_type == 'buy_one_get_one' || $item->item_type == 'free_gift') {
        $Product = $this->db->query("SELECT * FROM product WHERE id = ?", $item->pid)->result();
    } else {
        $Product = $this->db->query("SELECT * FROM order_voucher_details WHERE id = ?", $item->pid)->result();
    }
    ?>
    <tr valign="top" height="38px;">
        <td style="text-align:right" id="product_name_<?php echo $item->id ?>">
            <?php echo $item->item_type == 'buy_one_get_one' ? 'Free ': '' ?>
            <?php echo $item->item_type == 'free_gift' ? 'Gift ': '' ?>
            <?php echo ($item->item_type == 'product' || $item->item_type == 'buy_one_get_one' || $item->item_type == 'free_gift') ? $Product[0]->name : $Product[0]->title ?>
        </td>
        <td style="text-align:right" id="txt_product_qty_<?php echo $item->id ?>"
            name="txt_product_qty_<?php echo $item->id ?>">
            <span id="span_product_qty_<?php echo $item->id ?>"><?php echo $item->qty ?></span>
            <input type="hidden" id="product_qty_<?php echo $item->id ?>" name="product_qty_<?php echo $item->id ?>"
                   value="<?php echo $item->qty ?>">
            <input type="hidden" id="hid_product_qty_<?php echo $item->id ?>"
                   name="hid_product_qty_<?php echo $item->id ?>" value="<?php echo $item->qty + $item->is_return ?>">
        </td>
        <td style="text-align:right" id="txt_product_price_<?php echo $item->id ?>">
            <?php echo $item->price ?>
            <input type="hidden" id="product_price_<?php echo $item->id ?>" name="product_price_<?php echo $item->id ?>"
                   value="<?php echo $item->price ?>">
        </td>
        <td style="text-align:right" id="txt_product_total_price_<?php echo $item->id ?>">
            $<span id="span_product_price_<?php echo $item->id ?>"><?php echo $item->qty * $item->price ?></span>
            <input type="hidden" id="product_total_price_<?php echo $item->id ?>"
                   name="product_total_price_<?php echo $item->id ?>" value="<?php echo $item->qty * $item->price ?>"
                   class="hid_product_total_price">
        </td>
        <td style="text-align:right">
            <!-- <select class="product_return" name="product_return_<?php echo $item->id; ?>" id="product_return_<?php echo $item->id; ?>" size="1" ndx="<?php echo $item->id; ?>" onchange="funReturn(this, <?php echo $item->id; ?>);">
			                    		<option value="0">0</option>
										<?php
                for ($i = 1; $i <= ($item->qty + $item->is_return); $i++) {
                    $selected = '';
                    if ($item->is_return == $i) {
                        $selected = 'selected="selected"';
                    }
                    printf("<option value='%s' %s >%s</option>", $i, $selected, $i);
                }
                ?>
			                    	</select> -->
        </td>
        <td style="text-align:right">
            <!-- <a href="javascript:removeItem(<?php echo $item->id ?>);" id="btn_remove_item_<?php echo $item->id ?>" alt="1">Remove</a>
									<input type="hidden" name="product_remove_<?php echo $item->id ?>" value="<?php echo ($item->is_delete == 1) ? "1" : "0" ?>" id="product_remove_<?php echo $item->id ?>"> -->
        </td>

    </tr>

    <?php
    $subtotal += ($item->qty * $item->price);
    ?>

    <?php } ?>

    <tr>
        <td colspan="6">
            <div style="width:100%;height:1px;;border-bottom:1px dotted #dadada;">&nbsp;</div>
        </td>
    </tr>

    <tr>
        <td class="fieldlabel" style="text-align:right;">Subtotal</td>
        <td colspan="5" style="text-align:left" id="txt_subtotal">
            <?php echo $subtotal ?>
        </td>
        <input type="hidden" value="<?php echo $subtotal ?>" id="hid_subtotal">
    </tr>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Destination State</td>
        <td colspan="5">
            <select name="destination_state" id="destination_state" size="1">
                <option value="0" tax="0">Select a state</option>
                <?php foreach ($this->continental as $state): ?>

                <?php
                $is_selected = '';
                if ($state->id == $this->Order[0]->destination_id) {
                    $is_selected = "selected=\"selected\"";
                }
                printf("<option value=\"%s\" tax=\"%s\" %s >%s</option>", $state->id, $state->tax_rate, $is_selected, $state->tax_code);
                ?>

                <?php endforeach ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Total Tax</td>
        <td colspan="5">
            <span id="txt_product_tax"><?php echo $this->Order[0]->product_tax ?></span>
            <input type="hidden" name="hid_product_tax" value="<?php echo $this->Order[0]->product_tax ?>"
                   id="hid_product_tax">
            <input type="hidden" name="hid_taxrate" value="<?php echo $this->Order[0]->tax_rate ?>" id="hid_taxrate">
        </td>
    </tr>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Shipping Option</td>
        <td colspan="5">
            <select name="shipping_method" id="shipping_method" size="1">
                <option value="-1">Choose Shipping Option</option>
                <?php foreach ($this->ShippingMethod as $SP): ?>
                <option value="<?php echo $SP->id ?>"
                        price="<?php echo $SP->price ?>" <?php echo ($SP->id == $this->Order[0]->shipping_id) ? "selected='selected'" : "" ?> ><?php echo $SP->name ?>
                    &nbsp;&nbsp;<?php echo $SP->price ?></option>
                <?php endforeach ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Shipping</td>
        <td colspan="5" style="text-align:left">
            <input type="text" name="calculate_shipping" value="<?php echo $this->Order[0]->calculate_shipping ?>"
                   id="calculate_shipping">
        </td>
    </tr>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Discount Code</td>
        <td colspan="5">
            <select name="promo" id="promo" size="1">
                <option value="-1">Choose Promo Discount</option>
                <?php foreach ($this->DiscountCode as $discount): ?>
                <option
                    value="<?php echo $discount->id ?>" <?php echo ($discount->id == $this->Order[0]->discount_id) ? "selected='selected'" : "" ?>><?php echo $discount->code ?></option>
                <?php endforeach ?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Discount</td>
        <td colspan="5" style="text-align:left; color: green">
            <span id="discount"><?php echo $this->Order[0]->discount ?></span>
            <span
                id="discount_freeshipping"><?php echo ($this->Order[0]->promo_free_shipping == 1) ? "And Free Shipping." : "" ?></span>
            <input type="hidden" name="hid_discount" value="<?php echo $this->Order[0]->discount ?>" id="hid_discount">
            <input type="hidden" name="hid_discount_can_freeshipping"
                   value="<?php echo $this->Order[0]->promo_free_shipping ?>" id="hid_discount_can_freeshipping">
            <input type="hidden" name="hid_freeshipping" value="<?php echo $this->Order[0]->freeshipping ?>"
                   id="hid_freeshipping">
        </td>
    </tr>
    <?php if ($this->Voucher != null) : ?>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Voucher</td>
        <td colspan="5" style="text-align:left; color: green">
            <span>-$ <?php echo number_format($this->Voucher->amount, 2) ?></span>
        </td>
    </tr>
    <?php endif; ?>
    <?php
    $X = 0;
    if ($this->Order[0]->shipping_id == 1) {
        if ($this->Order[0]->promo_free_shipping == 1 || $this->Order[0]->freeshipping == 1) {
            $X = 7.95;
            ?>
            <tr>
                <td class="fieldlabel" style="text-align:right;">&nbsp;</td>
                <td colspan="5" style="text-align:left; color: green">-$7.95 Free Shipping</td>
            </tr>
            <?php
        }
    } ?>
    <tr>
        <td class="fieldlabel" style="text-align:right;">Grand Total</td>
        <td colspan="5" style="text-align:left"><input type="text" name="amount"
                                                       value="<?php echo $this->Order[0]->amount ?>" id="amount"></td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
</table>
</div>

<input type="hidden" name="user_id" value="<?php echo $this->Order[0]->user_id ?>" id="user_id">
<input type="hidden" name="method" value="update" id="method">
<input type="hidden" name="id" value="<?php echo $this->Order[0]->id ?>" id="id">
<input type="hidden" name="last_query" value="<?php echo $this->LAST_QUERY ?>" id="last_query">

</form>


</div>
</div>
</div>
</body>
</html>









