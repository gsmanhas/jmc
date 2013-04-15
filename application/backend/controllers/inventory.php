<?php

/**
* 
*/
class Inventory extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
		$order_by = 'product_name';
		
		if ($_POST) {
			
			if ($_POST['action'] == "single_update") {
				$this->single_update();
			} else if ($_POST['action'] == "saveAll") {
				$this->saveAll();
			}
			
			if ($_POST['action'] == "byname") {
				$order_by = 'product_name';
			} else if ($_POST['action'] == "bystock") {
				$order_by = 'p.in_stock';
			}
			
		}
		
		$result = $this->db->query(
			"SELECT p.id, p.name as 'product_name', p.can_pre_order, p.in_stock " .
			"FROM product as p " .
			"WHERE is_delete = 0 ORDER BY " . $order_by . " asc"
		);
		
		$this->Inventory = $result->result_array();
		
		$this->load->view('inventory');
	}
	
	public function single_update()
	{	
		
		$numrows = 0;
		
		//	最好是有庫存記錄,
		$Qty = $this->input->post("qty_" . $this->input->post('id'));
		$Can_Pre_Order = $this->input->post("can_pre_order_" . $this->input->post('id'));
		
		if (is_numeric($Qty) && ($Qty >= 0)) {
			
			$this->db->trans_start();
			if ($Can_Pre_Order == 1) {
				$product = array(
					'can_pre_order' => 1,
					'in_stock' => $Qty
				);
				$this->db->where('id', $this->input->post('id'));
				$this->db->update('product', $product);
			} else {
				$product = array(
					'can_pre_order' => 0,
					'in_stock' => $Qty
				);
				$this->db->where('id', $this->input->post('id'));
				$this->db->update('product', $product);
			}
			
			$numrows += $this->db->affected_rows();
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE) {
				$this->error_message = "Inventory Save Item, 發生了異常的動作, 系統取消了上次的動作";
				$this->db->trans_rollback();
			} else {
				if ($numrows != 0) {
					$this->message =  $numrows . " records updated";	
				}
			}
		}
		
	}
	
	public function saveAll()
	{
		$numrows = 0;
		if ($this->input->post('id') != "") {
			$ids = explode(',', $this->input->post('id'));
			if (is_array($ids) && (count($ids) >= 1)) {
				
				$this->db->trans_start();
				
				foreach ($ids as $id) {
					if (is_numeric($this->input->post('qty_'.$id)) && ($this->input->post('qty_'.$id) >= 0)) {
						
						// $Can_Pre_Order = $this->input->post("can_pre_order_".$id);
						$Can_Pre_Order = (isset($_POST["can_pre_order_".$id])) ? $_POST["can_pre_order_".$id] : 0;
						
						$In_Stock = array(
							'in_stock' => $this->input->post('qty_'.$id),
							'can_pre_order' => $Can_Pre_Order,
							'updated_at' => unix_to_human(time(), TRUE, 'us')
						);
						$this->db->where('id', $id);
						$this->db->update('product', $In_Stock);
						$numrows += $this->db->affected_rows();
					}
				}		
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE) {
					$this->error_message = "Inventory Save All, 發生了異常的動作, 系統取消了上次的動作";
					$this->db->trans_rollback();
				} else {
					if ($numrows != 0) {
						$this->message =  $numrows . " records updated";	
					}
				}
			}
		}
	}
	
}




