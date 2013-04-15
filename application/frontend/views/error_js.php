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
	<title>Javascript Turned Off</title>
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
			<h2>You Have Javascript Turned Off</h2>
			<p>Javascript is required to browse and shop JosieMaran.com.<br>
			Please be sure that JavaScript and Cookies are enabled; both are required to complete online transactions.</p>
		</div>
		
		
		
	</div>
	
	<?php $CI->load->view('base/footer') ?>

</body>
</html>