<?php

/**
* 
*/
class Shipping_address extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->view('shipping_address');
	}
	
	public function search()
	{
		$_MONTH = "";
		if (($this->input->post('selmonthly'))) {
			$_MONTH = $this->input->post('selmonthly')."-01";
		} else {
			$_MONTH = date("y")."-".date("m")."-01";
		}
		
		$this->Orders = $this->db->query(
			
			"SELECT order_no, order_date, track_number, firstname, lastname, email, phone_number, shipping_same, ship_first_name, ship_last_name " .
			", bill_city, (SELECT tax_code FROM tax_codes WHERE id = bill_state) as 'bill_state', bill_address, bill_zipcode" .
			", ship_city, (SELECT tax_code FROM tax_codes WHERE id = ship_state) as 'ship_state', ship_address, ship_zipcode" .
			" FROM `order` " .
			" WHERE order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " . 
			" AND order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0 " .
			" ORDER BY order_date ASC",
			array($_MONTH, $_MONTH))->result();
					
		// echo $this->db->last_query().br(2);
		
		$this->last_query = $this->db->last_query();
		
		$this->load->view('monthly_shipping_address_report_result');
		
	}
	
	public function export()
	{
		$rep = new Reporting();
		$rep->export_monthly_shipping_address_report($this->input->post('last_query'));
		$this->index();
	}
	
}