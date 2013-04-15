<?php

/**
* 
*/
class Press extends MY_Controller
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
		$result = $this->db->query("SELECT * FROM press WHERE is_delete = 0 AND publish = 1 ORDER BY ordering asc");
		$this->press = $result->result();
			
		$this->load->view('press');
	}
}
