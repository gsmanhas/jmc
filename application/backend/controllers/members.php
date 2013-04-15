<?php

/**
* 
*/
class Members extends CI_Controller
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
			} else if ($_POST['action'] == "enabled" && $_POST['id'] != "") {	// Publish or unPublish
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
		
		// $result = $this->db->query("SELECT * FROM users WHERE is_delete = 0 ORDER BY id desc");
		// $this->users = $result->result();
		
		$this->ListDestinationState = $this->ListDestinationState();
		
		$this->load->view('members');
	}
	
	public function _publish()
	{		
		
		if ($this->input->post('enabled') == '0') {
			$Publish = array(
				'block' => $this->input->post('enabled'),
				'activation' => ''
			);			
		} else {
			$Publish = array(
				'block' => $this->input->post('enabled')
			);
		}
		
		$this->db->where('id', $_POST['id']);
		$this->db->update('users', $Publish);
		$numrows = $this->db->affected_rows();
		$this->message =  $numrows . " records updated";
	}
	
	public function success()
	{
		$this->message = "Successfully Saved Members";
		$this->index();
	}

	public function addnew()
	{
		$this->load->view('members_addnew');
	}

	private function _submit_validate()
	{
		
		$this->form_validation->set_rules('username', 'Username', 'required|valid_email|callback_email_check');
		// $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|max_length[24]|callback_username_check');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[24]');
		$this->form_validation->set_rules('passconf', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		
		// $this->form_validation->set_rules('bill_firstname', 'Billing First Name', 'required');
		// $this->form_validation->set_rules('bill_lastname', 'Billing Last Name', 'required');
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		
		if ($this->input->post('shipSame') != 1) {
			// $this->form_validation->set_rules('ship_firstname', 'Shipping First Name', 'required');
			// $this->form_validation->set_rules('ship_lastname', 'Shipping Last Name', 'required');
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');	
		}
		
		return $this->form_validation->run();
	}

	public function username_check()
	{
		$query = $this->db->query('SELECT id FROM users WHERE username = \'' . $this->input->post('username') . '\'');
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('username_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function email_check()
	{
		$query = $this->db->query('SELECT id FROM users WHERE email = \'' . $this->input->post('email') . '\'');
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('email_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function save()
	{			
		
		if ($this->_submit_validate() === FALSE) {
			$this->load->view('members_addnew');
			return;
		}
		
		if ($this->input->post('shipSame') != 1) {
			$User = array(
				'username'          => $this->input->post('username'),
				'password'          => $this->encrypt->encode($this->input->post('password')),
				'email'             => $this->input->post('email'),
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				'bill_firstname'    => $this->input->post('bill_firstname'),
				'bill_lastname'     => $this->input->post('bill_lastname'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'bill_country'      => $this->input->post('bill_country'),
				'ship_firstname'    => $this->input->post('ship_firstname'),
				'ship_lastname'     => $this->input->post('ship_lastname'),
				'ship_address'      => $this->input->post('ship_address'),
				'ship_city'         => $this->input->post('ship_city'),
				'ship_zipcode'      => $this->input->post('ship_zipcode'),
				'ship_state'        => $this->input->post('ship_state'),
				'ship_country'      => $this->input->post('ship_country'),
				'subscribe'         => $this->input->post('subscribe'),
				'activation'        => MD5(uniqid()),
				'shipsame'          => 0,
				'created_at'        => unix_to_human(time(), TRUE, 'us'),
				'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);	
		} else {
			$User = array(
				'username'          => $this->input->post('username'),
				'password'          => $this->encrypt->encode($this->input->post('password')),
				'email'             => $this->input->post('email'),
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				'bill_firstname'    => $this->input->post('bill_firstname'),
				'bill_lastname'     => $this->input->post('bill_lastname'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'bill_country'      => $this->input->post('bill_country'),
				'ship_firstname'    => $this->input->post('bill_firstname'),
				'ship_lastname'     => $this->input->post('bill_lastname'),
				'ship_address'      => $this->input->post('bill_address'),
				'ship_city'         => $this->input->post('bill_city'),
				'ship_zipcode'      => $this->input->post('bill_zipcode'),
				'ship_state'        => $this->input->post('bill_state'),
				'ship_country'      => $this->input->post('bill_country'),
				'subscribe'         => $this->input->post('subscribe'),
				'activation'        => MD5(uniqid()),
				'shipsame'          => 1,
				'created_at'        => unix_to_human(time(), TRUE, 'us'),
				'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);
		}
		
		$this->db->trans_start();
		$this->db->insert('users', $User);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'RegisterUser->save() 交易錯誤');
		}
		
		// $this->_sendVaildationMail($this->input->post('email'));
		
		redirect('members/success', 'refresh');
	}

	public function _update($ndx)
	{
		$result = $this->db->query("SELECT * FROM users WHERE is_delete = 0 and id = " . $ndx);
		$this->user = $result->result();
		$this->load->view('members_update');
	}

	private function _update_submit_validate()
	{
		// $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|max_length[24]|callback_username2_check');
		$this->form_validation->set_rules('username', 'Username', 'required|valid_email|callback_email2_check');
		
		// $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[24]');
		// $this->form_validation->set_rules('passconf', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|callback_email2_check');
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		
		// $this->form_validation->set_rules('bill_firstname', 'Billing First Name', 'required');
		// $this->form_validation->set_rules('bill_lastname', 'Billing Last Name', 'required');
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		
		if ($this->input->post('shipSame') != 1) {
			// $this->form_validation->set_rules('ship_firstname', 'Shipping First Name', 'required');
			// $this->form_validation->set_rules('ship_lastname', 'Shipping Last Name', 'required');
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');	
		}
		
		return $this->form_validation->run();
	}

	public function username2_check()
	{
		$query = $this->db->query(
			'SELECT id FROM users WHERE username = \'' . $this->input->post('username') . '\'' .
			' AND id != ' . $this->input->post('id')
		);
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('username2_check', 'The %s already exists.');
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
		
		$ProductCatalogs = array(
			'name'       => $this->input->post('name'),
			'url'        => $this->input->post('url'),
			'upperid'    => $this->input->post('upperid'),
			'publish'    => $this->input->post('publish'),
			'updated_at' => unix_to_human(time(), TRUE, 'us')
		);
		
		if ($this->input->post('shipSame') != 1) {
			$User = array(
				'username'          => $this->input->post('username'),
				// 'password'          => $this->encrypt->encode($this->input->post('password')),
				'email'             => $this->input->post('email'),
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				'bill_firstname'    => $this->input->post('bill_firstname'),
				'bill_lastname'     => $this->input->post('bill_lastname'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'bill_country'      => $this->input->post('bill_country'),
				'ship_firstname'    => $this->input->post('ship_firstname'),
				'ship_lastname'     => $this->input->post('ship_lastname'),
				'ship_address'      => $this->input->post('ship_address'),
				'ship_city'         => $this->input->post('ship_city'),
				'ship_zipcode'      => $this->input->post('ship_zipcode'),
				'ship_state'        => $this->input->post('ship_state'),
				'ship_country'      => $this->input->post('ship_country'),
				'subscribe'         => $this->input->post('subscribe'),
				'shipsame'          => 0,
				// 'activation'        => MD5(uniqid()),
				'updated_at'        => unix_to_human(time(), TRUE, 'us')
				// 'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);	
		} else {
			$User = array(
				'username'          => $this->input->post('username'),
				// 'password'          => $this->encrypt->encode($this->input->post('password')),
				'email'             => $this->input->post('email'),
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				'bill_firstname'    => $this->input->post('bill_firstname'),
				'bill_lastname'     => $this->input->post('bill_lastname'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'bill_country'      => $this->input->post('bill_country'),
				'ship_firstname'    => $this->input->post('bill_firstname'),
				'ship_lastname'     => $this->input->post('bill_lastname'),
				'ship_address'      => $this->input->post('bill_address'),
				'ship_city'         => $this->input->post('bill_city'),
				'ship_zipcode'      => $this->input->post('bill_zipcode'),
				'ship_state'        => $this->input->post('bill_state'),
				'ship_country'      => $this->input->post('bill_country'),
				'subscribe'         => $this->input->post('subscribe'),
				'shipsame'          => 1,
				// 'activation'        => MD5(uniqid()),
				'updated_at'        => unix_to_human(time(), TRUE, 'us')
				// 'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);
		}

		$this->db->trans_start();
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('users', $User);
		$this->db->trans_complete();
				
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Member->_update_save() 交易錯誤');
		}

		$this->update_message = "1 records updated";
		return true;
	}

	public function _remove($ndx)
	{
		$numrows = 0;
		if (!empty($ndx)) {
			$ids = explode(',', $ndx);
			if (is_array($ids) && (count($ids) >= 1)) {
				$this->db->trans_start();
				foreach ($ids as $id) {
					$this->db->query('update users set is_delete = 1, updated_at = \'' . 
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
	
	public function email2_check()
	{
		$query = $this->db->query(
			'SELECT id FROM users WHERE email = \'' . $this->input->post('email') . '\'' .
			' AND id != ' . $this->input->post('id')
		);
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('email2_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function search()
	{
		$first_name   = $this->input->post('first_name');
		$last_name    = $this->input->post('last_name');
		$email        = $this->input->post('email');
		$bill_address = $this->input->post('bill_address');
		$ship_address = $this->input->post('ship_address');
		$state = $this->input->post('state');
		// $order_no     = $this->input->post('order_no');
		// $release_date = $this->input->post('release_date');
		// $expiry_date  = $this->input->post('expiry_date');
				
		$this->db->distinct();
		// $this->db->select("u.id, u.block, u.firstname, u.lastname, u.email, o.id as `order_id`, o.order_no, o.bill_address, o.ship_address, o.order_date");
		$this->db->select("u.username, u.password, u.id, u.block, u.firstname, u.lastname, u.email, u.bill_address, u.ship_address");
		$this->db->from("users as u");
		
		// $this->db->join('`order` as o', 'u.id = o.user_id', 'left');
		
		$this->db->where('is_delete', 0);
		
		if ($first_name != "") {
			$this->db->like('u.firstname', $first_name, 'after');
		}
		
		if ($last_name != "") {
			$this->db->or_like('u.lastname', $last_name, 'after');
		}
		
		if ($email != "") {
			$this->db->like('u.email', $email);
		}
		
		if ($bill_address != "") {
			$this->db->like('u.bill_address', $bill_address);
			$this->db->or_like('u.bill_city', $bill_address);
			$this->db->or_like('u.bill_zipcode', $bill_address);
		}
		
		if ($ship_address != "") {
			$this->db->like('u.ship_address', $ship_address);
			$this->db->or_like('u.ship_city', $ship_address);
			$this->db->or_like('u.ship_zipcode', $ship_address);
		}
		
		if ($state > 0) {
			$this->db->where('bill_state', $state);
			$this->db->or_where('ship_state', $state);
			$this->db->where('is_delete', 0);
		}
		
		// if ($order_no != "") {
		// 	$this->db->where('o.order_no', $order_no);
		// }
		// 
		// if ($release_date != "" && $expiry_date != "") {
		// 	$this->db->where('o.order_date >=', $release_date);
		// }
		// 
		// if ($expiry_date != "") {
		// 	$this->db->where('o.order_date <=', $expiry_date);
		// }
				
		$Query = $this->db->get()->result();
		
		// echo $this->db->last_query();
		// exit;
		
		if (count($Query) >= 1) {
			$this->users = $Query;
			$this->load->view("members_search_result");
			
		} else {
			$this->update_message = "There is no record based on your search";
			$this->index();
		}

	}

	public function ListDestinationState()
	{
		$Options = array();
		$Query = $this->db->query("SELECT * FROM state");
		$states = $Query->result();
		
		if (count($states) >= 1) {
			$Options = $states;
		}
		return $states;
	}
	
}