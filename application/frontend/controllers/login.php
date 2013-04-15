<?php

/**
* 
*/
class Login extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{
		switch ($this->uri->segment(2, 0)) {

			case "0" :
				$this->index();
			break;

			case "submit" :
				$this->_submit();
			break;
						
			default:
				redirect('page-not-found', 'refresh');
			break;
		}
	}
	
	public function index()
	{
		$this->load->view('frontend/signin');
	}
	
	public function checkout_page()
	{
		$this->load->view('checkout');
	}
	
	public function _submit()
	{
		if ($this->_submit_validate() === FALSE) {
			if ($this->input->post('checkout') == "true") {
				$this->checkout_page();
			} else {
				$this->index();
			}
			return;
		}
		redirect('/');
	}
	
	private function _submit_validate() {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_authenticate');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_message('authenticate','Invalid login. Please try again.');
		return $this->form_validation->run();
	}
	
	public function authenticate() {
		
		$query = $this->db->query(
			'SELECT * FROM users ' .
			'WHERE username = \'' . $this->input->post('username') . '\''
		);
		
		$u = $query->result();
		
		if (count($u) >= 1) {
			
			if ($this->encrypt->decode($u[0]->password) == $this->input->post('password')) {
				if ($u[0]->block == 0 && $u[0]->activation == '') {
					// print_r($u[0]);
					$data = array(
						'user_id'           => $u[0]->id,
						'username'          => $u[0]->username,
						'email'             => $u[0]->email,
						'firstname'         => $u[0]->firstname,
						'lastname'          => $u[0]->lastname,
						'phone'             => $u[0]->phone,
						'evening_phone'     => $u[0]->evening_phone,
						'year_of_birth'     => $u[0]->year_of_birth,
						'month_of_birth'    => $u[0]->month_of_birth,
						'day_of_birth'      => $u[0]->day_of_birth,
						'bill_firstname'    => $u[0]->bill_firstname,
						'bill_lastname'     => $u[0]->bill_lastname,
						'bill_address'      => $u[0]->bill_address,
						'bill_city'         => $u[0]->bill_city,
						'bill_zipcode'      => $u[0]->bill_zipcode,
						'bill_state'        => $u[0]->bill_state,
						'bill_country'      => $u[0]->bill_country,
						'bill_phone'        => $u[0]->bill_phone,
						'bill_evening_phone'=> $u[0]->bill_evening_phone,
						'ship_firstname'    => $u[0]->ship_firstname,
						'ship_lastname'     => $u[0]->ship_lastname,
						'ship_address'      => $u[0]->ship_address,
						'ship_city'         => $u[0]->ship_city,
						'ship_zipcode'      => $u[0]->ship_zipcode,
						'ship_state'        => $u[0]->ship_state,
						'ship_country'      => $u[0]->ship_country,
						'ship_phone'        => $u[0]->ship_phone,
						'ship_evening_phone'=> $u[0]->ship_evening_phone,
						'subscribe'         => $u[0]->subscribe,
						'account_type'      => 'customer',
						'logged_in' => TRUE
					);
					// 
					$this->session->set_userdata($data);
					
					//	Checking Wish List
					$query = $this->db->query(
						"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->session->userdata('user_id')
					);
					$result = $query->result();
					$this->session->set_userdata(array("wishlist_count" => $result[0]->wishlist_count));
					
					// $this->session->set_userdata($u[0]);
					
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
			
		} else {
			return FALSE;
		}
		
		// return Current_User::login($this->input->post('username'), $this->input->post('password'));
	}
}
