jQuery(document).ready(function(){

	jQuery("#shipSame").click(function(){
		if (jQuery(this).attr("checked") == true) {
			jQuery("#ship_address").attr("disabled"  , "true").val("");
			jQuery("#ship_city").attr("disabled"     , "true").val("");
			jQuery("#ship_state").attr("disabled"    , "true").attr("selectedIndex", "0");
			jQuery("#ship_zipcode").attr("disabled"  , "true").val("");
		} else {
			jQuery("#ship_address").attr("disabled"  , "");
			jQuery("#ship_city").attr("disabled"     , "");
			jQuery("#ship_state").attr("disabled"    , "");
			jQuery("#ship_zipcode").attr("disabled"  , "");
		}
	});

});



