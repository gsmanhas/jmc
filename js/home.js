
var SlideShowId = 0;

jQuery(document).ready(function(){
	
	jQuery(".homebgcontainer").hide();
	jQuery(".homebgcontainer img").hide();
	jQuery(".homebgcontainer").eq(0).show();
	jQuery(".homebgcontainer img").eq(0).show();
	
	var container = jQuery(".homebgcontainer");
	var imgs = jQuery(".homebgcontainer img");
	var SlideShow = window.setInterval(function(){
		var ndx = (SlideShowId + 1);
		if (ndx >= 3) { ndx = 0 };
		$(imgs[SlideShowId]).animate({
			opacity : 0
		  }, {
			queue: true,
		    duration: 500,
			step: function(now, fx) {

			},
		    complete: function() {
				jQuery(".homebgcontainer").hide();
				jQuery(".homebgcontainer").eq(ndx).show();
				jQuery(".homebgcontainer img").eq(ndx).show().css({"opacity" : "0"});
				jQuery(".homebgcontainer img").eq(ndx).animate({
					opacity: 1
				}, { queue: true, duration: 600 });
		    }
		  });
		
		
		SlideShowId++;
		if (SlideShowId >= 3) { SlideShowId = 0 }
		
	}, 7500);
	
	// jQuery("#topnav").children().children().each(function(){
	// 					
	// 	jQuery(this).mouseenter(function(){
	// 		jQuery(this).children().each(function(){
	// 			jQuery(this).show();
	// 		});
	// 	}).mouseleave(function(){
	// 		jQuery(this).children().next().each(function(){
	// 			jQuery(this).hide();
	// 		});
	// 	});
	// 	
	// });
		
})



// 
// var SlideShowId = 0;
// 
// jQuery(document).ready(function(){
// 	
// 	var TimerNo = window.setInterval(function(){
// 		
// 		
// 		var ndx = (SlideShowId + 1);
// 		if (ndx >= 3) { ndx = 0 };
// 		
// 		
// 		jQuery(".homebgcontainer").each(function(index, value){
// 			if (SlideShowId == index) {
// 				jQuery(this).hide();
// 			}
// 		});
// 		
// 		jQuery(".homebgcontainer img").each(function(index, value){
// 			if (SlideShowId == index) {
// 				
// 				// jQuery(this).animate({
// 				// 	opacity : 5
// 				// },{
// 				// 	2000, 'linear',
// 				// 	step: function(now, fx) {
// 				// 		
// 				// 	}
// 				// });
// 				
// 				jQuery(this).animate({
// 				}, {
// 					queue : false, duration : 5000,
// 					step: function(now, fx) {
// 						jQuery(this).animate({opacity : 0});
// 					}
// 				});
// 				
// 				// jQuery(this).fadeTo("slow", 0.01, function(){
// 				// 	jQuery(".homebgcontainer img").each(function(index, value){
// 				// 		if (ndx == index) {
// 				// 			jQuery(this).fadeTo("slow", 1);
// 				// 		}
// 				// 	});			
// 				// });
// 				
// 			}
// 		});
// 		
// 		jQuery(".homebgcontainer").each(function(index, value){
// 			if (ndx == index) {
// 				jQuery(this).show();
// 			}
// 		});
// 		
// 
// 
// 		
// 		console.log(ndx + ' ' + SlideShowId);
// 		
// 		SlideShowId++;
// 		if (SlideShowId >= 3) { SlideShowId = 0 }
// 		
// 	}, 10000);
// 	
// });
// 
