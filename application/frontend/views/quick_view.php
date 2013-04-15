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
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script src="/js/quickview.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="/js/jquery.raty.js"></script>
	<script type="text/javascript" charset="utf-8" src="/js/rateing.js"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="quickview">

	<div id="main" style="min-width:900px;">
		
		<div id="productimage" style="clear:both;">
			<a href="javascript:window.parent.location.href='/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($this->product[0]->url) ?>'">
				<img src="<?php echo $this->product[0]->large_image ?>" alt="" />
			</a>
				<?php

					$Query = $this->db->query(
						"SELECT pr.id, pr.title, pr.rate, pr.message, DATE_FORMAT(pr.created_at, '%M %d %Y') as 'created_at'," .
						"(SELECT firstname	 FROM users WHERE id = pr.uid) as 'name'" .
						" FROM product_review as pr WHERE pid = ? AND is_delete = 0 AND publish = 1 order by ordering ASC", 
							$this->product[0]->id
					);

					// echo $this->db->last_query();

					$Rate = $this->db->query(
						"SELECT SUM(pr.rate) as 'total_rate', COUNT(id) as 'total_count' FROM product_review as pr WHERE pid = ? AND is_delete = 0 AND publish = 1 order by created_at desc"
					, $this->product[0]->id)->result();

					$rate_number = 0;

					if (count($Rate) >= 1) {
						if ($Rate[0]->total_count >= 1) {
							$rate_number = round($Rate[0]->total_rate / $Rate[0]->total_count);
						}
					}

				?>
				<div id="overall_rating_quickview">
					<div id="overall_rating_container">
						<div style="float:left;font-style:italic;line-height:2em;font-weight:normal;">Overall Rating:&nbsp;</div>
						<div id="total_rating"></div>
					</div>
					<script type="text/javascript" charset="utf-8">
						$('#total_rating').raty({
						  readOnly:  true,
						  start:     <?php echo $rate_number ?>
						});
					</script>
				</div>
		</div>
		
		<div id="productinfo">

			<div id="pagetitle">
				<h1>
					<a href="javascript:window.parent.location.href='/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($this->product[0]->url) ?>'">
						<?php echo $this->product[0]->name ?>
					</a>
				</h1>
			</div>
			
			<ul class="tabs">
				<li class="active">
					<a href="#tab1">Description</a>
				</li>
				<li>
					<a href="#tab2">Being Responsible</a>
				</li>
				<li>
					<a href="#tab3">Tips</a>
				</li>
			</ul>
			<div class="tab_container">
				<div id="tab1" class="tab_content"><?php echo $this->product[0]->description ?></div>
				<div id="tab2" class="tab_content"><?php $this->load->view('shop/symbolkey') ?></div>
				<div id="tab3" class="tab_content"><p><?php echo $this->product[0]->tips ?></p></div>
			</div>

			<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">
									
			<div id="buybuttonswrapper">

					<?php $this->load->view('shop/swatch') ?>
					
					<div class="price">
						<span class="regularprice">
						<?php if ($this->product[0]->on_sale == 1) { ?>
						<?php echo "&#36;" . ($this->product[0]->price) ?>
						<?php } else { ?>
						<?php echo "&#36;" . ($this->product[0]->retail_price) ?>
						<?php } ?></span>
					</div>
					<div class="price">
						<?php if ($this->product[0]->on_sale == 1) { ?>
						<span class="discountedprice">&#36;<?php echo number_format($this->product[0]->retail_price, 2) ?></span>
						<?php } ?>
					</div>
					<div class="buybuttons">
						<?php if ($this->product[0]->in_stock <= 0 && $this->product[0]->can_pre_order == 1) { ?>
						<!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $this->product[0]->id ?>)" class="selectashade">Add to Cart</a>	 -->
						<!-- <a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>">Add to Cart</a> -->
						
						<a href="#" class="addtocart" onclick="javascript:addtocart('<?php echo $this->Catalog[0]->id ?>', '<?php echo $this->product[0]->id ?>', this)">Add to Cart</a>
						
						<?php } else if ($this->product[0]->in_stock <= 0 && $this->product[0]->can_pre_order == 0) { ?>
						<a href="#" class="outofstock">Out of Stock</a>
						<?php } else { ?>
						<!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $this->product[0]->id ?>)" class="selectashade">Add to Cart</a> -->
						<!-- <a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>">Add to Cart</a> -->
						<a href="#" class="addtocart" onclick="javascript:addtocart('<?php echo $this->Catalog[0]->id ?>', '<?php echo $this->product[0]->id ?>', this)">Add to Cart</a>
						<?php } ?>
						<!-- <a href="javascript:AddWishList(<?php echo $this->Catalog[0]->id ?>, <?php echo $this->product[0]->id ?>);" class="addtowishlist">Add to Wishlist</a> -->
						<?php if ($this->session->userdata('user_id') != "") { ?>
							<!-- <a href="#" class="addtowishlist" onclick="javascript:addtowishlist('<?php echo $this->Catalog[0]->id ?>', '<?php echo $this->product[0]->id ?>', this)" style="margin-top:5px">Add to Wishlist</a> -->
							<a href="#" class="addtowishlist" onclick="javascript:addtowishlist('<?php echo $this->Catalog[0]->id ?>', '<?php echo $this->product[0]->id ?>', this);" style="margin-top:5px">Add to Wishlist</a>					
						<?php } else { ?>
							<a href="#" class="addtowishlist_nosignin" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $this->product[0]->id ?>" style="margin-top:5px">Add to Wishlist</a>
						<?php } ?>
					</div>
					<div class="socialbuttons">
						
						<fb:like action='like' colorscheme='light' expr:href='data:<?php echo current_url() ?>'
						layout='button_count' show_faces='false' width='120'/></fb:like>
						
						<a href="http://twitter.com/share" 
							class="twitter-share-button" 
							data-text="I love Josie Maran products! Check this one out: " 
							data-url="<?php echo current_url() ?>" 
							data-count="horizontal" data-via="josie_maran">Tweet</a>
					</div>
					
				<input type="hidden" name="category_id" value="<?php echo $this->Catalog[0]->id ?>" id="category_id">
				<input type="hidden" name="product_id" value="<?php echo $this->product[0]->id ?>" id="product_id">
				<input type="hidden" name="qty" value="" id="qty">
				<input type="hidden" name="method" value="" id="method">
			
			</div>
			
			</form>
			
			<div id="colors">
				<?php echo $this->load->view('shop/quickview_group_by') ?>
			</div>
			
		</div>
			
	</div>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>

