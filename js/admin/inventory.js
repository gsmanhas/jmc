
function updateAll () {
	var ids = [];
	jQuery(':checkbox').each(function(index, value){
		if (jQuery(this).attr('checked') == true) {
			if (jQuery(this).val() != 0)
				ids.push(jQuery(this).val());
		}
	});
	
	if (ids.length <= 0) {
		alert('Please select an Product Catalog(s) from the list to trash');
	} else {
		if (confirm('Delete This Item(s) ?')) {
			// jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ids);
			jQuery("#frmMain").submit();	
		}
	}
}

function saveItem (ndx) {
	var Inventory_Id = "#inventory_" + ndx;
	var Qty = "#qty_" + ndx; 
	var pre_orderId = "#can_pre_order_" + ndx;	
	jQuery("#id").val(jQuery(Inventory_Id).val());
	jQuery("#action").val("single_update");
}

function SaveAll () {
	var ids = [];
	jQuery(':checkbox').each(function(index, value){		
		// jQuery(this).attr('checked', 'true');
		ids.push(jQuery(this).attr('inventory_id'));
	});
	if (ids.length <= 0) {
		alert('請先新增一個產品');
	} else {
		jQuery("#action").val('saveAll');
		jQuery("#id").val(ids);
		jQuery("#frmMain").submit();
	}
}

function funChangeState (obj) {
	if (obj.checked == true) {
		obj.value = 1;
	} else {
		obj.value = 0;
	}
}

function sortByName () {
	jQuery("#action").val('byname');
	jQuery("#frmMain").submit();
}

function sortByInStock () {
	jQuery("#action").val('bystock');
	jQuery("#frmMain").submit();
}




