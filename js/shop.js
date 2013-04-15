
var flag_shade_open = false;
var shade_id = 0;

jQuery(document).ready(function(){


	Shadowbox.init({
			modal: false,
		    displayNav:         true,
		    handleUnsupported:  "remove"
	});

	// $(document).ajaxStart(function(){
	// 	Shadowbox.open({
	// 		modal:      true,
	//         content:    '<div class="add2cartbody"><a href="javascript:Shadowbox.close()" class="selectashade">CONTINUE SHOPPING</a>&nbsp;&nbsp;<a href="/viewcart" class="selectashade">MY SHOPPING BAG</a></div>',
	//         player:     "html",
	//         title:      "The product was added to your cart.",
	//         height:     80,
	//         width:      260
	//     });
	//  });

	jQuery("#imagecontainer img").each(function(index, value){
		if (index == 0) { jQuery(this).show(); }
	});
	
	jQuery(".view_products").each(function(index, value){
		jQuery(this).click(function(){
			Shadowbox.open({
				modal:      true,
				displayNav: true,
			    handleUnsupported:  "remove",
				content:    "/view-icons/" + jQuery(this).attr('id'),
				player: 	'iframe',
				width:      "980",
				height:     "368",
				onClose:    "onShClose"
			});
		});
	});
	
	jQuery(".outofstock").click(function(){
		return false;
	});
	
	jQuery(document).click(function(){
		if (flag_shade_open == true) {
			if (shade_id != 0) {
				jQuery("#shadebox_" + shade_id).fadeOut(150);
			}
		}
	})
	
	jQuery(".quickview_container").each(function(index, value){
		
		jQuery(".quickview_button").eq(index).css({
			"width" : jQuery(this).width(),
			"height": jQuery(this).height()
		}).click(function(){
/*
			Shadowbox.init({
				skipSetup:  true
			});
*/
			Shadowbox.open({
				modal:      true,
				displayNav: true,
			    handleUnsupported:  "remove",
				content:    "/quickview/" + jQuery(".productimagecontainer a img").eq(index).attr('c') + "/" + jQuery(".productimagecontainer a img").eq(index).attr('u'),
				player: 	'iframe',
				width:      "920",
				height:     "478",
				onClose:    "onShClose"
			});
		});
		
		
	})

	jQuery(".productimagecontainer").each(function(index, value){
	
		jQuery(this).children('a').children('img').mouseenter(function(){
			
			var img = jQuery(this);
			var container = jQuery(".quickview_container").eq(index);
			
			jQuery(".quickview_container").eq(index).css({
				"top"  : (jQuery(img).height() / 2) - (jQuery(container).height() / 2),
				"left" : (jQuery(img).width()  / 2) - (jQuery(container).width() / 2)
			}).show().mouseenter(function(){
				jQuery(this).show();
			});
	
		});
		
		jQuery(this).children('a').children('img').mouseout(function(){
			jQuery(".quickview_container").each(function(ndx, val){
				jQuery(this).hide();
			})
		});
	});	
	
	// jQuery(".quickview_button").each(function(index, value){
	// 	jQuery(this).click(function(){
	// 		
	// 		alert(0);
	// 		
	// 		// Shadowbox.init({
	// 		// 	skipSetup:  true
	// 		// });
	// 		// Shadowbox.open({
	// 		// 	modal:      false,
	// 		// 	displayNav: false,
	// 		//     handleUnsupported:  "remove",
	// 		// 	content:    "/quickview/" + jQuery(".productimagecontainer").children("a").children("img").eq(index).attr('c') + "/" + jQuery(this).children("a").children("img").eq(index).attr('c').attr('u'),
	// 		// 	player: 	'iframe',
	// 		// 	width:      920
	// 		// });
	// 	});
	// });
	
	jQuery(".addtocart").click(function(){
		if(!jQuery(this).hasClass("voucher")) {
            var self = this;

            jQuery(this).attr('disabled', 'true');

            var windowWidth  = document.documentElement.clientWidth;
            var windowHeight = document.documentElement.clientHeight;
            var popupHeight  = $(".overlay-message").height();
            var popupWidth   = $(".overlay-message").width();

            jQuery(".overlay-message").css({
                "top": windowHeight / 2 - popupHeight / 2 + "px",
                "left": windowWidth / 2 - popupWidth / 2 + "px"
            }).hide();

            jQuery.post(base_url + 'shippingbag',{
                'method'     : "add",
                'product_id' : jQuery(this).attr('pid'),
                'category_id': jQuery(this).attr('cid'),
                'qty'        : 1
            },
            function (response) {
                if (response.success) {
                    Shadowbox.open({
                        modal:      true,
                        content:    '<p class="msg">' + response.total_items + ' ' + response.is_have + ' been added to your cart.' + "</p>" +
                                    '<div class="overlay-box"><a href="javascript:Shadowbox.close()">Continue Shopping</a><a href="/viewcart">Proceed to Checkout</a></div>',
                        player:     "html",
                        title:      "",
                        height:     100,
                        width:      320
                    });

                    if (response.return_stock <= 0 && response.return_cna_pre_order == 0) {
                        jQuery(self).attr('class', 'outofstock');
                        jQuery(self).attr('href', '#');
                        jQuery(self).html('Out of Stock');
                        jQuery(self).unbind('click');
                    }

                    if ((response.qty >= response.return_stock) && response.return_cna_pre_order == 0) {
                        jQuery(self).attr('class', 'outofstock');
                        jQuery(self).attr('href', '#');
                        jQuery(self).html('Out of Stock');
                        jQuery(self).unbind('click');
                    }

                    jQuery("#cart_total_items").html(response.total_items);
                    jQuery(this).attr('disabled', 'false');

                }
            }, "json");

            return false;
        }


		
	});
	
	// Shadowbox.setup(jQuery(".addtocart"), {
	// 	modal:      true,
	//         content:    '<div id="welcome-msg">Welcome to my website!</div>',
	//         player:     "html",
	//         title:      "Welcome",
	//         height:     200,
	//         width:      200
	// });
		
	jQuery(".tabs").children().each(function(index, value){
		if (index > 0) {
			jQuery(".tab_content").eq(index).hide();
			jQuery(".tab_content").eq(index).hide();			
		};
		jQuery(this).click(function(){
			
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

	jQuery("#btnReviewSubmit").click(function(){
		
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
			pid : jQuery("#product_id").val(),
			title : jQuery("#review_title").val(),
			rate : jQuery("#rate_this_product-score").val(),
			message : jQuery("#reviews").val()
		}, function(response){
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
	
	jQuery(".symbolkey a img").mouseover(function(){
		jQuery("#symbolkey_desc").html(
			"<strong>" + jQuery(this).attr('alt') + "</strong>" +
			" : " + jQuery(this).attr('desc')
		);
	});
	
	jQuery("#sorting_bar a").each(function(index, value){
		jQuery(this).click(function(){
			jQuery("#sort_by").val(jQuery(this).attr('alt'));
			document.frmMain.action = '';
			document.frmMain.submit();
		});
	});
	
	jQuery(".closebutton").click(function(){
		jQuery(this).parent().parent().fadeOut(150);
	});
	
	jQuery(".addtowishlist").click(function(){
			
			jQuery(this).attr('disabled', 'true');
	
			var windowWidth  = document.documentElement.clientWidth;  
			var windowHeight = document.documentElement.clientHeight;  
			var popupHeight  = $(".overlay-message").height();  
			var popupWidth   = $(".overlay-message").width();
	
			jQuery(".overlay-message").css({
				"top": windowHeight / 2 - popupHeight / 2 + "px",  
				"left": windowWidth / 2 - popupWidth / 2 + "px"
			}).hide();
	
/*
			Shadowbox.init({
				modal: true,
			    displayNav:         false,
			    handleUnsupported:  "remove"
			});
*/
	
			jQuery.post(base_url + 'shippingbag',{
				'method'     : "wish",
				'product_id' : jQuery(this).attr('pid'),
				'category_id': jQuery(this).attr('cid'),
				'qty'        : 1
			},
			function (response) {
				if (response.success) {				
					
					if (response.total_items == 0) {
						Shadowbox.open({
							modal:      true,
					        content:    '<p class="msg">' + 'This item has been already added.' + "</p>" +
										'<div class="overlay-box"><a href="javascript:Shadowbox.close()">Continue Shopping</a><a href="/myaccount/wishlist">Go to My Account</a></div>',
					        player:     "html",
					        title:      "",
					        height:     100,
					        width:      320
					    });
					} else {
						Shadowbox.open({
							modal:      true,
					        content:    '<p class="msg">' + response.total_items + ' item(s) has been added to your wishlist.' + "</p>" +
										'<div class="overlay-box"><a href="javascript:Shadowbox.close()">Continue Shopping</a><a href="/myaccount/wishlist">Go to My Account</a></div>',
					        player:     "html",
					        title:      "",
					        height:     100,
					        width:      320
					    });
					}
					
					jQuery("#wishlist_total_items").html(response.total_items);
					jQuery(this).attr('disabled', 'false');
				}
			}, "json");
			return false;
		});
	
		
	
});

function addtocart (p1, p2, obj) {
	
	jQuery(obj).attr('disabled', 'disabled');
	jQuery.post(base_url + 'shippingbag',{
		'method'     : "add",
		'category_id': p1,
		'product_id' : p2,
		'qty'        : 1
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

function addtowishlist (p1, p2, obj) {
	
	jQuery(obj).attr('disabled', 'true');
	
	jQuery.post(base_url + 'shippingbag',{
		'method'     : "wish",
		'category_id': p1,
		'product_id' : p2,
		'qty'        : 1
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

function showShade (ndx) {
	flag_shade_open = true;
	shade_id = ndx;
	jQuery("#shadebox_" + ndx).fadeIn(100);
	jQuery("#shadebox_" + ndx).children("div").children("div").focus();
}

function trimAll( strValue ) {
	
	var objRegExp = /^(\s*)$/;

    //check for all spaces
    if(objRegExp.test(strValue)) {
       strValue = strValue.replace(objRegExp, '');
       if( strValue.length == 0)
          return strValue;
    }

	//check for leading & trailing spaces
	objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;
	if(objRegExp.test(strValue)) {
		//remove leading and trailing whitespace characters
		strValue = strValue.replace(objRegExp, '$2');
	}
	return strValue;
}

function validateNotEmpty( strValue ) {
	var strTemp = strValue;
	strTemp = trimAll(strTemp);
	if(strTemp.length > 0){
		return true;
	}
	return false;
}