<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
	<script src="js/search.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
	
<script type="text/javascript">

function slideSwitch() {
    var $active = $('#slideshow IMG.active');
	
    if ( $active.length == 0 ) $active = $('#slideshow IMG:last');
	
	var bg_image = $('#slideshow IMG.active').attr('src');
    $("#center_content").css("background" , "Url('"+bg_image+"')");

    // use this to pull the images in the order they appear in the markup
    var $next =  $active.next().length ? $active.next()
        : $('#slideshow IMG:first');

    // uncomment the 3 lines below to pull the images in random order
    
    // var $sibs  = $active.siblings();
    // var rndNum = Math.floor(Math.random() * $sibs.length );
    // var $next  = $( $sibs[ rndNum ] );

     
    $active.addClass('last-active');

    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 1000, function() {
            $active.removeClass('active last-active');
        });
}

$(function() {
    setInterval( "slideSwitch()", 5000 );
});

</script>
<style>
#center_content {
	background-repeat:no-repeat !important; background-position:center!important;
	
}
</style>
</head>

<body id="home">

		
		<div id="header">
		 <div id="logo">
			 <a href="/">Josie Maran, Luxury with a Conscience</a>
		 </div>
		 <?php $this->load->view('base/utilities_home') ?>
	   </div>
	   
	   <div id="main">
	   <div id="center_content" style="background-image:url('./images/image1.jpg');">
	   		<?php $this->load->view('base/home_menu') ?>
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
   </div>
<?php $this->load->view('base/footer') ?>		
<div id="slideshow" style="display:none">
    <img src="./images/image1.jpg" alt="Slideshow Image 1" class="active" />
    <img src="./images/image2.jpg" alt="Slideshow Image 2" />
    <img src="./images/image3.jpg" alt="Slideshow Image 3" />
    <img src="./images/image4.jpg" alt="Slideshow Image 4" />
    <img src="./images/image5.jpg" alt="Slideshow Image 5" />
</div>
</body>
</html>