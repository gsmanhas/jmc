<?php
/**
* 
*/
class Home extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		if ($this->session->userdata('accout_type') != "manager" && $this->session->userdata('logged_in') == FALSE) {
            redirect('signin', 'refresh');
		}
		
	}
	
	public function index()
	{
        $this->load->view('home');
	}
	
	public function dashboard()
	{
		$this->load->view('home');
	}
	
}
