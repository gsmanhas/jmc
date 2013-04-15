

jQuery(document).ready(function(){

	Shadowbox.setup(jQuery(".portfolio-popup"), {
	    displayNav:         true,
	    handleUnsupported:  "remove",
	    autoplayMovies:     false
	});

	Shadowbox.setup(jQuery(".reel-popup"), {
	    language	: 	'en',
		players		:	 ['html','iframe']
	});
		
	// jQuery("#preview").click(function(){
	// 
	// 	return false;
	// });
	
	jQuery("#portfolio-popup").click(function(){
		jQuery(".portfolio-popup").each(function(index, value){
			var ndx = (jQuery("#previewimg").attr('ndx') != undefined) ? (jQuery("#previewimg").attr('ndx')) : "0";
			if (index == ndx) {
				jQuery(this).click();
			}
		});
		return false;
	});
	
	jQuery("#enlarge").click(function(){
		jQuery(".portfolio-popup").each(function(index, value){
			var ndx = (jQuery("#previewimg").attr('ndx') != undefined) ? (jQuery("#previewimg").attr('ndx')) : "0";
			if (index == ndx) {
				jQuery(this).click();
			}
		});
		return false;	
	});
	
});


function load_image (mid, large, ndx, title, desc) {
	jQuery("#previewimg").attr('src', mid);
	jQuery("#previewimg").attr('large', large);	
	jQuery("#previewimg").attr('ndx', ndx);	
	
	jQuery(".desc").children("h5").text(title);
	jQuery(".desc").children("p").text(desc);
	
	


	
	
	// jQuery("#portfolio-popup").attr('href', large);
}

// function openWin () {
// 	
// 	var iMyWidth;
// 	var iMyHeight;
// 	//half the screen width minus half the new window width (plus 5 pixel borders).
// 	iMyWidth = (window.screen.width/2) - (75 + 10);
// 	//half the screen height minus half the new window height (plus title and status bars).
// 	iMyHeight = (window.screen.height/2) - (100 + 50);
// 		
// 	window.open(jQuery("#previewimg").attr('large'), 
// 	"Josie's Portfolio", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no," +
// 	"width=" + jQuery("#previewimg").width() + ",height=" + jQuery("#previewimg").height() +
// 	"top="+iMyWidth+",left="+iMyHeight+"px"
// 	);
// 	
// 	return false;
// }

function openWin() {

var iMyWidth;
var iMyHeight;
//half the screen width minus half the new window width (plus 5 pixel borders).
iMyWidth = (window.screen.width/2) - (75 + 10);
//half the screen height minus half the new window height (plus title and status bars).
iMyHeight = (window.screen.height/2) - (100 + 50);

//Open the window.
var win2 = window.open(jQuery("#previewimg").attr('large'),"Josie's ",
	"status=no,height="+jQuery("#previewimg").height()+",width="+jQuery("#previewimg").width()+",resizable=yes,left=" + iMyWidth + ",top=" + iMyHeight + ",screenX=" + iMyWidth + ",screenY=" + iMyHeight + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
win2.focus();
}


