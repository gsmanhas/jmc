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
		
		<div id="pagetitle"><h1>My Account</h1></div>

		<div class="span-12 last">
			<fieldset id="order(s)" class="">
				<legend>Order(s)</legend>
				<?php
					$this->db->select('*');
					$this->db->from('order');
					$this->db->where('is_delete', 0);
					$this->db->where('user_id', $this->session->userdata('user_id'));
					$this->db->order_by('order_no', 'desc');
					foreach ($this->db->get()->result() as $order) {
						printf("<p><a href=\"%s\">%s</a></p>", $order->order_no, $order->order_no);
					}
				?>				
			</fieldset>
		</div>
		
		<div class="span-12">
			<fieldset id="wish_list" class="">
				<legend>Wish List</legend>
				<?php
					foreach ($this->wishlist as $wish) {
						$this->db->select('name, retail_price, price, small_image, on_sale');
						$this->db->from('product');
						$this->db->where('id', $wish->pid);
						$WishList_Product = $this->db->get()->result_array();
						if (count($WishList_Product) >= 1) {
							printf("<div>%s</div>", $WishList_Product[0]['name']);
							printf("<div><img src=\"%s\" /></div>", $WishList_Product[0]['small_image']);
							if ($WishList_Product[0]['on_sale'] == 1) {
				?>
								<span style="text-decoration: line-through; color: red">$<?php echo number_format($WishList_Product[0]['retail_price'], 2) ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="price"><?php echo number_format($WishList_Product[0]['price'], 2) ?></span>
				<?php
							} else {
				?>
								<span class="price">$<?php echo number_format($WishList_Product[0]['retail_price'], 2) ?></span>
				<?php			
							}
							printf("<div><a href=\"%s\">Remove this Item</a></div>", base_url() . "member/remove_wish_list/" . $wish->id);
						}
					}
				?>
			</fieldset>
		</div>

		
	</div>
	
	<?php $this->load->view('base/footer') ?>	
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>

