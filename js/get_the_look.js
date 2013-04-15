

jQuery(document).ready(function(){
	
	jQuery(".the_look_mail_image").each(function(index, value){
		jQuery(this).css({
			"opacity" : "0.65"
		});
		
		jQuery(this).mouseover(function(){
			jQuery(this).css({
				"opacity" : "1"
			});			
			
			jQuery(".lookpreview").each(function(ndx, value){
				if (index == ndx) {
					jQuery(this).show();
				} else {
					jQuery(this).hide();
				}
			});
			
		});
		
		jQuery(this).mouseout(function(){
			jQuery(this).css({
				"opacity" : "0.65"
			});
			// jQuery(".lookpreview").eq(index).hide();
		});
		
	});
	
	jQuery(".lookpreview").eq(0).show();
	
});