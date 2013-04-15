<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Press - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="author" content="">
	<?php $this->load->view('base/head') ?>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/jquery.scrollTo-1.3.3.js" type="text/javascript"></script>
	<script src="/js/jquery.localscroll-1.2.5.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/jquery.serialScroll-1.2.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/coda-slider.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/Genmetry.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/press.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="press">

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
		<div id="pagetitle"><h1>Press</h1></div>
				
		<div id="slider">
			<div class="navigationbar" style="">
				<ul class="navigation">
				<?php $i = 0; $j = 0; ?>
				<?php foreach ($this->press as $item): ?>
				<?php if ($i == 0 || ($i % 10 == 0)) { ?>
					<li><a class="selected" href="#p<?php echo ++$j ?>">&nbsp;</a></li>
				<?php } ?>
				<?php $i++; ?>
				<?php endforeach ?>
				</ul>
			</div>
			<div class="scroll">
				<div class="scrollContainer">
				<?php
				$i = 0; $j = 0;
				foreach ($this->press as $item) {
					if ($i == 0 || $i % 10 == 0) {
						$j++;
						echo "<div id='p".$j."' class='panel'>";
					}
				?>					
				<div class="wrapper">
					<div style="height:212px;margin-bottom:8px;">
						<a href="<?php echo $item->clip ?>" rel="shadowbox[press];" title="<?php echo $item->title ?>">
								<img src="<?php echo $item->cover ?>" alt="<?php echo $item->title ?>" />
						</a>
					</div>
					<h6><?php echo $item->title ?></h6>
					<p><?php echo $item->at_date ?></p>
				</div>
				<?php

					$i+=1;
					if ($i % 10 == 0) {
						echo "</div>";
					}
				}
				?>
				</div>
			</div>
		</div>
		
		
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>