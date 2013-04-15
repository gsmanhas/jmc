<?php

/**
* 
*/
class Signin extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{
		// $this->session->set_userdata('HTTP_REFERE', $_SERVER['HTTP_REFERER']);
				
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
		if (!$_POST) {
			
			$referrer = $this->agent->referrer();
			// echo $referrer;
			$arrRerrer = explode('/', $this->agent->referrer());
			
			foreach ($arrRerrer as $item) {
				if ($item == "signout") {
					$referrer = '';
				}
			}
			
			$this->history = $referrer;
			// $this->session->set_userdata('referrer', array(''));
		} else {
			$this->history = $this->input->post('history');
		}
		if($this->history == site_url().'viewcart'){
			$this->history = site_url().'membercheckout';
		}
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
		
		// $referer = $_SERVER['HTTP_REFERER'];
	 	
		// if ($this->session->userdata('HTTP_REFERE')) {
		// 	redirect($this->session->userdata('HTTP_REFERE'), 'refresh');
		// 	   	} else {
		// 	redirect('/', 'refresh');
		// }
		
		// echo $this->agent->referrer();
		// echo $this->input->post('history');
		
		// $referer = ($this->input->post('history') != "") ? $this->input->post('history') : "/";
		$referrer = $this->agent->referrer();
		
		if (!$_POST) {
			// echo $referrer;
			$arrRerrer = explode('/', $this->agent->referrer());
			foreach ($arrRerrer as $item) {
				if ($item == "signout") {
					$referrer = '';
				}
			}
		} else {
			$referrer = $this->input->post('history');
		}
		
		
		if($referrer == site_url().'guestcheckout/submit' or $referrer == site_url().'guestcheckout'){
			$referrer = site_url().'membercheckout';
		}
		// redirect('/');
		redirect($referrer, 'refresh');
	}
	
	private function _submit_validate() {
		$this->form_validation->set_rules('username', 'Username', 'trim|required|callback_authenticate');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		$this->form_validation->set_message('authenticate','Invalid Sign In. Please try again.');
		return $this->form_validation->run();
	}
	
	public function authenticate() {
		
		$query = $this->db->query(
			"SELECT * FROM users " .
			"WHERE username = " . $this->db->escape($this->input->post('username'))
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
						'year_of_birth'     => $u[0]->year_of_birth,
						'month_of_birth'    => $u[0]->month_of_birth,
						'day_of_birth'      => $u[0]->day_of_birth,
						'bill_address'      => $u[0]->bill_address,
						'bill_city'         => $u[0]->bill_city,
						'bill_zipcode'      => $u[0]->bill_zipcode,
						'bill_state'        => $u[0]->bill_state,
						'ship_address'      => $u[0]->ship_address,
						'ship_city'         => $u[0]->ship_city,
						'ship_zipcode1'      => $u[0]->ship_zipcode,
						'ship_state'        => $u[0]->ship_state,
						'subscribe'         => $u[0]->subscribe,
						'shipsame'          => $u[0]->shipsame,
						'account_type'      => 'customer',
						'logged_in' => TRUE
					);
					// 
					$this->session->set_userdata($data);
					
					//	Checking Wish List
					$query = $this->db->query(
						"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->session->userdata('user_id') .
						" AND is_delete = 0"
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
		
		// return Current_User::Signin($this->input->post('username'), $this->input->post('password'));
	}
}
