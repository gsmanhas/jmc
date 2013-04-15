<?php

$arrName = array('Face', 'Eyes', 'Lips', 'Hair', 'Skin');

for ($i=1; $i <= 5; $i++) { 
?>
<div id="workswellwith">
	<h4><?php echo $arrName[$i-1] ?></h4>
		<?php
			$query = $this->db->query(
				"SELECT id, name, small_image, title, price, retail_price, on_sale, url, can_pre_order, in_stock, " .
				"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
				"(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
				"FROM product as p WHERE id in(SELECT pid FROM get_the_look_rel_product WHERE look_id = ? AND type_id = $i)"
			, $this->get_the_look[0]->id);
			
			// echo $this->db->last_query();

			foreach ($query->result() as $item) {
		?>
	<div class="featuredproduct">
		<div class="image">
			<a href="/shop/<?php echo $item->catalog_name ?>/<?php echo $item->url ?>">
				<img alt="<?php echo $item->name ?>" src="<?php echo $item->small_image ?>">
			</a>
		</div>
		<div class="info">
			<div style="height:57px">
				<h3><a href="/shop/<?php echo $item->catalog_name ?>/<?php echo $item->url ?>"><?php echo $item->name ?></a></h3>
			</div>
			<div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;">	
				<span class="regularprice">
				<?php if ($item->on_sale == 1) { ?>
				<?php echo "&#36;" . $item->price ?>
				<?php } else { ?>
				<?php echo "&#36;" . $item->retail_price ?>
				<?php } ?></span>&nbsp;&nbsp;
				<?php if ($item->on_sale == 1) { ?>
				<span class="discountedprice">&#36;<?php echo $item->retail_price ?></span>
				<?php } ?>
			</div>

			<?php
				/**
				 * 需要加入判斷使用者的購物車 Item 中所加入的數量, 來判斷是否 Out of Stock
				 */
				$item->in_stock -= (int)$this->ShoppingCart->get_cart_item_qty($item->id);
			?>

			<div style="width:100px;height:25px;float:left;">
				<?php if ($item->in_stock <= 0 && $item->can_pre_order == 1) { ?>				
				<a href="#" class="addtocart2" onclick="javascript:addtocart('<?php echo $item->catalog_id ?>', '<?php echo $item->id ?>', this);return false;">Add to Cart</a>
				<?php } else if ($item->in_stock <= 0 && $item->can_pre_order == 0) { ?>
				<a href="#" class="outofstock">Out of Stock</a>
				<?php } else { ?>
				<a href="#" class="addtocart2" onclick="javascript:addtocart('<?php echo $item->catalog_id ?>', '<?php echo $item->id ?>', this);return false;">Add to Cart</a>
				<?php } ?>
			</div>

			<div style="width:50px;height:25px;float:left;overflow:hidden;">
				<fb:like href="<?php echo base_url() ?>shop/<?php echo strtolower($item->catalog_name) ?>/<?php echo strtolower($item->url) ?>"
					show_faces="false" colorscheme="light" layout="button_count"></fb:like>		
			</div>
						
			<div style="clear:both;width:100px;height:25px;float:left;">
				<?php if ($this->session->userdata('user_id') != "") { ?>
					<a href="#" class="addtowishlist" onclick="javascript:addtowishlist('<?php echo $item->catalog_id ?>', '<?php echo $item->id ?>', this);" style="margin-top:5px">Add to Wishlist</a>
				<?php } else { ?>
					<a href="/signin" class="addtowishlist_nosignin" cid="<?php echo $item->catalog_id ?>" pid="<?php echo $item->id ?>">Add to Wishlist</a>
				<?php } ?>
			</div>
			<div style="width:55px;height:25px;float:left;overflow:hidden;">
				<a href="http://twitter.com/share" 
					class="twitter-share-button" 
					data-text="I love Josie Maran products! Check this one out: " 
					data-url="<?php echo base_url() ?>shop/<?php echo strtolower($item->catalog_name) ?>/<?php echo strtolower($item->url) ?>" 
					data-count="none" data-via="josie_maran">Tweet</a>
			</div>
		</div>
	</div>
		<?php
			}
		?>
</div>
<?
}
?>