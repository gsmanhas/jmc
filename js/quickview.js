

jQuery(document).ready(function(){
	
	
	jQuery(".addtowishlist_nosignin").each(function(index, value){
		jQuery(this).click(function(){
			jQuery(this).text("Please sign in");
			jQuery(this).attr('class', 'outofstock');
			jQuery(this).attr('disabled', 'disabled');
		});
	});
	
	jQuery(".tabs").children().each(function(index, value){
		if (index > 0) {
			jQuery(".tab_content").eq(index).hide();
			jQuery(".tab_content").eq(index).hide();
		};
		jQuery(this).click(function(){
			jQuery(".tabs").children().attr('class', '');
			jQuery(this).attr('class', 'active');
			jQuery(".tab_content").hide().eq(index).fadeIn(800);			
			return false;
		});
	});
	
	jQuery(".symbolkey a img").mouseover(function(){
		jQuery("#symbolkey_desc").html(
			"<strong>" + jQuery(this).attr('alt') + "</strong>" +
			" : " + jQuery(this).attr('desc')
		);
	});
	
	jQuery("#sorting_bar li a").each(function(index, value){
		jQuery(this).click(function(){
			jQuery("#sort_by").val(jQuery(this).attr('alt'));
			document.frmMain.action = '';
			document.frmMain.submit();
		});
	});
	
	jQuery(".closebutton").click(function(){
		jQuery(this).parent().parent().fadeOut(150);
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
			// jQuery("#cart_total_items").html(response.total_items);
			jQuery(obj).text("Added");
			jQuery(obj).attr('class', 'outofstock');
			jQuery(obj).attr('disabled', 'disabled');
			// javascript:window.parent.Shadowbox.close();
			window.parent.document.getElementById('cart_total_items').innerHTML = response.total_items;
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
			// jQuery("#wishlist_total_items").html(response.total_items);
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
// 
// function AddWishList (p1, p2) {
// 	jQuery("#method").val('wish');
// 	jQuery("#product_id").val(p2);
// 	jQuery("#category_id").val(p1);
// 	jQuery("#frmMain").submit();
// }