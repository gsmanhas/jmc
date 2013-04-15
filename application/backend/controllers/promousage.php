<?php

/**
* 
*/
class Promousage extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index() {
		
		$this->load->view('promo_report');
	}
	
	public function search()
	{
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		

		// $this->db->select("discount_id, discount_code, discount, order_date, order_no, promo_free_shipping, freeshipping");
		// $this->db->from("order as o");
		// $this->db->where('discount_id <>', 0);
		// $this->db->order_by('order_date', 'asc');
		
		$this->db->select("discount_code, discount_id, count(id) as `orders`, sum(amount) as `subtotals`, sum(discount) as `discount`");
		$this->db->from("order as o");
		$this->db->where('discount_id <>', 0);
		$this->db->where('(order_state = 4 or order_state = 2)');
		//$this->db->where('order_state', 2);
		$this->db->group_by('discount_id');
		$this->db->order_by('discount_code', 'asc');
		
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$Query = $this->db->get()->result();
		
		if (count($Query) >= 1) {
			
			$this->Orders = $Query;
			// echo $this->db->last_query();
			$this->load->view("promo_report_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
}