
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

    jQuery("#bulk_print").click(function() {
        var $list = jQuery("input:checkbox.checkbox:checked");
        if($list.length > 0) {
            if (confirm('Print This Item(s) ?')) {
                var ids = [];
                $list.each(function(index, value){
                        if (jQuery(this).val() != 0)
                            ids.push(jQuery(this).val());
                });

                var id_string = ids.join(";");

                window.open("/printer/bulkprint?ids="+id_string);
                $list.removeAttr("checked");
            }

        } else {
            alert('Please select an Order(s) from the list to print');
        }
    });
    jQuery("#change_status").click(function() {
        var $list = jQuery("input:checkbox.checkbox:checked");
        if($list.length > 0) {
            if (confirm('Mark as Shipped & Email Customer ?')) {
                var ids = [];
                $list.each(function(index, value){
                        if (jQuery(this).val() != 0)
                            ids.push(jQuery(this).val());
                });

                var id_string = ids.join(";");

                $.post('/admin.php/orders/changestate', {ids:id_string, state: 4}, function (msg){
                    if(msg.success == 1) {
                        jQuery("input:checkbox.checkbox:checked").removeAttr("checked");
                        window.location.reload();
                    }
                });
            }

        } else {
            alert('Please select an Order(s) from the list first');
        }
    });

    jQuery("#chkAll, input.checkbox").click(function() {
        if(jQuery("input.checkbox:checked").length > 0) {
            jQuery("#order_state").show();
            jQuery("#change_status").show();
        } else {
            jQuery("#order_state").hide();
            jQuery("#change_status").hide();
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
		alert('Please select an Order(s) from the list to trash');
	} else {
		if (confirm('Delete This Item(s) ?')) {
			jQuery("#action").attr('value', 'remove');
			jQuery("#id").val(ids);
			jQuery("#frmMain").submit();	
		}
	}

}
