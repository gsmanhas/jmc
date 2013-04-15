<div id="topnav">
<ul>
	<li class="shop"><a href="/shop">Shop</a>
	<ul class="sub_shop">
	<?php
		$Product_Catalogs = $this->db->query("SELECT * FROM product_catalogs where ordering >= 1 and is_delete=0 order by ordering asc");
		foreach ($Product_Catalogs->result() as $Catalog) {
	?>
	<li <?php echo ($this->uri->segment(2) == $Catalog->url) ? 'class="selected"' : '' ?>>
		<a href="<?php echo "/shop/" . $Catalog->url ?>"><?php echo $Catalog->name ?></a>
	</li>
	<?php		
		}
	?>
   <?php /*?> <li <?php echo ($this->uri->segment(2) == 'egiftcards') ? 'class="selected"' : '' ?>>
        <a href="/egiftcards">eGift Cards</a>
    </li><?php */?>
	</ul>
	</li>
	<li>|</li>
	<?php
	$i = 0;
	$count = count($this->dynamicMenus);
	?>
	<?php foreach ($this->dynamicMenus as $menuItem): ?>
	<li class="<?php echo $menuItem->classname ?>">
		<a href="<?php echo $menuItem->url ?>"><?php echo $menuItem->title ?></a>
		<?php
			$this->db->select('*');
			$this->db->from('menus');
			$this->db->where('is_delete', 0);
			$this->db->where('parent', $menuItem->id);
			$this->db->order_by('ordering', 'asc');
			$SubMenu = $this->db->get()->result();
			if (count($SubMenu) != 0) {
		?>
				<ul class="sub_<?php echo $menuItem->classname ?>">
				<?php foreach ($SubMenu as $SubItem): ?>
					<li>
						<a href="<?php echo strtolower($SubItem->url) ?>">
							<?php echo $SubItem->title ?>
						</a>
					</li>
				<?php endforeach ?>
				</ul>
		<?php
			}
		?>
	</li>
	<?php if ($i < $count - 1) { ?>
	<li>|</li>
	<?php }
		$i++;
	?>
	<?php endforeach ?>
</ul>
</div>