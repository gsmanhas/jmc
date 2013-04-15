<!DOCTYPE HTML>
<html>
<head>	
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Products - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/js/jwplayer.js"></script>
	<script type="text/javascript">
		Shadowbox.init({
			language	: 	'en',
			players		:	 ['html','iframe']
		});
        <?php if($this->Catalog[0]->video_type == 'file' && !empty($this->Catalog[0]->video)):?>
        jQuery(function() {
            jwplayer("video_container").setup({
                flashplayer:"/js/player.swf",
                file:"<?php echo $this->Catalog[0]->video ?>"
            });
        });
        <?php endif; ?>
	</script>
	<script src="/js/quickview.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="">
	
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
		<div id="pagetitle"><h1><?php echo $this->Catalog[0]->name ?></h1></div>
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">
		<div id="masthead">
            <div class="slidecontainer" style="display: block;">
                <img src="<?php echo $this->Catalog[0]->top_image; ?>"
                     alt="Header Top Image" style="display: inline; opacity: 1;">
            </div>
        </div>
        <div id="content">
            <?php echo $this->Catalog[0]->content; ?>
            <?php if($this->Catalog[0]->video_type == 'file' && !empty($this->Catalog[0]->video)){?>
            <div id="video_container">Loading the player ...</div><br />
            <?php }elseif ($this->Catalog[0]->video_type == 'url' && !empty($this->Catalog[0]->video)){ ?>
            <a class="reel-popup" href="<?php echo $this->Catalog[0]->video?>" rel="shadowbox[];width=425;height=344;">
                <img src="<?php echo $this->Catalog[0]->video_preview ?>" />
            </a>
            <?php }?>
        </div>
        <div id="workswellwith">
            <h4>Featured Products</h4>
            <?php
            foreach ($this->products as $product) {
                
            ?>
            <div class="featuredproduct">
                <div class="image">
                    <a href="/shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>">
                        <img src="<?php echo $product->small_image ?>" alt="<?php echo $product->title ?>" />
                    </a>
                </div>
                <div class="info">
                    <div style="height:57px">
                        <h3>
                            <a href="/shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>">
                                <?php echo $product->name ?>
                            </a>
                        </h3>
                    </div>
                    <div style="height:25px;border-bottom:1px solid #D9CFCD;margin-bottom:15px;">
                        <span class="regularprice">
                        <?php if ($product->on_sale == 1) { ?>
                        <?php echo "&#36;" . $product->price ?>
                        <?php } else { ?>
                        <?php echo "&#36;" . $product->retail_price ?>
                        <?php } ?></span>&nbsp;&nbsp;
                        <?php if ($product->on_sale == 1) { ?>
                        <span class="discountedprice">&#36;<?php echo $product->retail_price ?></span>
                        <?php } ?>
                    </div>

                    <?php
                        /**
                         * ????????????? Item ???????, ????? Out of Stock
                         */
                        $product->in_stock -= (int)$this->ShoppingCart->get_cart_item_qty($product->id);
                    ?>

                    <div style="width:100px;height:25px;float:left;">
                        <?php if ($product->in_stock <= 0 && $product->can_pre_order == 1) { ?>
                        <!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $product->id ?>)" class="selectashade">Add to Cart</a> -->
                        <!-- <a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Cart</a> -->
                        <a href="#" class="addtocart2" onClick="javascript:addtocart('<?php echo $this->Catalog[0]->id ?>', '<?php echo $product->id ?>', this); return false;">Add to Cart</a>
                        <?php } else if ($product->in_stock <= 0 && $product->can_pre_order == 0) { ?>
                        <a href="#" class="outofstock">Out of Stock</a>
                        <?php } else { ?>
                        <!-- <a href="javascript:AddToCart(<?php echo $this->Catalog[0]->id ?>, <?php echo $product->id ?>)" class="selectashade">Add to Cart</a> -->
                        <!-- <a href="#" class="addtocart" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Cart</a> -->
                        <a href="#" class="addtocart2" onClick="javascript:addtocart('<?php echo $this->Catalog[0]->id ?>', '<?php echo $product->id ?>', this); return false;">Add to Cart</a>
                        <?php } ?>

                    </div>
                    <div style="width:50px;height:25px;float:left;overflow:hidden;">
                        <!-- <fb:like action='like' colorscheme='light'
                                expr:href='data:/shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>'
                                layout='button_count' show_faces='false'/> -->
                        <?php /*?><fb:like href="<?php echo str_replace("http://sandbox.", "http://www." , base_url()); ?>shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>"
                            show_faces="false" colorscheme="light" layout="button_count"></fb:like><?php */?>
							
                    </div>
                    <div style="clear:both;width:100px;height:25px;float:left;">
                        <!-- <a href="javascript:AddWishList(<?php echo $this->Catalog[0]->id ?>, <?php echo $product->id ?>);" class="addtowishlist">Add to Wishlist</a> -->
                        <?php if ($this->session->userdata('user_id') != "") { ?>
                            <!-- <a href="#" class="addtowishlist" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Wishlist</a> -->

                            <?php if ($this->session->userdata('user_id') != "") { ?>
                                <a href="#" class="addtowishlist" onClick="javascript:addtowishlist('<?php echo $this->Catalog[0]->id ?>', '<?php echo $product->id ?>', this);" style="margin-top:5px">Add to Wishlist</a>
                            <?php } else { ?>
                                <a href="/signin" class="addtowishlist_nosignin">Add to Wishlist</a>
                            <?php } ?>

                        <?php } else { ?>
                            <a href="/signin" class="addtowishlist_nosignin" cid="<?php echo $this->Catalog[0]->id ?>" pid="<?php echo $product->id ?>">Add to Wishlist</a>
                        <?php } ?>
                    </div>
                    <?php /*?><div style="width:55px;height:25px;float:left;overflow:hidden;">
                        <a href="http://twitter.com/share"
                            class="twitter-share-button"
                            data-url="<?php echo base_url() ?>shop/<?php echo strtolower($this->Catalog[0]->url) ?>/<?php echo strtolower($product->url) ?>"
                            data-count="none" data-via="josie_maran">Tweet</a>
                    </div><?php */?>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
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