<?php

class Reports extends CI_Controller
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
		
		if ($_POST) {
			if ($this->input->post('action') == "search") {
				$this->search();
				return;
			}
		}
		
		$query = $this->db->query("SELECT * FROM `tax_codes` where is_delete = 0 order by `tax_code` asc");
		$this->TaxCodes = $query->result();
		
		$this->load->view('admin/reports');
	}
	
	public function search()
	{
		
		if ($this->input->post('order_no') != "") {
			$query = $this->db->query("SELECT * FROM `order` WHERE order_no = ?", $this->input->post('order_no'));
			$this->Report = $query->result();
			$this->load->view('admin/report_result');
		}
		
		if ($this->input->post('release_date') != "" && $this->input->post('expiry_date')) {
			$query = $this->db->query("SELECT * FROM `order` WHERE order_date between ? and ?", array($this->input->post('release_date'), $this->input->post('expiry_date')));
			$this->Report = $query->result();
			
			$this->load->view('admin/report_result');
		}
		
		if ($this->input->post('customer_name') != "") {
			$query = $this->db->query(
				"SELECT * FROM `order` WHERE firstname like '" . $this->input->post('customer_name') . "%" . "'" .
				" or lastname like '" . $this->input->post('customer_name') . "%'"
			);
			$this->Report = $query->result();
			
			$this->load->view('admin/report_result');
		}
		
		if ($this->input->post('bill_address') != "") {
			$query = $this->db->query(
				"SELECT * FROM `order` WHERE bill_address like '" . $this->input->post('bill_address') . "%" . "'" .
				" or bill_city like '" . $this->input->post('bill_address') . "%'" .
				" or bill_zipcode like '" . $this->input->post('bill_address') . "%'"
			);
			$this->Report = $query->result();
			
			$this->load->view('admin/report_result');
		}
		
		if ($this->input->post('ship_address') != "") {
			$query = $this->db->query(
				"SELECT * FROM `order` WHERE ship_address like '" . $this->input->post('ship_address') . "%" . "'" .
				" or ship_city like '" . $this->input->post('ship_address') . "%'" .
				" or ship_zipcode like '" . $this->input->post('ship_address') . "%'"
			);
			$this->Report = $query->result();
			
			$this->load->view('admin/report_result');
		}
		
		if ($this->input->post('product_name') != "") {
			$query = $this->db->query("SELECT * FROM `product` WHERE name like '" . $this->input->post('product_name') . "%" . "'");
			$this->Report = $query->result();
			
			$this->load->view('admin/report_product');
		}
		
		if ($this->input->post('sku') != "") {
			$query = $this->db->query("SELECT * FROM `product` WHERE name like '" . $this->input->post('sku') . "%" . "'");
			$this->Report = $query->result();
			
			$this->load->view('admin/report_product');
		}
		
		if ($this->input->post('destination_state') != "") {
			$query = $this->db->query("SELECT * FROM `order` WHERE destination_id = ?", $this->input->post('destination_state'));
			$this->Report = $query->result();
			
			$this->load->view('admin/report_result');
		}
		
	}
	
}