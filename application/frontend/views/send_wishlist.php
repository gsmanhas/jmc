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
	<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script type="text/javascript" charset="utf-8">
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script src="/js/shop.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.error {
			color:#d02121;
		}
	</style>
	<?php $this->load->view('base/ga') ?>
</head>
<body id="send_wishlist">

	<div id="main" style="width:550px;">
		
		<div id="pagetitle" style="width:550px;margin-top:10px;"><h1 style="margin-bottom:10px;">Send Wishlist to My Friends</h1></div>
		
		<div style="width:450px;margin:0 auto;">
			
			<!--
<div id="closebutton">
				<a href="javascript:window.parent.Shadowbox.close();"><img src="/images/global/btn-close.gif" alt="Close" /></a>
			</div>
-->
			
			<form action="/send-wishlist/submit" method="post" accept-charset="utf-8">
				
				<label>Subject</label>
				<?php echo form_error('subject', '<span class="error">&nbsp;&nbsp;', '</span>'); ?>
				<br>
				<input type="text" name="subject" value="<?php echo set_value('subject') ?>" id="subject" class="inputtext" style="width:445px;">
				<br><br>
				
				<label>My Friends' Email (use semicolon(;) to separate different emails)</label>
				<?php echo form_error('maillist', '<span class="error">&nbsp;&nbsp;', '</span>'); ?>
				<br>
				<input type="text" name="maillist" value="<?php echo set_value('maillist') ?>" id="maillist" class="inputtext" style="width:445px;">
				<br><br>
				
				<label>Say something to my friends</label>
				<?php echo form_error('message', '<span class="error">&nbsp;&nbsp;', '</span>'); ?>
				<br>
				<textarea name="message" rows="8" cols="40" style="width:450px;"><?php echo set_value('message') ?></textarea>
				<br><br>
				
				<p><input type="submit" value="Send" class="inputbutton"></p>
				
				<input type="hidden" name="method" value="submit" id="method">
				
			</form>
			
		</div>
			
		</div>
			
	</div>
</body>
</html>

