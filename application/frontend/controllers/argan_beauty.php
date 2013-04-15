<?php
/**
* 
*/
class Argan_beauty extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
	
	public function _remap()
	{
		$this->index();
	}
	
	public function index()
	{
		$result = $this->db->query("SELECT * FROM special_page WHERE id = ?", 1);
		$this->webpage = $result->result();
		
		$this->special_page_with_product = $this->db->query("SELECT * FROM `special_page_with_product` WHERE sp_id = ? ORDER BY ordering ASC", 1)->result();
		
		
		$this->load->view('argan_beauty');
	}
}