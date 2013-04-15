<?php

/**
* 
*/
class testimonials extends CI_Controller
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
		
		$result = $this->db->query("SELECT * FROM testimonials WHERE is_delete = 0 ORDER BY ordering asc");
		
		$this->testimonials = $result->result();
		
		$this->load->view('testimonials');
	}
	
	public function allpublish()
	{
		$this->db->query("UPDATE  testimonials SET publish = 1  WHERE is_delete = 0 ");
		redirect('testimonials');
	}
	
	public function addnew()
	{
		$this->load->view('testimonials_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('publish', 'Publish');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('quote', 'Quote', 'required');
		return $this->form_validation->run();
	}
	
	public function save()
	{			
		if ($this->_submit_validate() === FALSE) {
			$this->addnew();
			return;
		}
		
		$testimonials = array(
			'image'         => $this->input->post('image'),
			'customer_name' => $this->input->post('customer_name'),
			'quote'         => $this->input->post('quote'),
			'publish'       => $this->input->post('publish'),
			'created_at'    => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('testimonials', $testimonials);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'testimonials->save() 交易錯誤');
		}
		
		redirect('testimonials/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Testimonials";
		$this->index();
	}
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('testimonials', $Publish);
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
					$this->db->query('update testimonials set is_delete = 1, updated_at = \'' . 
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
				$this->db->update('testimonials', $Ordering);
				
				$numrows += $this->db->affected_rows();
				// echo "$q";
				$i++;
			}
			if ($numrows != 0) {
				$this->message =  $numrows . " records updated";	
			}
		}

	}
	
	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM testimonials WHERE is_delete = 0 and id = " . $ndx);
		$this->testimonials = $result->result();
		$this->load->view('testimonials_update');
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$testimonials = array(
			'image'         => $this->input->post('image'),
			'customer_name' => $this->input->post('customer_name'),
			'quote'         => $this->input->post('quote'),
			'publish'       => $this->input->post('publish'),
			'updated_at'    => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('testimonials', $testimonials);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'testimonials->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}
	
}