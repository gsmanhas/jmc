<?php 
	if($this->router->fetch_class() != 'home') {
 ?><noscript>
 <div style="text-align:left; color:#FF0000;  clear:both; width:900px; font-size:11px;  margin:auto; padding:auto; margin-top:10px;" align="center">
   <span style="font-weight:bold">Note:</span>
   Note: JavaScript is not enabled on your computer/laptop. Please enable it from your browser in order to enjoy a trouble free experience on the site. 
   
    Alternatively you can visit <a style="color:#FF0000;" href="http://support.google.com/adsense/bin/answer.py?hl=en&answer=12654" target="_blank" >HERE</a> to learn how to enable it.
 </div>
</noscript>

<?php } ?>


<div style="clear:both">&nbsp;</div>
<div id="footer" style="margin-top:10px;">
	<div id="signup">
		<form method=post action="https://app.icontact.com/icp/signup.php" name="icpsignup" id="icpsignup7867" accept-charset="UTF-8" onsubmit="return verifyRequired7867();" >
		<input type="text" value="sign up for email" name="fields_email" id="signup_for_email">
		<a href="#" id="go">go</a>
		<input type=hidden name=redirect value="<?php echo base_url() ?>email-signup-success" />
		<input type=hidden name=errorredirect value="<?php echo base_url() ?>email-signup-error" />					
	    <input type=hidden name="listid" value="80976">
	    <input type=hidden name="specialid:80976" value="595Q">
	    <input type=hidden name=clientid value="279625">
	    <input type=hidden name=formid value="7867">
	    <input type=hidden name=reallistid value="1">
	    <input type=hidden name=doubleopt value="0">
		</form>
	</div>
	<div id="footerlinks">
		<?php foreach ($this->FooterMenus as $footer): ?>
			<a href="<?php echo "/" . $footer->url ?>"><?php echo $footer->title ?></a>&nbsp;|&nbsp;
		<?php endforeach ?>
		
		<?php if ($this->session->userdata('account_type') == 'customer') { ?>
			<a href="<?php echo "/" ?>myaccount">my account</a>
		<?php } else { ?>
			<a href="<?php echo "/" ?>signin">my account</a>
		<?php } ?> 
		
		&nbsp;&nbsp;&nbsp;
		<a href="#" id="copyright">&#169; <?=date('Y')?> josie maran cosmetics</a>
	</div>
			
	<div id="footericons">
		<a href="http://www.youtube.com/user/josiemarancosmetics" target="_blank"><img src="/images/global/icon-youtube.png" alt="" /></a>
		<a href="http://www.facebook.com/josiemarancosmetics" target="_blank"><img src="/images/global/icon-facebook.png" alt="" /></a>
		<a href="http://twitter.com/josie_maran" target="_blank"><img src="/images/global/icon-twitter.png" alt="" /></a>
		<a href="http://chicological.com" target="_blank"><img src="/images/global/icon-blog.png" alt="" /></a>
		<a href="/about-the-brand#view-icons" style="cursor:default;"><img src="/images/global/icon-crueltyfree.png" alt="" /></a>
	</div>
	<?php //if($this->router->fetch_class() != 'home') { ?>
	<?php $this->load->view('base/footer_bottom') ?>
	<?php //} ?>
	
</div>
<!-- Google Code for General Website Remarketing Remarketing List -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 964930626;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "666666";
var google_conversion_label = "raMDCMbD0QIQwtiOzAM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/964930626/?label=raMDCMbD0QIQwtiOzAM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<!-- begin Marin Software Tracking Script -->
<script type='text/javascript'>
    var _marinClientId = "209453p11938";
    var _marinProto = (("https:" == document.location.protocol) ? "https://" : "http://");
    document.write(unescape("%3Cscript src='" + _marinProto + "tracker.marinsm.com/tracker/" +
        _marinClientId + ".js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type='text/javascript'>
    try {
        _marinTrack.trackPage();
    } catch(err) {}
</script>
<noscript>
    <img src="https://tracker.marinsm.com/tp?act=1&cid=209453p11938&script=no" >
</noscript>
<!-- end Copyright Marin Software -->
