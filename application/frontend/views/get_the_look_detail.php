<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Get the Look - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="author" content="">
	<?php $this->load->view('base/head') ?>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="getthelook">
	
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
		<div id="pagetitle"><h1>Get the Look</h1></div>
		
		<div id="imagecontainer">
			<img src="<?php echo $this->get_the_look[0]->the_look ?>">
			<img src="<?php echo $this->get_the_look[0]->face_image ?>">
			<img src="<?php echo $this->get_the_look[0]->eyes_image ?>">
			<img src="<?php echo $this->get_the_look[0]->lips_image ?>">
			<img src="<?php echo $this->get_the_look[0]->hair_image ?>">
			<img src="<?php echo $this->get_the_look[0]->skin_image ?>">
		</div>
		
		<div id="desc">
			
			<div id="looktitle">
				<h2><?php echo $this->get_the_look[0]->title ?></h2>
			</div>
			<div id="lookdownload">
				<a href="<?php echo base_url() . $this->get_the_look[0]->download_this_look ?>">Download</a>
			</div>
			
			
			<ul class="tabs">
				<li class="active">
					<a href="#tab1">The Look</a>
				</li>
				<li>
					<a href="#tab2">Face</a>
				</li>
				<li>
					<a href="#tab3">Eyes</a>
				</li>
				<li>
					<a href="#tab4">Lips</a>
				</li>
				<li>
					<a href="#tab5">Hair</a>
				</li>
				<li>
					<a href="#tab6">Skin</a>
				</li>
			</ul>
			<div class="tab_container">
				<div id="tab1" class="tab_content">
					
					<div class="buybuttonswrapper">
					
						<div class="price">
							Buy This Look&nbsp;&nbsp;&nbsp;$<?php echo $this->LOOK_PRICE ?>
						</div>
						
						<div class="socialbuttons">
							<fb:like action='like' colorscheme='light' expr:href='data:<?php echo current_url() ?>'
							layout='button_count' show_faces='false' width='120'/></fb:like>
							<br>
							<a href="http://twitter.com/share" 
								class="twitter-share-button" 
								data-text="I love Josie Maran products! Check this one out: "
								data-url="<?php echo current_url() ?>" 
								data-count="none" data-via="josie_maran">Tweet</a>
						</div>
						
						<div class="buybuttons">
							<a href="/get-the-look/allbuy/<?php echo $this->get_the_look[0]->id ?>" class="addtocart2" >Add to Cart</a>
						
							<?php if ($this->session->userdata('user_id') != "") { ?>
								<a href="/get-the-look/add2wish/<?php echo $this->get_the_look[0]->id ?>" class="addtowishlist2">Add to Wishlist</a>
							<?php } else { ?>
								<a href="/signin" style="margin-top:5px" class="addtowishlist_nosignin" cid="" pid="">Add to Wishlist</a>
							<?php } ?>
						</div>

					</div>
					
				
					
				</div>
				
				<div id="tab2" class="tab_content">
					<?php echo $this->get_the_look[0]->face_desc ?>
				</div>
				
				<div id="tab3" class="tab_content">
					<?php echo $this->get_the_look[0]->eyes_desc ?>
				</div>
				
				<div id="tab4" class="tab_content">
					<?php echo $this->get_the_look[0]->lips_desc ?>
				</div>
				
				<div id="tab5" class="tab_content">
					<?php echo $this->get_the_look[0]->hair_desc ?>
				</div>
				
				<div id="tab6" class="tab_content">
					<?php echo $this->get_the_look[0]->skin_desc ?>
				</div>
			</div>
		</div>
		
		<?php $this->load->view('get_the_look_product_page') ?>
		
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>