<?php

/**
* 
*/
class Email_signup_page extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	public function _remap()
	{
		$this->index();
	}

	public function index()
	{
		$this->load->view('email_signup_page');
	}
}