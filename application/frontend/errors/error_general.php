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
	<title>Josie Maran Cosmetics | General Error</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<link rel="stylesheet" href="<?php echo base_url() ?>css/general.css" type="text/css" />
	<!--[if (IE 5)|(IE 6)|(IE 7)|(IE 8)|(IE 9)]> 
		<link href='<?php echo base_url() ?>css/iefixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]-->
	<!--[if IE 6]> 
		<link href='<?php echo base_url() ?>css/ie6fixes.css' media='screen' rel='stylesheet' type='text/css' />
	<![endif]--> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="<?php echo base_url() ?>js/shop.js" type="text/javascript" charset="utf-8"></script>
	<?php $CI->load->view('base/ga') ?>
</head>
<body id="error_404 message" onload="">
	
	<div id="header">
		<div id="logo">
			<a href="<?php echo base_url() ?>">Josie Maran, Luxury with a Conscience</a>
		</div>
		<?php $CI->load->view('base/utilities'); ?>
	</div>
	<div id="main">
		
		<div id="topnav">
			<?php $CI->load->view('base/menu') ?>
		</div>
		
		<div id="pagetitle"><h1>&nbsp;</h1></div>
		
		
		<div id="messagewrapper">
			<h2><?php echo $heading; ?></h2>
			<p><?php echo $message; ?></p>
		</div>
		
	</div>
	
	<?php $CI->load->view('base/footer') ?>

</body>
</html>