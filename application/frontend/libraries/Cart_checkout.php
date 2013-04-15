<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* 
*/
class Cart_checkout
{	
	
	private $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->getCardExpiry();
	}
	
	public function get_cart_total_items()
	{
		$total_items = 0;
		foreach($this->CI->cart->contents() as $items) {
			$total_items += $items['qty'];
		}
		return $total_items;
	}
	
	//	檢查訂單中是否具備 Pre-order 的項目
	public function check_is_pre_order()
	{
		$bol_is_pre_order = FALSE;
		foreach ($this->CI->cart->contents() as $items) {
            if(key_exists("type", $items) &&
                ($items['type'] == 'voucher' ||
                 $items['type'] == 'buy_one_get_one'||
                 $items['type'] == 'free_gift')) continue;

			if ($this->CI->cart->product_options($items['rowid'])) {
                $bol_is_pre_order = TRUE;
			}
		}
		return $bol_is_pre_order;
	}
	
	//	設定信用卡年份
	public function getCardExpiry()
	{
		$this->CI->CardExpiryMonth = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

		$date = new DateTime();
		$date->modify('-2 year');
		$this->CI->CardExpiryYear = array();
		$begin_year = $date->format('Y');
		$date->modify('+12 year');
		$end_year   = $date->format('Y');
		do {
			array_push($this->CI->CardExpiryYear, $begin_year);
			$begin_year++;
		} while ($begin_year < $end_year);
	}
	
	//	計算 Pre-Order 的價錢與加上稅務
	public function split_order_price()
	{
		$pre_order_price = 0;
		$pre_order_tax   = 0;
		
		$ds = $this->CI->session->userdata('DestinationState');
		
		//	查詢是否有 Pre-order 的商品
		foreach ($this->CI->cart->contents() as $items) {
			if ($this->CI->cart->product_options($items['rowid'])) {
				$pre_order_price += ($this->CI->cart->format_number($items['price']) * $this->CI->cart->format_number($items['qty']));
			}
		}
		
		if ($ds[0]['tax_rate'] == 0) {
			$pre_order_tax = 0;
		} else {
			$pre_order_tax = round(($pre_order_price) * ($ds[0]['tax_rate'] / 100), 2);
		}
		
		$pre_order_price += $pre_order_tax;
		
		// return (float)number_format($pre_order_price, 2);
		// return (float)$pre_order_price;
		return round($pre_order_price, 2);
	}	
	
	//	計算 Pre-Order 的價錢 沒有含稅
	public function split_order_price_no_tax()
	{
		$pre_order_price = 0;
		
		$ds = $this->CI->session->userdata('DestinationState');
		
		//	查詢是否有 Pre-order 的商品
		foreach ($this->CI->cart->contents() as $items) {
			if ($this->CI->cart->product_options($items['rowid'])) {
				$pre_order_price += ($this->CI->cart->format_number($items['price']) * $this->CI->cart->format_number($items['qty']));
			}
		}
		
		// return (float)number_format($pre_order_price, 2);
		// return (float)$pre_order_price;
		return round($pre_order_price, 2);
		
	}
	
	//	訂單中需要請款的實際金額.
	public function get_capture_price()
	{
		$capture_price   = 0;
		$capture_tax     = 0;
		
		$ds = $this->CI->session->userdata('DestinationState');
		//	查詢是否有 Pre-order 的商品
		foreach ($this->CI->cart->contents() as $items) {
			if (!$this->CI->cart->product_options($items['rowid'])) {
				$capture_price += ($this->CI->cart->format_number($items['price']) * $this->CI->cart->format_number($items['qty']));
			}
		}
		
		//	檢查是否具備稅務金額
		if ($ds[0]['tax_rate'] == 0) {
			$capture_tax = 0;
		} else {
			$capture_tax = round(($capture_price) * ($ds[0]['tax_rate'] / 100), 2);
		}
		
		$capture_price += $capture_tax;
		
		// return (float)number_format($capture_price, 2);
		// return (float)$capture_price;
		
		return round($capture_price, 2);
		
	}
	
	public function get_capture_price_no_tax()
	{
		$capture_price   = 0;
				
		$ds = $this->CI->session->userdata('DestinationState');
		//	查詢是否有 Pre-order 的商品
		foreach ($this->CI->cart->contents() as $items) {
			if (!$this->CI->cart->product_options($items['rowid'])) {
				$capture_price += ($this->CI->cart->format_number($items['price']) * $this->CI->cart->format_number($items['qty']));
			}
		}
		
		// return (float)number_format($capture_price, 2);
		// return (float)$capture_price;
		return round($capture_price, 2);
	}
		
	public function get_customer()
	{
		$customer = (object)array();
        $customer->first_name  = $this->CI->input->post('firstname');
		$customer->last_name   = $this->CI->input->post('lastname');
		
        $customer->address     = $this->CI->input->post('bill_address');
        $customer->city        = $this->CI->input->post('bill_city');
        $customer->state       = $this->CI->input->post('bill_state');
        $customer->zip         = $this->CI->input->post('bill_zipcode');
        $customer->country     = "US";
        $customer->phone       = $this->CI->input->post('phone');
        $customer->email       = $this->CI->input->post('email');
		//	Guest Checkout 所以cust_id 設定為 0
        $customer->cust_id     = ($this->CI->session->userdata('user_id')) ? $this->CI->session->userdata('user_id') : 0;
        $customer->customer_ip = $this->CI->session->userdata('ip_address');
		return $customer;
	}
	
	public function get_shipping()
	{
		$ShippingOptions  = $this->CI->session->userdata('ShippingOptions');
		$DestinationState = $this->CI->session->userdata('DestinationState');
		$DiscountCode     = $this->CI->session->userdata('DiscountCode');
		$ProductTax       = $this->CI->session->userdata('ProductTax');
		
		$shipping_info = (object)array();
		$shipping_info->ship_to_first_name = $this->CI->input->post('ship_first_name');
        $shipping_info->ship_to_last_name  = $this->CI->input->post('ship_last_name');
        $shipping_info->ship_to_address    = $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2');
        $shipping_info->ship_to_city       = $this->CI->input->post('ship_city');
        $shipping_info->ship_to_state      = $DestinationState[0]['tax_code'];
        $shipping_info->ship_to_zip        = $this->CI->input->post('ship_state');
        $shipping_info->ship_to_country    = "US";
	
        $shipping_info->tax = $DestinationState[0]['tax_code'];
        $shipping_info->freight = "Freight<|>" . $ShippingOptions[0]['name'] . "<|>" . $ShippingOptions[0]['price'];
        $shipping_info->tax_exempt = ($ProductTax > 0) ? "true" : "false";
        // $shipping_info->po_num = "";
 		$shipping_info->tax = $ProductTax;

		return $shipping_info;
	}
	
	//	將 Authorize.net 回傳得資料寫入到 authorize_net_response
	public function write_to_authorize_reponse($response)
	{
		$auth_reponse = array(
			//	Authorize.NET respose code.
			'transaction_id'     => $response->transaction_id,
			'authorization_code' => $response->authorization_code,
			'avs_response'       => $response->avs_response,
			'invoice_number'     => $response->invoice_number,
			'method'             => $response->method,
			'transaction_type'   => $response->transaction_type,
			'card_code_response' => $response->card_code_response,
			'cavv_response'      => $response->cavv_response,
			'account_number'     => $response->account_number,
			'split_tender_id'    => $response->split_tender_id,
			'requested_amount'   => $response->requested_amount,
			'balance_on_card'    => $response->balance_on_card,
			'amount'             => $response->amount,
			'created_at'         => unix_to_human(time(), TRUE, 'us')
		);
				
		$this->CI->db->insert('authorize_net_response', $auth_reponse);
		
	}
	
	public function special_payment($invoice_num)
	{
		
		if($this->CI->session->userdata('order_is_added')){
			return true;
		}else {
				$this->CI->session->set_userdata('order_is_added', 'yes');
		}
		$ShippingOptions  = $this->CI->session->userdata('ShippingOptions');
		$DestinationState = $this->CI->session->userdata('DestinationState');
		$DiscountCode     = $this->CI->session->userdata('DiscountCode');
		$VoucherCode      = $this->CI->session->userdata('VoucherCode');
		
		 
		
		
		
		//$ORDER_DATETIME   = unix_to_human(time(), TRUE, 'us');
		$ORDER_DATETIME = date('Y-m-d H:i:s');
		$shippingSame     = $this->CI->input->post('shipSame');
		
		$ship_address = "";
		$ship_city    = "";
		$ship_state   = "";
		$ship_zipcode = "";
		
		$this->ORDER_DATETIME = $ORDER_DATETIME;
		$user_id = ((($this->CI->session->userdata('user_id'))) ? $this->CI->session->userdata('user_id') : 0);
		
		if ($shippingSame == 1) {
			$ship_address   = $this->CI->input->post('bill_address');
			$ship_city      = $this->CI->input->post('bill_city');
			$ship_state     = $this->CI->input->post('bill_state');
			$ship_zipcode   = $this->CI->input->post('bill_zipcode');
		} else {              
			$ship_address   = $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2');
			$ship_city      = $this->CI->input->post('ship_city');
			$ship_state     = $this->CI->input->post('ship_state');
			$ship_zipcode   = $this->CI->input->post('ship_zipcode');
		}
		
		//	有折扣條件下的訂單資訊
		if (is_array($DiscountCode)) {
			$Invoice = array(
				'order_no'     => $invoice_num,
				'order_date'   => $ORDER_DATETIME,
				'order_state'  => 2,
                'product_quantity' => count($this->CI->cart->contents()),
				'user_id'      => $user_id,
				'firstname'    => $this->CI->input->post('firstname'),
				'lastname'     => $this->CI->input->post('lastname'),
				'ship_first_name'=> $this->CI->input->post('ship_first_name'),
				'ship_last_name'=> $this->CI->input->post('ship_last_name'),
				'email'        => $this->CI->input->post('email'),
				'phone_number' => $this->CI->input->post('phone'),
				'bill_country' => $this->CI->input->post('bill_country'),
				'ship_county' => $this->CI->input->post('ship_country'),
				
				
				'bill_address'   => $this->CI->input->post('bill_address'),
				'bill_city'      => $this->CI->input->post('bill_city'),
				'bill_state'     => $this->CI->input->post('bill_state'),
				'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),

				'ship_first_name'=> $ship_first_name,
				'ship_last_name' => $ship_last_name,	
				'ship_address'   => $ship_address,
				'ship_city'      => $ship_city,
				'ship_state'     => $DestinationState[0]['id'],
				'ship_zipcode'   => $ship_zipcode,

				'payment_method'    => 3,

				'discount_id'        => $DiscountCode[0]->id,
				'discount_code'      => $DiscountCode[0]->code,
				'discount'           => $this->CI->session->userdata('Discount_Sub_Total'),

				'product_tax'        => $this->CI->session->userdata('ProductTax'),
				'calculate_shipping' => $this->CI->session->userdata('CalculateShipping'),

				'shipping_same'      => $shippingSame,

				'shipping_id'        => $ShippingOptions[0]['id'],
				'shipping_name'      => $ShippingOptions[0]['name'],
				'shipping_price'     => $ShippingOptions[0]['price'],
				'shipping_delivery'  => $ShippingOptions[0]['delivery'],
				// 'shipping_option'    => $this->session->userdata('ShippingOptions'),

				'promo_free_shipping'=> $this->CI->session->userdata('FreeShipping'),
				'freeshipping'       => $this->CI->session->userdata('FreeShipping2'),

				'destination_id'     => $DestinationState[0]['id'],
				'destination_state'  => $DestinationState[0]['tax_code'],
				'tax_rate'           => $DestinationState[0]['tax_rate'],
				// 'amount'             => $response->amount,
				'amount'			 => $this->CI->session->userdata('Amount'),
				'created_at'         => unix_to_human(time(), TRUE, 'us')
	 		);
	
			if ($shippingSame == 1) {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $shippingData = array(
					'ship_first_name'=> $this->CI->input->post('firstname'),
					'ship_last_name' => $this->CI->input->post('lastname'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $this->CI->input->post('bill_state'),
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $Invoice = array_merge($Invoice, $shippingData);

			} else {
			
				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
					'ship_city'      => $this->CI->input->post('ship_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('ship_zipcode')
				);
							
			}
	
			//	減去 Discount xuses
			// $this->CI->db->query("UPDATE discountcode SET xuses = xuses - 1 WHERE id = ?", $DiscountCode[0]->id);
	
		} else {
			
			$Invoice = array(
				'order_no'     => $invoice_num,
				'order_date'   => $ORDER_DATETIME,
				'order_state'  => 2,
                'product_quantity' => count($this->CI->cart->contents()),

				'user_id'      => $user_id,
				'firstname'    => $this->CI->input->post('firstname'),
				'lastname'     => $this->CI->input->post('lastname'),
				'ship_first_name'=> $this->CI->input->post('ship_first_name'),
				'ship_last_name'=> $this->CI->input->post('ship_last_name'),
				'email'        => $this->CI->input->post('email'),
				'phone_number' => $this->CI->input->post('phone'),
				'bill_country' => $this->CI->input->post('bill_country'),
				'ship_county' => $this->CI->input->post('ship_country'),

				'bill_address'   => $this->CI->input->post('bill_address'),
				'bill_city'      => $this->CI->input->post('bill_city'),
				'bill_state'     => $this->CI->input->post('bill_state'),
				'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
				
				'ship_address'   => $ship_address,
				'ship_city'      => $ship_city,
				'ship_state'     => $DestinationState[0]['id'],
				'ship_zipcode'   => $ship_zipcode,

				'payment_method'    => 3,
				// 'name_on_card'      => $this->CI->input->post('name_on_card'),
				// 'card_type'         => $response->card_type,
				// 'card_number'       => $this->CI->input->post('card_number'),
				// 'card_expiry_month' => $this->CI->input->post('CardExpiryMonth'),
				// 'card_expiry_year'  => $this->CI->input->post('CardExpiryYear'),
				// 'ccv_number'        => $this->CI->input->post('ccv'),

				// 'discount_id'        => $DiscountCode[0]->id,
				// 'discount_code'      => $DiscountCode[0]->code,
				'discount'           => $this->CI->session->userdata('Discount_Sub_Total'),

				'product_tax'        => $this->CI->session->userdata('ProductTax'),
				'calculate_shipping' => $this->CI->session->userdata('CalculateShipping'),

				'shipping_same'      => $shippingSame,

				'shipping_id'        => $ShippingOptions[0]['id'],
				'shipping_name'      => $ShippingOptions[0]['name'],
				'shipping_price'     => $ShippingOptions[0]['price'],
				'shipping_delivery'  => $ShippingOptions[0]['delivery'],
				// 'shipping_option'    => $this->session->userdata('ShippingOptions'),

				'promo_free_shipping'=> $this->CI->session->userdata('FreeShipping'),
				'freeshipping'       => $this->CI->session->userdata('FreeShipping2'),

				'destination_id'     => $DestinationState[0]['id'],
				'destination_state'  => $DestinationState[0]['tax_code'],
				'tax_rate'           => $DestinationState[0]['tax_rate'],
				// 'amount'             => $response->amount,
				'amount'			 => $this->CI->session->userdata('Amount'),
				'created_at'         => unix_to_human(time(), TRUE, 'us')
	 		);
	
			if ($shippingSame == 1) {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $shippingData = array(
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $this->CI->input->post('bill_state'),
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $Invoice = array_merge($Invoice, $shippingData);

			} else {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_first_name'=> $this->CI->input->post('ship_first_name'),
					'ship_last_name' => $this->CI->input->post('ship_last_name'),	
					'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
					'ship_city'      => $this->CI->input->post('ship_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('ship_zipcode')
				);
			
			}
	
		}		

		//print_r($Invoice);

		$this->CI->db->trans_start();

		//	寫入訂單.
		$this->CI->db->insert('order', $Invoice);

		//	取得新加入的訂單 ID
		$order_id = $this->CI->db->insert_id();
		
		if(is_array($VoucherCode)) {
		
          /*  $VoucherSubTotal = $this->CI->session->userdata('Voucher_Sub_Total');
            $OrderRelVoucher = array(
                'order_id' => $order_id,
                'voucher_id' => $VoucherCode[0]->id,
                'amount' => $VoucherSubTotal,
                'created_at' => unix_to_human(time(), TRUE, 'us')
            );

            //$this->CI->db->insert('order_rel_voucher', $OrderRelVoucher);
            $remainingBalance = number_format($VoucherCode[0]->balance - $VoucherSubTotal, 0);
            $VoucherDetails = array('balance' => $remainingBalance);
            $this->CI->db->where('id', $VoucherCode[0]->id);
            $this->CI->db->update('order_voucher_details', $VoucherDetails);*/

        }
		

		//	新增 Order List
		foreach($this->CI->cart->contents() as $items) {
			
		$type_db = 'product';
		
		
		if(isset($items['type']) && $items['type']!="") {
			$type_db = $items['type'];
		}
			
			$OrderList = array(
				'order_id'   => $order_id,
				'invoice_number' => $invoice_num,
				'pid'        => $items['id'],
				'price'      => $items['price'],
				'p_name'      => $items['name'],
				'item_type'      => $type_db,
				'qty'        => $items['qty'],
				'created_at' => unix_to_human(time(), TRUE, 'us')
			);
			
			if ($this->CI->cart->has_options($items['rowid'])) {
			
				$this->CI->db->query("UPDATE product set in_stock = (in_stock - ?) WHERE id = ?", array($items['qty'], $items['id']));
				log_message('DEBUG', $this->CI->db->last_query());
							
				$CurrStock = $this->CI->db->query("SELECT in_stock FROM product WHERE id = ?", $items['id'])->result();
			
				if (count($CurrStock) >= 1) {
					if ($CurrStock[0]->in_stock <= 0) {
						$this->CI->db->query("UPDATE product set in_stock = 0 WHERE id = ?", $items['id']);
						log_message('DEBUG', $this->CI->db->last_query());
					}
				}
			
			} else {

				$this->CI->db->query("UPDATE product set in_stock = (in_stock - ?) WHERE id = ?", array($items['qty'], $items['id']));
				log_message('DEBUG', $this->CI->db->last_query());
			
			}
			
			$this->CI->db->insert('order_list', $OrderList);
		}

		if ($this->CI->db->trans_status() === FALSE) {
			$this->CI->db->trans_rollback();
			log_message('DEBUG', 'Checkout->save() 交易錯誤');
		}

		if ($this->CI->session->userdata('user_id')) {
			$this->CI->db->where('id', $this->CI->session->userdata('user_id'));
			$this->CI->db->update('users', $user_data);
		}

		$this->CI->db->trans_complete();
		
	}
	
	public function add2Invoice_paypal($invoice_num)
	{
	
		if($this->CI->session->userdata('order_is_added')){
			return true;
		}else {
				$this->CI->session->set_userdata('order_is_added', 'yes');
		}
		
		$ShippingOptions  = $this->CI->session->userdata('ShippingOptions');
		$DestinationState = $this->CI->session->userdata('DestinationState');
		$DiscountCode     = $this->CI->session->userdata('DiscountCode');
		
		//$ORDER_DATETIME = unix_to_human(time(), TRUE, 'us');
		$ORDER_DATETIME = date('Y-m-d H:i:s');
		$shippingSame = $this->CI->input->post('shipSame');
		
		$this->ORDER_DATETIME = $ORDER_DATETIME;

		$user_id = ((($this->CI->session->userdata('user_id'))) ? $this->CI->session->userdata('user_id') : 0);
		
		$ship_address = "";
		$ship_city    = "";
		$ship_state   = "";
		$ship_zipcode = "";
		
		if ($shippingSame == 1) {
			$ship_address   = $this->CI->input->post('bill_address');
			$ship_city      = $this->CI->input->post('bill_city');
			$ship_state     = $this->CI->input->post('bill_state');
			$ship_zipcode   = $this->CI->input->post('bill_zipcode');
		} else {              
			$ship_address   = $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2');
			$ship_city      = $this->CI->input->post('ship_city');
			$ship_state     = $this->CI->input->post('ship_state');
			$ship_zipcode   = $this->CI->input->post('ship_zipcode');
		}
		
		//	有折扣條件下的訂單資訊
		if (is_array($DiscountCode)) {
			$Invoice = array(
				'order_no'     => $invoice_num,
				'order_date'   => $ORDER_DATETIME,
				'order_state'  => 5,
                'product_quantity' => count($this->CI->cart->contents()),
				'user_id'      => $user_id,
				'firstname'    => $this->CI->input->post('firstname'),
				'lastname'     => $this->CI->input->post('lastname'),
				'email'        => $this->CI->input->post('email'),
				'phone_number' => $this->CI->input->post('phone'),
				'bill_country' => $this->CI->input->post('bill_country'),
				'ship_county' => $this->CI->input->post('ship_country'),
				
				'bill_address'   => $this->CI->input->post('bill_address'),
				'bill_city'      => $this->CI->input->post('bill_city'),
				'bill_state'     => $this->CI->input->post('bill_state'),
				'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
				'ship_first_name'=> $this->CI->input->post('ship_first_name'),
				'ship_last_name'=> $this->CI->input->post('ship_last_name'),
				'ship_address'   => $ship_address,
				'ship_city'      => $ship_city,
				// 'ship_state'     => $this->CI->input->post('ship_state'),
				'ship_state'     => $DestinationState[0]['id'],
				'ship_zipcode'   => $ship_zipcode,

				'payment_method'    => $this->CI->input->post('payment_method'),
				// 'name_on_card'      => $this->CI->input->post('name_on_card'),
				// 'card_type'         => $response->card_type,
				// 'card_number'       => $this->CI->input->post('card_number'),
				// 'card_expiry_month' => $this->CI->input->post('CardExpiryMonth'),
				// 'card_expiry_year'  => $this->CI->input->post('CardExpiryYear'),
				// 'ccv_number'        => $this->CI->input->post('ccv'),

				'discount_id'        => $DiscountCode[0]->id,
				'discount_code'      => $DiscountCode[0]->code,
				'discount'           => $this->CI->session->userdata('Discount_Sub_Total'),

				'product_tax'        => $this->CI->session->userdata('ProductTax'),
				'calculate_shipping' => $this->CI->session->userdata('CalculateShipping'),

				'shipping_same'      => $shippingSame,

				'shipping_id'        => $ShippingOptions[0]['id'],
				'shipping_name'      => $ShippingOptions[0]['name'],
				'shipping_price'     => $ShippingOptions[0]['price'],
				'shipping_delivery'  => $ShippingOptions[0]['delivery'],
				// 'shipping_option'    => $this->session->userdata('ShippingOptions'),

				'promo_free_shipping'=> $this->CI->session->userdata('FreeShipping'),
				'freeshipping'       => $this->CI->session->userdata('FreeShipping2'),

				'destination_id'     => $DestinationState[0]['id'],
				'destination_state'  => $DestinationState[0]['tax_code'],
				'tax_rate'           => $DestinationState[0]['tax_rate'],
				// 'amount'             => $response->amount,
				'amount'			 => $this->CI->session->userdata('Amount'),
				'created_at'         => unix_to_human(time(), TRUE, 'us')
	 		);
	
			if ($shippingSame == 1) {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $shippingData = array(
					'ship_first_name'=> $this->CI->input->post('firstname'),
					'ship_last_name' => $this->CI->input->post('lastname'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $this->CI->input->post('bill_state'),
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $Invoice = array_merge($Invoice, $shippingData);

			} else {
			
				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
					'ship_city'      => $this->CI->input->post('ship_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('ship_zipcode')
				);
							
			}
	
			//	減去 Discount xuses
			// $this->CI->db->query("UPDATE discountcode SET xuses = xuses - 1 WHERE id = ?", $DiscountCode[0]->id);
	
		} else {
			$Invoice = array(
				'order_no'     => $invoice_num,
				'order_date'   => $ORDER_DATETIME,
				'order_state'  => 5,
                'product_quantity' => count($this->CI->cart->contents()),

				'user_id'      => $user_id,
				'firstname'    => $this->CI->input->post('firstname'),
				'lastname'     => $this->CI->input->post('lastname'),
				'email'        => $this->CI->input->post('email'),
				'phone_number' => $this->CI->input->post('phone'),
				'bill_country' => $this->CI->input->post('bill_country'),
				'ship_county' => $this->CI->input->post('ship_country'),

				'bill_address'   => $this->CI->input->post('bill_address'),
				'bill_city'      => $this->CI->input->post('bill_city'),
				'bill_state'     => $this->CI->input->post('bill_state'),
				'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
				'ship_first_name'=> $this->CI->input->post('ship_first_name'),
				'ship_last_name'=> $this->CI->input->post('ship_last_name'),
				'ship_address'   => $ship_address,
				'ship_city'      => $ship_city,
				// 'ship_state'     => $this->CI->input->post('ship_state'),
				'ship_state'     => $DestinationState[0]['id'],
				'ship_zipcode'   => $ship_zipcode,

				'payment_method'    => $this->CI->input->post('payment_method'),
				// 'name_on_card'      => $this->CI->input->post('name_on_card'),
				// 'card_type'         => $response->card_type,
				// 'card_number'       => $this->CI->input->post('card_number'),
				// 'card_expiry_month' => $this->CI->input->post('CardExpiryMonth'),
				// 'card_expiry_year'  => $this->CI->input->post('CardExpiryYear'),
				// 'ccv_number'        => $this->CI->input->post('ccv'),

				// 'discount_id'        => $DiscountCode[0]->id,
				// 'discount_code'      => $DiscountCode[0]->code,
				'discount'           => $this->CI->session->userdata('Discount_Sub_Total'),

				'product_tax'        => $this->CI->session->userdata('ProductTax'),
				'calculate_shipping' => $this->CI->session->userdata('CalculateShipping'),

				'shipping_same'      => $shippingSame,

				'shipping_id'        => $ShippingOptions[0]['id'],
				'shipping_name'      => $ShippingOptions[0]['name'],
				'shipping_price'     => $ShippingOptions[0]['price'],
				'shipping_delivery'  => $ShippingOptions[0]['delivery'],
				// 'shipping_option'    => $this->session->userdata('ShippingOptions'),

				'promo_free_shipping'=> $this->CI->session->userdata('FreeShipping'),
				'freeshipping'       => $this->CI->session->userdata('FreeShipping2'),

				'destination_id'     => $DestinationState[0]['id'],
				'destination_state'  => $DestinationState[0]['tax_code'],
				'tax_rate'           => $DestinationState[0]['tax_rate'],
				// 'amount'             => $response->amount,
				'amount'			 => $this->CI->session->userdata('Amount'),
				'created_at'         => unix_to_human(time(), TRUE, 'us')
	 		);
	
			// print_r($Invoice);
	
			if ($shippingSame == 1) {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $shippingData = array(
					'ship_first_name'=> $this->CI->input->post('firstname'),
					'ship_last_name' => $this->CI->input->post('lastname'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $this->CI->input->post('bill_state'),
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $Invoice = array_merge($Invoice, $shippingData);

			} else {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'ship_first_name'=> $this->CI->input->post('ship_first_name'),
					'ship_last_name' => $this->CI->input->post('ship_last_name'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
					'ship_city'      => $this->CI->input->post('ship_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('ship_zipcode')
				);
			
			}
	
		}		

		$this->CI->db->trans_start();

		// //	寫入訂單.
		// $this->CI->db->insert('order', $Invoice);
		// 
		// //	取得新加入的訂單 ID
		// $order_id = $this->CI->db->insert_id();

		//	寫入訂單.
		$this->CI->db->where('order_no', $invoice_num);
		$this->CI->db->update('order', $Invoice);
		
		//	寫入訂單.
		// $this->CI->db->insert('order', $Invoice);
		
		//	取得新加入的訂單 ID
		
		$result = $this->CI->db->query("SELECT id FROM `order` WHERE order_no = ?", $invoice_num);
		$order_id = $result->row()->id;

		//	新增 Order List
		foreach($this->CI->cart->contents() as $items) {
		
		$type_db = 'product';
		
		if(isset($items['type']) && $items['type']!="") {
			$type_db = $items['type'];
		}
		
            $OrderList = array(
                'order_id'   => $order_id,
                'invoice_number' => $invoice_num,
                'pid'        => $items['id'],
                'price'      => $items['price'],
				'p_name'      => $items['name'],
				'item_type'      => $type_db,
                'qty'        => $items['qty'],
                'created_at' => unix_to_human(time(), TRUE, 'us')
            );
            if($items['type'] == 'voucher') {
                $OrderList['item_type'] = 'voucher';
                $this->CI->item = $items;
                //$this->CI->session->set_userdata('email_item', $items);
                $Mailer = new Mailer();
                $Mailer->send_voucher($items['options']['Recipient\'s Email']);

                $OrderVoucherDetails = array(
                    'order_id'   => $order_id,
                    'invoice_number' => $invoice_num,
                    'voucher_id' => $items['id'],
                    'qty' => $items['qty'],
                    'price' => $items['price'],
                    'balance' => $items['price'],
                    'title' => $items['name'],
                    'to'=>$items['options']['To'],
                    'from'=>$items['options']['From'],
                    'recipient_email'=>$items['options']['Recipient\'s Email'],
                    'message'=>$items['options']['Message'],
                    'created_at' => unix_to_human(time(), TRUE, 'us')
                );

                $this->CI->db->insert('order_voucher_details', $OrderVoucherDetails);

                $OrderList['pid'] = $this->CI->db->insert_id();
                $OrderList['item_type'] = 'voucher';
            }



			if ($this->CI->cart->has_options($items['rowid'])) {
			
				$this->CI->db->query("UPDATE product set in_stock = (in_stock - ?) WHERE id = ?", array($items['qty'], $items['id']));
				log_message('DEBUG', $this->CI->db->last_query());
							
				$CurrStock = $this->CI->db->query("SELECT in_stock FROM product WHERE id = ?", $items['id'])->result();
			
				if (count($CurrStock) >= 1) {
					if ($CurrStock[0]->in_stock <= 0) {
						$this->CI->db->query("UPDATE product set in_stock = 0 WHERE id = ?", $items['id']);
						log_message('DEBUG', $this->CI->db->last_query());
					}
				}
			
			} else {

				$this->CI->db->query("UPDATE product set in_stock = (in_stock - ?) WHERE id = ?", array($items['qty'], $items['id']));
				log_message('DEBUG', $this->CI->db->last_query());
			
			}
			
			$this->CI->db->insert('order_list', $OrderList);
		}

		if ($this->CI->db->trans_status() === FALSE) {
			$this->CI->db->trans_rollback();
			log_message('DEBUG', 'Checkout->save() 交易錯誤');
		}

		if ($this->CI->session->userdata('user_id')) {
			$this->CI->db->where('id', $this->CI->session->userdata('user_id'));
			$this->CI->db->update('users', $user_data);
		}

		$this->CI->db->trans_complete();
			
	}
		
	public function add2Invoice($response)
	{
	
		if($this->CI->session->userdata('order_is_added')){
			return true;
		}else {
				$this->CI->session->set_userdata('order_is_added', 'yes');
		}
		
		// $this->CI->db->trans_start();
		$ShippingOptions  = $this->CI->session->userdata('ShippingOptions');
		$DestinationState = $this->CI->session->userdata('DestinationState');
		$DiscountCode     = $this->CI->session->userdata('DiscountCode');
        $VoucherCode      = $this->CI->session->userdata('VoucherCode');
		
		//$ORDER_DATETIME = unix_to_human(time(), TRUE, 'us');
		$ORDER_DATETIME = date('Y-m-d H:i:s');
		$shippingSame = $this->CI->input->post('shipSame');
		
		$this->ORDER_DATETIME = $ORDER_DATETIME;
		
		$user_id = ((($this->CI->session->userdata('user_id'))) ? $this->CI->session->userdata('user_id') : 0);
		
		$user_data = array();
		
		//	有折扣條件下的訂單資訊
		if (is_array($DiscountCode)) {
			$Invoice = array(
				
				'order_no'     => $response->invoice_number,
				'order_date'   => $ORDER_DATETIME,
				'order_state'  => 2,
                'product_quantity' => count($this->CI->cart->contents()),

				'user_id'      => $user_id,
			
				'firstname'    => $this->CI->input->post('firstname'),
				'lastname'     => $this->CI->input->post('lastname'),
				'email'        => $this->CI->input->post('email'),
				'phone_number' => $this->CI->input->post('phone'),
				'bill_country' => $this->CI->input->post('bill_country'),
				'ship_county' => $this->CI->input->post('ship_country'),

				'ship_first_name'=> $this->CI->input->post('ship_first_name'),
				'ship_last_name'=> $this->CI->input->post('ship_last_name'),		
				'bill_address'   => $this->CI->input->post('bill_address'),
				'bill_city'      => $this->CI->input->post('bill_city'),
				'bill_state'     => $this->CI->input->post('bill_state'),
				'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),

				'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
				'ship_city'      => $this->CI->input->post('ship_city'),
				// 'ship_state'     => $this->CI->input->post('ship_state'),
				'ship_state'     => $DestinationState[0]['id'],
				'ship_zipcode'   => $this->CI->input->post('ship_zipcode'),
				
				'use_encryption'      => 'Y',
				'payment_method'    => $this->CI->input->post('payment_method'),
				'name_on_card'      => base64_encode($this->CI->input->post('name_on_card')),
				'card_type'         => base64_encode($response->card_type),
				'card_number'       => base64_encode($this->CI->input->post('card_number')),
				'card_expiry_month' => base64_encode($this->CI->input->post('CardExpiryMonth')),
				'card_expiry_year'  => base64_encode($this->CI->input->post('CardExpiryYear')),
				'ccv_number'        => base64_encode($this->CI->input->post('ccv')),

				'discount_id'        => $DiscountCode[0]->id,
				'discount_code'      => $DiscountCode[0]->code,
				'discount'           => $this->CI->session->userdata('Discount_Sub_Total'),

				'product_tax'        => $this->CI->session->userdata('ProductTax'),
				'calculate_shipping' => $this->CI->session->userdata('CalculateShipping'),

				'shipping_same'      => $shippingSame,
				
				'shipping_id'        => $ShippingOptions[0]['id'],
				'shipping_name'      => $ShippingOptions[0]['name'],
				'shipping_price'     => $ShippingOptions[0]['price'],
				'shipping_delivery'  => $ShippingOptions[0]['delivery'],
				// 'shipping_option'    => $this->session->userdata('ShippingOptions'),

				'promo_free_shipping'=> $this->CI->session->userdata('FreeShipping'),
				'freeshipping'       => $this->CI->session->userdata('FreeShipping2'),

				'destination_id'     => $DestinationState[0]['id'],
				'destination_state'  => $DestinationState[0]['tax_code'],
				'tax_rate'           => $DestinationState[0]['tax_rate'],
				// 'amount'             => $response->amount,
				'amount'			 => $this->CI->session->userdata('Amount'),
				'created_at'         => unix_to_human(time(), TRUE, 'us')
	 		);
			
			if ($shippingSame == 1) {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $shippingData = array(
					'ship_first_name'=> $this->CI->input->post('firstname'),
					'ship_last_name' => $this->CI->input->post('lastname'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $this->CI->input->post('bill_state'),
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $Invoice = array_merge($Invoice, $shippingData);

			} else {
				
				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('ship_address'),
					'ship_city'      => $this->CI->input->post('ship_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('ship_zipcode')
				);
								
			}
			
			//	減去 Discount xuses
			// $this->CI->db->query("UPDATE discountcode SET xuses = xuses - 1 WHERE id = ?", $DiscountCode[0]->id);
			
	
		} else {
			$Invoice = array(
				'order_no'     => $response->invoice_number,
				'order_date'   => $ORDER_DATETIME,
				'order_state'  => 2,
                'product_quantity' => count($this->CI->cart->contents()),

				'user_id'      => $user_id,
				'firstname'    => $this->CI->input->post('firstname'),
				'lastname'     => $this->CI->input->post('lastname'),
				'email'        => $this->CI->input->post('email'),
				'phone_number' => $this->CI->input->post('phone'),
				'bill_country' => $this->CI->input->post('bill_country'),
				'ship_county' => $this->CI->input->post('ship_country'),

				'bill_address'   => $this->CI->input->post('bill_address'),
				'bill_city'      => $this->CI->input->post('bill_city'),
				'bill_state'     => $this->CI->input->post('bill_state'),
				'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
				'ship_first_name'=> $this->CI->input->post('ship_first_name'),
				'ship_last_name'=> $this->CI->input->post('ship_last_name'),
				'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
				'ship_city'      => $this->CI->input->post('ship_city'),
				// 'ship_state'     => $this->CI->input->post('ship_state'),
				'ship_state'     => $DestinationState[0]['id'],
				'ship_zipcode'   => $this->CI->input->post('ship_zipcode'),

				'payment_method'    => $this->CI->input->post('payment_method'),
				
				'use_encryption'      => 'Y',
				'name_on_card'      => base64_encode($this->CI->input->post('name_on_card')),
				'card_type'         => base64_encode($response->card_type),
				'card_number'       => base64_encode($this->CI->input->post('card_number')),
				'card_expiry_month' => base64_encode($this->CI->input->post('CardExpiryMonth')),
				'card_expiry_year'  => base64_encode($this->CI->input->post('CardExpiryYear')),
				'ccv_number'        => base64_encode($this->CI->input->post('ccv')),
				
				

				// 'discount_id'        => $DiscountCode[0]->id,
				// 'discount_code'      => $DiscountCode[0]->code,
				'discount'           => $this->CI->session->userdata('Discount_Sub_Total'),

				'product_tax'        => $this->CI->session->userdata('ProductTax'),
				'calculate_shipping' => $this->CI->session->userdata('CalculateShipping'),
				
				'shipping_same'      => $shippingSame,
				
				'shipping_id'        => $ShippingOptions[0]['id'],
				'shipping_name'      => $ShippingOptions[0]['name'],
				'shipping_price'     => $ShippingOptions[0]['price'],
				'shipping_delivery'  => $ShippingOptions[0]['delivery'],
				// 'shipping_option'    => $this->session->userdata('ShippingOptions'),

				'promo_free_shipping'=> $this->CI->session->userdata('FreeShipping'),
				'freeshipping'       => $this->CI->session->userdata('FreeShipping2'),

				'destination_id'     => $DestinationState[0]['id'],
				'destination_state'  => $DestinationState[0]['tax_code'],
				'tax_rate'           => $DestinationState[0]['tax_rate'],
				// 'amount'             => $response->amount,
				'amount'			 => $this->CI->session->userdata('Amount'),
				'created_at'         => unix_to_human(time(), TRUE, 'us')
	 		);
	
			
			if ($shippingSame == 1) {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $shippingData = array(
					'ship_first_name'=> $this->CI->input->post('firstname'),
					'ship_last_name' => $this->CI->input->post('lastname'),
					'ship_address'   => $this->CI->input->post('bill_address'),
					'ship_city'      => $this->CI->input->post('bill_city'),
					'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $this->CI->input->post('bill_state'),
					'ship_zipcode'   => $this->CI->input->post('bill_zipcode')
				);

                $Invoice = array_merge($Invoice, $shippingData);

			} else {

				$user_data = array(
					'phone'          => $this->CI->input->post('phone'),
					'bill_address'   => $this->CI->input->post('bill_address'),
					'bill_city'      => $this->CI->input->post('bill_city'),
					'bill_state'     => $this->CI->input->post('bill_state'),
					'bill_zipcode'   => $this->CI->input->post('bill_zipcode'),
					'ship_address'   => $this->CI->input->post('ship_address')." ".$this->CI->input->post('ship_address2'),
					'ship_city'      => $this->CI->input->post('ship_city'),
					// 'ship_state'     => $this->CI->input->post('ship_state'),
					'ship_state'     => $DestinationState[0]['id'],
					'ship_zipcode'   => $this->CI->input->post('ship_zipcode')
				);
				
			}
			
	
		}

		// $this->CI->db->trans_start();
		
		//	寫入訂單.
		$this->CI->db->where('order_no', $response->invoice_number);
		$this->CI->db->update('order', $Invoice);


        //	寫入訂單.
		// $this->CI->db->insert('order', $Invoice);
		
		//	取得新加入的訂單 ID
		
		$result = $this->CI->db->query("SELECT id FROM `order` WHERE order_no = ?", $response->invoice_number);
		$order_id = $result->row()->id;
		// $order_id = $this->CI->db->insert_id();
		
		// 
		// echo $order_id;
		// exit;

        if(is_array($VoucherCode)) {
		
		
           /* $VoucherSubTotal = $this->CI->session->userdata('Voucher_Sub_Total');
            $OrderRelVoucher = array(
                'order_id' => $order_id,
                'voucher_id' => $VoucherCode[0]->id,
                'amount' => $VoucherSubTotal,
                'created_at' => unix_to_human(time(), TRUE, 'us')
            );

            $this->CI->db->insert('order_rel_voucher', $OrderRelVoucher);
            $remainingBalance = number_format($VoucherCode[0]->balance - $VoucherSubTotal, 0);
            $VoucherDetails = array('balance' => $remainingBalance);
            $this->CI->db->where('id', $VoucherCode[0]->id);
            $this->CI->db->update('order_voucher_details', $VoucherDetails);*/

        }
		
		//	新增 Order List
		foreach($this->CI->cart->contents() as $items) {
		
		$type_db = 'product';
		if(isset($items['type']) && $items['type']!="") {
			$type_db = $items['type'];
		 }
		
		
            $OrderList = array(
                'order_id'   => $order_id,
                'invoice_number' => $response->invoice_number,
                'pid'        => $items['id'],
				'item_type'        => $type_db,
				'p_name'        => $items['name'],
                'price'      => $items['price'],
                'qty'        => $items['qty'],
                'created_at' => unix_to_human(time(), TRUE, 'us')
            );
			
			if(key_exists("type", $items) && $items['type'] == 'voucher') {
                $OrderList['item_type'] = 'voucher';

                $Mailer = new Mailer();
                $this->CI->item = $items;
                $Mailer->send_voucher($items['options']['Recipient\'s Email']);

                $OrderVoucherDetails = array(
                    'order_id'   => $order_id,
                    'invoice_number' => $response->invoice_number,
                    'voucher_id' => $items['id'],
                    'code' => $items['code'],
                    'qty' => $items['qty'],
                    'price' => $items['price'],
                    'balance' => $items['price'],
                    'title' => $items['name'],
                    'to'=>$items['options']['To'],
                    'from'=>$items['options']['From'],
                    'recipient_email'=>$items['options']['Recipient\'s Email'],
                    'message'=>$items['options']['Message'],
                    'created_at' => unix_to_human(time(), TRUE, 'us')
                );

                $this->CI->db->insert('order_voucher_details', $OrderVoucherDetails);

                $OrderList['pid'] = $this->CI->db->insert_id();
                $OrderList['item_type'] = 'voucher';
            } elseif(key_exists("type", $items)){
                $OrderList['item_type'] = $items['type'];
            }
			
			$this->CI->db->insert('order_list', $OrderList);
			
			if ($this->CI->cart->has_options($items['rowid'])) {
				
				$this->CI->db->query("UPDATE product set in_stock = (in_stock - ?) WHERE id = ?", array($items['qty'], $items['id']));
				log_message('DEBUG', $this->CI->db->last_query());
								
				$CurrStock = $this->CI->db->query("SELECT in_stock FROM product WHERE id = ?", $items['id'])->result();
				
				if (count($CurrStock) >= 1) {
					if ($CurrStock[0]->in_stock <= 0) {
						$this->CI->db->query("UPDATE product set in_stock = 0 WHERE id = ?", $items['id']);
						log_message('DEBUG', $this->CI->db->last_query());
					}
				}
				
			} else {
				//	這好像會有問題
				$this->CI->db->query("UPDATE product set in_stock = (in_stock - ?) WHERE id = ?", array($items['qty'], $items['id']));
				log_message('DEBUG', $this->CI->db->last_query());
				
			}
			
		}

		if ($this->CI->session->userdata('user_id')) {
			$this->CI->db->where('id', $this->CI->session->userdata('user_id'));
			$this->CI->db->update('users', $user_data);
		}
		
		// if ($this->CI->db->trans_status() === FALSE) {
		// 	$this->CI->db->trans_rollback();
		// 	log_message('error', 'Checkout->save() 交易錯誤');
		// }
		// 
		// $this->CI->db->trans_complete();
	}
	
}