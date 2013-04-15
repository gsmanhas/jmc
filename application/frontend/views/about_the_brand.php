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
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>

    <script type="text/javascript">
        jQuery(function() {
            if(location.hash != "") {
                jQuery(location.hash).click();
            }
        });
    </script>
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
		
		<div id="about-the-brand">

			<ul class="tabs">
				<li class="active">
					<a href="#" id="about-brand">About Josie Maran Cosmetics</a>
				</li>
				<li>
					<a href="#" id="view-icons">View Our Icons</a>
				</li>
			</ul>
			
			<div class="tab_container">
				<div id="tab1" class="tab_content">
					<?php echo $this->webpage[0]->page_content ?>
				</div>
				<div id="tab2" class="tab_content">
					<?php $this->load->view('view_our_icons') ?>
				</div>
			</div>
			
		</div>

		
		
		
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>