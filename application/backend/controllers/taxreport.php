<?php

/**
* 
*/
class Taxreport extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index() {
		
		$this->load->view('tax_report');
	}
	
	public function search()
	{
		$this->db->query("SET SQL_BIG_SELECTS=1");
		
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		$this->db->select(
			"SUM(ol.price * ol.qty) as 'subtotal'"
		);	
		
				
		$this->db->from("order as o");		
		$this->db->join('order_list as ol', 'o.id = ol.order_id', 'left');
		$this->db->where('destination_state', 'CA');
		$this->db->where('order_state != 5 AND order_state != 6 AND o.is_delete = 0 AND ol.is_delete = 0');
		$this->db->where("item_type", "voucher");
		$this->db->where('order_state != 5 AND order_state != 6 AND o.is_delete = 0 AND ol.is_delete = 0');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$SubTotal_Voucher = $this->db->get()->result();
		
		$this->db->select(
			"(SELECT sum(product_tax) FROM `order` WHERE destination_state != 'CA' AND is_delete = 0) as `tax_collected`," .
			"sum(ol.price * ol.qty) as 'subtotal'," .
			// "sum(o.shipping_price) as 'shipping_charged'," .
			"(SELECT sum(shipping_price) FROM `order` WHERE destination_state != 'CA' AND freeshipping = 0 AND promo_free_shipping = 0) as 'shipping_charged'," .
			"sum(o.discount) as 'discounts'"
		);
		
		$this->db->from("order as o");
		$this->db->join('order_list as ol', 'o.id = ol.order_id', 'left');
		$this->db->where("item_type", "voucher");
		$this->db->where('destination_state !=', 'CA');
		$this->db->where('order_state != 5 AND order_state != 6');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$this->db->where('o.is_delete = 0');
		
		$NOT_CA_Query_Voucher = $this->db->get()->result();
		$this->NOT_CA_Voucher = $NOT_CA_Query_Voucher;
		$this->SubTotal_Voucher = $SubTotal_Voucher;
		
		/*************************************************************/
		$this->db->select(
			"SUM(ol.price * ol.qty) as 'subtotal'"
		);	
		
				
		$this->db->from("order as o");		
		$this->db->join('order_list as ol', 'o.id = ol.order_id', 'left');
		$this->db->where('destination_state', 'CA');
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6 AND o.is_delete = 0 AND ol.is_delete = 0');
		$this->db->where("item_type !=", "voucher");
		$this->db->where('order_state != 5 AND order_state != 6 AND o.is_delete = 0 AND ol.is_delete = 0');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$SubTotal = $this->db->get()->result();
		
		// echo $this->db->last_query();
		
		$this->db->select(
			"SUM(product_tax) as 'product_tax'"
		);
		$this->db->from("order as o");
		$this->db->where('destination_state', 'CA');
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$Product_tax = $this->db->get()->result();
		
		
		$this->db->select(
			"SUM(calculate_shipping) as shipping_charged"
		);
		$this->db->from("order as o");
		$this->db->where('destination_state', 'CA');
		$this->db->where('promo_free_shipping', 0);
		$this->db->where('freeshipping', 0);
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$Shipping_Charged = $this->db->get()->result();
		
		// echo $this->db->last_query().br(2);
		
		
		$this->db->select(
			"SUM(discount) as 'discount'"
		);
		$this->db->from("order as o");
		$this->db->where('destination_state', 'CA');
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$Discount = $this->db->get()->result();
		
		// echo $this->db->last_query();
		
		$this->db->select(
			"(SELECT sum(product_tax) FROM `order` WHERE destination_state != 'CA' AND is_delete = 0) as `tax_collected`," .
			"sum(ol.price * ol.qty) as 'subtotal'," .
			// "sum(o.shipping_price) as 'shipping_charged'," .
			"(SELECT sum(shipping_price) FROM `order` WHERE destination_state != 'CA' AND freeshipping = 0 AND promo_free_shipping = 0) as 'shipping_charged'," .
			"sum(o.discount) as 'discounts'"
		);
		
		$this->db->from("order as o");
		$this->db->join('order_list as ol', 'o.id = ol.order_id', 'left');
		$this->db->where("item_type !=", "voucher");
		$this->db->where('destination_state !=', 'CA');
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6');
		$this->db->order_by('order_date', 'asc');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$this->db->where('o.is_delete = 0');
		
		$NOT_CA_Query = $this->db->get()->result();

		// echo $this->db->last_query().br(2);

		if (count($SubTotal) >= 1 && count($Shipping_Charged) >= 1 && count($Discount) >= 1 && count($Product_tax) >= 1) {
			
			// $this->CA     = $CA_Query;
			
			$this->SubTotal = $SubTotal;
			$this->Shipping_Charged = $Shipping_Charged;
			$this->Discount = $Discount;
			$this->Product_tax = $Product_tax;
			
			$this->NOT_CA = $NOT_CA_Query;
			
			$this->load->view("tax_report_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
	
	public function sales_ca()
	{
		$release_date = $this->input->post('release_date');
		$expiry_date  = $this->input->post('expiry_date');
		
		$this->db->select(
			"id, order_no, product_tax, order_date, destination_state, tax_rate, shipping_price, discount, amount, freeshipping, promo_free_shipping" .
			",(SELECT sum(qty * price) FROM order_list as ol WHERE o.id = ol.order_id) as `subtotal`," .
			" DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
			" DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
			" DATE_FORMAT(o.order_date, '%T') as 'otime'" 
		, FALSE);
		$this->db->from('`order` as o');
		$this->db->where('destination_state', 'CA');
		$this->db->where('o.order_state != 3 AND order_state != 5 AND order_state != 6');
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		$this->db->where('o.is_delete = 0');
		
		$this->CA_Query = $this->db->get()->result();
				
		$this->last_query = $this->db->last_query();
		$this->load->view('tax_report_cs_result');
		
	}

	public function export()
	{
		$rep = new Reporting();
		$rep->export_sales_tax_report("Sales Tax Report", $this->input->post('last_query'));
		$this->index();
	}
	
}