<?php

/**
* 
*/
class Testimonials extends MY_Controller
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
		$this->db->from('testimonials');
		$this->db->where('is_delete', 0);
		$this->db->where('publish', 1);
		$this->db->order_by('ordering', 'asc');
		
		$this->Testimonials = $this->db->get()->result();
		
		$this->load->view('testimonials');
	}
	
}