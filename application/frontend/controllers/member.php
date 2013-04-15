<?php

/**
* 
*/
class Member extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function _remap()
	{	
		switch ($this->uri->segment(2, 0)) {
			case "profile" :
				$this->profile();
			break;
			case "savechange" :
				$this->SaveChange();
			break;
			
			case "remove_wish_list" :
				$this->remove_wish_list();
			break;
			
			case "0" :
				
			break;
			
			default:
				
			break;
		}
	}
	
	private function _submit_validate()
	{
		
		// $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|max_length[24]|callback_username_check');
		$this->form_validation->set_rules('old_password', 'Old Password', 'required|min_length[6]|max_length[24]|callback_old_password_check');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[24]');
		$this->form_validation->set_rules('passconf', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|callback_email_check');
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		
		$this->form_validation->set_rules('bill_firstname', 'Billing First Name', 'required');
		$this->form_validation->set_rules('bill_lastname', 'Billing Last Name', 'required');
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		
		if ($this->input->post('shipSame') != 1) {
			$this->form_validation->set_rules('ship_firstname', 'Shipping First Name', 'required');
			$this->form_validation->set_rules('ship_lastname', 'Shipping Last Name', 'required');
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');	
		}
		
		return $this->form_validation->run();
	}
	
	public function old_password_check()
	{		
		$query = $this->db->query(
			"SELECT password FROM users WHERE id = " . $this->session->userdata('user_id')
		);
		
		$u = $query->result();
		
		if (count($u) <= 0) {
			redirect('login');
		} else {
						
			if ($this->encrypt->decode($u[0]->password) == $this->input->post('old_password')) {
				return TRUE;
			} else {
				$this->form_validation->set_message('old_password_check', 'old passwords do not match');
				return FALSE;
			}
		}
				
	}
	
	public function username_check($username)
	{
		$query = $this->db->query('SELECT id FROM users WHERE username = \'' . $username . '\'');
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('username_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function email_check($email)
	{
		$query = $this->db->query(
			'SELECT id FROM users WHERE email = \'' . $email . '\'' .
			' AND id != ' . $this->session->userdata('user_id')
		);
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('email_check', 'The %s already exists.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function index()
	{
		
	}
	
	private function SaveChange()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->profile();
			return;
		}
		
		if ($this->input->post('shipSame') != 1) {
			$User = array(
				// 'username'          => $this->input->post('username'),
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
				// 'activation'        => MD5(uniqid()),
				'shipsame'          => 0,
				'updated_at'        => unix_to_human(time(), TRUE, 'us')
				// 'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);	
		} else {
			$User = array(
				// 'username'          => $this->input->post('username'),
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
				// 'activation'        => MD5(uniqid()),
				'shipsame'          => 1,
				'updated_at'        => unix_to_human(time(), TRUE, 'us')
				// 'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);
		}
		
		$this->db->trans_start();
		$this->db->where('id = ' . $this->session->userdata('user_id'));
		$this->db->update('users', $User);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'MemberUpdate->save() 交易錯誤');
		}
		
		$query = $this->db->query("SELECT * FROM users WHERE id = " . $this->session->userdata('user_id'));
		$u = $query->result();
		
		$data = array(
			'user_id'           => $u[0]->id,
			'username'          => $u[0]->username,
			'email'             => $u[0]->email,
			'firstname'         => $u[0]->firstname,
			'lastname'          => $u[0]->lastname,
			'bill_firstname'    => $u[0]->bill_firstname,
			'bill_lastname'     => $u[0]->bill_lastname,
			'bill_address'      => $u[0]->bill_address,
			'bill_city'         => $u[0]->bill_city,
			'bill_zipcode'      => $u[0]->bill_zipcode,
			'bill_state'        => $u[0]->bill_state,
			'bill_country'      => $u[0]->bill_country,
			'ship_firstname'    => $u[0]->ship_firstname,
			'ship_lastname'     => $u[0]->ship_lastname,
			'ship_address'      => $u[0]->ship_address,
			'ship_city'         => $u[0]->ship_city,
			'ship_zipcode'      => $u[0]->ship_zipcode,
			'ship_state'        => $u[0]->ship_state,
			'ship_country'      => $u[0]->ship_country,
			'subscribe'         => $u[0]->subscribe,
			'logged_in' => TRUE
		);
		
		$this->session->set_userdata($data);
		
		echo "<a href=\"" . base_url() . "\">Home</a>";
		
	}
	
	private function profile()
	{
		if ($this->session->userdata('username') == '') {
			redirect('login');
		} else {
			
			$query = $this->db->query(
				"SELECT * FROM users WHERE id = " . $this->session->userdata('user_id')
			);
			
			$this->UserInfo = $query->result();
			
			$query = $this->db->query('SELECT * FROM state order by state asc');
			$this->continental = $query->result();

			$query = $this->db->query('SELECT * FROM country order by country asc');
			$this->countrys = $query->result();
			
			$query = $this->db->query('SELECT * FROM wishlist where uid = ? and is_delete = 0', $this->session->userdata('user_id'));
			$this->wishlist = $query->result();
			
			if (count($this->UserInfo) <= 0) {
				redirect('login');
			} else {
				$this->load->view('member_profile');
			}
			
		}
	}
	
	public function remove_wish_list()
	{
		if ($this->session->userdata('username') == '') {
			redirect('login');
		} else {
			if ($this->uri->segment(3, 0) != "0") {
				
				$Update_WishList = array(
					'updated_at' => unix_to_human(time(), TRUE, 'us'),
					'is_delete'  => 1
				);
				
				$this->db->where('uid', $this->session->userdata('user_id'));
				$this->db->where('id', $this->uri->segment(3, 0));
				$this->db->update('wishlist', $Update_WishList);
				
				//	Checking Wish List
				$query = $this->db->query(
					"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->session->userdata('user_id') .
					" and is_delete = 0"
				);
				$result = $query->result();
				$this->session->set_userdata(array("wishlist_count" => $result[0]->wishlist_count));

				redirect("member");
			}	
		}		
	}
	
}