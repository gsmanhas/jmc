<style type="text/css">
    img.voucher_image {
        /* for firefox, safari, chrome, etc. */
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        /* for ie */
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        height: auto !important;
        width: 150px !important;
    }
</style>
<?php
if (count($this->wishlist) >= 1) {
    ?>
<div id="wishlist">
    <!-- <h4>Wishlist</h4> -->
    <?php foreach ($this->wishlist as $wish): ?>
    <?php if ($wish->item_type == 'product'):
        $query = $this->db->query(
            "SELECT id, name, small_image, title, price, retail_price, on_sale, url, in_stock, can_pre_order, " .
                "(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
                "(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
                "FROM product as p WHERE id = " . $wish->pid
        );
        $WishList_Product = $query->result_array();?>
        <div class="featuredproduct">
            <div style="width:130px;float:left;">
                <a href="/shop/<?php echo strtolower($WishList_Product[0]['catalog_name']) ?>/<?php echo strtolower($WishList_Product[0]['url']) ?>">
                    <img src="<?php echo $WishList_Product[0]['small_image'] ?>"
                         alt="<?php echo $WishList_Product[0]['name'] ?>"/>
                </a>
            </div>
            <div class="info" style="width:126px">
                <div style="height:57px">
                    <h3>
                        <a href="/shop/<?php echo strtolower($WishList_Product[0]['catalog_name']) ?>/<?php echo strtolower($WishList_Product[0]['url']) ?>">
                            <?php echo $WishList_Product[0]['name'] ?>
                        </a>
                    </h3>
                </div>

                <?php
                /**
                 * 需要加入判斷使用者的購物車 Item 中所加入的數量, 來判斷是否 Out of Stock
                 */
                $WishList_Product[0]['in_stock'] -= (int)$this->ShoppingCart->get_cart_item_qty($WishList_Product[0]['id']);
                ?>

                <div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;">
                <span class="regularprice">
                <?php if ($WishList_Product[0]['on_sale'] == 1) { ?>
                    <?php echo "&#36;" . $WishList_Product[0]['price'] ?>
                    <?php } else { ?>
                    <?php echo "&#36;" . $WishList_Product[0]['retail_price'] ?>
                    <?php } ?></span>&nbsp;&nbsp;
                    <?php if ($WishList_Product[0]['on_sale'] == 1) { ?>
                    <span class="discountedprice">&#36;<?php echo $WishList_Product[0]['retail_price'] ?></span>
                    <?php } ?>
                    </span>
                </div>
                <div style="width:100px;height:25px;float:left;">
                    <?php if ($WishList_Product[0]['in_stock'] <= 0 && $WishList_Product[0]['can_pre_order'] == 1) { ?>
                    <!-- <a href="javascript:AddToCart(<?php echo $WishList_Product[0]['catalog_id'] ?>, <?php echo $WishList_Product[0]['id']?>)" class="selectashade">Add to Cart</a>	 -->
                    <a href="#" class="addtocart" cid="<?php echo $WishList_Product[0]['catalog_id'] ?>"
                       pid="<?php echo $WishList_Product[0]['id'] ?>">Add to Cart</a>
                    <?php } else if ($WishList_Product[0]['in_stock'] <= 0 && $WishList_Product[0]['can_pre_order'] == 0) { ?>
                    <a href="#" class="outofstock">Out of Stock</a>
                    <?php } else { ?>
                    <!-- <a href="javascript:AddToCart('<?php echo $WishList_Product[0]['catalog_id'] ?>', '<?php echo $WishList_Product[0]['id'] ?>');" class="selectashade">Add to Cart</a> -->
                    <a href="#" class="addtocart" cid="<?php echo $WishList_Product[0]['catalog_id'] ?>"
                       pid="<?php echo $WishList_Product[0]['id'] ?>">Add to Cart</a>
                    <?php } ?>
                </div>
                <!-- <div style="width:50px;height:25px;float:left;overflow:hidden;">
                <fb:like href="/shop/<?php echo strtolower($WishList_Product[0]['catalog_name']) ?>/<?php echo strtolower($WishList_Product[0]['url']) ?>"
                    show_faces="false" colorscheme="light" layout="button_count"></fb:like>
            </div> -->

                <div style="clear:both;width:100px;height:25px;float:left;">
                    <a href="/myaccount/remove_wishlist/<?php echo $wish->id ?>"
                       class="addtowishlist2">Remove</a>
                </div>

                <!-- <div style="width:55px;height:25px;float:left;overflow:hidden;">
                <a href="http://twitter.com/share"
                    class="twitter-share-button"
                    data-text="I love Josie Maran products! Check this one out: "
                    data-url="/shop/<?php echo strtolower($WishList_Product[0]['catalog_name']) ?>/<?php echo strtolower($WishList_Product[0]['url']) ?>"
                    data-count="none" data-via="josie_maran">Tweet</a>
            </div> -->

            </div>
        </div>
        <?php else:
        $query = $this->db->query(
            "SELECT id, gift_voucher_image_small as small_image, gift_voucher_balance as price,gift_voucher_balance as retail_price FROM gift_voucher WHERE id = " . $wish->pid
        );
        $WishList_Product = $query->result_array();
        if (count($WishList_Product) >= 1) : ?>
        <div class="featuredproduct">
            <div style="width:130px;float:left;padding-top: 30px;">
                <a href="/egiftcards/<?php echo $WishList_Product[0]['id'] ?>">
                    <img class="voucher_image" src="<?php echo $WishList_Product[0]['small_image'] ?>"
                         alt="<?php echo $WishList_Product[0]['small_image'] ?>"/>
                </a>
            </div>
            <div class="info" style="width:126px">
                <div style="height:57px">
                    <h3>
                        <a href="/egiftcards/<?php echo $WishList_Product[0]['id'] ?>">
                            eGift Card $<?php echo $WishList_Product[0]['price'] ?>
                        </a>
                    </h3>
                </div>
                <div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;">
                    <span class="regularprice">
                    <?php echo "&#36;" . $WishList_Product[0]['price'] ?>
                    </span>&nbsp;&nbsp;
                </div>

                <div style="width:100px;height:25px;float:left;">
                    <a href="/egiftcards//<?php echo $WishList_Product[0]['id'] ?>" class="addtocart voucher">Add to Cart</a>
                </div>

                <div style="clear:both;width:100px;height:25px;float:left;">
                    <a href="/myaccount/remove_wishlist/<?php echo $wish->id ?>"
                       class="addtowishlist2">Remove</a>
                </div>
            </div>
        </div>
        <?php endif;?>
        <?php endif; ?>

    <?php endforeach; ?>
</div>
<?php
} else {
    ?>
<div style="width:810px;float:right;">
    You don't have any item in your wishlist. Please <a href="/shop">find a product</a> and click on its "Add to
    Wishlist" button.
</div>
<?php
}
?>