

jQuery(document).ready(function(){			

	jQuery("#rate_this_product").raty({
		scoreName:  'rate_this_product',
		number:     5,
		width: "300px"
	});
	
	jQuery("#readmore_reviews").click(function(){
				
		jQuery(".product_rating_list").each(function(index, value){
			if (index == 2) {
				jQuery(this).attr('class', 'product_rating_list');
			}
		});
		
		jQuery(".product_rating_list").each(function(index, value){
			jQuery(this).show(600);
		});
		
		jQuery(this).hide();
			
		return false;
		
	});
	
	jQuery(".review_morelink").each(function(index, value){
		
		jQuery(this).click(function(){
			// console.log(jQuery(this).parent());
			jQuery(this).parent().children().show(600);
			jQuery(this).hide();
			return false;
		});
		
	});
	
	// jQuery(".product_rating_list:").each(function(index, value){
	// 	console.log(jQuery(this).eq());
	// });
	
	jQuery(".product_rating_list").each(function(index, value){
		if (index == 2) {
			var oldClass = jQuery(this).attr('class');
			jQuery(this).attr('class', oldClass + ' last');
		}
	});
	
	var oldClass = jQuery(".product_rating_list:last").attr('class');
	jQuery(".product_rating_list:last").attr('class', oldClass + ' last');
	
});