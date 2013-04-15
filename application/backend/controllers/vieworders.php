<?php

/**
* 
*/
class Vieworders extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{		
		$Query = $this->db->query("SELECT * FROM order_state WHERE id order by id asc");
		$this->OrderState = $Query->result();
		
		$this->load->view('view_orders');
	}
	
	public function export()
	{
		// echo $this->input->post('last_query');				
		$rep = new Reporting();
		$rep->export_view_orders("View Orders", $this->input->post('last_query'));
		$this->index();

	}
		
	public function search()
	{
		$this->load->library('pagination');
		$config['base_url'] = $this->config->item('base_url').'/admin.php/vieworders/search/';
		$config['total_rows'] = 100;
		$config['per_page'] = '10';
		$config['full_tag_open'] = '';
		$config['full_tag_close'] = '';
		$config['num_links'] = '10';
		
		$config['cur_tag_open'] = '<span class="emm-page emm-current">';
		$config['cur_tag_close'] = '</span>';

		$config['first_link'] = 'First';
		$config['first_tag_open'] = '';
		$config['first_tag_close'] = '';

		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '';
		$config['last_tag_close'] = '';
		
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';

		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		
		$config['uri_segment'] = '3';
		$this->pagination->initialize($config);
		
		if(isset($_POST) && $_POST!="") {
			$order_no     = $this->input->post('order_no');
			$release_date = $this->input->post('release_date');
			$expiry_date  = $this->input->post('expiry_date');
			$first_name   = $this->input->post('first_name');
			$last_name    = $this->input->post('last_name');
			$order_state  = $this->input->post('order_state');
			
			$this->session->set_userdata('order_no_ss', $order_no);
			$this->session->set_userdata('release_date_ss', $release_date);
			$this->session->set_userdata('expiry_date_ss', $expiry_date);
			$this->session->set_userdata('first_name_ss', $first_name);
			$this->session->set_userdata('last_name_ss', $last_name);
			$this->session->set_userdata('order_state_ss', $order_state);
		}else {
			
			$order_no     = $this->session->userdata('order_no_ss');
			$release_date = $this->session->userdata('release_date_ss');
			$expiry_date  = $this->session->userdata('expiry_date_ss');
			$first_name   = $this->session->userdata('first_name_ss');
			$last_name    = $this->session->userdata('last_name_ss');
			$order_state  = $this->session->userdata('order_state_ss');
		}
		
		$this->db->distinct();
		$this->db->select(
			" o.created_at as `created_at`, o.id as `order_id`, o.user_id, o.track_number, o.payment_method, o.use_encryption, o.card_type, " .
			"o.firstname, o.lastname, o.discount," .
			", o.order_no, o.bill_address, o.ship_address, o.order_date, o.amount,o.product_tax,calculate_shipping," .
			"o.shipping_id," .
			"promo_free_shipping, freeshipping,freeshipping_db," .
			"(SELECT SUM(price * qty) FROM order_list as ol WHERE ol.order_id = o.id) as 'subtotal'," .
			" DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
			" DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
			" DATE_FORMAT(o.order_date, '%T') as 'otime'," .
			" (SELECT `name` FROM order_state as os WHERE os.id = o.order_state) as `order_state`"
		, FALSE);
		$this->db->from("`order` as o");
		
		// if ($customer_name != "") {
		// 	$this->db->like('o.firstname', $customer_name, 'after');
		// 	$this->db->or_like('o.lastname', $customer_name, 'after');
		// }
		
		if ($first_name != "") {
			$this->db->like('o.firstname', $first_name, 'after');
		}
		
		if ($last_name != "") {
			$this->db->or_like('o.lastname', $last_name, 'after');
		}
		
		if ($order_no != "") {
			$this->db->where('o.order_no', $order_no);
		}
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		if ($order_state != "0") {
			$this->db->where('o.order_state = ', $order_state);
		}
		
		$this->db->where("o.is_delete = 0");
		
		$this->db->order_by('order_date', 'desc');
		//$this->db->limit($config['per_page'], $this->uri->segment(3));
		$Query = $this->db->get()->result();
		
		$this->QUERY_STRING = $this->db->last_query();
				
		if (count($Query) >= 1) {
			
			$this->Orders = $Query;
			$this->last_query = $this->db->last_query();
			// echo $this->db->last_query();
			$this->load->view("view_order_search_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
	
}