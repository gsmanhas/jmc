<?php
class Logout extends MY_Controller {

	function __construct()
	{		
		parent::__construct();
	}

	public function _remap()
	{
		$this->index();
	}

	public function index() {
		$this->session->sess_destroy();
		$this->load->view('admin/login');
	}

}
