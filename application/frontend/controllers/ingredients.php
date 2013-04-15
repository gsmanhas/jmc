<?php

/**
* 
*/
class Ingredients extends MY_Controller
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
		
		$this->db->select('*');
		$this->db->from('product_ingredients');
		$this->db->where('publish', 1);
		$this->db->where('is_delete', 0);
		$this->db->order_by('ordering', 'asc');
		
		$this->Ingredients = $this->db->get()->result();
		
		$this->load->view('ingredients');
	}
	
}