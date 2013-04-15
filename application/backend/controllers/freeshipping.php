<?php

/**
* 
*/
class Freeshipping extends CI_Controller
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
		
		$this->db->select('*');
		$this->db->from('freeshipping');
		$this->db->where('is_delete', 0);
		
		$this->freeshipping = $this->db->get()->result();
		
		$this->load->view('freeshipping');
	}
	
	public function addnew()
	{
		$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 order by `price` asc");
		$this->ListShippingMethod = $Query->result();
		$this->load->view('freeshipping_addnew');
	}
	
	public function save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('freeshipping_addnew');
			return;
		}
		
		$release_timezone = strtotime(date($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		$expiry_timezone  = strtotime(date($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));
		
		// $release_timezone = date_timestamp_get(date_create($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		// $expiry_timezone  = date_timestamp_get(date_create($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));
		
		$FreeShipping = array(
			'x_dollar_amount'           => $this->input->post('x_dollar_amount'),
			'shipping_method'           => $this->input->post('shipping_method'),
			'release_date'              => $this->input->post('release_date'),
			'release_hour'              => $this->input->post('release_hour'),
			'release_mins'              => $this->input->post('release_mins'),
			'release_seconds'           => $this->input->post('release_seconds'),
			'release_timezone'          => $release_timezone,
			'expiry_date'               => $this->input->post('expiry_date'),
			'expiry_hour'               => $this->input->post('expiry_hour'),
			'expiry_mins'               => $this->input->post('expiry_mins'),
			'expiry_seconds'            => $this->input->post('expiry_seconds'),
			'expiry_timezone'           => $expiry_timezone,
			'created_at'                => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('freeshipping', $FreeShipping);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'FreeShipping->save() 交易錯誤');
		}
		
		redirect('freeshipping/success', 'refresh');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('x_dollar_amount', 'X Dollar Amount', 'required|numeric');
		$this->form_validation->set_rules('release_date', 'Release Date', 'required');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
		return $this->form_validation->run();
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Free Shipping";
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
					$this->db->query('update freeshipping set is_delete = 1, updated_at = \'' . 
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
		$result = $this->db->query("SELECT * FROM freeshipping WHERE is_delete = 0 and id = " . $ndx);
		$this->fs = $result->result();
		
		$Query = $this->db->query("SELECT `id`, `name`, `price` FROM shipping_method where is_delete = 0 AND id != 99 order by `price` asc");
		$this->ListShippingMethod = $Query->result();
		
		$this->load->view('freeshipping_update');
	}

	public function _update_save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$release_timezone = strtotime(date($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		$expiry_timezone  = strtotime(date($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));
		
		// $release_timezone = date_timestamp_get(date_create($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		// $expiry_timezone  = date_timestamp_get(date_create($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));
		
		$FreeShipping = array(
			'x_dollar_amount'           => $this->input->post('x_dollar_amount'),
			'shipping_method'           => $this->input->post('shipping_method'),
			'release_date'              => $this->input->post('release_date'),
			'release_hour'              => $this->input->post('release_hour'),
			'release_mins'              => $this->input->post('release_mins'),
			'release_seconds'           => $this->input->post('release_seconds'),
			'release_timezone'          => $release_timezone,
			'expiry_date'               => $this->input->post('expiry_date'),
			'expiry_hour'               => $this->input->post('expiry_hour'),
			'expiry_mins'               => $this->input->post('expiry_mins'),
			'expiry_seconds'            => $this->input->post('expiry_seconds'),
			'expiry_timezone'           => $expiry_timezone,
			'created_at'                => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('freeshipping', $FreeShipping);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'FreeShipping->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}
	
}