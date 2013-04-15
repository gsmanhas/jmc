<?php

/**
* 
*/
class Signout extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->admin->signout();
	}
}