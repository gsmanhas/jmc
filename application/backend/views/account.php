<div id="top-menu">
	
	<!-- <a title="My account" href="#">My account</a> |
	<a title="Settings" href="#">Settings</a> -->
	
	<!--<span>Logged in as <a title="Logged in as admin" href="#">admin</a></span>
	| <a title="Edit profile" href="#">Edit profile</a>-->
	<span style="margin-right:5px;font-weight:bold;">Hello, <?php echo $this->session->userdata('username') ?></span>&nbsp;|&nbsp;<a title="Logout" href="<?php echo base_url() ?>admin/logout" style="color:#493838;margin-left:5px;margin-right:10px;font-weight:bold;text-decoration:underline;">Sign Out</a>
</div>
<div id="sitename">
	<a title="Josie Maran Cosmetics" class="logo float-left" href="<?php echo base_url() . "admin/home" ?>">Josie Maran Cosmetics</a>
	<div class="button float-right" style="margin-right:10px;">
		<img src="<?php echo base_url() ?>images/logo-6sm-2.png" alt="Powered by Six Spoke Media" />
	</div>
</div>