<div id="utilities" >
	<div id="freeshipping" style="float:left" >
		<a href="/freeshipping">Free Shipping on orders of $25 or more</a>
	</div>
	<div id="oldheader" style="float:right">
	<div id="account">
		<?php if ($this->session->userdata('username') == '' && $this->session->userdata('account_type') != "manager") { ?>
		<a href="/signin">sign in</a>&nbsp;or&nbsp;<a href="/register">register</a>&nbsp;/&nbsp;
		<?php } ?>
		
		<?php if ($this->session->userdata('account_type') == 'customer') { ?>
		Hello, <a href="/myaccount/account-info"><?php echo $this->session->userdata('firstname') ?></a>&nbsp;/&nbsp;<a href="/signout">sign out</a>&nbsp;/&nbsp;	
		<?php } ?>
		
		<?php if ($this->session->userdata('username') == '') { ?>
		<a href="/signin">wishlist</a>&nbsp;/&nbsp;
		<?php } ?>
		
		<?php if ($this->session->userdata('account_type') == 'customer') { ?>
		<a href="/myaccount/wishlist">wishlist (<span id="wishlist_total_items"><?php echo $this->session->userdata('wishlist_count') ?></span>)</a>&nbsp;/&nbsp;
		<?php } ?>
		<a href="/viewcart">cart (<span id="cart_total_items"><?php echo $this->cart->total_qtys() ?>&nbsp;product<?php if($this->cart->total_qtys() > 1) { echo 's'; } ?></span>)</a>
</div>

	<div id="search">
		<form action="/search-products" method="post" accept-charset="utf-8" id="searchForm" name="searchForm">
			<?php /*<a href="#" id="btnSearch">Enter Search Here</a>*/ ?>
			<input type="text" name="txtsearch" value="Enter Search Here" id="txtsearch" maxlength="40">
			<a href="#" style="float:left;" id="btngo">go</a>
		</form>
	</div>
	</div>
	
</div>