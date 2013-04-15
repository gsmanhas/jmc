<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="Author" content="Six Spoke Media" />
	<meta name="viewport" content="width=1024" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
	<title>Customer Services - Josie Maran Cosmetics</title>	
	<meta name="Keywords" content="" />
	<meta name="Description" content="" />
	<?php $this->load->view('base/head') ?>
	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function(){
			
			// console.log(jQuery("#services_options"));
			
			jQuery("#services_options").children().each(function(index, value) {
				if (jQuery(this).attr('selected') == true) {
					if (index >= 1) {
						jQuery("#services_title").html(jQuery(this).text());
					}
				}
			});
			
			jQuery("#services_options").change(function(){
				jQuery("select option:selected").each(function(){
					if (jQuery(this).text() != "-- Please select --") {
						jQuery("#services_title").html(jQuery(this).text());
					} else {
						jQuery("#services_title").html("");
					}
				});
				
			});
			
		});
	</script>
	<style type="text/css" media="screen">
		
		.error {
			color:#d02121;
		}
		
	</style>

</head>
<body id="contactus" class="customerservice">
	
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
		
		<div id="pagetitle"><h1>Contact Us</h1></div>
		
		<div id="sidenav">
			<ul>
				<li><a href="/my-order">My Order					   				</a></li>
				<li><a href="/shipping-returns">Shipping and Returns		  	    </a></li>
				<li><a href="/myaccount/account-info">My Account				  			    </a></li>
				<li><a href="/new-to-josie-maran">New to Josie Maran Cosmetics	</a></li>
				<li><a href="/faqs">Frequently Asked Questions  					</a></li>
				<li class="selected"><a href="#">Contact Us				 	    </a></li>
				<li><a href="/email-signup-page">Sign-Up for Email								</a></li>
				<li><a href="/corporate">Corporate Information				    </a></li>
				<li><a href="/privacy">Privacy Policy 			    			</a></li>
				<li><a href="/terms">Terms and Conditions	 			   	    </a></li>
			</ul>
		</div>
		
		<div id="contentwrapperright" class="accountinputbox">
		
		<p>Thank you for taking the time to email us. We appreciate your inquiries and comments. Please note that for inquiries regarding your order, registration, your account, and/or technical support, it may take us 1-2 days to respond. For general questions and comments, distribution inquiries, product recommendations, and press inquiries, it may take us 3-5 days to respond. Please be sure to include your full email address.</p>

		<p>If you would like to speak to a customer service representative, please call our toll free number (855) 461-6512 from 9am to 5pm Pacific Time Monday through Friday. If calling from outside the U.S. please call +1-323-461-6512 and then press 0.</p>
		
		<div style="width: 100%; height: 1px; border-bottom: 1px solid rgb(243, 228, 233); margin-bottom: 15px; margin-top: 15px;">&nbsp;</div>
		
		<form action="/contact-us/submit" method="post" accept-charset="utf-8">
			
			<p>
				<label><strong>Please select the type of question you hove and fill out the form below.</strong><?php echo form_error('services_options', '<span class="error">&nbsp;&nbsp;&nbsp;', '</span>'); ?></label>
				<br>
				<select name="services_options" id="services_options" size="1" class="dropdown" style="width:275px;">
					<option value="">-- Please select --</option>
					<?php foreach ($this->CaseCatalogs as $catalogs): ?>
					<option value="<?php echo $catalogs->id ?>" <?php echo (set_value('services_options') == $catalogs->id) ? "selected=\"selected\"" : "" ?>><?php echo $catalogs->name ?></option>
					<?php endforeach ?>
				</select>
			</p>
						
			<p>
				<label>First Name<?php echo form_error('first_name', '<span class="error">&nbsp;&nbsp;&nbsp;', '</span>'); ?></label><br>
				<input type="text" name="first_name" value="<?php echo set_value('first_name') ?>" id="first_name" class="inputtext">
			</p>
			
			<p>
				<label>Last Name<?php echo form_error('last_name', '<span class="error">&nbsp;&nbsp;&nbsp;', '</span>'); ?></label><br>
				<input type="text" name="last_name" value="<?php echo set_value('last_name') ?>" id="last_name" class="inputtext">
			</p>
			
			<p>
				<label>Email Address<?php echo form_error('email', '<span class="error">&nbsp;&nbsp;&nbsp;', '</span>'); ?></label><br>
				<input type="text" name="email" value="<?php echo set_value('email') ?>" id="email" class="inputtext">
			</p>
			
			<p>
				<label>Comments<?php echo form_error('comments', '<span class="error">&nbsp;&nbsp;&nbsp;', '</span>'); ?></label><br>
				<textarea name="comments" rows="8" cols="40" class="textarea"><?php echo set_value('comments') ?></textarea>
			</p>
			
			<p>
				<label>Do you use Josie Maran Cosmetics? (optional)</label><br>
				<input type="radio" name="use_jmc_cosmetics" value="1" id="">Yes<br>
				<input type="radio" name="use_jmc_cosmetics" value="0" id="">No
			</p>
			
			<p>
				<label>Are you a registered member with the Josie Maran Cosmetics Web Site? (optional)</label><br>
				<input type="radio" name="is_register" value="1" id="">Yes<br>
				<input type="radio" name="is_register" value="0" id="">No
			</p>
			
			<br>
			
			<p><input type="submit" value="submit" class="inputbutton"></p>
		
		</form>
		
		</div>
		
	</div>
	
	<?php $this->load->view('base/footer') ?>
	<?php $this->load->view('base/facebook') ?>
	
</body>
</html>