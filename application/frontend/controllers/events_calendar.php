<?php

/**
* 
*/
class Events_calendar extends MY_Controller
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
		$this->load->view('events_calendar');
	}
	
}