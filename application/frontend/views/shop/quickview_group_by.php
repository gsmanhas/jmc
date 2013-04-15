<?php	
	// $Swatch = $this->db->query(
	// 	"SELECT p.url," .
	// 	"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
	// 	"(SELECT image FROM product_swatch as ps WHERE ps.id = p.swatch_id AND publish = 1 AND is_delete = 0) as 'image' ," .
	// 	"(SELECT DISTINCT sorting FROM product_group_by WHERE with_id = p.id limit 1) as 'sorting'" .
	// 	", swatch_name, swatch_title, color  " .
	// 	"FROM product as p " .
	// 	"WHERE id in (SELECT with_id FROM `product_group_by` WHERE pid = ?) ORDER BY sorting ASC", $this->product[0]->id
	// );
	// // echo $this->db->last_query();
	
	$Swatch = $this->db->query("SELECT with_id FROM product_group_by WHERE pid = ? ORDER BY sorting ASC", $this->product[0]->id);
	
?>

<?php if (count($Swatch->result()) >= 1) { ?>
	
	<?php if ($this->product[0]->id == 122 || $this->product[0]->id == 123) { ?>
		<h4>Various Scents Available</h4>	
	<?php } else if ($this->product[0]->id == 119 || $this->product[0]->id == 120 || $this->product[0]->id == 121) { ?>
		<h4>Various Sizes Available</h4>	
	<?php } else { ?>
		<h4>Various Colors Available</h4>
	<?php } ?>

<?php } ?>


<?php foreach ($Swatch->result() as $value): ?>
	
	<?php
	$GroupBy = $this->db->query(
		"SELECT p.url," .
		"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
		"(SELECT image FROM product_swatch as ps WHERE ps.id = p.swatch_id AND publish = 1 AND is_delete = 0) as 'image', " .
		"swatch_name, swatch_title, color  " .
		"FROM product as p " .
		"WHERE id = ?", $value->with_id
	)->result();

	?>
	
	<div class="swatch">
		<a href="<?php echo base_url() . 'quickview/' . strtolower($GroupBy[0]->catalog_name) . '/' . strtolower($GroupBy[0]->url) ?>">
			<img src="<?php echo base_url() . substr($GroupBy[0]->image, 1, strlen($GroupBy[0]->image)) ?>" alt="<?php echo $GroupBy[0]->swatch_name ?>" style="background-color:#<?php echo $GroupBy[0]->color ?>" />
		</a>
		<h6><?php echo $GroupBy[0]->swatch_name ?></h6>
		<p><?php echo $GroupBy[0]->swatch_title ?></p>
	</div>	
<?php endforeach ?>