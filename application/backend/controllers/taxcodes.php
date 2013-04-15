<?php

/**
* 
*/
class TaxCodes extends CI_Controller
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
			} else if ($_POST['action'] == "update" && $_POST['id'] != "") {	// Change Update Mode
				$this->_update($_POST['id']);
				return;
			} else if ($_POST['action'] == "update_save" && $_POST['id'] != "") {	//	Save Update Data.
				if ($this->_update_save() == false) {
					return;
				}
			}
		}

		$result = $this->db->query(
			"SELECT " . 
			"id, (SELECT state FROM state as s WHERE s.id = t.state_id) as `state`," .
            "(SELECT c.country FROM country as c, state as s
                        WHERE s.id = t.state_id && c.id = s.country_id) as `country`".
			",tax_code, tax_rate, is_delete" .
			" FROM tax_codes as t WHERE is_delete = 0 ORDER BY id asc"
		);
		$this->taxcodes = $result->result();
		
		$this->load->view('tax_codes');	
	}
	
	public function addnew()
	{
		$this->load->view('tax_codes_addnew');
	}
	
	public function save()
	{			
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('tax_codes_addnew');
			return;
		}
		
		$TaxRate = array(
			'state_id'   => $this->input->post('state'),
			'tax_code'   => $this->input->post('tax_code'),
			'tax_rate'   => $this->input->post('tax_rate'),
			'created_at' => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('tax_codes', $TaxRate);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'TaxRate->save() 交易錯誤');
		}
		
		redirect('taxcodes/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Tax Code";
		$this->index();
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('state', 'state', 'required|callback_state_check');
		$this->form_validation->set_rules('tax_code', 'Tax Code', 'required|trim');
		$this->form_validation->set_rules('tax_rate', 'Tax Rate', 'required|trim|numeric');
		return $this->form_validation->run();
	}
	
	public function state_check()
	{
		if ($this->input->post('state') <= 0) {
			$this->form_validation->set_message('state_check', 'Please Select State');
			return FALSE;
		}
		
		$query = $this->db->query(
			"SELECT state_id FROM tax_codes WHERE state_id = " . $this->input->post('state') .
			" AND is_delete = 0"
		);
		
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('state_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
		
	}
	
	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update tax_codes set is_delete = 1, updated_at = \'' . 
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
		$result = $this->db->query("SELECT * FROM tax_codes WHERE is_delete = 0 and id = " . $ndx);
		$this->TaxRate = $result->result();
		$this->load->view('tax_codes_update');
	}
	
	private function _update_submit_validate()
	{
		$this->form_validation->set_rules('state', 'state', 'required|callback_state2_check');
		$this->form_validation->set_rules('tax_code', 'Tax Code', 'required|trim');
		$this->form_validation->set_rules('tax_rate', 'Tax Rate', 'required|trim|numeric');
		return $this->form_validation->run();
	}
	
	public function state2_check()
	{
		if ($this->input->post('state') <= 0) {
			$this->form_validation->set_message('state2_check', 'Please Select State');
			return FALSE;
		}
		
		$query = $this->db->query(
			"SELECT state_id FROM tax_codes WHERE state_id = " . $this->input->post('state') .
			" AND is_delete = 0 AND id != " . $this->input->post('id')
		);
		
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('state2_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function _update_save()
	{
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}

		$TaxRate = array(
			'state_id'   => $this->input->post('state'),
			'tax_code'   => $this->input->post('tax_code'),
			'tax_rate'   => $this->input->post('tax_rate'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->where('id', $_POST['id']);
		$this->db->update('tax_codes', $TaxRate);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'TaxRate->update_save() 交易錯誤');
		}
		
		redirect('taxcodes/success', 'refresh');	
	
	}
	
}