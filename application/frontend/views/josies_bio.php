<!DOCTYPE HTML>
<html>
<head>	
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title><?php echo $this->webpage[0]->page_title ?></title>
	<meta name="Keywords" content="<?php echo $this->webpage[0]->meta_keyword ?>" />
	<meta name="Description" content="<?php echo $this->webpage[0]->meta_description ?>" />
	<meta name="author" content="<?php echo $this->webpage[0]->author ?>">
	<?php $this->load->view('base/head') ?>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/jquery.scrollTo-1.3.3.js" type="text/javascript"></script>
	<script src="/js/jquery.localscroll-1.2.5.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/jquery.serialScroll-1.2.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/coda-slider.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/Genmetry.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		Shadowbox.init({
			skipSetup	: 	true
		});
	</script>
	<script src="/js/josies_bio.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="bio">

	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $this->load->view('base/utilities') ?>
	</div>
	
	<div id="main">
		<div id="topnav">
			<?php $this->load->view('base/menu') ?>
		</div>
		<div id="pagetitle"><h1><?php echo $this->webpage[0]->page_name ?></h1></div>
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">
		
		<?php echo $this->webpage[0]->page_content; ?>
				
		<?php $this->load->view('base/special_page_bio') ?>
		
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