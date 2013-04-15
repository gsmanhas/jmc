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
</head>
<body id="message">
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
		
		<div id="pagetitle"><h1>View Cart</h1></div>
		
		<!-- <div style="width:100%;height:1px;border-bottom:1px solid #f9e4e9;margin-bottom:20px;">&nbsp;</div> -->
		
		<div id="messagewrapper">
		
			<table border="0" cellspacing="5" cellpadding="5" width="100%" style="font-size:1em;margin-bottom:0;">
				<tr style="color:#493838;background-color:#f3e4e9;text-shadow: 0 1px 0 #fff;">
					<th style="text-align:left" width="65%">Product</th>
				 	<th style="text-align:left" width="15%">Unit</th>
					<th style="text-align:center" width="10%">Unit Price</th>
					<th style="text-align:right;" width="10%">Total Price</th>
				</tr>
			</table>
			
			<br><br>
		
			<h2>Your shopping cart is empty</h2>
			<p>Please <a href="<?php echo base_url() ?>/shop">click here</a> to continue shopping</p>
			
			
			<?php 
				$query = $this->db->get_where('webpages', array('id' => 29, 'publish' => '1')); 	
				if($query->num_rows() > 0){ 
					$webpages = $query->row();
			?>
				<p><font size="2" color="black" align="centre"><?php echo $webpages->page_content; ?></font></p>

			<?php } ?>
			
		
		</div>	
		
		
	</div>
	
	<?php $this->load->view('base/footer_cart') ?>
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

