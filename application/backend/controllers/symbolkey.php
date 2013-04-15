<?php

/**
* 
*/
class Symbolkey extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{	
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

		$result = $this->db->query("SELECT * FROM product_symbolkey WHERE is_delete = 0 ORDER BY title asc");
		$this->symbolkeys = $result->result();
		
		$this->load->view('symbolkey');
				
	}
	
	public function addnew()
	{
		$this->load->view('symbolkey_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('image', 'Image', 'required');
		$this->form_validation->set_rules('large_image', 'Large Image', 'required');
		$this->form_validation->set_rules('description', 'Description');
		return $this->form_validation->run();
	}
	
	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('symbolkey_addnew');
			return;
		}
		
		$Symbolkey = array(
			'image'        => $this->input->post('image'),
			'large_image'  => $this->input->post('large_image'),
			'title'        => $this->input->post('title'),
			'description'  => $this->input->post('description'),
			'publish'      => $this->input->post('publish'),
			'created_at'   => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('product_symbolkey', $Symbolkey);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Symbolkey->save() 交易錯誤');
		}
		
		redirect('symbolkey/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Symbol Key Saved Successfully";
		$this->index();
	}
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('product_symbolkey', $Publish);
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
					$this->db->query('update product_symbolkey set is_delete = 1, updated_at = \'' . 
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
	
	public function _update($ndx)
	{		
		$result = $this->db->query("SELECT * FROM product_symbolkey WHERE is_delete = 0 and id = " . $ndx);
		$this->symbol = $result->result();
		$this->load->view('symbolkey_update');
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$symbol = array(
			'image'        => $this->input->post('image'),
			'large_image'  => $this->input->post('large_image'),
			'title'        => $this->input->post('title'),
			'description'  => $this->input->post('description'),
			'publish'      => $this->input->post('publish'),
			'updated_at'   => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('product_symbolkey', $symbol);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Symbolkey->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}
	
}