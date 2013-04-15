var flag_shade_open = false;
var shade_id = 0;

jQuery(document).ready(function () {


    Shadowbox.init({
        modal:false,
        displayNav:false,
        handleUnsupported:"remove",
        onFinish: function() {
            jQuery("#sb-overlay").unbind();

        }
    });

    if(jQuery("#frmMain").validate != undefined) {
        jQuery("#frmMain").validate({
            rules:{
                to:{
                    required:true
                },
                from:{
                    required:true
                },
                recipient_email:{
                    required:true,
                    email: true
                },
                crecipient_email:{
                    required:true,
                    email:true,
                    equalTo: "#recipient_email"
                },
                message:{
                    maxlength: 250
                }
            },
            messages:{
                to:"Please enter To name",
                from:"Please enter From name",
                recipient_email:{
                    required:"Please enter a recipient email address",
                    email:"Entered email address is not valid"
                },
                crecipient_email:{
                    required:"Please confirm a recipient email address",
                    email:"Entered email address is not valid",
                    equalTo: "Email address is not equal"
                }
            }
        });
    }

    jQuery("#imagecontainer img").each(function (index, value) {
        if (index == 0) {
            jQuery(this).show();
        }
    });

    jQuery(".addtocart").click(function (e) {
        e.preventDefault();
        window.location = jQuery(this).parents(".product").children("a:first").attr("href");
    });

    jQuery(".addtocart_details").click(function () {
        if(!jQuery("#frmMain").valid( )) {
            return false;
        }
        var self = this;

        jQuery(this).attr('disabled', 'true');

        var windowWidth = document.documentElement.clientWidth;
        var windowHeight = document.documentElement.clientHeight;
        var popupHeight = $(".overlay-message").height();
        var popupWidth = $(".overlay-message").width();

        jQuery(".overlay-message").css({
            "top":windowHeight / 2 - popupHeight / 2 + "px",
            "left":windowWidth / 2 - popupWidth / 2 + "px"
        }).hide();

        var data = {
                        'method':"add_voucher",
                        'id':jQuery(this).attr('pid'),
                        'qty':1,
                        'to':jQuery("#to").val(),
                        'from':jQuery("#from").val(),
                        'email':jQuery('#recipient_email').val(),
                        'message':jQuery('#message').val()
                    };
        jQuery.post(base_url + 'shippingbag', data,
            function (response) {
                if (response.success) {
                    Shadowbox.open({
                        modal:true,
                        content:'<p class="msg">' + response.qty + ' eGift Card has been added to your cart.' + "</p>" +
                            '<div class="overlay-box"><a href="/egiftcards">Buy another eGift Card</a><a href="/viewcart">Proceed to Checkout</a></div>',
                        player:"html",
                        title:"",
                        height:100,
                        width:320
                    });

                    jQuery("#cart_total_items").html(response.total_items);
                    jQuery(this).attr('disabled', 'false');

                }
            }, "json");

        return false;

    });


    jQuery(".tabs").children().each(function (index, value) {
        if (index > 0) {
            jQuery(".tab_content").eq(index).hide();
            jQuery(".tab_content").eq(index).hide();
        }
        ;
        jQuery(this).click(function () {

            jQuery(".tabs").children().attr('class', '');

            if (jQuery("#imagecontainer") != undefined) {
                jQuery("#imagecontainer img").hide();
            }

            jQuery(this).attr('class', 'active');
            jQuery(".tab_content").hide().eq(index).fadeIn(800);

            if (jQuery("#imagecontainer") != undefined) {
                jQuery("#imagecontainer img").eq(index).fadeIn(800);
            }

            return false;
        });
    });

    jQuery("#btnReviewSubmit").click(function () {

        if (jQuery("#rate_this_product-score").val() == "" || validateNotEmpty(jQuery("#rate_this_product-score").val()) == false) {
            alert('Please, selected a rating.');
            return false;
        }

        if (jQuery("#review_title").val() == "" || validateNotEmpty(jQuery("#review_title").val()) == false) {
            alert('Please enter the subject');
            jQuery("#review_title").focus();
            return false;
        }

        if (jQuery("#reviews").val() == "" || validateNotEmpty(jQuery("#reviews").val()) == false) {
            alert('Please enter the reviews');
            jQuery("#review").focus();
            return false;
        }


        jQuery("#rate_this_product").fadeOut(600);
        jQuery("#txt_rate_this_product").fadeOut(600);
        jQuery("#review_title").fadeOut(600);

        jQuery(".title").fadeOut(600);
        jQuery("#review_message").fadeIn();

        jQuery(".reviews").fadeOut(600);
        jQuery("#btnReviewSubmit").hide();

        jQuery.post(base_url + 'reviews/sendmessage', {
                pid:jQuery("#product_id").val(),
                title:jQuery("#review_title").val(),
                rate:jQuery("#rate_this_product-score").val(),
                message:jQuery("#reviews").val()
            }, function (response) {
                if (response) {
                    if (response.error_message != "") {
                        jQuery(".receive_message").html(response.error_message);
                        jQuery(".reviews").show();
                        jQuery("#review_message").hide();
                        jQuery("#btnReviewSubmit").show();
                    }
                    if (response.success != "") {
                        jQuery("#rate_this_product").fadeOut(600);
                        jQuery(".title").fadeOut(600);
                        jQuery("#review_message").fadeOut(600);
                        jQuery("#review_title").fadeOut(600);
                        jQuery("#txt_rate_this_product").fadeOut(600);
                        jQuery("#txt_review_title").fadeOut(600);
                        jQuery("#btnReviewSubmit").hide();
                        jQuery(".receive_message").html(response.success);
                    }
                }
            }, "json"
        );
    });

    jQuery(".symbolkey a img").mouseover(function () {
        jQuery("#symbolkey_desc").html(
            "<strong>" + jQuery(this).attr('alt') + "</strong>" +
                " : " + jQuery(this).attr('desc')
        );
    });

    jQuery("#sorting_bar a").each(function (index, value) {
        jQuery(this).click(function () {
            jQuery("#sort_by").val(jQuery(this).attr('alt'));
            document.frmMain.action = '';
            document.frmMain.submit();
        });
    });

    jQuery(".closebutton").click(function () {
        jQuery(this).parent().parent().fadeOut(150);
    });

    jQuery(".addtowishlist").click(function () {

        jQuery(this).attr('disabled', 'true');

        var windowWidth = document.documentElement.clientWidth;
        var windowHeight = document.documentElement.clientHeight;
        var popupHeight = $(".overlay-message").height();
        var popupWidth = $(".overlay-message").width();

        jQuery(".overlay-message").css({
            "top":windowHeight / 2 - popupHeight / 2 + "px",
            "left":windowWidth / 2 - popupWidth / 2 + "px"
        }).hide();

        /*
         Shadowbox.init({
         modal: true,
         displayNav:         false,
         handleUnsupported:  "remove"
         });
         */

        jQuery.post(base_url + 'shippingbag', {
                'method':"wish",
                'product_id':jQuery(this).attr('pid'),
                'category_id':0,
                'type' : 'voucher',
                'qty':1
            },
            function (response) {
                if (response.success) {

                    if (response.total_items == 0) {
                        Shadowbox.open({
                            modal:true,
                            content:'<p class="msg">' + 'This item has been already added.' + "</p>" +
                                '<div class="overlay-box"><a href="javascript:Shadowbox.close()">Continue Shopping</a><a href="/myaccount/wishlist">Go to My Account</a></div>',
                            player:"html",
                            title:"",
                            height:100,
                            width:320
                        });
                    } else {
                        Shadowbox.open({
                            modal:true,
                            content:'<p class="msg">' + response.total_items + ' item(s) has been added to your wishlist.' + "</p>" +
                                '<div class="overlay-box"><a href="javascript:Shadowbox.close()">Continue Shopping</a><a href="/myaccount/wishlist">Go to My Account</a></div>',
                            player:"html",
                            title:"",
                            height:100,
                            width:320
                        });
                    }

                    jQuery("#wishlist_total_items").html(response.total_items);
                    jQuery(this).attr('disabled', 'false');
                }
            }, "json");
        return false;
    });


});

function addtocart(p1, p2, obj) {

    jQuery(obj).attr('disabled', 'disabled');
    jQuery.post(base_url + 'shippingbag', {
            'method':"add",
            'category_id':p1,
            'product_id':p2,
            'qty':1
        },
        function (response) {
            if (response.success) {
                jQuery("#cart_total_items").html(response.total_items);
                jQuery(obj).text("Added");
                jQuery(obj).attr('class', 'outofstock');
                jQuery(obj).attr('disabled', 'disabled');
            }
        }, "json");

    jQuery(this).unbind('click');
    return false;
}

function addtowishlist(p1, p2, obj) {

    jQuery(obj).attr('disabled', 'true');

    jQuery.post(base_url + 'shippingbag', {
            'method':"wish",
            'category_id':p1,
            'product_id':p2,
            'qty':1
        },
        function (response) {
            if (response.success) {
                jQuery(obj).text("Added");
                jQuery(obj).attr('class', 'outofstock');
                jQuery(obj).attr('disabled', 'disabled');
                jQuery("#wishlist_total_items").html(response.total_items);
                jQuery(obj).attr('disabled', 'false');
            }
        }, "json");

    jQuery(obj).unbind('click');

    return false;
}

// function AddToCart (p1, p2) {
// 	jQuery("#method").val('add');
// 	jQuery("#product_id").val(p2);
// 	jQuery("#category_id").val(p1);
// 	jQuery("#qty").val(1);
// 	jQuery("#frmMain").submit();
// }

// function AddWishList (p1, p2) {	
// 	jQuery("#method").val('wish');
// 	jQuery("#product_id").val(p2);
// 	jQuery("#category_id").val(p1);
// 	jQuery("#frmMain").submit();
// }

function showShade(ndx) {
    flag_shade_open = true;
    shade_id = ndx;
    jQuery("#shadebox_" + ndx).fadeIn(100);
    jQuery("#shadebox_" + ndx).children("div").children("div").focus();
}

function trimAll(strValue) {

    var objRegExp = /^(\s*)$/;

    //check for all spaces
    if (objRegExp.test(strValue)) {
        strValue = strValue.replace(objRegExp, '');
        if (strValue.length == 0)
            return strValue;
    }

    //check for leading & trailing spaces
    objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;
    if (objRegExp.test(strValue)) {
        //remove leading and trailing whitespace characters
        strValue = strValue.replace(objRegExp, '$2');
    }
    return strValue;
}

function validateNotEmpty(strValue) {
    var strTemp = strValue;
    strTemp = trimAll(strTemp);
    if (strTemp.length > 0) {
        return true;
    }
    return false;
}