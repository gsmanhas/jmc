<html>
    <head>
        
    </head>
    <body>
    
    	<style type="text/css">
            body { margin-top: 0; margin-right: 10px; margin-bottom: 20px; margin-left: 10px; color: #493838; }
            body, table { font: 12px/18px Helvetica, Arial, sans-serif; }
            table { min-width:680px; margin-bottom: 20px; }
            table span { color: #888; font-weight: bold; }
            p { margin-bottom: 20px; }
			#wishlist { width: 830px; }
			.featuredproduct { float: left; margin: 0 20px 0px 0; }
			.featuredproduct img { width: 128px; }
        </style>
        
        <table width="98%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
                <tr>
                    <td colspan="5" style="height:65px;">
						<div style="width:100%;height:65px;border-bottom:1px solid #f3e4e9;">
							<img src="<?php echo base_url() ?>images/global/josie-maran.gif" alt="Josie Maran" />
						</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr valign="top">
                    <td colspan="5">
	                    <p>Hi,</p>
	                    <p><?php echo $this->session->userdata('firstname') ?> would like to share the Josie Maran wish list below with you.</p>
						<p>Message:&nbsp;<?php echo $this->MESSAGE ?></p>
						<p>&nbsp;</p>
						
						<div id="wishlist">
							<!-- <h4>Wishlist</h4> -->
							<?php foreach ($this->wishlist as $wish): ?>
							<?php
								$query = $this->db->query(
									"SELECT id, name, small_image, title, price, retail_price, on_sale, url, in_stock, can_pre_order, " .
									"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
									"(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
									"FROM product as p WHERE id = " . $wish->pid
								);
								$WishList_Product = $query->result_array();

								if (count($WishList_Product) >= 1) {
							?>
							<div class="featuredproduct">
								<div style="width:150px;">
									<a href="<?php echo base_url() ?>shop/<?php echo strtolower($WishList_Product[0]['catalog_name']) ?>/<?php echo strtolower($WishList_Product[0]['url']) ?>">
										<img src="<?php echo base_url() . $WishList_Product[0]['small_image'] ?>" alt="<?php echo $WishList_Product[0]['name'] ?>" />
									</a>
								</div>
								<div class="info" style="width:126px">
									<div style="height:57px">
										<h3 style="font-size:11px;">
											<a style="color:#977778;" href="<?php echo base_url() ?>shop/<?php echo strtolower($WishList_Product[0]['catalog_name']) ?>/<?php echo strtolower($WishList_Product[0]['url']) ?>">
												<?php echo $WishList_Product[0]['name'] ?>
											</a>
										</h3>
									</div>
									<div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;">
										
										<?php if ($WishList_Product[0]['on_sale'] == 1) { ?>
										<span style="text-decoration:line-through;color:red;">
										<?php echo "&#36;" . number_format($WishList_Product[0]['price'], 2) ?>
										</span>
										<?php } else { ?>
										<span>
										<?php echo "&#36;" . number_format($WishList_Product[0]['retail_price'], 2) ?>
										<?php } ?></span>&nbsp;&nbsp;
										<?php if ($WishList_Product[0]['on_sale'] == 1) { ?>
										<span class="discountedprice">&#36;<?php echo number_format($WishList_Product[0]['retail_price'], 2) ?></span>
										<?php } ?>
										</span>
									</div>
									<div style="width:100px;height:25px;float:left;">
									</div>
									<div style="clear:both;width:100px;height:25px;float:left;">
									</div>
								</div>
							</div>
							<?php
								}
							?>
							<?php endforeach ?>
						</div>
						<div style="clear:both;">
							<p>Best,<br />Josie Maran</p>
						</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>