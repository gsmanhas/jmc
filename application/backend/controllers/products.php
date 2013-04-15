<?php

class Products extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{			
		//	IS_POSTBACK
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
				
		$result = $this->db->query("SELECT * FROM product WHERE is_delete = 0 ORDER BY id asc");
		$this->products = $result->result();
		
		
		$this->load->view('products');
	}
	
	public function addnew()
	{
		$this->load->view('products_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('url', 'Page URL', 'required|min_length[1]|max_length[255]|alpha_dash|unique[product.url]');
		$this->form_validation->set_rules('name', 'Product Name', 'required|regex_match[/^[\/\.\:\,\%\+\-_ a-z0-9\(\)&]+$/i]');
        $this->form_validation->set_message('regex_match', 'The Product Name field is not in the correct format (The name can only contain alpha-numeric characters, dashes, underscores, colons, and spaces).');
		$this->form_validation->set_rules('title', 'Title', 'required');
		
		$this->form_validation->set_rules('small_image', 'Small Image', 'required');
		$this->form_validation->set_rules('large_image', 'Large Image', 'required');
		
		$this->form_validation->set_rules('sku', 'SKU', 'required|min_length[1]|max_length[255]|unique[product.sku]');
		//$this->form_validation->set_rules('item_number', 'Item Number', 'required|min_length[1]|max_length[255]|unique[product.item_number]');
		$this->form_validation->set_rules('retail_price', 'Regular Price', 'required|numeric');
		$this->form_validation->set_rules('price', 'Discounted Price', 'required|numeric');

		$this->form_validation->set_rules('category_id', 'Category', 'required');
		
		if ($_POST['swatche'] == "0") {
			
		} else {
			$this->form_validation->set_rules('swatche_name', 'Swatch Name', 'required');
			$this->form_validation->set_rules('swatche_title', 'Swatch Description', 'required');
			$this->form_validation->set_rules('color', 'Swatch Color', 'required');
		}
		
		return $this->form_validation->run();
	}
	
	public function save()
	{			
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('products_addnew');
			return;
		}
				
		$this->swatche_id = $this->input->post('swatche');
		
		if ($this->swatche_id != 0) {
			$Product = array(
				'name'         => $this->input->post('name'),
				'title'        => $this->input->post('title'),
				'list_desc'    => $this->input->post('list_desc'),
				'tips'         => $this->input->post('tips'),
				'description'  => $this->input->post('description'),
				'small_image'  => $this->input->post('small_image'),
				'large_image'  => $this->input->post('large_image'),
				'sku'          => $this->input->post('sku'),
				'item_number'  => $this->input->post('item_number'),
				'url'          => $this->input->post('url'),
				'price'        => $this->input->post('price'),
				'retail_price' => $this->input->post('retail_price'),
				'on_sale'      => (isset($_POST['on_sale']) ? "1" : "0"),
				'publish'      => $this->input->post('publish'),
				'swatch_id'    => $this->input->post('swatche'),
				'swatch_name'  => $this->input->post('swatche_name'),
				'swatch_title' => $this->input->post('swatche_title'),
				'how_to_use'   => $this->input->post('how_to_use'),
				'ingredient'   => $this->input->post('ingredient'),
 				'color'        => $this->input->post('color'),
				'video_path'   => $this->input->post('video_path'),
				'created_at'   => unix_to_human(time(), TRUE, 'us')
			);
		} else {
			$Product = array(
				'name'         => $this->input->post('name'),
				'title'        => $this->input->post('title'),
				'list_desc'    => $this->input->post('list_desc'),
				'tips'         => $this->input->post('tips'),
				'description'  => $this->input->post('description'),
				'small_image'  => $this->input->post('small_image'),
				'large_image'  => $this->input->post('large_image'),
				'sku'          => $this->input->post('sku'),
				'item_number'  => $this->input->post('item_number'),
				'url'          => $this->input->post('url'),
				'price'        => $this->input->post('price'),
				'retail_price' => $this->input->post('retail_price'),
				'on_sale'      => (isset($_POST['on_sale']) ? "1" : "0"),
				'how_to_use'   => $this->input->post('how_to_use'),
				'ingredient'   => $this->input->post('ingredient'),
				'publish'      => $this->input->post('publish'),
				'swatch_id'    => $this->input->post('swatche'),
				'video_path'   => $this->input->post('video_path'),
				'created_at'   => unix_to_human(time(), TRUE, 'us')
			);
		}
		
		$this->db->trans_start();
		$this->db->insert('product', $Product);
		
		$result = $this->db->query("SELECT id FROM product WHERE is_delete = 0 order by id desc limit 1");
		$newProduct = $result->result();
		
		if (is_array($this->input->post('category_id'))) {
			foreach ($this->input->post('category_id') as $value) {
				$Product_rel_Catalogs = array(
					'pid'        => $newProduct[0]->id,
					'cid'        => $value,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('product_rel_catalog', $Product_rel_Catalogs);
			}
		}
		
		if (is_array($this->input->post('symbolkey_id'))) {
			foreach ($this->input->post('symbolkey_id') as $value) {
				$Product_rel_Symbolkey = array(
					'pid'        => $newProduct[0]->id,
					'sid'        => $value,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('product_rel_symbolkey', $Product_rel_Symbolkey);
			}
		}
		
		// if (is_array($this->input->post('ingredient_id'))) {
		// 	foreach ($this->input->post('ingredient_id') as $value) {
		// 		$product_rel_ingredients = array(
		// 			'pid'        => $newProduct[0]->id,
		// 			'ing_id'     => $value,
		// 			'created_at' => unix_to_human(time(), TRUE, 'us')
		// 		);
		// 		$this->db->insert('product_rel_ingredients', $product_rel_ingredients);
		// 	}
		// }
		
		$Inventory = array(
			'pid'          => $newProduct[0]->id,
			'in_stock'     => 0,
			'created_at'   => unix_to_human(time(), TRUE, 'us')
		);
		$this->db->insert('inventory', $Inventory);

		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Product->save() 交易錯誤');
		}
		
		redirect('products/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Product";
		$this->index();
	}

	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('product', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
	}

	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update product set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where id = ' . $id);	
						
					$numrows += $this->db->affected_rows();
					
					$this->db->query('update product_rel_catalog set is_delete = 1 where pid = ' . $id);
					$this->db->query('update product_rel_symbolkey set is_delete = 1 where pid = ' . $id);
					$this->db->query('update inventory set is_delete = 1 where pid = ' . $id);
					$this->db->query("DELETE FROM `product_group_by` WHERE `pid` ={$id} || `with_id` ={$id}");
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

	private function _update_submit_validate()
	{
		//	這的 SUK & URL 需要寫成 AJAX 先告知使用者這是唯一的變數
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('url', 'Page URL', 'required|min_length[1]|max_length[255]|alpha_dash');
		$this->form_validation->set_rules('name', 'Product Name', 'required');
		$this->form_validation->set_rules('title', 'Title', 'required');
		
		$this->form_validation->set_rules('small_image', 'Small Image', 'required');
		$this->form_validation->set_rules('large_image', 'Large Image', 'required');
		
		$this->form_validation->set_rules('sku', 'SKU', 'required|min_length[1]|max_length[255]');
		$this->form_validation->set_rules('retail_price', 'Regular Price', 'required|numeric');
		$this->form_validation->set_rules('price', 'Discounted Price', 'required|numeric');

		$this->form_validation->set_rules('category_id', 'Category', 'required');
		
		if ($_POST['swatche'] == "0") {
			
		} else {
			$this->form_validation->set_rules('swatche_name', 'Swatch Name', 'required');
			$this->form_validation->set_rules('swatche_title', 'Swatch Description', 'required');
			$this->form_validation->set_rules('color', 'Swatch Color', 'required');
		}
		
		return $this->form_validation->run();
	}

	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM product WHERE is_delete = 0 and id = " . $ndx);
		$this->products = $result->result();
		$this->load->view('products_update');
	}	

	public function _update_save()
	{
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$this->swatche_id = $this->input->post('swatche');
		
		if ($this->swatche_id != 0) {
			$Product = array(
				'name'         => $this->input->post('name'),
				'title'        => $this->input->post('title'),
				'list_desc'    => $this->input->post('list_desc'),
				'tips'         => $this->input->post('tips'),
				'description'  => $this->input->post('description'),
				'small_image'  => $this->input->post('small_image'),
				'large_image'  => $this->input->post('large_image'),
				'sku'          => $this->input->post('sku'),
				'item_number'  => $this->input->post('item_number'),
				'url'          => $this->input->post('url'),
				'price'        => $this->input->post('price'),
				'retail_price' => $this->input->post('retail_price'),
				'on_sale'      => (isset($_POST['on_sale']) ? "1" : "0"),
				'publish'      => $this->input->post('publish'),
				'swatch_id'    => $this->input->post('swatche'),
				'swatch_name'  => $this->input->post('swatche_name'),
				'swatch_title' => $this->input->post('swatche_title'),
				'how_to_use'   => $this->input->post('how_to_use'),
				'ingredient'   => $this->input->post('ingredient'),
				'color'        => $this->input->post('color'),
				'video_path'   => $this->input->post('video_path'),
				'created_at'   => unix_to_human(time(), TRUE, 'us')
			);
		} else {
			$Product = array(
				'name'         => $this->input->post('name'),
				'title'        => $this->input->post('title'),
				'list_desc'    => $this->input->post('list_desc'),
				'tips'         => $this->input->post('tips'),
				'description'  => $this->input->post('description'),
				'small_image'  => $this->input->post('small_image'),
				'large_image'  => $this->input->post('large_image'),
				'sku'          => $this->input->post('sku'),
				'item_number'  => $this->input->post('item_number'),
				'url'          => $this->input->post('url'),
				'price'        => $this->input->post('price'),
				'retail_price' => $this->input->post('retail_price'),
				'on_sale'      => (isset($_POST['on_sale']) ? "1" : "0"),
				'publish'      => $this->input->post('publish'),
				'how_to_use'   => $this->input->post('how_to_use'),
				'ingredient'   => $this->input->post('ingredient'),
				'swatch_id'    => $this->input->post('swatche'),
				'video_path'   => $this->input->post('video_path'),
				'created_at'   => unix_to_human(time(), TRUE, 'us')
			);
		}
		
		
		$this->db->trans_start();
		
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('product', $Product);
		
		//	先取出 Sorting 的資料及SHOW|HIDE 的舊設定
		$product_rel_catalog_sotring = $this->db->query("SELECT cid, sorting, show_it FROM product_rel_catalog WHERE pid = ?", $this->input->post('id'))->result();
		
		$this->db->query("delete from product_rel_catalog where pid = ?", $this->input->post('id'));
		$this->db->query("delete from product_rel_symbolkey where pid = ?", $this->input->post('id'));
		$this->db->query("delete from product_rel_ingredients where pid = ?", $this->input->post('id'));
		
		if (is_array($this->input->post('category_id'))) {
			foreach ($this->input->post('category_id') as $value) {
				$bol = FALSE;
				foreach ($product_rel_catalog_sotring as $item) {
					if ($item->cid == $value) {
						
						// echo $value. " " . $item->show_it . " " . $item->sorting .br(1);
						
						$Product_rel_Catalogs = array(
							'pid'        => $this->input->post('id'),
							'cid'        => $value,
							'show_it'    => $item->show_it,
							'sorting'    => $item->sorting,
							'created_at' => unix_to_human(time(), TRUE, 'us')
						);
						
						// print_r($Product_rel_Catalogs);
						$bol = TRUE;
					}
					
				}
			
				if ($bol == FALSE) {
					$Product_rel_Catalogs = array(
						'pid'        => $this->input->post('id'),
						'cid'        => $value,
						'created_at' => unix_to_human(time(), TRUE, 'us')
					);
				}
			
				$this->db->insert('product_rel_catalog', $Product_rel_Catalogs);
			}
		}
		
		// exit;
		
		if (is_array($this->input->post('symbolkey_id'))) {
			foreach ($this->input->post('symbolkey_id') as $value) {
				$Product_rel_Symbolkey = array(
					'pid'        => $this->input->post('id'),
					'sid'        => $value,
					'created_at' => unix_to_human(time(), TRUE, 'us')
				);
				$this->db->insert('product_rel_symbolkey', $Product_rel_Symbolkey);
			}
		}
		
		// if (is_array($this->input->post('ingredient_id'))) {
		// 	foreach ($this->input->post('ingredient_id') as $value) {
		// 		$product_rel_ingredients = array(
		// 			'pid'        => $this->input->post('id'),
		// 			'ing_id'        => $value,
		// 			'created_at' => unix_to_human(time(), TRUE, 'us')
		// 		);
		// 		$this->db->insert('product_rel_ingredients', $product_rel_ingredients);
		// 	}
		// }
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Product->update() 交易錯誤');
		}
		
		$this->update_message = "1 records updated";
		return true;
	}

}