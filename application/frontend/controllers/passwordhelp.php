<?php

/**
* 
*/
class Passwordhelp extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{		
		switch (strtolower($this->uri->segment(2, 0))) {
			case 'submit':
				$this->submit();
				break;
			default:
				$this->index();
				break;
		}		
	}
	
	public function index()
	{
		$this->load->view('passwordhelp');
	}
	
	public function submit()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		// print_r($this->forget_password_user);
		
		//	Email Template params
		$this->CUSTOMER_NAME     = $this->forget_password_user[0]->firstname;
		$this->USER_NAME         = $this->forget_password_user[0]->username;
		$this->CUSTOMER_PASSWORD = $this->encrypt->decode($this->forget_password_user[0]->password);
		
		$mailer = new Mailer();
		$mailer->retrieve_password($this->forget_password_user[0]->email, $this->forget_password_user[0]->firstname);
		
		// $this->load->view('LostPasswordProcess');
		redirect('lostpasswordprocess', 'refresh');
		
	}
	
	private function _submit_validate() {
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_email_check');
		$this->form_validation->set_message('required', 'required');
		return $this->form_validation->run();
	}
	
	public function email_check()
	{
		$query = $this->db->query('SELECT id, username, firstname, lastname, email, password FROM users WHERE email = ? and is_delete = 0', $this->input->post('email') );
		$this->forget_password_user = $query->result();
		if (count($this->forget_password_user) >= 1) {
			return TRUE;
		} else {
			$this->form_validation->set_message('email_check', 'The %s does not exists');
			return FALSE;
		}
	}
	
}