<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Order Status - Josie Maran Cosmetics</title>
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<link rel="stylesheet" href="/css/shadowbox.css" type="text/css" />
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/shadowbox.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/myaccount.js" type="text/javascript" charset="utf-8"></script>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="myaccount">
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
		
		<div id="pagetitle"><h1>Order Status</h1></div>
		
		<?php $this->load->view('myaccount/left_menu') ?>
		
		<?php if (count($this->Orders) >= 1) { ?>
		<table border="0" cellpadding="5" cellspacing="0" style="width:810px;float:right;">
			<tr style="background-color:#f3e4e9;color:#493838;">
				<td align="left" width="22%">Order No</td>
				<td align="center" width="22%">Order Date</td>
				<td align="right" width="8%">Amount</td>
				<td align="center" width="18%">Order Status</td>
				<td align="center" width="30%">Tracking No.</td>
			</tr>
			<?php foreach ($this->Orders as $Order): ?>
			<tr style="border-bottom:1px dotted #ccc;">
				<td align="left"><a href="<?php echo $Order->order_no ?>" class="order_no" id="<?php echo $Order->id ?>"><?php echo $Order->order_no ?></a></td>
				<td align="center"><?php echo $Order->order_date ?></td>
				<td align="right">$<?php echo $Order->amount ?></td>
				<td align="center"><?php echo $Order->order_state ?></td>
				<td align="center"><?php echo $Order->track_number ?></td>
			</tr>
			<?php endforeach ?>
		</table>
		<?php } else { ?>
			<div style="width:810px;float:right;">
				You don't have any order. Please <a href="/shop">click here</a> to continue shopping.
			</div>
		<?php } ?>
		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

