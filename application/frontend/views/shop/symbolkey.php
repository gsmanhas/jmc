<?php	
	$Symbol = $this->db->query(
		"SELECT * FROM " .
		"product_symbolkey " .
		"WHERE id in( " .
		"SELECT sid  " .
		"FROM `product_rel_symbolkey` where pid = ?) " .
		"AND publish = 1 " .
		"ORDER BY `title` asc"
	, $this->product[0]->id);
	
	$symbolkey_title = '';
	$symbolkey_desc  = '';
	$i = 0;
?>
<?php foreach ($Symbol->result() as $key): ?>
<?php
	if ($i == 0) {
		$symbolkey_title = $key->title;
		$symbolkey_desc  = $key->description;
	}
?>
<div class="symbolkey">
	<a href="#">
		<img alt="<?php echo $key->title ?>" src="<?php echo base_url() . substr($key->image, 1, strlen($key->image)) ?>" desc="<?php echo $key->description ?>">
	</a>
</div>
<?php
	$i++;
?>
<?php endforeach ?>

<?php if ($symbolkey_title) { ?>
<div id="symbolkey_desc">
	<strong><?php echo $symbolkey_title ?></strong>&nbsp;:&nbsp;<?php echo $symbolkey_desc ?>
</div>
<?php } ?>
