<?php

/**
* 
*/
class Register extends MY_Controller
{
	
	function __construct()
	{		
		
		parent::__construct();
		
		$query = $this->db->query('SELECT * FROM state order by state asc');
		$this->continental = $query->result();
		
		// $query = $this->db->query('SELECT * FROM country order by country asc');
		// $this->countrys = $query->result();
		
		// $query = $this->db->query('SELECT * FROM skin_color order by id asc');
		// $this->skin_colors = $query->result();

	}
	
	public function _remap()
	{
		
		if ($this->session->userdata('user_id') != "") {
			//redirect("/myaccount", 'refresh');
		}
		
		switch ($this->uri->segment(2, 0)) {

			case "0" :
				$this->index();
			break;

			case "submit" :
				$this->_submit();
			break;
			
			case "success" :
				$this->success();
			break;
			
			case "activate" :
				$this->_activate();
			break;
			
			default:
				redirect('page-not-found', 'refresh');
			break;
		}
		
		
		
		// $this->index();
	}
	
	public function index()
	{
		// $this->load->view('front/register');
		$this->load->view('frontend/register');
	}
	
	private function _submit()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		if ($this->input->post('shipSame') != 1) {
			$User = array(
				// 'username'          => $this->input->post('username'),
				'username'          => $this->input->post('email'),
				'password'          => $this->encrypt->encode($this->input->post('password')),
				'email'             => $this->input->post('email'),
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				
				'phone'             => $this->input->post('phone'),
				'evening_phone'     => $this->input->post('evening_phone'),
				'skin_color_id'     => $this->input->post('skin_color_id'),
				'year_of_birth'     => $this->input->post('year_of_birth'),
				'month_of_birth'    => $this->input->post('month_of_birth'),
				'day_of_birth'      => $this->input->post('day_of_birth'),
				
				// 'bill_firstname'    => $this->input->post('bill_firstname'),
				// 'bill_lastname'     => $this->input->post('bill_lastname'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'bill_country'      => $this->input->post('bill_country'),
				
				'bill_phone'        => $this->input->post('bill_phone'),
				'bill_evening_phone'=> $this->input->post('bill_evening_phone'),
				
				// 'ship_firstname'    => $this->input->post('ship_firstname'),
				// 'ship_lastname'     => $this->input->post('ship_lastname'),
				'ship_address'      => $this->input->post('ship_address'),
				'ship_city'         => $this->input->post('ship_city'),
				'ship_zipcode'      => $this->input->post('ship_zipcode'),
				'ship_state'        => $this->input->post('ship_state'),
				'ship_country'      => $this->input->post('ship_country'),
				
				'ship_phone'        => $this->input->post('ship_phone'),
				'ship_evening_phone'=> $this->input->post('ship_evening_phone'),
				
				'subscribe'         => $this->input->post('subscribe'),
				'activation'        => '',
				'block'             => 0,
				'shipsame'          => 0,
				'created_at'        => unix_to_human(time(), TRUE, 'us'),
				'updated_at'        => unix_to_human(time(), TRUE, 'us'),				
				'registerDate'      => unix_to_human(time(), TRUE, 'us')
			);	
		} else {
			$User = array(
				// 'username'          => $this->input->post('username'),
				'username'          => $this->input->post('email'),
				'password'          => $this->encrypt->encode($this->input->post('password')),
				'email'             => $this->input->post('email'),
				'firstname'         => $this->input->post('firstname'),
				'lastname'          => $this->input->post('lastname'),
				
				'phone'             => $this->input->post('phone'),
				'evening_phone'     => $this->input->post('evening_phone'),
				'skin_color_id'     => $this->input->post('skin_color_id'),
				'year_of_birth'     => $this->input->post('year_of_birth'),
				'month_of_birth'    => $this->input->post('month_of_birth'),
				'day_of_birth'      => $this->input->post('day_of_birth'),
				
				// 'bill_firstname'    => $this->input->post('bill_firstname'),
				// 'bill_lastname'     => $this->input->post('bill_lastname'),
				'bill_address'      => $this->input->post('bill_address'),
				'bill_city'         => $this->input->post('bill_city'),
				'bill_zipcode'      => $this->input->post('bill_zipcode'),
				'bill_state'        => $this->input->post('bill_state'),
				'bill_country'      => $this->input->post('bill_country'),
				'bill_phone'        => $this->input->post('bill_phone'),
				'bill_evening_phone'=> $this->input->post('bill_evening_phone'),
				// 'ship_firstname'    => $this->input->post('bill_firstname'),
				// 'ship_lastname'     => $this->input->post('bill_lastname'),
				'ship_address'      => $this->input->post('bill_address'),
				'ship_city'         => $this->input->post('bill_city'),
				'ship_zipcode'      => $this->input->post('bill_zipcode'),
				'ship_state'        => $this->input->post('bill_state'),
				'ship_country'      => $this->input->post('bill_country'),
				'ship_phone'        => $this->input->post('bill_phone'),
				'ship_evening_phone'=> $this->input->post('bill_evening_phone'),
				'subscribe'         => $this->input->post('subscribe'),
				'activation'        => '',
				'block'             => 0,
				'shipsame'          => 1,
				'created_at'        => unix_to_human(time(), TRUE, 'us'),
				'updated_at'        => unix_to_human(time(), TRUE, 'us'),				
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
		
		$this->_sendVaildationMail();
		
		$this->_login_new_user();
		
		redirect("/register/success", 'refresh');
		
	}
	
	private function _login_new_user(){
		
		$query = $this->db->query(
			"SELECT * FROM users " .
			"WHERE username = " . $this->db->escape($this->input->post('email'))
		);
		
		$u = $query->result();
		
		if (count($u) >= 1) {
		
			if ($u[0]->block == 0 && $u[0]->activation == '') {
					
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
					// 
					$this->session->set_userdata($data);
					
					
					$query = $this->db->query(
						"SELECT count(id) as `wishlist_count` from wishlist where uid = " . $this->session->userdata('user_id') .
						" AND is_delete = 0"
					);
					$result = $query->result();
					$this->session->set_userdata(array("wishlist_count" => $result[0]->wishlist_count));
					
					
					
					
				}
		
		}
	}
	
	private function _submit_validate()
	{
		
		// $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|max_length[24]|callback_username_check');
		// $this->form_validation->set_rules('username', 'Username', 'required|valid_email|callback_username_check');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[24]');
		$this->form_validation->set_rules('passconf', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_check');
		$this->form_validation->set_rules('confirm_email', 'Confirm Email Address', 'required|valid_email|matches[email]');
		
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		
		$this->form_validation->set_rules('phone', 'Phone', 'required|numeric|max_length[20]');
		// $this->form_validation->set_rules('evening_phone', 'Evening Phone', 'required|numeric|max_length[20]');
		$this->form_validation->set_rules('year_of_birth', 'Year of Birth', 'required|numeric|min_length[4]|max_length[4]');
		$this->form_validation->set_rules('month_of_birth', 'Month of Birth', 'required|numeric|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('day_of_birth', 'Day of Birth', 'required|numeric|min_length[1]|max_length[2]|callback_day_of_birth_check');
		
		// $this->form_validation->set_rules('bill_firstname', 'Billing First Name', 'required');
		// $this->form_validation->set_rules('bill_lastname', 'Billing Last Name', 'required');
		$this->form_validation->set_rules('bill_address', 'Billing Address', 'required');
		$this->form_validation->set_rules('bill_city', 'Billing City', 'required');
		$this->form_validation->set_rules('bill_zipcode', 'Billing Zip Code', 'required');
		$this->form_validation->set_rules('bill_state', 'Billing State', 'required');
		// $this->form_validation->set_rules('bill_phone', 'Billing Phone', 'required|numeric|max_length[20]');
		// $this->form_validation->set_rules('bill_evening_phone', 'Billing Evening Phone', 'required|numeric|max_length[20]');
		
		if ($this->input->post('shipSame') != 1) {
			// $this->form_validation->set_rules('ship_firstname', 'Shipping First Name', 'required');
			// $this->form_validation->set_rules('ship_lastname', 'Shipping Last Name', 'required');
			$this->form_validation->set_rules('ship_address', 'Shipping Address', 'required');
			$this->form_validation->set_rules('ship_city', 'Shipping City', 'required');
			$this->form_validation->set_rules('ship_zipcode', 'Shipping Zip Code', 'required');	
			$this->form_validation->set_rules('ship_state', 'Shipping State', 'required');
			// $this->form_validation->set_rules('ship_phone', 'Shipping Phone', 'required|numeric|max_length[20]');
			// $this->form_validation->set_rules('ship_evening_phone', 'Shipping Evening Phone', 'required|numeric|max_length[20]');
		}
		
		$this->form_validation->set_message('required', 'required');
		
		return $this->form_validation->run();
	}
	
	public function username_check($username)
	{
		// $query = $this->db->query('SELECT id FROM users WHERE username = \'' . $username . '\'');
		
		$query = $this->db->query('SELECT id FROM users WHERE username = ? and is_delete = 0', $username);
		
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('username_check', 'The %s already exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function email_check($email)
	{
		$query = $this->db->query('SELECT id FROM users WHERE email = ? and is_delete = 0', $email );
		$u = $query->result();
		if (count($u) >= 1) {
			$this->form_validation->set_message('email_check', '<br>The %s already exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function match_email($con_email)
	{
		$email = $this->input->post('email');
		
		if($email != $con_email){
		
			$this->form_validation->set_message('match_email', '<br>%s mismatch');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	
	
	public function day_of_birth_check($day_of_birth)
	{
		if (($day_of_birth >= 1) && ($day_of_birth) <= 31) {
			return TRUE;
		} else {
			$this->form_validation->set_message('day_of_birth_check', 'The %s error');
			return FALSE;
		}
	}
	
	public function success()
	{
		$this->load->view('register_success');
	}
	
	public function _sendVaildationMail()
	{	
		
		$query = $this->db->query('SELECT * FROM users WHERE username = ? and is_delete = 0', $this->input->post('email'));
		$user = $query->result();
		
		//	傳送 activation 到 E-MAIL Template
		$this->activation = base_url() . 'register/activate/' . $user[0]->activation;
		
		$mailer = new Mailer();
		$mailer->account_activation($this->input->post('email'), $this->input->post('firstname'));
		
		// $config['protocol'] = 'smtp';
		// $config['mailpath'] = '/usr/sbin/sendmail';
		// $config['charset']  = 'utf-8';
		// $config['wordwrap'] = TRUE;
		// 
		// $config['mailtype'] = 'html';
		// $config['priority'] = 3;
		// 
		// $config['smtp_host'] = 'mail.sixspokemedia.com';
		// $config['smtp_user'] = 'hhuang@sixspokemedia.com';
		// $config['smtp_pass'] = 'lit89dmz%%';
		// $config['smtp_port'] = '26';
		// 
		// $config['mailfrom']  = 'developer@sixspokemedia.com';
		// $config['mailbcc']   = 'developer@sixspokemedia.com';
		// $config['cc']        = 'developer@sixspokemedia.com';
		// 
		// $this->email->initialize($config);
		// 
		// $this->email->from($this->config->item('mailfrom'), 'Josie Maran');
		// $this->email->to($email);
		// // $this->email->cc();
		// $this->email->bcc($this->config->item('mailbcc'));
		// 	
		// // $query = $this->db->query('SELECT * FROM users WHERE username = ? and is_delete = 0', $this->input->post('username'));
		// // $user = $query->result();
		// 
		// $query = $this->db->query('SELECT * FROM users WHERE username = ? and is_delete = 0', $this->input->post('email'));
		// $user = $query->result();
		// 
		// $this->email->subject('Account Details for ' . $this->input->post('firstname') .  ' at Josie Maran.');
		// $this->email->message(
		// 	'Hello ' . $this->input->post('firstname') . "," . br(2) .
		// 	'Thank you for registering at Josie Maran. Your account is created and must be activated before you can use it.' . br(1) .
		// 	'To activate the account click on the follwing link or copy-paste it in your browser:' . br(1) .
		// 	'<a href=' . base_url() . 'register/activate/' . $user[0]->activation . '>' . base_url() . 'register/activate/' . $user[0]->activation . '</a>'. br(2). 
		// 	'Username : ' . $this->input->post('email') . br(1) .
		// 	'Password : ' . $this->input->post('password') . br(1)
		// );
		// 
		// $this->email->send();
		// // $this->email->print_debugger()
	}
	
	function _activate()
	{	
		if ($this->uri->total_segments() == 3) {
			if ($this->uri->segment(3, 0) != "0") {
												
				$query = $this->db->query(
					'SELECT * FROM users WHERE activation = ? and is_delete = 0 and block = 1', $this->uri->segment(3, 0)
				);
				
				$user = $query->result();
				
				if (count($user) >= 1) {	
					$Activation = array(
						'block' => 0,
						'activation' => '',
						'updated_at' => unix_to_human(time(), TRUE, 'us')
					);
					$this->db->where('id', $user[0]->id);
					$this->db->update('users', $Activation);
					$numrows = $this->db->affected_rows();
					
					if ($numrows >= 1) {
						$this->load->view('activation_complete');
					} else {
						$this->load->view('activation_invalid');
					}

				} else {
					$this->load->view('activation_invalid');
				}
						
			} else {
				redirect('page-not-found', 'refresh');
			}
		} else {
			redirect('page-not-found', 'refresh');
		}
	}
	
}