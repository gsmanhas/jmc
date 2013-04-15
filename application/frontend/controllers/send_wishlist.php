<?php

/**
* 
*/
class Send_wishlist extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{
		
		if ($_POST) {
			if ($_POST['method'] == "submit") {
				$this->sendmail();				
			}
		} else {
			$this->index();			
		}

	}
	
	public function index()
	{
		$this->load->view('send_wishlist');
	}
	
	private function _submit_validate()
	{
		$this->form_validation->set_rules('subject', 'subject', 'required');
		$this->form_validation->set_rules('maillist', 'maillist', 'required|callback_maillist_check');
		$this->form_validation->set_rules('message', 'maillist', 'required');
		
		$this->form_validation->set_message('required', 'required');
		
		return $this->form_validation->run();
	}
	
	public function sendmail()
	{
		if ($this->_submit_validate() === FALSE) {
			$this->index();
			return;
		}
		
		$query = $this->db->query('SELECT * FROM wishlist where uid = ? and is_delete = 0', $this->session->userdata('user_id'));
		$this->wishlist = $query->result();
		
		$this->MESSAGE = $this->input->post('message');
		
		$mailer = new Mailer();
		$mails = explode(';', $_POST['maillist']);
		$mailer->send_wishlist($mails, $this->input->post('subject'));
		
		// $mailer->retrieve_password($this->session->userdata('email'), $this->input->post('firstname'));
		
		$this->load->view('send_wishlist_success');
		
	}
	
	public function maillist_check($str)
	{		
		if (isset($_POST['maillist'])) {
			$mails = explode(';', $_POST['maillist']);
			if (is_array($mails) && (count($mails) >= 1)) {
				$bol = FALSE;
				foreach ($mails as $mail) {
					if ($this->valid_email($mail) == FALSE) {
						$this->form_validation->set_message('maillist_check', 'Invalid email(s). Please check the format.');
						return FALSE;
					}
				}
			} else {
				$this->form_validation->set_message('maillist_check', 'Invalid email(s)');
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}
	
}