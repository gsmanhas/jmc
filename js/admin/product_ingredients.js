

jQuery(document).ready(function(){
	
	jQuery("#chkAll").click(function(){
		
		// console.log(jQuery(':checkbox'));
		
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
		jQuery("#frmMain").attr('action', '<?php echo base_url() ?>admin/ingredients');
		jQuery("#action").attr('value', 'remove');
		jQuery("#id").val(ndx);
		jQuery("#frmMain").submit();
	}
}

function publish (ndx, state) {
	jQuery("#action").attr('value', 'publish');
	jQuery("#id").val(ndx);
	jQuery("#publish_state").val(state);
	jQuery("#frmMain").submit();
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
		alert('Please select an Ingredients from the list to trash');
	} else {
		if (confirm('Delete This Item(s) ?')) {
			jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ids);
			jQuery("#frmMain").submit();	
		}
	}

}

function orderSave () {
	
	var ids = [];
	jQuery(':checkbox').each(function(index, value){
		//if (jQuery(this).attr('checked') == true) {
			if (jQuery(this).val() != 0)
				ids.push(jQuery(this).val());
		//}
	});
	
	jQuery("#frmMain").attr('action', 'admin/ingredients');
	jQuery("#action").attr('value', 'order');
	jQuery("#id").val(ids);
	jQuery("#frmMain").submit();
}
