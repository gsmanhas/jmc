<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Wishlist - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/myaccount_wishlist.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
	<script type="text/javascript" charset="utf-8">
		var base_url = "<?php echo base_url() ?>";
	</script>
</head>
<body id="myaccount">
	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $this->load->view('base/utilities') ?>
	</div>
	<div id="main" style="position:relative;">
		
		<div id="topnav">
			<?php $this->load->view('base/menu') ?>
		</div>
		
		<div id="pagetitle"><h1>Wishlist</h1></div>
		
		<?php $this->load->view('myaccount/left_menu') ?>
		
		<div style="position:absolute;right:0;top:68px;">
			<a href="#" class="send_wishlist_to_my_friends">Tell my friends</a>
		</div>
		
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">		
			<?php $this->load->view('myaccount/wishlist') ?>
			<input type="hidden" name="category_id" value="" id="category_id">
			<input type="hidden" name="product_id" value="" id="product_id">
			<input type="hidden" name="qty" value="" id="qty">
			<input type="hidden" name="method" value="" id="method">
		</form>
		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

