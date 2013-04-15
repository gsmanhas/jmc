<?php

/**
* 
*/
class Ajax_shippingbag extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->ShoppingCart = new SystemCart();
	}
		
	public function index()
	{
		if (!isset($_POST['method'])) {
			echo json_encode(array('error_404' => 'page not find'));
		}
		
		switch($_POST['method']) {
			case "add" :
				$this->_add2Cart();
			break;
			case "wish" :
				$this->_addwish();
			break;
		}
	}
	
	public function _add2Cart()
	{
		$return_stock = 0;
		$return_cna_pre_order = 0;
		
		$this->ShoppingCart->add2Cart(FALSE);
		
		$stock = $this->db->query("SELECT in_stock, can_pre_order FROM product WHERE id = ?", $this->input->post('product_id'))->result();
		if (count($stock) >= 1) {
			$return_stock = (int)$stock[0]->in_stock;
			$return_cna_pre_order = (int)$stock[0]->can_pre_order;
		}
		
		$total_items = $this->cart->total_qtys();
		
		// sleep(2);
		
		echo json_encode(
			array(
				'success'              => true,
				'total_items'          => $total_items,
				'qty'                  => (int)$this->ShoppingCart->get_cart_item_qty($this->input->post('product_id')),
				'return_stock'         => $return_stock,
				'return_cna_pre_order' => $return_cna_pre_order
			)
		);
	}
	
	public function _add2Cart2()
	{
		// 尋找 Product 商品資料, 判斷 is_delete = 0, publish = 1 (發佈 = 1, 不發佈 = 0)
		$query = $this->db->query(
			'SELECT p.* ' . 
			// ',(SELECT in_stock FROM inventory as i WHERE i.pid = p.id AND is_delete = 0) as `in_stock` ' .
			' FROM product as p WHERE id = ' . $this->input->post('product_id') .
			" AND is_delete = 0 AND publish = 1"
		);
		
		$Product = $query->result(); 
		
		// print_r($Product);
		
		//	若有找到 Product 就加入到 MyShippingCart
		if (count($Product) >= 1) {
			
			//	可以 Pre-Order
			if ($Product[0]->can_pre_order == 1) {
				
				
				
				$MyShippingCart = array(
					array(
						'id'        => $Product[0]->id,
						'qty'       => $this->input->post('qty'),
						'price'     => $Product[0]->price,
						'name'      => $Product[0]->name,
						'options' => array(
							'Pre-order' => ''
						)
					)
				);
			} else {
				$MyShippingCart = array(
					array(
						'id'      => $Product[0]->id,
						'qty'     => $this->input->post('qty'),
						'price'   => $Product[0]->price,
						'name'    => $Product[0]->name
					)
				);
			}
					
			$isExists = FALSE;
			//	檢查產品是否以放入到購物車
			$bol_MAX_UNIT = FALSE;
			foreach($this->cart->contents() as $items) {
				if ($items['id'] == $Product[0]->id) {
					//	目前的設定是最多 5 筆 
					$MAX_QTY = $items['qty'] + $this->input->post('qty');
					
					// //	檢查是否超過 in_stock
					// if ($items['qty'] >= $Product[0]->in_stock) {
					// 	$items['qty'] = $Product[0]->in_stock - 1;
					// }
					
					if ($Product[0]->can_pre_order == 0) {
						if ($MAX_QTY > $Product[0]->in_stock) {
							$bol_MAX_UNIT = TRUE;
							$MAX_QTY = $Product[0]->in_stock;
						}
					}
					
					if ($MAX_QTY > 5) {
						$items['qty'] = 4;
					}
					
					if ($bol_MAX_UNIT) {
						$MyShippingCart = array(
							'rowid' => $items['rowid'],
							'qty'   => $MAX_QTY
						);
					} else {
						$MyShippingCart = array(
							'rowid' => $items['rowid'],
							'qty'   => $items['qty'] + $this->input->post('qty')
						);						
					}

					
					$isExists = TRUE;
				}
			}
		
			if ($isExists == TRUE) {
				$this->cart->update($MyShippingCart);
			} else {
				$this->cart->insert($MyShippingCart);
			}
		}
		
		// sleep(1);
		
		$total_items = 0;
		foreach($this->cart->contents() as $items) {
			$total_items += $items['qty'];
		}
		
		echo json_encode(
			array(
				'success' => true,
				'total_items' => $total_items
			)
		);
		
	}

	public function _addwish()
	{
		$query = $this->db->query(
			"SELECT id FROM wishlist where pid = " . $this->input->post('product_id') .
			" AND uid = " . $this->session->userdata('user_id') .
			" AND is_delete = 0"
		);

		if ($query->num_rows() == 0) {
			$WishList = array(
				'cid' => ($this->input->post('category_id') != "") ? $this->input->post('category_id') : "0",
				'pid' => ($this->input->post('product_id') != "")  ? $this->input->post('product_id')  : "0",
				'uid' => $this->session->userdata('user_id'),
				'created_at' => unix_to_human(time(), TRUE, 'us')
			);

			$this->db->trans_start();
			$this->db->insert('wishlist', $WishList);
			$this->db->trans_complete();
			
			$query = $this->db->query(
				"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->session->userdata('user_id') .
				" AND is_delete = 0"
			);
			$result = $query->result();
			$this->session->set_userdata(array("wishlist_count" => $result[0]->wishlist_count));
			
			echo json_encode(
				array(
					'success' => true,
					'total_items' => $result[0]->wishlist_count
				)
			);
			
		} else {	
			echo json_encode(
				array(
					'success' => true,
					'total_items' => 0
				)
			);
		}
	}

}