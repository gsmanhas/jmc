<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Testimonials - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
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
		
		<div id="pagetitle"><h1>Testimonials</h1></div>
		
		<?php $i = 0; ?>
		<?php foreach ($this->Testimonials as $testimonial): ?>
		<?php echo ($i % 2 == 0) ? '<div class="ingredients-row">' : '' ?>
		<div class="<?php echo ($i % 2 == 0) ? "ingredients-box-left" : "ingredients-box-right" ?> testimonials">
			<img src="<?php echo $testimonial->image ?>" />
			<div style="width:249px;height:auto;overflow:hidden;float:left;">
				<h2><?php echo $testimonial->customer_name ?></h2>
				<?php echo $testimonial->quote?>
			</div>
		</div>
		<?php echo ($i % 2 == 0) ? '' : '</div>' ?>
		<?php $i++; ?>
		<?php endforeach ?>
		
		
		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

