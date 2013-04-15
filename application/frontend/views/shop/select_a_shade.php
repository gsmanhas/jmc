<?php
	$x_top = ceil(count($this->shade) / 3);
	if (count($this->shade) >= 1) {
		
		// print_r($this->shade);
	
	
?>

<div id="shadebox_<?php echo $this->product_id ?>" class="shadeboxupward" style="display:none;">
	<div class="shadeboxheader">
		
	<?php
	if ($this->product_id == 122 || $this->product_id == 123) {
		printf("<h4>Select a Scent</h4>");
	} else if ($this->product_id == 119 || $this->product_id == 120 || $this->product_id == 121) {
		printf("<h4>Select a Size</h4>");		
	} else {
		printf("<h4>Select a Shade</h4>");
	}
	?>
		<a class="closebutton">Close</a>
	</div>
	<div class="shadewrapper" style="position:absolute;top:-<?php echo (($x_top * 136) >= 408) ? "408" : ($x_top * 136) ?>px;z-index:9999;width:186px<?php echo ($x_top > 3) ? ";overflow-y:auto;max-height:408px" : "" ?>">
		<?php
		//echo $this->product_id;
		$ShadeSorting = $this->db->query("SELECT with_id FROM product_group_by WHERE pid = ? ORDER BY sorting ASC", $this->product_id)->result();
		
		// echo $this->db->last_query();
		
		foreach ($ShadeSorting as $item) {
					
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
		<?php foreach ($GroupBy as $item): ?>
		<div class="shade">
			<a href="/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($item->url) ?>">
				<img src="<?php echo base_url() . $item->image ?>" style="background-color:#<?php echo $item->color ?>" />
			</a>
			<h6>
				<a href="/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($item->url) ?>" >
					<?php echo $item->swatch_name ?>
				</a>
			</h6>
			<?php /*?><p>
				<a href="/shop/<?php echo $this->Catalog[0]->url ?>/<?php echo strtolower($item->url) ?>" style="font-weight:normal;">
					<?php echo $item->swatch_title ?>
				</a>
			</p><?php */?>
		</div>
		<?php endforeach ?>
		<?php } ?>
	</div>
</div>
<?php
	}
?>


