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
	<script type="text/javascript" charset="utf-8">
		var base_url = '/';
	</script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/giftcard.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
    <link href="/css/giftcard.css" type="text/css" rel="stylesheet" />
    <style type="text/css">
        .check-balance {
            background: none repeat scroll 0 0 #F4E4E9;
            border: 1px solid #F4E4E9;
            border-radius: 20px 20px 20px 20px;
            display: block;
            float: right;
            font-size: 15px;
            font-weight: normal;
            height: 25px;
            left: 0;
            padding-top: 5px;
            position: relative;
            text-align: center;
            top: 0;
            width: 205px;
        }

        .check-balance:hover {
            text-decoration: none;
        }

    </style>
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
		
		<div id="pagetitle">
			<h1>
				eGift Cards
                <a class="check-balance" href="/egiftcards/balance">Check eGift Card Balance</a>
			</h1>
		</div>
		
		<?php $this->load->view('shop/left_menu') ?>
		
		<form id="frmMain" name="frmMain" action="/myshoppingcart" method="post" accept-charset="utf-8">		
			<div id="productwrapper">
				<?php if(count($this->cards) > 0): ?>
				<?php
					$i = 1;
					foreach ($this->cards as $card) {
						
						$this->card_id = $card->id;

				?>

				<div class="product">
                    <a href="/egiftcards/<?php echo $card->id ?>">

                        <?php if(empty($card->gift_voucher_image_small)):?>
                        <div class="card">
                            <div class="card_header">
                                JOSIE MARAN
                            </div>
                            <div class="card_balance">$<?php echo $card->gift_voucher_balance?></div>
                            <div class="card_footer">eGift Card</div>
                        </div>
                        <?php else:?>
                        <img src="<?php echo $card->gift_voucher_image_small?>" alt="JOSIE MARAN $<?php echo $card->gift_voucher_balance?> eGift Card" style="margin-bottom: 40px; border: 0px;" />
                        <?php endif?>

                    </a>
                    <h3><a href="/egiftcards/<?php echo $card->id ?>">eGift Card $<?php echo $card->gift_voucher_balance ?></a></h3>
					<div style="width:256px;height:auto;overflow:hidden;">
						<div style="width:42px;height:26px;float:left;">
							<span class="regularprice">$
							<?php echo $card->gift_voucher_balance; ?>

							</span>
						</div>
						<div class="buybuttons" style="width:92px;height:26px;float:left;margin-right:3px;">
							<a href="#" class="addtocart" pid="<?php echo $card->id ?>">Add to Cart</a>
						</div>
												
						<div class="socialbuttons" style="width:119px;height:26px;float:left">							
							<fb:like 
							href="<?php echo base_url() ?>egiftcards/<?php echo $card->id ?>" layout="button_count"></fb:like>
						</div>

						<div class="price" style="width:42px;height:26px;float:left;">
							
						</div>
						<div style="width:92px;height:26px;float:left;margin-right:3px;">
							<?php if ($this->session->userdata('user_id') != "") { ?>
								<a href="#" class="addtowishlist" cid="<?php //echo $this->Catalog[0]->id ?>" pid="<?php echo $card->id ?>">Add to Wishlist</a>
							<?php } else { ?>
								<a href="/signin" class="addtowishlist_nosignin" cid="<?php //echo $this->Catalog[0]->id ?>" pid="<?php echo $card->id ?>">Add to Wishlist</a>
							<?php } ?>
						</div>
						<div style="width:119px;height:26px;float:left;">
							<a href="http://twitter.com/share" 
								class="twitter-share-button" 
								data-text="I love Josie Maran eGift Card! Check this one out: "
								data-url="<?php echo base_url() ?>egiftcards/<?php echo $card->id?>"
								data-count="horizontal" data-via="josie_maran">Tweet</a>
						</div>
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
			<input type="hidden" name="sort_by" value="" id="sort_by">
			<input type="hidden" name="sort_type" value="" id="sort_type">
            <?php else:?>
             <h4>No eGift Cards found</h4>
            <?php endif;?>
		</form>

	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
	<div class="overlay">
		<div class="overlay-message">
			<p><span id="x_items">X</span> has been added to your cart.</p>
			<div class="overlay-box">
				<a href="../controllers">Continue Shopping</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="../controllers">Proceed to Checkout</a>
			</div>
		</div>
	</div>
		
</body>
</html>

