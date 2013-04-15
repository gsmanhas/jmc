<?php

/**
* 
*/
class Voucherorders extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index() {
		
		$this->load->view('voucher_orders');
	}
	
	public function search()
	{
		$code = $this->input->post('voucher_code');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		// $this->db->select("discount_id, discount_code, discount, order_date, order_no, promo_free_shipping, freeshipping");
		// $this->db->from("order as o");
		// $this->db->where('discount_id <>', 0);
		// $this->db->order_by('order_date', 'asc');
		if($code) {
            /*$query = $this->db->query("SELECT voucher.*, o.*, orv.amount,
                                            DATE_FORMAT(orv.created_at, '%Y-%m-%d') as 'odate',
                                            DATE_FORMAT(orv.created_at, '%p') as 'oapm',
                                            DATE_FORMAT(orv.created_at, '%T') as 'otime'
                                       FROM `order_voucher_details` as `voucher`, `order_rel_voucher` as `orv`, `order` as `o`
                                       WHERE voucher.is_delete = 0
                                           AND voucher.code=?
                                           AND orv.voucher_id = voucher.id
                                           AND o.id = orv.order_id", $code);*/
										   
			 $query = $this->db->query("SELECT *, order_rel_voucher.amount as v_amount FROM 
		                                          `order_voucher_details`, `order_rel_voucher` , `order` 
                                                   WHERE
												       order_voucher_details.is_delete = 0 
													   and order_voucher_details.id = order_rel_voucher.voucher_id 
													   and order_rel_voucher.order_id = order.order_no                                                       
                                                       AND order_voucher_details.code=?
                                                       group by order.order_no", $code);							   
										   
        } else {
		
            /*$query = $this->db->query("SELECT voucher.*, o.*, orv.amount,
                                                        DATE_FORMAT(orv.created_at, '%Y-%m-%d') as 'odate',
                                                        DATE_FORMAT(orv.created_at, '%p') as 'oapm',
                                                        DATE_FORMAT(orv.created_at, '%T') as 'otime'
                                                   FROM `order_voucher_details` as `voucher`, `order_rel_voucher` as `orv`, `order` as `o`
                                                   WHERE voucher.is_delete = 0
                                                       AND DATE(orv.created_at) >= ?
                                                       AND DATE(orv.created_at) <= ?
                                                       AND orv.voucher_id = voucher.id
                                                       AND o.id = orv.order_id group by o.order_no", array($from_date, $to_date));*/
													   
           $query = $this->db->query("SELECT *, order_rel_voucher.amount as v_amount  FROM 
		                                          `order_voucher_details`, `order_rel_voucher` , `order` 
                                                   WHERE
												       order_voucher_details.is_delete = 0 
													   and order_voucher_details.id = order_rel_voucher.voucher_id 
													   and order_rel_voucher.order_id = order.order_no                                                       
                                                       AND DATE(order.order_date) >= '".$from_date."' and DATE(order.order_date) <= '".$to_date."'
                                                       group by order.order_no");													   
													   
        }

		
		$result = $query->result();
		

        if (count($result) >= 1) {

            $this->Orders = $result;
			// echo $this->db->last_query();
			$this->load->view("voucher_orders_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
}