var _Product = {
    "sayHey":function () {
        console.log(_Product.version);
    },
    "version":"1.0"
};

var NewProduct = new Array();

jQuery(document).ready(function () {

    // _Product.sayHey(1000);


    jQuery("#destination_state").change(function () {

        // console.log(jQuery(this).val());
        // console.log(jQuery(this).attr('tax'));
        var bol = false;
        var SubTotal = jQuery("#hid_subtotal").val();

        jQuery(this).children().each(function (index, value) {
            if (jQuery(this).attr('selected')) {
                if (jQuery(this).attr('tax') > 0) {
                    // console.log(jQuery(this).attr('tax') / 100);
                    var tax = jQuery(this).attr('tax') / 100;
                    // console.log(SubTotal);
                    var SubTotal_and_Tax = SubTotal * tax;
                    // console.log(SubTotal_and_Tax.toFixed(2));

                    jQuery("#txt_product_tax").text(SubTotal_and_Tax.toFixed(2));
                    jQuery("#hid_product_tax").val(SubTotal_and_Tax.toFixed(2));
                    bol = true;
                }
            }
        });

        if (bol == false) {
            jQuery("#txt_product_tax").text(0);
            jQuery("#hid_product_tax").val(0);
        }

        amount();


    });

    jQuery("#promo").change(function () {

    });

    jQuery("#sel_products").change(function () {

        // console.log(jQuery(this).text());

        jQuery(this).children().each(function () {
            // console.log(jQuery(this));
            if (jQuery(this).attr('selected')) {
                // console.log(jQuery(this).val());
                // console.log(jQuery(this).attr('price'));
                if (jQuery(this).val() == 0) {
                    jQuery("#txt_price").val(0);
                    jQuery("#txt_total").val(0);
                } else {
                    jQuery("#txt_price").val(jQuery(this).attr('price'));
                    jQuery("#txt_total").val(jQuery(this).attr('price') * jQuery("#txt_qty").val());
                }
            }
        });

    });

    jQuery("#txt_price").focus(function () {
        jQuery(this.select())
    });
    jQuery("#txt_qty").focus(function () {
        jQuery(this.select())
    });

    jQuery("#txt_price").blur(function () {
        jQuery("#txt_total").val(jQuery("#txt_price").val() * jQuery("#txt_qty").val());
    });

    jQuery("#txt_qty").blur(function () {
        jQuery("#txt_total").val(jQuery("#txt_price").val() * jQuery("#txt_qty").val());
    });

    jQuery("#btn_restore_to_inventory").click(function () {

        if (window.confirm('確定將這筆資料回復到庫存嗎?')) {
            alert(0);
        }

        return false;

    });

    // jQuery("#new_item").wrap("<tr><td>112233</td></tr>");

    jQuery("#table_order_details tbody").children().each(function (index, value) {
        // console.log(value);
    });

    // console.log()

    // jQuery("#new_item").wrap("<tr><td colspan='6'>Hello</td></tr>");


    jQuery("#btn_addnew_product").click(function () {

        // jQuery("#table_order_details tbody").children().eq(3).insertBefore("<tr><td colspan='6'>Hello</td></tr>");

        if (jQuery("#sel_products :selected").val() <= 0) {
            alert('Please Select a Product');
            return false;
        }

        jQuery("#table_order_details tr:eq(" + MAX_LEN + ")").after(
            // "<tr><td colspan='6'><a href='#' onclick='jQuery(this).parent().parent().remove();return false'>Hello</a></td></tr>"

            "<tr valign='top' height='38px'>" +

                "<td id='product_name_' style='text-align:right'>" + jQuery("#sel_products :selected").text() + "<input type='hidden' name='new_product_id[]' value='" + jQuery("#sel_products").val() + "' id=''></td>" +
                "<td style='text-align:right'>" + jQuery("#txt_qty").val() + "<input name='new_product_qty[]' type='hidden' value='" + jQuery("#txt_qty").val() + "' id=''></td>" +
                "<td style='text-align:right'>" + jQuery("#txt_price").val() + "<input name='new_product_price[]' type='hidden' value='" + jQuery("#txt_price").val() + "' id=''></td>" +
                "<td style='text-align:right'>$" + jQuery("#txt_total").val() + "<input name='new_product_subtotal[]' type='hidden' value='" + jQuery("#txt_total").val() + "' id=''></td>" +
                "<td style='text-align:right'>&nbsp;</td>" +
                "<td style='text-align:right'><a href='#' onclick='funRemove(this, " + jQuery("#txt_total").val() + ");return false'>Remove</a></td>" +
                "</tr>"
        );

        NewProduct.push(jQuery("#sel_products").val());

        var total = jQuery("#hid_subtotal").val() * 1 + jQuery("#txt_total").val() * 1;
        jQuery("#hid_subtotal").val(total);
        jQuery("#txt_subtotal").text(total);

        // console.log(jQuery("#table_order_details tbody").children().eq(3).html());

        amount();


        return false;

    });


    jQuery("#shipping_method").change(function () {
        jQuery(this).children().each(function (index, value) {
            if (jQuery(this).attr('selected')) {
                if (index == 0) {
                    jQuery("#calculate_shipping").val(0);
                } else {
                    jQuery("#calculate_shipping").val(jQuery(this).attr('price'));
                }
            }
        });
        amount();
    });

    jQuery(".product_return").change(function () {

        var ndx = jQuery(this).attr('ndx');

        var ObjName = '#btn_remove_item_' + ndx;
        var PName = '#product_name_' + ndx;
        var TxtPUnit = '#txt_product_qty_' + ndx;
        var TxtPPrice = '#txt_product_price_' + ndx;
        var TxtPTotal = '#txt_product_total_price_' + ndx;
        var PUnit = '#product_qty_' + ndx;
        var PPrice = '#product_price_' + ndx;
        var PTotal = '#product_total_price_' + ndx;
        var PReturn = '#product_return_' + ndx;
        var PDelete = '#product_remove_' + ndx;

    });

    jQuery("#add_note").click(function (e) {
        e.preventDefault();
        var text = jQuery.trim(jQuery("#note_text").val());
        if (text != "") {
            var order_id = jQuery(this).attr('oid');
            $.post("/admin.php/orders/addnote", {order_id:order_id, text:text},
                function (msg) {
                    if(msg.success) {
                        jQuery("#notes ul").append('<li class="btn ui-state-default ui-corner-all" style="position: relative;">\
                                                                <span class="ui-icon ui-icon-comment"></span>\
                                                                <i>Date:'+msg.date+'&nbsp;Created by:'+msg.user+'</i>\
                                                                <p>'+text+'</p>\
                                                                <div style="position: absolute; top:0px; right: 0px;">\
                                                                    <a href="/admin.php/orders/deletenote/'+msg.id+'" class="deletenote">x</a>\
                                                                </div>\
                                                            </li>');
                        jQuery("#note_text").val('');
                    }
                }
            );
        }
    });

    jQuery("a.deletenote").live("click", function(e) {
        e.preventDefault();
        if(confirm("Confirm to delete note?")) {
            var $el = jQuery(this);
            jQuery.get($el.attr("href"), function(msg) {
                if(msg.success) {
                    $el.parent().parent().remove();
                }
            });
        }
    });
});

function amount() {

    // jQuery("#destination_state").change();

    var subtotal = jQuery("#hid_subtotal").val() * 1;
    var shipping = jQuery("#calculate_shipping").val() * 1;
    var tax = jQuery("#hid_product_tax").val() * 1;
    var amount = subtotal + shipping + tax;
    jQuery("#amount").val(amount.toFixed(2));
}

function IsNumeric(input) {
    return (input - 0) == input && input.length > 0;
}

function funRemove(self, price) {

    var total = jQuery("#hid_subtotal").val() * 1 - price * 1;
    jQuery("#hid_subtotal").val(total);
    jQuery("#txt_subtotal").text(total);
    jQuery(self).parent().parent().remove();

    amount();

    return false;
}

function removeItem(ndx) {

    // console.log(ndx);
    // console.log(jQuery(obj));
    //
    var ObjName = '#btn_remove_item_' + ndx;
    var PName = '#product_name_' + ndx;

    var TxtPUnit = '#txt_product_qty_' + ndx;
    var TxtPPrice = '#txt_product_price_' + ndx;
    var TxtPTotal = '#txt_product_total_price_' + ndx;

    var PUnit = '#product_qty_' + ndx;
    var PPrice = '#product_price_' + ndx;
    var PTotal = '#product_total_price_' + ndx;

    var PReturn = '#product_return_' + ndx;

    var PDelete = '#product_remove_' + ndx;


    // console.log(jQuery(ObjName).html());

    if (jQuery(ObjName).attr('alt') == '0') {

        jQuery(ObjName).attr('alt', '1');
        jQuery(PDelete).val(0);
        jQuery(ObjName).html('Remove');

        jQuery(PName).css({"text-decoration":""});
        jQuery(TxtPUnit).css({"text-decoration":""});
        jQuery(TxtPPrice).css({"text-decoration":""});
        jQuery(TxtPTotal).css({"text-decoration":""});

        jQuery(PReturn).children().each(function (index, val) {
            if (jQuery(this).val() == 0) {
                jQuery(this).attr('selected', 'true');
            }
        });

        var SubTotal = parseFloat(jQuery("#hid_subtotal").val()).toFixed(2);
        var _subTotal = parseFloat(jQuery(PTotal).val()).toFixed(2);

        // console.log(SubTotal);
        // console.log(_subTotal);
        // console.log(SubTotal + _subTotal);

        var curr_SubTotal = (SubTotal * 1 + _subTotal * 1);

        jQuery("#hid_subtotal").val(curr_SubTotal);
        jQuery("#txt_subtotal").text(curr_SubTotal);

    } else if (jQuery(ObjName).attr('alt') == '1') {

        jQuery(ObjName).attr('alt', '0');
        jQuery(PDelete).val(1);
        jQuery(ObjName).html('Undo');

        jQuery(PName).css({"text-decoration":"line-through"});
        jQuery(TxtPUnit).css({"text-decoration":"line-through"});
        jQuery(TxtPPrice).css({"text-decoration":"line-through"});
        jQuery(TxtPTotal).css({"text-decoration":"line-through"});

        jQuery(PReturn).children().each(function (index, val) {
            if (jQuery(this).val() == jQuery(PUnit).val()) {
                jQuery(this).attr('selected', 'true');
            }
        });

        var SubTotal = parseFloat(jQuery("#hid_subtotal").val()).toFixed(2);
        var _subTotal = parseFloat(jQuery(PTotal).val()).toFixed(2);

        // console.log(SubTotal);
        // console.log(_subTotal);
        // console.log(SubTotal - _subTotal);

        var curr_SubTotal = SubTotal - _subTotal;

        jQuery("#hid_subtotal").val(curr_SubTotal);
        jQuery("#txt_subtotal").text(curr_SubTotal);

    }

    amount();

    // console.log(jQuery(ObjName).attr('alt'));

}

function funReturn(self, ndx) {
    // console.log(jQuery(self));
    // console.log(jQuery(self).val());

    var ObjName = '#btn_remove_item_' + ndx;
    var PName = '#product_name_' + ndx;
    var TxtPUnit = '#txt_product_qty_' + ndx;
    var TxtPPrice = '#txt_product_price_' + ndx;
    var TxtPTotal = '#txt_product_total_price_' + ndx;
    var PUnit = '#product_qty_' + ndx;
    var PPrice = '#product_price_' + ndx;
    var PTotal = '#product_total_price_' + ndx;
    var PReturn = '#product_return_' + ndx;
    var PDelete = '#product_remove_' + ndx;

    // var TxtPUnit   = '#txt_product_qty_'  + ndx;
    var SpanText = '#span_product_qty_' + ndx;
    var SpanPrice = '#span_product_price_' + ndx;

    // var PUnit      = '#product_qty_'      + ndx;
    var HidUnit = '#hid_product_qty_' + ndx;

    var origin_qty = jQuery(HidUnit).val();
	
	if(jQuery(self).val() < origin_qty ) {
    	var qty = origin_qty - jQuery(self).val();
	}else {
		var qty = jQuery(self).val() - origin_qty;
	}
	
	if(jQuery(self).val() == origin_qty ) {
			var qty = origin_qty;
	}
	
	var qty = jQuery(self).val();
	
    // console.log(qty);

    jQuery(SpanText).text(qty);
    jQuery(PUnit).val(qty);

    var SubTotal = parseFloat(jQuery("#hid_subtotal").val()).toFixed(2);
    var _subTotal = parseFloat(jQuery(PTotal).val()).toFixed(2);

    // console.log(SubTotal);
    // console.log(_subTotal);
    // console.log(SubTotal + _subTotal);

    // var curr_SubTotal = (SubTotal * 1 - _subTotal * 1);


    // console.log(qty * jQuery(PPrice).val());


    jQuery(SpanPrice).text(qty * jQuery(PPrice).val());
    jQuery(PTotal).val(qty * jQuery(PPrice).val());
    // TxtPTotal
    var SubTotal = 0;
    jQuery(".hid_product_total_price").each(function (index, value) {
        SubTotal += jQuery(this).val() * 1;
    });
    // console.log(SubTotal);

    jQuery("#hid_subtotal").val(SubTotal);
    jQuery("#txt_subtotal").text(SubTotal);
    amount();
}



