<div id="sidenav">
	<ul>
		<?php
			$Product_Catalogs = $this->db->query("SELECT * FROM product_catalogs where ordering >= 1 and is_delete=0 order by ordering asc");
			foreach ($Product_Catalogs->result() as $Catalog) {
		?>
		<li <?php echo ($this->uri->segment(2) == $Catalog->url) ? 'class="selected"' : '' ?>>
			<a href="<?php echo base_url() . "shop/" . $Catalog->url ?>"><?php echo $Catalog->name ?></a>
		</li>
		<?php		
			}
		?>
       <?php /*?> <li <?php echo ($this->uri->segment(2) == 'egiftcards') ? 'class="selected"' : '' ?>>
            <a href="/egiftcards">eGift Cards</a>
        </li><?php */?>
	</ul>
</div>