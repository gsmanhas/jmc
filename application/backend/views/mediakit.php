<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php $this->load->view('base/head'); ?>
</head>
<body>
	<div id="header">
		<?php 
			$this->load->view('base/account');
			# 載入 Menu
			$this->load->view('base/menu'); 
		?>
	</div>
	
	<div id="page-wrapper" class="fluid">
		<div id="main-wrapper">
			<div id="main-content">
				
				<div class="page-title ui-widget-content ui-corner-all">
					<h1><b>Media Center</b></h1>
					<div class="other">
						<div class="float-left">The Media Center is where you store all of your website's images, media files and other related files.</div>
						<div class="button float-right">
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<iframe src="/kcfinder/browse.php?type=files&dir=files/public" width="100%" height="800px"></iframe>
			</div>			
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		<?php
		$this->load->view('base/footer');
		?>		
	</div>
	
</body>
</html>