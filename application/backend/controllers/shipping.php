<?php

/**
* 
*/
class Shipping extends CI_Controller
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

		$result = $this->db->query("SELECT * FROM shipping_method WHERE is_delete = 0 ORDER BY id asc");
		$this->ShippingMethods = $result->result();
		
		$this->load->view('shipping_method');
	
	}
	
	public function addnew()
	{
		$this->load->view('shipping_method_addnew');
	}
	
	public function save()
	{			
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('shipping_method_addnew');
			return;
		}
		
		$ShippingMethod = array(
			'name'       => $this->input->post('name'),
			'price'      => $this->input->post('price'),
			'delivery'   => $this->input->post('estDelivery'),
			// 'publish'    => $this->input->post('publish'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('shipping_method', $ShippingMethod);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'ShippingMethod->save() 交易錯誤');
		}
		
		redirect('shipping/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Shipping Method";
		$this->index();
	}
	
	public function _submit_validate()
	{
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('estDelivery', 'Estimate Delivery Time', 'required');
		return $this->form_validation->run();
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update shipping_method set is_delete = 1, updated_at = \'' . 
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
		$result = $this->db->query("SELECT * FROM shipping_method WHERE is_delete = 0 and id = " . $ndx);
		$this->ShippingMethods = $result->result();
		$this->load->view('shipping_method_update');
	}
	
	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return;
		}
		
		$ShippingMethod = array(
			'name'       => $this->input->post('name'),
			'price'      => $this->input->post('price'),
			'delivery'   => $this->input->post('estDelivery'),
			// 'publish'    => $this->input->post('publish'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('shipping_method', $ShippingMethod);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'ShippingMethod->Update() 交易錯誤');
		}
		
		redirect('shipping/success', 'refresh');
	}
	
	public function _publish()
	{		
		$Publish = array('publish' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('shipping_method', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
	}
	
}