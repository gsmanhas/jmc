<?php

/**
* 
*/
class Quick_view extends MY_Controller
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
		
		// echo $this->uri->segment(2, 0).br(1);
		// echo $this->uri->segment(3, 0).br(1);
	
		$query = $this->db->query(
			'SELECT p.* ' .
			' FROM product as p WHERE is_delete = 0 AND url = \'' . $this->uri->segment(3, 0) . '\''
		);
		
		$this->product = $query->result();
		
		if (count($this->product) >= 1) {
			
			$query = $this->db->query(
				'SELECT ps.id, ps.image, ps.title, ps.description' .
			    ' FROM ' .
			    'product_rel_symbolkey as prs ' .
			    'JOIN ' .
			    'product_symbolkey as ps ' .
			    'ON prs.sid = ps.id ' .
			    'WHERE prs.pid = ' . $this->product[0]->id .
			    ' ORDER by ps.title asc');
			
			$this->symbolkeys = $query->result();
			
			$query = $this->db->query(
				'SELECT ps.id, ps.image, ps.title, ps.description' .
			    ' FROM ' .
			    'product_rel_ingredients as prs ' .
			    'JOIN ' .
			    'product_ingredients as ps ' .
			    'ON prs.ing_id = ps.id ' .
			    'WHERE prs.pid = ' . $this->product[0]->id .
			    ' ORDER by ps.title asc');
			
			$this->ingredients = $query->result();
			
			$query = $this->db->query("SELECT with_id FROM works_well_with WHERE pid = " . $this->product[0]->id);
			$this->Works_Well_With = $query->result();
			
			$CatalogInfo = $this->db->query('SELECT * from product_catalogs WHERE url = \'' . $this->uri->segment(2, 0) . '\'');
			
			$this->Catalog = $CatalogInfo->result();	
			
			$this->load->view('quick_view');
			
		} else {
			redirect('page-not-found', 'refresh');
		}
	}
}