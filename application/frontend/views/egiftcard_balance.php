<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Check Gift Card / Voucher Balance</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<meta name="author" content="">
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
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
		<div id="pagetitle"><h1>Check Gift Card / Voucher Balance</h1></div>
        <form method="post" action="/egiftcards/balance">
            <table>
                <tr>
                    <td><b>Gift Card / Voucher Code:</b></td>
                    <td><input type="text" name="code" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        For your security enter the word shown below.
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php
                            echo $this->cap['image'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="text" name="captcha" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input style="cursor:pointer;border: 1px solid rgb(244, 228, 233); width: 100%; height: 30px; border-radius: 20px 20px 20px 20px; background: none repeat scroll 0% 0% rgb(244, 228, 233); font-weight: bold; color: rgb(73, 56, 56); margin-top: 10px;" type="submit" value="check value" /></td>
                </tr>
            </table>
        </form>
        <div style="font-weight: bold; font-size: 17px;">
            <?php echo @$this->voucher_message;?>
        </div>
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
</body>
</html>