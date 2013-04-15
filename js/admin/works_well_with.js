

jQuery(document).ready(function(){

});

function update (ndx) {
	jQuery("#action").attr('value', 'update');
	jQuery("#id").val(ndx);
	jQuery("#frmMain").submit();
}

