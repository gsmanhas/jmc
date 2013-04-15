<?php

/**
* 
*/
class Salesbysku extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		
		$this->load->view('sales_by_sku_report');
	}

	public function export()
	{
		$rep = new Reporting();
		$rep->sales_by_SKU("Sales by SKU", $this->input->post('last_query'));
		$this->index();
	}

	public function search()
	{
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		// $this->db->distinct();
		$this->db->select("p.sku, p.name as `product`, o.order_date, sum(qty) as 'qty', o.amount, ol.price");
		$this->db->from("order as o");
		$this->db->join('`order_list` as ol', 'o.id = ol.order_id AND item_type=\'product\'', 'left');
		$this->db->join('`product` as p', 'ol.pid = p.id', 'left');
		$this->db->group_by('product, sku');
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$this->db->where('o.is_delete = 0');
		
		$Query = $this->db->get()->result();
		
		// echo $this->db->last_query();
		// 
		// exit;
		
		if (count($Query) >= 1) {
			
			$this->Orders = $Query;
			$this->last_query = $this->db->last_query();
			$this->load->view("sales_by_sku_report_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
	
}