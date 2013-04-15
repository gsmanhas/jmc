<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body>
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
		
		<div id="pagetitle"><h1>Search</h1></div>
		
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">
			
		<?php
			if (count($this->SearchResult) >= 1) {
		?>
		<div id="workswellwith">
			<?php
			foreach ($this->SearchResult as $works) {
				$query = $this->db->query(
					"SELECT id, name, small_image, title, price, retail_price, on_sale, url, can_pre_order, in_stock, " .
					"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
					"(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
					"FROM product as p WHERE id = " . $works->id
				);
				$works_well_with = $query->result();
			?>
			<div class="featuredproduct" style="margin-top:0;margin-bottom:30px;">
				<div class="image">
					<a href="/shop/<?php echo strtolower($works_well_with[0]->catalog_name) ?>/<?php echo strtolower($works_well_with[0]->url) ?>">
						<img src="<?php echo $works_well_with[0]->small_image ?>" alt="<?php echo $works_well_with[0]->title ?>" />
					</a>
				</div>
				<div class="info">
					<div style="height:57px">
						<h3>
							<a href="/shop/<?php echo strtolower($works_well_with[0]->catalog_name) ?>/<?php echo strtolower($works_well_with[0]->url) ?>">
								<?php echo ($works_well_with[0]->name) ?>
							</a>
						</h3>
					</div>
					<div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;">		
						<span class="regularprice">
						<?php if ($works_well_with[0]->on_sale == 1) { ?>
						<?php echo "&#36;" . $works_well_with[0]->price ?>
						<?php } else { ?>
						<?php echo "&#36;" . $works_well_with[0]->retail_price ?>
						<?php } ?></span>&nbsp;&nbsp;
						<?php if ($works_well_with[0]->on_sale == 1) { ?>
						<span class="discountedprice">&#36;<?php echo $works_well_with[0]->retail_price ?></span>
						<?php } ?>
					</div>
					<div style="width:100px;height:25px;float:left;">
						<?php if ($works_well_with[0]->in_stock <= 0 && $works_well_with[0]->can_pre_order == 1) { ?>
						<!-- <a href="javascript:AddToCart(<?php echo $works_well_with[0]->catelog_id ?>, <?php echo $works_well_with[0]->id ?>)" class="selectashade">Add to Cart</a>	 -->
						<a href="#" class="addtocart2" onclick="javascript:addtocart('<?php echo $works_well_with[0]->catalog_id ?>', '<?php echo $works_well_with[0]->id ?>', this)">Add to Cart</a>
						<?php } else if ($works_well_with[0]->in_stock <= 0 && $works_well_with[0]->can_pre_order == 0) { ?>
						<a href="#" class="outofstock">Out of Stock</a>
						<?php } else { ?>
						<!-- <a href="javascript:AddToCart(<?php echo $works_well_with[0]->catalog_id ?>, <?php echo $works_well_with[0]->id ?>)" class="selectashade">Add to Cart</a> -->
						<a href="#" class="addtocart2" onclick="javascript:addtocart('<?php echo $works_well_with[0]->catalog_id ?>', '<?php echo $works_well_with[0]->id ?>', this)">Add to Cart</a>
						<?php } ?>
					</div>
					<div style="width:50px;height:25px;float:left;overflow:hidden;">
						
						<fb:like href="<?php echo base_url() ?>shop/<?php echo strtolower($works_well_with[0]->catalog_name) ?>/<?php echo strtolower($works_well_with[0]->url) ?>"
							show_faces="false" colorscheme="light" layout="button_count"></fb:like>

					</div>
					<div style="clear:both;width:100px;height:25px;float:left;">
						
						<?php if ($this->session->userdata('user_id') != "") { ?>
							<a href="#" class="addtowishlist" onclick="javascript:addtowishlist('<?php echo $works_well_with[0]->catalog_id ?>', '<?php echo $works_well_with[0]->id ?>', this);" style="margin-top:5px">Add to Wishlist</a>
						<?php } else { ?>
							<a href="/signin" class="addtowishlist_nosignin">Add to Wishlist</a>
						<?php } ?>
						
						<!-- <a href="javascript:AddWishList(<?php echo $works_well_with[0]->catalog_id ?>, <?php echo $works_well_with[0]->id ?>);" class="addtowishlist">Add to Wishlist</a> -->
						
					</div>
					<div style="width:55px;height:25px;float:left;overflow:hidden;">
						<a href="http://twitter.com/share" 
							class="twitter-share-button" 
							data-url="<?php echo base_url() ?>shop/<?php echo strtolower($works_well_with[0]->catalog_name) ?>/<?php echo strtolower($works_well_with[0]->url) ?>" 
							data-count="horizontal" data-via="josie_maran">Tweet</a>
					</div>
				</div>
			</div>				
			<?php
			}
			?>
		</div>
		<?php
			}
		?>
			<input type="hidden" name="category_id" value="" id="category_id">
			<input type="hidden" name="product_id" value="" id="product_id">
			<input type="hidden" name="qty" value="" id="qty">
			<input type="hidden" name="method" value="" id="method">	
		</form>

		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

