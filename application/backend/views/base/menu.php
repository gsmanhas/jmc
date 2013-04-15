<?php
foreach($this->menus as $menu) {
?>
<ul id="navigation" class="sf-navbar sf-js-enabled">
	<li>
		<a href="<?php echo base_url() . "admin.php/" . strtolower($menu->url) ?>" class="sf-with-ul"><?php echo $menu->title ?></a>
		<?php
			$submenus = $this->db->query('SELECT * FROM sys_submenus WHERE parent_id = ? order by ordering asc', $menu->id);
		?>
		<ul style="">
			<?php
			foreach ($submenus->result() as $submenu) {
			?>
			<li><a href="<?php echo base_url() . "admin.php/" . strtolower($submenu->url) ?>"><?php echo $submenu->title ?></a></li>
			<?php
			}
			?>
		</ul>
	</li>
</ul>
<?php
}
?>