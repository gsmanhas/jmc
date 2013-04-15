<?php

/**
* 
*/
class Membercheckout extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
		
		//	運費的方式
		$sp = $this->session->userdata('ShippingOptions');
		//	送達的目的地 (主要是判別是否有含稅物)
		$ds = $this->session->userdata('DestinationState');
		
		if ($this->cart->total_items() <= 0) {
			redirect('viewcart', 'refresh');
		}
		
		if (count($sp) <= 0 || count($ds) <= 0) {
			redirect('viewcart', 'refresh');
		}
		
		$this->AIM = new AuthorizeNetAIM(
			$this->config->item('at_login'), $this->config->item('at_password')
		);
		
		//	這個函數決定, 是 Test Mode 或是 正式交易, false = 正式交易, true = 測試模式
		//$this->AIM->setSandbox(false);
		 $this->AIM->setSandbox(true);
		
		// print_r($this->AIM);
		
		// 初始化 Checkout 物件
		$this->CartCheckout = new Cart_checkout();
		
	}
	
	public function _remap()
	{		
		switch ($this->uri->segment(2, 0)) {

			case "0" :
				$this->index();
			break;

			case "submit" :
				$this->_submit();
			break;
			
			// case "paypal_return" :
			// 	$this->paypal_return();
			// break;
						
			default:
				redirect('page-not-found', 'refresh');
			break;
		}
	}
	
	public function index()
	{
		$query = $this->db->query('SELECT * FROM state order by state asc');
		$this->continental = $query->result();
		
		$this->load->view('membercheckout');
	}

	// public function paypal_return()
	// {
	// 	echo "QQ";
	// }

	private function _submit_validate() {
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'required|max_length[20]');
		
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		
		if ($this->input->post('shipSame') != 1) {
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');			
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');
		}
		
		//	Using Authorize.Net
		if ($this->input->post('payment_method') == 1 && $this->session->userdata('Amount') != 0) {
			$this->form_validation->set_rules('name_on_card', 'Name of Card', 'required');
			//$this->form_validation->set_rules('card_number', 'Card Number', 'required|min_length[13]|max_length[24]|numeric');
			$this->form_validation->set_rules('card_number', 'Card Number', 'required|min_length[13]|max_length[24]');
			$this->form_validation->set_rules('card_type', 'Card Type', 'required');
			$this->form_validation->set_rules('ccv', 'CCV Number', 'required|numeric|min_length[3]');
		}
		
		$this->form_validation->set_message('required', 'required');
		
		return $this->form_validation->run();
	}
	
	private function _submit_special_validate() {
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'required|max_length[20]');
		
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		
		if ($this->input->post('shipSame') != 1) {
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');			
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');
		}
				
		$this->form_validation->set_message('required', 'required');
		
		return $this->form_validation->run();
	}
	
	private function _inventoryControl()
	{
		
		$errstr = "";
		$bol = TRUE;
		
		// $this->db->query("LOCK TABLES product READ");
		//	UPDATE product SET in_stock = in_stock - 1 WHERE id = 119;
		
		
		foreach ($this->cart->contents() as $item) {
						
			$query = $this->db->query("SELECT in_stock, can_pre_order FROM product WHERE id = ?", $item['id'])->result();
			if (count($query) >= 1) {
				if ($query[0]->can_pre_order == 0) {
					if ($item['qty'] > $query[0]->in_stock) {
						
						// $errstr .= "Sorry, we currently don't have enough stock of " . $item['name'] . " " . $query[0]->in_stock.br(1);
						
						$errstr .= "*".$item['name'].br(1);
						$MyShoppingCart = array();
						$MyShoppingCart[0]['rowid'] = $item['rowid'];
						$MyShoppingCart[0]['id']    = $item['id'];
						$MyShoppingCart[0]['qty']   = $query[0]->in_stock;
						$this->cart->update($MyShoppingCart);
						$this->cart->total_qtys();
						
						// $errstr .= "但是你購買的數量是 " . $item['qty'].br(1);
						
	
						// echo $item['id'].br(1);
						// echo $item['rowid'].br(1);
						// echo $item['name'].br(1);
						// echo $item['qty'].br(1);
						// echo $item['price'].br(1);
						// $item['qty'] = 0;
						$bol = FALSE;
					}
				}
			}
			
		}
		
		if ($errstr != "") {
			$errstr_01 = "Sorry, due to high demand we currently don't have enough stock of following product(s):".br(1);
			$errstr_02 = "<br>Please <a href='/viewcart'>click here</a> to go back and review your shopping cart.";
			$this->ErrorMessage = $errstr_01 . $errstr . $errstr_02;
		}
		
		// $this->db->query("UNLOCK TABLES");
		
		return $bol;
	}
	
	private function _submit()
	{
		if ($this->_inventoryControl() === FALSE) {
			$this->index();
			return;
		}

		/**
		 * 2011/06/14 特別狀態
		 * 這個是特殊狀態, 由於要先提供給客戶退換貨的功能, 所以要先打開這個功能
		 * 當客戶輸入 Discount Code, 總數小於等於零, 就要先讓他先寫入訂單
		 */
		$Discount = ($this->session->userdata('Discount_Sub_Total') ? $this->session->userdata('Discount_Sub_Total') : 0);
        $Voucher = '';//($this->session->userdata('Voucher_Sub_Total') ? $this->session->userdata('Voucher_Sub_Total') : 0);
		
		
		 if ($this->session->userdata('Amount') > 0) {
				
			if ($this->input->post('payment_method') == 1) {
				$this->Using_Authorize_net();
					// echo "string";
				} else {
					$this->Using_Payapl_pro();
				}						
			
		}else {
			
			if ($this->_Special_Status() === FALSE) {
				$this->index();
				return;
			}
			
		}
        
		
		

		
				
	}

	function _Special_Status()
	{
		if ($this->_submit_special_validate() === FALSE) {
			return FALSE;
		}
		
		$VoucherCode_s = $this->session->userdata('VoucherCode');				
		$Voucher_Sub_Total_s = $this->session->userdata('Voucher_Sub_Total');

		$sp 			  = $this->session->userdata('ShippingOptions');
		$DestinationState = $this->session->userdata('DestinationState');
		$DiscountCode     = $this->session->userdata('DiscountCode');

		// echo $this->input->post('shipSame');

		if ($this->input->post('shipSame')) {
			$this->CITY = $this->input->post('bill_city');
			$this->STATE = $this->input->post('bill_state');
			$this->ADDR = $this->input->post('bill_address');
			$this->ZIPCODE = $this->input->post('bill_zipcode');

		} else {
			$this->CITY = $this->input->post('ship_city');
			$this->STATE = $this->input->post('ship_state');
			$this->ADDR = $this->input->post('ship_address')." ".$this->input->post('ship_address2');
			$this->ZIPCODE = $this->input->post('ship_zipcode');
		}

		// exit;

        //	產生 invoice 編號
        $this->db->query("CALL insert_order_no (@ORDER_NO);");
        $res = $this->db->query("SELECT @ORDER_NO as 'order_no'");
        $row = $res->row();

        $invoice_num = $row->order_no;

		$this->INVOICE_NUM = $invoice_num;
		$this->AMOUNT = $this->session->userdata('Amount');
		// $this->CartCheckout->add2Invoice_paypal($this->INVOICE_NUM);
		$this->CartCheckout->special_payment($this->INVOICE_NUM);
				
		sleep(1);
		$this->cart->destroy();

		$query = $this->db->query("SELECT * FROM `order` WHERE order_no = ?", $this->INVOICE_NUM);
		$this->Order = $query->result();

		$query = $this->db->query(
			"SELECT invoice_number," .
			"(SELECT name FROM product WHERE id = ol.pid)  as 'name'," .
			"(SELECT sku FROM product WHERE id = ol.pid)  as 'sku'," .
			"price, qty " .
			"FROM `order_list` as ol " .
			"where invoice_number = ?",
			$this->INVOICE_NUM);
		$this->OrderList = $query->result();

		$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUM);
		$this->session->set_userdata('VoucherCode', $VoucherCode_s);
		$this->session->set_userdata('Voucher_Sub_Total', $Voucher_Sub_Total_s);
		
		redirect('thankyou', 'refresh');

	}

	public function Using_Payapl_pro()
	{
		
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		$sp 			  = $this->session->userdata('ShippingOptions');
		$DestinationState = $this->session->userdata('DestinationState');
		$DiscountCode     = $this->session->userdata('DiscountCode');

// echo $this->input->post('shipSame');


		if ($this->input->post('shipSame')) {
			$this->CITY = $this->input->post('bill_city');
			$this->STATE = $this->input->post('bill_state');
			$this->ADDR = $this->input->post('bill_address');
			$this->ZIPCODE = $this->input->post('bill_zipcode');

		} else {
			$this->CITY = $this->input->post('ship_city');
			$this->STATE = $this->input->post('ship_state');
			$this->ADDR = $this->input->post('ship_address')." ".$this->input->post('ship_address2');
			$this->ZIPCODE = $this->input->post('ship_zipcode');
		}

// exit;
		
		//	產生 invoice 編號
		// $invoice_num = time()."-".rand(1000, 9999);
		// $this->INVOICE_NUM = 'P'.$invoice_num;
		
		//	產生 invoice 編號
		$this->db->query("CALL insert_order_no (@ORDER_NO);");
		$res = $this->db->query("SELECT @ORDER_NO as 'order_no'");
		$row = $res->row();
		
		$this->INVOICE_NUM = $row->order_no;
		
		$this->AMOUNT = $this->session->userdata('Amount');
		// echo "begin";
		$this->CartCheckout->add2Invoice_paypal($this->INVOICE_NUM);
		// echo "end";
		// exit;
		
		sleep(1);
		$this->cart->destroy();
		
		$query = $this->db->query("SELECT * FROM `order` WHERE order_no = ?", $this->INVOICE_NUM);
		$this->Order = $query->result();
			
		$query = $this->db->query(
			"SELECT invoice_number," .
			"(SELECT name FROM product WHERE id = ol.pid)  as 'name'," .
			"(SELECT sku FROM product WHERE id = ol.pid)  as 'sku'," .
			"price, qty " .
			"FROM `order_list` as ol " .
			"where invoice_number = ?",
			$this->INVOICE_NUM);
		$this->OrderList = $query->result();
		
		$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUM);
		
		$this->load->view('send_to_paypal');
		
		// $mailer = new Mailer();
		// $mailer->send_invoice($this->input->post('email'));
		
	}
	
	
	public function Using_Authorize_net()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		//	運費的方式
		$sp = $this->session->userdata('ShippingOptions');
		//	送達的目的地 (主要是判別是否有含稅物)
		$ds = $this->session->userdata('DestinationState');
		//	取得折扣的模式
		$DiscountCode = $this->session->userdata('DiscountCode');
		//	取的折扣的金額
		$Discount = ($this->session->userdata('Discount_Sub_Total') ? $this->session->userdata('Discount_Sub_Total') : 0);
		//	是否具備 Free Shipping
		$FREE_SHIPPING = ($this->session->userdata('FreeShipping') ? $this->session->userdata('FreeShipping') : 0);
		//	總金額是否大於 Free Shipping 2 的規則
		$IS_FREE_SHIPPING2 = ($this->session->userdata('FreeShipping2') ? $this->session->userdata('FreeShipping2') : 0);
		
		//	先判斷訂單中是否具備 Promo 的功能
		if (is_array($DiscountCode)) {
		
			// echo "Promot id : " . $DiscountCode[0]->id.br(1);
			// echo "Promot code : " . $DiscountCode[0]->code.br(1);
			// echo "Promot type : " . $DiscountCode[0]->discount_type.br(1);
			// echo "Promot discount_percentage : " . $DiscountCode[0]->discount_percentage.br(1);
			// echo "Promot discount_amount_threshold : " . $DiscountCode[0]->discount_amount_threshold.br(1);
			// echo "Promot xuses : " . $DiscountCode[0]->xuses.br(1);
			// echo "Promot can_free_shipping : " . $DiscountCode[0]->can_free_shipping.br(1);
			
			switch ($DiscountCode[0]->discount_type) {
				
				case '1':
					
					//	產生 invoice 編號
					$invoice_num = time();
					$this->AIM->invoice_num = $invoice_num;

					$this->AIM->amount    = $this->session->userdata('Amount');
					$this->AIM->card_num  = $this->input->post('card_number');
					$this->AIM->exp_date  = $this->input->post('CardExpiryMonth') . '/' . $this->input->post('CardExpiryYear');
					$this->AIM->card_code = $this->input->post('ccv');
					
					if ($this->CartCheckout->check_is_pre_order() == TRUE) {
												
						$Query = $this->db->query("SELECT * FROM discountcode_rel_products WHERE d_id = ?", $DiscountCode[0]->id);
						$Promo_with_Product = $Query->result();
						
						//	表示針對部份產品進行折扣
						if (count($Promo_with_Product) >= 1) {
													
							$pre_order_price = $this->CartCheckout->split_order_price_no_tax(); 
							$capture_price   = $this->CartCheckout->get_capture_price_no_tax();
							
							//	折扣的金額
							$sub_total = 0;
							//	折扣後的稅務
							$sub_total_tax = 0;
							$temp_capture_price = 0;
							
							foreach ($Promo_with_Product as $Product) {
								foreach ($this->cart->contents() as $items) {
									if ($items['id'] == $Product->pid) {
										$sub_total += $items['price'] - ($items['price'] * $DiscountCode[0]->discount_percentage);
									} else {
										$sub_total += $items['price'];
									}
								}
							}
							
							// echo $sub_total.br(1);
							
							//	檢查是否具備稅務金額
							if ($ds[0]['tax_rate'] == 0) {
								//	Do nothing.
							} else {
								$sub_total_tax = round(($sub_total * ($ds[0]['tax_rate'] / 100)), 2);
							}
							
							// echo $sub_total_tax.br(1);
							
							$temp_capture_price = ($sub_total - $pre_order_price);
							
							if ($temp_capture_price <= 0) {
								if ($this->_GoCharge() == TRUE) {
									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
								} else {
									$this->index();
									return FALSE;
								}
							} else {
								
								//	這兩個條件下一個為真, 就不需要送出釘單中的運費款項
								if ($FREE_SHIPPING == 0 && $IS_FREE_SHIPPING2 == 0) {
									
									//	STEP 1 先請運費的款項
									if ($sp[0]['price'] > 0) {
										$this->AIM->amount = $sp[0]['price'];			
										$response = $this->AIM->authorizeOnly();
										//	請款成功
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else { 
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}						
									}
									
									//	Setp 2 請款實際金額
									if ($capture_price > 0) {
										$this->AIM->amount = (($sub_total - $pre_order_price) + $sub_total_tax);
										$response = $this->AIM->authorizeOnly();
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else {
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}
									}
									
									//	Setp 3 請款 Pre-Order 金額
									$this->AIM->amount = $pre_order_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}

									$this->CartCheckout->add2Invoice($response);

									$this->INVOICE_NUMBER = $response->invoice_number;
									$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
									$mailer = new Mailer();
									$mailer->send_invoice($this->input->post('email'));

									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
									
								} else {
									
									//	Setp 2 請款實際金額
									if ($capture_price > 0) {
										$this->AIM->amount = (($sub_total - $pre_order_price) + $sub_total_tax);
										$response = $this->AIM->authorizeOnly();
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else {
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}
									}

									//	Setp 3 請款 Pre-Order 金額
									$this->AIM->amount = $pre_order_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}

									$this->CartCheckout->add2Invoice($response);

									$this->INVOICE_NUMBER = $response->invoice_number;
									$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
									$mailer = new Mailer();
									$mailer->send_invoice($this->input->post('email'));

									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
									
								}
								
							}

						} else {
							
							// echo "have pro-order";						
							$pre_order_price = $this->CartCheckout->split_order_price(); 
							$capture_price   = $this->CartCheckout->get_capture_price();

							//	計算折扣後的實際請款金額
							$capture_price = number_format(($capture_price - ($capture_price * $DiscountCode[0]->discount_percentage)), 2);
							// echo $capture_price;

							$pre_order_price = number_format(($pre_order_price - ($pre_order_price * $DiscountCode[0]->discount_percentage)), 2);
							// echo $pre_order_price;

							//	這兩個條件下一個為真, 就不需要送出釘單中的運費款項
							if ($FREE_SHIPPING == 0 && $IS_FREE_SHIPPING2 == 0) {

								//	STEP 1 先請運費的款項
								if ($sp[0]['price'] > 0) {
									$this->AIM->amount = $sp[0]['price'];			
									$response = $this->AIM->authorizeOnly();
									//	請款成功
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else { 
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}						
								}

								//	Setp 2 請款實際金額
								if ($capture_price > 0) {
									$this->AIM->amount = $capture_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}
								}

								//	Setp 3 請款 Pre-Order 金額
								$this->AIM->amount = $pre_order_price;
								$response = $this->AIM->authorizeOnly();
								if ($response->approved) {
									$this->CartCheckout->write_to_authorize_reponse($response);
								} else {
									$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
									$this->index();
									return FALSE;
								}
								
								$this->CartCheckout->add2Invoice($response);

								$this->INVOICE_NUMBER = $response->invoice_number;
								$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
								$mailer = new Mailer();
								$mailer->send_invoice($this->input->post('email'));

								$this->cart->destroy();
								$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
								redirect('thankyou', 'refresh');

							} else {

								//	Setp 2 請款實際金額
								if ($capture_price > 0) {
									$this->AIM->amount = $capture_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}
								}

								//	Setp 3 請款 Pre-Order 金額
								$this->AIM->amount = $pre_order_price;
								$response = $this->AIM->authorizeOnly();
								if ($response->approved) {
									$this->CartCheckout->write_to_authorize_reponse($response);
								} else {
									$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
									$this->index();
									return FALSE;
								}

								$this->CartCheckout->add2Invoice($response);

								$this->INVOICE_NUMBER = $response->invoice_number;

								$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
								$mailer = new Mailer();
								$mailer->send_invoice($this->input->post('email'));
								
								$this->cart->destroy();
								$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
								redirect('thankyou', 'refresh');

							}
							
						}
						
					} else {
						if ($this->_GoCharge() == TRUE) {
							$this->cart->destroy();
							$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
							redirect('thankyou', 'refresh');
						} else {
							$this->index();
							return FALSE;
						}
					}
					
					break;
				case '2':
					
					//	產生 invoice 編號
					$invoice_num = time();
					$this->AIM->invoice_num = $invoice_num;

					$this->AIM->amount    = $this->session->userdata('Amount');
					$this->AIM->card_num  = $this->input->post('card_number');
					$this->AIM->exp_date  = $this->input->post('CardExpiryMonth') . '/' . $this->input->post('CardExpiryYear');
					$this->AIM->card_code = $this->input->post('ccv');
					
					if ($this->CartCheckout->check_is_pre_order() == TRUE) {
						
						$Query = $this->db->query("SELECT * FROM discountcode_rel_products WHERE d_id = ?", $DiscountCode[0]->id);
						$Promo_with_Product = $Query->result();
						
						//	表示針對部份產品進行折扣
						if (count($Promo_with_Product) >= 1) {
																				
							$pre_order_price = $this->CartCheckout->split_order_price_no_tax(); 
							$capture_price   = $this->CartCheckout->get_capture_price_no_tax();

							// echo $pre_order_price.br(1);
							// echo $capture_price.br(1);
							
							//	折扣的金額
							$sub_total = 0;
							//	折扣後的稅務
							$sub_total_tax = 0;
							$temp_capture_price = 0;
							
							foreach ($Promo_with_Product as $Product) {
								foreach ($this->cart->contents() as $items) {
									if ($items['id'] == $Product->pid) {
										$sub_total += $DiscountCode[0]->discount_percentage;
									}
								}
							}

							//	檢查是否具備稅務金額
							if ($ds[0]['tax_rate'] == 0) {
								//	Do nothing.
							} else {
								$sub_total_tax = ($this->cart->total() - $sub_total);
								$sub_total_tax = round(($sub_total_tax * ($ds[0]['tax_rate'] / 100)), 2);
								// echo $sub_total_tax.br(1);
							}
							
							//	將要請款的金額減去折扣後的金額
							$temp_capture_price = ($capture_price - $sub_total);
							
							//	若實際請款金額小於等於零, 就直接請款
							if ($temp_capture_price <= 0) {
								if ($this->_GoCharge() == TRUE) {
									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
								} else {
									$this->index();
									return FALSE;
								}
							} else {
								
								//	這兩個條件下一個為真, 就不需要送出釘單中的運費款項
								if ($FREE_SHIPPING == 0 && $IS_FREE_SHIPPING2 == 0) {
									
									//	STEP 1 先請運費的款項
									if ($sp[0]['price'] > 0) {
										$this->AIM->amount = $sp[0]['price'];			
										$response = $this->AIM->authorizeOnly();
										//	請款成功
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else { 
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}						
									}
									
									//	Setp 2 請款實際金額
									if ($capture_price > 0) {
										$this->AIM->amount = (($capture_price - $sub_total) + $sub_total_tax);
										$response = $this->AIM->authorizeOnly();
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else {
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}
									}
									
									//	Setp 3 請款 Pre-Order 金額
									$this->AIM->amount = $pre_order_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}

									$this->CartCheckout->add2Invoice($response);

									$this->INVOICE_NUMBER = $response->invoice_number;
									$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
									$mailer = new Mailer();
									$mailer->send_invoice($this->input->post('email'));

									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
									
								} else {
									
									//	Setp 2 請款實際金額
									if ($capture_price > 0) {
										$this->AIM->amount = (($capture_price - $sub_total) + $sub_total_tax);
										$response = $this->AIM->authorizeOnly();
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else {
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}
									}

									//	Setp 3 請款 Pre-Order 金額
									$this->AIM->amount = $pre_order_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}

									$this->CartCheckout->add2Invoice($response);

									$this->INVOICE_NUMBER = $response->invoice_number;
									$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
									$mailer = new Mailer();
									$mailer->send_invoice($this->input->post('email'));

									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
									
								}
								
							}
							
							
						} else {
							//	全部都可以有折扣

							$pre_order_price = $this->CartCheckout->split_order_price_no_tax(); 
							$capture_price   = $this->CartCheckout->get_capture_price_no_tax();

							// echo $pre_order_price . br(1);
							// echo $capture_price . br(1);
							
							// $pre_order_price = ($pre_order_price - $DiscountCode[0]->discount_percentage);
							$capture_price   = ($capture_price   - $DiscountCode[0]->discount_percentage);
														
							// echo $pre_order_price . br(1);
							// echo $capture_price . br(1);
							
							//	檢查是否具備稅務金額
							if ($ds[0]['tax_rate'] == 0) {
								//	Do nothing.
							} else {
								$pre_order_price_tax = number_format((($pre_order_price * $ds[0]['tax_rate']) / 100), 2);
								$capture_price_tax   = number_format((($capture_price * $ds[0]['tax_rate']) / 100), 2);
							}
							
							// echo $pre_order_price += $pre_order_price_tax. br(1);
							// echo $capture_price   += $capture_price_tax . br(1);
							
							if ($capture_price <= 0) {
								if ($this->_GoCharge() == TRUE) {
									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
								} else {
									$this->index();
									return FALSE;
								}
							} else {
								
								//	這兩個條件下一個為真, 就不需要送出釘單中的運費款項
								if ($FREE_SHIPPING == 0 && $IS_FREE_SHIPPING2 == 0) {
									
									//	STEP 1 先請運費的款項
									if ($sp[0]['price'] > 0) {
										$this->AIM->amount = $sp[0]['price'];			
										$response = $this->AIM->authorizeOnly();
										//	請款成功
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else { 
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}						
									}

									//	Setp 2 請款實際金額
									if ($capture_price > 0) {
										$this->AIM->amount = $capture_price;
										$response = $this->AIM->authorizeOnly();
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else {
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}
									}

									//	Setp 3 請款 Pre-Order 金額
									$this->AIM->amount = $pre_order_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}

									$this->CartCheckout->add2Invoice($response);

									$this->INVOICE_NUMBER = $response->invoice_number;
									$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
									$mailer = new Mailer();
									$mailer->send_invoice($this->input->post('email'));

									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
								} else {
									
									//	Setp 2 請款實際金額
									if ($capture_price > 0) {
										$this->AIM->amount = $capture_price;
										$response = $this->AIM->authorizeOnly();
										if ($response->approved) {
											$this->CartCheckout->write_to_authorize_reponse($response);
										} else {
											$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
											$this->index();
											return FALSE;
										}
									}

									//	Setp 3 請款 Pre-Order 金額
									$this->AIM->amount = $pre_order_price;
									$response = $this->AIM->authorizeOnly();
									if ($response->approved) {
										$this->CartCheckout->write_to_authorize_reponse($response);
									} else {
										$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
										$this->index();
										return FALSE;
									}

									$this->CartCheckout->add2Invoice($response);

									$this->INVOICE_NUMBER = $response->invoice_number;
									$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
									$mailer = new Mailer();
									$mailer->send_invoice($this->input->post('email'));

									$this->cart->destroy();
									$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
									redirect('thankyou', 'refresh');
								}	
							}
						}
												
					} else {
						if ($this->_GoCharge() == TRUE) {
							$this->cart->destroy();
							$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
							redirect('thankyou', 'refresh');
						} else {
							$this->index();
							return FALSE;
						}	
					}
					
					break;
				case '3':
				case '4':
				case '5':

					//	產生 invoice 編號
					$invoice_num = time();
					$this->AIM->invoice_num = $invoice_num;

					$this->AIM->amount    = $this->session->userdata('Amount');
					$this->AIM->card_num  = $this->input->post('card_number');
					$this->AIM->exp_date  = $this->input->post('CardExpiryMonth') . '/' . $this->input->post('CardExpiryYear');
					$this->AIM->card_code = $this->input->post('ccv');
					
					if ($this->CartCheckout->check_is_pre_order() == TRUE) {
						
						$pre_order_price = $this->CartCheckout->split_order_price(); 
						$capture_price   = $this->CartCheckout->get_capture_price();
						
						//	Setp 2 請款實際金額
						if ($capture_price > 0) {
							$this->AIM->amount = $capture_price;
							$response = $this->AIM->authorizeOnly();
							if ($response->approved) {
								$this->CartCheckout->write_to_authorize_reponse($response);
							} else {
								$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
								$this->index();
								return FALSE;
							}
						}

						//	Setp 3 請款 Pre-Order 金額
						$this->AIM->amount = $pre_order_price;
						$response = $this->AIM->authorizeOnly();
						if ($response->approved) {
							$this->CartCheckout->write_to_authorize_reponse($response);
						} else {
							$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
							$this->index();
							return FALSE;
						}

						$this->CartCheckout->add2Invoice($response);

						$this->INVOICE_NUMBER = $response->invoice_number;
						$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
						$mailer = new Mailer();
						$mailer->send_invoice($this->input->post('email'));

						$this->cart->destroy();
						$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
						redirect('thankyou', 'refresh');
						
					} else {
						if ($this->_GoCharge() == TRUE) {
							$this->cart->destroy();
							$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
							redirect('thankyou', 'refresh');
						} else {
							$this->index();
							return FALSE;
						}
					}
					
					break;
				default:
					break;
			}
			
		} else {
		
		 
		
			//	沒有 Promo 的計算
			// echo "No Promo";
			
			if ($this->CartCheckout->check_is_pre_order() == TRUE) {
				
				// echo "have pro-order";
				
				//	產生 invoice 編號
				$invoice_num = time();
				$this->AIM->invoice_num = $invoice_num;
				
				$this->AIM->amount    = $this->session->userdata('Amount');
				$this->AIM->card_num  = $this->input->post('card_number');
				$this->AIM->exp_date  = $this->input->post('CardExpiryMonth') . '/' . $this->input->post('CardExpiryYear');
				$this->AIM->card_code = $this->input->post('ccv');
								
				//	這兩個條件下一個為真, 就不需要送出釘單中的運費款項
				if ($FREE_SHIPPING == 0 && $IS_FREE_SHIPPING2 == 0) {
										
					//	STEP 1 先請運費的款項
					if ($sp[0]['price'] > 0) {
						$this->AIM->amount = $sp[0]['price'];			
						$response = $this->AIM->authorizeOnly();
						//	請款成功
						if ($response->approved) {
							$this->CartCheckout->write_to_authorize_reponse($response);
						} else { 
							$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
							$this->index();
							return FALSE;
						}						
					}
										
					//	Setp 2 請款實際金額
					if ($this->CartCheckout->get_capture_price() > 0) {
						$this->AIM->amount = $this->CartCheckout->get_capture_price();
						$response = $this->AIM->authorizeOnly();
						if ($response->approved) {
							$this->CartCheckout->write_to_authorize_reponse($response);
						} else {
							$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
							$this->index();
							return FALSE;
						}
					}
					
					//	Setp 3 請款 Pre-Order 金額
					$this->AIM->amount = $this->CartCheckout->split_order_price();
					$response = $this->AIM->authorizeOnly();
					if ($response->approved) {
						$this->CartCheckout->write_to_authorize_reponse($response);
					} else {
						$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
						$this->index();
						return FALSE;
					}
										
					$this->CartCheckout->add2Invoice($response);
					
					$this->INVOICE_NUMBER = $response->invoice_number;
					$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;

					$mailer = new Mailer();
					$mailer->send_invoice($this->input->post('email'));
					$this->cart->destroy();
					$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
					redirect('thankyou', 'refresh');
					
				} else {
					
					//	有 Free Shipping, 直接走 2, 3
					//	Setp 2 請款實際金額
					
					// echo $this->CartCheckout->get_capture_price();
					// echo $this->CartCheckout->split_order_price();
					
					if ($this->CartCheckout->get_capture_price() > 0) {
						$this->AIM->amount = $this->CartCheckout->get_capture_price();
						$response = $this->AIM->authorizeOnly();
						if ($response->approved) {
							$this->CartCheckout->write_to_authorize_reponse($response);
						} else {
							$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
							$this->index();
							return FALSE;
						}						
					}
					
					//	Setp 3 請款 Pre-Order 金額
					$this->AIM->amount = $this->CartCheckout->split_order_price();
					$response = $this->AIM->authorizeOnly();
					if ($response->approved) {
						$this->CartCheckout->write_to_authorize_reponse($response);
					} else {
						$this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
						$this->index();
						return FALSE;
					}
										
					//	Free Shipping 寫入訂單
					$this->CartCheckout->add2Invoice($response);
					
					$this->INVOICE_NUMBER = $response->invoice_number;
					$this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;
					$mailer = new Mailer();
					$mailer->send_invoice($this->input->post('email'));
					
					$this->cart->destroy();
					$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
					redirect('thankyou', 'refresh');
				}
				
			} else {
				
				
				if ($this->_GoCharge() == TRUE) {								
					$this->cart->destroy();
					$this->session->set_userdata('INVOICE_NUMBER', $this->INVOICE_NUMBER);
					redirect('thankyou', 'refresh');
				} else {
					$this->index();
					return FALSE;
				}
			}
			
		}
				
		$this->index();
	}

	private function _GoCharge()
	{
		
		
		
		//	!!!! 祕密武器 !!!!!
		// $t = time();
		// echo date("YmdHis", $t).'-'.rand(1000, 9999);
		
		
		// //	產生 invoice 編號		
		// $invoice_num = time()."-".rand(1000, 9999);
		// $this->AIM->invoice_num = $invoice_num;
		
		//	產生 invoice 編號
		$this->db->query("CALL insert_order_no (@ORDER_NO);");
		$res = $this->db->query("SELECT @ORDER_NO as 'order_no'");
		$row = $res->row();
		
		$invoice_num = $row->order_no;
		
		
        if($this->session->userdata('Amount') != 0) {
		
			
		
            $this->AIM->invoice_num =  $row->order_no;

		  

            // // do {
            // // 	//	產生 invoice 編號
            // // 	$invoice_num = time();
            // $num = $this->db->query("SELECT count(order_no) as 'count' FROM `order` WHERE order_no = ?", $invoice_num)->result();
            // if (count($num) >= 1) {
            // 	if ($num[0]->count >= 1) {
            // 		//	在產生ㄧ次...
            // 		$invoice_num = time().rand(1000, 9999);
            // 	}
            // }

            $b_state = $this->db->query("SELECT sharthand FROM `state` WHERE id = ?", $this->input->post('bill_state'))->result();
            $str_bill_state = '';
            if (count($b_state) >= 1) {
                $str_bill_state = $b_state[0]->sharthand;
            }

            // } while ($num[0]->count == 0);

            $this->AIM->amount    = $this->session->userdata('Amount');
            $this->AIM->card_num  = $this->input->post('card_number');
            $this->AIM->exp_date  = $this->input->post('CardExpiryMonth') . '/' . $this->input->post('CardExpiryYear');
            $this->AIM->card_code = $this->input->post('ccv');
		
			
		
            $customer = (object)array();

            $customer->first_name    = $this->input->post('firstname');
            $customer->last_name     = $this->input->post('lastname');
            $customer->address       = $this->input->post('bill_address');
            $customer->city          = $this->input->post('bill_city');
            $customer->state         = $str_bill_state;
            $customer->zip           = $this->input->post('bill_zipcode');
            $customer->country       = "US";
            $customer->email         = $this->input->post('email');

            $shipping_info = (object)array();

            $shipping_info->ship_to_first_name   = $this->input->post('firstname');
            $shipping_info->ship_to_last_name    = $this->input->post('lastname');

            $shippingSame = $this->input->post('shipSame');

            if ($shippingSame == 1) {
                $shipping_info->ship_to_address      = $this->input->post('bill_address');
                $shipping_info->ship_to_city         = $this->input->post('bill_city');
                $shipping_info->ship_to_state        = $this->input->post('ship_state');
                $shipping_info->ship_to_zip          = $this->input->post('bill_zipcode');
                $shipping_info->ship_to_country      = "US";

            } else {
                $shipping_info->ship_to_address      = $this->input->post('ship_address')." ".$this->input->post('ship_address2');
                $shipping_info->ship_to_city         = $this->input->post('ship_city');
                $shipping_info->ship_to_state        = $str_bill_state;
                $shipping_info->ship_to_zip          = $this->input->post('ship_zipcode');
                $shipping_info->ship_to_country      = "US";
            }

            // $this->AIM->setCustomFields($shipping_info);
            // $this->AIM->setCustomFields($customer);

            $this->AIM->setFields($customer);
            $this->AIM->setFields($shipping_info);

            // print_r($customer);
            // print_r($shipping_info);
            // print_r($this->AIM);
            // exit;

            $response = $this->AIM->authorizeOnly();

            if ($response->approved) {

                $this->db->trans_start();

                $this->CartCheckout->write_to_authorize_reponse($response);
                $this->CartCheckout->add2Invoice($response);

                $this->INVOICE_NUMBER = $response->invoice_number;
                $this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;

                $mailer = new Mailer();
                $mailer->send_invoice($this->input->post('email'));

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    log_message('DEBUG', 'Checkout->save() 交易錯誤');
                } else {
                    log_message('DEBUG', '交易成功, 並寫入到資料庫完成');
                    $this->db->trans_complete();
                }

                return TRUE;
            } else {
                $this->CartCheckout->write_to_authorize_reponse($response);

                //	這很危險, 可是我沒時間, 無言, 先給, 我在找時間偷偷修
                $this->db->query("DELETE FROM `order` WHERE order_no = ?", $invoice_num);

                // $this->ErrorMessage = htmlentities($response->response_reason_text) . br(1);
                $this->ErrorMessage =  $response->response_reason_text.br(1);

                // $this->ErrorMessage .= $response->error_message.br(1);
                // print_r($response);

                // print_r($response);
                // exit;
                // $this->index();
                return FALSE;
            }
        } else {
            $response_object = new stdClass();
            $response_object->invoice_number = $invoice_num;
            $response_object->card_type = '4';

            $this->CartCheckout->add2Invoice($response_object);

            $this->INVOICE_NUMBER = $response_object->invoice_number;
            $this->ORDER_DATE = $this->CartCheckout->ORDER_DATETIME;

            $mailer = new Mailer();
            $mailer->send_invoice($this->input->post('email'));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('DEBUG', 'Checkout->save() 交易錯誤');
            } else {
                log_message('DEBUG', '交易成功, 並寫入到資料庫完成');
                $this->db->trans_complete();
            }

            return TRUE;
        }
	}		
	
	private function _getOrderNo()
	{
		//	Default Order No. 10000
		$new_order_no = '10000';
		
		$this->db->select('order_no');
		$this->db->from('order');
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$result = $this->db->get()->result_array();
		if (count($result) >= 1) {
			$new_order_no = $result[0]['order_no'] + 1;
		}
		
		return $new_order_no;
	}

}