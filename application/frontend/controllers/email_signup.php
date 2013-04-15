<?php

/**
* 
*/
class Email_signup extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{
		// $this->index();
		
		// echo $this->uri->segment(2, 0).br(1);
		// echo $this->uri->segment(3, 0).br(1);
		
		if ($this->uri->segment(2, 0) == "success") {
			$this->load->view('email_signup_success');
		} else {
			// $this->error();
		}
		
	}
	
	public function index()
	{
		$this->load->view('email_signup_success');
	}
	
	public function success()
	{
		$this->load->view('email_signup_success');
	}	
	
}