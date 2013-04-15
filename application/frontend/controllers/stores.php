<?php

/**
* 
*/
class Stores extends MY_Controller
{
	
	function __construct()
	{		
		parent::__construct();
	}
	
	function _remap()
	{
		$this->index();
	}
	
	public function index()
	{		
		$this->load->view('stores');
	}
	
}