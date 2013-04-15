<?php

/**
* 
*/
class Voucheramount extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index() {
		
		$this->load->view('voucher_amount');
	}
	
	public function search()
	{
		$code = $this->input->post('voucher_code');
		$date = $this->input->post('date');

		$query = $this->db->query("SELECT *
                                   FROM `order_voucher_details` as `voucher`
                                   WHERE voucher.code=?", $code);



        if ($query) {
            $voucher = $query->row();

			$amount = 0;
			if(isset($date) && $date!="") {
            $query = $this->db->query("SELECT SUM(amount) as amount
                                       FROM `order_rel_voucher` as `orv`
                                       WHERE DATE(created_at) > ? AND voucher_id = ?
                                       GROUP BY voucher_id", array($date, $voucher->id));
            if($query) {
                $data = $query->row();
                if(!empty($data)) {
                    $amount = $query->row()->amount;
                } else {
                    $amount = 0;
                }
            } else {
                $amount = 0;
            }
			}

            $balance = $voucher->balance + $amount;
			
            $this->amount = $balance;
			

			$this->load->view("voucher_amount");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
}