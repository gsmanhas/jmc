<?php

/**
* 
*/
class Thanks_for_contact extends MY_Controller
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
		$this->load->view('thanks_for_contact');
	}
	
}