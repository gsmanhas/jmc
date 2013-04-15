<?php

/**
* 
*/
class About_the_brand extends MY_Controller
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
		
		$result = $this->db->query("SELECT * FROM special_page WHERE id = ?", 5);
		$this->webpage = $result->result();
		
		$this->load->view('about_the_brand');
	}
	
}