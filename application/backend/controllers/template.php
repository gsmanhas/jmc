<?php

/**
* 
*/
class Template extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->admin->is_login();
		
		//	載入後台的選單資訊
		$this->db->select('*')->from('sys_menus');
		$query = $this->db->get();
		$this->menus = $query->result();
		
		if ($this->session->userdata('ip_address') == "0.0.0.0") {
			$this->output->enable_profiler(TRUE);
		}
	}
	
	public function index()
	{
		// $result = $this->db->query("SELECT * FROM product_catalogs WHERE is_delete = 0 ORDER BY ordering asc");
		// $this->catalogs = $result->result();
		
		$this->load->view('admin/template');
	}
	
}
