var SlideShowId = 0;

jQuery(document).ready(function(){

	jQuery(".slidecontainer").hide();
	jQuery(".slidecontainer img").hide();
	
	jQuery(".slidecontainer").eq(0).show();
	jQuery(".slidecontainer img").eq(0).show();

	var container = jQuery(".slidecontainer");
	var imgs = jQuery(".slidecontainer img");
	
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
				jQuery(".slidecontainer").hide();
				jQuery(".slidecontainer").eq(ndx).show();
				jQuery(".slidecontainer img").eq(ndx).show().css({"opacity" : "0"});
				jQuery(".slidecontainer img").eq(ndx).animate({
					opacity: 1
				}, { queue: true, duration: 600 });
				
		    }
		  });
		
		
		SlideShowId++;
		if (SlideShowId >= 3) { SlideShowId = 0 }
		
	}, 6000);

})
