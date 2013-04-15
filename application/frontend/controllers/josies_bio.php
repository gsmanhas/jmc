<?php
/**
* 
*/
class Josies_bio extends MY_Controller
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
		$result = $this->db->query("SELECT * FROM special_page WHERE id = ?", 3);
		$this->webpage = $result->result();
		
		$this->special_page_with_portfolio = $this->db->query(
			"SELECT * FROM josies_profolio WHERE id in (" .
			"SELECT pro_id FROM `special_page_with_bio` WHERE sp_id = 3 ) AND is_delete = 0"
		)->result();
		
		$this->portfolio_count = count($this->special_page_with_portfolio) / 4;
		
		$this->special_page_with_reel = $this->db->query(
			"SELECT * FROM josies_reel WHERE id in (" .
			"SELECT reel_id FROM `special_page_with_bio` WHERE sp_id = 3 ) AND is_delete = 0"
		)->result();
		
		$this->reel_count = count($this->special_page_with_reel) / 4;
		
		
		$this->load->view('josies_bio');
	}
}