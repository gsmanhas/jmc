<div id="sidenav" style="height:305px;">
	<ul>
		<li <?php echo ($this->uri->segment(2) == "") ? 'class="selected"' : '' ?>>
			<a href="<?php echo secure_base_url() ?>myaccount/account-info">My Account</a>
		</li>
		<li <?php echo ($this->uri->segment(2) == "") ? 'class="selected"' : '' ?> style="line-height:1.25em;">
			<a href="<?php echo secure_base_url() ?>myaccount/password">Change<br>Password</a>
		</li>
		<li <?php echo ($this->uri->segment(2) == "") ? 'class="selected"' : '' ?>>
			<a href="<?php echo secure_base_url() ?>myaccount/order-status">Order Status</a>
		</li>
		<li <?php echo ($this->uri->segment(2) == "") ? 'class="selected"' : '' ?>>
			<a href="<?php echo secure_base_url() ?>myaccount/wishlist">Wishlist</a>
		</li>
	</ul>
</div>