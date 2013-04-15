

jQuery(document).ready(function(){

});

function update (ndx) {
	jQuery("#action").attr('value', 'update');
	jQuery("#id").val(ndx);
	jQuery("#frmMain").submit();
}

function orderSave () {
	jQuery("#frmMain").attr('action', 'admin.php/groupby');
	jQuery("#action").attr('value', 'order');
	jQuery("#frmMain").submit();
}

