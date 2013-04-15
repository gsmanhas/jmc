jQuery(document).ready(function(){

	

   	jQuery("#clear_voucher").click(function() {
		
   		jQuery("#frmMain").attr('action', base_url + 'viewcart');

   		jQuery("#method").val('clearVoucher');

   		jQuery("#frmMain").submit();

   	});

	

	jQuery("#clear_promo").click(function() {

		jQuery("#frmMain").attr('action', base_url + 'viewcart');

		jQuery("#method").val('clearPromo');

		jQuery("#frmMain").submit();

	});

	



	jQuery("#btnSaveOrder").click(function() {

		jQuery("#frmMain").attr('action', base_url + 'viewcart');

		jQuery("#method").val('checkout');

	});



	jQuery("#btnClear").click(function(){

		jQuery("#method").val('clear');

	});



	

});




function get_shipping_option(cid, action, replace_id, is_state){

	//jQuery("#zipcodeInput").val('');

	jQuery("#shipping_method").val('-1');

	

	if(is_state == 'yes'){

		jQuery("#state").val('-1');

	}

	

	ajaxFunction(action,cid, replace_id);

	update_shipping_option();

	

}



function update_shipping_option(){

	

	var action = '/ajax/update_shipping_option';

  	$.post(action, {		

		state: jQuery("#state").val(),

		zipcodeInput: jQuery("#zipcodeInput").val(),

		shipping_method: jQuery("#shipping_method").val(),

		vouchercodeInput: jQuery("#vouchercodeInput").val(),

		discountcodeInput: jQuery("#discountcodeInput").val()		

	},

   	function(data){	 

	      var str = data;		

	      var data_substr = str.split('|||');

		  var grand_total = '$'+data_substr[0];

		  var productTax = '$'+data_substr[1];

		  var free_shipping = '';

		  

		  if(data_substr[3] == 'yes'){

		    var free_shipping = '-$'+data_substr[2]+ ' Free Shipping';

			jQuery("#free_shipping_row").show();

		  }else {

			  jQuery("#free_shipping_row").hide(); 

		  }

		  

		 $('#grand_total').html(grand_total);					   

		 $('#productTax').html(productTax);					   

		 $('#free_shipping').html(free_shipping);					   

		 

		 $('#vouchercodeInput_td').html(data_substr[4]);					   

		 $('#discountcodeInput_td').html(data_substr[5]);					   
		 
		 $('#cart_listing').html(data_substr[6]);					   

		 

		}

 	);	

	

}



function removeItem (rowid) {

	jQuery("#method").val('remove');

	jQuery("#rowid").val(rowid);

	jQuery("#frmMain").submit();

}



function changeQty () {

	jQuery("#method").val('update');

	jQuery("#frmMain").submit();

}