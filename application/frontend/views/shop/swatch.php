<?php	
	$Swatch = $this->db->query(
		"SELECT " .
		"(SELECT image FROM product_swatch as ps WHERE ps.id = p.swatch_id AND publish = 1 AND is_delete = 0) as 'image' " .
		", swatch_name, swatch_title, color  " .
		"FROM product as p " .
		"WHERE id = ? AND swatch_id != 0", $this->product[0]->id
	);
	// echo $this->product[0]->id;
	// echo $this->db->last_query();
?>
<?php foreach ($Swatch->result() as $value): ?>
<div class="swatch active" style="float:none;">
	<a href="#">
		<img src="<?php echo base_url() . $value->image ?>" alt="<?php echo $value->swatch_name ?>" 
			style="background-color:#<?php echo $value->color ?>" />
	</a>
	<h6><?php echo $value->swatch_name ?></h6>
	<p style="clear:both; width:100px;">&nbsp;<?php //echo $value->swatch_title ?></p>
</div>
<?php endforeach ?>