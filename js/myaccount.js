



jQuery(document).ready(function(){
	
	jQuery(".order_no").each(function(index, value){
		
		jQuery(this).click(function(){
			
			// console.log(jQuery(this).attr('href'));
			
			Shadowbox.open({
				modal:      true,
				displayNav: false,
			    handleUnsupported:  "remove",
				content:    "/view-order/" + jQuery(this).attr('id'),
				player: 	'iframe',
				width:      "920",
				height:     "478",
				onClose:    "onShClose"
			});
			
			
			return false;
			
		});
	
	});
	
});