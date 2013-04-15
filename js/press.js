

jQuery(document).ready(function(){
	
	// jQuery("a").hide();
	
	var div = document.createElement('div');
	jQuery(div).css({
		"width" : "100%",
		"height" : "100%",
		"top" : "0",
		"left" : "0",
		"z-index" : 10000,
		"position" : "absolute",
		"background-color" : "#dddddd",
		"opacity" : "0.01"
	});
	
	jQuery('document, body').append(div);
	
	
	// jQuery(".wrapper").children('div').children('a').each(function(){
	// 	jQuery(this).hide();
	// });
	
	Shadowbox.init({
		"handleOversize" : "drag"
	});
	
	// jQuery(".wrapper").children('div').children('a').each(function(){
	// 	jQuery(this).fadeIn(1200);
	// });
	
	var MrTimer = window.setInterval(function(){
		jQuery(div).hide();
		window.clearInterval(MrTimer);
	}, 1000);
	

	
	
	
});




