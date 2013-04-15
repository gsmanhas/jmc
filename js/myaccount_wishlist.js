

jQuery(document).ready(function(){
	
	
	jQuery(".send_wishlist_to_my_friends").click(function(){
		
		Shadowbox.init({
			skipSetup:  true
		});
		Shadowbox.open({
			modal:      false,
			displayNav: true,
		    handleUnsupported:  "remove",
			content:    "/send-wishlist",
			player: 	"iframe",
			width:      "550",
			height:     "400"
		});
		
	});
		
	
});