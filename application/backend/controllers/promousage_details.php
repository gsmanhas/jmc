<?php

class Promousage_details extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{	
		$this->load->view('promousage_details');
	}
	
	public function search()
	{

		// echo $this->uri->segment(3, 0);

		// if ($this->uri->segment(3, 0) != 0) {
		// 	echo $this->uri->segment(3, 0);
		// }
		

		
		
		// echo $this->input->post('release_date');
		
		// $result = $this->db->query(
		// 	"SELECT * FROM `order` as o WHERE discount_id = ? ", 
		// 	$this->uri->segment(3, 0)
		// );
	
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
	
		$this->db->select("*");
		$this->db->from("order as o");
		$this->db->where('discount_id', $this->uri->segment(3, 0));

		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}

		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
	
		$this->orders = $this->db->get()->result();
		
		$this->load->view('promousage_details');
		
		
		
	}
	
}
