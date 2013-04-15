<!DOCTYPE HTML>
<html xmlns:og="http://opengraphprotocol.org/schema/"
xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Josie Maran Cosmetics</title>
	<meta name="Keywords" content="Josie Maran Cosmetics, eGift Card $<?php echo $this->card[0]->gift_voucher_balance ?>" />
	<meta name="Description" content="Josie Maran Cosmetics, eGift Card $<?php echo $this->card[0]->gift_voucher_balance ?>" />
	<?php $this->load->view('base/head') ?>
	<meta property="og:title" content="Josie Maran Cosmetics, eGift Card $<?php echo $this->card[0]->gift_voucher_balance ?>"/>
    <meta property="og:url" content="<?php echo current_url() ?>"/>
    <meta property="og:site_name" content="Josie Maran Cosmetics"/>
    <meta property="fb:admins" content="polly@josiemarancosmetics.com"/>
    <meta property="og:description" content="Josie Maran Cosmetics, eGift Card $<?php echo $this->card[0]->gift_voucher_balance ?>"/>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/giftcard.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="/js/jquery.raty.js"></script>
	<?php $this->load->view('base/ga') ?>
    <link href="/css/giftcard.css" type="text/css" rel="stylesheet" />
	
</head>
<body id="details">
	
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
		<div id="pagetitle"><h1>eGift Card $<?php echo $this->card[0]->gift_voucher_balance ?></h1></div>
		
		<div id="productimage" style="width: 400px;">
            <?php if(empty($this->card[0]->gift_voucher_image_big)):?>
			<div class="card" style="width:350px;height:250px;font-size: 28px;padding: 20px;">
                <div class="card_header">
                    JOSIE MARAN
                </div>
                <div class="card_balance">$<?php echo $this->card[0]->gift_voucher_balance?></div>
                <div class="card_footer" style="bottom: 20px;right: 20px;">eGift Card</div>
            </div>
            <?php else:?>
            <img src="<?php echo $this->card[0]->gift_voucher_image_big?>" alt="JOSIE MARAN $<?php echo $this->card[0]->gift_voucher_balance?> eGift Card" style="margin-bottom: 40px; border: 0px;" />
            <?php endif?>
		</div>
				
		<div id="productinfo" style="width: 570px;">
			<form id="frmMain" name="frmMain" action="/egiftcard" method="post" accept-charset="utf-8">
			<div>
                <table width="100%" cellpadding="10" cellspacing="10">
                    <tr>
                        <td>
                            <label>To</label>
                            <br />
                            <input type="text" style="width: 260px;" class="inputtext" id="to" name="to" />
                        </td>
                        <td>
                            <label>From</label>
                            <br />
                            <input type="text" style="width: 260px;" class="inputtext" id="from" name="from" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Recipient's Email Address</label>
                            <br />
                            <input type="text" style="width: 260px;" class="inputtext" id="recipient_email" name="recipient_email" />
                        </td>
                        <td>
                            <label>Confirm Recipient's Email Address</label>
                            <br />
                            <input type="text" style="width: 260px;" class="inputtext" id="crecipient_email" name="crecipient_email" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Message - 250 characters limited (optional)</label>
                            <br />
                            <textarea name="message" id="message" style="width: 260px; height: 150px;"></textarea>
                        </td>
                        <td>
                            <div id="buybuttonswrapper">

                                <?php //$this->load->view('shop/swatch') ?>

                                <!--<div class="price">
                                    <span class="regularprice">
                                        <?php /*echo $this->card[0]->gift_voucher_balance*/?>$
                                    </span>
                                </div>-->

                                <div class="buybuttons">
                                    <a href="#" class="addtocart_details" pid="<?php echo $this->card[0]->id ?>">Add to Cart</a>
                                    <?php if ($this->session->userdata('user_id') != "") { ?>
                                        <a href="#" class="addtowishlist" cid="<?php echo $this->card[0]->id ?>" pid="<?php echo $this->card[0]->id ?>">Add to Wishlist</a>
                                    <?php } else { ?>
                                        <a href="/signin" style="margin-top:5px" class="addtowishlist_nosignin" cid="<?php echo $this->card[0]->id ?>" pid="<?php echo $this->card[0]->id ?>">Add to Wishlist</a>
                                    <?php } ?>

                                </div>
                                <div class="socialbuttons">

                                    <fb:like href="<?php echo current_url() ?>"
                                        show_faces="false" colorscheme="light" layout="button_count"></fb:like>

                                    <a href="http://twitter.com/share"
                                        class="twitter-share-button"
                                        data-text="I love Josie Maran products! Check this one out: "
                                        data-url="<?php echo current_url() ?>"
                                        data-count="horizontal" data-via="josie_maran">Tweet</a>
                                </div>

                            </div>
                        </td>
                    </tr>
                </table>
			</div>

            <input type="hidden" name="category_id" value="<?php echo $this->card[0]->id ?>" id="category_id">
            <input type="hidden" name="product_id" value="<?php echo $this->card[0]->id ?>" id="product_id">
            <input type="hidden" name="qty" value="" id="qty">
            <input type="hidden" name="method" value="" id="method">

			
			</form>
			
		</div>
		
		
		
	</div>
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>

