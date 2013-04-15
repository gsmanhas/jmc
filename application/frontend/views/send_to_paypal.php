<html>
<head>
	<?php $this->load->view('base/head') ?>
	<?php # $this->load->view('base/ga') ?>
	
	<?php		
	
	if (isset($this->Order) && count($this->Order) >= 1) {
	?>

	<script type="text/javascript"> 
	   var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www."); 
	   document.write("<script src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'>" + "</sc" + "ript>"); 
	</script>

	<script type='text/javascript'>
	var pageTracker = _gat._getTracker("UA-11098190-20");
	pageTracker._initData();
	pageTracker._trackPageview();

	var timeObj     = new Date;
	var unixTimeMs  = timeObj.getTime();
	var unixTime    = parseInt(unixTimeMs / 1000);
	var orderID     = '<?php echo $this->Order[0]->order_no ?>';

	pageTracker._addTrans(
		'<?php echo $this->Order[0]->order_no ?>',
		'Josie Maran Cosmetics',
		'<?php echo $this->Order[0]->amount ?>',
		'<?php echo $this->Order[0]->product_tax ?>',			
		'<?php echo $this->Order[0]->shipping_price ?>',
		'<?php echo $this->Order[0]->ship_city ?>',
		'<?php echo $this->Order[0]->ship_state ?>',
		'USA'
	  );

	<?php foreach ($this->OrderList as $item): ?>
	pageTracker._addItem(
	    '<?php echo $this->Order[0]->order_no ?>',      // Order ID
	    '<?php echo $item->sku ?>',						// SKU
	    '<?php echo $item->name ?>',   					// Product Name
	    '',                            					// Category
	    '<?php echo number_format($item->price, 2) ?>',	// Price
	    '<?php echo $item->qty ?>'                     	// Quantity
	);
	<?php endforeach ?>
	pageTracker._trackTrans();
	</script>

	<?php
	}
	?>

</head>

<body>
	

<?php	
if($this->input->post('email') == "Testing@sixspokemedia.com" || $this->input->post('email') == "TestingJMC@sixspokemedia.com" || $this->input->post('email') == "devteamintenss@gmail.com") { ?>
<form id="frmMain2" action="https://sandbox.paypal.com/cgi-bin/webscr" method="post">
<?php } else { ?>
<form id="frmMain2" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<?php } ?>
	
	<input type="hidden" name="cmd" value="_cart">
	<input type="hidden" name="rm" value="2" />
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="currency_code" value="US" />
	
	<input type="hidden" name="return" value="<?php echo base_url() . "thankyou" ?>" />
	<input type="hidden" name="cancel_return" value="<?php echo base_url() . "viewcart" ?>" />
	<input type="hidden" name="business" value="coco@josiemarancosmetics.com">
	<?php /*?><input type="hidden" name="paymentaction" value="authorization" /><?php */?>
	<?php /*?><input type="hidden" name="business" value="tanvee_1339745566_biz@yahoo.com"><?php */?>
	
	
	<input type="hidden" name="invoice" value="<?php echo $this->INVOICE_NUM ?>" />
	<input type="hidden" name="no_note" value="1" />
		
	<input type="hidden" name="first_name" value="<?php echo $this->input->post('firstname') ?>" />
	<input type="hidden" name="last_name" value="<?php echo $this->input->post('lastname') ?>" />
	
	<input type="hidden" name="city" value="<?php echo $this->CITY ?>" />
	<input type="hidden" name="address1" value="<?php echo $this->ADDR ?>" />
	<input type="hidden" name="address2" value="" />
	<input type="hidden" name="zip" value="<?php echo $this->ZIPCODE ?>" />
	<input type="hidden" name="state" value="<?php echo $this->input->post('ship_state') ?>" id="state">
	<input type="hidden" name="country" value="US" />
	
	<input type="hidden" name="charset" value="utf-8" id="charset">
	<input type="hidden" name="lc" value="us" />
	
	<input type="hidden" name="email" value="<?php echo $this->input->post('email') ?>" />
	
	
	<?php
	
	$counter = 1;
	foreach($this->cart->contents() as $items) {			
	$price_item = $items['price'];
	?>
		<input type="hidden" name="amount_<?=$counter?>" value="<?php echo number_format($price_item, 2) ?>" id="amount_<?=$counter?>">
		<input type="hidden" name="item_name_<?=$counter?>" value="<?php echo $items['name']; ?>" id="item_name_<?=$counter?>">
		<input type="hidden" name="item_number_<?=$counter?>" value="<?php //echo $item->sku ?>" id="item_number_<?=$counter?>">
		<input type="hidden" name="quantity_<?=$counter?>" value="<?php echo $items['qty']; ?>" id="quantity_<?=$counter?>">
	<?php 
		$counter++;
		}
		$this->cart->destroy();
	?>
	
	<input type="hidden" name="discount_amount_cart" value="0" />
	
	
</form>

<script type="text/javascript" charset="utf-8">
	var TimerID = 0;
	TimerID = window.setInterval(function(){
		document.getElementById("frmMain2").submit();
		window.clearInterval(TimerID);
	}, 1000);
</script>

</body>
</html>
