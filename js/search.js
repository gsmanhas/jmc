



jQuery(document).ready(function(){
			
	jQuery("#txtsearch").fadeIn().focus();
	jQuery("#btngo").fadeIn();		

	jQuery("#btnSearch").click(function(){
		jQuery(this).fadeOut(600);
		// jQuery("#txtsearch").fadeIn().focus().val('search').select();
		jQuery("#txtsearch").fadeIn().focus();
		jQuery("#btngo").fadeIn();
	});
	
	jQuery("#btngo").click(function(){
		jQuery("#searchForm").submit();
	});
	
	jQuery("#signup_for_email").focus(function(){
		jQuery(this).val((jQuery(this).val() == "sign up for email") ? "" : jQuery(this).val());
	}).blur(function(){
		jQuery(this).val((jQuery(this).val() == "") ? "sign up for email" : jQuery(this).val());
	});
	
	jQuery("#txtsearch").focus(function(){
		jQuery(this).val((jQuery(this).val() == "search") ? "" : jQuery(this).val());
	}).blur(function(){
		jQuery(this).val((jQuery(this).val() == "") ? "search" : jQuery(this).val());
		//jQuery(this).fadeOut();
		//jQuery("#btngo").fadeOut();
		//jQuery("#btnSearch").fadeIn(600);
	});

	jQuery("#go").click(function() {
		document.getElementById("icpsignup7867").submit();
	})
	
});
