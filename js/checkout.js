jQuery(document).ready(function(){

	jQuery("#shipSame").click(function(){
		if (jQuery(this).is(":checked")) {
			jQuery("#ship_firstname").attr("disabled", "true").val("");
			jQuery("#ship_lastname").attr("disabled" , "true").val("");
			jQuery("#ship_address").attr("disabled"  , "true").val("");
			jQuery("#ship_address2").attr("disabled"  , "true").val("");
			jQuery("#ship_city").attr("disabled"     , "true").val("");
			jQuery("#ship_zipcode").attr("disabled"  , "true").val("");
		} else {
			jQuery("#ship_firstname").removeAttr("disabled");
			jQuery("#ship_lastname").removeAttr("disabled");
			jQuery("#ship_address").removeAttr("disabled");
			jQuery("#ship_city").removeAttr("disabled");
			jQuery("#ship_state").removeAttr("disabled");
			jQuery("#ship_zipcode").removeAttr("disabled");
		}
	});
		
	jQuery("input[name='payment_method']").click(function(){
		(jQuery(this).val() == 1) ? jQuery("#card_information").show(300) : jQuery("#card_information").hide(300);
		(jQuery(this).val() == 1) ? jQuery(".error").show(300) : jQuery(".error").hide(300);
		
		(jQuery(this).val() == 2) ? jQuery("#paypal_information").show(300) : jQuery("#paypal_information").hide(300);
		
		
		
	});

	jQuery("#btnSubmit").submit();

    jQuery("#bill_country").change(function() {
        $.post('/ajax/get_states', {id:jQuery(this).val()}, function(msg) {
            if(msg != null && msg.data.length > 0) {
                jQuery("#bill_state").empty();
                jQuery("#bill_state").append('<option selected="selected" value="">Please Select</option>');
                jQuery.each(msg.data, function(i, val){
                    jQuery("#bill_state").append(jQuery('<option value="'+val.id+'">'+val.state+'</option>'));
                });
            }
        });
    });

});