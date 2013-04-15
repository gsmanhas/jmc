<?php if ($this->session->userdata('username') != '') { ?>

	<div id="reviewinput" style="margin-bottom:10px;height:auto;overflow:hidden;position:relative;">
		
		<div id="review_message" style="display:none;"></div>
		
		<div class="title">Rate This Product</div>
		
		<div id="rate_this_product"></div>
		
		<div class="title">Title of Your Review</div>
		
		<input type="text" name="review_title" value="" id="review_title" style="font-size:0.917em;line-height:1.25em;background:none;border:1px solid #ccc;width:245px;float:left;padding:3px 5px;">
		
		<div class="title">Your Review</div>
		
		<textarea id="reviews" name="reviews" class="reviews" maxlength="2000" style="font-size:0.917em;line-height:1.25em;background:none;border:1px solid #ccc;width:245px;height:70px;float:left;padding:3px 5px;"></textarea>	
		
		<p class="receive_message" style="margin:0;color:#977778;font-style:italic;"></p>	
		
		<p class="review-submit" style="float:left;"><input type="button" id="btnReviewSubmit" value="Submit Review &#187;" class="inputbutton" style="position:absolute;bottom:1px;right:0;"></p>
	
	</div>

<?php } else { ?>

	<div>
		<div id="review_message" style="display:none;"></div>
		<p class="receive_message" style="margin-bottom:10px;">Please <a href="/signin">sign in</a> to post your review.</p>
	</div>

<?php } ?>