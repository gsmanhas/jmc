<?php

/**
* 
*/
class Myaccount extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
		if ((!$this->session->userdata('user_id')) && ($this->session->userdata('account_type') != "customer")) {
			redirect("signin", "refresh");
		}
		
		$query = $this->db->query('SELECT * FROM state order by state asc');
		$this->continental = $query->result();
		
		$query = $this->db->query('SELECT * FROM wishlist where uid = ? and is_delete = 0', $this->session->userdata('user_id'));
		$this->wishlist = $query->result();
		
		$this->ShoppingCart = new SystemCart();

	}

	public function _remap()
	{
		switch (strtolower($this->uri->segment(2, 0))) {
			case 'account-info':
				$this->account_info();
				break;
			case 'password':
				$this->password();
				break;
			case 'order-status':
				$this->order_status();
				break;
			case 'wishlist':
				$this->wishlist();
				break;
			case 'account-info-update':
				$this->_submit();
				break;
			case 'remove_wishlist':
				$this->remove_wisthlist();
				break;
			case 'reset-password':
				$this->_reset_password();
				break;
			default:
				// $this->index();
				secure_redirect('myaccount/account-info', 'refresh');
				break;
		}
	}
		
	public function index()
	{
		$this->load->view('myaccount');
	}

	public function account_info()
	{
		$this->load->view('myaccount_account_info');
	}
	
	public function password()
	{
		$this->load->view('myaccount_password');
	}
	
	public function order_status()
	{
		$Query = $this->db->query("SELECT id, order_no, order_date, amount, track_number, (SELECT name FROM order_state WHERE id = order_state) as `order_state` FROM `order` WHERE user_id = ? AND order_state != 5 AND order_state != 6", $this->session->userdata('user_id'));
		$this->Orders = $Query->result();

		$this->load->view('myaccount_order_status');
	}
	
	public function wishlist()
	{
		$this->load->view('myaccount_wishlist');
	}
		
	private function _reset_password_validate() {				
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[24]|callback_authenticate_check');
		$this->form_validation->set_rules('new_pass', 'New Password', 'required|min_length[6]|max_length[24]');
		$this->form_validation->set_rules('passconf', 'Confirm New Password', 'required|min_length[6]|max_length[24]|matches[new_pass]');
		return $this->form_validation->run();
	}
	
	public function authenticate_check($password)
	{
		$this->db->select('id, password');
		$this->db->from('users');
		$this->db->where('id', $this->session->userdata('user_id'));
		// $this->db->where('password', $this->encrypt->decode($this->input->post('password')));
		$u = $this->db->get()->result();
		
		// echo $this->encrypt->decode($u[0]->password). br(1);
		// echo $this->input->post('password'). br(1);
		
		if (count($u) <= 0) {
			$this->form_validation->set_message('authenticate_check', 'invalid');
			return FALSE;
		} else {
			if ($this->encrypt->decode($u[0]->password) == $this->input->post('password')) {
				return TRUE;
			} else {
				$this->form_validation->set_message('authenticate_check', 'invalid');
				return FALSE;
			}
		}
	}
	
	public function _reset_password()
	{
		if ($this->_reset_password_validate() === FALSE) {
			$this->password();
			return;
		}
		
		$User = array(
			'id' => $this->session->userdata('user_id'),
			'password' => $this->encrypt->encode($this->input->post('new_pass'))
		);
		
		$this->db->trans_start();
		$this->db->where('id', $this->session->userdata('user_id'));
		$this->db->update('users', $User);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Myaccount->reset_password() 交易錯誤');
		}
		
		$this->reset_user_session();
		$this->_send_mail_to_reset_password();
		$this->session->set_flashdata('message', 'Your password has been updated successfully!');
		redirect('/myaccount/password', 'refresh');
		
	}
	
	private function _send_mail_to_reset_password()
	{
		
		// $query = $this->db->query('SELECT * FROM users WHERE username = ? and is_delete = 0', $this->session->userdata('email'));
		// $user = $query->result();
		
		//	Email Template params
		$this->CUSTOMER_NAME     = $this->input->post('firstname');
		$this->USER_NAME         = $this->session->userdata('username');
		$this->CUSTOMER_PASSWORD = $this->input->post('new_pass');
				
		$mailer = new Mailer();
		// $mailer->retrieve_password($this->session->userdata('email'), $this->input->post('firstname'));
		$mailer->account_update($this->session->userdata('email'), $this->input->post('firstname'));
		// $mailer->retrieve_password($this->session->userdata('email'), $this->input->post('firstname'));
	}
	
	function valid_phone()
	{
		// $this->form_validation->set_message('phone', 'required');
		return ( ! preg_match("/\(?\d{3}\)?[-\s.]?\d{3}[-\s.]\d{4}/x", $this->input->post('phone'))) ? FALSE : TRUE;
	}
	
	private function _submit_validate()
	{
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		
		$this->form_validation->set_rules('year_of_birth', 'Year of Birth', 'required|numeric|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('month_of_birth', 'Month of Birth', 'required|numeric|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('day_of_birth', 'Day of Birth', 'required|numeric|min_length[1]|max_length[2]');
		
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		$this->form_validation->set_rules('bill_state', 'Billing State', 'required');
		
		if ($this->input->post('shipSame') != 1) {
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');	
			$this->form_validation->set_rules('ship_state', 'Shipping State', 'required');
		}
		
		$this->form_validation->set_message('required', 'required');
		$this->form_validation->set_message('valid_phone','required');

		
		return $this->form_validation->run();
	}
	
	private function _submit()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->account_info();
			return;
		}
		
		if ($this->input->post('shipSame') != 1) {
			$User = array(
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				'phone'             => $this->input->post('phone'),
				'year_of_birth'     => $this->input->post('year_of_birth'),
				'month_of_birth'    => $this->input->post('month_of_birth'),
				'day_of_birth'      => $this->input->post('day_of_birth'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'ship_address'      => $this->input->post('ship_address'),
				'ship_city'         => $this->input->post('ship_city'),
				'ship_zipcode'      => $this->input->post('ship_zipcode'),
				'ship_state'        => $this->input->post('ship_state'),
				'subscribe'         => $this->input->post('subscribe'),
				'shipsame'          => 0,
				'updated_at'        => unix_to_human(time(), TRUE, 'us')
			);	
		} else {
			$User = array(
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				'phone'             => $this->input->post('phone'),
				'year_of_birth'     => $this->input->post('year_of_birth'),
				'month_of_birth'    => $this->input->post('month_of_birth'),
				'day_of_birth'      => $this->input->post('day_of_birth'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'ship_address'      => $this->input->post('bill_address'),
				'ship_city'         => $this->input->post('bill_city'),
				'ship_zipcode'      => $this->input->post('bill_zipcode'),
				'ship_state'        => $this->input->post('bill_state'),
				'ship_country'      => $this->input->post('bill_country'),
				'subscribe'         => $this->input->post('subscribe'),
				'shipsame'          => 1,
				'updated_at'        => unix_to_human(time(), TRUE, 'us')
			);
		}
		
		$this->db->trans_start();
		$this->db->where('id', $this->session->userdata('user_id'));
		$this->db->update('users', $User);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			log_message('error', 'Myaccount->update() 交易錯誤');
		}
		
		//	Email Template params
		$this->CUSTOMER_NAME = $this->input->post('firstname');
		
		$mailer = new Mailer();
		$mailer->account_update($this->session->userdata('email'), $this->input->post('firstname'));
		
		$this->reset_user_session();
		$this->session->set_flashdata('message', 'Your account information has been updated successfully!');
		redirect('/myaccount/account-info', 'refresh');
		// secure_redirect('/myaccount/account-info', 'refresh');
		
	}
	
	private function reset_user_session()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $this->session->userdata('user_id'));
		$u = $this->db->get()->result();

		$data = array(
			'user_id'           => $u[0]->id,
			'username'          => $u[0]->username,
			'email'             => $u[0]->email,
			'firstname'         => $u[0]->firstname,
			'lastname'          => $u[0]->lastname,
			'phone'             => $u[0]->phone,
			'year_of_birth'     => $u[0]->year_of_birth,
			'month_of_birth'    => $u[0]->month_of_birth,
			'day_of_birth'      => $u[0]->day_of_birth,
			'bill_address'      => $u[0]->bill_address,
			'bill_city'         => $u[0]->bill_city,
			'bill_zipcode'      => $u[0]->bill_zipcode,
			'bill_state'        => $u[0]->bill_state,
			'ship_address'      => $u[0]->ship_address,
			'ship_city'         => $u[0]->ship_city,
			'ship_zipcode'      => $u[0]->ship_zipcode,
			'ship_state'        => $u[0]->ship_state,
			'subscribe'         => $u[0]->subscribe,
			'shipsame'          => $u[0]->shipsame,
			'account_type'      => 'customer',
			'logged_in' => TRUE
		);
		
		$this->session->set_userdata($data);
				
	}
	
	public function remove_wisthlist()
	{
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

			secure_redirect("myaccount/wishlist", "refresh");
		}
	}
}