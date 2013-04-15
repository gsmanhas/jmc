<?php

/**
* 
*/
class Voucherreport extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index() {
		
		$this->load->view('voucher_report');
	}
	
	public function search()
	{
		$from_date = $this->input->post('from_date');
		$to_date  = $this->input->post('to_date');
		
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		

		// $this->db->select("discount_id, discount_code, discount, order_date, order_no, promo_free_shipping, freeshipping");
		// $this->db->from("order as o");
		// $this->db->where('discount_id <>', 0);
		// $this->db->order_by('order_date', 'asc');
		
		$query = $this->db->query("SELECT *,
                                        DATE_FORMAT(created_at, '%Y-%m-%d') as 'odate',
                                        DATE_FORMAT(created_at, '%p') as 'oapm',
                                        DATE_FORMAT(created_at, '%T') as 'otime'
                                   FROM order_voucher_details
                                   WHERE is_delete = 0
                                       AND DATE(created_at) > ?
                                       AND DATE(created_at) < ?", array($from_date, $to_date));

		
		$result = $query->result();

        if (count($result) >= 1) {

            foreach($result as $key => $voucher) {
                if($voucher->gift_voucher_type == 'purchased') {
                    $query = $this->db->query("SELECT 	firstname, lastname FROM `order` WHERE id=?", $voucher->order_id);
                    $name = $query->result();

                    $result[$key]->name = $name[0]->firstname . " " . $name[0]->lastname;
                } else {
                    $result[$key]->name = '';
                }
            }
			
			$this->Orders = $result;
			// echo $this->db->last_query();
			$this->load->view("voucher_report_result", $data);
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
	
	
	public function export()
	{
		$from_date = $this->uri->segment(3);
		$to_date  = $this->uri->segment(4);
		
		
		
		

		// $this->db->select("discount_id, discount_code, discount, order_date, order_no, promo_free_shipping, freeshipping");
		// $this->db->from("order as o");
		// $this->db->where('discount_id <>', 0);
		// $this->db->order_by('order_date', 'asc');
		
		$query = $this->db->query("SELECT *,
                                        DATE_FORMAT(created_at, '%Y-%m-%d') as 'odate',
                                        DATE_FORMAT(created_at, '%p') as 'oapm',
                                        DATE_FORMAT(created_at, '%T') as 'otime'
                                   FROM order_voucher_details
                                   WHERE is_delete = 0
                                       AND DATE(created_at) > ?
                                       AND DATE(created_at) < ?", array($from_date, $to_date));

		
		$result = $query->result();

        if (count($result) >= 1) {

            foreach($result as $key => $voucher) {
                if($voucher->gift_voucher_type == 'purchased') {
                    $query = $this->db->query("SELECT 	firstname, lastname FROM `order` WHERE id=?", $voucher->order_id);
                    $name = $query->result();

                    $result[$key]->name = $name[0]->firstname . " " . $name[0]->lastname;
                } else {
                    $result[$key]->name = '';
                }
            }
			
			$this->Orders = $result;
			// echo $this->db->last_query();
			$this->load->view("voucher_report_result_xls");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
}

?>