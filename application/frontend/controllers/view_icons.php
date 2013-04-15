<?php

/**
* 
*/
class View_icons extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{
		$this->index();
	}
	
	public function index()
	{
		
		$Query = $this->db->query(
			"SELECT id, name, small_image, title, price, retail_price, on_sale, url, can_pre_order, in_stock, " .
			"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
			"(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
			"FROM product as p WHERE id in (SELECT DISTINCT pid FROM product_rel_symbolkey WHERE sid = ?) ORDER BY name asc", $this->uri->segment(2, 0)
		);
		
		$this->icons = $Query->result();
		
		$this->load->view('view_icons');
	}
	
}