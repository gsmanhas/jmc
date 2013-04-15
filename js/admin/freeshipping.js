
jQuery(document).ready(function(){
	
	jQuery("#chkAll").click(function(){
				
		if (jQuery(this).attr('checked') == true) {
			jQuery(':checkbox').each(function(index, value){
				jQuery(this).attr('checked', 'true');
			});
		} else {
			jQuery(':checkbox').each(function(index, value){
				jQuery(this).attr('checked', '');
			});
		}
		
	});
	
});


function trash (ndx) {
	if (confirm('Delete this Item ?')) {
		jQuery("#action").attr('value', 'remove');
		jQuery("#id").val(ndx);
		jQuery("#frmMain").submit();
	}
}

function update (ndx) {
	jQuery("#action").attr('value', 'update');
	jQuery("#id").val(ndx);
	jQuery("#frmMain").submit();
}

function removeAll () {
	var ids = [];
	jQuery(':checkbox').each(function(index, value){
		if (jQuery(this).attr('checked') == true) {
			if (jQuery(this).val() != 0)
				ids.push(jQuery(this).val());
		}
	});
	
	if (ids.length <= 0) {
		alert('Please select an Free Shipping(s) from the list to trash');
	} else {
		if (confirm('Delete This Item(s) ?')) {
			jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ids);
			jQuery("#frmMain").submit();	
		}
	}

}