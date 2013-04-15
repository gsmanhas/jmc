<?php

/**
* 
*/
class Orders extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

	}
	
	public function index()
	{

		switch ($this->input->post('method')) {
			case "changeState" :
				$this->ShippingOptions($this->input->post('shipping_method'));
				$this->_update($this->input->post('id'));
				return;
			break;
			default:
			break;
		}

		if ($_POST) {
			if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.
				$this->_remove($_POST['id']);
			} else if ($_POST['action'] == "publish" && $_POST['id'] != "") {	// Publish or unPublish
				$this->_publish($_POST['id']);
			} else if ($_POST['action'] == "update" && $_POST['id'] != "") {	// Change Update Mode
				$this->_update($_POST['id']);
				return;				
			} else if ($_POST['action'] == "update_save" && $_POST['id'] != "") {	//	Save Update Data.
				if ($this->_update_save() == false) {
					return;
				}				
			}
		}

		// $result = $this->db->query(
		// 	"SELECT " .
		// 	"o.id, o.order_no, o.order_date, o.user_id, " .
		// 	"o.firstname, o.lastname, o.amount," .
		// 	"(SELECT `name` FROM `order_state` as os WHERE os.id = o.order_state) as `order_state`" .
		// 	" FROM `order` as o WHERE is_delete = 0 ORDER BY order_date desc"
		// );
		// 		
		// $this->orders = $result->result();
		
		$this->ListDestinationState = $this->ListDestinationState();
		$query = $this->db->query("SELECT * FROM `discountcode` where is_delete = 0 order by `code` asc");
		$this->DiscountCode = $query->result();
		
		$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 order by `id` asc");
		$this->shipping_method = $Query->result();
		
		$Query = $this->db->query("SELECT * FROM order_state ORDER BY id ASC");
		$this->order_status = $Query->result();
		
		$this->load->view('orders');	
	}
	
	private function _update_submit_validate()
	{
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'required');
				
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		$this->form_validation->set_rules('bill_state', 'Billing State', 'required');
		
		$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');
		$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
		$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');
		$this->form_validation->set_rules('ship_state', 'Shipping State', 'required');
		
		
		return $this->form_validation->run();
	}
	
	public function update2()
	{
		
		// print_r($_POST);
		
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($this->input->post('id'));
			return false;
		}
		
		$query = $this->db->query('SELECT * FROM state WHERE id = ?', $this->input->post('destination_state'));
		$destination = $query->result();
		$sharthand = "";
		if (count($destination) >= 1) {
			$sharthand = $destination[0]->sharthand;
		}
		
		$query = $this->db->query("SELECT * FROM shipping_method WHERE id = ?", $this->input->post('shipping_method'))->result();
		if (count($query) <= 0) {			
			$shipping_id       = 0;
			$shipping_name     = '';
			$shipping_price    = 0;
			$shipping_delivery = '';
		} else {
			$shipping_id       = $query[0]->id;
			$shipping_name     = $query[0]->name;
			$shipping_price    = $query[0]->price;
			$shipping_delivery = $query[0]->delivery;
		}
		
		$InvoiceNo = $this->db->query("SELECT order_no FROM `order` WHERE id = ?", $this->input->post('id'))->result();
		$INVOICE_NO = '';
		if (count($InvoiceNo) >= 1) {
			$INVOICE_NO = $InvoiceNo[0]->order_no;
		}
		
		$Order = array(
			
			'track_number' => $this->input->post('track_number'),
			
			'firstname'    => $this->input->post('firstname'),
			'lastname'     => $this->input->post('lastname'),
			'email'        => $this->input->post('email'),
			'phone_number' => $this->input->post('phone'),
			
			'order_state'  => $this->input->post('order_state'),
			'ship_first_name'   => $this->input->post('ship_first_name'),
			'ship_last_name'      => $this->input->post('ship_last_name'),				
			'bill_address'   => $this->input->post('bill_address'),
			'bill_city'      => $this->input->post('bill_city'),
			'bill_zipcode'   => $this->input->post('bill_zipcode'),
			'bill_state'     => $this->input->post('bill_state'),

            //'shipping_same'  => $this->input->post('shipSame'),
            
			'ship_address'   => $this->input->post('ship_address'),
			'ship_city'      => $this->input->post('ship_city'),
			'ship_zipcode'   => $this->input->post('ship_zipcode'),
			'ship_state'     => $this->input->post('ship_state'),			
			
			'destination_id' => $this->input->post('destination_state'),
			'destination_state' => $sharthand,
			
			'shipping_id'       => $shipping_id,
			'shipping_name'     => $shipping_name,
			'shipping_price'    => $shipping_price,
			'shipping_delivery' => $shipping_delivery,
			'calculate_shipping'=> $shipping_price,
			
			// shipping_method
			// calculate_shipping
			// 'shipping_id' => $this->input->post('shipping_method'),
			
			// 'product_tax'    => $this->input->post('product_tax'),
			// 'calculate_shipping' => $this->input->post('sel_shipping_option'),
			
			// 'promo' => $this->input->post('promo_discount'),
			'destination_id' => $this->input->post('destination_state'),
			'product_tax' => $this->input->post('hid_product_tax'),
			
			
			// 	'destination_state' => $this->input->post('sel_destination_state')
			
			'amount' => $this->input->post('amount'),
			'track_number' => $this->input->post('track_number')
			
			
		);
		
		// print_r($Order);
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('order', $Order);
		// echo $this->db->last_query();
		
		$OL = $this->db->query("SELECT * FROM `order_list` WHERE order_id = ?", $this->input->post('id'))->result();
		
		foreach ($OL as $item) {
			
			
			// echo $item->pid.br(1);
			// 
			// echo $this->input->post('product_qty_'.$item->id).br(1);
			// echo $this->input->post('product_price_'.$item->id).br(1);
			// echo $this->input->post('product_total_price_'.$item->id).br(1);
			// echo $this->input->post('product_return_'.$item->id).br(1);
			// echo $this->input->post('product_remove_'.$item->id).br(1);
			// if () {
			// 	# code...
			// }
			
			$this->db->query("UPDATE `order_list` SET is_return = ?, price = ?, qty = ? WHERE id = ?", 
				array(
					$this->input->post('product_return_'.$item->id), 
					$this->input->post('product_price_' .$item->id),
					$this->input->post('product_qty_'   .$item->id),
					$item->id)
				);
			
			if ($this->input->post('product_remove_'.$item->id) == 1) {
				// echo $item->id.br(1);
				$this->db->query("UPDATE `order_list` SET is_delete = 1 WHERE id = ?", $item->id);
				// echo $this->db->last_query();
			}
			
			
		}
		
		
		if (isset($_POST['new_product_id'])) {
			
			$ids = ($_POST['new_product_id']);
			$qty = ($_POST['new_product_qty']);
			$price = ($_POST['new_product_price']);
			$subtotal = ($_POST['new_product_subtotal']);


			$i = 0;
			foreach ($ids as $id) {

				// echo $ids[$i].br(1);
				// echo $qty[$i].br(1);
				// echo $price[$i].br(1);
				// echo $subtotal[$i].br(1);

				$newProduct = array(
					'order_id' => $this->input->post('id'),
					'pid'   => $ids[$i],
					'price' => $price[$i],
					'qty'   => $qty[$i],
					'invoice_number' => $INVOICE_NO,
					'updated_at' => unix_to_human(time(), TRUE, 'us')
				);

				$this->db->trans_start();
				$this->db->insert('order_list', $newProduct);
				$this->db->trans_complete();

				$i++;
			}
			
		}
		
		
		$order_qry = $this->db->query("SELECT * FROM `order` WHERE id = '".$this->input->post('id')."' ");
		$order_qry_detail = $order_qry->row();
		
		if($order_qry_detail->is_charge == 'N') {
		
			$is_done = $this->go_to_charge($order_qry_detail);			
			if($is_done){
				$this->db->query("UPDATE `order` SET is_charge = 'Y' WHERE id = '".$this->input->post('id')."' ");
			}
			
		}
				
		
		$this->update_message = "1 records updated";
		
		$this->_update($this->input->post('id'));		
		
	}
	
	public function go_to_charge($order_qry_detail) {
	
			$post_url = "https://test.authorize.net/gateway/transact.dll";
			
			$post_values = array(
	
				"x_login"			=> "3mPZ93Dm7",
				"x_tran_key"		=> "72P6HSdj4C7N37yY",
			
				"x_version"			=> "3.1",
				"x_delim_data"		=> "TRUE",
				"x_delim_char"		=> "|",
				"x_relay_response"	=> "FALSE",
			
				"x_type"			=> "AUTH_CAPTURE",
				"x_method"			=> "CC",
				"x_card_num"		=> $order_qry_detail->card_number,
				"x_exp_date"		=> $order_qry_detail->card_expiry_month.''.$order_qry_detail->card_expiry_year,
			
				"x_amount"			=> $order_qry_detail->amount,
				"x_description"		=> "JMC",
			
				"x_first_name"		=> $order_qry_detail->firstname,
				"x_last_name"		=> $order_qry_detail->lastname,
				"x_address"			=> $order_qry_detail->bill_address,
				"x_state"			=> $order_qry_detail->bill_state,
				"x_zip"				=> $order_qry_detail->bill_zipcode
				
			);

			$post_string = "";
			foreach( $post_values as $key => $value )
				{ $post_string .= "$key=" . urlencode( $value ) . "&"; }
			$post_string = rtrim( $post_string, "& " );
			
			
			$request = curl_init($post_url); // initiate curl object
				curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
				curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
				curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
				curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
				$post_response = curl_exec($request); // execute curl post and store results in $post_response
				// additional options may be required depending upon your server configuration
				// you can find documentation on curl options at http://www.php.net/curl_setopt
			curl_close ($request); // close curl object
			
			// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array = explode($post_values["x_delim_char"],$post_response);
			if($response_array) {
				return true;
			}
			
			return false;

			
			
	}
	
	
	public function addnew()
	{
		
		$this->session->unset_userdata('ShippingOptions');
		$this->session->unset_userdata('DestinationState');
		$this->session->unset_userdata('DiscountCode');
				
		//	Order State
		$query = $this->db->query("SELECT * FROM `order_state`");
		$this->OrderState = $query->result();
		
		//	State
		$query = $this->db->query("SELECT * FROM `state`");
		$this->States = $query->result();
		
		//	Shipping_Method
		$query = $this->db->query("SELECT * FROM `shipping_method`");
		$this->ShippingMethod = $query->result();
		
		$this->ListDestinationState = $this->ListDestinationState();
		
		$query = $this->db->query("SELECT * FROM `discountcode` where is_delete = 0 order by `code` asc");
		$this->DiscountCode = $query->result();
		
		$this->load->view('order_addnew.php');
	}
	
	public function _update_save()
	{
		exit;
		$Order = array(
			
			'firstname'          => $this->input->post('firstname'),
			'lastname'           => $this->input->post('lastname'),
			'email'              => $this->input->post('email'),
			'phone_number'       => $this->input->post('phone'),
			'order_state'        => $this->input->post('order_state'),
			'bill_firstname'     => $this->input->post('bill_firstname'),
			'bill_lastname'      => $this->input->post('bill_lastname'),
			'bill_address'       => $this->input->post('bill_address'),
			'bill_city'          => $this->input->post('bill_city'),
			'bill_zipcode'       => $this->input->post('bill_zipcode'),
			'ship_first_name'    => $this->input->post('ship_first_name'),
			'ship_last_name'     => $this->input->post('ship_last_name'),
			'ship_firstname'     => $this->input->post('ship_firstname'),
			'ship_lastname'      => $this->input->post('ship_lastname'),
			'ship_address'       => $this->input->post('ship_address'),
			'ship_city'          => $this->input->post('ship_city'),
			'ship_zipcode'       => $this->input->post('ship_zipcode'),
			'product_tax'        => $this->input->post('product_tax'),
			'calculate_shipping' => $this->input->post('sel_shipping_option'),
			'promo'              => $this->input->post('promo_discount'),
			'destination_id'     => $this->input->post('sel_destination_state'),
			// 			'destination_state' => $this->input->post('sel_destination_state')
			'amount'             => $this->input->post('amount')
			// 'track_number' => $this->input->post('track_number')
			
			
		);
		
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('order', $Order);
		
		$Products_id        = explode(',', $this->input->post('products_id'));
		$Products_price     = explode(',', $this->input->post('products_price'));
		$Products_qty       = explode(',', $this->input->post('products_qty'));
		
		// print_r($Products_qty);
		
		$Products_subtotal  = explode(',', $this->input->post('products_subtotal'));
		
		$query = $this->db->query("DELETE FROM order_list WHERE order_id = ?", $this->input->post('id'));
		// $query->result();
		
		for ($i = 0; $i < count($Products_id); $i++) {
			
			$OrderList = array(
				'pid'   => $Products_id[$i],
				'price' => $Products_price[$i],
				'qty'   => $Products_qty[$i],
				'order_id' => $this->input->post('id')
			);
			
			$this->db->insert('order_list', $OrderList);
			
		}
		
		$this->update_message = "1 records updated";
		return true;
		
		
	}
	
	public function _update($ndx)
	{
	
		
		// //	Get Order Info.		
		// $result = $this->db->query("SELECT * FROM `order` WHERE is_delete = 0 and id = ? ORDER BY order_date desc", $ndx);
		// 
		// $this->Order = $result->result();
		// 
		// //	Get Order List Info.
		// $query = $this->db->query(
		// 	"SELECT * FROM `order_list` WHERE is_delete = 0 and order_id = " . $ndx
		// );
		// $this->OrderLists = $query->result();
		// 
		// //	Get User Info.
		// $query = $this->db->query(
		// 	"SELECT * FROM users WHERE id = " . $this->Order[0]->user_id
		// );
		// 
		// $this->UserInfo = $query->result();
		// 
		// $query = $this->db->query("SELECT * FROM order_rel_tax_codes WHERE order_id = ?", $ndx);
		// $this->TaxCodes = $query->result();
		// 
		$query = $this->db->query("SELECT * FROM shipping_method order by id asc");
		$this->SPMethod = $query->result();
		// 
		// $query = $this->db->query("SELECT * FROM order_rel_shipping_method WHERE order_id = ?", $ndx);
		// $this->ShippingMethod = $query->result();
		// 
		// $query = $this->db->query("SELECT * FROM `order_rel_discount` WHERE order_id = ?", $ndx);
		// $this->DiscountCode = $query->result();
		// 
		$query = $this->db->query("SELECT * FROM `order_state`");
		$this->OrderState = $query->result();
		// 
		$query = $this->db->query("SELECT * FROM `state` order by sharthand asc");
		$this->States = $query->result();
		// 
		// $query = $this->db->query("SELECT * FROM `product` where is_delete = 0 order by `name` asc");
		// $this->Products = $query->result();
		// 
		// $query = $this->db->query("SELECT * FROM `tax_codes` where is_delete = 0 order by `tax_code` asc");
		// $this->TaxCodes = $query->result();
		// 
		$query = $this->db->query("SELECT * FROM `discountcode` where is_delete = 0 order by `code` asc");
		$this->DiscountCode = $query->result();
		// 
		// $query = $this->db->query("SELECT * FROM `order_rel_discount` where is_delete = 0 order by `code` asc");
		// $this->Order_Rel_Discount = $query->result();
		
		// $query = $this->db->query('SELECT * FROM state order by state asc');
		// $this->continental = $query->result();
		
		$query = $this->db->query("SELECT " .
		"id, (select state from state as s where s.id = t.state_id) as `state`," .
		"state_id, " .
		"tax_code, tax_rate " .
		"FROM " .
		"tax_codes as t " .
		"where is_delete = 0 ORDER BY `state` ASC");
		$this->continental = $query->result();
		
		// //	由這開始
		// $this->ListShippingMethod   = $this->ListShippingMethod();
		// $this->ListDestinationState = $this->ListDestinationState();
		
		//	Shipping_Method
		$query = $this->db->query("SELECT * FROM `shipping_method`");
		$this->ShippingMethod = $query->result();
		
		$this->ListDestinationState = $this->ListDestinationState();
		
		$query = $this->db->query("SELECT * FROM `discountcode` where is_delete = 0 order by `code` asc");
		$this->DiscountCode = $query->result();
		
		$result = $this->db->query(
			"SELECT *, " .
			"DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
			"DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
			"DATE_FORMAT(o.order_date, '%T') as 'otime'" .
			" FROM `order` as o WHERE is_delete = 0 and id = ? ORDER BY order_date desc", $ndx);
		$this->Order = $result->result();
		
		
		
		//	Get Order List Info.
		$query = $this->db->query(
			"SELECT * FROM `order_list` WHERE order_id = ? AND is_delete = 0", $ndx
		);
		
		$this->OrderList = $query->result();

        $query = $this->db->query("SELECT * FROM order_rel_voucher WHERE order_id = ?", $ndx);
        if($query->num_rows() > 0) {
            $this->Voucher = $query->row();
        } else {
            $this->Voucher = null;
        }

        // print_r($this->TaxCodes);
		
		$this->LAST_QUERY = $this->input->post('last_query');

        $this->notes = $this->db->query("SELECT notes.*, manager.username as username FROM notes, manager
                                            WHERE notes.order_id=? AND manager.id = notes.created_by", $ndx)->result();

		$this->load->view('orders_update');
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update `order` set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where id = ' . $id);	
					$numrows += $this->db->affected_rows();

					$this->db->query('update `order_list` set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where order_id = ' . $id);
				}
								
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE) {
					$this->error_message = "發生了異常的動作, 系統取消了上次的動作";
					$this->db->trans_rollback();
				} else {
					if ($numrows != 0) {
						$this->message =  $numrows . " records deleted";	
					}
				}
			}
		}
	}
	
	public function ListDestinationState()
	{
		$Options = array();
		$Query = $this->db->query("SELECT " .
		"id, (select state from state as s where s.id = t.state_id) as `state`," .
		"state_id, " .
		"tax_code, tax_rate " .
		"FROM " .
		"tax_codes as t " .
		"where is_delete = 0");
		$states = $Query->result();
		
		if (count($states) >= 1) {
			$Options = $states;
		}
		return $states;
	}
	
	public function ListShippingMethod()
	{
		$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 order by id asc");
		$result = $Query->result();
		return $result;
	}
	
	public function ShippingOptions($opt)
	{
		if ($opt == -1) {
			$this->session->set_userdata('ShippingOptions', array());
		} else {
			$Query = $this->db->query("SELECT * FROM shipping_method WHERE id = ? AND is_delete = 0", $opt);
			$ShippingOptions = $Query->result_array();
			if (count($ShippingOptions) >= 1) {
				$this->session->set_userdata('ShippingOptions', $ShippingOptions);
			} else {
				$this->session->set_userdata('ShippingOptions', array());
			}
		}
	}
	
	public function update_success()
	{
		// redirect('/admin/update_orders');
		$this->update_message = "1 records updated";
		// $this->_update($this->input->post('id'));
		$this->index();
	}
	
	public function success()
	{
		// $this->load->view('admin/orders');
		// redirect('/admin/success_orders');
		$this->update_message = "Order Saved Successfully";
		$this->index();
	}
	
	public function search()
	{
		$order_no      = $this->input->post('order_no');
		$release_date  = $this->input->post('release_date');
		$expiry_date   = $this->input->post('expiry_date');
		
		// $customer_name = $this->input->post('customer_name');
		
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		
		$order_status  = $this->input->post('order_status');
		
		$bill_address  = $this->input->post('bill_address');
		$ship_address  = $this->input->post('ship_address');
		$product_name  = $this->input->post('product_name');
		$sku           = $this->input->post('sku');
		$discount_code = $this->input->post('discount_code');
		$state         = $this->input->post('state');
		
		$this->db->distinct();
		$this->db->select(
			"o.id," .
			"DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
			"DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
			"DATE_FORMAT(o.order_date, '%T') as 'otime'"
		, FALSE);
		$this->db->from("`order` as o");
		$this->db->join('`order_list` as ol', 'o.id = ol.order_id', 'left');
		$this->db->join('`product` as p', 'p.id = ol.pid', 'left');
	
		if ($order_no != "") {
			$this->db->like('o.order_no', $order_no);
		}
		
		if ($order_status != 0) {
			$this->db->where('order_state', $order_status);
		}
		
		//	release_date & expiry_date
		
		if ($release_date != "" && $expiry_date != "") {
			$this->db->where('o.order_date >=', $release_date);
		}
		
		if ($expiry_date != "") {
			$this->db->where('o.order_date <=', $expiry_date);
		}
		
		if ($first_name != "") {
			$this->db->like('o.firstname', $first_name, 'after');
		}
		
		if ($last_name != "") {
			$this->db->like('o.lastname', $last_name, 'after');
		}
		
		if ($bill_address != "") {
			$this->db->like('o.bill_address', $bill_address, 'after');
			$this->db->or_like('o.bill_city', $bill_address, 'after');
			$this->db->or_like('o.bill_zipcode', $bill_address, 'after');
		}
		
		if ($ship_address != "") {
			$this->db->like('o.ship_address', $ship_address, 'after');
			$this->db->or_like('o.ship_city', $ship_address, 'after');
			$this->db->or_like('o.ship_zipcode', $ship_address, 'after');
		}
				
		if ($discount_code != "-1") {
			$this->db->where('o.discount_id', $discount_code);		
		}
		
		if ($state != "0") {
			$this->db->where('o.destination_id', $state);		
		}
		
		if ($product_name != "") {
			$this->db->like('p.name', $product_name, 'after');
		}
		
		if ($sku != "") {
			$this->db->where('p.sku', $sku);
		}
		
		if ($this->input->post('shipping_method') >= 1) {
			$this->db->where('shipping_id', $this->input->post('shipping_method'));
		}
		
		// $this->db->order_by('o.order_date', 'desc');
		
		$this->db->where('o.is_delete = 0');
		
		$result = $this->db->get()->result();
		
		$this->QUERY_STRING = $this->db->last_query();
		
		if (count($result) >= 1) {
			$ids = '';			
			foreach ($result as $obj) {
				$ids .= $obj->id . ",";
			}
			$ids = substr($ids, 0, strlen($ids) - 1);
			
			$search = $this->db->query("SELECT *, " . 
				"DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
				"DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
				"DATE_FORMAT(o.order_date, '%T') as 'otime'" .
				" FROM `order` as o WHERE id in(" . $ids . ") ORDER BY order_date desc");
			
			$this->orders = $search->result();

            $Query = $this->db->query("SELECT * FROM order_state ORDER BY id ASC");
            $this->order_status = $Query->result();
			
			$this->load->view("order_search_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}
		
	}
	
	public function search2()
	{
		if ($this->input->post('last_query') != "") {

			// echo $this->input->post('last_query');

			$result = $this->db->query($this->input->post('last_query'))->result();

			$this->QUERY_STRING = $this->db->last_query();

			if (count($result) >= 1) {
				$ids = '';			
				foreach ($result as $obj) {
					$ids .= $obj->id . ",";
				}
				$ids = substr($ids, 0, strlen($ids) - 1);

				// $search = $this->db->query("SELECT * FROM `order` WHERE id in(" . $ids . ") ORDER BY order_date desc");
				$search = $this->db->query("SELECT *, " . 
					"DATE_FORMAT(o.order_date, '%Y-%m-%d') as 'odate', " .
					"DATE_FORMAT(o.order_date, '%p') as 'oapm', " .
					"DATE_FORMAT(o.order_date, '%T') as 'otime'" .
					" FROM `order` as o WHERE id in(" . $ids . ") ORDER BY order_date desc");

				$this->orders = $search->result();

				$this->load->view("order_search_result");

			} else {
				$this->update_message = "There is no record based on your search";
				$this->index();
			}

		}
	}

	public function restore()
	{
		// echo $this->input->post('id');
		$Query = $this->db->query("SELECT pid, qty FROM `order_list` WHERE order_id = ?", $this->input->post('id'))->result();
		foreach ($Query as $item) {
			// echo $item->pid.br(1);
			// echo $item->qty.br(1);
			$this->db->query("UPDATE inventory SET in_stock = (in_stock + ?) WHERE pid = ?", array((int)$item->qty, (int)$item->pid));
		}
		
		$this->db->query("UPDATE `order` SET restore = 1 WHERE id = ?", $this->input->post('id'));
		$this->update_message = "1 records updated";
		$this->_update($this->input->post('id'));
	}

    public function addnote() {
        $this->db->insert('notes', array(
            'order_id'   => $this->input->post('order_id'),
            'text'       => $this->input->post('text'),
            'created_by' => $this->session->userdata('id')
        ));

        $this->output->set_status_header(200);
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode(array(
            'success'=> '1',
            'date'   => date('d-m-Y H:i:s'),
            'id'     => $this->db->insert_id(),
            'user'   => $this->session->userdata('username')
        )));
    }

    public function deletenote() {
        $id = $this->uri->segment(3);
        $this->db->delete('notes', array('id'=>$id));

        $this->output->set_status_header(200);
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode(array(
            'success'=>'1'
        )));
    }

    public function changestate() {
        $ids = str_replace(";", ",", $this->input->post('ids'));
        $state = $this->input->post('state');
		

        $this->db->query("UPDATE `order` SET order_state = {$state} WHERE id IN ({$ids})");

        if($state == 4) {
            $ids = explode(",", $ids);
            foreach ($ids as $id) {
                $this->db->insert('mail_queue', array('order_id'=>$id, 'for'=>'shipped_notification'));
			
            }
        }

        $this->output->set_status_header(200);
        $this->output->set_header('Content-type: application/json');
        $this->output->set_output(json_encode(array(
            'success'=>'1'
        )));
    }


}