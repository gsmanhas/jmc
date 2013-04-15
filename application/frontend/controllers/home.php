<?php

/**
* 
*/
class Home extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{		
		$this->db->select('*');
		$this->db->from('home_banners');		
		$this->HomeBanners = $this->db->get()->result();
		$this->load->view('home');
	}
		
}
