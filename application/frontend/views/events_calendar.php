<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Events Calendar - Josie Maran Cosmetics</title>
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
		
		<div id="pagetitle"><h1 style="margin-bottom:0;">Events Calendar</h1></div>
		
		<div id="masthead">
			<img alt="events" src="/images/files/events/masthead-events.jpg" />
		</div>
		
		<div style="clear:both;">
		
		<iframe src="gcalendar-wrapper.php?showTitle=0&amp;showNav=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=500&amp;wkst=1&amp;hl=en&amp;bgcolor=%23FFFFFF&amp;src=josiemarancalendar%40gmail.com&amp;color=%23B1365F&amp;ctz=America%2FLos_Angeles" style=" border-width:0 " width="980" height="500" frameborder="0" scrolling="no"></iframe>
		
		<!--
<iframe src="gcalendar-wrapper.php?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=500&amp;wkst=1&amp;hl=en&amp;bgcolor=%23FFFFFF&amp;src=josiemarancalendar%40gmail.com&amp;color=%237A367A&amp;ctz=America%2FLos_Angeles" style=" border-width:0 " width="980" height="500" frameborder="0" scrolling="no"
		></iframe>
-->
		
		</div>
			
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

