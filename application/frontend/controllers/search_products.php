<?php

/**
* 
*/
class Search_products extends MY_Controller
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
		if ($this->input->post('txtsearch')) {
			$this->db->select('*');
			$this->db->from('product as p');
			$this->db->where('is_delete', 0);
			$this->db->where('publish', 1);
			$this->db->like('p.name', $this->input->post('txtsearch'), 'both');
			$this->db->order_by('name', 'asc');
			$this->SearchResult = $this->db->get()->result();
			if (count($this->SearchResult) >= 1) {
				$this->load->view('search_products');
			} else {
				$this->load->view('not_find');
			}	
		} else {
			$this->load->view('not_find');
		}
	}
		
}