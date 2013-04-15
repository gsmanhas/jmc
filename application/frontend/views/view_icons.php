<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>View Our Icons - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script src="/js/quickview.js" type="text/javascript" charset="utf-8"></script>
</head>

<body id="quickview">

	<div id="main" style="min-width:900px;">
<?php
	if (count($this->icons) >= 1) {
?>

<!--
<div id="closebutton">
	<a href="javascript:window.parent.Shadowbox.close();"><img src="/images/global/btn-close.gif" alt="Close" /></a>
</div>
-->

<div id="viewouricons_viewproducts">
	
	<?php
	foreach ($this->icons as $icon) {
	?>
	<div class="featuredproduct">
		<div class="image">
			<a href="javascript:window.parent.location.href='/shop/<?php echo strtolower($icon->catalog_name) ?>/<?php echo strtolower($icon->url) ?>'">
				<img src="<?php echo $icon->small_image ?>" alt="<?php echo $icon->title ?>" />
			</a>
		</div>
		<div class="info">
			<div style="height:80px">
				<h3>
					<a href="javascript:window.parent.location.href='/shop/<?php echo strtolower($icon->catalog_name) ?>/<?php echo strtolower($icon->url) ?>'">
						<?php echo ($icon->name) ?>
					</a>
				</h3>
			</div>
			<!-- <div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;"> -->
			<div style="position:absolute;left:0;bottom:60px;height:25px;float:left;">	
				<span class="regularprice">
				<?php if ($icon->on_sale == 1) { ?>
				<?php echo "&#36;" . number_format($icon->price, 2) ?>
				<?php } else { ?>
				<?php echo "&#36;" . number_format($icon->retail_price, 2) ?>
				<?php } ?></span>&nbsp;&nbsp;
				<?php if ($icon->on_sale == 1) { ?>
				<span class="discountedprice">&#36;<?php echo number_format($icon->retail_price, 2) ?></span>
				<?php } ?>
			</div>
			<div style="width:100px;height:25px;float:left;">
				<?php if ($icon->in_stock <= 0 && $icon->can_pre_order == 1) { ?>
				<!-- <a href="javascript:AddToCart(<?php echo $icon->catelog_id ?>, <?php echo $icon->id ?>)" class="selectashade">Add to Cart</a> -->
				<!-- <a href="#" class="addtocart" cid="<?php echo $icon->catalog_id ?>" pid="<?php echo $icon->id ?>">Add to Cart</a> -->
				
				<!-- <a href="#" class="addtocart" onclick="javascript:addtocart('<?php echo $icon->catalog_id ?>', '<?php echo $icon->id ?>', this)">Add to Cart</a> -->				
				<?php } else if ($icon->in_stock <= 0 && $icon->can_pre_order == 0) { ?>
				<!-- <a href="#" class="outofstock">Out of Stock</a> -->
				<?php } else { ?>
				<!-- <a href="javascript:AddToCart(<?php echo $icon->catalog_id ?>, <?php echo $icon->id ?>)" class="selectashade">Add to Cart</a> -->				
				<!-- <a href="#" class="addtocart" cid="<?php echo $icon->catalog_id ?>" pid="<?php echo $icon->id ?>">Add to Cart</a> -->
				<!-- <a href="#" class="addtocart" onclick="javascript:addtocart('<?php echo $icon->catalog_id ?>', '<?php echo $icon->id ?>', this)">Add to Cart</a> -->
				<?php } ?>
			</div>
			<div style="width:63px;height:25px;float:left;">
				<!-- <fb:like action='like' colorscheme='light' 
						expr:href='data:/shop/<?php echo strtolower($icon->catalog_name) ?>/<?php echo strtolower($icon->url) ?>'
						layout='button_count' show_faces='false'/></fb:like> -->
			</div>
			<div style="clear:both;width:100px;height:25px;float:left;">
				<!-- <a href="javascript:AddWishList(<?php echo $icon->catalog_id ?>, <?php echo $icon->id ?>);" class="addtowishlist">Add to Wishlist</a> -->
				<?php if ($this->session->userdata('user_id') != "") { ?>
					<!-- <a href="#" class="addtowishlist" cid="<?php echo $icon->catalog_id ?>" pid="<?php echo $icon->id ?>">Add to Wishlist</a> -->
					<!-- <a href="#" class="addtowishlist" onclick="javascript:addtowishlist('<?php echo $icon->catalog_id ?>', '<?php echo $icon->id ?>', this);" style="margin-top:5px">Add to Wishlist</a> -->
				<?php } else { ?>
					<!-- <a href="/signin" class="addtowishlist_nosignin" cid="<?php echo $icon->catalog_id ?>" pid="<?php echo $icon->id ?>">Add to Wishlist</a> -->
				<?php } ?>
				
			</div>
			<div style="width:63px;height:25px;float:left;">
				<!-- <a href="http://twitter.com/share" 
					class="twitter-share-button" 
					data-url="/shop/<?php echo strtolower($icon->catalog_name) ?>/<?php echo strtolower($icon->url) ?>" 
					data-count="none" data-via="hammhuang">Tweet</a> -->
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
</div>
</body>
</html>