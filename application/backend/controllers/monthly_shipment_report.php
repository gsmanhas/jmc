<?php

class Monthly_shipment_report extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('monthly_shipment_report');
	}
	
	public function search()
	{		
		// echo $this->input->post('selmonthly');
		
		$_MONTH = "";
		if (($this->input->post('selmonthly'))) {
			$_MONTH = $this->input->post('selmonthly')."-01";
		} else {
			$_MONTH = date("y")."-".date("m")."-01";
		}
		
		// $this->Orders = $this->db->query(
		// 	"SELECT " .
		// 	"(SELECT sku FROM product WHERE id = pid) as 'sku'," .
		// 	"(SELECT name FROM product WHERE id = pid) as 'pname'," .
		// 	"(SELECT DATE_FORMAT(`order_date`, '%M-%d') FROM `order` WHERE order_no = invoice_number) as 'order_date'," .
		// 	" qty, price " .
		// 	"FROM `order_list` WHERE invoice_number in (" .
		// 	"SELECT order_no FROM `order` WHERE" .
		// 	" order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) " .
		// 	" AND LAST_DAY(?) " .
		// 	" AND order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0 ORDER BY order_date ASC" .
		// 	")", array($_MONTH, $_MONTH))->result();
		
		// echo $this->db->last_query();
		
		// $Query = $this->db->query(
		// 	"SELECT DATE_FORMAT(`order_date`, '%M-%d') FROM `order` WHERE " .
		// 	" order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) " .
		// 	" AND LAST_DAY(?) " .
		// 	" AND order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0 " .
		// 	" ORDER BY order_date ASC"
		// , array($_MONTH, $_MONTH));
		// echo $this->db->last_query();

		$this->OrderDate = $this->db->query(
			"SELECT DISTINCT DATE_FORMAT(`order_date`, '%Y-%m-%d') as 'order_date' FROM `order` " .
			" WHERE order_date BETWEEN DATE_FORMAT(CURRENT_DATE , ?) AND DATE_ADD(LAST_DAY(?), INTERVAL 1 DAY) " . 
			" AND order_state != 3 AND order_state != 5 AND order_state != 6 AND is_delete = 0 " . 
			" GROUP BY order_date" .
			" ORDER BY order_date ASC"
			, array($_MONTH, $_MONTH))->result();
		
		// echo $this->db->last_query().br(20);
		
		$this->last_query = $this->db->last_query();
		

		$this->load->view('monthly_shipment_report_result');
	}
	
	public function export()
	{
		$rep = new Reporting();
		$rep->export_monthly_shipment_report($this->input->post('last_query'));
		$this->index();
	}
	
}
