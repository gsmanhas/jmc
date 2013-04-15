<?php

/**
* 
*/
class Discountcode extends CI_Controller
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
		
		$result = $this->db->query("SELECT * FROM discountcode WHERE is_delete = 0 and gift_with_purchase = 'N' ORDER BY id asc");
		$this->discountcodes = $result->result();
		
		$this->load->view('discountcode');
	}
	
	public function addnew()
	{
		$this->load->view('discountcode_addnew');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('enabled', 'Enabled');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('code', 'Discount Code', 'required|callback_discount_code_check');

        if ($this->input->post('discountcodetype') != '3' && $this->input->post('discountcodetype') != 4
            && $this->input->post('discountcodetype') != 5) {
			$this->form_validation->set_rules('discount_percentage', 
				'Discount Percentage or Fixed Amount', 'required|numeric');	
		}
	
		if ($this->input->post('discountcodetype') == '3') {
			$this->form_validation->set_rules('discount_amount_threshold', 
				'Discount Percentage or Fixed Amount must be a number and may contain a decimal point.', 'required|numeric');	
		}
			
		$this->form_validation->set_rules('release_date', 'Release Date', 'required');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
		return $this->form_validation->run();
	}
	
	private function _update_submit_validate()
	{
		$this->form_validation->set_rules('enabled', 'Enabled');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('code', 'Discount Code', 'required|callback_discount_code2_check');
		if ($this->input->post('discountcodetype') != '3' && $this->input->post('discountcodetype')!=4) {
			$this->form_validation->set_rules('discount_percentage', 
				'Discount Percentage or Fixed Amount must be a number and may contain a decimal point.', 'required|numeric');	
		}
		if ($this->input->post('discountcodetype') == '3') {
			$this->form_validation->set_rules('discount_amount_threshold', 
				'Discount Percentage or Fixed Amount must be a number and may contain a decimal point.', 'required|numeric');	
		}
			
		$this->form_validation->set_rules('release_date', 'Release Date', 'required');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');
		return $this->form_validation->run();
	}
	
	public function discount_code_check()
	{
		$query = $this->db->query(
			"SELECT id FROM discountcode WHERE code = '" . $this->input->post('code') . "'" .
			" AND is_delete = 0"
		);
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('discount_code_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function discount_code2_check()
	{
		$query = $this->db->query(
			"SELECT id FROM discountcode WHERE code = '" . $this->input->post('code') . "'" .
			" AND id != " . $this->input->post('id') .
			" AND is_delete = 0"
		);
		$isExists = $query->result();
		if (count($isExists) >= 1) {
			$this->form_validation->set_message('discount_code2_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function save()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('discountcode_addnew');
			return;
		}
		
		$release_timezone = strtotime(date($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		$expiry_timezone  = strtotime(date($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));
		
		// $release_timezone = date_timestamp_get(date_create($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		// $expiry_timezone  = date_timestamp_get(date_create($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));
		
		$DiscountCode = array(
			'description'               => $this->input->post('description'),
			'code'                      => $this->input->post('code'),
			'discount_type'             => $this->input->post('discountcodetype'),
			'discount_percentage'       => $this->input->post('discount_percentage'),
			'discount_amount_threshold' => $this->input->post('discount_amount_threshold'),
			'xuses'                     => $this->input->post('xuses'),
			'apply_ones'                => $this->input->post('apply_ones'),
			'can_free_shipping'         => (isset($_POST['can_free_shipping']) && $_POST['can_free_shipping'] == 1) ? 1 : 0,
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
			'enabled'                   => $this->input->post('enabled'),
			'created_at'                => unix_to_human(time(), TRUE, 'us')
		);
							
		$this->db->trans_start();
		$this->db->insert('discountcode', $DiscountCode);
		$did = $this->db->insert_id();
		if (isset($_POST['enabled_product_list']) && $_POST['enabled_product_list'] == 1) {
			$query = $this->db->query("SELECT * FROM discountcode order by id desc limit 1");
			$DiscountCode = $query->result();
			if (count($DiscountCode) >= 1) {
				
				// $Cids = explode(',', $this->input->post('hid_cids'));
				// if (is_array($Cids) && count($Cids) >= 1) {
				// 	foreach ($Cids as $value) {
				// 		$DiscountCode_rel_Catalogs = array(
				// 			'd_id'       => $DiscountCode[0]->id,
				// 			'cid'        => $value,
				// 			'created_at' => unix_to_human(time(), TRUE, 'us')
				// 		);
				// 		$this->db->insert('discountcode_rel_catalogs', $DiscountCode_rel_Catalogs);
				// 	}
				// }

				$Pids = explode(',', $this->input->post('hid_pids'));
				// $Pid_rel_Cids = explode(',', $this->input->post('hid_pid_with_cid'));

				// if (count($Pids) == count($Pid_rel_Cids)) {
					if (is_array($Pids) && count($Pids) >= 1) {
						for ($i = 0; $i < count($Pids); $i++) {
							$DiscountCode_rel_Products = array(
								'd_id' => $DiscountCode[0]->id,
								// 'cid'  => $Pid_rel_Cids[$i],
								'pid'  => $Pids[$i],
								'created_at' => unix_to_human(time(), TRUE, 'us')
							);
							$this->db->insert('discountcode_rel_products', $DiscountCode_rel_Products);
						}
					}
				// }
			}
		}

        if($this->input->post('discountcodetype') == 5) {
            $GiftProduct = array(
                'd_id' => $did,
                'p_id' => $this->input->post('free_gift'),
                'created_at' => unix_to_human(time(), TRUE, 'us')
            );

            $this->db->insert('discountcode_rel_gift', $GiftProduct);
        }

		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Discount->save() 交易錯誤');
		}
		
		redirect('discountcode/success', 'refresh');
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Discount Code";
		$this->index();
	}
	
	public function _update($ndx)
	{
		$result = $this->db->query(
			"SELECT `id`, `description`, `code`, `discount_type`, `discount_percentage`, `discount_amount_threshold`" .
			", `xuses`, apply_ones, date_format(release_date, '%Y-%m-%d') as 'release_date', " .
			"date_format(expiry_date, '%Y-%m-%d') as 'expiry_date'" .
			", release_hour, release_mins, release_seconds, expiry_hour, expiry_mins, expiry_seconds, `can_free_shipping` as 'can_free_shipping' " .
			", `enabled`, `created_at`, `updated_at`, `is_delete` " . 
			"FROM discountcode WHERE is_delete = 0 and id = " . $ndx
		);
		$this->discountcode = $result->result();
		$this->load->view('discountcode_update');
	}

	public function _publish()
	{		
		$Enabled = array('enabled' => $this->input->post('publish_state'));	
		$this->db->where('id', $_POST['id']);
		$this->db->update('discountcode', $Enabled);
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
					$this->db->query('update discountcode set is_delete = 1, updated_at = \'' . 
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
		if ($this->_update_submit_validate() === FALSE) {
			$this->_update($_POST['id']);
			return false;
		}
		
		$release_timezone = strtotime(date($_POST['release_date'] . " " . $_POST['release_hour'] . ":" . $_POST['release_mins'] . ":" . $_POST['release_seconds']));
		$expiry_timezone  = strtotime(date($_POST['expiry_date'] . " " . $_POST['expiry_hour'] . ":" . $_POST['expiry_mins'] . ":" . $_POST['expiry_seconds']));		
		
		$DiscountCode = array(
			'description'               => $this->input->post('description'),
			'code'                      => $this->input->post('code'),
			'discount_type'             => $this->input->post('discountcodetype'),
			'discount_percentage'       => $this->input->post('discount_percentage'),
			'discount_amount_threshold' => $this->input->post('discount_amount_threshold'),
			'xuses'                     => $this->input->post('xuses'),
			'apply_ones'                => $this->input->post('apply_ones'),
			'can_free_shipping'         => (isset($_POST['can_free_shipping']) && $_POST['can_free_shipping'] == 1) ? 1 : 0,
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
			'enabled'                   => $this->input->post('enabled'),
			'created_at'                => unix_to_human(time(), TRUE, 'us')
		);

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('discountcode', $DiscountCode);
		
		//	不管有沒有都要刪
		$query = $this->db->query("DELETE FROM discountcode_rel_products WHERE d_id = " . $this->input->post('id'));
		
		if (isset($_POST['enabled_product_list']) && $_POST['enabled_product_list'] == 1) {
			
			// $query = $this->db->query("DELETE FROM discountcode_rel_catalogs WHERE d_id = " . $this->input->post('id'));
			
			if (count($DiscountCode) >= 1) {
				// $Cids = explode(',', $this->input->post('hid_cids'));
				// if (is_array($Cids) && count($Cids) >= 1) {
				// 	foreach ($Cids as $value) {
				// 		if ($value != 0) {
				// 			$DiscountCode_rel_Catalogs = array(
				// 				'd_id'       => $this->input->post('id'),
				// 				'cid'        => $value,
				// 				'created_at' => unix_to_human(time(), TRUE, 'us')
				// 			);
				// 			$this->db->insert('discountcode_rel_catalogs', $DiscountCode_rel_Catalogs);	
				// 		}
				// 	}
				// }

				$Pids = explode(',', $this->input->post('hid_pids'));
				// $Pid_rel_Cids = explode(',', $this->input->post('hid_pid_with_cid'));

				// if (count($Pids) == count($Pid_rel_Cids)) {
					if (is_array($Pids) && count($Pids) >= 1) {
						for ($i = 0; $i < count($Pids); $i++) {
							// if ($Pids[$i] != 0 && $Pid_rel_Cids[$i] != 0) {
								$DiscountCode_rel_Products = array(
									'd_id' => $this->input->post('id'),
									// 'cid'  => $Pid_rel_Cids[$i],
									'pid'  => $Pids[$i],
									'created_at' => unix_to_human(time(), TRUE, 'us')
								);
								$this->db->insert('discountcode_rel_products', $DiscountCode_rel_Products);	
							// }
						}
					}
				// }
			}			
		}

        if($this->input->post('discountcodetype') == 5) {
            $GiftProduct = array(
                'p_id' => $this->input->post('free_gift'),
                'updated_at' => unix_to_human(time(), TRUE, 'us')
            );

            $this->db->where('d_id', $this->input->post('id'));
            $this->db->update('discountcode_rel_gift', $GiftProduct);
        }
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'DiscountCode->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;	
	}
	
}