<?php

/**
* 
*/
class Ingredients extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
	
		if ($_POST) {
			if ($_POST['action'] == "order" && $_POST['id'] != "") {	//	Save Order.
				$this->_save_order();				
			} else if ($_POST['action'] == "remove" && $_POST['id'] != "") {	//	Remove Item.
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
		
		$result = $this->db->query("SELECT * FROM product_ingredients WHERE is_delete = 0 ORDER BY ordering asc");
		$this->ingredients = $result->result();
		
		$this->load->view('ingredients');
	}
	
	public function addnew()
	{
		$this->load->view('ingredients_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('image', 'Symbol Key image', 'required');
		$this->form_validation->set_rules('description', 'Description');
		return $this->form_validation->run();
	}
	
	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('ingredients_addnew');
			return;
		}
		
		$Symbolkey = array(
			'image'        => $this->input->post('image'),
			'title'        => $this->input->post('title'),
			'description'  => $this->input->post('description'),
			'publish'      => $this->input->post('publish'),
			'created_at'   => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('product_ingredients', $Symbolkey);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'ingredients->save() 交易錯誤');
		}
		
		redirect('ingredients/success', 'refresh');
	}
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('product_ingredients', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
		
	}
	
	public function success()
	{
		$this->message = "Ingredients Saved Successfully";
		$this->index();
	}

	public function _update($ndx)
	{		
		$result = $this->db->query("SELECT * FROM product_ingredients WHERE is_delete = 0 and id = " . $ndx);
		$this->ingredients = $result->result();
		$this->load->view('ingredients_update');
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update product_ingredients set is_delete = 1, updated_at = \'' . 
						unix_to_human(time(), TRUE, 'us') . '\' where id = ' . $id);	
					$numrows += $this->db->affected_rows();
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
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$symbol = array(
			'image'        => $this->input->post('image'),
			'title'        => $this->input->post('title'),
			'description'  => $this->input->post('description'),
			'publish'      => $this->input->post('publish'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('product_ingredients', $symbol);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'ingredients->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}
	
	public function _save_order()
	{
		$numrows = 0;
		if (count($_POST['lists']) != count($_POST['order'])) {
			$this->message = "Oops 排序資料不同步";
		} else {
			$lists  = $_POST['lists'];
			$orders = $_POST['order'];
			$i = 0;
			foreach ($lists as $list) {
				$Ordering = array('ordering' => $orders[$i]);
				$this->db->where('id', $list);
				$this->db->update('product_ingredients', $Ordering);
				
				$numrows += $this->db->affected_rows();
				// echo "$q";
				$i++;
			}
			if ($numrows != 0) {
				$this->message =  $numrows . " records updated";	
			}
		}

	}
	
}