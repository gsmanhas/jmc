<?php
	if (count($this->Works_Well_With) >= 1) {
?>
<div id="workswellwith">
	<h4>Works Well With</h4>
	<?php
	foreach ($this->Works_Well_With as $works) {
		$query = $this->db->query(
			"SELECT id, name, small_image, title, price, retail_price, on_sale, url, can_pre_order, in_stock, " .
			"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
			"(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
			"FROM product as p WHERE id = " . $works->with_id
		);
		$works_well_with = $query->result();
	?>
	<div class="featuredproduct">
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
			
			<?php
				/**
				 * 需要加入判斷使用者的購物車 Item 中所加入的數量, 來判斷是否 Out of Stock
				 */
				$works_well_with[0]->in_stock -= (int)$this->ShoppingCart->get_cart_item_qty($works_well_with[0]->id);
			?>
			
			<div style="width:100px;height:25px;float:left;">
				<?php if ($works_well_with[0]->in_stock <= 0 && $works_well_with[0]->can_pre_order == 1) { ?>
				<!-- <a href="javascript:AddToCart(<?php echo $works_well_with[0]->catelog_id ?>, <?php echo $works_well_with[0]->id ?>)" class="selectashade">Add to Cart</a> -->
				<!-- <a href="#" class="addtocart" cid="<?php echo $works_well_with[0]->catalog_id ?>" pid="<?php echo $works_well_with[0]->id ?>">Add to Cart</a> -->
				
				<a href="#" class="addtocart2" onclick="javascript:addtocart('<?php echo $works_well_with[0]->catalog_id ?>', '<?php echo $works_well_with[0]->id ?>', this); return false;">Add to Cart</a>
				
				<?php } else if ($works_well_with[0]->in_stock <= 0 && $works_well_with[0]->can_pre_order == 0) { ?>
				<a href="#" class="outofstock">Out of Stock</a>
				<?php } else { ?>
				<!-- <a href="javascript:AddToCart(<?php echo $works_well_with[0]->catalog_id ?>, <?php echo $works_well_with[0]->id ?>)" class="selectashade">Add to Cart</a> -->				
				<!-- <a href="#" class="addtocart" cid="<?php echo $works_well_with[0]->catalog_id ?>" pid="<?php echo $works_well_with[0]->id ?>">Add to Cart</a> -->
				<a href="#" class="addtocart2" onclick="javascript:addtocart('<?php echo $works_well_with[0]->catalog_id ?>', '<?php echo $works_well_with[0]->id ?>', this); return false;">Add to Cart</a>
				<?php } ?>
			</div>
			<div style="width:50px;height:25px;float:left;overflow:hidden;">
										
				<fb:like href="<?php echo base_url() ?>shop/<?php echo strtolower($works_well_with[0]->catalog_name) ?>/<?php echo strtolower($works_well_with[0]->url) ?>"
					show_faces="false" colorscheme="light" layout="button_count"></fb:like>
						
			</div>
			<div style="clear:both;width:100px;height:25px;float:left;">
				<!-- <a href="javascript:AddWishList(<?php echo $works_well_with[0]->catalog_id ?>, <?php echo $works_well_with[0]->id ?>);" class="addtowishlist">Add to Wishlist</a> -->
				<?php if ($this->session->userdata('user_id') != "") { ?>
					<!-- <a href="#" class="addtowishlist" cid="<?php echo $works_well_with[0]->catalog_id ?>" pid="<?php echo $works_well_with[0]->id ?>">Add to Wishlist</a> -->
					<a href="#" class="addtowishlist" onclick="javascript:addtowishlist('<?php echo $works_well_with[0]->catalog_id ?>', '<?php echo $works_well_with[0]->id ?>', this);" style="margin-top:5px">Add to Wishlist</a>					
				<?php } else { ?>
					<a href="/signin" class="addtowishlist_nosignin" cid="<?php echo $works_well_with[0]->catalog_id ?>" pid="<?php echo $works_well_with[0]->id ?>">Add to Wishlist</a>
				<?php } ?>

			</div>
			<div style="width:55px;height:25px;float:left;overflow:hidden;">
				<a href="http://twitter.com/share" 
					class="twitter-share-button" 
					data-text="I love Josie Maran products! Check this one out: " 
					data-url="<?php echo base_url() ?>shop/<?php echo strtolower($works_well_with[0]->catalog_name) ?>/<?php echo strtolower($works_well_with[0]->url) ?>" 
					data-count="none" data-via="josie_maran">Tweet</a>
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