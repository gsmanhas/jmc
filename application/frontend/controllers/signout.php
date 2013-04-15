<?php
class Signout extends MY_Controller {

	function __construct()
	{		
		parent::__construct();
	}

	public function _remap()
	{
		switch (strtolower($this->uri->segment(2, 0))) {
			case 'success':
				$this->success();
				break;
			default:
				$this->index();
				break;
		}
	}

	public function index() {
		
		$this->session->sess_destroy();		
		redirect('signout/success', 'refresh');
	}

	public function success()
	{
		$this->load->view('frontend/signout');
	}

}
