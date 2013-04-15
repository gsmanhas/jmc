


var SearchProducts  = [];
var SearchCustomers = [];

var Products = new Array();
var ShippingOption = new Object();
var DestinationState = new Object();
var Product_Tax = 0;

var DiscountCode_Rel_Products = new Array();

function Product (id, name, price, retail_price, qty) {
	this.id = id;
	this.name = name;
	this.price = price;
	this.retail_price = retail_price;
	this.qty = qty;
	return this;
}

function _ShippingOption (id, name, price, delivery) {
	this.id       = id;
	this.name     = name;
	this.price    = price;
	this.delivery = delivery
	return this;
}

function _DestinationState (id, state_id, tax_code, tax_rate) {
	this.id = id;
	this.state_id = state_id;
	this.tax_code = tax_code;
	this.tax_rate = tax_rate;
	return this;
}

function DiscountCode_Rel_Product (id, d_id, pid) {
	this.id   = id;
	this.d_id = d_id;
	this.pid  = pid;
}

function get_Order_Products (_order_id) {
	jQuery.post(base_url + 'ajax/search_orderlist_products',{
		order_id: _order_id
	},
	function (response) {
		if (response.length >= 1) {
			SearchProducts = response;
			for (var i = 0; i < response.length; i++) {
				addProduct(i);
				changeQty(i, response[i].id);
			}
		}
	}, "json");
}

jQuery(document).ready(function(){
	
	jQuery(".response-msg").hide();
	
	// jQuery("#order_date").datepicker({
	// 	showButtonPanel: true,
	// 	dateFormat: "yy-mm-dd"
	// });
	
	jQuery("#btn_update").click(function(){
		
		var ErrorMessage = '';
		
		if (jQuery("#order_date").val() == "") {
			ErrorMessage += "Order Date\r\n";
		}
		
		if (jQuery("#firstname").val() == "") {
			ErrorMessage += "First Name\r\n";
		}
		
		if (jQuery("#lastname").val() == "") {
			ErrorMessage += "Last Name\r\n";
		}
		
		if (jQuery("#email").val() == "") {
			ErrorMessage += "Email\r\n";
		}
		
		if (jQuery("#phone").val() == "") {
			ErrorMessage += "Phone\r\n";
		}
		
		//	Billing.
		// if (jQuery("#bill_firstname").val() == "") {
		// 	ErrorMessage += "Billing First Name\r\n";
		// }
		// if (jQuery("#bill_lastname").val() == "") {
		// 	ErrorMessage += "Billing Last Name\r\n";
		// }
		if (jQuery("#bill_address").val() == "") {
			ErrorMessage += "Billing Address\r\n";
		}
		if (jQuery("#bill_city").val() == "") {
			ErrorMessage += "Billing City\r\n";
		}
		if (jQuery("#bill_state").val() == "0") {
			ErrorMessage += "Billing State\r\n";
		}
		if (jQuery("#bill_zipcode").val() == "") {
			ErrorMessage += "Billing Zip\r\n";
		}
		
		//	Shipping.
		// if (jQuery("#ship_firstname").val() == "") {
		// 	ErrorMessage += "Shipping First Name\r\n";
		// }
		// if (jQuery("#ship_lastname").val() == "") {
		// 	ErrorMessage += "Shipping Last Name\r\n";
		// }
		if (jQuery("#ship_address").val() == "") {
			ErrorMessage += "Shipping Address\r\n";
		}
		if (jQuery("#ship_city").val() == "") {
			ErrorMessage += "Shipping City\r\n";
		}
		if (jQuery("#ship_state").val() == "0") {
			ErrorMessage += "Shipping State\r\n";
		}
		if (jQuery("#ship_zipcode").val() == "") {
			ErrorMessage += "Shipping Zip\r\n";
		}
		
		//	檢查是否有產品加入
		if (Products.length <= 0 ) {
			ErrorMessage += "Add at least one product\r\n";
		}
		
		if (jQuery("#product_tax").val() == "NaN") {
			ErrorMessage += "Check Sales Tax field \r\n";
		}
		
		if (jQuery("#shipping_method").val() == -1) {
			ErrorMessage += "Select a shipping option\r\n";
		}
		
		if (jQuery("#state").val() == 0) {
			ErrorMessage += "Select a shipping destination state \r\n";
		}
		
		if (ErrorMessage != "") {
			alert("Please enter following thing(s):\r\n" + ErrorMessage);
			return false;
		}
				
		var pids = '';
		var qtys = '';
		var prices = '';
		
		for (var i = 0; i < Products.length; i++) {
			pids += Products[i].id + ",";
			qtys += Products[i].qty + ",";
			prices += Products[i].price + ",";
		}
		
		$.blockUI();
		
		jQuery.post(base_url + 'ajax/update_order', {
			
			"id"          : jQuery("#id").val(),
			
			"user_id"     : jQuery("#user_id").val(),
			"order_state" : jQuery("#order_state").val(),
			"order_date"  : jQuery("#order_date").val(),
			"firstname"   : jQuery("#firstname").val(),
			"lastname"    : jQuery("#lastname").val(),
			"email"       : jQuery("#email").val(),
			"phone"       : jQuery("#phone").val(),
			
			// "bill_firstname" : jQuery("#bill_firstname").val(),
			// "bill_lastname"  : jQuery("#bill_lastname").val(),
			"bill_zipcode"   : jQuery("#bill_zipcode").val(),
			"bill_city"      : jQuery("#bill_city").val(),
			"bill_state"     : jQuery("#bill_state").val(),
			"bill_address"   : jQuery("#bill_address").val(),
			
			// "ship_firstname" : jQuery("#ship_firstname").val(),
			// "ship_lastname"  : jQuery("#ship_lastname").val(),
			"ship_zipcode"   : jQuery("#ship_zipcode").val(),
			"ship_city"      : jQuery("#ship_city").val(),
			"ship_state"     : jQuery("#ship_state").val(),
			"ship_address"   : jQuery("#ship_address").val(),			
			
			"pids" : pids,
			"prices" : prices,
			"qtys" : qtys,
			
			"track_number" : jQuery("#track_number").val(),
			
			"product_tax" : jQuery("#product_tax").val(),
			"shipping_method" : jQuery("#shipping_method").val(),
			"state" : jQuery("#state").val(),
			"promo" : jQuery("#promo").val(),
			"hid_discount" : jQuery("#hid_discount").val(),
			"hid_discount_can_freeshipping" : jQuery("#hid_discount_can_freeshipping").val(),
			"product_tax" : jQuery("#product_tax").val(),
			"calculate_shipping" : jQuery("#calculate_shipping").val(),
			"amount" : jQuery("#amount").val()
			
		}, function(response){
			
			// if (response.length >= 1) {
			// 	if (response[0].success == 1) {
			// 		alert(0);
			// 	}
			// } else {
			// 	alert(1);
			// }
			
			// alert(response.length);
			
			$.unblockUI({ fadeOut: 800 });
			location.href = base_url + "admin.php/orders/update_success";
			
		}, "json"
		);
	});
	
	//	All Ready to Save Order...
	jQuery("#btn_submit").click(function(){
				
		var ErrorMessage = '';
		
		if (jQuery("#order_date").val() == "") {
			ErrorMessage += "Order Date\r\n";
		}
		
		if (jQuery("#firstname").val() == "") {
			ErrorMessage += "First Name\r\n";
		}
		
		if (jQuery("#lastname").val() == "") {
			ErrorMessage += "Last Name\r\n";
		}
		
		if (jQuery("#email").val() == "") {
			ErrorMessage += "Email\r\n";
		}
		
		if (jQuery("#phone").val() == "") {
			ErrorMessage += "Phone\r\n";
		}
		
		//	Billing.
		// if (jQuery("#bill_firstname").val() == "") {
		// 	ErrorMessage += "Billing First Name\r\n";
		// }
		// if (jQuery("#bill_lastname").val() == "") {
		// 	ErrorMessage += "Billing Last Name\r\n";
		// }
		if (jQuery("#bill_address").val() == "") {
			ErrorMessage += "Billing Address\r\n";
		}
		if (jQuery("#bill_city").val() == "") {
			ErrorMessage += "Billing City\r\n";
		}
		if (jQuery("#bill_state").val() == "0") {
			ErrorMessage += "Billing State\r\n";
		}
		if (jQuery("#bill_zipcode").val() == "") {
			ErrorMessage += "Billing Zip\r\n";
		}
		
		//	Shipping.
		// if (jQuery("#ship_firstname").val() == "") {
		// 	ErrorMessage += "Shipping First Name\r\n";
		// }
		// if (jQuery("#ship_lastname").val() == "") {
		// 	ErrorMessage += "Shipping Last Name\r\n";
		// }
		if (jQuery("#ship_address").val() == "") {
			ErrorMessage += "Shipping Address\r\n";
		}
		if (jQuery("#ship_city").val() == "") {
			ErrorMessage += "Shipping City\r\n";
		}
		if (jQuery("#ship_state").val() == "0") {
			ErrorMessage += "Shipping State\r\n";
		}
		if (jQuery("#ship_zipcode").val() == "") {
			ErrorMessage += "Shipping Zip\r\n";
		}
		
		//	檢查是否有產品加入
		if (Products.length <= 0 ) {
			ErrorMessage += "Add at least one product\r\n";
		}
		
		if (jQuery("#product_tax").val() == "NaN") {
			ErrorMessage += "Check Sales Tax field \r\n";
		}
		
		if (jQuery("#shipping_method").val() == -1) {
			ErrorMessage += "Select a shipping option\r\n";
		}
		
		if (jQuery("#state").val() == 0) {
			ErrorMessage += "Select a shipping destination state \r\n";
		}
		
		if (ErrorMessage != "") {
			alert("Please enter following thing(s):\r\n" + ErrorMessage);
			return false;
		}
				
		var pids = '';
		var qtys = '';
		var prices = '';
		
		for (var i = 0; i < Products.length; i++) {
			pids += Products[i].id + ",";
			qtys += Products[i].qty + ",";
			prices += Products[i].price + ",";
		}
		
		$.blockUI();
		
		jQuery.post(base_url + 'ajax/save_order', {
			
			"user_id"     : jQuery("#user_id").val(),
			"order_state" : jQuery("#order_state").val(),
			"order_date"  : jQuery("#order_date").val(),
			"firstname"   : jQuery("#firstname").val(),
			"lastname"    : jQuery("#lastname").val(),
			"email"       : jQuery("#email").val(),
			"phone"       : jQuery("#phone").val(),
			
			// "bill_firstname" : jQuery("#bill_firstname").val(),
			// "bill_lastname"  : jQuery("#bill_lastname").val(),
			"bill_zipcode"   : jQuery("#bill_zipcode").val(),
			"bill_city"      : jQuery("#bill_city").val(),
			"bill_state"     : jQuery("#bill_state").val(),
			"bill_address"   : jQuery("#bill_address").val(),
			
			// "ship_firstname" : jQuery("#ship_firstname").val(),
			// "ship_lastname"  : jQuery("#ship_lastname").val(),
			"ship_zipcode"   : jQuery("#ship_zipcode").val(),
			"ship_city"      : jQuery("#ship_city").val(),
			"ship_state"     : jQuery("#ship_state").val(),
			"ship_address"   : jQuery("#ship_address").val(),			
			
			"pids" : pids,
			"prices" : prices,
			"qtys" : qtys,
			
			"track_number" : jQuery("#track_number").val(),
			
			"product_tax" : jQuery("#product_tax").val(),
			"shipping_method" : jQuery("#shipping_method").val(),
			"state" : jQuery("#state").val(),
			"promo" : jQuery("#promo").val(),
			"hid_discount" : jQuery("#hid_discount").val(),
			"hid_discount_can_freeshipping" : jQuery("#hid_discount_can_freeshipping").val(),
			"product_tax" : jQuery("#product_tax").val(),
			"calculate_shipping" : jQuery("#calculate_shipping").val(),
			"amount" : jQuery("#amount").val()
			
		}, function(response){
			
			// if (response.length >= 1) {
			// 	if (response[0].success == 1) {
			// 		alert(0);
			// 	}
			// } else {
			// 	alert(1);
			// }
			
			// alert(response.length);
			
			$.unblockUI({ fadeOut: 800 });
			
			// jQuery(".response-msg").fadeIn(800).html("Order Saved Successfully");
			
			location.href = base_url + "admin.php/orders/success";
			
		}, "json"
		);
	});
	
	//	ship_state.
	// jQuery("#ship_state").change(function(){
	// 	
	// 	if (Products.length <= 0) {
	// 		alert("Please Add Product");
	// 		return false;
	// 	}
	// 	
	// 	$.blockUI();
	// 	jQuery.post(base_url + 'ajax/ship_state', {
	// 		"id" : ""
	// 	}, function(response){
	// 		$.unblockUI({ fadeOut: 800 });
	// 	}, "json"
	// 	);
	// });

	//	destination state.
	jQuery("#state").change(function(){		
		$.blockUI();
		jQuery.post(base_url + 'ajax/destination_state', {
			"state" : jQuery("#state").val()
		}, function(response){
			DestinationState = new Object();
			if (response.length >= 1) {
				DestinationState = new _DestinationState(
					response[0].id,
					response[0].state_id,
					response[0].tax_code,
					response[0].tax_rate
				);
				
				// console.log(DestinationState);
				
				if (jQuery("#shipping_method").val() >= 1) {					
					var tax = (ShippingOption.price * (DestinationState.tax_rate / 100));
					// var calculate_shipping = parseFloat(ShippingOption.price) + parseFloat(tax);
					var calculate_shipping = parseFloat(ShippingOption.price);
					jQuery("#calculate_shipping").val(calculate_shipping.toFixed(2));
					var subTotal = 0;
					for (var i = 0; i < Products.length; i++) {
						subTotal += (Products[i].price * Products[i].qty);
					} 
					
					// jQuery("#product_tax").val(parseFloat(tax).toFixed(2));
					// console.log(DestinationState);
					// console.log(subTotal);
					// console.log(parseFloat(tax).toFixed(2));
					// console.log(DestinationState.tax_rate);
					if (subTotal > 0) {
						var Product_tax = subTotal * (DestinationState.tax_rate / 100);
						jQuery("#product_tax").val(parseFloat(Product_tax).toFixed(2));
					} else {
						jQuery("#product_tax").val("0");
					}
				}
			} else {
				if (jQuery("#shipping_method").val() >= 1) {
					jQuery("#calculate_shipping").val(ShippingOption.price);
					jQuery("#product_tax").val(0);
				} else {
					jQuery("#calculate_shipping").val(0);
					jQuery("#product_tax").val(0);
				}
			}
			
			// jQuery("#ship_state option:selected").val(jQuery('#state option:selected').val()).attr("selected", "selected");
			
			document.getElementById("ship_state").selectedIndex = jQuery('#state option:selected').val();
			
			SumAmount();
			jQuery.unblockUI({ fadeOut: 800 });
		}, "json"
		);
	});
	
	//	shipping option.
	jQuery("#shipping_method").change(function(){	
		$.blockUI();
		jQuery.post(base_url + 'ajax/shipping_method', {
			"id" : jQuery("#shipping_method").val()
		}, function(response){

			if (response.length >= 1) {

				ShippingOption = new _ShippingOption(
					response[0].id, 
					response[0].name,
					response[0].price,
					response[0].delivery
				);

				if (jQuery("#state").val() >= 1) {
					var tax = (ShippingOption.price * (DestinationState.tax_rate / 100));
					// var calculate_shipping = parseFloat(ShippingOption.price) + parseFloat(tax)
					var calculate_shipping = parseFloat(ShippingOption.price);
					jQuery("#calculate_shipping").val(calculate_shipping.toFixed(2));
					var subTotal = 0;
					for (var i = 0; i < Products.length; i++) {
						subTotal += (Products[i].price * Products[i].qty);
					}
					if (subTotal > 0) {
						var Product_tax = subTotal * (DestinationState.tax_rate / 100);
						jQuery("#product_tax").val(parseFloat(Product_tax).toFixed(2));
					} else {
						jQuery("#product_tax").val("0");
					}					

				} else {
					jQuery("#calculate_shipping").val(ShippingOption.price);
				}

			} else {
				jQuery("#calculate_shipping").val(0);
				jQuery("#product_tax").val(0);
			}
			SumAmount();
			$.unblockUI({ fadeOut: 800 });
		}, "json"
		);
	
	});
	
	//	Promo Discount.
	jQuery("#promo").change(function(){
		
		$.blockUI();
		
		jQuery("#discount").html("0");
		jQuery("#discount_freeshipping").html("");
		
		var pid = '';
		var price = '';
		var qty = '';
		
		for (var i = 0; i < Products.length; i++) {
			pid   += Products[i].id + ',';
			price += Products[i].price + ',';
			qty   += Products[i].qty + ',';
		}
				
		jQuery.post(base_url + 'ajax/promo', {
			"id"    : jQuery("#promo").val(),
			"pid"   : pid,
			"price" : price,
			"qty"   : qty
		}, function(response){
			if (response.length >= 1) {
				jQuery("#discount").html(parseFloat(response[0].discount_sub_total).toFixed(2));
				jQuery("#discount_freeshipping").html((response[0].FreeShipping == 1) ? "Plus Free Shipping" : "");
				jQuery("#hid_discount").val(
					parseFloat(response[0].discount_sub_total).toFixed(2)
				);
				jQuery("#hid_discount_can_freeshipping").val(response[0].FreeShipping);
			} else {
				jQuery("#discount").html("0");
				jQuery("#hid_discount").val(0);
			}
			SumAmount();
			$.unblockUI({ fadeOut: 800 });
		}, "json"
		);
	});
	
	jQuery("#btn_addnew_product").click(function(){
		jQuery.blockUI({
			message: jQuery('#search_product'), css: {
				top : '20px',
				left: '20px',
				right: '20px',
				backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px',
				width: '95%'
			}
		}); 
	});
	
	jQuery("#btnAddUser").click(function(){
		jQuery.blockUI({
			message: jQuery('#search_customers'), css: {
				top : '20px',
				left: '20px',
				right: '20px',
				backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px',
				width: '95%'
			}
		});
	});
	
	jQuery("#btnSearchCustomers").click(function(){
		jQuery.post(base_url + 'ajax/search_customers',{
			name: jQuery("#customer_name").val()
		},
		function(response){
			SearchCustomers = response;
			for (var i=0; i < response.length; i++) {
				jQuery(".search_customers_result").append(
					"<div><a href='javascript:addCustomer(" + i + ")'>" + response[i].firstname + "</a></div>"
				);
			}
		}, "json")
	});
		
	jQuery("#btnSearchProduct").click(function(){
		jQuery.post(base_url + 'ajax/search_products',{
			name: jQuery("#product_name").val()
		},
		function(response){
			SearchProducts = response;
			//	先清空查訊的資料, 再將最新的查詢資料放入到 .search_result
			jQuery(".search_result").empty();
			
			for (var i = 0; i < response.length; i++) {
				jQuery(".search_result").append(
					"<div id=\"search_" + response[i].id + "\">" +
					"<a href='javascript:addProduct(" + i + ")'>" + response[i].name + "</a>" +
					"</div>"
				);	
			}
		}, "json")
	});
	
	jQuery("#btnCancelSearchProduct").click(function(){
		jQuery(".search_result").children().remove();
		$.unblockUI({ fadeOut: 200 });
	});
	
	jQuery("#btnCancelSearchCustomers").click(function(){
		jQuery(".search_customers_result").children().remove();
		$.unblockUI({ fadeOut: 200 });
	});
	
	
	
});

function addCustomer (ndx) {
	
	jQuery("#firstname").val(SearchCustomers[ndx].firstname);
	jQuery("#lastname").val(SearchCustomers[ndx].lastname);
	jQuery("#email").val(SearchCustomers[ndx].email);
	jQuery("#phone").val(SearchCustomers[ndx].phone);
	
	jQuery("#bill_firstname").val(SearchCustomers[ndx].bill_firstname);
	jQuery("#bill_lastname").val(SearchCustomers[ndx].bill_lastname);
	jQuery("#bill_zipcode").val(SearchCustomers[ndx].bill_zipcode);
	jQuery("#bill_city").val(SearchCustomers[ndx].bill_city);
	jQuery("#bill_address").val(SearchCustomers[ndx].bill_address);
	
	jQuery("#ship_firstname").val(SearchCustomers[ndx].ship_firstname);
	jQuery("#ship_lastname").val(SearchCustomers[ndx].ship_lastname);
	jQuery("#ship_zipcode").val(SearchCustomers[ndx].ship_zipcode);
	jQuery("#ship_city").val(SearchCustomers[ndx].ship_city);
	jQuery("#ship_address").val(SearchCustomers[ndx].ship_address);
	
	jQuery("#user_id").val(SearchCustomers[ndx].id);
	
}

function addProduct(ndx) {
		
	for (var i = 0; i < Products.length; i++) {
		if (Products[i].id == SearchProducts[ndx].id) {
			return;
		}
	}
	
	//	建立 Product 物件, Qty 的預設值設定為 1
	var P = new Product(
		SearchProducts[ndx].id, 
		SearchProducts[ndx].name, 
		SearchProducts[ndx].retail_price, 
		SearchProducts[ndx].price,
		SearchProducts[ndx].qty
	);
	
	Products.push(P);

	var table = document.getElementById("table_order_details");
	var tr = table.insertRow(2);
	// tr.class = 'pid_' + ndx;
	jQuery(table.insertRow(2)).append(
		"<td><a>" + SearchProducts[ndx].name + "</a></td>" +
		"<td><input type=\"text\" onfocus='this.select()' id='pqty_" + ndx + "' value=\"" + SearchProducts[ndx].qty + "\" onblur=\"changeQty(" +  ndx + "," + SearchProducts[ndx].id + ")\"></td>" +
		"<td><input type=\"text\" onfocus='this.select()' id='pprice_" + ndx + "' value=\"" + SearchProducts[ndx].price + "\" onblur=\"changeQty(" +  ndx + "," + SearchProducts[ndx].id + ")\"></td>" +
		"<td><input type=\"text\" onfocus='this.select()' id='psubtotal_" + ndx + "' value=\"" + (SearchProducts[ndx].price * SearchProducts[ndx].qty) + "\"></td>" +
		"<td><a href=\"javascript:remove_product(" + ndx + ", " + SearchProducts[ndx].id + ")\">Remove</a></td>" +
		// "<td><select></select></td>"
		"<td></td>"
	).attr("class", "pid_" + ndx);
}

function remove_product (ndx, id) {
	jQuery(".pid_" + ndx).fadeOut('normal', function(){
		jQuery(this).remove();
	});
	
	for (var i = 0; i < Products.length; i++) {
		if (Products[i].id == id) {
			Products.remove(i);
		}
	}
	
	for (var i = 0; i < Products.length; i++) {
		if (Products[i].id == id) {
			Products[i].price = jQuery("#pprice_" + ndx).val();
			Products[i].qty   = jQuery("#pqty_" + ndx).val();
		}
	}
	jQuery("#psubtotal_" + ndx).val(
		jQuery("#pprice_" + ndx).val() * jQuery("#pqty_" + ndx).val()
	);
	getProductTax();		
	SumTotal();
	SumAmount();
	
}

function changeQty (ndx, id) {
	
	if (Products.length <= 0) {return};
	
	var Subtotal = jQuery("#pprice_" + ndx).val() * jQuery("#pqty_" + ndx).val();
	if (isNaN(Subtotal)) {
		alert('Subtotal filed is numeric only');
	} else {
		$.blockUI();
		for (var i = 0; i < Products.length; i++) {
			if (Products[i].id == id) {
				Products[i].price = jQuery("#pprice_" + ndx).val();
				Products[i].qty   = jQuery("#pqty_" + ndx).val();
			}
		}
		jQuery("#psubtotal_" + ndx).val(
			parseFloat(jQuery("#pprice_" + ndx).val() * jQuery("#pqty_" + ndx).val()).toFixed(2)
		);
		getProductTax();		
		SumTotal();
		SumAmount();
		jQuery.unblockUI({ fadeOut: 800 });
	}
}

function getProductTax () {
		
	var tax = parseFloat((DestinationState.tax_rate / 100));
	// console.log(getProductTotal());
	var p_tax = parseFloat(getProductTotal() * (tax)).toFixed(2);
	// console.log(p_tax);
	jQuery("#product_tax").val(p_tax);
	
}

function getProductTotal () {
	var Total = 0;
	for (var i = 0; i < Products.length; i++) {
		Total += Products[i].price * Products[i].qty;
	}
	return parseFloat(Total).toFixed(2);
}

function SumTotal () {
	var total = 0;
	for (var i = 0; i < Products.length; i++) {
		total += Products[i].price * Products[i].qty;
		// console.log(total);
	}
	jQuery("#total").val(parseFloat(total).toFixed(2));
}

function SumAmount () {
	var amount = parseFloat(jQuery("#total").val()) + 
				 parseFloat(jQuery("#product_tax").val()) + 
				 parseFloat(jQuery("#calculate_shipping").val());

	amount = amount - (jQuery("#hid_discount").val());
		
	if (jQuery("#hid_freeshipping").val() == 1) {
		if (jQuery("#shipping_method").val() == 1) {
			amount = (amount - 7.95);
		}
	}

	jQuery("#amount").val(parseFloat(amount).toFixed(2));
}

function getDiscountCode_rel_Products (id) {
	jQuery.post(base_url + 'ajax/discountcode_rel_products', {
		"id"  : id
	}, function(response){
		
		DiscountCode_Rel_Products = [];
		
		if (response.length >= 1) {
			for (var i = 0; i < response.length; i++) {
				var d_rel_p = new DiscountCode_Rel_Product(
					response[i].id, 
					response[i].d_id,
					response[i].pid
				);
				DiscountCode_Rel_Products.push(d_rel_p);
			}
		}		
	}, "json"
	);
}

// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
	var rest = this.slice((to || from) + 1 || this.length);
	this.length = from < 0 ? this.length + from : from;
	return this.push.apply(this, rest);
};

	
