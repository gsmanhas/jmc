<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Systemcart
{	
	private $CI;
	
	//	單個商品的最大購買數量
	private $MAX_UNIT_COUNT = 5;
	
	function __construct()
	{
		$this->CI =& get_instance();
		log_message('debug', "Systemcart Class Initialized");
	}
	
	public function all_add_to_wish($gid)
	{
		$query = $this->CI->db->query(
			"SELECT id, name, small_image, title, price, retail_price, on_sale, url, can_pre_order, in_stock, " .
			"(SELECT DISTINCT (SELECT url FROM product_catalogs WHERE id = cid) as 'catalog_name' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_name', " .
			"(SELECT DISTINCT (SELECT id FROM product_catalogs WHERE id = cid) as 'catalog_id' FROM product_rel_catalog where pid = p.id and is_delete = 0 limit 1) as 'catalog_id' " .
			"FROM product as p WHERE id in(SELECT pid FROM get_the_look_rel_product WHERE look_id = ?)"
		, $gid);
		
		foreach ($query->result() as $Product) {
			
			$is_exists = $this->CI->db->query(
				"SELECT id FROM wishlist WHERE uid = " . $this->CI->session->userdata('user_id') .
				" AND pid = " . $Product->id .
				" AND is_delete = 0"
			);
			
			if ($is_exists->num_rows() == 0) {
				
				$WishList = array(
					'cid' => $Product->catalog_id,
					'pid' => $Product->id,
					'uid' => $this->CI->session->userdata('user_id'),
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->CI->db->trans_start();
				$this->CI->db->insert('wishlist', $WishList);
				$this->CI->db->trans_complete();
				
			}
			
		}
		
		$query = $this->CI->db->query(
			"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->CI->session->userdata('user_id') .
			" AND is_delete = 0"
		);
		$result = $query->result();
		$this->CI->session->set_userdata(array("wishlist_count" => $result[0]->wishlist_count));
		
		
	}
	
	public function get_the_look_all_buy($gid)
	{
		$Products = $this->CI->db->query(
			"SELECT p.* FROM product as p " .
			"WHERE id in(" .
			"SELECT pid FROM get_the_look_rel_product WHERE look_id = ? ORDER BY pid asc" .
			") AND p.is_delete = 0 AND p.publish = 1", $gid
		)->result();
		
		foreach ($Products as $Product) {
			
			if ($Product->can_pre_order == 1) {
				$MyShippingCart = array(
					array(
						'id'        => $Product->id,
						'qty'       => 1,
						'price'     => $Product->price,
						'name'      => $Product->name,
						'options' => array(
							'Pre-order' => ''
						)
					)
				);
			} else {
				$MyShippingCart = array(
					array(
						'id'      => $Product->id,
						'qty'     => 1,
						'price'   => $Product->price,
						'name'    => $Product->name
					)
				);
			}
			
			$isExists = FALSE;
			//	檢查產品是否以放入到購物車
			$bol_MAX_UNIT = FALSE;

			foreach($this->CI->cart->contents() as $items) {

				if ($items['id'] == $Product->id) {
					//	目前的設定是最多 5 筆 
					$MAX_QTY = $items['qty'] + 1;

					// //	檢查是否超過 in_stock
					// if ($items['qty'] >= $Product[0]->in_stock) {
					// 	$items['qty'] = $Product[0]->in_stock - 1;
					// }

					if ($Product->can_pre_order == 0) {
						if ($MAX_QTY > $Product->in_stock) {
							$bol_MAX_UNIT = TRUE;
							$MAX_QTY = $Product->in_stock;
						}
					}

					if ($MAX_QTY > $this->MAX_UNIT_COUNT) {
						$items['qty'] = 4;
					}

					if ($bol_MAX_UNIT) {
						
						// $MyShippingCart = array(
						// 	'rowid' => $items['rowid'],
						// 	'qty'   => $MAX_QTY
						// );
						
						$MyShippingCart[0]['rowid'] = $items['rowid'];
						$MyShippingCart[0]['qty']   = $MAX_QTY;
						
					} else {
						
						// $MyShippingCart = array(
						// 	'rowid' => $items['rowid'],
						// 	'qty'   => $items['qty'] + 1
						// );
						
						$MyShippingCart[0]['rowid'] = $items['rowid'];
						$MyShippingCart[0]['qty']   = $items['qty'] + 1;
						
					}
					$isExists = TRUE;
				}
			}

			if ($isExists == TRUE) {				
				if ($Product->can_pre_order == 0) {
					if ($Product->in_stock >= 1) {
						$this->CI->cart->update($MyShippingCart);
					}
				} else {
					$this->CI->cart->update($MyShippingCart);
				}
			} else {
				if ($Product->can_pre_order == 0) {
					if ($Product->in_stock >= 1) {
						$this->CI->cart->insert($MyShippingCart);
					}
				} else {
					$this->CI->cart->insert($MyShippingCart);
				}
			}
			
		}
		
	}
	
	public function product_item_in_cart($pid)
	{
		foreach($this->CI->cart->contents() as $items) {
			if ($items['id'] == $pid) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public function get_cart_item_qty($pid)
	{
		foreach($this->CI->cart->contents() as $items) {
			if ($items['id'] == $pid) {
				return $items['qty'];
			}
		}
		return 0;
	}
	
	//	查詢目前購物車中的 SESSION 資料, 並找出對應的商品
	public function find_current_cart_itme($pid)
	{
		foreach($this->CI->cart->contents() as $items) {
			//	找到對映的商品
			if ($items['id'] == $pid) {
				return $items;
			}
		}
	}
	
	public function add2Cart($load_view = TRUE)
	{
		
		log_message('debug', "Begin Systemcart->add2Cart()");
		
		//	這個是加入的數量
		$QTY = $this->CI->input->post('qty');
		
		log_message('debug', "輸入的 QTY = $QTY");
		
		//	庫存資料變數
		$IN_STOCK = 0;
		
		// 尋找 Product 商品資料, 判斷 is_delete = 0, publish = 1 (發佈 = 1, 不發佈 = 0)
		$query = $this->CI->db->query(
			'SELECT p.* ' . 
			' FROM product as p WHERE id = ' . $this->CI->input->post('product_id') .
			" AND is_delete = 0 AND publish = 1"
		);
		
		$Product = $query->result();
		
		//	沒有找到產品, 就離開
		if (count($Product) <= 0) {
			return FALSE;
		}
		
		//	將產品的庫存加入變數之中 (in_stock 在資料庫中是 int, 不是 Unsigned Int 所以目前可以出現負數)
		$IN_STOCK = $Product[0]->in_stock;
		
		$PRICE = 0;
		
		if ($Product[0]->on_sale == 0) {
			$PRICE = $Product[0]->retail_price;
		}
		
		if ($Product[0]->on_sale == 1) {
			$PRICE = $Product[0]->price;
		}
		
		log_message('debug', "IN_STOCK = $IN_STOCK");
				
		//	是 Pro-Order 的商品
		if ($Product[0]->can_pre_order == 1) {
			
			log_message('debug', "這是 Pre-Order 的商品");
			
			//	商品已經在購物車中
			if ($this->product_item_in_cart($Product[0]->id) == TRUE) {	
				
				log_message('debug', "Systemcart->add2Cart() 更新購物車中的商品 (Pre-Order)");
				
				$items = $this->find_current_cart_itme($this->CI->input->post('product_id'));
				
				if (is_array($items)) {
					
					log_message('debug', $items['qty'] . " " . $this->CI->input->post('qty'));
					
					$QTY = $items['qty'] + $this->CI->input->post('qty');
					
					$Catalogs = $this->CI->db->query("SELECT cid FROM product_rel_catalog WHERE pid = ?", $this->CI->input->post('product_id'))->result();
					foreach ($Catalogs as $item) {
						if ($item->cid == '10009') {
							if ($QTY > 5) {
								return;
							}
						}
					}
					
					if ($QTY > 10) {
						return;
					}
					
					$MyShoppingCart[0]['rowid'] = $items['rowid'];
					$MyShoppingCart[0]['id']    = $items['id'];
					$MyShoppingCart[0]['qty']   = $items['qty'] + $this->CI->input->post('qty');
					$this->CI->cart->update($MyShoppingCart);
				}
				
			}
			
			//	商品已經不在購物車中
			if ($this->product_item_in_cart($Product[0]->id) == FALSE) {	
				log_message('debug', "Systemcart->add2Cart() 新購物車中的商品 (Pre-Order)");
				
				//	購買的數量 - 庫存
				$STOCK = ($IN_STOCK - $QTY);
				
				log_message('debug', "$STOCK = ($IN_STOCK - $QTY)");
				
				//	小於零 
				if ($STOCK < 0) {
					log_message('debug', "Pre-Order $STOCK < 0");
					$this->CI->db->query("UPDATE product SET in_stock = 0 WHERE id = ?", $this->CI->input->post('product_id'));
				}
				
				$MyShoppingCart = array(
					array(
						'id'      => $Product[0]->id,
						'qty'     => $this->CI->input->post('qty'),
						'price'   => $PRICE,
						'name'    => $Product[0]->name,
						'options' => array(
							'Pre-order' => ''
						)
					)
				);
				log_message('debug', "Pre-Order 加入購物車");
				
				//	加入購物車
				$this->CI->cart->insert($MyShoppingCart);
				
			}
			
		}
		
		//	不是 Pre-Order 的商品
		if ($Product[0]->can_pre_order == 0) {
			
			log_message('debug', "不是 Pre-Order 的商品");
						
			//	商品已經在購物車中
			if ($this->product_item_in_cart($Product[0]->id) == TRUE) {
				
				log_message('debug', "Systemcart->add2Cart() 更新購物車中的商品");
				
				$items = $this->find_current_cart_itme($this->CI->input->post('product_id'));
				
				if (is_array($items)) {
					
					$QTY = $items['qty'] + $this->CI->input->post('qty');
					
					$Catalogs = $this->CI->db->query("SELECT cid FROM product_rel_catalog WHERE pid = ?", $this->CI->input->post('product_id'))->result();
					foreach ($Catalogs as $item) {
						if ($item->cid == '10009') {
							if ($QTY > 5) {
								return;
							}
						}
					}
					
					if ($QTY > 10) {
						return;
					}
					
					log_message('debug', $items['qty'] . " " . $this->CI->input->post('qty'));
					
					$MyShoppingCart[0]['rowid'] = $items['rowid'];
					$MyShoppingCart[0]['id']    = $items['id'];
					$MyShoppingCart[0]['qty']   = $items['qty'] + $this->CI->input->post('qty');
					$this->CI->cart->update($MyShoppingCart);
				}
				
			}
			
			//	代表這是新加入購物車中的商品
			if ($this->product_item_in_cart($Product[0]->id) == FALSE) {
				
				log_message('debug', "新的購物車商品");
				
				//	購買的數量 - 庫存
				$STOCK = ($IN_STOCK - $QTY);
				
				log_message('debug', "$STOCK = ($IN_STOCK - $QTY)");
				
				//	小於零 
				if ($STOCK < 0) {
					log_message('debug', "$STOCK < 0");
					return FALSE;
				}
				
				//	扣完數量等於 0
				if ($STOCK >= 0) {
					log_message('debug', "$STOCK >= 0");
					$MyShoppingCart = array(
						array(
							'id'      => $Product[0]->id,
							'qty'     => $this->CI->input->post('qty'),
							'price'   => $PRICE,
							'name'    => $Product[0]->name
						)
					);
					log_message('debug', "加入購物車");
					//	加入購物車
					$this->CI->cart->insert($MyShoppingCart);
				}

				// //	當購買的數量, 大於庫存
				// if ($QTY > $IN_STOCK) {
				// 	log_message('debug', "$STOCK > $IN_STOCK");
				// 	return FALSE;
				// }
				
			}
			
		}
		
		
	}
		
	// public function add2Cart($load_view = TRUE)
	// {
	// 	// 尋找 Product 商品資料, 判斷 is_delete = 0, publish = 1 (發佈 = 1, 不發佈 = 0)
	// 	$query = $this->CI->db->query(
	// 		'SELECT p.* ' . 
	// 		' FROM product as p WHERE id = ' . $this->CI->input->post('product_id') .
	// 		" AND is_delete = 0 AND publish = 1"
	// 	);
	// 	
	// 	$Product = $query->result();
	// 
	// 	// $this->inventory_control($this->CI->input->post('product_id'), $this->CI->input->post('qty'));
	// 					
	// 	if (count($Product) >= 1) {			
	// 		if ($Product[0]->can_pre_order == 1) {
	// 			$MyShoppingCart = array(
	// 				array(
	// 					'id'        => $Product[0]->id,
	// 					'qty'       => $this->CI->input->post('qty'),
	// 					'price'     => $Product[0]->price,
	// 					'name'      => $Product[0]->name,
	// 					'options' => array(
	// 						'Pre-order' => ''
	// 					)
	// 				)
	// 			);				
	// 		} else {
	// 			$MyShoppingCart = array(
	// 				array(
	// 					'id'      => $Product[0]->id,
	// 					'qty'     => $this->CI->input->post('qty'),
	// 					'price'   => $Product[0]->price,
	// 					'name'    => $Product[0]->name
	// 				)
	// 			);
	// 		}
	// 	}
	// 	
	// 	$isExists = FALSE;
	// 	//	檢查產品是否以放入到購物車
	// 	$bol_MAX_UNIT = FALSE;
	// 	
	// 	//	在購物袋中找到對應的產品然後判斷是否要可以加入購物車
	// 	foreach($this->CI->cart->contents() as $items) {
	// 		
	// 		//	找到對映的商品
	// 		if ($items['id'] == $Product[0]->id) {
	// 							
	// 			//	目前的設定是最多 5 筆 
	// 			$MAX_QTY = $items['qty'] + $this->CI->input->post('qty');
	// 			
	// 			// //	檢查是否超過 in_stock
	// 			// if ($items['qty'] >= $Product[0]->in_stock) {
	// 			// 	$items['qty'] = $Product[0]->in_stock - 1;
	// 			// }
	// 			
	// 			if ($Product[0]->can_pre_order == 0) {
	// 				if ($MAX_QTY > $Product[0]->in_stock) {
	// 					$bol_MAX_UNIT = TRUE;
	// 					$MAX_QTY = $Product[0]->in_stock;
	// 				}
	// 			}
	// 			
	// 			if ($MAX_QTY > $this->MAX_UNIT_COUNT) {
	// 				$items['qty'] = 4;
	// 			}
	// 			
	// 			// $this->comparison($items['id'], $this->CI->input->post('qty'));
	// 			
	// 			if ($bol_MAX_UNIT) {
	// 				// $MyShippingCart = array(
	// 				// 	'rowid' => $items['rowid'],
	// 				// 	'qty'   => $MAX_QTY
	// 				// );
	// 				
	// 				// $this->comparison($items['id'], $MAX_QTY);
	// 				
	// 				$MyShoppingCart[0]['rowid'] = $items['rowid'];
	// 				$MyShoppingCart[0]['id']    = $items['id'];
	// 				$MyShoppingCart[0]['qty']   = $MAX_QTY;
	// 				
	// 			} else {
	// 				// $MyShippingCart = array(
	// 				// 	'rowid' => $items['rowid'],
	// 				// 	'qty'   => $items['qty'] + $this->CI->input->post('qty')
	// 				// );
	// 				$MyShoppingCart[0]['rowid'] = $items['rowid'];
	// 				$MyShoppingCart[0]['id']    = $items['id'];
	// 				$MyShoppingCart[0]['qty']   = $items['qty'] + $this->CI->input->post('qty');		
	// 			}
	// 			$isExists = TRUE;
	// 		}
	// 	}
	// 	
	// 	if ($isExists == TRUE) {
	// 		$this->CI->cart->update($MyShoppingCart);
	// 	} else {
	// 		$this->CI->cart->insert($MyShoppingCart);
	// 	}
	// 	
	// 	if ($load_view == TRUE) {
	// 		redirect('viewcart');
	// 	}
	// }
	
	public function update2Cart() {
		$this->CI->cart->update($_POST);
		secure_redirect('viewcart', '');
	}
	
	public function get_product_is_by_rowid($rowid) {
		$p_id = '';
		foreach($this->CI->cart->contents() as $items) {
			if($items['rowid'] == $rowid){
				$p_id = $items['id'];
			}
		}
	
		return $p_id;	
	}
	
	public function remove2cart($rowid = null)
	{
		$MyShoppingCart = array();

        if(is_null($rowid)) {
            $rowid = $this->CI->input->post('rowid');
        }
		
		$p_id = $this->get_product_is_by_rowid($rowid);
		 	
		foreach($this->CI->cart->contents() as $items) {
			if ($items['rowid'] != $rowid and $items['id'] != $p_id) {
				
				if ($this->CI->cart->has_options($items['rowid'])) {
                    $new_item = array(
                    						'id' => $items['id'],
                    						'qty' => $items['qty'],
                    						'price' => $items['price'],
                    						'name' => $items['name'],
                    						'options' => $this->CI->cart->product_options($items['rowid'])
                    					);
					if(array_key_exists('type', $items)) {
                        $new_item['type'] = $items['type'];
                    }

                    array_push($MyShoppingCart, $new_item);

				} else {
                    $new_item = array(
                                'id' => $items['id'],
                                'qty' => $items['qty'],
                                'price' => $items['price'],
                                'name' => $items['name']
                                );
                    if(array_key_exists('type', $items)) {
                        $new_item['type'] = $items['type'];
                    }

                    array_push($MyShoppingCart, $new_item);
				}
			}
		}
		
		// print_r($MyShippingCart);
		$this->CI->cart->destroy();
		$this->CI->cart->insert($MyShoppingCart);
		secure_redirect('viewcart');
	}

	public function ClearCart()
	{
		$this->CI->cart->destroy();
		
		$this->CI->session->unset_userdata('FreeShipping');
		$this->CI->session->unset_userdata('FreeShipping2');
		$this->CI->session->unset_userdata('ProductTax');
		$this->CI->session->unset_userdata('ShippingOptions');
		$this->CI->session->unset_userdata('CalculateShipping');
		$this->CI->session->unset_userdata('DestinationState');
		$this->CI->session->unset_userdata('DiscountCode');
		$this->CI->session->unset_userdata('Discount_Sub_Total');
		$this->CI->session->unset_userdata('Amount');
		
		$this->CI->load->view('continueshopping');
	}
	
	public function add2Wish()
	{
		if ($this->CI->session->userdata('user_id') == '') {
			secure_redirect("signin", 'refresh');
		} else {
			
			$query = $this->CI->db->query(
				"SELECT id FROM wishlist where pid = " . $this->CI->input->post('product_id') .
				" AND uid = " . $this->CI->session->userdata('user_id') .
				" AND is_delete = 0"
			);

			if ($query->num_rows() == 0) {
				$WishList = array(
					'cid' => ($this->CI->input->post('category_id') != "") ? $this->CI->input->post('category_id') : "0",
					'pid' => ($this->CI->input->post('product_id') != "")  ? $this->CI->input->post('product_id')  : "0",
					'uid' => $this->CI->session->userdata('user_id'),
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);

				$this->CI->db->trans_start();
				$this->CI->db->insert('wishlist', $WishList);
				$this->CI->db->trans_complete();
				
				$query = $this->CI->db->query(
					"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->CI->session->userdata('user_id') .
					" AND is_delete = 0"
				);
				$result = $query->result();
				$this->CI->session->set_userdata(array("wishlist_count" => $result[0]->wishlist_count));
				
				secure_redirect("myaccount/wishlist", "refresh");		
			} else {	
				secure_redirect("myaccount/wishlist", "refresh");
			}
		}
	}
	
	//	查詢 ShippingOptions, 
	//	若有找到資料, 將會存在 Session->userdata('ShippingOptions)
	public function ShippingOptions($opt = -1)
	{
		if ($opt == -1) {
			$this->CI->session->set_userdata('ShippingOptions', array());
		} else {
			$Query = $this->CI->db->query("SELECT * FROM shipping_method WHERE id = ? AND is_delete = 0", $opt);
			$ShippingOptions = $Query->result_array();
			if (count($ShippingOptions) >= 1) {
				$this->CI->session->set_userdata('ShippingOptions', $ShippingOptions);
			} else {
				$this->CI->session->set_userdata('ShippingOptions', array());
			}
		}
	}
	
	public function DestinationCountry($country_id = 0)
	{
		$this->CI->session->set_userdata('DestinationCountry', $country_id);
	}

	public function DestinationState($opt = 0)
	{
		if ($opt == -1) {
			$this->CI->session->set_userdata('DestinationState', array());
		} else {
			$Query = $this->CI->db->query("SELECT * FROM tax_codes WHERE id = ?", $opt);
			$DestinationState = $Query->result_array();
			if (count($DestinationState) >= 1) {
				$this->CI->session->set_userdata('DestinationState', $DestinationState);
			} else {
				$this->CI->session->set_userdata('DestinationState', $DestinationState);
			}
		}
	}
	
	public function ListShippingMethod()
	{
		$Query = $this->CI->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 order by `price` asc");
		$result = $Query->result();
		return $result;
	}

	public function OnlineShippingMethod()
	{
		$Query = $this->CI->db->query("SELECT `id`, `name` FROM shipping_method where is_delete = 0 AND id = 99");
		$result = $Query->row();
		return $result;
	}
	
	public function ListDestinationState($country_id)
	{
		$Options = array();
		$Query =
            $this->CI->db->query("SELECT t.id, s.state,
                                         t.state_id,
                                         t.tax_code, t.tax_rate
                                    FROM tax_codes as t, state as s
                                    where is_delete = 0 && s.id = t.state_id && s.country_id = ?
                                    ORDER BY `sharthand` ASC", $country_id);
		$states = $Query->result();
		
		if (count($states) >= 1) {
			$Options = $states;
		}
		return $states;
	}

	public function CalculateShipping()
	{
		if ($this->CI->session->userdata('ShippingOptions') == FALSE) {
			return number_format(0, 2);
		} else {
			
			$shipping_options = $this->CI->session->userdata('ShippingOptions');
			
			if (isset($shipping_options[0]['price'])) {
				$tax_rate = $this->getDestinationState();
				if ($tax_rate == 0) {
					
					// if ($this->CI->session->userdata('FreeShipping')) {
					// 	return number_format(0, 2);
					// } else {
						return number_format($shipping_options[0]['price'], 2);
					// }
					
				} else {
					
					// if ($this->CI->session->userdata('FreeShipping')) {
					// 	return number_format(0, 2);
					// } else {
						return number_format(
							$shipping_options[0]['price'] + ($shipping_options[0]['price'] * ($this->getDestinationState() / 100))
						, 2);
					// }
					
				}
				
			} else {
				return number_format(0, 2);
			}
		}
	}
	
	public function getDestinationState()
	{
		if ($this->CI->session->userdata('DestinationState')) {
			$Dest = $this->CI->session->userdata('DestinationState');
			if (isset($Dest[0]['tax_rate'])) {
				//	這個稅務先取消, 有含稅
				// return number_format($Dest[0]['tax_rate'], 2);
				return number_format(0, 2);
			} else {
				return number_format(0, 2);
			}
		} else {
			return number_format(0, 2);
		}
	}
	
	public function ProductTax()
	{
		$p_order_tax = number_format(0, 2);
		
		if ($this->CI->session->userdata('DestinationState')) {
			$ds = $this->CI->session->userdata('DestinationState');
			//	$this->discount_sub_total 會透過 Promo 來計算若有計算出折扣才會出現, 這個變數很可怕 QQ"
			
			$cart_total = $this->CI->cart->total();
            $voucher_total = 0;

            foreach ($this->CI->cart->contents() as $item) {
                if(array_key_exists('type', $item) && $item['type'] == 'voucher') {
                    $voucher_total += $item['price'];
                }
            }

            $cart_total -= $voucher_total;
			$ds_tax_rate = '';
			if (11 == $ds[0]['id']) {
					$shipping_option = $this->CI->session->userdata('ShippingOptions');
					if($shipping_option[0]['id'] != 99) {
						$ship_zipcode = $this->CI->session->userdata('ship_zipcode');
						
						$this->CI->db->select('*');
						$this->CI->db->from('zipcodes');
						$this->CI->db->where('zipcodes', $ship_zipcode);
						$this->CI->db->where('status', 'Y');
						$zipcodes = $this->CI->db->get()->row();
						if($zipcodes){
							$ds_tax_rate = $zipcodes->taxrate;
						}
					}
			}
			
			if($ds_tax_rate == ''){
				$ds_tax_rate = $ds[0]['tax_rate'];
			}
			
			if (isset($this->discount_sub_total)) {
			   $Discount_Sub_Total_p = $this->discount_sub_total;
			}else {
			   $Discount_Sub_Total_p = $this->CI->session->userdata('Discount_Sub_Total');
			}
            

			if (isset($Discount_Sub_Total_p) && $Discount_Sub_Total_p!="") {
				$p_order_tax = number_format(($cart_total - $Discount_Sub_Total_p) * ($ds_tax_rate / 100), 2);
			} else {
				$p_order_tax = number_format($cart_total * ($ds_tax_rate / 100), 2);
			}
		} 		
		$this->CI->session->set_userdata('ProductTax', $p_order_tax);
		return $p_order_tax;
	}
	
	

    public function checkGiftVouchers($discount = null) {
        $cart_contents = $this->CI->cart->contents();

        if (is_null($discount)) {
            $num_vouchers = 0;
            foreach($cart_contents as $item) {
                if(array_key_exists('type', $item) && $item['type'] == 'voucher') $num_vouchers++;
            }

            if(count($cart_contents) > $num_vouchers)
                return true;
            else
                return false;
        } else {
            $Product_Subtotal = 0;
            foreach($cart_contents as $item) {
                if(!array_key_exists('type', $item) || $item['type'] != 'voucher') {
                    $Product_Subtotal += $item['price']*$item['qty'];
                }
            }

            if($Product_Subtotal >= $discount)
                return true;
            else
                return false;
        }
    }

	public function Promo($DiscountCode = '')
	{
		
		$this->CI->session->unset_userdata('DiscountCode');
		$this->CI->session->unset_userdata('Discount_Sub_Total');

        if(!$this->checkGiftVouchers()) return;
		
		$dt = new DateTime();
		
		//	STEP 1 : 先找到輸入的 Discount Code 是否正確, 
		//	是否介於 release and expiry
		$Query = $this->CI->db->query(
			"SELECT * FROM discountcode WHERE code = " . 
			$this->CI->db->escape($DiscountCode) .
			" AND is_delete = 0 AND enabled = 1" .
			" AND release_timezone <= " . now() .
			" AND expiry_timezone >= " . now()
		);


		$DiscountCode = $Query->result();


		//	STEP 2 : 找到了對應的 Discount Code
		if (count($DiscountCode) >= 1) {

            if($DiscountCode[0]->discount_amount_threshold != 0 && $this->checkGiftVouchers($DiscountCode[0]->discount_amount_threshold) === false) {
                return;
            }


			//	xuses 判斷使用次數
			if ($DiscountCode[0]->xuses > 0) {
				$has_been_used = $this->CI->db->query("SELECT count(id) as 'count' FROM `order` WHERE discount_id = ? AND is_delete = 0", $DiscountCode[0]->id)->result();
				if (count($has_been_used) >= 1) {
					// echo $DiscountCode[0]->xuses.br(1);
					// echo $has_been_used[0]->count;
					if ($has_been_used[0]->count >= $DiscountCode[0]->xuses) {
						return;
					}
				}
			}


			//	STEP 3 : 查詢是否是針對特定商品進行折扣 
			$Query = $this->CI->db->query(
				"SELECT id, d_id, pid FROM discountcode_rel_products " . 
				"WHERE d_id = ? AND is_delete = 0", $DiscountCode[0]->id
			);
			
			$Products = $Query->result();

			$discount_sub_total = 0;
			
			switch($DiscountCode[0]->discount_type) {
				case "1" :
					if (count($Products) >= 1) {
						//	針對產品進行折扣 
						foreach($this->CI->cart->contents() as $item) {
							foreach ($Products as $Product) {
								$Single_Product_Total = ($item['price'] * $item['qty']);
								if ($item['id'] == $Product->pid) {
									//	找到對應的商品, 並計算金額超過 amount threashold 就享有(產品單一)折扣
									if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
										//if ($this->CI->session->userdata('ShippingOptions') == TRUE) {
											$discount_sub_total += $Single_Product_Total * ($DiscountCode[0]->discount_percentage);
										//}
									}
								}
							}
						}
						
						if ($discount_sub_total > 0) {
							//	1 = 可以 Free Shipping, 0 = 不可以
							if ($DiscountCode[0]->can_free_shipping == 1) {
								$this->CI->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);	
							}
							$this->CI->session->unset_userdata('DiscountCode');
							$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
							$this->discount_sub_total = $discount_sub_total;	
						}
						
					} else {
						if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
							
							foreach($this->CI->cart->contents() as $item) {
							
								if($item['type'] != 'voucher'){
									
									$Single_Product_Total = ($item['price'] * $item['qty']);
									$discount_sub_total += $Single_Product_Total * ($DiscountCode[0]->discount_percentage);
									
								}
							}
							
							
							//$discount_sub_total = ($this->CI->cart->total() * ($DiscountCode[0]->discount_percentage));
							
							if ($discount_sub_total > 0) {
								//	1 = 可以 Free Shipping, 0 = 不可以
								if ($DiscountCode[0]->can_free_shipping == 1) {
									$this->CI->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);	
								}
								$this->CI->session->unset_userdata('DiscountCode');
								$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
								$this->discount_sub_total = $discount_sub_total;	
							}
						}
					}					
				break;
				case "2" :
                    if (count($Products) >= 1) {
						//	針對產品進行折扣

                        foreach($this->CI->cart->contents() as $item) {
							foreach ($Products as $Product) {
								$Single_Product_Total = ($item['price'] * $item['qty']);
								if ($item['id'] == $Product->pid) {
                                    //	找到對應的商品, 並計算金額超過 amount threashold 就享有(產品單一)折扣
                                    if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
                                        if ($this->CI->session->userdata('ShippingOptions') == TRUE) {

											//	這個是暫時計算使用, 這樣會害死人 > <"
											$discount_sub_total += ($DiscountCode[0]->discount_percentage);

                                            if($DiscountCode[0]->apply_ones == 1) {
                                                break(2);
                                            }
										}
									}
								}
							}
						}

						if ($discount_sub_total > 0) {
							//	1 = 可以 Free Shipping, 0 = 不可以
							if ($DiscountCode[0]->can_free_shipping == 1) {
								$this->CI->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);	
							}
							$this->CI->session->unset_userdata('DiscountCode');
							$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
							//	若有計算出 Promo 的金額, 才會寫入到 $this->discount_sub_total
							$this->discount_sub_total = $discount_sub_total;	
						}
					
					} else {
						if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
							//	1 = 可以 Free Shipping, 0 = 不可以
							if ($DiscountCode[0]->can_free_shipping == 1) {
								$this->CI->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);	
							}
							$discount_sub_total = ($DiscountCode[0]->discount_percentage);
							if ($discount_sub_total > 0) {
								$this->CI->session->unset_userdata('DiscountCode');
								$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
								$this->discount_sub_total = $discount_sub_total;	
							}	
						}
					}
				break;
				case "3" :
				if (count($Products) >= 1) {
					$bolFreeShipping = FALSE;
					//	針對產品進行折扣 
					foreach($this->CI->cart->contents() as $item) {
						foreach ($Products as $Product) {
							$Single_Product_Total = ($item['price'] * $item['qty']);
							if ($item['id'] == $Product->pid) {
								//	找到對應的商品, 並計算金額超過 amount threashold 就享有(產品單一)折扣
								if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
									$bolFreeShipping = TRUE;
								}
							}
						}
					}
				
					if ($bolFreeShipping == TRUE) {
						//	1 = 可以 Free Shipping, 0 = 不可以
						$this->CI->session->set_userdata('FreeShipping', 1);
						$this->CI->session->unset_userdata('DiscountCode');
						$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
						$this->discount_sub_total = 0;
					}
				
				} else {
					if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
						//	1 = 可以 Free Shipping, 0 = 不可以
						$this->CI->session->set_userdata('FreeShipping', 1);
						$this->CI->session->unset_userdata('DiscountCode');
						$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
						$this->discount_sub_total = 0;
					}
				}
				break;
				case "4" :
				if (count($Products) >= 1) {
                    $boolApplied = false;

					foreach($this->CI->cart->contents() as $item) {
						foreach ($Products as $Product) {
							if ($item['id'] == $Product->pid) {

                                $_product = $this->CI->db->query("SELECT * FROM product WHERE id=?", $item['id'])->row();

                                $FreeProduct = array(
                                    'id' => $item['id'],
                                    'qty' => 1,
                                    'type' => 'buy_one_get_one',
                                    'price' => 0,
                                    'name' => 'FREE ' . $_product->name,
                                    'options' => array(
                                        ' '=>'Free product by promo'
                                    )

                                );

                                $this->CI->cart->insert($FreeProduct);

                                $boolApplied = true;
								if($DiscountCode[0]->apply_ones) {
                                    break;
                                }
							}
						}
					}

					if ($boolApplied) {
						$this->CI->session->unset_userdata('DiscountCode');
						$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
						$this->discount_sub_total = 0;
					}

				}
				break;
				case "5" :
				
				
				//echo $this->CI->cart->total(); 
				$is_apply_code = 'yes';
				if($DiscountCode[0]->gift_with_purchase == 'Y') {
					$is_apply_code = 'yes';
					if($this->CI->cart->total() < $DiscountCode[0]->discount_amount_threshold ) {
						$is_apply_code = 'no';
					}
				}
				
				if($is_apply_code == 'yes' ) {
				  	
					$this->CI->session->set_userdata('discount_amount_threshold', $DiscountCode[0]->discount_amount_threshold);
					
					if (count($Products) >= 1) {
                    $boolApplied = false;
                    if ($DiscountCode[0]->can_free_shipping == 1) {
                        $this->CI->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);
                    }

					foreach($this->CI->cart->contents() as $item) {
						foreach ($Products as $Product) {
							if ($item['id'] == $Product->pid) {
							
								//echo $DiscountCode[0]->id; die;

                                $_product = $this->CI->db->query("SELECT * FROM product as p, discountcode_rel_gift as d
                                                                           WHERE d.d_id=? AND p.id=d.p_id", $DiscountCode[0]->id)->row();
								
                                $FreeProduct = array(
                                    'id' => $_product->p_id,
                                    'qty' => 1,
                                    'type' => 'free_gift',
                                    'price' => 0,
                                    'name' => 'GIFT ' . $_product->name,
                                    'options' => array(
                                        ' '=>'Gift product by promo'
                                    )

                                );

                                $this->CI->cart->insert($FreeProduct);

                                $boolApplied = true;
								if($DiscountCode[0]->apply_ones) {
                                    break;
                                }
							}
						}
					}

					if ($boolApplied) {
						$this->CI->session->unset_userdata('DiscountCode');
						$this->CI->session->set_userdata('DiscountCode', $DiscountCode);
						$this->discount_sub_total = 0;
					}

				} else {
                    if ($this->CI->cart->total() >= $DiscountCode[0]->discount_amount_threshold) {
                        if ($DiscountCode[0]->can_free_shipping == 1) {
                            $this->CI->session->set_userdata('FreeShipping', $DiscountCode[0]->can_free_shipping);
                        }
						
						//echo $DiscountCode[0]->id; die;

                        $_product = $this->CI->db->query("SELECT * FROM product as p, discountcode_rel_gift as d
                                                            WHERE d.d_id=? AND p.id=d.p_id", $DiscountCode[0]->id)->row();

                        $FreeProduct = array(
                            'id' => $_product->p_id,
                            'qty' => 1,
                            'type' => 'free_gift',
                            'price' => 0,
                            'name' => 'GIFT ' . $_product->name,
                            'options' => array(
                                ' '=>'Gift product by promo'
                            )

                        );
						
						

                        $this->CI->cart->insert($FreeProduct);

                        $this->CI->session->unset_userdata('DiscountCode');
                        $this->CI->session->set_userdata('DiscountCode', $DiscountCode);
                        $this->discount_sub_total = 0;

                    }
                }
				}		
					
				break;
			}
		}		
	}

	public function Voucher($VoucherCode = '')
	{
		$this->CI->session->unset_userdata('VoucherCode');
		$this->CI->session->unset_userdata('Voucher_Sub_Total');

        if(empty($VoucherCode)) {
            return;
        }


		$Query = $this->CI->db->query("SELECT * FROM order_voucher_details WHERE code = ". $this->CI->db->escape($VoucherCode) ." AND is_delete = 0");

		$VoucherCode = $Query->result();

		if (count($VoucherCode) >= 1 && $VoucherCode[0]->balance > 0) {

			$voucher_sub_total = 0;
            //if ($this->CI->session->userdata('ShippingOptions') == TRUE) {
                $sum = $this->Sum();
                if($sum > $VoucherCode[0]->balance) {
                    $voucher_sub_total = $VoucherCode[0]->balance;
                } else {
                    $voucher_sub_total = $sum;
                    $voucher_balance = $VoucherCode[0]->balance - $sum;
                    if($voucher_balance > 0) {
                        $this->CI->session->set_userdata('VoucherBalance', $voucher_balance);
                    }else {
						$this->CI->session->unset_userdata('VoucherBalance');
					}
                }

                $this->CI->session->unset_userdata('VoucherCode');
                $this->CI->session->set_userdata('VoucherCode', $VoucherCode);

                $this->voucher_sub_total = $voucher_sub_total;
            //}
		}
	}
	
	public function FreeShipping($s_method=NULL)
	{
		$dt = new DateTime();		
		// $dt = date_create();
		$SubTotal = $this->CI->cart->total() + $this->ProductTax();

        $cart_total = $this->CI->cart->total();
        $voucher_total = 0;

        foreach ($this->CI->cart->contents() as $item) {
            if(array_key_exists('type', $item) && $item['type'] == 'voucher') {
                $voucher_total += $item['price'];
            }
        }

        $cart_total -= $voucher_total;

		$this->CI->db->select('*');
		$this->CI->db->from('freeshipping');
		$this->CI->db->where('x_dollar_amount <=', $cart_total);
		$this->CI->db->where('is_delete', 0);
		if(isset($s_method) && $s_method!="") {
			$this->CI->db->where('shipping_method =', $s_method);
		}
		$this->CI->db->where('release_timezone <=', now());
		$this->CI->db->where('expiry_timezone >=', now());
		//echo $this->CI->db->_compile_select();
		// $this->db->where('release_timezone <=', $dt->getTimestamp());
		// $this->db->where('expiry_timezone >=', $dt->getTimestamp());
		
		// $this->db->where('release_timezone <=', date_timestamp_get($dt));
		// $this->db->where('expiry_timezone >=',  date_timestamp_get($dt));
		
		$can_FreeShipping = $this->CI->db->get()->result_array();

        //var_dump($can_FreeShipping);
		if (count($can_FreeShipping) >= 1) {
			if(isset($s_method) && $s_method!="") {
			  if($s_method == 3){
				$this->CI->session->set_userdata('FreeShipping2', $s_method);
			   }	
				$this->CI->session->set_userdata('FreeShipping2_DB', $s_method);
			}else {
				$this->CI->session->set_userdata('FreeShipping2', 0);
			}
			return TRUE;
		} else {
			$this->CI->session->set_userdata('FreeShipping2', 0);
			return FALSE;
		}
	}



	/**
	 * 我知道這樣改很恐怖, 7.95 減來減去, 很可怕的事 天阿, 希望不要在改了, 等有時間還要在整理這幾個 Class, 救救我阿 !
	 */
	public function Sum()
	{
		$this->FreeShipping = $this->CI->session->userdata('FreeShipping');
		$IS_FREE_SHIPPING2 = ($this->CI->session->userdata('FreeShipping2') ? $this->CI->session->userdata('FreeShipping2') : 0);
				
		$Cal_Shipping = 0;
		
		if ($this->CalculateShipping() > 0) {
			$Cal_Shipping = $this->CalculateShipping();
		}
				
		if (isset($this->FreeShipping) && ($this->FreeShipping == TRUE)) {
			if ($Cal_Shipping == 4.95) {
				$Sum = $this->CI->cart->total() + $this->CI->ShoppingCart->ProductTax();
			} else {
				$Sum = $this->CI->cart->total() + $this->CI->ShoppingCart->ProductTax() + $Cal_Shipping;
			}
		} else {
			$Sum = $this->CI->cart->total() + $this->ProductTax() + $this->CalculateShipping();
		}
	
		if ($IS_FREE_SHIPPING2 > 0) {
			if (isset($Cal_Shipping) && $Cal_Shipping != '') {
				$Sum = $this->CI->cart->total() + $this->CI->ShoppingCart->ProductTax();
			} else {
				$Sum = $this->CI->cart->total() + $this->CI->ShoppingCart->ProductTax() + $Cal_Shipping;
			}
		}
				
		if (isset($this->discount_sub_total)) {
			$this->CI->session->set_userdata('Discount_Sub_Total', $this->discount_sub_total);
			$Sum -= $this->discount_sub_total;
			
			
			//	當有輸入 Discount 後折扣小於零, 就要變成零, 客戶要的, 會有問題
			if ($Sum <= 0) {
				$Sum = 0;
			}
			
		}

		if (isset($this->voucher_sub_total)) {
			$this->CI->session->set_userdata('Voucher_Sub_Total', $this->voucher_sub_total);
			$Sum -= $this->voucher_sub_total;


			if ($Sum <= 0) {
				$Sum = 0;
			}

		}
		
		return number_format($Sum, 2);
	}
	


	
}