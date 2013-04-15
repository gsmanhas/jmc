<?php

/**
* 
*/
class Swatch extends CI_Controller
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

		$result = $this->db->query("SELECT * FROM product_swatch WHERE is_delete = 0 ORDER BY ordering asc");
		$this->swatches = $result->result();
		
		$this->load->view('swatch');	
	}
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('product_swatch', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";	
	}
	
	public function addnew()
	{
		$this->load->view('swatch_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('image', 'Swatch Image', 'required');
		$this->form_validation->set_rules('description', 'Description');
		return $this->form_validation->run();
	}
	
	public function save()
	{		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('swatch_addnew');
			return;
		}
		
		$Swatch = array(
			'image'       => $this->input->post('image'),
			'title'       => $this->input->post('title'),
			'publish'     => $this->input->post('publish'),
			'created_at'  => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('product_swatch', $Swatch);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Swatch->save() 交易錯誤');
		}
		
		redirect('swatch/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Swatch Saved Successfully";
		$this->index();
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update product_swatch set is_delete = 1, updated_at = \'' . 
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
		$result = $this->db->query("SELECT * FROM product_swatch WHERE is_delete = 0 and id = " . $ndx);
		$this->swatch = $result->result();
		$this->load->view('swatch_update');
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$Swatch = array(
			'image'       => $this->input->post('image'),
			'title'        => $this->input->post('title'),
			'publish'    => $this->input->post('publish'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('product_swatch', $Swatch);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Swatch->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
		
	}
	
}