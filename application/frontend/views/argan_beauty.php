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
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		Shadowbox.init({
			language	: 	'en',
			players		:	 ['html','iframe']
		});
	</script>
	<script src="/js/arganbeautySlides.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/quickview.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="">
	
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
		<?php $this->load->view('base/special_page') ?>
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