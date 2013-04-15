jQuery(document).ready(function(){
	jQuery("#btnClear").click(function(){
		jQuery("#method").val('clear');
	});
	
	jQuery("#btnpromo").click(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('promo');
	});
	
	jQuery("#btnzipcode").click(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('zipcode');
	});


   	jQuery("#clear_voucher").click(function() {
   		jQuery("#frmMain").attr('action', base_url + 'viewcart');
   		jQuery("#method").val('clearVoucher');
   		jQuery("#frmMain").submit();
   	});

	jQuery("#btnvoucher").click(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('voucher');
	});
	
	jQuery("#btn_gift_code").click(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('gift_code');
	});
	
	jQuery("#btnSaveOrder").click(function() {
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('checkout');
	});

	jQuery("#shipping_method").change(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('changeState');
		jQuery("#frmMain").submit();
	});
	
	jQuery("#state").change(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('changeState');
		jQuery("#frmMain").submit();
	});

	jQuery("#pobox").click(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('changeState');
		jQuery("#frmMain").submit();
	});
    
	jQuery("#country").change(function(){
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('changeState');
		jQuery("#frmMain").submit();
	});
	
	jQuery("#clear_promo").click(function() {
		jQuery("#frmMain").attr('action', base_url + 'viewcart');
		jQuery("#method").val('clearPromo');
		jQuery("#frmMain").submit();
	});
	
});

function removeItem (rowid) {
	jQuery("#method").val('remove');
	jQuery("#rowid").val(rowid);
	jQuery("#frmMain").submit();
}

function changeQty () {
	jQuery("#frmMain").submit();
}