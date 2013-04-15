<?php	
	
	$Swatch = $this->db->query(
		"SELECT with_id, sorting FROM product_group_by WHERE pid = ? ORDER BY sorting ASC", $this->product[0]->id
	);
	
	// echo $this->db->last_query();
	
	if (count($Swatch->result()) >= 1) {
?>
		<?php if ($this->product[0]->id == 122 || $this->product[0]->id == 123) { ?>
			<h4>Various Scents Available</h4>	
		<?php } else if ($this->product[0]->id == 119 || $this->product[0]->id == 120 || $this->product[0]->id == 121) { ?>
			<h4>Various Sizes Available</h4>	
		<?php } else { ?>
			<h4>Various Colors Available</h4>
		<?php } ?>

<?php
	}
	
	foreach ($Swatch->result() as $item) {

		// echo $item->with_id.br(1);
		
		$GroupBy = $this->db->query(
			"SELECT p.url," .
			"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
			"(SELECT image FROM product_swatch as ps WHERE ps.id = p.swatch_id AND publish = 1 AND is_delete = 0) as 'image', " .
			"swatch_name, swatch_title, color  " .
			"FROM product as p " .
			"WHERE id = ?", $item->with_id
		)->result();
		
		// echo $this->db->last_query();
?>

		<?php if (count($GroupBy) >= 1) { ?>
	
			<div class="swatch">
				<a href="<?php echo base_url() . 'shop/' . strtolower($GroupBy[0]->catalog_name) . '/' . strtolower($GroupBy[0]->url) ?>">
					<img src="<?php echo base_url() . substr($GroupBy[0]->image, 1, strlen($GroupBy[0]->image)) ?>" alt="<?php echo $GroupBy[0]->swatch_name ?>" style="background-color:#<?php echo $GroupBy[0]->color ?>" width="25" height="25" />
				</a>
				<h6><?php echo $GroupBy[0]->swatch_name ?></h6>
				<p><?php echo $GroupBy[0]->swatch_title ?></p>
			</div>

		<?php } ?>

<?php
		
	}
	
	
?>

