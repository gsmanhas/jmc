<!DOCTYPE HTML>
<html xmlns:og="http://opengraphprotocol.org/schema/"
xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title><?php echo $this->product[0]->meta_title ?></title>
	<meta name="Keywords" content="<?php echo moss_truncate(strip_tags($this->product[0]->meta_keywords), 1024) ?>" />
	<meta name="Description" content="<?php echo moss_truncate(strip_tags($this->product[0]->meta_description), 170) ?>" />
	<?php if ($this->product[0]->is_canonical == "1") {echo $this->product[0]->canonical_link;} ?>
	<?php $this->load->view('base/head') ?>
	<meta property="og:title" content="<?php echo $this->product[0]->title ?>"/>
    <meta property="og:url" content="<?php echo current_url() ?>"/>
    <meta property="og:image" content="<?php echo base_url() . substr($this->product[0]->large_image, 1, strlen($this->product[0]->large_image)) ?>"/>
    <meta property="og:site_name" content="Josie Maran Cosmetics"/>
    <meta property="fb:admins" content="polly@josiemarancosmetics.com"/>
    <meta property="og:description" content="<?php echo moss_truncate(strip_tags($this->product[0]->meta_description), 128) ?>"/>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="/js/jquery.raty.js"></script>
	<?php $this->load->view('base/ga') ?>
	<?php // $this->load->view('base/facebook') ?>
	<script type="text/javascript" charset="utf-8" src="/js/rateing.js"></script>
	<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
</head>
<body id="details">
	<div id="fb-root"></div>
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
		
		<!-- <div id="pagetitle"><h1><?php echo $this->product[0]->name ?></h1></div> -->
		<div id="pagetitle"><h1><?php echo $this->product[0]->title ?></h1></div>
		
		<div id="productimage">
			<?php
				if ($this->product[0]->video_path != "") {
			?>
			<div id="product_video">
				<a rel="shadowbox[];width=425;height=344;" href="<?php echo $this->product[0]->video_path ?>" class="reel-popup">Watch Video</a>
			</div>
			<?php
				}
			?>
			<img src="<?php echo $this->product[0]->large_image ?>" alt="" />
			<?php $this->load->view('product_rate'); ?>
		</div>
				
		<div id="productinfo">
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">
									
			<div id="buybuttonswrapper" >

					
					
					<div class="price">
						<span class="regularprice">
						<?php if ($this->product[0]->on_sale == 1) { ?>
						<?php echo "&#36;" . $this->product[0]->price ?>
						<?php } else { ?>
						<?php echo "&#36;" . $this->product[0]->retail_price ?>
						<?php } ?></span>
					</div>
					<div class="price"> 
						<?php if ($this->product[0]->on_sale == 1) { ?>
						<span class="discountedprice">&#36;<?php echo $this->product[0]->retail_price ?></span>
						<?php } ?>
					</div>
					
					<?php
						/**
						 * 需要加入判斷使用者的購物車 Item 中所加入的數量, 來判斷是否 Out of Stock
						 */
						$this->product[0]->in_stock -= (int)$this->ShoppingCart->get_cart_item_qty($this->product[0]->id);
					?>
					
					<div class="buybuttons">
						<?php if ($this->product[0]->in_stock <= 0 && $this->product[0]->can_pre_order == 1) { ?>
						<!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $this->product[0]->id ?>)" class="selectashade">Add to Cart</a>	 -->
						<a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>">Add to Cart</a>
						<?php } else if ($this->product[0]->in_stock <= 0 && $this->product[0]->can_pre_order == 0) { ?>
						<a href="#" class="outofstock">Out of Stock</a>
						<?php } else { ?>
						<!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $this->product[0]->id ?>)" class="selectashade">Add to Cart</a> -->
						<a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>">Add to Cart</a>
						<?php } ?>						
						<!-- <a href="javascript:AddWishList(<?php echo $this->Catalog[0]->id ?>, <?php echo $this->product[0]->id ?>);" class="addtowishlist">Add to Wishlist</a> -->
						<?php if ($this->session->userdata('user_id') != "") { ?>
							<a href="#" class="addtowishlist" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>">Add to Wishlist</a>
						<?php } else { ?>
							<a href="/signin" style="margin-top:5px" class="addtowishlist_nosignin" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>">Add to Wishlist</a>
						<?php } ?>
						
					</div>
					<div class="socialbuttons">
<fb:like href="<?php echo str_replace("http://sandbox.", "http://www." , current_url());?>"  send="false" header="false" colorscheme="light" layout="button_count" ></fb:like>
		
    			
						<a href="http://twitter.com/share" 
							class="twitter-share-button" 
							data-text="I love Josie Maran products! Check this one out: "  
							data-url="<?php echo current_url() ?>" 
							data-count="horizontal" data-via="josie_maran">Tweet</a>
					</div>
				<div style="float:left; width:210px;">
				<?php $this->load->view('shop/swatch') ?>		
				</div>	
				<input type="hidden" name="category_id" value="<?php echo $this->Catalog[0]->id ?>" id="category_id">
				<input type="hidden" name="product_id" value="<?php echo $this->product[0]->id ?>" id="product_id">
				<input type="hidden" name="qty" value="" id="qty">
				<input type="hidden" name="method" value="" id="method">
			<?php /*?><div style="clear:both" ></div><?php */?>
			</div>
			
			</form>
			
			<div id="colors" style="clear:both">
				<?php echo $this->load->view('shop/group_by') ?>
			</div>
			
			<div id="desc" style="clear:both">
				<h4>Description</h4>
				<p><?php echo $this->product[0]->description ?></p>
				<?php //$this->load->view('shop/swatch') ?>				
			</div>
			
			<ul class="tabs">
				<li class="active">
					<a href="#tab1">Being Responsible</a>
				</li>
				<li>
					<a href="#tab2">Ingredients</a>
				</li>
				<li>
					<a href="#tab3">How To Use</a>
				</li>
				<li>
					<a href="#tab4">Reviews</a>
				</li>
			</ul>
			<div class="tab_container">
				<div id="tab1" class="tab_content"><?php $this->load->view('shop/symbolkey') ?></div>
				<div id="tab2" class="tab_content"><p><?php echo $this->product[0]->ingredient ?></p></div>
				<div id="tab3" class="tab_content"><p><?php echo $this->product[0]->how_to_use ?></p></div>
				<div id="tab4" class="tab_content">
					<?php $this->load->view('shop/reviews') ?>
				</div>
			</div>
			
			<div id="tips">
				<h4>Josie's Tips</h4>
				<p><?php echo $this->product[0]->tips ?></p>
			</div>
			
			<?php /*?><div id="colors">
				<?php echo $this->load->view('shop/group_by') ?>
			</div><?php */?>
			
		</div>
		
		<?php echo $this->load->view('shop/works_well_with') ?>
		
		
	</div>
	<?php $this->load->view('base/footer') ?>

</body>
</html>
<script>

</script>
