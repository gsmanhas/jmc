<!DOCTYPE HTML>
<html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Products - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>	
	<script type="text/javascript" charset="utf-8">
		var base_url = '/';
	</script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="list">
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
		
		<!-- <div id="pagetitle"><h1><?php echo $this->uri->segment(2) ?></h1></div> -->
		<div id="pagetitle">
			<h1>
				<?php echo $this->Catalog[0]->name; ?>
				<span id="sorting_bar">Sort by : <a href="#" alt="name" rel="">Product Name</a>&nbsp;|&nbsp;<a href="#" alt="price" rel="">Price</a>&nbsp;|&nbsp;<a href="#" alt="rating">Rating</a></span>
				<?php if ($this->uri->segment(2, 0) == "good-buys") { ?>
				<a href="#" class="good-buys"></a>
				<?php } ?>
			</h1>
		</div>
		
		<?php $this->load->view('shop/left_menu') ?>
		
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">		
			<div id="productwrapper">
								
				<?php
					$i = 1;
					foreach ($this->products as $product) {
						
						$this->product_id = $product->id;
						$this->product_url = $product->url;
						
						//	Search from Shade.
						// $Query = $this->db->query(
						// 	"SELECT (SELECT image FROM product_swatch WHERE id = p.swatch_id) as 'swatch_image', " .
						// 	"(SELECT DISTINCT sorting FROM product_group_by WHERE with_id = p.id ORDER BY id DESC limit 1) as 'sorting'," .
						// 	"(SELECT id FROM product_swatch WHERE id = p.swatch_id) as 'swatch_id', p.swatch_name, p.swatch_title, p.color, p.url, p.id " .
						// 	"FROM product as p " .
						// 	"WHERE p.id in( " .
						// 	"SELECT DISTINCT with_id FROM product_group_by " .
						// 	"WHERE pid = ? OR with_id = ? ORDER BY sorting ASC" .
						// 	") ORDER BY sorting ASC", array($product->id, $product->id)
						// );
						$Query = $this->db->query(
							"SELECT with_id FROM product_group_by WHERE pid = ?", $product->id
						);
												
						// echo $this->db->last_query().br(1);
						$this->shade = $Query->result();
				?>
				
				<div class="product">
					<div class="productimagecontainer">
						<a href="/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($product->url) ?>">
							<img src="<?php echo $product->small_image ?>" alt="<?php echo $product->title ?>" c="<?php echo $this->Catalog[0]->url ?>" u="<?php echo strtolower($product->url) ?>" />
						</a>
						
						<!--<div class="quickview_container">
							<div class="quickview_button">Quick View</div>
						</div>-->
					</div>
					<h3><a href="/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($product->url) ?>"><?php echo $product->title ?></a></h3>
					<div style="width:256px;height:auto;overflow:hidden;">
						<div style="width:42px;height:26px;float:left;">						
							<span class="regularprice">
							<?php if ($product->on_sale == 1) { ?>
							<?php echo "&#36;" . $product->price ?>
							<?php } else { ?>
							<?php echo "&#36;" . $product->retail_price ?>
							<?php } ?>
							</span>
						</div>
																		
						<?php
							/**
							 * 需要加入判斷使用者的購物車 Item 中所加入的數量, 來判斷是否 Out of Stock
							 */
							$product->in_stock -= (int)$this->ShoppingCart->get_cart_item_qty($product->id);
						?>
						
						<div class="buybuttons" style="width:92px;height:26px;float:left;margin-right:3px;">
							<!-- <a href="#" class="selectashade">Select a Shade</a> -->
							<?php if ($product->in_stock <= 0 && $product->can_pre_order == 1) { ?>
							<!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $product->id ?>)" class="selectashade">Add to Cart</a> -->
							<a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Cart</a>
							<!-- <a rel="shadowbox[SHOP]" href="index.php" class="selectashade">Add to Cart</a> -->
							<?php } else if ($product->in_stock <= 0 && $product->can_pre_order == 0 && count($this->shade) <= 0) { ?>
							<a href="#" class="outofstock">Out of Stock</a>
							<?php } else { ?>
							<!-- <a rel="shadowbox[SHOP]" href="index.php" class="selectashade">Add to Cart</a> -->
								<?php if (count($this->shade) >= 10000) { ?>
								
									<?php
									if ($this->product_id == 122 || $this->product_id == 123) {
									?>
										<a href="javascript:showShade(<?php echo $product->id ?>)" class="selectashade">Select a Scent</a>
									<?php } else if ($this->product_id == 119 || $this->product_id == 120 || $this->product_id == 121) { ?>
										<a href="javascript:showShade(<?php echo $product->id ?>)" class="selectashade">Select a Size</a>
									<?php } else { ?>
										<a href="javascript:showShade(<?php echo $product->id ?>)" class="selectashade">Select a Shade</a>
									<?php
									}
									?>
									
								<?php } else { ?>
									<a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Cart</a>
									<!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $product->id ?>)" class="selectashade">Add to Cart</a> -->
								<?php } ?>
							
							<?php } ?>
						</div>
												
						<div class="socialbuttons" style="width:119px;height:26px;float:left">							
							<?php /*?><fb:like 
							href="<?php echo str_replace("http://sandbox.", "http://www." , base_url()); ?>shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>" layout="button_count"></fb:like><?php */?>
							<?php if ($this->session->userdata('user_id') != "") { ?>
								<a href="#" class="addtowishlist" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Wishlist</a>
							<?php } else { ?>
								<a href="/signin" class="addtowishlist_nosignin" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Wishlist</a>
							<?php } ?>
						</div>

						<div class="price" style="width:42px;height:26px;float:left;">
							<?php if ($product->on_sale == 1) { ?>
							<span class="discountedprice">&#36;<?php echo $product->retail_price ?></span>
							<?php } ?>
						</div>
						<?php /*?><div style="width:92px;height:26px;float:left;margin-right:3px;">
							<?php if ($this->session->userdata('user_id') != "") { ?>
								<a href="#" class="addtowishlist" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Wishlist</a>
							<?php } else { ?>
								<a href="/signin" class="addtowishlist_nosignin" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Wishlist</a>
							<?php } ?>
							<!-- javascript:AddWishList(<?php echo $this->Catalog[0]->id ?>, <?php echo $product->id ?>);" -->
						</div>
						<div style="width:119px;height:26px;float:left;">
							<a href="http://twitter.com/share" 
								class="twitter-share-button" 
								data-text="I love Josie Maran products! Check this one out: " 
								data-url="<?php echo base_url() ?>shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>" 
								data-count="horizontal" data-via="josie_maran">Tweet</a>
						</div><?php */?>
						
					</div>
					<?php $this->load->view('shop/select_a_shade') ?>
				</div>
				<?php
				}
				?>
			</div>
			
			<input type="hidden" name="category_id" value="" id="category_id">
			<input type="hidden" name="product_id" value="" id="product_id">
			<input type="hidden" name="qty" value="" id="qty">
			<input type="hidden" name="method" value="" id="method">
			<input type="hidden" name="sort_by" value="" id="sort_by">
			<input type="hidden" name="sort_type" value="" id="sort_type">
		</form>
		
		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
	<div class="overlay">
		<div class="overlay-message">
			<p><span id="x_items">X</span> has been added to your cart.</p>
			<div class="overlay-box">
				<a href="">Continue Shopping</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="">Proceed to Checkout</a>
			</div>
		</div>
	</div>
		
</body>
</html>

