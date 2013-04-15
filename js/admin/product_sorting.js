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

function publish (ndx, state) {
	jQuery("#frmMain").attr('action', '');
	jQuery("#action").attr('value', 'publish');
	jQuery("#id").val(ndx);
	jQuery("#publish_state").val(state);
	jQuery("#frmMain").submit();
}


function orderSave () {
	
	var ids = [];
	jQuery(':checkbox').each(function(index, value){
		if (jQuery(this).val() != 0)
			ids.push(jQuery(this).val());
	});
	
	jQuery("#action").val('order');
	jQuery("#pid").val(ids);
	jQuery("#frmMain").submit();
}

function funChangeState (obj) {
	if (obj.checked == true) {
		obj.value = 1;
	} else {
		obj.value = 0;
	}
}