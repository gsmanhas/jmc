<?php

	$Query = $this->db->query(
		"SELECT pr.id, pr.title, pr.rate, pr.message, DATE_FORMAT(pr.created_at, '%M %d %Y') as 'created_at'," .
		"(SELECT firstname	 FROM users WHERE id = pr.uid) as 'name'" .
		" FROM product_review as pr WHERE pid = ? AND is_delete = 0 AND publish = 1 order by ordering ASC", 
			$this->product[0]->id
	);
	
	// echo $this->db->last_query();
	
	$Rate = $this->db->query(
		"SELECT SUM(pr.rate) as 'total_rate', COUNT(id) as 'total_count' FROM product_review as pr WHERE pid = ? AND is_delete = 0 AND publish = 1 order by created_at desc"
	, $this->product[0]->id)->result();
	
	// echo $this->db->last_query();
	
	$rate_number = 0;
	
	if (count($Rate) >= 1) {
		if ($Rate[0]->total_count >= 1) {
			$rate_number = round($Rate[0]->total_rate / $Rate[0]->total_count);
		}
	}
	
?>
<div id="overall_rating">
	<div id="overall_rating_container">
		<div style="float:left;font-style:italic;line-height:2em;">Overall Rating:&nbsp;</div>
		<div id="total_rating" style="float:left;"></div>
	</div>
	
	<script type="text/javascript" charset="utf-8">
		$('#total_rating').raty({
		  readOnly:  true,
		  start:     <?php echo $rate_number ?>
		});
	</script>
	
	<?php
	$i = 0;
	$DISPLAY = "";
	foreach ($Query->result() as $review) {		
		if ($i > 2) {
			$DISPLAY = "style='display:none'";
		}
	?>
	<div class="product_rating_list" <?php echo $DISPLAY ?>>
		<div class="review_container">
			<div class="review_by">
				<div class="stars">
					<div id="fixed<?php echo $review->id?>"></div>
					<script type="text/javascript" charset="utf-8">
						$('#fixed<?php echo $review->id?>').raty({
						  readOnly:  true,
						  start:     <?php echo $review->rate ?>
						});
					</script>
				</div>
				<div class="review_by_title">Review By</div>
				<div class="review_by_name"><?php echo $review->name ?></div>
				<div class="review_by_date"><?php echo $review->created_at ?></div>
			</div>
			<div class="review_content">
				<h2><?php echo $review->title ?></h2>
				<div>
					<?php 
						// echo strlen($review->message).br(1);
						if (strlen($review->message) <= 144) {
							echo $review->message;
						} else {
							echo substr($review->message, 0, 144)."<a href='' class='review_morelink' id='review_btn_".$review->id."'>...View More</a>";
							echo "<span style='display:none'>".substr($review->message, 144)."</span>";
						}	
					?>
				</div>
			</div>
		</div>
	</div>	
	<?php
		$i++;
	}
	
	if ($i > 2) {
		printf("<div class='readmore_reviews'><a id='readmore_reviews' href=''>&#187;&nbsp;Read More Reviews</a></div>");
	}
	
	?>			
</div>
