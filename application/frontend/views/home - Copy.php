<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<!--[if (IE 5)|(IE 6)|(IE 7)|(IE 8)|(IE 9)]> 
		<link href='css/iefixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]-->
	<!--[if IE 6]> 
		<link href='css/ie6fixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]-->
	<link rel="stylesheet" href="/css/home.css" type="text/css" media="screen" title="" charset="utf-8">	
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.3.custom.min.js"></script>
	<script type="text/javascript" src="js/fullbackground.js"></script>
	<script src="js/search.js" type="text/javascript" charset="utf-8"></script>
	<?php /*?><script src="js/home.js" type="text/javascript" charset="utf-8"></script><?php */?>
	<?php $this->load->view('base/ga') ?>
	<noscript><meta http-equiv="refresh" content="0;URL=/error-js" /></noscript>
	
</head>

<body id="home">
	
	
	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $this->load->view('base/utilities_home') ?>
	</div>
	
	
	
	<div id="main">
		<?php $this->load->view('base/home_menu') ?>		
		
		<div id="supersize">
	
	 <?php
	 	$bg_counter = 1;
	 	 for($i = 0; $i<3; $i++){ 
		 if($this->HomeBanners[$i]->banner_status == 'Y') {
		 
	 ?>
		<div class="homebgcontainer" <?php if($i == 1) { ?> style="display:block" <?php } ?>>
			<img src="<?php echo $this->HomeBanners[$i]->image ?>" alt="<?php echo $this->HomeBanners[$i]->title ?>" style="display:block">
			<a href="<?php echo $this->HomeBanners[$i]->url ?>">foo</a>
			<h1><?php echo $this->HomeBanners[$i]->title ?></h1>
			<p><?php echo $this->HomeBanners[$i]->text ?></p>
		</div>
	
	 <?php $bg_counter++; } } $bg_counter--; ?>	
	

<?php if($bg_counter > 1 )	{ ?>
<script >


		
		var SlideShowId = 0;

jQuery(document).ready(function(){
	
	jQuery(".homebgcontainer").hide();
	jQuery(".homebgcontainer img").hide();
	jQuery(".homebgcontainer").eq(0).show();
	jQuery(".homebgcontainer img").eq(0).show();
	
	var container = jQuery(".homebgcontainer");
	var imgs = jQuery(".homebgcontainer img");
	var SlideShow = window.setInterval(function(){
		var ndx = (SlideShowId + 1);
		if (ndx >= <?php echo $bg_counter; ?>) { ndx = 0 };
		$(imgs[SlideShowId]).animate({
			opacity : 0
		  }, {
			queue: true,
		    duration: 500,
			step: function(now, fx) {

			},
		    complete: function() {
				jQuery(".homebgcontainer").hide();
				jQuery(".homebgcontainer").eq(ndx).show();
				jQuery(".homebgcontainer img").eq(ndx).show().css({"opacity" : "0"});
				jQuery(".homebgcontainer img").eq(ndx).animate({
					opacity: 1
				}, { queue: true, duration: 600 });
		    }
		  });
		
		
		SlideShowId++;
		if (SlideShowId >= <?php echo $bg_counter; ?>) { SlideShowId = 0 }
		
	}, 7500);
	
	// jQuery("#topnav").children().children().each(function(){
	// 					
	// 	jQuery(this).mouseenter(function(){
	// 		jQuery(this).children().each(function(){
	// 			jQuery(this).show();
	// 		});
	// 	}).mouseleave(function(){
	// 		jQuery(this).children().next().each(function(){
	// 			jQuery(this).hide();
	// 		});
	// 	});
	// 	
	// });
		
})

		
	</script>
<?php } ?>	
		
	</div>
		
		
		<div id="banners">
			<div id="banner1">
				<a href="<?php echo $this->HomeBanners[5]->url ?>"><img src="<?php echo $this->HomeBanners[5]->image ?>" alt="<?php echo $this->HomeBanners[5]->title ?>" /></a>
				<h2><?php echo $this->HomeBanners[5]->title ?></h2>
				<p><?php echo $this->HomeBanners[5]->text ?></p>
			</div>
			<div id="banner2">
				<a href="<?php echo $this->HomeBanners[6]->url ?>"><img src="<?php echo $this->HomeBanners[6]->image ?>" alt="<?php echo $this->HomeBanners[6]->title ?>" /></a>
				<h2><?php echo $this->HomeBanners[6]->title ?></h2>
				<p><?php echo $this->HomeBanners[6]->text ?></p>
			</div>
			<div id="banner3">
				<a href="<?php echo $this->HomeBanners[7]->url ?>"><img src="<?php echo $this->HomeBanners[7]->image ?>" alt="<?php echo $this->HomeBanners[7]->title ?>" /></a>
				<h2><?php echo $this->HomeBanners[7]->title ?></h2>
				<p><?php echo $this->HomeBanners[7]->text ?></p>
			</div>
		</div>	
	</div>
	<?php $this->load->view('base/footer') ?>
</body>
</html>

