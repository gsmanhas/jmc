<?php
	$CI =& get_instance();
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Please Upgrade Your Browser</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="/css/general.css" type="text/css" />
	<!--[if (IE 5)|(IE 6)|(IE 7)|(IE 8)|(IE 9)]> 
		<link href='/css/iefixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]-->
	<!--[if IE 6]> 
		<link href='/css/ie6fixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]--> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<?php $CI->load->view('base/ga') ?>
</head>
<body id="message" onload="">
	
	<div id="header">
		<div id="logo">
			<a href="/">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $CI->load->view('base/utilities'); ?>
	</div>
	<div id="main">
		
		<div id="topnav">
			<?php $CI->load->view('base/menu') ?>
		</div>
		
		<div id="pagetitle"><h1>&nbsp;</h1></div>
		
		<div id="messagewrapper">
			<h2>Upgrade your browser to view our site and start a better browsing future today!</h2>
			<p>We’ve incorporated some really cool new features that are not supported by your browser. To view our site as well as many others in their full form, we recommend that you invest some time now to download a modern web browser.</p>
			<p>This site is best viewed with Chrome, Firefox 3, Safari 3, Internet Explorer 7 or higher and a minimum monitor resolution of 1024 x 768 or higher. Please keep in mind that monitors display colors differently based upon their settings.</p>
		</div>
				
	</div>
	
	<?php $CI->load->view('base/footer') ?>

</body>
</html>